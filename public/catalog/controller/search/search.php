<?php
class ControllerSearchSearch extends Controller {
    public function index() {

        $this->load->language('search/search');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('search/search', '', true)
        );

        if (isset($this->request->get['search_customer'])) {
            $search_customer = $this->request->get['search_customer'];
        } else {
            $search_customer = 1;
        }

        if (isset($this->request->get['search_order'])) {
            $search_order = $this->request->get['search_order'];
        } else {
            $search_order = 1;
        }

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];

            $url = '';

            if ($search_customer) {
                $url .= '&search_customer=' . $search_customer;
            }

            if ($search_order) {
                $url .= '&search_order=' . $search_order;
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_search') . ' ' . $search,
                'href' => $this->url->link('search/search', 'search=' . $search . $url, true)
            );
        } else {
            $search = '';
        }

        $data['results'] = array();

        if ($search) {
            $this->load->model('search/search');
            $this->load->model('tool/image');

            $filter_data = [
                'search_customer' => $search_customer,
                'search_order' => $search_order,
                'search' => $search,
            ];

            $data['results'] = array();

            $results = $this->model_search_search->search($filter_data);

            if ($results) {
                foreach ($results as $result) {
                    if ($result['type'] == 'customer') {
                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], 80, 80);
                        } else {
                            $image = $this->model_tool_image->resize('profile.png', 80, 80);
                        }

                        $data['results'][] = [
                            'title' => $result['title'],
                            'type' => $result['type'],
                            'online' => $result['online'],
                            'image' => $image,
                            'href' => $this->url->link('account/customer', 'customer_id=' . $result['id']),
                        ];
                    } elseif ($result['type'] == 'order') {
                        $data['results'][] = [
                            'title' => $result['title'],
                            'type' => $result['type'],
                            'description' => $result['description'],
                            'section' => $result['section'],
                            'subject' => $result['subject'],
                            'work_type' => $result['work_type'],
                            'href' => $this->url->link('order/order/info', 'order_id=' . $result['id']),
                        ];
                    }
                }
            }
        }

        $data['search'] = $search;
        $data['search_customer'] = $search_customer;
        $data['search_order'] = $search_order;
        $data['action'] = $this->url->link('search/search');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('search/search', $data));
    }
}
