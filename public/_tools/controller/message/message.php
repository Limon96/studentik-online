<?php
class ControllerMessageMessage extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('message/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('message/message');

        $this->getList();
    }

    public function add() {
        $this->load->language('message/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('message/message');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_finance_blocked_cash->addCountry($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['filter_sender'])) {
                $url .= '&filter_sender=' . $this->request->get['filter_sender'];
            }

            if (isset($this->request->get['filter_sender_id'])) {
                $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
            }

            if (isset($this->request->get['filter_recipient'])) {
                $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
            }

            if (isset($this->request->get['filter_recipient_id'])) {
                $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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

            $this->response->redirect($this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('finance/blocked_cash');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('finance/blocked_cash');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_finance_blocked_cash->editCountry($this->request->get['country_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['filter_sender'])) {
                $url .= '&filter_sender=' . $this->request->get['filter_sender'];
            }

            if (isset($this->request->get['filter_sender_id'])) {
                $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
            }

            if (isset($this->request->get['filter_recipient'])) {
                $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
            }

            if (isset($this->request->get['filter_recipient_id'])) {
                $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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

            $this->response->redirect($this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('message/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('message/message');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $message_id) {
                $this->model_message_message->deleteMessage($message_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['filter_sender'])) {
                $url .= '&filter_sender=' . $this->request->get['filter_sender'];
            }

            if (isset($this->request->get['filter_sender_id'])) {
                $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
            }

            if (isset($this->request->get['filter_recipient'])) {
                $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
            }

            if (isset($this->request->get['filter_recipient_id'])) {
                $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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

            $this->response->redirect($this->url->link('message/message', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    public function return()
    {
        $this->load->language('finance/blocked_cash');

        $this->load->model('finance/blocked_cash');

        if (isset($this->request->get['customer_blocked_cash_id']) && $this->validateReturn()) {

            $this->model_finance_blocked_cash->returnBlockedCash($this->request->get['customer_blocked_cash_id']);

            $this->session->data['success'] = $this->language->get('text_success');

        } else {
            $this->session->data['error'] = $this->error['warning'];
        }

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_sender'])) {
            $url .= '&filter_sender=' . $this->request->get['filter_sender'];
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
        }

        if (isset($this->request->get['filter_recipient'])) {
            $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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

        $this->response->redirect($this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true));
    }

    public function pay()
    {
        $this->load->language('finance/blocked_cash');

        $this->load->model('finance/blocked_cash');

        if (isset($this->request->get['customer_blocked_cash_id']) && $this->validatePay()) {

            $this->model_finance_blocked_cash->payBlockedCash($this->request->get['customer_blocked_cash_id']);

            $this->session->data['success'] = $this->language->get('text_success');

        } else {
            $this->session->data['error'] = $this->error['warning'];
        }

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_sender'])) {
            $url .= '&filter_sender=' . $this->request->get['filter_sender'];
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
        }

        if (isset($this->request->get['filter_recipient'])) {
            $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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

        $this->response->redirect($this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true));
    }

    protected function getList() {
        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['filter_sender'])) {
            $filter_sender = $this->request->get['filter_sender'];
        } else {
            $filter_sender = '';
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $filter_sender_id = $this->request->get['filter_sender_id'];
        } else {
            $filter_sender_id = '';
        }

        if (isset($this->request->get['filter_recipient'])) {
            $filter_recipient = $this->request->get['filter_recipient'];
        } else {
            $filter_recipient = '';
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $filter_recipient_id = $this->request->get['filter_recipient_id'];
        } else {
            $filter_recipient_id = '';
        }

        if (isset($this->request->get['filter_viewed'])) {
            $filter_viewed = $this->request->get['filter_viewed'];
        } else {
            $filter_viewed = '';
        }

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
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_sender'])) {
            $url .= '&filter_sender=' . $this->request->get['filter_sender'];
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
        }

        if (isset($this->request->get['filter_recipient'])) {
            $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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
            'href' => $this->url->link('message/message', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('message/message/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('message/message/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['blocked_cashs'] = array();

        $filter_data = array(
            'search'  => $search,
            'filter_sender_id'  => $filter_sender_id,
            'filter_recipient_id'  => $filter_recipient_id,
            'filter_viewed'  => $filter_viewed,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $message_total = $this->model_message_message->getTotalMessages($filter_data);

        $results = $this->model_message_message->getMessages($filter_data);

        foreach ($results as $result) {

            // Attachments
            $attachments = $this->model_message_message->getAttachments($result['message_id']);

            $attachment_data = array();

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $attachment_data[] = [
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

            $sender = [];

            if ($result['sender']) {
                $sender = [
                    'login' => $result['sender']['login'],
                    'href' => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['sender']['customer_id']),
                ];
            }

            $recipient = [];

            if ($result['recipient']) {
                $recipient = [
                    'login' => $result['recipient']['login'],
                    'href' => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['recipient']['customer_id']),
                ];
            }

            $data['messages'][] = array(
                'message_id'                => $result['message_id'],
                'sender'                    => $sender,
                'recipient'                 => $recipient,
                'attachments'               => $attachment_data,
                'text'                      => nl2br($this->observeText($result['text'])),
                'viewed'                    => $result['viewed'],
                'date_added'                => format_date($result['date_added'], 'H:i d.m.Y'),
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

        $data['sort_date_added'] = $this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);
        $data['sort_date_end'] = $this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . '&sort=date_end' . $url, true);

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_sender'])) {
            $url .= '&filter_sender=' . $this->request->get['filter_sender'];
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
        }

        if (isset($this->request->get['filter_recipient'])) {
            $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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
        $pagination->total = $message_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('message/message', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($message_total - $this->config->get('config_limit_admin'))) ? $message_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $message_total, ceil($message_total / $this->config->get('config_limit_admin')));

        $data['search'] = $search;
        $data['filter_sender'] = $filter_sender;
        $data['filter_sender_id'] = $filter_sender_id;
        $data['filter_recipient'] = $filter_recipient;
        $data['filter_recipient_id'] = $filter_recipient_id;
        $data['filter_viewed'] = $filter_viewed;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('message/message', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['country_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_sender'])) {
            $url .= '&filter_sender=' . $this->request->get['filter_sender'];
        }

        if (isset($this->request->get['filter_sender_id'])) {
            $url .= '&filter_sender_id=' . $this->request->get['filter_sender_id'];
        }

        if (isset($this->request->get['filter_recipient'])) {
            $url .= '&filter_recipient=' . $this->request->get['filter_recipient'];
        }

        if (isset($this->request->get['filter_recipient_id'])) {
            $url .= '&filter_recipient_id=' . $this->request->get['filter_recipient_id'];
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
            'href' => $this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['country_id'])) {
            $data['action'] = $this->url->link('finance/blocked_cash/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('finance/blocked_cash/edit', 'user_token=' . $this->session->data['user_token'] . '&country_id=' . $this->request->get['country_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['country_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $country_info = $this->model_finance_blocked_cash->getCountry($this->request->get['country_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($country_info)) {
            $data['name'] = $country_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['iso_code_2'])) {
            $data['iso_code_2'] = $this->request->post['iso_code_2'];
        } elseif (!empty($country_info)) {
            $data['iso_code_2'] = $country_info['iso_code_2'];
        } else {
            $data['iso_code_2'] = '';
        }

        if (isset($this->request->post['iso_code_3'])) {
            $data['iso_code_3'] = $this->request->post['iso_code_3'];
        } elseif (!empty($country_info)) {
            $data['iso_code_3'] = $country_info['iso_code_3'];
        } else {
            $data['iso_code_3'] = '';
        }

        if (isset($this->request->post['address_format'])) {
            $data['address_format'] = $this->request->post['address_format'];
        } elseif (!empty($country_info)) {
            $data['address_format'] = $country_info['address_format'];
        } else {
            $data['address_format'] = '';
        }

        if (isset($this->request->post['postcode_required'])) {
            $data['postcode_required'] = $this->request->post['postcode_required'];
        } elseif (!empty($country_info)) {
            $data['postcode_required'] = $country_info['postcode_required'];
        } else {
            $data['postcode_required'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($country_info)) {
            $data['status'] = $country_info['status'];
        } else {
            $data['status'] = '1';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('finance/blocked_cash_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'message/message')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'message/message')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateReturn() {
        if (!$this->user->hasPermission('modify', 'finance/blocked_cash')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validatePay() {
        if (!$this->user->hasPermission('modify', 'finance/blocked_cash')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }



    private function observeText($text)
    {
        preg_match_all("~[a-z]+://\S+~", $text, $urls);

        if ($urls) {
            if ($urls[0]) {

                $search = [];
                $replace = [];
                foreach ($urls[0] as $item) {
                    if ($item) {
                        $str = '<a href="' . $item . '" target="_blank">' . $item . '</a>';
                        if (!in_array($item, $search)) {
                            $search[] = $item;
                            $replace[] = $str;
                        }
                    }
                }

                $text = str_replace($search, $replace, $text);
            }
        }

        return $text;
    }

}