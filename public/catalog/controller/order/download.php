<?php

class ControllerOrderDownload extends Controller {

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/attachment');

        if (isset($this->request->get['attachment_id'])) {
            $order_offer_attachment_id = $this->request->get['attachment_id'];
        } else {
            $order_offer_attachment_id = 0;
        }

        $order_offer_attachment_info = $this->model_order_order->getOrderOfferAttachment($order_offer_attachment_id);

        $order_info = $this->model_order_order->getOrder($order_offer_attachment_info['order_id']);

        $offer_info = $this->model_order_offer->getOfferAssigned($order_offer_attachment_info['order_id']);

        $attachment_info = $this->model_tool_attachment->getAttachment($order_offer_attachment_info['attachment_id']);

        if ($order_info['is_owner'] && $order_info['order_status_id'] != $this->config->get('config_verification_order_status_id') && $order_info['order_status_id'] != $this->config->get('config_complete_order_status_id')) {
            // Переводим заказ в статус "на проверке"
            $this->model_order_order->setOrderStatus($order_info['order_id'], $this->config->get('config_verification_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_info['order_id'],
                'customer_id' => $order_info['customer_id'],
                'text' => $this->language->get('text_customer_verification')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);
            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
            $order_id = $order_info['order_id'];

            // Уведомление order owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_verification_offer'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_verification_offer'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($order_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $order_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_verification_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_verification_offer'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>',
                     $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_verification_offer'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($offer_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);


                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $offer_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_verification_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Добавляем в очередь задачу разблокировки средств по истечению срока

            $payment_blocking_value = $this->model_order_order->getOrderPaymentBlockingValue($order_info['order_id']);

            $blocking_time = 86400 * $payment_blocking_value;

            $this->taskManager->set([
                'channel' => 'default',
                'type' => 'offer_close',
                'time_exec' => time() + $blocking_time,
                'object' => [
                    'order' => $order_info,
                    'offer' => $offer_info,
                    'order_customer_info' => $order_customer_info,
                    'offer_customer_info' => $offer_customer_info
                ]
            ]);
        }

        if (($order_info['is_owner'] || $offer_info['is_owner'] || $this->customer->isAdmin()) && $attachment_info) {
            $file = DIR_UPLOAD . $attachment_info['src'];
            $mask = basename($attachment_info['name']);

            if (!headers_sent()) {
                if (file_exists($file)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    if (ob_get_level()) {
                        ob_end_clean();
                    }

                    readfile($file, 'rb');

                    exit();
                } else {
                    exit('Error: Could not find file ' . $file . '!');
                }
            } else {
                exit('Error: Headers already sent out!');
            }
        } else {
            $this->response->redirect($this->url->link('order/order', '', true));
        }
    }

}