<?php

class ModelOrderOffer extends Model {

    public function addOffer($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "offer SET 
            order_id = '" . $data['order_id'] . "', 
            customer_id = '" . $this->customer->getId() . "', 
            bet = '" . $this->db->escape($data['bet']) . "', 
            earned = '" . $this->db->escape($data['earned']) . "', 
            text = '" . $this->db->escape($data['text']) . "', 
            date_added = '" . time() . "'
        ");

        $offer_id = $this->db->getLastId();

        return $offer_id;
    }

    public function editOffer($offer_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "offer SET
             bet = '" . $this->db->escape($data['bet']) . "', 
            earned = '" . $this->db->escape($data['earned']) . "', 
            text = '" . $this->db->escape($data['text']) . "', 
            date_updated = NOW()
            WHERE offer_id = '" . (int)$offer_id . "'
        ");
    }

    public function getOffer($offer_id)
    {
        $result = $this->db->query("SELECT *, IF(customer_id = '" . (int)$this->customer->getId() . "',1,0) AS is_owner FROM ". DB_PREFIX . "offer WHERE offer_id = '" . $offer_id . "'");
        $offer_data = array();

        if ($result->row) {

            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomerInfo($result->row['customer_id']);

            $offer_data = [
                'offer_id' => $result->row['offer_id'],
                'order_id' => $result->row['order_id'],
                'is_owner' => $result->row['is_owner'],
                'customer_id' => $customer_info['customer_id'],
                'login' => $customer_info['login'],
                'firstname' => $customer_info['firstname'],
                'image' => $customer_info['image'],
                'pro' => $customer_info['pro'],
                'online' => $customer_info['online'],
                'rating' => $customer_info['rating'],
                'total_reviews_positive' => $customer_info['total_reviews_positive'],
                'total_reviews_negative' => $customer_info['total_reviews_negative'],
                'text' => $result->row['text'],
                'bet' => $result->row['bet'],
                'earned' => $result->row['earned'],
                'assigned' => $result->row['assigned'],
                'date_added' => $result->row['date_added'],
                'date_updated' => $result->row['date_updated'],
            ];
        }
        return $offer_data;
    }

    public function getOffers($order_id)
    {
        $result = $this->db->query("SELECT *, IF(customer_id = '" . (int)$this->customer->getId() . "',1,0) AS is_owner FROM ". DB_PREFIX . "offer WHERE order_id = '" . $order_id . "' ORDER BY assigned DESC");
        $offer_data = array();

        if ($result->rows) {

            $this->load->model('account/customer');

            foreach ($result->rows as $row) {

                $customer_info = $this->model_account_customer->getCustomerInfo($row['customer_id']);

                $offer_data[] = [
                    'offer_id' => $row['offer_id'],
                    'order_id' => $row['order_id'],
                    'is_owner' => $row['is_owner'],
                    'customer_id' => $customer_info['customer_id'],
                    'login' => $customer_info['login'],
                    'firstname' => $customer_info['firstname'],
                    'image' => $customer_info['image'],
                    'pro' => $customer_info['pro'],
                    'online' => $customer_info['online'],
                    'rating' => $customer_info['rating'],
                    'new_rating' => $customer_info['new_rating'],
                    'total_reviews_positive' => $customer_info['total_reviews_positive'],
                    'total_reviews_negative' => $customer_info['total_reviews_negative'],
                    'text' => $row['text'],
                    'bet' => $row['bet'],
                    'earned' => $row['earned'],
                    'assigned' => $row['assigned'],
                    'date_added' => $row['date_added'],
                    'date_updated' => $row['date_updated'],
                ];
            }
        }
        return $offer_data;
    }

    public function getOfferAssigned($order_id)
    {
        $result = $this->db->query("SELECT * FROM ". DB_PREFIX . "offer WHERE order_id = '" . $order_id . "' AND assigned = '1'");

        if (isset($result->row['offer_id'])) {
            return $this->getOffer($result->row['offer_id']);
        }
        return false;
    }

