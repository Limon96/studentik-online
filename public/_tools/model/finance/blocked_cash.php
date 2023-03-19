<?php
class ModelFinanceBlockedCash extends Model {

    public function deleteBlockedCash($customer_blocked_cash_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_blocked_cash WHERE customer_blocked_cash_id = '" . (int)$customer_blocked_cash_id . "'");

        $this->cache->delete('country');
    }

    public function getBlockedCash($customer_blocked_cash_id) {
        $query = $this->db->query("SELECT *,
                    (SELECT c.login FROM " . DB_PREFIX . "customer c WHERE c.customer_id = cbs.customer_id) AS customer_login,
                    (SELECT o.title FROM `" . DB_PREFIX . "order` o WHERE o.order_id = cbs.order_id) AS order_title,
                    (SELECT oo.customer_id FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_id,
                    (SELECT oc.login FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_login
                FROM " . DB_PREFIX . "customer_blocked_cash cbs WHERE cbs.customer_blocked_cash_id = '" . (int)$customer_blocked_cash_id . "'");

        return $query->row;
    }

    public function getBlockedCashByOrder($order_id) {
        $query = $this->db->query("SELECT *,
                    (SELECT c.login FROM " . DB_PREFIX . "customer c WHERE c.customer_id = cbs.customer_id) AS customer_login,
                    (SELECT o.title FROM `" . DB_PREFIX . "order` o WHERE o.order_id = cbs.order_id) AS order_title,
                    (SELECT oo.customer_id FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_id,
                    (SELECT oc.login FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_login
                FROM " . DB_PREFIX . "customer_blocked_cash cbs WHERE cbs.order_id = '" . (int)$order_id . "'");

        return $query->row;
    }

    public function getBlockedCashs($data = array()) {
        $sql = "SELECT
                   *,
                    (SELECT c.login FROM " . DB_PREFIX . "customer c WHERE c.customer_id = cbs.customer_id) AS customer_login,
                    (SELECT o.title FROM `" . DB_PREFIX . "order` o WHERE o.order_id = cbs.order_id) AS order_title,
                    (SELECT oo.customer_id FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_id,
                    (SELECT oc.login FROM " . DB_PREFIX . "customer oc LEFT JOIN " . DB_PREFIX . "offer oo ON oc.customer_id = oo.customer_id WHERE oo.offer_id = cbs.offer_id) AS offer_customer_login
                FROM " . DB_PREFIX . "customer_blocked_cash cbs";

        $sort_data = array(
            'date_added',
            'date_end'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalBlockedCashs() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_blocked_cash");

        return $query->row['total'];
    }

    public function returnBlockedCash($customer_blocked_cash_id)
    {
        $blocked_cash_info = $this->getBlockedCash($customer_blocked_cash_id);

        if ($blocked_cash_info) {
            $this->load->model('customer/customer');

            $this->model_customer_customer->addTransaction(
                $blocked_cash_info['customer_id'],
                "Возврат заблокированных средств администратором",
                $blocked_cash_info['balance']
            );

            (new \Model\Notification($this->db))->set(
                $blocked_cash_info['customer_id'],
                'order',
                'Возврат заблокированных средств (' . $this->currency->format($blocked_cash_info['balance'], $this->config->get('config_currency')) . ') администратором'
            );

            $this->deleteBlockedCash($customer_blocked_cash_id);
        }
    }

    public function payBlockedCash($customer_blocked_cash_id)
    {
        $blocked_cash_info = $this->getBlockedCash($customer_blocked_cash_id);

        if ($blocked_cash_info) {
            $this->load->model('customer/customer');

            $cash = $blocked_cash_info['balance'] / (1 + (int)$this->config->get('config_commission_customer') / 100) * (1 - (int)$this->config->get('config_commission') / 100);

            $this->model_customer_customer->addTransaction(
                $blocked_cash_info['offer_customer_id'],
                "Выплата средств проведена администратором",
                $cash
            );

            (new \Model\Notification($this->db))->set(
                $blocked_cash_info['offer_customer_id'],
                'order',
                'Выплата средств (' . $this->currency->format($cash, $this->config->get('config_currency')) . ') проведена администратором'
            );

            $this->deleteBlockedCash($customer_blocked_cash_id);
        }
    }
}
