<?php

class ControllerOrderOffer extends Controller {

    private $error = array();

    public function add()
    {
        $this->load->language('order/offer');
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/image');

        if (!$this->customer->isLogged()) {
            $json['error_auth'] = $this->language->get('error_auth');
        } else {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $offer_id = $this->model_order_offer->addOffer($this->request->post);

                $offer_info = $this->model_order_offer->getOffer($offer_id);
                $order_info = $this->model_order_order->getOrder($this->request->post['order_id']);

                if ($offer_info['image']) {
                    $image = $this->model_tool_image->resize($offer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $json['offer'] = [
                    'offer_id' => $offer_info['offer_id'],
                    'login' => $offer_info['login'],
                    'is_owner' => $offer_info['is_owner'],
                    'bet' => $this->currency->format($offer_info['bet'], $this->config->get('config_currency')),
                    'earned' => $this->currency->format($offer_info['earned'], $this->config->get('config_currency')),
                    'pro' => $offer_info['pro'],
                    'text' => $offer_info['text'],
                    'online' => $offer_info['online'],
                    'image' => $image,
                    'date_added' => format_date($offer_info['date_added'], 'd.m.Y в H:i'),
                    'href' => $this->url->link('account/customer', 'customer_id=' . $offer_info['customer_id'])
                ];

                // Notification
                $this->load->model('tool/notification');
                $this->load->model('account/customer');

                $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
                $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);

                $order_id = $order_info['order_id'];

                // Уведомление order owner

                if ($offer_info['bet'] > 0) {
                    $bet = $this->currency->format($offer_info['bet'], $this->config->get('config_currency'));
                } else {
                    $bet = 'Без ставки';
                }

                $message = sprintf(
                    $this->language->get('notification_new_offer'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title'],
                    $bet
                );

                $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $order_info['customer_id'],
                    'text' => $message,
                ]);

                if ($order_customer_info['setting_email_notify']) {
                    $this->load->model('setting/setting');

                    $data['comment'] = '';
                    $data['message'] = $message;
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
                            'subject' => sprintf($this->language->get('text_subject_new_offer'), $this->config->get('config_name')),
                            'message' => $this->load->view('mail/offer', $data)
                        ]
                    ]);
                }

                // Уведомление offer owner
               /* $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $offer_info['customer_id'],
                    'text' => sprintf($this->language->get('notification_new_offer'),'Я',$this->url->link('order/order/info', 'order_id=' . $order_info['order_id']) ,$order_info['title']),
                ]);

                if ($offer_customer_info['setting_email_notify']) {
                    $this->load->model('setting/setting');

                    $data['comment'] = '';
                    $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
                    $data['message'] = sprintf($this->language->get('notification_new_offer'),'Я',$this->url->link('order/order/info', 'order_id=' . $order_info['order_id']) ,$order_info['title']);

                    // Unsubscribe generate
                    $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($offer_customer_info['email']);
                    $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                    $this->taskManager->set([
                        'channel' => 'emails',
                        'type' => 'email_send',
                        'time_exec' => time(),
                        'object' => [
                            'to' => $offer_customer_info['email'],
                            'subject' => sprintf($this->language->get('text_subject_new_offer'), $this->config->get('config_name')),
                            'message' => $this->load->view('mail/offer', $data)
                        ]
                    ]);
                }*/

                $json['success'] = $this->language->get('text_success_add');
            } else {
                if (isset($this->error['order'])) {
                    $json['error_order'] = $this->error['order'];
                }
                if (isset($this->error['bet'])) {
                    $json['error_bet'] = $this->error['bet'];
                }
                if (isset($this->error['earned'])) {
                    $json['error_earned'] = $this->error['earned'];
                }
                if (isset($this->error['access_denied'])) {
                    $json['error_access_denied'] = $this->error['access_denied'];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit()
    {
        $this->load->language('order/offer');
        $this->load->model('order/order');
        $this->load->model('order/offer');

        $json = array();

        if (!$this->customer->isLogged()) {
            $json['error_auth'] = $this->language->get('error_auth');
        } else {
            if (isset($this->request->post['order_id'])) {
                $order_id = (int)$this->request->post['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_order_order->getOrder($order_id);

            if ($order_info) {
                if (isset($this->request->post['offer_id'])) {
                    $offer_id = (int)$this->request->post['offer_id'];
                } else {
                    $offer_id = 0;
                }

                $offer_info = $this->model_order_offer->getOffer($offer_id);

                if ($offer_info && $offer_info['is_owner']) {
                    $this->model_order_offer->editOffer($offer_id, $this->request->post);

                    $json['success'] = $this->language->get('text_success_edit');

                    // Notification
                    $this->load->model('tool/notification');
                    $this->load->model('account/customer');

                    $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
                    $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);

                    $offer_info = $this->model_order_offer->getOffer($offer_id);

                    // Уведомление order owner

                    if ($offer_info['bet'] > 0) {
                        $bet = $this->currency->format($offer_info['bet'], $this->config->get('config_currency'));
                    } else {
                        $bet = 'Без ставки';
                    }

                    $message = sprintf(
                        $this->language->get('notification_edit_offer'),
                        '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                        $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                        $order_info['title'],
                        $bet
                    );

                    $this->model_tool_notification->set([
                        'type' => 'order',
                        'customer_id' => $order_info['customer_id'],
                        'text' => $message,
                    ]);

                    if ($order_customer_info['setting_email_notify']) {
                        $this->load->model('setting/setting');

                        $data['message'] = $message;
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
                                'subject' => sprintf($this->language->get('text_subject_edit_offer'), $this->config->get('config_name')),
                                'message' => $this->load->view('mail/offer', $data)
                            ]
                        ]);
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function offer()
    {
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/image');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if ($order_info && $order_info['is_owner']) {
            if (isset($this->request->get['offer_id'])) {
                $offer_id = (int)$this->request->get['offer_id'];
            } else {
                $offer_id = 0;
            }

            $offer_info = $this->model_order_offer->getOffer($offer_id);

            if ($offer_info) {
                if ($offer_info['image']) {
                    $image = $this->model_tool_image->resize($offer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $data['customer'] = [
                    'login' => $offer_info['login'],
                    'rating' => $offer_info['rating'],
                    'image' => $image,
                    'chat' => $this->url->link('message/chat', 'chat_id=' . $offer_info['customer_id']),
                    'href' => $this->url->link('account/customer', 'customer_id=' . $offer_info['customer_id']),
                ];

                $earned = $offer_info['bet'] + $offer_info['bet'] / 100 * (int)$this->config->get('config_commission_customer');

                $data['bet'] = $this->currency->format($offer_info['bet'], $this->config->get('config_currency'));
                $data['earned'] = $this->currency->format($earned, $this->config->get('config_currency'));

                $data['commission'] = $this->config->get('config_commission_customer');
                $data['order_id'] = $order_id;
                $data['offer_id'] = $offer_id;

                if ($offer_info['bet'] > 0) {
                    $this->response->setOutput($this->load->view('order/offer_info', $data));
                } else {
                    $this->response->setOutput($this->load->view('order/offer_no_bet', $data));
                }
            } else {
                $this->response->setOutput($this->load->view('order/offer_access_denied'));
            }
        } else {
            $this->response->setOutput($this->load->view('order/offer_access_denied'));
        }
    }

    public function info()
    {
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/image');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        $json = array();

        $order_info = $this->model_order_order->getOrder($order_id);

        if ($order_info) {
            if (isset($this->request->get['offer_id'])) {
                $offer_id = (int)$this->request->get['offer_id'];
            } else {
                $offer_id = 0;
            }

            $offer_info = $this->model_order_offer->getOffer($offer_id);

            if ($offer_info && $offer_info['is_owner']) {

                $json['offer'] = $offer_info;

                if ($offer_info['image']) {
                    $image = $this->model_tool_image->resize($offer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $json['offer']['customer'] = [
                    'login' => $offer_info['login'],
                    'rating' => $offer_info['rating'],
                    'image' => $image,
                    'href' => $this->url->link('account/customer', 'customer_id=' . $offer_info['customer_id']),
                ];

                $json['offer']['order_id'] = $order_id;
                $json['offer']['offer_id'] = $offer_id;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function assignOffer()
    {
        $json = array();
        $error = array();

        $this->load->model('order/offer');

        if (isset($this->request->get['offer_id'])) {
            $offer_id = (int)$this->request->get['offer_id'];
        } else {
            $offer_id = 0;
        }

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $result = $this->model_order_offer->assignedOffer($offer_id, $order_id);

        if (isset($result['success'])) {
            $json = $result;
        } else {
            $json['error'] = $result;
            if (isset($result['redirect'])) {
                $json['redirect'] = $result['redirect'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function acceptOffer()
    {
        $json = array();
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем находится ли заказ в статусе ожидания
            if ($order_info['order_status_id'] != $this->config->get('config_pending_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if (!$total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_not_assigned');
            }
        }

        if (isset($this->request->get['offer_id'])) {
            $offer_id = (int)$this->request->get['offer_id'];
        } else {
            $offer_id = 0;
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOffer($offer_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {

                // Проверяем хозяина предложения
                if (!$offer_info['is_owner']) {
                    $error['access_denied'] = $this->language->get('error_access_denied');
                }

                // Проверяем назначен ли offer исполнителем
                if (!$offer_info['assigned']) {
                    $error['not_assigned'] = $this->language->get('error_not_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {
            // Переводим заказ в статус "в работе"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_progress_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $offer_info['customer_id'],
                'text' => $this->language->get('text_customer_accept')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);

            // Уведомление order owner

            $message = sprintf(
                $this->language->get('notification_accept_offer'),
                '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                $order_info['title']
            );

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => $message,
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = $message;

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
                        'subject' => sprintf($this->language->get('text_subject_accept_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner

            $message = sprintf(
                $this->language->get('notification_accept_offer'),
                'Я',
                $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                $order_info['title']
            );

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => $message,
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = $message;

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
                        'subject' => sprintf($this->language->get('text_subject_accept_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            $json['success'] = $this->language->get('text_processing');
        } else {
            $json['error'] = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function cancelOffer()
    {
        $json = array();
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем находится ли заказ в статусе ожидания
            if ($order_info['order_status_id'] != $this->config->get('config_pending_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if (!$total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_not_assigned');
            }
        }

        if (isset($this->request->get['offer_id'])) {
            $offer_id = (int)$this->request->get['offer_id'];
        } else {
            $offer_id = 0;
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOffer($offer_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {

                // Проверяем хозяина предложения
                if (!$offer_info['is_owner'] && !$order_info['is_owner']) {
                    $error['access_denied'] = $this->language->get('error_access_denied');
                }

                // Проверяем назначен ли offer исполнителем
                if (!$offer_info['assigned']) {
                    $error['not_assigned'] = $this->language->get('error_not_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {
            // Возвращяем заблокированные деньги заказчику
            $this->model_account_customer->returnBlockedCash([
                'customer_id' => $order_info['customer_id'],
                'order_id' => $order_id,
                'offer_id' => $offer_id
            ]);

            // отменяем offer
            $this->model_order_offer->cancelOffer($offer_id);

            // Переводим заказ в статус "Открытый"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_open_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            if ($order_info['is_owner']) { //text_customer_canceled_assigned_owner
                $message = $this->language->get('text_customer_canceled_assigned_owner');
                $this->model_order_history->addHistory(array(
                    'order_id' => $order_id,
                    'customer_id' => $order_info['customer_id'],
                    'text' => $message
                ));
            } else {
                $message = $this->language->get('text_customer_canceled_assigned');
                $this->model_order_history->addHistory(array(
                    'order_id' => $order_id,
                    'customer_id' => $offer_info['customer_id'],
                    'text' => $message
                ));
            }

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);

            // Уведомление order owner

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    ($order_info['is_owner'] ? $this->language->get('notification_cancel_offer_order_owner'): $this->language->get('notification_cancel_offer')),
                    ($order_info['is_owner'] ? 'Я': '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>'),
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    ($order_info['is_owner'] ? $this->language->get('notification_cancel_offer_order_owner'): $this->language->get('notification_cancel_offer')),
                    ($order_info['is_owner'] ? 'Я': '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>'),
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
                        'subject' => sprintf(
                            ($order_info['is_owner'] ? $this->language->get('text_subject_cancel_offer_order_owner'): $this->language->get('text_subject_cancel_offer')),
                            $this->config->get('config_name')
                        ),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => sprintf(
                    ($offer_info['is_owner'] ? $this->language->get('notification_cancel_offer') : $this->language->get('notification_cancel_offer_order_owner')),
                    ($offer_info['is_owner'] ? 'Я': '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>'),
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    ($offer_info['is_owner'] ? $this->language->get('notification_cancel_offer') : $this->language->get('notification_cancel_offer_order_owner')),
                    ($offer_info['is_owner'] ? 'Я': '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>'),
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
                        'subject' => sprintf(
                            ($offer_info['is_owner'] ? $this->language->get('text_subject_cancel_offer'): $this->language->get('text_subject_cancel_offer_order_owner')),
                            $this->config->get('config_name')
                        ),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление owner
            $json['success'] = $this->language->get('text_canceled_offer');
        } else {
            $json['error'] = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function awaitingOffer()
    {
        $json = array();
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем находится ли заказ в работе
            if ($order_info['order_status_id'] != $this->config->get('config_progress_order_status_id') && $order_info['order_status_id'] != $this->config->get('config_revision_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if (!$total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_not_assigned');
            }
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOfferAssigned($order_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {

                // Проверяем хозяина предложения
                if (!$offer_info['is_owner']) {
                    $error['access_denied'] = $this->language->get('error_access_denied');
                }

                // Проверяем назначен ли offer исполнителем
                if (!$offer_info['assigned']) {
                    $error['not_assigned'] = $this->language->get('error_not_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {
            // Переводим заказ в статус "ожидает скачивания"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_awaiting_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $offer_info['customer_id'],
                'text' => $this->language->get('text_customer_awaiting')
            ));

            // Уведомление в order/order/addOfferAttachment
            $json['success'] = $this->language->get('text_awaiting');
        } else {
            $json['error'] = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function completeOffer()
    {
        $json = array();
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('account/customer');
        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем хозяина заказа
            if (!$order_info['is_owner']) {
                $error['access_denied'] = $this->language->get('error_access_denied');
            }

            // Проверяем находится не выполнен ли заказ
            if ($order_info['order_status_id'] == $this->config->get('config_complete_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }
            // Проверяем находится ли заказ на проверке
            if ($order_info['order_status_id'] != $this->config->get('config_verification_order_status_id') && $order_info['order_status_id'] != $this->config->get('config_revision_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if (!$total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_not_assigned');
            }
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOfferAssigned($order_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {

                // Проверяем назначен ли offer исполнителем
                if (!$offer_info['assigned']) {
                    $error['not_assigned'] = $this->language->get('error_not_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {

            // Зачисляем деньги автору
            $this->model_account_customer->setBalanceOfferFromBlockedCash($offer_info['customer_id'], $offer_info['offer_id']);

            // Переводим заказ в статус "готовые"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_complete_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $order_info['customer_id'],
                'text' => $this->language->get('text_customer_complete')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);
            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // Уведомление order owner

            $notice_message = sprintf(
                $this->language->get('notification_complete_offer'),
                'Я',
                $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                $order_info['title']
            );

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => $notice_message,
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = $notice_message;

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
                        'subject' => sprintf($this->language->get('text_subject_complete_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            $notice_message = sprintf(
                $this->language->get('notification_complete_offer'),
                '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>',
                $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                $order_info['title']
            );
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => $notice_message,
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = $notice_message;

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
                        'subject' => sprintf($this->language->get('text_subject_complete_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление owner
            $json['success'] = $this->language->get('text_complete');
        } else {
            $json['error'] = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function revisionOffer()
    {
        $json = array();
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('account/customer');
        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем хозяина заказа
            if (!$order_info['is_owner']) {
                $error['access_denied'] = $this->language->get('error_access_denied');
            }

            // Проверяем находится не выполнен ли заказ
            /*if ($order_info['order_status_id'] == $this->config->get('config_complete_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }*/
            // Проверяем находится ли заказ на проверке
            /*if ($order_info['order_status_id'] != $this->config->get('config_verification_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }*/

            // Проверяем есть ли выбранный исполнитель
            $total_offer_assigned = $this->model_order_offer->getTotalOfferAssigned($order_id);
            if (!$total_offer_assigned) {
                $error['assigned'] = $this->language->get('error_not_assigned');
            }
        }

        if (!$error) {
            $offer_info = $this->model_order_offer->getOfferAssigned($order_id);

            if (!$offer_info) {
                $error['unknown'] = $this->language->get('error_unknown');
            } else {

                // Проверяем назначен ли offer исполнителем
                if (!$offer_info['assigned']) {
                    $error['not_assigned'] = $this->language->get('error_not_assigned');
                }

                // Проверяем совпадает ли id заказа
                if ($order_info['order_id'] != $offer_info['order_id']) {
                    $error['unknown'] = $this->language->get('error_unknown');
                }
            }
        }

        if (!$error) {
            // Переводим заказ в статус "на доработке"
            $this->model_order_order->setOrderStatus($order_id, $this->config->get('config_revision_order_status_id'));

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $order_info['customer_id'],
                'text' => $this->language->get('text_customer_revision')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);
            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // Уведомление order owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_revision_offer'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_revision_offer'),
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
                        'subject' => sprintf($this->language->get('text_subject_revision_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_revision_offer'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $order_customer_info['customer_id']) . '">' . $order_customer_info['login'] . '</a>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']),
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_revision_offer'),
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
                        'subject' => sprintf($this->language->get('text_subject_revision_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление owner
            $json['success'] = $this->language->get('text_revision');
        } else {
            $json['error'] = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function assignOfferDouble()
    {
        $error = array();

        $this->load->language('order/offer');

        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->session->data['order_id'])) {
            $order_id = (int)$this->session->data['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

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

        if (isset($this->session->data['offer_id'])) {
            $offer_id = (int)$this->session->data['offer_id'];
        } else {
            $offer_id = 0;
        }

        $offer_info = array();

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
                    $json['redirect'] = str_replace('&amp;','&', $this->url->link('account/finance/payment', 'amount=' . $amount));
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

        if (isset($this->session->data['referer']) && $this->session->data['referer'] != '') {
            $referer = $this->session->data['referer'];
        } else {
            unset($this->session->data['referer']);
            unset($this->session->data['order_id']);
            unset($this->session->data['offer_id']);
            $referer = $this->url->link('account/finance/success');
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
            $this->model_order_offer->assignedOffer($offer_id);

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

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($order_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

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

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($offer_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

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

            unset($this->session->data['order_id']);
            unset($this->session->data['offer_id']);
            unset($this->session->data['referer']);
            //$json['success'] = sprintf($this->language->get('text_assigned'), $this->currency->format($bet, $this->config->get('config_currency')));
        }
        $this->response->redirect($referer);
    }

    private function validate()
    {
        if (!isset($this->request->post['order_id']) || $this->request->post['order_id'] < 1) {
            $this->error['order'] = $this->language->get('error_order');
        }

        $order_info = $this->model_order_order->getOrder($this->request->post['order_id']);
        if (!$order_info) {
            $this->error['order'] = $this->language->get('error_order');
        } else {
            if ($order_info['order_status_id'] != $this->config->get('config_open_order_status_id')) {
                $this->error['order'] = $this->language->get('error_order');
            }

            $offers = $this->model_order_offer->getTotalOffersByOrderIdAndCustomerId($this->request->post['order_id'], $this->customer->getId());
            if ($offers) {
                $this->error['offer'] = $this->language->get('error_offer');
            }
        }

        if ($this->customer->getGroupId() != 2) {
            $this->error['access_denied'] = $this->language->get('error_access_denied');
        }

        /*if (!isset($this->request->post['bet']) || $this->request->post['bet'] < 1) {
            $this->error['bet'] = $this->language->get('error_bet');
        }

        if (!isset($this->request->post['earned']) || $this->request->post['earned'] < 1) {
            $this->error['earned'] = $this->language->get('error_earned');
        }*/

        if (!$this->error) {
            return true;
        }
        return false;
    }
}
