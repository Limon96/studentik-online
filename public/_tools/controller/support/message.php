<?php
class ControllerSupportMessage extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('support/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('support/message');

        $this->getList();
    }

    public function delete() {
        $this->load->language('support/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('support/message');

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

            $this->response->redirect($this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url, true));
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
            'href' => $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['delete'] = $this->url->link('support/message/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['messages'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $message_total = $this->model_support_message->getTotalMessages();

        $results = $this->model_support_message->getMessages($filter_data);

        foreach ($results as $result) {
            $data['messages'][] = array(
                'message_id'            => $result['message_id'],
                'name'                  => $result['name'],
                'email'                 => $result['email'],
                'telephone'             => $result['telephone'],
                'date_added'            => $result['date_added'],
                'viewed'                => $result['viewed'],
                'view'                  => $this->url->link('support/message/view', 'user_token=' . $this->session->data['user_token'] . '&message_id=' . $result['message_id'] . $url, true)
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

        $data['sort_viewed'] = $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . '&sort=viewed' . $url, true);
        $data['sort_date_added'] = $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);

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
        $pagination->total = $message_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($message_total - $this->config->get('config_limit_admin'))) ? $message_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $message_total, ceil($message_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('support/message_list', $data));
    }

    public function view() {

        $this->load->language('support/message');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('support/message');

        $data['text_form'] = !isset($this->request->get['message_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
            'href' => $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['message_id'])) {
            $data['action'] = $this->url->link('support/message/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('support/message/edit', 'user_token=' . $this->session->data['user_token'] . '&message_id=' . $this->request->get['message_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['message_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $message_info = $this->model_support_message->getMessage($this->request->get['message_id']);
            $this->model_support_message->viewedMessage($this->request->get['message_id']);
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

            $this->response->redirect($this->url->link('support/message', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }


        if (!empty($message_info)) {
            $data['name'] = $message_info['name'];
        } else {
            $data['name'] = '';
        }

        if (!empty($message_info)) {
            $data['email'] = $message_info['email'];
        } else {
            $data['email'] = '';
        }

        if (!empty($message_info)) {
            $data['telephone'] = $message_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (!empty($message_info)) {
            $data['text'] = $message_info['text'];
        } else {
            $data['text'] = '';
        }

        if (!empty($message_info)) {
            $data['viewed'] = $message_info['viewed'];
        } else {
            $data['viewed'] = '';
        }

        if (!empty($message_info)) {
            $data['date_added'] = $message_info['date_added'];
        } else {
            $data['date_added'] = '';
        }

        if (!empty($message_info)) {
            $data['utm_source'] = $message_info['utm_source'];
        } else {
            $data['utm_source'] = '';
        }

        if (!empty($message_info)) {
            $data['utm_medium'] = $message_info['utm_medium'];
        } else {
            $data['utm_medium'] = '';
        }

        if (!empty($message_info)) {
            $data['utm_campaign'] = $message_info['utm_campaign'];
        } else {
            $data['utm_campaign'] = '';
        }

        if (!empty($message_info)) {
            $data['utm_content'] = $message_info['utm_content'];
        } else {
            $data['utm_content'] = '';
        }

        if (!empty($message_info)) {
            $data['utm_term'] = $message_info['utm_term'];
        } else {
            $data['utm_term'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('support/message_view', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'support/message')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (utf8_strlen($this->request->post['text']) < 3) {
            $this->error['text'] = $this->language->get('error_text');
        }

        return !$this->error;
    }
}
