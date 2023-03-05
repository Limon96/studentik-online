<?php
class ControllerAccountReview extends Controller
{
    public function index()
    {
        $this->load->language('account/review');

        if (isset($this->request->get['customer_id'])) {
            $customer_id = (int)$this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = 'all';
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = 5;
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomerInfo($customer_id);

        if ($customer_info && $customer_id > 0) {
            $this->document->setTitle($customer_info['login'] . ' - ' . $this->language->get('heading_title'));

            $data['breadcrumbs'][] = array(
                'text' => $customer_info['login'],
                'href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'], true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_reviews'),
                'href' => $this->url->link('account/review', 'customer_id=' . $customer_info['customer_id'], true)
            );

            $this->load->model('account/review');
            $this->load->model('tool/image');

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => $filter,
                'start' => $limit * ($page - 1),
                'limit' => $limit
            ];

            $reviews = $this->model_account_review->getReviews($filter_data);
            $total_reviews = $this->model_account_review->getTotalReviews($filter_data);

            $data['total_reviews_all'] = $this->model_account_review->getTotalReviews($filter_data);

            $data['reviews'] = array();
            if ($reviews) {
                foreach ($reviews as $review) {
                    $customer_info = $this->model_account_customer->getCustomerInfo($review['customer_id']);

                    if ($customer_info['image']) {
                        $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                    } else {
                        $image = $this->model_tool_image->resize('profile.png', 80, 80);
                    }

                    $data['reviews'][] = [
                        'review_id' => $review['review_id'],
                        'order_id' => $review['order_id'],
                        'order_title' => $review['order_title'],
                        'order_href' => $this->url->link('order/order/info', 'order_id=' . $review['order_id'], true),
                        "login" => $customer_info['login'],
                        "online" => $customer_info['online'],
                        "image" => $image,
                        "href" => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'], true),
                        "text" => $review['text'],
                        "positive" => $review['positive'],
                        "date_added" => format_date($review['date_added'])
                    ];
                }
            }

            $url = '';

            if (isset($this->request->get['customer_id'])) {
                $url .= '&customer_id=' . (int)$this->request->get['customer_id'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . (int)$this->request->get['limit'];
            }

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => 'all'
            ];
            $data['total_reviews_all'] = $this->model_account_review->getTotalReviews($filter_data);
            $data['reviews_all'] = $this->url->link('account/review', $url);

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => 'negative'
            ];
            $data['total_reviews_negative'] = $this->model_account_review->getTotalReviews($filter_data);
            $data['reviews_negative'] = $this->url->link('account/review', 'filter=negative' . $url);

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => 'positive'
            ];
            $data['total_reviews_positive'] = $this->model_account_review->getTotalReviews($filter_data);
            $data['reviews_positive'] = $this->url->link('account/review', 'filter=positive' . $url);

            $url = '';

            if (isset($this->request->get['customer_id'])) {
                $url .= '&customer_id=' . (int)$this->request->get['customer_id'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . (int)$this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . (int)$this->request->get['limit'];
            }

            if ($total_reviews > ($page * $limit)) {
                $data['next'] = $this->url->link('account/review', 'page=' . ($page + 1) . $url);
            } else {
                $data['next'] = '';
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('account/review', $data));
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('error/not_found')
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function add()
    {
        $this->load->language('account/review');
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $this->document->addScript('catalog/assets/js/review.js', 'footer');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['customer_id'])) {
            $customer_id = (int)$this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        if ($order_info) {
            $data['order'] = [
                'order_id' => $order_info['order_id'],
                'title' => $order_info['title'],
                'href' => $this->url->link('order/order/info', 'order_id=' . $order_info['order_id'])
            ];
        } else {
            $data['order'] = [];
        }

        $data['customer_group_id'] = $this->customer->getId();

        $customer_info = $this->model_account_customer->getCustomerInfo($customer_id);

        if ($customer_info) {
            $this->document->setTitle($customer_info['login'] . ' - ' . $this->language->get('heading_title'));

            $data['breadcrumbs'][] = array(
                'text' => $customer_info['login'],
                'href' => $this->url->link('account/review', 'customer_id=' . $customer_info['customer_id'], true)
            );

            $data['customer'] = [
                'customer_id' => $customer_info['customer_id'],
                'login' => $customer_info['login'],
                'online' => $customer_info['online'],
                'href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'], true)
            ];

            if ($customer_info['image']) {
                $data['customer']['image'] = $this->model_tool_image->resize($customer_info['image'], 80, 80);
            } else {
                $data['customer']['image'] = $this->model_tool_image->resize('profile.png', 80, 80);
            }


            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('account/review_add', $data));

        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('error/not_found')
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function create()
    {
        $this->load->language('account/review');
        $this->load->model('account/customer');
        $this->load->model('account/review');

        $json = [];

        if (!$this->customer->isLogged()) {
            $json['error_warning'] = $this->language->get('error_auth');
        }

        if (!isset($this->request->post['positive'])) {
            $json['error_positive'] = $this->language->get('error_positive');
        }

        if (!isset($this->request->post['text']) || strlen($this->request->post['text']) < 20) {
            $json['error_text'] = $this->language->get('error_text');
        }

        if (!$json) {
            $this->model_account_review->addReview([
                'customer_id' => $this->request->post['customer_id'],
                'order_id' => $this->request->post['order_id'] ?? 0,
                'positive' => $this->request->post['positive'],
                'text' => $this->request->post['text'],
                'time' => (isset($this->request->post['time']) ? $this->request->post['time']: 0),
            ]);

            $json['success'] = $this->language->get('text_success');
            $this->session->data['success'] = $this->language->get('text_success');
            if ($this->request->post['order_id']) {
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('order/order/info', 'order_id=' . $this->request->post['order_id']));
            } else {
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('account/customer', 'customer_id=' . $this->request->post['customer_id']));
            }
        }

        $this->response->setOutput(json_encode($json));
    }
}
