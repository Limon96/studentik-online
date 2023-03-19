<?php

class ControllerOrderMessage extends Controller {

    private $error = array();

    public function index()
    {
        if (isset($this->request->get['offer_id'])) {
            $offer_id = (int)$this->request->get['offer_id'];
        } else {
            $offer_id = 0;
        }

        $this->load->model('order/message');
        $this->load->model('tool/image');

        $result = $this->model_order_message->getMessages($offer_id);

        $data['messages'] = array();

        if ($result) {
            $result = array_reverse($result);

            foreach ($result as $message) {
                if ($message['image']) {
                    $image = $this->model_tool_image->resize($message['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $data['messages'][] = [
                    'offer_message_id' => $message['offer_message_id'],
                    'offer_id' => $message['offer_id'],
                    "text" => nl2br(htmlspecialchars_decode($message['text'])),
                    'date_added' => format_date($message['date_added'], 'd.m.Y H:i'),
                    'image' => $image,
                    'login' => $message['login'],
                    'href' => $this->url->link('account/customer', 'customer_id=' . $message['customer_id'])
                ];
            }
        }

        $this->response->setOutput($this->load->view('order/message', $data));
    }

    public function live()
    {
        if (isset($this->request->post['offer_id'])) {
            $offer_id = (int)$this->request->post['offer_id'];
        } else {
            $offer_id = 0;
        }

        if (isset($this->request->post['last_offer_message_id'])) {
            $last_offer_message_id = (int)$this->request->post['last_offer_message_id'];
        } else {
            $last_offer_message_id = 0;
        }

        $json['offer_id'] = $offer_id;
        $json['last_offer_message_id'] = $last_offer_message_id;

        $this->load->model('order/message');
        $this->load->model('tool/image');

        $result = $this->model_order_message->getMessages($offer_id, $last_offer_message_id);

        $json['messages'] = array();

        if ($result) {
            $result = array_reverse($result);

            foreach ($result as $message) {
                if ($message['image']) {
                    $image = $this->model_tool_image->resize($message['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $json['messages'][] = [
                    'offer_message_id' => $message['offer_message_id'],
                    'offer_id' => $message['offer_id'],
                    "text" => nl2br(htmlspecialchars_decode($message['text'])),
                    'date_added' => format_date($message['date_added'], 'd.m.Y H:i'),
                    'image' => $image,
                    'login' => $message['login'],
                    'href' => $this->url->link('account/customer', 'customer_id=' . $message['customer_id'])
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function send()
    {
        $this->load->language('order/offer');
        $this->load->model('order/offer');
        $this->load->model('order/message');
        if (!$this->customer->isLogged()) {
            $json['error_auth'] = $this->language->get('error_auth');
        } else {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $offer_message_id = $this->model_order_message->addMessage($this->request->post);

                // Notification
                $this->load->model('tool/notification');
                $this->load->model('account/customer');
                $this->load->model('order/order');

                $offer_info = $this->model_order_offer->getOffer($this->request->post['offer_id']);
                $order_info = $this->model_order_order->getOrder($offer_info['order_id']);

                $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);
                $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

                // Уведомление order owner
                $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $order_info['customer_id'],
                    'text' => sprintf(
                        $this->language->get('notification_comment_offer'),
                        $order_info['is_owner'] ? 'Я' : '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                        $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                        $order_info['title']
                    ),
                    'comment' => $this->request->post['text']
                ]);

                // Уведомление offer owner
                $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $offer_info['customer_id'],
                    'text' => sprintf(
                        $this->language->get('notification_comment_offer'),
                        $offer_info['is_owner'] ? 'Я' : '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>',
                        $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                        $order_info['title']
                    ),
                    'comment' => $this->request->post['text']
                ]);

                $json['success'] = $this->language->get('text_success');
            } else {
                if (isset($this->error['offer'])) {
                    $json['error_offer'] = $this->error['offer'];
                }
                if (isset($this->error['text'])) {
                    $json['error_text'] = $this->error['text'];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate()
    {
        if (!isset($this->request->post['offer_id']) || $this->request->post['offer_id'] < 1) {
            $this->error['offer'] = $this->language->get('error_offer');
        }

        $offer_info = $this->model_order_offer->getOffer($this->request->post['offer_id']);
        if (!$offer_info) {
            $this->error['offer'] = $this->language->get('error_offer');
        }

        if (!isset($this->request->post['text']) || strlen($this->request->post['text']) < 1) {
            $this->error['text'] = $this->language->get('error_text');
        }

        if (!$this->error) {
            return true;
        }
        return false;
    }
}