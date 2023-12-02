<?php
class ControllerAccountCustomer extends Controller
{

    const AVATAR_SIZE = 300;
    public function index()
    {
        $this->load->language('account/customer');

        if (isset($this->request->get['customer_id'])) {
            $customer_id = (int)$this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        if (isset($this->session->data['success'])) {
            $data['alert_success'] = $this->session->data['success'];
        } else {
            $data['alert_success'] = '';
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

            $this->load->model('tool/image');

            if ($customer_info['image']) {
                $data['image'] = $this->model_tool_image->resize($customer_info['image'], self::AVATAR_SIZE, self::AVATAR_SIZE);
            } else {
                $data['image'] = $this->model_tool_image->resize('profile.png', self::AVATAR_SIZE, self::AVATAR_SIZE);
            }

            $this->load->model('tool/online');
            $data['text_online'] = $this->model_tool_online->format($customer_info['last_seen']);

            $data['customer_id'] = $customer_id;
            $data['is_owner'] = $customer_id == $this->customer->getId();
            $data['is_admin'] = $this->customer->isAdmin();
            $data['login'] = $customer_info['login'];
            $data['online'] = $customer_info['online'];
            $data['gender'] = $customer_info['gender'];
            $data['note'] = $customer_info['note'];
            $data['customer_group_id'] = $customer_info['customer_group_id'];
            $data['bdate'] = $customer_info['bdate'];
            $data['country'] = $customer_info['country'];
            $data['languages'] = $customer_info['languages'];
            //$data['comment'] = nl2br(str_replace("\n\r", " ", $customer_info['comment']));
            $data['comment'] = str_replace("\n\r", "<br><br>", $customer_info['comment']);
            //dd(explode("\n\r", $customer_info['comment']));

            $data['email'] = $customer_info['email'];
            $data['telephone'] = $customer_info['telephone'];
            $data['firstname'] = $customer_info['firstname'];
            $data['rating'] = $customer_info['rating'];
            $data['new_rating'] = $customer_info['new_rating'];
            $data['total_orders'] = $customer_info['total_orders'];
            $data['total_reviews'] = $customer_info['total_reviews'];
            $data['total_reviews_positive'] = $customer_info['total_reviews_positive'];
            $data['total_reviews_negative'] = $customer_info['total_reviews_negative'];
            $data['last_seen'] = $this->model_tool_online->format($customer_info['last_seen']);
            $data['date_added'] = format_date($customer_info['date_added'], 'd.m.Y');
            $data['message'] = $this->url->link('message/chat', 'chat_id=' . $customer_info['customer_id'], true);

            $white_timezones = [
                'Europe/Kaliningrad' => 'Калининград (МСК -1)',
                'Europe/Moscow'      => 'Москва',
                'Europe/Samara'      => 'Самара (МСК +1)',
                'Asia/Yekaterinburg' => 'Екатеринбург (МСК +2)',
                'Asia/Omsk'          => 'Омск (МСК +3)',
                'Asia/Krasnoyarsk'   => 'Красноярск (МСК +4)',
                'Asia/Irkutsk'       => 'Иркутск (МСК +5)',
                'Asia/Yakutsk'       => 'Якутск (МСК +6)',
                'Asia/Vladivostok'   => 'Владивосток (МСК +7)',
                'Asia/Magadan'       => 'Магадан (МСК +8)',
                'Asia/Kamchatka'     => 'Камчатка (МСК +9)',
            ];

            if (isset($customer_info['timezone']) && isset($white_timezones[$customer_info['timezone']])) {
                $data['timezone'] = $white_timezones[$customer_info['timezone']];
            } else {
                $data['timezone'] = $white_timezones[$this->config->get('config_timezone')];
            }

            $data['edit'] = $this->url->link('account/edit');
            $data['specialization'] = $this->url->link('account/specialization');
            $data['review'] = $this->url->link('account/review', 'customer_id=' . $customer_id);

            $data['sections'] = array();
            $sections = $this->model_account_customer->getCustomerSections($customer_id);
            foreach ($sections as $section) {
                $subjects = $this->model_account_customer->getCustomerSubjects($customer_id, $section['section_id']);
                $data['sections'][] = [
                    'section_id' => $section['section_id'],
                    'name' => $section['section'],
                    'subjects' => $subjects
                ];
            }


            $attachments = $this->model_account_customer->getCustomerAttachment($customer_id);

            $data['attachments'] = array();
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $data['attachments'][] = [
                        'attachment_id' => $attachment['attachment_id'],
                        'type'          => $attachment['type'],
                        'name'          => $attachment['name'],
                        'size'          => format_size($attachment['size']),
                        'date_added'    => format_date($attachment['date_added'], 'full_datetime'),
                        'href'          => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                        'upload'        => $this->url->link('common/download', 'attachment_id=' . $attachment['attachment_id']),
                    ];
                }
            }

            $this->load->model('account/review');
            $this->load->model('tool/image');

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'start' => 0,
                'limit' => 3
            ];


            $reviews = $this->model_account_review->getReviews($filter_data);

            $data['reviews'] = array();

            if ($reviews) {
                foreach ($reviews as $review) {
                    $customer_info = $this->model_account_customer->getCustomerInfo($review['customer_id']);

                    if ($customer_info['image']) {
                        $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 80, 80);
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

            $data['total_reviews'] = $this->model_account_review->getTotalReviews($filter_data);

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => 'positive',
                'start' => 0,
                'limit' => 3
            ];

            $data['total_reviews_positive'] = $this->model_account_review->getTotalReviews($filter_data);

            $filter_data = [
                'filter_customer_id' => $customer_id,
                'filter' => 'negative',
                'start' => 0,
                'limit' => 3
            ];

            $data['total_reviews_negative'] = $this->model_account_review->getTotalReviews($filter_data);

            /*if ($customer_info['customer_group_id'] == 2) {
                $data['text_rating'] = $this->language->get('text_rating_customer');
            } elseif ($customer_info['customer_group_id'] == 1) {
                $data['text_rating'] = $this->language->get('text_rating_author');
            }*/

            $data['text_rating'] = $this->language->get('text_rating');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('account/customer', $data));
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


    public function addAttachment()
    {
        $this->load->language('account/customer');
        $this->load->model('account/customer');
        $this->load->model('tool/attachment');

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_account_customer->addAttachment($attachment_id);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteAttachment()
    {
        $this->load->language('account/customer');
        $this->load->model('account/customer');
        $this->load->model('tool/attachment');

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_account_customer->deleteAttachment($attachment_id);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
