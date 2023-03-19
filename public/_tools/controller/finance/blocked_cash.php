<?php
class ControllerFinanceBlockedCash extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('finance/blocked_cash');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('finance/blocked_cash');

        $this->getList();
    }

    public function add() {
        $this->load->language('finance/blocked_cash');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('finance/blocked_cash');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_finance_blocked_cash->addCountry($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

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
        $this->load->language('finance/blocked_cash');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('finance/blocked_cash');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $customer_blocked_cash_id) {
                $this->model_finance_blocked_cash->deleteBlockedCash($customer_blocked_cash_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

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

        $data['add'] = $this->url->link('finance/blocked_cash/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('finance/blocked_cash/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['blocked_cashs'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $country_total = $this->model_finance_blocked_cash->getTotalBlockedCashs();

        $results = $this->model_finance_blocked_cash->getBlockedCashs($filter_data);

        foreach ($results as $result) {
            $data['blocked_cashs'][] = array(
                'customer_blocked_cash_id'  => $result['customer_blocked_cash_id'],
                'order_title'               => $result['order_title'],
                'customer_login'            => $result['customer_login'],
                'offer_customer_login'      => $result['offer_customer_login'],
                'balance'                   => $this->currency->format($result['balance'], $this->config->get('config_currency')),
                'date_added'                => format_date($result['date_added'], 'd.m.Y'),
                'date_end'                  => format_date($result['date_end'], 'd.m.Y H:i'),
                'return'                      => $this->url->link('finance/blocked_cash/return', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $result['customer_blocked_cash_id'] . $url, true),
                'pay'                      => $this->url->link('finance/blocked_cash/pay', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $result['customer_blocked_cash_id'] . $url, true)
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

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $country_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('finance/blocked_cash', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($country_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($country_total - $this->config->get('config_limit_admin'))) ? $country_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $country_total, ceil($country_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('finance/blocked_cash_list', $data));
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
        if (!$this->user->hasPermission('modify', 'finance/blocked_cash')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'finance/blocked_cash')) {
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

}