<?php
class ControllerExtensionDashboardCustomer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_customer', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/customer', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/customer', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_customer_width'])) {
			$data['dashboard_customer_width'] = $this->request->post['dashboard_customer_width'];
		} else {
			$data['dashboard_customer_width'] = $this->config->get('dashboard_customer_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_customer_status'])) {
			$data['dashboard_customer_status'] = $this->request->post['dashboard_customer_status'];
		} else {
			$data['dashboard_customer_status'] = $this->config->get('dashboard_customer_status');
		}

		if (isset($this->request->post['dashboard_customer_sort_order'])) {
			$data['dashboard_customer_sort_order'] = $this->request->post['dashboard_customer_sort_order'];
		} else {
			$data['dashboard_customer_sort_order'] = $this->config->get('dashboard_customer_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/customer_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
		
	public function dashboard() {
		$this->load->language('extension/dashboard/customer');

		$data['user_token'] = $this->session->data['user_token'];

		// Total Orders
		$this->load->model('customer/customer');

        $filter_data = [
            'filter_date_added' => '',
            'filter_date_added_from' => '',
            'filter_customer_group_id' => 2
        ];

        $data['totals'] = [];

        $total_stud = $this->model_customer_customer->getTotalCustomers($filter_data);

        $data['totals'][] = [
            'title' => 'Всего исполнителей',
            'total' => format_number($total_stud),
            'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'])
        ];

        $filter_data['filter_date_added_from'] = time() - 86400;

        $total_stud = $this->model_customer_customer->getTotalCustomers($filter_data);

        $data['totals'][] = [
            'title' => 'Новые исполнители',
            'total' => format_number($total_stud),
            'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'])
        ];

        $filter_data = [
            'filter_date_added' => '',
            'filter_date_added_from' => '',
            'filter_customer_group_id' => 1
        ];

        $total_stud = $this->model_customer_customer->getTotalCustomers($filter_data);

        $data['totals'][] = [
            'title' => 'Всего заказчиков',
            'total' => format_number($total_stud),
            'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'])
        ];

        $filter_data['filter_date_added_from'] = time() - 86400;

        $total_stud = $this->model_customer_customer->getTotalCustomers($filter_data);

        $data['totals'][] = [
            'title' => 'Новые заказчики',
            'total' => format_number($total_stud),
            'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'])
        ];

		return $this->load->view('extension/dashboard/customer_info', $data);
	}
}
