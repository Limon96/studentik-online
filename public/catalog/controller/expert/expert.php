<?php

class ControllerExpertExpert extends Controller
{

    public function index()
    {
        $this->load->language('expert/expert');

        if (isset($this->request->get['filter_section_id'])) {
            $filter_section_id = $this->request->get['filter_section_id'];
        } else {
            $filter_section_id = 0;
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $filter_subject_id = $this->request->get['filter_subject_id'];
        } else {
            $filter_subject_id = 0;
        }

        if (isset($this->request->get['filter_online'])) {
            $filter_online = $this->request->get['filter_online'];
        } else {
            $filter_online = 0;
        }

        if (isset($this->request->get['filter_mutual'])) {
            $filter_mutual = $this->request->get['filter_mutual'];
        } else {
            $filter_mutual = 0;
        }

        if (isset($this->request->get['filter_pro'])) {
            $filter_pro = $this->request->get['filter_pro'];
        } else {
            $filter_pro = 0;
        }

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if ($page > 1) {
            $this->document->setTitle('Рейтинг исполнителей студенческих работ — страница ' . $page);
            $this->document->setDescription('Лучшие авторы студенческих работ биржи ' . $this->config->get('config_name') . ': помощь студентам с курсовыми, контрольными и другими работами срочно и недорого. Страница ' . $page . '.');

        } else {
            $this->document->setTitle('Рейтинг исполнителей студенческих работ');
            $this->document->setDescription('Лучшие авторы студенческих работ биржи ' . $this->config->get('config_name') . ': помощь студентам с курсовыми, контрольными и другими работами срочно и недорого.');
        }

        $this->document->addLink($this->url->link('expert/expert'), 'canonical');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $url = '';

        if ($filter_section_id) {
            $url .= "&filter_section_id=" . $filter_section_id;
        }

        if ($filter_subject_id) {
            $url .= "&filter_subject_id=" . $filter_subject_id;
        }

        if ($filter_online) {
            $url .= "&filter_online=" . $filter_online;
        }

        if ($filter_mutual) {
            $url .= "&filter_mutual=" . $filter_mutual;
        }

        if ($filter_pro) {
            $url .= "&filter_pro=" . $filter_pro;
        }

        if ($search) {
            $url .= "&search=" . $search;
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_expert'),
            'href' => $this->url->link('expert/expert', $url, true)
        );

        $data['is_logged'] = $this->customer->isLogged();

        $this->load->model('order/section');
        $data['sections'] = $this->model_order_section->getSections();

        $this->load->model('order/work_type');
        $data['work_types'] = $this->model_order_work_type->getWorkTypes();

        if ($filter_section_id) {
            $this->load->model('order/subject');
            $data['subjects'] = $this->model_order_subject->getSubjects($filter_section_id);
        } else {
            $data['subjects'] = array();
        }

        $url = '';

        if ($filter_section_id) {
            $url .= "&filter_section_id=" . $filter_section_id;
        }

        if ($filter_subject_id) {
            $url .= "&filter_subject_id=" . $filter_subject_id;
        }

        if ($search) {
            $url .= "&search=" . $search;
        }

        $this->load->model('account/customer');

        $limit = 30;

        $filter_data = [
            'filter_section_id' => $filter_section_id,
            'filter_subject_id' => $filter_subject_id,
            'filter_customer_group_id' => $this->config->get('module_top_customer_customer_group_id'),
            'filter_online' => $filter_online,
            'filter_mutual' => $filter_mutual,
            'filter_pro' => $filter_pro,
            'search' => $search,
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
            'sort' => 'rating',
            'order' => 'DESC'
        ];

        $total_experts = $this->model_account_customer->getTotalCustomers($filter_data);
        $data['text_total'] = sprintf($this->language->get('text_total'), $total_experts);

        $data['experts'] = array();

        $experts = $this->model_account_customer->getCustomers($filter_data);

        if ($experts) {

            $this->load->model('tool/image');

            foreach ($experts as $customer) {
                if ($customer['image']) {
                    $image = $this->model_tool_image->resize($customer['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $data['experts'][] = [
                    "customer_id" => $customer['customer_id'],
                    "firstname" => $customer['firstname'],
                    "login" => $customer['login'],
                    "rating" => $customer['rating'],
                    "new_rating" => $customer['new_rating'],
                    "pro" => $customer['pro'],
                    "online" => $customer['online'],
                    "total_orders" => num_word($customer['total_orders'], ['Выполнена', 'Выполнены', 'Выполнено'], false) . ' <span>' . $customer['total_orders'] . '</span> ' . num_word($customer['total_orders'], ['работа', 'работы', 'работ'], false),
                    "total_reviews" => $customer['total_reviews'],
                    "total_reviews_positive" => $customer['total_reviews_positive'],
                    "percent_reviews_positive" => ($customer['total_reviews'] ? floor($customer['total_reviews_positive'] / $customer['total_reviews'] * 100) : 0 ),
                    "total_reviews_negative" => $customer['total_reviews_negative'],
                    "percent_reviews_negative" => ($customer['total_reviews'] ? 100 - floor($customer['total_reviews_positive'] / $customer['total_reviews'] * 100) : 100 ),
                    "image" => $image,
                    "date_added" => $customer['date_added'],
                    "href" => $this->url->link('account/customer', 'customer_id=' . $customer['customer_id'] . $url, true),
                ];
            }
        }

        $data['limit'] = $limit * $page;
        $data['total_experts'] = $total_experts;

        $data['continue'] = $this->url->link('expert/expert', 'page=' . ($page + 1));

        $data['filter_section_id'] = $filter_section_id;
        $data['filter_subject_id'] = $filter_subject_id;
        $data['search'] = $search;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('expert/expert', $data));
    }
}