    public function getTotalOffersByOrderIdAndCustomerId($order_id, $customer_id)
    {
        $result = $this->db->query("SELECT * FROM ". DB_PREFIX . "offer WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$customer_id . "'");

        if ($result->num_rows) {
            return (int)$result->num_rows;
        }
        return 0;
    }

    public function getTotalOfferAssigned($order_id)
    {
        $result = $this->db->query("SELECT COUNT(1) AS total FROM ". DB_PREFIX . "offer WHERE order_id = '" . $order_id . "' AND assigned = '1'");

        if (isset($result->row['total'])) {
            return (int)$result->row['total'];
        }
        return false;
    }

    public function assignedOffer($offer_id, $order_id, $referer = '')
    {
        $this->load->language('order/offer');

        $this->load->model('order/order');
        $this->load->model('order/offer');
        $order_info = $this->model_order_order->getOrder($order_id);

        $error = false;

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {
            // Проверяем хозяина заказа
            if (!$order_info['is_owner']) {
                $error['access_denied'] = $this->language->get('error_access_denied');
            }

            // Проверяем открыт ли заказ
            if ($order_info['order_status_id'] != $this->config->get('config_open_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if ($total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_assigned');
            }
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOffer($offer_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {
                // Проверяем хватает ли средств
                $balance = $this->customer->getBalance();

                $bet = floor($offer_info['bet'] * (100 + $this->config->get('config_commission_customer')) / 100);

                if ($bet > $balance) {
                    $error['balance'] = $this->language->get('error_balance');

                    $amount = $bet - $balance;

                    $this->session->data['order_id'] = $order_info['order_id'];
                    $this->session->data['offer_id'] = $offer_info['offer_id'];
                    $this->session->data['referer'] = $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']);
                    $error['redirect'] = str_replace('&amp;','&', $this->url->link('account/finance/payment', 'amount=' . $amount));
                }

                // Проверяем назначен ли offer исполнителем
                if ($offer_info['assigned']) {
                    $error['assigned'] = $this->language->get('error_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {
            $this->load->model('account/customer');

            // Блокируем сумму
            //$payment_blocking_value = $this->model_order_order->getOrderPaymentBlockingValue($order_id);

            $payment_blocking_value = 15;

            $date_end = time() + (86400 * $payment_blocking_value);

            $bet = $offer_info['bet'] + $offer_info['bet'] * (int)$this->config->get('config_commission_customer') / 100;

            $this->model_account_customer->setBlockedCash([
                'customer_id' => $order_info['customer_id'],
                'order_id' => $order_id,
                'offer_id' => $offer_id,
                'balance' => $bet,
                'date_end' => $date_end
            ]);

            // Назначаем offer
            $this->db->query("UPDATE " . DB_PREFIX . "offer SET assigned = '1' WHERE offer_id = '" . (int)$offer_id . "'");

            // Переводим заказ в статус "на подтверждении"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_pending_order_status_id'));

            // Записываем в историю
            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $offer_info['customer_id'],
                'text' => $this->language->get('text_customer_assigned')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);

            // Уведомление order owner
            (new \Model\Notification($this->db))
                ->set(
                    $order_info['customer_id'],
                    'order',
                    sprintf(
                        $this->language->get('notification_assign_offer'),
                        'Я',
                        'предложение <a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                        $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                        trim($order_info['title']),
                        ""
                    )
                );

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
                $data['message'] = sprintf(
                    $this->language->get('notification_assign_offer'),
                    'Я',
                    'предложение <a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    trim($order_info['title']),
                    ""
                );

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $order_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_assigned_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            (new \Model\Notification($this->db))
                ->set(
                    $offer_info['customer_id'],
                    'order',
                    sprintf(
                        $this->language->get('notification_assign_offer'),
                        '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' .  $order_customer_info['login'] . '</a>',
                        'Ваше предложение',
                        $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                        trim($order_info['title']),
                        ' У вас есть 24 часа, чтобы принять заказ в работу.'
                    )
                );

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
                $data['message'] = sprintf(
                    $this->language->get('notification_assign_offer'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' .  $order_customer_info['login'] . '</a>',
                    'Ваше предложение',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    trim($order_info['title']),
                    ' У вас есть 24 часа, чтобы принять заказ в работу.'
                );

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $offer_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_assigned_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Добавляем в очередь задачу вернуть статус "Открытый" по истечению суток
            $this->taskManager->set([
                'channel' => 'default',
                'type' => 'offer_cancel',
                'time_exec' => time() + 86400,
                'object' => [
                    'order' => $order_info,
                    'offer' => $offer_info,
                    'order_customer_info' => $order_customer_info,
                    'offer_customer_info' => $offer_customer_info
                ]
            ]);

            if ($referer != '') {
                $success['redirect'] = $referer;
            }

            $success['success'] = sprintf($this->language->get('text_assigned'), $this->currency->format($bet, $this->config->get('config_currency')));

            unset($this->session->data['order_id']);
            unset($this->session->data['offer_id']);
            unset($this->session->data['referer']);

            return $success;
        }

        return $error;
    }

    public function cancelOffer($offer_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "offer SET assigned = '0', canceled = '1' WHERE offer_id = '" . (int)$offer_id . "'");
    }


}