<?php
class ControllerCommonHome extends Controller {
	public function index() {
        if ($this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('common/home', '', true);

            $this->response->redirect($this->url->link('order/order', '', true));
        }

		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

        $this->load->model('order/section');
        $this->load->model('order/subject');

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

        $data['telephone'] = $this->config->get('config_telephone');
        $data['email'] = $this->config->get('config_email');

        $this->load->model('tool/statistic');

        $data['total_experts'] = $this->model_tool_statistic->totalExperts();
        $data['text_total_experts'] = num_word($data['total_experts'], ['исполнитель', 'исполнителя', 'исполнителей'], false) ;
        $data['total_students'] = $this->model_tool_statistic->totalStudents();
        $data['text_total_students'] = num_word($data['total_students'], ['студент', 'студента', 'студентов'], false);
        $data['total_order_completed'] = $this->model_tool_statistic->totalOrderCompleted();
        $data['text_total_order_completed'] = num_word($data['total_order_completed'], ['выполненный заказ', 'выполненных заказа', 'выполненных заказов'], false);

        $this->load->model('order/order');
        $this->load->model('order/offer');
        $this->load->model('order/history');
        $filter_data = [
            'filter_order_status_id' => 6,
            'limit' => 10,
            'order' => 'DESC'
        ];
        $last_orders = $this->model_order_order->getOrders($filter_data);
        $data['last_orders'] = [];
        if ($last_orders) {
            foreach ($last_orders as $order) {

                $offer_info = $this->model_order_offer->getOfferAssigned($order['order_id']);

                if (isset($order['completed_at']) && $order['completed_at']) {
                    $completed_at = format_date($order['completed_at'], 'dMt');
                } else {
                    $lastHistoryRecord = $this->model_order_history->lastHistoryRecord($order['order_id']);
                    $completed_at = format_date($lastHistoryRecord['date_added'], 'dMt');
                }

                $data['last_orders'][] = [
                    "order_id" => $order['order_id'],
                    "title" => $order['title'],
                    "subject" => mb_strtolower($order['subject']),
                    "work_type" => $order['work_type'],
                    "plagiarism_check" => $order['plagiarism_check'] ?? '75%',
                    "completed_at" => $completed_at,
                    "bet" => $offer_info['bet'] ?? 0,
                    "href" => $this->url->link('order/order/info', 'order_id=' . $order['order_id'], true),
                ];
            }
        }

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header_landing'] = $this->load->controller('common/header_landing');

		$this->response->setOutput($this->load->view('common/home', $data));
	}

    public function send()
    {
        $mail = new Mail($this->config->get('config_mail_engine'));

        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setFrom($this->config->get('config_mail_smtp_sender_mail'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject("Test");
        $mail->setHTML("<h1>Hello world</h1>");

        foreach (['nicker08@inbox.ru', 'nicker.pro25@gmail.com', 'radik-hamitov@bk.ru'] as $to) {
            $mail->setTo($to);
            $mail->send();
        }

    }
}
