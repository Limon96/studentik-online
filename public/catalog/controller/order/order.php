<?php

class ControllerOrderOrder extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('order/order');

        $this->document->setTitle('Лента заказов – Онлайн сервис студенческих работ ' . $this->config->get('config_name'));
        $this->document->setDescription('Все заказы по гуманитарным, экономическим, техническим и другим дисциплинам. Воспользуйтесь фильтром для поиска подходящего заказа.');

        $this->document->addScript('../catalog/assets/js/offer.js', 'footer');

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

        if (isset($this->request->get['filter_work_type_id'])) {
            $filter_work_type_id = $this->request->get['filter_work_type_id'];
        } else {
            $filter_work_type_id = 0;
        }

        if (isset($this->request->get['filter_no_offer'])) {
            $filter_no_offer = $this->request->get['filter_no_offer'];
        } else {
            $filter_no_offer = 0;
        }

        if (isset($this->request->get['filter_my_specialization'])) {
            $filter_my_specialization = $this->request->get['filter_my_specialization'];
        } else {
            $filter_my_specialization = 0;
        }

        if (isset($this->request->get['filter_my_work_type'])) {
            $filter_my_work_type = $this->request->get['filter_my_work_type'];
        } else {
            $filter_my_work_type = 0;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        $filter_order_status_id = $this->config->get('config_open_order_status_id');

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

        if ($filter_work_type_id) {
            $url .= "&filter_work_type_id=" . $filter_work_type_id;
        }

        if ($filter_no_offer) {
            $url .= "&filter_no_offer=" . $filter_no_offer;
        }

        if ($filter_my_specialization) {
            $url .= "&filter_my_specialization=" . $filter_my_specialization;
        }

        if ($filter_my_work_type) {
            $url .= "&filter_my_work_type=" . $filter_my_work_type;
        }

        if ($search) {
            $url .= "&search=" . $search;
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('order/order', $url, true)
        );

        $data['is_logged'] = $this->customer->isLogged();

        $this->load->model('order/section');
        $data['sections'] = $this->model_order_section->getSections();

        $this->load->model('order/work_type');
        $data['work_types'] = $this->model_order_work_type->getWorkTypes();

        if ($filter_section_id) {
            $this->load->model('order/subject');

            $data['subjects'] = $this->model_order_subject->getSubjects(array(
                "filter_section_id" => $filter_section_id
            ));
        } else {
            $data['subjects'] = false;
        }

        $url = '';

        if ($filter_section_id) {
            $url .= "&filter_section_id=" . $filter_section_id;
        }

        if ($filter_subject_id) {
            $url .= "&filter_subject_id=" . $filter_subject_id;
        }

        if ($filter_work_type_id) {
            $url .= "&filter_work_type_id=" . $filter_work_type_id;
        }

        if ($filter_no_offer) {
            $url .= "&filter_no_offer=" . $filter_no_offer;
        }

        if ($filter_my_specialization) {
            $url .= "&filter_my_specialization=" . $filter_my_specialization;
        }

        if ($filter_my_work_type) {
            $url .= "&filter_my_work_type=" . $filter_my_work_type;
        }

        if ($search) {
            $url .= "&search=" . $search;
        }

        $this->load->model('order/order');
        $this->load->model('account/customer');
        $this->load->model('tool/image');

        $limit = 20;

        $data['orders'] = array();

        $filter_data = [
            'filter_section_id' => $filter_section_id,
            'filter_subject_id' => $filter_subject_id,
            'filter_work_type_id' => $filter_work_type_id,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_no_offer' => $filter_no_offer,
            'filter_my_specialization' => $filter_my_specialization,
            'search' => $search,
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
            'sort' => 'premium',
            'order' => 'DESC'
        ];

        $order_total = $this->model_order_order->getTotalOrders($filter_data);


        $data['text_total'] = sprintf($this->language->get('text_total'), $order_total, num_word($order_total, ['заказ', 'заказа', 'заказов'], false));
        $orders = $this->model_order_order->getOrders($filter_data);

        if ($orders) {
            foreach ($orders as $order) {
                $customer_info = $this->model_account_customer->getCustomerInfo($order['customer_id']);

                if ($customer_info['image']) {
                    $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                if ($order['price'] > 0) {
                    $price = $this->currency->format($order['price'], $this->config->get('config_currency'));
                } else {
                    $price = $this->language->get('text_negotiable_price');
                }

                $data['orders'][] = [
                    "order_id" => $order['order_id'],
                    "title" => $order['title'],
                    "section" => $order['section'],
                    "subject" => $order['subject'],
                    "work_type" => $order['work_type'],
                    "date_added" => format_date($order['date_added'], 'full_datetime'),
                    "date_end" => ($order['date_end'] != '0000-00-00'? format_date($order['date_end'], 'full_date') : 'Не указан'),
                    "viewed" => num_word($order['viewed'], ['просмотр', 'просмотра', 'просмотров']),
                    "premium" => $order['premium'],
                    "hot" => $order['hot'],
                    "count_offer" =>  num_word($order['count_offer'], ['отклик', 'отклика', 'откликов']),
                    "exist_offer" => $order['exist_offer'],
                    "description" => htmlspecialchars_decode($order['description']),
                    "price" => $price,
                    "customer" => [
                        "login" => $customer_info['login'],
                        "online" => $customer_info['online'],
                        "image" => $image,
                        "href" => $this->url->link('account/customer', 'customer_id=' . $order['customer_id'] . $url, true)
                    ],
                    "href" => $this->url->link('order/order/info', 'order_id=' . $order['order_id'] . $url, true),
                ];
            }
        }

        $url = '';

        if ($filter_section_id) {
            $url .= "&filter_section_id=" . $filter_section_id;
        }

        if ($filter_subject_id) {
            $url .= "&filter_subject_id=" . $filter_subject_id;
        }

        if ($filter_work_type_id) {
            $url .= "&filter_work_type_id=" . $filter_work_type_id;
        }

        if ($filter_no_offer) {
            $url .= "&filter_no_offer=" . $filter_no_offer;
        }

        if ($filter_my_specialization) {
            $url .= "&filter_my_specialization=" . $filter_my_specialization;
        }

        if ($filter_my_work_type) {
            $url .= "&filter_my_work_type=" . $filter_my_work_type;
        }

        if ($search) {
            $url .= "&search=" . $search;
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('order/order', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($order_total - $limit)) ? $order_total : ((($page - 1) * $limit) + $limit), $order_total, ceil($order_total / $limit));

        // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
        /*if ($page == 1) {
            $this->document->addLink($this->url->link('order/order', 'canonical'));
        } else {
            $this->document->addLink($this->url->link('order/order', 'page='. $page), 'canonical');
        }

        if ($page > 1) {
            $this->document->addLink($this->url->link('order/order', (($page - 2) ? 'page='. ($page - 1) : '')), 'prev');
        }

        if ($limit && ceil($order_total / $limit) > $page) {
            $this->document->addLink($this->url->link('order/order', 'page='. ($page + 1)), 'next');
        }*/

        $data['filter_section_id'] = $filter_section_id;
        $data['filter_subject_id'] = $filter_subject_id;
        $data['filter_work_type_id'] = $filter_work_type_id;
        $data['filter_no_offer'] = $filter_no_offer;
        $data['filter_my_specialization'] = $filter_my_specialization;
        $data['filter_my_work_type'] = $filter_my_work_type;
        $data['search'] = $search;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('order/order', $data));
    }

    public function info()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('order/offer');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->session->data['success'])) {
            $data['alert_success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['alert_success'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $order_info = $this->model_order_order->getOrder($order_id);

        if ($order_info) {

            $this->document->setTitle($order_info['work_type'] . ' на тему ' . $order_info['title'] . ' - Заказ №' . $order_info['order_id']);
            $this->document->setDescription($order_info['work_type'] . ' по предмету ' . $order_info['subject'] . ' на тему ' . $order_info['title'] . ' - Заказ №' . $order_info['order_id']);

            $this->document->addScript('../catalog/assets/js/offer.js', 'footer');

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('order/order', '', true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $order_info['title'],
                'href' => ''
            );

            $data['is_admin'] = $this->customer->isAdmin();
            $data['is_logged'] = $this->customer->isLogged();

            $data['customer_group_id'] = $this->customer->getGroupId();

            $this->load->model('order/section');
            $data['sections'] = $this->model_order_section->getSections();

            $this->load->model('order/work_type');
            $data['work_types'] = $this->model_order_work_type->getWorkTypes();

            $data['config_open_order_status_id'] = $this->config->get('config_open_order_status_id');
            $data['config_pending_order_status_id'] = $this->config->get('config_pending_order_status_id');
            $data['config_progress_order_status_id'] = $this->config->get('config_progress_order_status_id');
            $data['config_awaiting_order_status_id'] = $this->config->get('config_awaiting_order_status_id');
            $data['config_verification_order_status_id'] = $this->config->get('config_verification_order_status_id');
            $data['config_revision_order_status_id'] = $this->config->get('config_revision_order_status_id');
            $data['config_complete_order_status_id'] = $this->config->get('config_complete_order_status_id');
            $data['config_canceled_order_status_id'] = $this->config->get('config_canceled_order_status_id');

            $data['order_id'] = $order_info['order_id'];
            $data['title'] = $order_info['title'];
            $data['description'] = nl2br(htmlspecialchars_decode($order_info['description']));
            $data['note'] = $order_info['note'];
            $data['section'] = $order_info['section'];
            $data['subject'] = $order_info['subject'];
            $data['work_type'] = $order_info['work_type'];
            $data['work_type_id'] = $order_info['work_type_id'];
            $data['date_added'] = format_date($order_info['date_added'], 'full_datetime');

            $data['date_end'] = ($order_info['date_end'] != '0000-00-00'? format_date($order_info['date_end'], 'full_date') : 'Не указан');
            $data['viewed'] = $order_info['viewed'];
            $data['order_status'] = $order_info['order_status'];
            $data['order_status_id'] = $order_info['order_status_id'];
            $data['is_owner'] = $order_info['is_owner'];
            $data['exist_offer'] = $order_info['exist_offer'];
            $data['payment_blocking'] = $order_info['payment_blocking'];
            $data['plagiarism_check'] = ($order_info['plagiarism_check_id'] > 0 ? $order_info['plagiarism_check'] : 'Не указан');
            $data['plagiarism'] = ($order_info['plagiarism'] ? json_decode($order_info['plagiarism']) : false);
            $data['commission'] = $this->config->get('config_commission');

            if ($order_info['order_status_id'] != $this->config->get('config_open_order_status_id')) {
                $offer_info = $this->model_order_offer->getOfferAssigned($order_info['order_id']);

                $data['offer_assigned_customer_id'] = $offer_info['customer_id'];
                $data['offer_assigned_is_owner'] = $offer_info['is_owner'];
            } else {
                $data['offer_assigned_customer_id'] = 0;
                $data['offer_assigned_is_owner'] = 0;
            }

            if ($order_info['price'] > 0) {
                $data['price'] = $this->currency->format($order_info['price'], $this->config->get('config_currency'));
            } else {
                $data['price'] = $this->language->get('text_negotiable_price');
            }

            $this->load->model('account/customer');
            $this->load->model('tool/image');
            $this->load->model('tool/online');

            $customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);
            if ($customer_info['image']) {
                $image = $this->model_tool_image->resize($customer_info['image'], 80, 80);
            } else {
                $image = $this->model_tool_image->resize('profile.png', 80, 80);
            }

            $data['customer'] = [
                'login' => $customer_info['login'],
                'online' => $customer_info['online'],
                'image' => $image,
                'last_seen' => $this->model_tool_online->format($customer_info['last_seen']),
                'chat' => $this->url->link('message/chat', 'chat_id=' . $customer_info['customer_id']),
                'href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'])
            ];

            $history = $this->model_order_order->getOrderHistory($order_id);

            $data['history'] = array();
            if ($history) {
                foreach ($history as $item) {
                    if ($item['customer_id']) {
                        $customer = $this->model_account_customer->getCustomerInfo($item['customer_id']);

                        if ($customer['image']) {
                            $image = $this->model_tool_image->resize($customer['image'], 80, 80);
                        } else {
                            $image = $this->model_tool_image->resize('profile.png', 80, 80);
                        }

                        $text = sprintf($item['text'], $this->load->view('tool/customer', [
                            'login' => $customer['login'],
                            'image' => $image,
                            'online' => $customer['online'],
                            'href' => $this->url->link('account/customer', 'customer_id=' . $customer['customer_id'], true)
                        ]));
                    } else {
                        $text = $item['text'];
                    }

                    $data['history'][] = [
                        'text' => $text,
                        'date_added' => format_date($item['date_added'], 'd.m.Y H:i')
                    ];
                }
            }

            $attachments = $this->model_order_order->getOrderAttachment($order_id);

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

            $offer_attachments = $this->model_order_order->getOrderOfferAttachments($order_id);

            $data['offer_attachments'] = array();
            if ($offer_attachments) {
                foreach ($offer_attachments as $attachment) {
                    $data['offer_attachments'][] = [
                        'attachment_id' => $attachment['order_offer_attachment_id'],
                        'type'          => $attachment['type'],
                        'name'          => $attachment['name'],
                        'size'          => format_size($attachment['size']),
                        'date_added'    => format_date($attachment['date_added'], 'full_datetime'),
                        'href'          => $this->url->link('order/download', 'attachment_id=' . $attachment['order_offer_attachment_id']),
                        'upload'        => $this->url->link('order/download', 'attachment_id=' . $attachment['order_offer_attachment_id']),
                    ];
                }
            }

            $this->load->model('order/offer');
            $offers = $this->model_order_offer->getOffers($order_id);

            $data['offers'] = array();
            if ($offers) {
                foreach ($offers as $offer) {
                    if ($offer['image']) {
                        $image = $this->model_tool_image->resize($offer['image'], 80, 80);
                    } else {
                        $image = $this->model_tool_image->resize('profile.png', 80, 80);
                    }

                    if ($offer['bet']) {
                        $bet = $this->currency->format($offer['bet'], $this->config->get('config_currency'));
                        $earned = $this->currency->format($offer['earned'], $this->config->get('config_currency'));
                    } else {
                        $bet = 'Без ставки';
                        $earned = false;
                    }

                    $data['offers'][] = [
                        'offer_id' => $offer['offer_id'],
                        'is_owner' => $offer['is_owner'],
                        'login' => $offer['login'],
                        'bet' => $bet,
                        'earned' => $earned,
                        'pro' => $offer['pro'],
                        'online' => $offer['online'],
                        'text' => $offer['text'],
                        'assigned' => $offer['assigned'],
                        'rating' => (isset($offer['rating']) ? $offer['rating']: 0),
                        'new_rating' => (isset($offer['new_rating']) ? $offer['new_rating']: 0),
                        'total_reviews_positive' => (isset($offer['total_reviews_positive']) ? $offer['total_reviews_positive']: 0),
                        'total_reviews_negative' => (isset($offer['total_reviews_negative']) ? $offer['total_reviews_negative']: 0),
                        'image' => $image,
                        'date_added' => format_date($offer['date_added'], 'd.m.Y в H:i'),
                        'chat' => $this->url->link('message/chat', 'chat_id=' . $offer['customer_id']),
                        'href' => $this->url->link('account/customer', 'customer_id=' . $offer['customer_id']),
                    ];
                }
            }

            $data['action_upload'] = $this->url->link('common/upload/upload', '', true);
            $data['edit'] = $this->url->link('order/order/edit', 'order_id=' . $order_info['order_id'], true);

            if ($order_info['is_owner']) {
                $data['review_add'] = $this->url->link('account/review/add', 'customer_id=' . $data['offer_assigned_customer_id'] .  '&order_id=' . $order_info['order_id'], true);
            } else {
                $data['review_add'] = $this->url->link('account/review/add', 'customer_id=' . $order_info['customer_id'] .  '&order_id=' . $order_info['order_id'], true);
            }
            //$data['review_add'] = $this->url->link('account/review/add', 'order_id=' . $order_info['order_id'], true);

            $data['claim'] = '';
            if ($data['is_owner']) {
                $data['claim'] = $this->url->link('claim/claim/add', 'type=order&object_id=' . $order_info['order_id'] . '&defendant_id=' . $data['offer_assigned_customer_id'], true);
            } elseif ($data['offer_assigned_is_owner']) {
                $data['claim'] = $this->url->link('claim/claim/add', 'type=order&object_id=' . $order_info['order_id'] . '&defendant_id=' . $order_info['customer_id'], true);
            }

            $this->load->model('account/review');
            $data['is_exists_review'] = $this->model_account_review->isExistsReview($order_info['order_id'], $this->customer->getId());

            if (!$order_info['is_owner']) {
                $this->model_order_order->updateViewed($order_id);
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('order/order_info', $data));
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
        $this->load->language('order/order');
        $this->load->model('order/section');
        $this->load->model('order/subject');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['sections'] = array();
        $sections = $this->model_order_section->getSections();
        if ($sections) {
            foreach ($sections as $section) {
                $filter_data = array(
                    'filter_section_id' => $section['section_id']
                );

                $subjects = $this->model_order_subject->getSubjects($filter_data);

                $data['sections'][] = [
                    'section_id' => $section['section_id'],
                    'name' => $section['name'],
                    'subjects' => $subjects,
                ];
            }
        }

        $this->load->model('order/work_type');
        $data['work_types'] = $this->model_order_work_type->getWorkTypes();

        $this->load->model('tool/payment_blocking');
        $data['payment_blockings'] = $this->model_tool_payment_blocking->getAll();

        $this->load->model('tool/plagiarism_check');
        $data['plagiarism_checks'] = $this->model_tool_plagiarism_check->getAll();

        if (isset($this->session->data['order']) && isset($this->session->data['order']['title'])) {
            $data['title'] = $this->session->data['order']['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->session->data['order']) && isset($this->session->data['order']['subject'])) {
            $data['subject_id'] = $this->session->data['order']['subject'];
        } else {
            $data['subject_id'] = 0;
        }

        if (isset($this->session->data['order']) && isset($this->session->data['order']['work_type'])) {
            $data['work_type_id'] = $this->session->data['order']['work_type'];
        } else {
            $data['work_type_id'] = 0;
        }

        if (isset($this->session->data['order']) && isset($this->session->data['order']['date_end'])) {
            $data['date_end'] = $this->session->data['order']['date_end'];
        } else {
            $data['date_end'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('order/order_add', $data));
    }

    public function create()
    {
        $this->load->language('order/order');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if ($this->request->post['date_unknown']) {
                $this->request->post['date_end'] = '0000-00-00';
            }

            if (!isset($this->request->post['plagiarism_check_unknown'])) {
                $this->request->post['plagiarism_check_id'] = '0';
                $this->request->post['plagiarism'] = [];
            }

            $this->load->model('order/order');
            $order_id = $this->model_order_order->addOrder($this->request->post);
            $order_info = $this->model_order_order->getOrder($order_id);

            $this->load->model('account/customer');
            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => $this->language->get('text_create')
            ));


            // Send notice to admin
            $mdata['comment'] = '';
            $mdata['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
            $mdata['message'] = sprintf(
                $this->language->get('notification_new_order'),
                '<a href="' . $this->url->link('account/customer', 'customer_id=' . (int)$this->customer->getId()) . '">' . $this->customer->getLogin() . '</a>',
                $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
            );

            $mails_admin = explode(',',$this->config->get('config_mail_alert_email'));

            foreach ($mails_admin as $mail_admin) {

                if (filter_var($mail_admin, FILTER_VALIDATE_EMAIL)) {
                    $this->taskManager->set([
                        'channel' => 'emails',
                        'type' => 'email_send',
                        'time_exec' => time(),
                        'object' => [
                            'to' => trim($mail_admin),
                            'subject' => sprintf($this->language->get('text_subject_new_order'), $this->config->get('config_name')),
                            'message' => $this->load->view('mail/offer', $mdata)
                        ]
                    ]);
                }
            }

            unset($this->session->data['order']);

            // Добавляем в очередь задачу вернуть статус "Открытый" по истечению суток
            $this->taskManager->set([
                'channel' => 'default',
                'type' => 'order_cancel',
                'time_exec' => time() + 1209600,
                'object' => [
                    'order' => $order_info,
                    'order_customer_info' => $order_customer_info
                ]
            ]);

            // Создаем задачу на рассылку
            $this->taskManager->set([
                'channel' => 'default',
                'type' => 'emailing_new_order',
                'time_exec' => time(),
                'object' => [
                    'order' => $order_info
                ]
            ]);

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        if (isset($this->error['description'])) {
            $json['error_description'] = $this->error['description'];
            unset($this->error['description']);
        }

        if (isset($this->error['subject'])) {
            $json['error_subject'] = $this->error['subject'];
            unset($this->error['subject']);
        }

        if (isset($this->error['work_type'])) {
            $json['error_work_type'] = $this->error['work_type'];
            unset($this->error['work_type']);
        }

        if (isset($this->error['date_end'])) {
            $json['error_date_end'] = $this->error['date_end'];
            unset($this->error['date_end']);
        }

        if (isset($this->error['balance_premium']) && isset($this->error['balance_hot'])) {
            $json['error_balance'] = $this->error['balance_premium'];

            $price_premium_order = 100;
            $price_hot_order = 100;

            $json['amount'] = $price_premium_order + $price_hot_order;
            $json['balance_redirect'] = $this->url->link('account/finance/payment', 'amount=' . $json['amount'], true);
        } elseif (isset($this->error['balance_premium'])) {
            $json['error_balance'] = $this->error['balance_premium'];

            $price_premium_order = 100;

            $json['amount'] = $price_premium_order;
            $json['balance_redirect'] = $this->url->link('account/finance/payment', 'amount=' . $json['amount'], true);
        } elseif (isset($this->error['balance_hot'])) {
            $json['error_balance'] = $this->error['balance_hot'];

            $price_hot_order = 100;

            $json['amount'] = $price_hot_order;
            $json['balance_redirect'] = $this->url->link('account/finance/payment', 'amount=' . $json['amount'], true);
        }

        if (isset($this->error['auth'])) {
            $json['error_auth'] = $this->error['auth'];
            unset($this->error['auth']);
            $json['redirect'] = $this->url->link('account/login', '', true);
        }

        if (isset($this->error['customer_group'])) {
            $json['error_customer_group'] = $this->error['customer_group'];
            unset($this->error['customer_group']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('order/section');
        $this->load->model('order/subject');

        $this->document->setTitle($this->language->get('text_edit_order'));

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $order_info = $this->model_order_order->getOrder($order_id);

        if ($order_info) {
            $data['breadcrumbs'][] = array(
                'text' => $order_info['title'],
                'href' => '#'
            );

            $data['is_logged'] = $this->customer->isLogged();

            $data['sections'] = array();
            $sections = $this->model_order_section->getSections();
            if ($sections) {
                foreach ($sections as $section) {
                    $filter_data = array(
                        'filter_section_id' => $section['section_id']
                    );

                    $subjects = $this->model_order_subject->getSubjects($filter_data);

                    $data['sections'][] = [
                        'section_id' => $section['section_id'],
                        'name' => $section['name'],
                        'subjects' => $subjects,
                    ];
                }
            }

            $this->load->model('order/work_type');
            $data['work_types'] = $this->model_order_work_type->getWorkTypes();

            $this->load->model('tool/payment_blocking');
            $data['payment_blockings'] = $this->model_tool_payment_blocking->getAll();

            $this->load->model('tool/plagiarism_check');
            $data['plagiarism_checks'] = $this->model_tool_plagiarism_check->getAll();

            $data['order_id'] = $order_info['order_id'];
            $data['title'] = $order_info['title'];
            $data['description'] = $order_info['description'];
            $data['note'] = $order_info['note'];
            $data['section'] = $order_info['section'];
            $data['section_id'] = $order_info['section_id'];
            $data['subject'] = $order_info['subject'];
            $data['subject_id'] = $order_info['subject_id'];
            $data['work_type'] = $order_info['work_type'];
            $data['work_type_id'] = $order_info['work_type_id'];
            $data['payment_blocking_id'] = $order_info['payment_blocking_id'];
            $data['plagiarism_check_id'] = $order_info['plagiarism_check_id'];
            $data['plagiarism'] = ($order_info['plagiarism'] ? json_decode($order_info['plagiarism']) : []);
            $data['date_added'] = $order_info['date_added'];
            $data['date_end'] = ($order_info['date_end'] != '0000-00-00'? format_date($order_info['date_end'], 'Y-m-d') : 'Не указан');
            $data['date_unknown'] = ($order_info['date_end'] == '0000-00-00 00:00:00' ? 1: 0);
            $data['viewed'] = $order_info['viewed'];
            $data['order_status'] = $order_info['order_status'];
            $data['is_owner'] = $order_info['is_owner'];
            $data['price'] = $order_info['price'];

            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            $data['customer'] = [
                'login' => $customer_info['login'],
                'online' => $customer_info['online'],
                'last_seen' => date($this->language->get('datetime_format'), $customer_info['last_seen']),
                'href' => $this->url->link('account/customer', 'customer_id=' . $customer_info['customer_id'])
            ];

            $data['action_upload'] = $this->url->link('common/upload/upload', '', true);
            $data['edit'] = $this->url->link('order/order/edit', 'customer_id=' . $order_info['order_id'], true);

            $this->model_order_order->updateViewed($order_id);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('order/order_edit', $data));
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

    public function update()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        $json = array();

        if ($order_info && $order_info['order_status_id'] == $this->config->get('config_open_order_status_id') && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if (isset($this->request->post['date_unknown']) && $this->request->post['date_unknown'] == 1) {
                $this->request->post['date_end'] = '0000-00-00';
            }

            if (!isset($this->request->post['plagiarism_check_unknown'])) {
                $this->request->post['plagiarism_check_id'] = '0';
                $this->request->post['plagiarism'] = [];
            }

            $this->model_order_order->editOrder($order_id, $this->request->post);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');

            if ($order_info['title'] != $this->request->post['title']) {
                $this->model_order_history->addHistory(array(
                    'order_id' => $order_id,
                    'customer_id' => $this->customer->getId(),
                    'text' => $this->language->get('text_update') . sprintf($this->language->get('text_updated_title'), $this->request->post['title'])
                ));
            }

            if ($order_info['description'] != $this->request->post['description']) {
                $this->model_order_history->addHistory(array(
                    'order_id' => $order_id,
                    'customer_id' => $this->customer->getId(),
                    'text' => $this->language->get('text_update') . $this->language->get('text_updated_description')
                ));
            }

            if ($order_info['price'] != $this->request->post['price']) {

                if ($this->request->post['price']) {
                    $new_price = $this->currency->format($this->request->post['price'], $this->config->get('config_currency'));
                } else {
                    $new_price = '"Договорная"';
                }

                if ($order_info['price'] != '0') {
                    $old_price = $this->currency->format($order_info['price'], $this->config->get('config_currency'));
                } else {
                    $old_price = '"Договорная"';
                }

                $this->model_order_history->addHistory(array(
                    'order_id' => $order_id,
                    'customer_id' => $this->customer->getId(),
                    'text' => $this->language->get('text_update') .
                        sprintf(
                            $this->language->get('text_updated_price'),
                            $old_price,
                            $new_price
                        )
                ));
            }

            if ($order_info['date_end'] != $this->request->post['date_end']) {

                if ($this->request->post['date_end']) {
                    $new_date_end = format_date($this->request->post['date_end'], 'd.m.Y');
                } else {
                    $new_date_end = '"Срок не указан"';
                }

                if ($order_info['date_end'] != '0000-00-00 00:00:00') {
                    $old_date_end = format_date($order_info['date_end'], 'd.m.Y');
                } else {
                    $old_date_end = '"Срок не указан"';
                }

                if ($old_date_end != $new_date_end) {
                    $this->model_order_history->addHistory(array(
                        'order_id' => $order_id,
                        'customer_id' => $this->customer->getId(),
                        'text' => $this->language->get('text_update') .
                            sprintf(
                                $this->language->get('text_updated_date_end'),
                                $old_date_end,
                                $new_date_end
                            )
                    ));
                }
            }

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        if (isset($this->error['description'])) {
            $json['error_description'] = $this->error['description'];
            unset($this->error['description']);
        }

        if (isset($this->error['subject'])) {
            $json['error_subject'] = $this->error['subject'];
            unset($this->error['subject']);
        }

        if (isset($this->error['work_type'])) {
            $json['error_work_type'] = $this->error['work_type'];
            unset($this->error['work_type']);
        }

        if (isset($this->error['date_end'])) {
            $json['error_date_end'] = $this->error['date_end'];
            unset($this->error['date_end']);
        }

        if (isset($this->error['auth'])) {
            $json['error_auth'] = $this->error['auth'];
            unset($this->error['auth']);
            $json['redirect'] = $this->url->link('account/login', '', true);
        }

        if (isset($this->error['customer_group'])) {
            $json['error_customer_group'] = $this->error['customer_group'];
            unset($this->error['customer_group']);
        }

        if ($order_info['order_status_id'] != $this->config->get('config_open_order_status_id')) {
            $json['error_customer_group'] = $this->language->get('error_unknown');
            unset($this->error['customer_group']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function cancel()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        $json = array();

        $error = [];

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем находится ли заказ в статусе ожидания
            if ($order_info['order_status_id'] != $this->config->get('config_open_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }
            // Проверяем находится ли заказ в статусе ожидания
            if (!$order_info['is_owner']) {
                $error['unknown'] = $this->language->get('error_unknown');
            }
        }

        if (!$error) {

            // отменяем order
            $this->model_order_order->cancelOrder($order_info['order_id']);

            // История
            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => $this->language->get('text_cancel')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // Уведомление order owner

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_cancel_order_owner'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_cancel_order_owner'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($order_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $order_customer_info['email'],
                        'subject' => sprintf(
                            $this->language->get('text_subject_cancel_order'),
                            $this->config->get('config_name')
                        ),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }
            $json['success'] = 'Успех';
        } else {
            $json = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function open()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        $json = array();

        $error = [];

        if (!$order_info) {
            $error['access_denied'] = $this->language->get('error_access_denied');
        } else {

            // Проверяем находится ли заказ в статусе ожидания
            if ($order_info['order_status_id'] != $this->config->get('config_canceled_order_status_id')) {
                $error['unknown'] = $this->language->get('error_unknown');
            }
            // Проверяем находится ли заказ в статусе ожидания
            if (!$order_info['is_owner']) {
                $error['unknown'] = $this->language->get('error_unknown');
            }
        }

        if (!$error) {

            // открываем order
            $this->model_order_order->openOrder($order_info['order_id']);

            // История
            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => $this->language->get('text_open')
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // Уведомление order owner

            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_open_order_owner'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_open_order_owner'),
                    'Я',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($order_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $order_customer_info['email'],
                        'subject' => sprintf(
                            $this->language->get('text_subject_open_order'),
                            $this->config->get('config_name')
                        ),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }
            $json['success'] = 'Успех';
        } else {
            $json = $error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addAttachment()
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

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($order_info && $order_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->addAttachment($order_id, $attachment_id);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_upload_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteAttachment()
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

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($order_info && $order_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->deleteAttachment($order_id, $attachment_id);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_remove_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteOfferAttachment()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/attachment');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);
        $offer_info = $this->model_order_offer->getOfferAssigned($order_id);

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $offer_attachment_info = $this->model_order_order->getOrderOfferAttachment($attachment_id);
        $attachment_info = $this->model_tool_attachment->getAttachment($offer_attachment_info['attachment_id']);

        $json = array();

        if ($order_info && $offer_info && $offer_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
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
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_remove_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            $json['success'] = 'Файл успешно удален';
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOfferAttachment()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/attachment');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_order_order->getOrder($order_id);

        $offer_info = $this->model_order_offer->getOfferAssigned($order_id);

        if (isset($this->request->post['attachment_id'])) {
            $attachment_id = $this->request->post['attachment_id'];
        } else {
            $attachment_id = 0;
        }

        $attachment_info = $this->model_tool_attachment->getAttachment($attachment_id);

        $json = array();

        if ($order_info && $offer_info && $offer_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->addOfferAttachment($order_id, $attachment_id);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_id,
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_upload_offer_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            // Notification
            $this->load->model('tool/notification');
            $this->load->model('account/customer');

            $offer_customer_info = $this->model_account_customer->getCustomerInfo($offer_info['customer_id']);
            $order_customer_info = $this->model_account_customer->getCustomerInfo($order_info['customer_id']);

            // Уведомление order owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $order_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_offer_attachment_add'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                    '<span>' . $attachment_info['name'] . ' (' . format_size($attachment_info['size']) . ')</span>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($order_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_offer_attachment_add'),
                    '<a href="' . $this->url->link('account/customer', 'customer_id=' . $offer_customer_info['customer_id']) . '">' . $offer_customer_info['login'] . '</a>',
                    '<span>' . $attachment_info['name'] . ' (' . format_size($attachment_info['size']) . ')</span>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($order_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $order_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_awaiting_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            // Уведомление offer owner
            $this->model_tool_notification->set([
                'type' => 'order',
                'customer_id' => $offer_info['customer_id'],
                'text' => sprintf(
                    $this->language->get('notification_offer_attachment_add'),
                    'Я',
                    '<span>' . $attachment_info['name'] . ' (' . format_size($attachment_info['size']) . ')</span>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                ),
            ]);

            if ($offer_customer_info['setting_email_notify']) {
                $this->load->model('setting/setting');

                $data['message'] = sprintf(
                    $this->language->get('notification_offer_attachment_add'),
                    'Я',
                    '<span>' . $attachment_info['name'] . ' (' . format_size($attachment_info['size']) . ')</span>',
                    $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']),
                    $order_info['title']
                );

                $data['comment'] = '';
                $data['link'] = $this->url->link('order/order/info', 'order_id=' . $order_id);

                // Unsubscribe generate
                $unsubscribe_token = (new \Model\Subscribe($this->db))->generateUnsubscribeToken($offer_customer_info['email']);
                $data['unsubscribe'] = $this->url->link('account/unsubscribe', 'key=' . $unsubscribe_token);

                $this->taskManager->set([
                    'channel' => 'emails',
                    'type' => 'email_send',
                    'time_exec' => time(),
                    'object' => [
                        'to' => $offer_customer_info['email'],
                        'subject' => sprintf($this->language->get('text_subject_awaiting_offer'), $this->config->get('config_name')),
                        'message' => $this->load->view('mail/offer', $data)
                    ]
                ]);
            }

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_id);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
/*
    public function deleteOfferAttachment()
    {
        $this->load->language('order/order');
        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('tool/attachment');

        if (isset($this->request->post['attachment_id'])) {
            $order_offer_attachment_id = $this->request->post['attachment_id'];
        } else {
            $order_offer_attachment_id = 0;
        }

        $order_offer_attachment_info = $this->model_order_order->getOrderOfferAttachment($order_offer_attachment_id);

        $order_info = $this->model_order_order->getOrder($order_offer_attachment_info['order_id']);

        $offer_info = $this->model_order_offer->getOfferAssigned($order_offer_attachment_info['order_id']);

        $attachment_info = $this->model_tool_attachment->getAttachment($order_offer_attachment_info['attachment_id']);

        $json = array();

        if ($order_info && $offer_info['is_owner'] && $attachment_info && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('order/order');
            $this->model_order_order->deleteOfferAttachment($order_offer_attachment_id);

            // set history
            $this->load->language('order/history');
            $this->load->model('order/history');
            $this->load->model('account/customer_group');
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

            $this->model_order_history->addHistory(array(
                'order_id' => $order_info['order_id'],
                'customer_id' => $this->customer->getId(),
                'text' => sprintf($this->language->get('text_remove_file'), $customer_group_info['name'], '%s', $attachment_info['name'])
            ));

            $json['success'] = $this->language->get('text_success');
            $json['redirect'] = $this->url->link('order/order/info', 'order_id=' . $order_info['order_id']);
        }

        if (isset($this->error['title'])) {
            $json['error_title'] = $this->error['title'];
            unset($this->error['title']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }*/

    protected function validate()
    {
        if (!$this->customer->isLogged()) {
            $this->error['auth'] = $this->language->get('error_auth');
        }

        if ($this->customer->getGroupId() != 1) {
            $this->error['customer_group'] = $this->language->get('error_customer_group');
        }

        if (!isset($this->request->post['title']) || strlen($this->request->post['title']) > 255 || strlen($this->request->post['title']) < 3) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (!isset($this->request->post['description']) || strlen($this->request->post['description']) < 3) {
            $this->error['description'] = $this->language->get('error_description');
        }

        if (!isset($this->request->post['subject']) || $this->request->post['subject'] < 1) {
            $this->error['subject'] = $this->language->get('error_subject');
        }

        if (!isset($this->request->post['work_type']) || $this->request->post['work_type'] < 1) {
            $this->error['work_type'] = $this->language->get('error_work_type');
        }
        if (!(isset($this->request->post['date_unknown']) && $this->request->post['date_unknown'] == 1)) {

            if (!isset($this->request->post['date_end']) || $this->request->post['date_end'] == '') {
                $this->error['date_end'] = $this->language->get('error_date_end');
            }

            if (isset($this->request->post['date_end']) && $this->request->post['date_end'] !== '') {

                if (strtotime($this->request->post['date_end']) < strtotime(date('Y-m-d'))) {
                    $this->error['date_end'] = $this->language->get('error_date_end_incorrect');
                }

                $date_end = explode('-', $this->request->post['date_end']);

                if (!checkdate($date_end[1], $date_end[2], $date_end[0])) {
                    $this->error['date_end'] = $this->language->get('error_date_end_incorrect2');
                }
            }
        }

        if (isset($this->request->post['premium']) && $this->request->post['premium'] == 1) {
            $price_premium_order = 100;

            if ($this->customer->getBalance() - $price_premium_order < 0) {
                $this->error['balance_premium'] = $this->language->get('error_balance');
            }
        }

        if (isset($this->request->post['hot']) && $this->request->post['hot'] == 1) {
            $price_hot_order = 100;

            if ($this->customer->getBalance() - $price_hot_order < 0) {
                $this->error['balance_hot'] = $this->language->get('error_balance');
            }
        }

        if (!$this->error) {
            return true;
        }

        return false;
    }

}
