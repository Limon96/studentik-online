<?php
class ControllerOrderOrder extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('order/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/order');

        $this->getList();
    }

    public function add() {
        $this->load->language('order/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/order');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_order_order->addOrder($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_owner'])) {
                $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_offer'])) {
                $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_subject_id'])) {
                $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
            }

            if (isset($this->request->get['filter_section_id'])) {
                $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('order/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/order');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_order_order->editOrder($this->request->get['order_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_owner'])) {
                $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_offer'])) {
                $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_subject_id'])) {
                $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
            }

            if (isset($this->request->get['filter_section_id'])) {
                $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('order/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/order');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $order_id) {
                $this->model_order_order->deleteOrder($order_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_owner'])) {
                $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_offer'])) {
                $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_subject_id'])) {
                $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
            }

            if (isset($this->request->get['filter_section_id'])) {
                $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('order/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/order');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $order_id) {
                $this->model_order_order->copyOrder($order_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_owner'])) {
                $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_offer'])) {
                $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_subject_id'])) {
                $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
            }

            if (isset($this->request->get['filter_section_id'])) {
                $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = '';
        }

        if (isset($this->request->get['filter_title'])) {
            $filter_title = $this->request->get['filter_title'];
        } else {
            $filter_title = '';
        }

        if (isset($this->request->get['filter_owner'])) {
            $filter_owner = $this->request->get['filter_owner'];
        } else {
            $filter_owner = '';
        }

        if (isset($this->request->get['filter_offer'])) {
            $filter_offer = $this->request->get['filter_offer'];
        } else {
            $filter_offer = '';
        }

        if (isset($this->request->get['filter_section_id'])) {
            $filter_section_id = $this->request->get['filter_section_id'];
        } else {
            $filter_section_id = '';
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $filter_subject_id = $this->request->get['filter_subject_id'];
        } else {
            $filter_subject_id = '';
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = '';
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
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

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_owner'])) {
            $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_offer'])) {
            $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
        }

        if (isset($this->request->get['filter_section_id'])) {
            $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('order/order/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['copy'] = $this->url->link('order/order/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('order/order/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $this->load->model('order/order_status');

        $data['order_statuses'] = $this->model_order_order_status->getOrderStatuses();

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id'	        => $filter_order_id,
            'filter_title'	            => $filter_title,
            'filter_owner'	            => $filter_owner,
            'filter_offer'	            => $filter_offer,
            'filter_section_id'	        => $filter_section_id,
            'filter_subject_id'	        => $filter_subject_id,
            'filter_order_status_id'    => $filter_order_status_id,
            'filter_date_added'	        => $filter_date_added,
            'filter_date_end'           => $filter_date_end,
            'sort'                      => $sort,
            'order'                     => $order,
            'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                     => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');
        $this->load->model('customer/customer');

        $order_total = $this->model_order_order->getTotalOrders($filter_data);

        $results = $this->model_order_order->getOrders($filter_data);

        foreach ($results as $result) {

            $customer_data = $this->model_customer_customer->getCustomer($result['customer_id']);

            $order_customer = [
                "customer_id" => $customer_data['customer_id'],
                "login" => $customer_data['login'],
                'edit'           => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_data['customer_id'], true)
            ];

            $offer_customer = [];

            if ($result['order_status_id'] != $this->config->get('config_open_order_status_id') && $result['order_status_id'] != $this->config->get('config_cancel_order_status_id')) {
                $offer = $this->model_order_order->getAssignedOffer($result['order_id']);

                $customer_data = $this->model_customer_customer->getCustomer($offer['customer_id']);

                if ($customer_data) {
                    $offer_customer = [
                        "customer_id" => $customer_data['customer_id'],
                        "login" => $customer_data['login'],
                        'edit'           => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_data['customer_id'], true)
                    ];
                }
            }

            $data['orders'][] = array(
                'order_id'      => $result['order_id'],
                'title'         => $result['title'],
                'order_customer' => $order_customer,
                'offer_customer' => $offer_customer,
                'order_status'  => $result['order_status'],
                'date_added'    => format_date($result['date_added'], 'd.m.Y H:i'),
                'date_end'      => format_date($result['date_end'], 'd.m.Y'),
                'status'        => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'          => $this->url->link('order/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true),
                'view'          => HTTP_CATALOG . 'index.php?route=order/order/info&order_id=' . $result['order_id'],
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

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

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_owner'])) {
            $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_offer'])) {
            $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
        }

        if (isset($this->request->get['filter_section_id'])) {
            $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_status'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.status' . $url, true);
        $data['sort_title'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.title' . $url, true);
        $data['sort_order_status'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url, true);
        $data['sort_order'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url, true);
        $data['sort_date_added'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, true);
        $data['sort_date_end'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_end' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_owner'])) {
            $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_offer'])) {
            $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
        }

        if (isset($this->request->get['filter_section_id'])) {
            $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_title'] = $filter_title;
        $data['filter_owner'] = $filter_owner;
        $data['filter_offer'] = $filter_offer;
        $data['filter_subject_id'] = $filter_subject_id;
        $data['filter_section_id'] = $filter_section_id;
        $data['filter_order_status_id'] = $filter_order_status_id;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_end'] = $filter_date_end;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('order/order_list', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_owner'])) {
            $url .= '&filter_owner=' . urlencode(html_entity_decode($this->request->get['filter_owner'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_offer'])) {
            $url .= '&filter_offer=' . urlencode(html_entity_decode($this->request->get['filter_offer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $url .= '&filter_subject_id=' . $this->request->get['filter_subject_id'];
        }

        if (isset($this->request->get['filter_section_id'])) {
            $url .= '&filter_section_id=' . $this->request->get['filter_section_id'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['order_id'])) {
            $data['action'] = $this->url->link('order/order/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('order/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $order_info = $this->model_order_order->getOrder($this->request->get['order_id']);
        }

        $data['order_id'] = $this->request->get['order_id'] ?? 0;

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($order_info)) {
            $data['title'] = $order_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($order_info)) {
            $data['description'] = $order_info['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($order_info)) {
            $data['price'] = $order_info['price'];
        } else {
            $data['price'] = '';
        }

        $this->load->model('order/section');

        $data['sections'] = $this->model_order_section->getSections();

        if (isset($this->request->post['section_id'])) {
            $data['section_id'] = $this->request->post['section_id'];
        } elseif (!empty($order_info)) {
            $data['section_id'] = $order_info['section_id'];
        } else {
            $data['section_id'] = '';
        }

        $this->load->model('order/subject');

        $data['subjects'] = $this->model_order_subject->getSubjects();

        if (isset($this->request->post['subject_id'])) {
            $data['subject_id'] = $this->request->post['subject_id'];
        } elseif (!empty($order_info)) {
            $data['subject_id'] = $order_info['subject_id'];
        } else {
            $data['subject_id'] = '';
        }

        $this->load->model('order/work_type');

        $data['work_types'] = $this->model_order_work_type->getWorkTypes();

        if (isset($this->request->post['work_type_id'])) {
            $data['work_type_id'] = $this->request->post['work_type_id'];
        } elseif (!empty($order_info)) {
            $data['work_type_id'] = $order_info['work_type_id'];
        } else {
            $data['work_type_id'] = '';
        }

        $this->load->model('order/order_status');

        $data['order_statuses'] = $this->model_order_order_status->getOrderStatuses();

        if (isset($this->request->post['order_status_id'])) {
            $data['order_status_id'] = $this->request->post['order_status_id'];
        } elseif (!empty($order_info)) {
            $data['order_status_id'] = $order_info['order_status_id'];
        } else {
            $data['order_status_id'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($order_info)) {
            $data['status'] = $order_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['premium'])) {
            $data['premium'] = $this->request->post['premium'];
        } elseif (!empty($order_info)) {
            $data['premium'] = $order_info['premium'];
        } else {
            $data['premium'] = false;
        }

        if (isset($this->request->post['hot'])) {
            $data['hot'] = $this->request->post['hot'];
        } elseif (!empty($order_info)) {
            $data['hot'] = $order_info['hot'];
        } else {
            $data['hot'] = false;
        }

        if (isset($this->request->post['date_end'])) {
            $data['date_end'] = $this->request->post['date_end'];
        } elseif (!empty($order_info) && $order_info['date_end'] != '0000-00-00') {
            $data['date_end'] = $order_info['date_end'];
        } else {
            $data['date_end'] = '';
        }

        // Attachments

        if (isset($this->request->post['attachment'])) {
            $order_attachments = $this->request->post['attachment'];
        } elseif (isset($this->request->get['order_id'])) {
            $order_attachments = $this->model_order_order->getOrderAttachment($this->request->get['order_id']);
        } else {
            $order_attachments = array();
        }

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

        if (isset($this->request->post['offer_attachments'])) {
            $offer_attachments = $this->request->post['offer_attachments'];
        } elseif (isset($this->request->get['order_id'])) {
            $offer_attachments = $this->model_order_order->getOrderOfferAttachments($this->request->get['order_id']);
        } else {
            $offer_attachments = array();
        }

        $data['offer_attachments'] = array();

        if ($offer_attachments) {
            foreach ($offer_attachments as $attachment) {
                $data['offer_attachments'][] = [
                    'order_offer_attachment_id' => $attachment['order_offer_attachment_id'],
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

        $this->load->model('finance/blocked_cash');
        $blocked_cash = $this->model_finance_blocked_cash->getBlockedCashByOrder($this->request->get['order_id']);

        $data['blocked_cash'] = array();

        if ($blocked_cash) {

            $cash = $blocked_cash['balance'] / (1 + (int)$this->config->get('config_commission_customer') / 100) * (1 - (int)$this->config->get('config_commission') / 100);

            $data['blocked_cash'] = [
                'customer_blocked_cash_id'  => $blocked_cash['customer_blocked_cash_id'],
                'order_title'               => $blocked_cash['order_title'],
                'customer_login'            => $blocked_cash['customer_login'],
                'offer_customer_login'      => $blocked_cash['offer_customer_login'],
                'balance'                   => $this->currency->format($blocked_cash['balance'], $this->config->get('config_currency')),
                'cash'                      => $this->currency->format($cash, $this->config->get('config_currency')),
                'date_added'                => format_date($blocked_cash['date_added'], 'd.m.Y'),
                'date_end'                  => format_date($blocked_cash['date_end'], 'd.m.Y'),
                'return'                    => $this->url->link('finance/blocked_cash/return', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $blocked_cash['customer_blocked_cash_id'] . $url, true),
                'pay'                       => $this->url->link('finance/blocked_cash/pay', 'user_token=' . $this->session->data['user_token'] . '&customer_blocked_cash_id=' . $blocked_cash['customer_blocked_cash_id'] . $url, true)
            ];
        }


        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('order/order_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'order/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['title']) < 1) || (utf8_strlen($this->request->post['title']) > 255)) {
            $this->error['title'] = $this->language->get('error_name');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'order/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'order/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_subject_id'])) {
            $this->load->model('order/order');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_title'])) {
                $filter_title = $this->request->get['filter_title'];
            } else {
                $filter_title = '';
            }

            if (isset($this->request->get['filter_subject_id'])) {
                $filter_subject_id = $this->request->get['filter_subject_id'];
            } else {
                $filter_subject_id = 0;
            }

            if (isset($this->request->get['limit'])) {
                $limit = (int)$this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_title'  => $filter_title,
                'filter_subject_id' => $filter_subject_id,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_order_order->getOrders($filter_data);

            foreach ($results as $result) {
                $option_data = array();

                $product_options = $this->model_order_order->getOrderOptions($result['order_id']);

                foreach ($product_options as $product_option) {
                    $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                    if ($option_info) {
                        $product_option_value_data = array();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

                            if ($option_value_info) {
                                $product_option_value_data[] = array(
                                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                                    'option_value_id'         => $product_option_value['option_value_id'],
                                    'name'                    => $option_value_info['name'],
                                    'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                    'price_prefix'            => $product_option_value['price_prefix']
                                );
                            }
                        }

                        $option_data[] = array(
                            'product_option_id'    => $product_option['product_option_id'],
                            'product_option_value' => $product_option_value_data,
                            'option_id'            => $product_option['option_id'],
                            'name'                 => $option_info['name'],
                            'type'                 => $option_info['type'],
                            'value'                => $product_option['value'],
                            'required'             => $product_option['required']
                        );
                    }
                }

                $json[] = array(
                    'order_id' => $result['order_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'option'     => $option_data,
                    'price'      => $result['price']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteOfferAttachment()
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

        $offer_attachment_info = $this->model_order_order->getOrderOfferAttachment($attachment_id);
        $attachment_info = $this->model_tool_attachment->getAttachment($offer_attachment_info['attachment_id']);

        $json = array();

        if ($order_info && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->deleteOfferAttachment($attachment_id);

            if ($order_info['order_status_id'] == $this->config->get('config_complete_order_status_id')) {
                $this->model_order_order->setOrderStatus($order_info['order_id'], $this->config->get('config_revision_order_status_id'));
            } elseif ($order_info['order_status_id'] == $this->config->get('config_awaiting_order_status_id') || $order_info['order_status_id'] == $this->config->get('config_verification_order_status_id')) {
                $this->model_order_order->setOrderStatus($order_info['order_id'], $this->config->get('config_progress_order_status_id'));
            }

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_remove_file'), "Администратор", '', $attachment_info['name'])
            ));

            $json['success'] = 'Файл успешно удален';
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
