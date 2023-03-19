<?php
class ControllerExtensionDashboardRecent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/recent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_recent', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/recent', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/recent', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_recent_width'])) {
			$data['dashboard_recent_width'] = $this->request->post['dashboard_recent_width'];
		} else {
			$data['dashboard_recent_width'] = $this->config->get('dashboard_recent_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_recent_status'])) {
			$data['dashboard_recent_status'] = $this->request->post['dashboard_recent_status'];
		} else {
			$data['dashboard_recent_status'] = $this->config->get('dashboard_recent_status');
		}

		if (isset($this->request->post['dashboard_recent_sort_order'])) {
			$data['dashboard_recent_sort_order'] = $this->request->post['dashboard_recent_sort_order'];
		} else {
			$data['dashboard_recent_sort_order'] = $this->config->get('dashboard_recent_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/recent_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/recent')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/recent');

		$data['user_token'] = $this->session->data['user_token'];

		// Last 5 withdrawals

        $this->load->model('customer/withdrawal');
        $this->load->model('claim/claim');

        $data['withdrawals'] = array();

        $filter_data = array(
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 5
        );

        $data['statuses'] = [
            ['id' => '0', 'name' => 'Ожидание'],
            ['id' => '1', 'name' => 'Выполнен'],
            ['id' => '2', 'name' => 'Не выполнен'],
        ];

        $withdrawal_total = $this->model_customer_withdrawal->getTotalWithdrawals();

        $data['withdrawals_total'] = $withdrawal_total;
        $data['withdrawals'] = [];

        $withdrawals = $this->model_customer_withdrawal->getWithdrawals($filter_data);

        foreach ($withdrawals as $withdrawal) {
            $data['withdrawals'][] = [
                'withdrawal_id' => $withdrawal['withdrawal_id'],
                'status' => $withdrawal['status'],
                'customer' => [
                    'login' =>  $withdrawal['customer']['login'],
                    'href' =>  $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $withdrawal['customer']['customer_id']),
                ],
                'amount' => $this->currency->format($withdrawal['amount'], $this->config->get('config_currency')),
                'balance' => $withdrawal['balance'],
                'comment' => $withdrawal['comment'],
                'method' => $withdrawal['method'],
                'card_number' => $withdrawal['card_number'],
                'view' => $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token']),
                'date_added' => format_date($withdrawal['date_added'], 'H:i d.m.Y'),
                'date_updated' => format_date($withdrawal['date_updated'], 'H:i d.m.Y')
            ];
        }


        // Last 5 claims
        $data['claims'] = [];

        $filter_data = array(
            'filter_status_on'  => 1,
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 5
        );

        $claim_total = $this->model_claim_claim->getTotalClaims($filter_data);

        $results = $this->model_claim_claim->getClaims($filter_data);

        foreach ($results as $result) {

            $white_list = ['order'];
            $object = [];
            if (in_array($result['type'], $white_list)) {
                switch ($result['type']) {
                    case 'order':
                        $this->load->model('order/order');
                        $order_info = $this->model_order_order->getOrder($result['object_id']);
                        $object = [
                            'title' => $order_info['title'],
                            'href' => HTTP_CATALOG . 'index.php?route=order/order/info&order_id=' . $order_info['order_id'],
                        ];
                        break;

                }
            }

            $customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
            $defendant_info = $this->model_customer_customer->getCustomer($result['defendant_id']);

            $data['claims'][] = array(
                'claim_id'              => $result['claim_id'],
                'title'                 => 'Претензия #' . $result['claim_id'],
                'object_title'          => $object['title'],
                'object_href'           => $object['href'],
                'customer_login'        => $customer_info['login'],
                'customer_href'         => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $customer_info['customer_id'],
                'defendant_login'       => $defendant_info['login'],
                'defendant_href'        => HTTP_CATALOG . 'index.php?route=account/customer&customer_id=' . $defendant_info['customer_id'],
                'status'                => $result['status'],
                'viewed'                => $result['viewed'],
                'date_added'            => format_date($result['date_added'], 'H:i d.m.Y'),
                'view'                  => $this->url->link('claim/claim/view', 'user_token=' . $this->session->data['user_token'] . '&claim_id=' . $result['claim_id'], true)
            );
        }

		return $this->load->view('extension/dashboard/recent_info', $data);
	}
}
