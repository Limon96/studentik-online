<?php
class ControllerExtensionDashboardSale extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/sale');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_sale', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/sale', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/sale', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_sale_width'])) {
			$data['dashboard_sale_width'] = $this->request->post['dashboard_sale_width'];
		} else {
			$data['dashboard_sale_width'] = $this->config->get('dashboard_sale_width');
		}
	
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_sale_status'])) {
			$data['dashboard_sale_status'] = $this->request->post['dashboard_sale_status'];
		} else {
			$data['dashboard_sale_status'] = $this->config->get('dashboard_sale_status');
		}

		if (isset($this->request->post['dashboard_sale_sort_order'])) {
			$data['dashboard_sale_sort_order'] = $this->request->post['dashboard_sale_sort_order'];
		} else {
			$data['dashboard_sale_sort_order'] = $this->config->get('dashboard_sale_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/sale_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/sale')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/sale');

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('order/order');

        $filter_data = [
            'filter_order_id'	        => '',
            'filter_title'	            => '',
            'filter_section_id'	        => '',
            'filter_subject_id'	        => '',
            'filter_order_status_id'    => $this->config->get('config_complete_order_status_id'),
            'filter_date_added'	        => '',
            'filter_date_end'           => '',
        ];

        $data['totals'] = [];

        $total_sum = $this->model_order_order->getTotalSumOffers($filter_data);

        $data['totals'][] = [
            'title' => 'Всего оплачено',
            'total' => number_format($total_sum, 0, '.', ' ') . ' р.',
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'])
        ];

        /* Чистая прибыль */
        $clear_sum_total = ($total_sum * ((int)$this->config->get('config_commission_customer') * 0.01 + 1) * ((int)$this->config->get('config_commission') * 0.01 + 1)) - $total_sum;

        $data['totals'][] = [
            'title' => 'Чистая прибыль',
            'total' => number_format($clear_sum_total, 0, '.', ' ') . ' р.',
            'href' => $this->url->link('order/order', 'user_token=' . $this->session->data['user_token'])
        ];

		return $this->load->view('extension/dashboard/sale_info', $data);
	}
}
