<?php
class ControllerClaimClaim extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('claim/claim');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('claim/claim');
        $this->load->model('customer/customer');

        $this->getList();
    }

    public function delete() {
        $this->load->language('claim/claim');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('claim/claim');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $message_id) {
                $this->model_support_message->deleteAttributeGroup($message_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . $this->request->get['filter_email'];
            }

            if (isset($this->request->get['filter_viewed'])) {
                $url .= '&filter_viewed=' . $this->request->get['filter_viewed'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_viewed'])) {
            $url .= '&filter_viewed=' . $this->request->get['filter_viewed'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['delete'] = $this->url->link('claim/claim/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['claims'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $claim_total = $this->model_claim_claim->getTotalClaims($filter_data);

        $results = $this->model_claim_claim->getClaims($filter_data);

        foreach ($results as $result) {

            $white_list = ['order'];
            $object = [];
            if (in_array($result['type'], $white_list)) {
                switch ($result['type']) {
                    case 'order':
                        $this->load->model('order/order');
                        $order_info = $this->model_order_order->getOrder($result['object_id']);
                        $object = [
                            'title' => $order_info['title'],
                            'href' => HTTP_CATALOG . 'index.php?route=order/order/info&order_id=' . $order_info['order_id'],
                        ];
                        break;

                }
            }

            $customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
            $defendant_info = $this->model_customer_customer->getCustomer($result['defendant_id']);

            $data['claims'][] = array(
                'claim_id'              => $result['claim_id'],
                'title'                 => '#' . $result['claim_id'],
                'object_title'          => $object['title'],
                'object_href'           => $object['href'],
                'customer_login'        => $customer_info['login'],
                'customer_href'         => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $customer_info['customer_id'],
                'defendant_login'       => $defendant_info['login'],
                'defendant_href'        => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $defendant_info['customer_id'],
                'status'                => $result['status'],
                'viewed'                => $result['viewed'],
                'date_added'            => format_date($result['date_added'], 'full_datetime'),
                'view'                  => $this->url->link('claim/claim/view', 'user_token=' . $this->session->data['user_token'] . '&claim_id=' . $result['claim_id'] . $url, true)
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_viewed'] = $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . '&sort=viewed' . $url, true);
        $data['sort_date_added'] = $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_viewed'])) {
            $url .= '&filter_viewed=' . $this->request->get['filter_viewed'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $claim_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($claim_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($claim_total - $this->config->get('config_limit_admin'))) ? $claim_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $claim_total, ceil($claim_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('claim/claim_list', $data));
    }

    public function view() {

        $this->load->language('claim/claim');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('claim/claim');

        $data['text_form'] = !isset($this->request->get['claim_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['question'])) {
            $data['error_question'] = $this->error['question'];
        } else {
            $data['error_question'] = array();
        }

        if (isset($this->error['answer'])) {
            $data['error_answer'] = $this->error['answer'];
        } else {
            $data['error_answer'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_viewed'])) {
            $url .= '&filter_viewed=' . $this->request->get['filter_viewed'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['claim_id'])) {
            $data['action'] = $this->url->link('claim/claim/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('claim/claim/edit', 'user_token=' . $this->session->data['user_token'] . '&claim_id=' . $this->request->get['claim_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['claim_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $claim_info = $this->model_claim_claim->getClaim($this->request->get['claim_id']);
        } else {
            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . $this->request->get['filter_email'];
            }

            if (isset($this->request->get['filter_viewed'])) {
                $url .= '&filter_viewed=' . $this->request->get['filter_viewed'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('claim/claim', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->load->model('customer/customer');

        $object_title = '';
        $object_href = '';

        $data['blocked_cash'] = array();

        if ($claim_info['type'] == 'order') {
            $this->load->model('order/order');
            $order_info = $this->model_order_order->getOrder($claim_info['object_id']);

            $object_status = $order_info['order_status_id'] == $this->config->get('config_complete_order_status_id');
            $object_title = $order_info['title'];
            $object_href = HTTP_CATALOG . 'index.php?route=order/order/info&order_id=' . $order_info['order_id'];

            $this->load->model('finance/blocked_cash');
            $blocked_cash = $this->model_finance_blocked_cash->getBlockedCashByOrder($order_info['order_id']);

            // Attachments
            $order_attachments = $this->model_order_order->getOrderAttachment($claim_info['object_id']);

            $data['order_attachments'] = array();

            if ($order_attachments) {
                foreach ($order_attachments as $attachment) {
                    $data['order_attachments'][] = [
                        'attachment_id' => $attachment['attachment_id'],
                        'type'          => $attachment['type'],
                        'name'          => $attachment['name'],
                        'size'          => format_size($attachment['size']),
                        'date_added'    => format_date($attachment['date_added'], 'full_datetime'),
                        'href'          => HTTP_CATALOG . 'index.php?route=common/download&attachment_id=' . $attachment['attachment_id'],
                        'upload'        => HTTP_CATALOG . 'index.php?route=common/download&attachment_id=' . $attachment['attachment_id'],
                    ];
                }
            }

            // Attachments
            $offer_attachments = $this->model_order_order->getOrderOfferAttachments($claim_info['object_id']);

            $data['offer_attachments'] = array();

            if ($offer_attachments) {
                foreach ($offer_attachments as $attachment) {
                    $data['offer_attachments'][] = [
                        'attachment_id' => $attachment['attachment_id'],
                        'type'          => $attachment['type'],
                        'name'          => $attachment['name'],
                        'size'          => format_size($attachment['size']),
                        'date_added'    => format_date($attachment['date_added'], 'full_datetime'),
                        'href'          => HTTP_CATALOG . 'index.php?route=common/download&attachment_id=' . $attachment['attachment_id'],
                        'upload'        => HTTP_CATALOG . 'index.php?route=common/download&attachment_id=' . $attachment['attachment_id'],
                    ];
                }
            }

            if ($blocked_cash) {
                $data['blocked_cash'] = [
                    'customer_blocked_cash_id'  => $blocked_cash['customer_blocked_cash_id'],
                    'order_title'               => $blocked_cash['order_title'],
                    'customer_login'            => $blocked_cash['customer_login'],
                    'offer_customer_login'      => $blocked_cash['offer_customer_login'],
                    'balance'                   => $this->currency->format($blocked_cash['balance'], $this->config->get('config_currency')),
                    'date_added'                => format_date($blocked_cash['date_added'], 'd.m.Y'),
                    'date_end'                  => format_date($blocked_cash['date_end'], 'd.m.Y'),
                    'return'                      => htmlspecialchars_decode($this->url->link('finance/blocked_cash/return', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $blocked_cash['customer_blocked_cash_id'] . $url, true)),
                    'pay'                      => htmlspecialchars_decode($this->url->link('finance/blocked_cash/pay', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $blocked_cash['customer_blocked_cash_id'] . $url, true))
                ];
            }
        }

        $this->model_claim_claim->setViewedClaim($this->request->get['claim_id']);

        $customer_info = $this->model_customer_customer->getCustomer($claim_info['customer_id']);
        $defendant_info = $this->model_customer_customer->getCustomer($claim_info['defendant_id']);
        $data['title'] = sprintf($this->language->get('text_claim'), $claim_info['claim_id']);
        $data['claim_id'] = $claim_info['claim_id'];
        $data['claim'] = [
            'claim_id' => $claim_info['claim_id'],
            'object_title' => $object_title,
            'object_href' => $object_href,
            'object_status' => $object_status ?? false,
            'customer_login' => $customer_info['login'],
            'customer_href' => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $customer_info['customer_id'],
            'defendant_login' => $defendant_info['login'],
            'defendant_href' => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $defendant_info['customer_id'],
            'comment' => $claim_info['comment'],
            'status' => $claim_info['status'],
            'date_added' => format_date($claim_info['date_added'], 'd.m.Y'),
            'href' => $this->url->link('claim/claim/info', 'user_token=' . $this->session->data['user_token'] .  '&claim_id=' . $claim_info['claim_id']),
            'close' => htmlspecialchars_decode($this->url->link('claim/claim/closeClaim', 'user_token=' . $this->session->data['user_token'] .  '&claim_id=' . $claim_info['claim_id'])),
            'order_close' => htmlspecialchars_decode($this->url->link('claim/claim/closeOrder', 'user_token=' . $this->session->data['user_token'] .  '&claim_id=' . $claim_info['claim_id'])),
        ];

        $attachments = $this->model_claim_claim->getClaimAttachment($claim_info['claim_id']);

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
                    'upload'        => HTTP_CATALOG . 'index.php?route=common/download&attachment_id=' . $attachment['attachment_id'],
                ];
            }
        }

        $data['user_token'] = $this->request->get['user_token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('claim/claim_view', $data));
    }

    public function closeClaim()
    {
        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('customer/customer');

        if (isset($this->request->get['claim_id']) && $this->validateClose()) {
            $this->model_claim_claim->closeClaim($this->request->get['claim_id']);

            $this->response->setOutput(json_encode(['success' => 'Успешно выполнено']));
        } else {
            $this->response->setOutput(json_encode(['error' => 'Произошла ошибка']));
        }

    }

    public function closeOrder()
    {
        $this->load->language('claim/claim');
        $this->load->model('claim/claim');
        $this->load->model('customer/customer');

        if (isset($this->request->get['claim_id']) && $this->validateClose()) {
            $this->model_claim_claim->closeOrder($this->request->get['claim_id']);

            $this->response->setOutput(json_encode(['success' => 'Успешно выполнено']));
        } else {
            $this->response->setOutput(json_encode(['error' => 'Произошла ошибка']));
        }

    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'claim/claim')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (utf8_strlen($this->request->post['text']) < 3) {
            $this->error['text'] = $this->language->get('error_text');
        }

        return !$this->error;
    }

    protected function validateClose() {
        if (!$this->user->hasPermission('modify', 'claim/claim')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
