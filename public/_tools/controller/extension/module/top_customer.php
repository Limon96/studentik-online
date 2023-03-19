<?php
class ControllerExtensionModuleTopCustomer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/top_customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_top_customer', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/top_customer', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/top_customer', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_top_customer_status'])) {
			$data['module_top_customer_status'] = $this->request->post['module_top_customer_status'];
		} else {
			$data['module_top_customer_status'] = $this->config->get('module_top_customer_status');
		}

		if (isset($this->request->post['module_top_customer_customer_group_id'])) {
			$data['module_top_customer_customer_group_id'] = $this->request->post['module_top_customer_customer_group_id'];
		} else {
			$data['module_top_customer_customer_group_id'] = $this->config->get('module_top_customer_customer_group_id');
		}

        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['module_top_customer_limit'])) {
			$data['module_top_customer_limit'] = $this->request->post['module_top_customer_limit'];
		} elseif ($this->config->get('module_top_customer_limit')) {
			$data['module_top_customer_limit'] = $this->config->get('module_top_customer_limit');
		} else {
			$data['module_top_customer_limit'] = 3;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/top_customer', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/top_customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}