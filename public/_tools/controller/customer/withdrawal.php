<?php
class ControllerCustomerWithdrawal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/withdrawal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/withdrawal');

		$this->getList();
	}

	public function edit() {
		$this->load->language('customer/withdrawal');

		$this->load->model('customer/withdrawal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            if (isset($this->request->post['withdrawal_id'])) {
                if (isset($this->request->post['status']) && is_numeric($this->request->post['status'])) {
                    $this->model_customer_withdrawal->setStatus($this->request->post['withdrawal_id'], $this->request->post['status']);
                }

                if (isset($this->request->post['comment']) && strlen($this->request->post['comment']) < 256) {
                    $this->model_customer_withdrawal->setComment($this->request->post['withdrawal_id'], $this->request->post['comment']);
                }

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error_warning'] = $this->language->get('error_warning');
            }

		} else {
            $json['error_warning'] = $this->language->get('error_warning');
        }

        $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
	}

	public function delete() {
		$this->load->language('customer/withdrawal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/withdrawal');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $withdrawal_id) {
				$this->model_customer_withdrawal->deleteWithdrawal($withdrawal_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['delete'] = $this->url->link('customer/withdrawal/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['withdrawals'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
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
                'amount' => $withdrawal['amount'],
                'balance' => $withdrawal['balance'],
                'comment' => $withdrawal['comment'],
                'method' => $withdrawal['method'],
                'card_number' => $withdrawal['card_number'],
                'date_added' => format_date($withdrawal['date_added'], 'Y.m.d H:i'),
                'date_updated' => format_date($withdrawal['date_updated'], 'Y.m.d H:i')
            ];
        }

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $withdrawal_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($withdrawal_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($withdrawal_total - $this->config->get('config_limit_admin'))) ? $withdrawal_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $withdrawal_total, ceil($withdrawal_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/withdrawal_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['withdrawal_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['withdrawal_id'])) {
			$data['action'] = $this->url->link('customer/withdrawal/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/withdrawal/edit', 'user_token=' . $this->session->data['user_token'] . '&withdrawal_id=' . $this->request->get['withdrawal_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/withdrawal', 'user_token=' . $this->session->data['user_token'] . $url, true);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/withdrawal_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/withdrawal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/withdrawal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		return !$this->error;
	}
}
