<?php
class ControllerOrderWorkType extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('order/work_type');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/work_type');

        $this->getList();
    }

    public function add() {
        $this->load->language('order/work_type');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/work_type');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_order_work_type->addWorkType($this->request->post);

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

            $this->response->redirect($this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('order/work_type');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/work_type');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_order_work_type->editWorkType($this->request->get['work_type_id'], $this->request->post);

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

            $this->response->redirect($this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('order/work_type');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('order/work_type');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $work_type_id) {
                $this->model_order_work_type->deleteWorkType($work_type_id);
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

            $this->response->redirect($this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
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
            'href' => $this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('order/work_type/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('order/work_type/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['work_types'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $work_type_total = $this->model_order_work_type->getTotalWorkTypes();

        $results = $this->model_order_work_type->getWorkTypes($filter_data);

        foreach ($results as $result) {
            $data['work_types'][] = array(
                'work_type_id' => $result['work_type_id'],
                'name'            => $result['name'] . (($result['work_type_id'] == $this->config->get('config_work_type_id')) ? $this->language->get('text_default') : null),
                'edit'            => $this->url->link('order/work_type/edit', 'user_token=' . $this->session->data['user_token'] . '&work_type_id=' . $result['work_type_id'] . $url, true)
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

        $data['sort_name'] = $this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $work_type_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($work_type_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($work_type_total - $this->config->get('config_limit_admin'))) ? $work_type_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $work_type_total, ceil($work_type_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('order/work_type_list', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['work_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
            'href' => $this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['work_type_id'])) {
            $data['action'] = $this->url->link('order/work_type/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('order/work_type/edit', 'user_token=' . $this->session->data['user_token'] . '&work_type_id=' . $this->request->get['work_type_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('order/work_type', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['work_type'])) {
            $data['work_type'] = $this->request->post['work_type'];
        } elseif (isset($this->request->get['work_type_id'])) {
            $data['work_type'] = $this->model_order_work_type->getWorkTypeDescriptions($this->request->get['work_type_id']);
        } else {
            $data['work_type'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('order/work_type_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'order/work_type')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['work_type'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'order/work_type')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('order/order');

        foreach ($this->request->post['selected'] as $work_type_id) {

            $order_total = $this->model_order_order->getTotalOrdersByWorkTypeId($work_type_id);

            if ($order_total) {
                $this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
            }

        }

        return !$this->error;
    }
}
