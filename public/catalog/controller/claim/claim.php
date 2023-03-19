<?php
class ControllerClaimClaim extends Controller
{
    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('claim/claim');

        if (isset($this->request->get['filter_status_off'])) {
            $filter_status_off = (int)$this->request->get['filter_status_off'];
        } else {
            $filter_status_off = '';
        }

        if (isset($this->request->get['filter_status_on'])) {
            $filter_status_on = (int)$this->request->get['filter_status_on'];
        } else {
            $filter_status_on = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/order', '', true)
        );

        $this->load->model('claim/claim');
        $this->load->model('order/order');
        $this->load->model('account/customer');

        $limit = 10;

        $data['claims'] = array();

        $filter_data = array(
            'filter_status_off' => $filter_status_off,
            'filter_status_on' => $filter_status_on,
            'sort' => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $limit
        );

        $claim_total = $this->model_claim_claim->getTotalClaims($filter_data);
        $results = $this->model_claim_claim->getClaims($filter_data);

        $data['claims'] = array();

        foreach ($results as $result) {
            $object_title = '';
            $object_href = '';

            if ($result['type'] == 'order') {
                $order_info = $this->model_order_order->getOrder($result['object_id']);

                $object_title = $order_info['title'];
                $object_href = $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']);
            }

            $customer_info = $this->model_account_customer->getCustomerInfo($result['customer_id']);
            $defendant_info = $this->model_account_customer->getCustomerInfo($result['defendant_id']);

            $data['claims'][] = [
                'object_title' => $object_title,
                'object_href' => $object_href,
                'customer_login' => $customer_info['login'],
                'customer_href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id']),
                'defendant_login' => $defendant_info['login'],
                'defendant_href' => $this->url->link('account/customer', 'customer_id=' . $defendant_info['customer_id']),
                'comment' => $result['comment'],
                'status' => $result['status'],
                'date_added' => format_date($result['date_added'], 'd.m.Y'),
                'href' => $this->url->link('claim/claim/info', 'claim_id=' . $result['claim_id']),
            ];
        }

        $url = '';

        if ($filter_status_off) {
            $url .= "&filter_status_off=" . $filter_status_off;
        }

        if ($filter_status_on) {
            $url .= "&filter_status_on=" . $filter_status_on;
        }

        $pagination = new Pagination();
        $pagination->total = $claim_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('claim/claim', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($claim_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($claim_total - $limit)) ? $claim_total : ((($page - 1) * $limit) + $limit), $claim_total, ceil($claim_total / $limit));

        // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
        /*if ($page == 1) {
            $this->document->addLink($this->url->link('order/order', 'canonical'));
        } else {
            $this->document->addLink($this->url->link('order/order', 'page='. $page), 'canonical');
        }

        if ($page > 1) {
            $this->document->addLink($this->url->link('order/order', (($page - 2) ? 'page='. ($page - 1) : '')), 'prev');
        }

        if ($limit && ceil($claim_total / $limit) > $page) {
            $this->document->addLink($this->url->link('order/order', 'page='. ($page + 1)), 'next');
        }*/

        $data['filter_status_on'] = $filter_status_on;
        $data['filter_status_off'] = $filter_status_off;

        $data['continue'] = $this->url->link('account/account', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('claim/claim_list', $data));
    }

    public function info() {
        $this->load->language('claim/claim');

        if (isset($this->request->get['claim_id'])) {
            $claim_id = $this->request->get['claim_id'];
        } else {
            $claim_id = 0;
        }

        if (!$this->customer->isLogged()) {
            //$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->document->addScript('catalog/assets/js/claim.js', 'footer');

        $this->load->model('claim/claim');
        $this->load->model('account/customer');
        $this->load->model('order/order');

        $claim_info = $this->model_claim_claim->getClaim($claim_id);

        if ($claim_info) {
            $this->document->setTitle(sprintf($this->language->get('text_claim'), $claim_id));

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('claim/claim', $url, true)
            );

            $data['breadcrumbs'][] = array(
                'text' => sprintf($this->language->get('text_claim'), $claim_id),
                'href' => $this->url->link('claim/claim/info', 'claim_id=' . $this->request->get['claim_id'], true)
            );

            $object_title = '';
            $object_href = '';

            if ($claim_info['type'] == 'order') {
                $order_info = $this->model_order_order->getOrder($claim_info['object_id']);

                $object_title = $order_info['title'];
                $object_href = $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']);
            }

            $customer_info = $this->model_account_customer->getCustomerInfo($claim_info['customer_id']);
            $defendant_info = $this->model_account_customer->getCustomerInfo($claim_info['defendant_id']);
            $data['title'] = sprintf($this->language->get('text_claim'), $claim_id);
            $data['claim_id'] = $claim_info['claim_id'];
            $data['claim'] = [
                'claim_id' => $claim_info['claim_id'],
                'object_title' => $object_title,
                'object_href' => $object_href,
                'customer_login' => $customer_info['login'],
                'customer_href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id']),
                'defendant_login' => $defendant_info['login'],
                'defendant_href' => $this->url->link('account/customer', 'customer_id=' . $defendant_info['customer_id']),
                'comment' => $claim_info['comment'],
                'status' => $claim_info['status'],
                'date_added' => format_date($claim_info['date_added'], 'd.m.Y'),
                'href' => $this->url->link('claim/claim/info', 'claim_id=' . $claim_info['claim_id']),
            ];

            $attachments = $this->model_claim_claim->getClaimAttachment($claim_id);

            $data['attachments'] = array();
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $data['attachments'][] = [
                        'attachment_id' => $attachment['attachment_id'],
                        'is_owner'      => $attachment['customer_id'] == $this->customer->getId(),
                        'type'          => $attachment['type'],
                        'name'          => $attachment['name'],
                        'size'          => format_size($attachment['size']),
                        'date_added'    => format_date($attachment['date_added'], 'full_datetime'),
                        'href'          => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                        'upload'        => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                    ];
                }
            }

            $data['continue'] = $this->url->link('claim/claim', '', true);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('claim/claim_info', $data));
        } else {
            return new Action('error/not_found');
        }
    }

    public function add()
    {
        if (!$this->customer->isLogged()) {
            //$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('account/customer');

        $white_type_objects = ['order'];

        if (isset($this->request->get['type']) &&  in_array($this->request->get['type'], $white_type_objects)) {
            $type = $this->request->get['type'];
        } else {
            $type = '';
        }

        if (isset($this->request->get['object_id']) && $this->request->get['object_id'] > 0) {
            $object_id = (int)$this->request->get['object_id'];
        } else {
            $object_id = '';
        }

        if (isset($this->request->get['defendant_id']) && $this->request->get['defendant_id'] > 0) {
            $defendant_id = (int)$this->request->get['defendant_id'];
        } else {
            $defendant_id = 0;
        }

        $this->document->setTitle($this->language->get('text_claim_add'));

        $object_info = array();

        if ($type && $object_id) {
            switch ($type) {
                case "order":
                    $this->load->model('order/order');
                    $this->load->model('order/offer');

                    $order_info = $this->model_order_order->getOrder($object_id);
                    $offer_info = $this->model_order_offer->getOfferAssigned($object_id);

                    if ($order_info && $offer_info) {
                        if (!$order_info['is_owner'] && !$offer_info['is_owner']) {
                            return new Action('error/not_found');
                        }
                    } else {
                        return new Action('error/not_found');
                    }

                    $object_info = [
                        "title" => $order_info['title'],
                        "href" => $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    ];
                    break;
            }
        }

        $defendant_info = array();

        if ($defendant_id > 0) {
            $defendant_info = $this->model_account_customer->getCustomerInfo($defendant_id);
        }

        if ($object_info && $defendant_info) {
            $data['object'] = $object_info;

            $customer_info = $this->model_account_customer->getCustomerInfo($this->customer->getId());
            $data['customer'] = [
                "login" => $customer_info['login'],
                "href" => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'])
            ];

            $data['defendant'] = [
                "login" => $defendant_info['login'],
                "href" => $this->url->link('account/customer', 'customer_id=' . $defendant_info['customer_id'])
            ];

            $data['date_add'] = date('d.m.Y');

            $data['type'] = $type;
            $data['object_id'] = $object_id;
            $data['defendant_id'] = $defendant_id;

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('claim/claim_add', $data));
        } else {
            return new Action('error/not_found');
        }
    }

    public function create()
    {
        if (!$this->customer->isLogged()) {
            //$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('account/customer');

        $json = array();

        $white_type_objects = ['order'];

        if (isset($this->request->post['type']) &&  in_array($this->request->post['type'], $white_type_objects)) {
            $type = $this->request->post['type'];
        } else {
            $type = '';
        }

        if (isset($this->request->post['object_id']) && $this->request->post['object_id'] > 0) {
            $object_id = (int)$this->request->post['object_id'];
        } else {
            $object_id = '';
        }

        if (isset($this->request->post['defendant_id']) && $this->request->post['defendant_id'] > 0) {
            $defendant_id = (int)$this->request->post['defendant_id'];
        } else {
            $defendant_id = 0;
        }

        if (isset($this->request->post['comment']) && utf8_strlen($this->request->post['comment']) > 3) {
            $comment = $this->request->post['comment'];
        } else {
            $comment = '';
            $json['error_comment'] = $this->language->get('error_comment');
        }

        $object_info = array();

        if ($type && $object_id && empty($json)) {
            switch ($type) {
                case "order":
                    $this->load->model('order/order');
                    $this->load->model('order/offer');

                    $order_info = $this->model_order_order->getOrder($object_id);
                    $offer_info = $this->model_order_offer->getOfferAssigned($object_id);

                    if ($order_info && $offer_info) {
                        if (!$order_info['is_owner'] && !$offer_info['is_owner']) {
                            $json['error_access_denied'] = $this->language->get('error_access_denied');
                        }
                    } else {
                        $json['error_access_denied'] = $this->language->get('error_access_denied');
                    }

                    $object_info = [
                        "title" => $order_info['title'],
                        "href" => $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    ];
                    break;
            }
        }

        $defendant_info = array();

        if ($defendant_id > 0) {
            $defendant_info = $this->model_account_customer->getCustomerInfo($defendant_id);
        }

        if (empty($json)) {
            if ($object_info && $defendant_info) {
                $claim_id = $this->model_claim_claim->addClaim([
                    'type' => $type,
                    'object_id' => $object_id,
                    'customer_id' => $this->customer->getId(),
                    'defendant_id' => $defendant_id,
                    'comment' => $comment,
                    'attachment' => $this->request->post['attachment'] ?? [],
                ]);

                // Notification
                $this->load->model('tool/notification');
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomerInfo($this->customer->getId());

                // Уведомление order owner
                $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $customer_info['customer_id'],
                    'text' => sprintf(
                        $this->language->get('notification_claim_created'),
                        'Я',
                        $object_info['href'],
                        $object_info['title']
                    ),
                ]);

                if ($customer_info['setting_email_notify']) {
                    $this->load->model('setting/setting');

                    $data['message'] = sprintf(
                        $this->language->get('notification_claim_created'),
                        'Я',
                        $object_info['href'],
                        $object_info['title']
                    );

                    $data['comment'] = '';

                    $this->taskManager->set([
                        'channel' => 'emails',
                        'type' => 'email_send',
                        'time_exec' => time(),
                        'object' => [
                            'to' => $customer_info['email'],
                            'subject' => sprintf($this->language->get('subject_claim_created'), $this->config->get('config_name')),
                            'message' => $this->load->view('mail/claim', $data)
                        ]
                    ]);
                }

                // Уведомление offer owner
                $this->model_tool_notification->set([
                    'type' => 'order',
                    'customer_id' => $defendant_info['customer_id'],
                    'text' => sprintf(
                        $this->language->get('notification_claim_created'),
                        '<a href="' . $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id']) . '">' . $customer_info['login'] . '</a>',
                        $object_info['href'],
                        $object_info['title']
                    ),
                ]);

                if ($defendant_info['setting_email_notify']) {
                    $this->load->model('setting/setting');

                    $data['message'] = sprintf(
                        $this->language->get('notification_claim_created'),
                        '<a href="' . $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id']) . '">' . $customer_info['login'] . '</a>',
                        $object_info['href'],
                        $object_info['title']
                    );

                    $data['comment'] = '';

                    $this->taskManager->set([
                        'channel' => 'emails',
                        'type' => 'email_send',
                        'time_exec' => time(),
                        'object' => [
                            'to' => $defendant_info['email'],
                            'subject' => sprintf($this->language->get('subject_claim_created'), $this->config->get('config_name')),
                            'message' => $this->load->view('mail/claim', $data)
                        ]
                    ]);
                }

                $json['success'] = $this->language->get('text_success');
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('claim/claim/info', 'claim_id=' . $claim_id));
            } else {
                $json['error_access_denied'] = $this->language->get('error_access_denied');
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function addAttachment()
    {
        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('tool/attachment');

        if (isset($this->request->get['claim_id'])) {
            $claim_id = $this->request->get['claim_id'];
        } else {
            $claim_id = 0;
        }

        $claim_info = $this->model_claim_claim->getClaim($claim_id);

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($claim_info && ($claim_info['customer_id'] == $this->customer->getId() ||  $claim_info['defendant_id'] == $this->customer->getId() ) && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('claim/claim');
            $this->model_claim_claim->addAttachment($claim_id, $attachment_id);


            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomerInfo($claim_info['customer_id']);
            $defendant_info = $this->model_account_customer->getCustomerInfo($claim_info['defendant_id']);

            $claim_title = "Претензия №" . $claim_id;
            // Уведомление order owner
            (new \Model\Notification($this->db))
                ->set(
                    $customer_info['customer_id'],
                    'order',
                    sprintf(
                        $this->language->get('notification_attachment_claim'),
                        $claim_info['customer_id'] == $this->customer->getId() ? 'Я' : "<a href='" . $this->url->link('account/customer', 'customer_id=' . $defendant_info['customer_id']) . "'></a>",
                        $this->url->link('claim/claim/info', 'claim_id=' . $claim_info['claim_id']),
                        $claim_title
                    )
                );

            // Уведомление order owner
            (new \Model\Notification($this->db))
                ->set(
                    $defendant_info['customer_id'],
                    'order',
                    sprintf(
                        $this->language->get('notification_attachment_claim'),
                        $claim_info['customer_id'] == $this->customer->getId() ? "<a href='" . $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id']) . "'></a>" : 'Я',
                        $this->url->link('claim/claim/info', 'claim_id=' . $claim_info['claim_id']),
                        $claim_title
                    )
                );

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('claim/claim/info', 'claim_id=' . $claim_id);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteAttachment()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('tool/attachment');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($order_info && $order_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->deleteAttachment($order_id, $attachment_id);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_remove_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}