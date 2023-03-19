<?php

namespace Model;

class Customer extends Model {

    public function get($customer_id)
    {
        $result = $this->db->query("SELECT c.customer_id, 
            c.customer_group_id, 
            c.firstname, 
            c.login, 
            c.gender, 
            c.image, 
            c.date_added, 
            c.last_seen, 
            c.languages, 
            c.bdate, 
            c.comment, 
            c.email, 
            c.telephone, 
            c.rating,
            c.timezone,
            (SELECT SUM(cr.rating) FROM " . DB_PREFIX . "customer_rating cr WHERE cr.customer_id = c.customer_id AND cr.date_added > " . (time() - 604800) . ") as new_rating,
            c.setting_email_notify, 
            IF((SELECT COUNT(1) AS count_pro FROM " . DB_PREFIX . "customer_pro WHERE customer_id = '" . (int)$customer_id . "' AND date_end > '" . time() . "') > 0, 1, 0) AS pro,
            (SELECT country.name FROM " . DB_PREFIX . "country country WHERE country.country_id = c.country_id AND country.status = '1') AS country,
            IF(" . time() . " - c.last_seen  < 900, 1, 0) AS online, 
            (SELECT cn.text FROM " . DB_PREFIX . "customer_note cn WHERE cn.customer_id = c.customer_id AND cn.owner_id = '" . (int)$customer_id . "') AS note 
            FROM " . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int)$customer_id . "'");

        return $result->row;
    }

    public function getCustomersFromFilter($data = [])
    {
        $sql = "SELECT c.customer_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_subject cs ON c.customer_id = cs.customer_id WHERE c.status = 1";

        if (isset($data['customer_group_id']) && $data['customer_group_id'] > 0) {
            $sql .= " AND c.customer_group_id = '" . (int)$data['customer_group_id'] . "'";
        }

        if (isset($data['setting_email_new_order']) && $data['setting_email_new_order'] == 1) {
            $sql .= " AND c.setting_email_new_order = 1";
        }

        if (isset($data['subject_id']) && $data['subject_id'] > 0) {
            $sql .= " AND cs.subject_id = '" . (int)$data['subject_id'] . "'";
        }

        $sql .= " GROUP BY c.customer_id";

        $customer_data = [];

        $result = $this->db->query($sql);

        $customer_ids = [];
        if ($result->num_rows) {
            foreach ($result->rows as $row) {
                if (in_array($row['customer_id'], $customer_ids)) continue;
                $customer_ids[] = $row['customer_id'];
                $customer_data[] = $this->get($row['customer_id']);
            }
        }

        return $customer_data;
    }

    public function getEmail($customer_id)
    {
        $result = $this->db->query("SELECT customer_id, email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        if (isset($result->row['email'])) {
            return $result->row['email'];
        }
        return null;
    }

    public function returnBlockedCash($data)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$data['customer_id'] . "' AND order_id = '" . (int)$data['order_id'] . "' AND offer_id = '" . (int)$data['offer_id'] . "'");

        if (isset($result->row['balance'])) {
            // Удаляем запись о блокировке средств
            $this->db->query("DELETE FROM  " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$data['customer_id'] . "' AND order_id = '" . (int)$data['order_id'] . "' AND offer_id = '" . (int)$data['offer_id'] . "'");

            // Пополняем баланс на блокированную сумму
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance + '" . (float)$result->row['balance'] . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");

            // Запись истории транзакций
            $amount = $result->row['balance'];

            $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$data['customer_id'] . "'");

            $this->setTransaction([
                'customer_id' => $data['customer_id'],
                'amount' => (float)$amount,
                'description' => sprintf("Возврат средств в заказе №%s %s",  $data['order_id'], $data['title']),
                'balance' => (isset($result->row['balance']) ? (float)$result->row['balance']: 0),
            ]);
        }
    }

    public function setBalanceOfferFromBlockedCash($customer, $order, $offer_id, $commission_order, $commission_offer)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_blocked_cash WHERE offer_id = '" . (int)$offer_id . "'");
        if ($result->row) {
            // Вычисляем сумму для выплаты автору
            // блок.сумма - процент студента - процент автора

            $cash = $result->row['balance'] / (1 + (int)$commission_order / 100) * (1 - (int)$commission_offer/ 100);

            // Записываем автору блокированную сумму у заказчика
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET balance = balance + '" . (float)$cash . "' WHERE customer_id = '" . (int)$customer->customer_id . "'");


            // Удаляем запись
            $this->db->query("DELETE FROM  " . DB_PREFIX . "customer_blocked_cash WHERE customer_id = '" . (int)$result->row['customer_id'] . "' AND order_id = '" . (int)$result->row['order_id'] . "' AND offer_id = '" . (int)$offer_id . "'");

            // Запись истории транзакций

            $customer_info = $this->get($result->row['customer_id']);

            $amount = $result->row['balance'] / (1 + (int)$commission_order / 100);

            // order owner зачисляем рейтинг
            $this->setRating($result->row['customer_id'], $amount / 100);
            // offer owner зачисляем рейтинг
            $this->setRating($customer->customer_id, $amount / 100);


            $result = $this->db->query("SELECT balance FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer->customer_id . "'");

            $this->setTransaction([
                'customer_id' => $customer->customer_id,
                'amount' => (float)$cash,
                'description' => sprintf("Доход от %s в заказе №%s %s", $customer_info['login'], $order->order_id, $order->title),
                'balance' => (isset($cash) ? (float)$cash: 0),
            ]);
        }
    }

    public function setTransaction($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET
            customer_id = '" . (int)$data['customer_id'] . "',
            amount = '" . (float)$data['amount'] . "',
            description = '" . $this->db->escape($data['description']) . "',
            balance = '" . (float)$data['balance'] . "',
            date_added = '" . time() . "'");
    }

    public function setRating($customer_id, $rating)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_rating SET 
            customer_id = '" . (int)$customer_id . "',
            rating = '" . (int)$rating . "', 
            date_added = '" . time() . "'
        ");

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET rating = rating + '" . (int)$rating . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function removeReview($review_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_review WHERE review_id = '" . (int)$review_id . "'");
    }

    public function unsubscribe($token)
    {
        $result = $this->db->query("SELECT c.customer_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "unsubscribe_token ut ON (LOWER(c.email) = LOWER(ut.email)) WHERE ut.token = '" . $this->db->escape($token) . "'");
        if (isset($result->row['customer_id'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET setting_email_notify = 0, setting_email_new_order = 0 WHERE customer_id = '" . (int)$result->row['customer_id'] . "'");
        }
    }
}