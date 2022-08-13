<?php
class ControllerExtensionDashboardOrder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_order', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/order', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/order', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_order_width'])) {
			$data['dashboard_order_width'] = $this->request->post['dashboard_order_width'];
		} else {
			$data['dashboard_order_width'] = $this->config->get('dashboard_order_width');
		}
		
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_order_status'])) {
			$data['dashboard_order_status'] = $this->request->post['dashboard_order_status'];
		} else {
			$data['dashboard_order_status'] = $this->config->get('dashboard_order_status');
		}

		if (isset($this->request->post['dashboard_order_sort_order'])) {
			$data['dashboard_order_sort_order'] = $this->request->post['dashboard_order_sort_order'];
		} else {
			$data['dashboard_order_sort_order'] = $this->config->get('dashboard_order_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/order_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/order');

		$data['user_token'] = $this->session->data['user_token'];

		// Total Orders
		$this->load->model('order/order');

        $filter_data = [
            'filter_order_id'	        => '',
            'filter_title'	            => '',
            'filter_section_id'	        => '',
            'filter_subject_id'	        => '',
            'filter_order_status_id'    => '',
            'filter_date_added'	        => '',
            'filter_date_end'           => '',
        ];

        $data['totals'] = [];

		$order_total = $this->model_order_order->getTotalOrders($filter_data);

        $data['totals'][] = [
            'title' => 'Всего заказов',
            'total' => format_number($order_total),
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'])
        ];


        /* В работе */
        $filter_data['filter_order_status_id'] = $this->config->get('config_progress_order_status_id');
        $order_total = $this->model_order_order->getTotalOrders($filter_data);

        $data['totals'][] = [
            'title' => 'В работе',
            'total' => format_number($order_total),
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&filter_order_status_id=' . $this->config->get('config_progress_order_status_id'))
        ];

        /* На проверке */
        $filter_data['filter_order_status_id'] = $this->config->get('config_verification_order_status_id');
        $order_total = $this->model_order_order->getTotalOrders($filter_data);

        $data['totals'][] = [
            'title' => 'На проверке',
            'total' => format_number($order_total),
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&filter_order_status_id=' . $this->config->get('config_verification_order_status_id'))
        ];

        /* Готовые */
        $filter_data['filter_order_status_id'] = $this->config->get('config_complete_order_status_id');
        $order_total = $this->model_order_order->getTotalOrders($filter_data);

        $data['totals'][] = [
            'title' => 'Выполненные',
            'total' => format_number($order_total),
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'] . '&filter_order_status_id=' . $this->config->get('config_complete_order_status_id'))
        ];

		return $this->load->view('extension/dashboard/order_info', $data);
	}
}
