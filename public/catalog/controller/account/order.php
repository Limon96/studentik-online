<?php
class ControllerAccountOrder extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', '', true)
		);

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

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
        }

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
		} else {
            $filter_order_status_id = 0;
		}

		if (isset($this->request->get['filter_assigned'])) {
			$filter_assigned = (int)$this->request->get['filter_assigned'];
		} else {
            $filter_assigned = 1;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 20;

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

        $this->load->model('order/order_status');
        $data['order_statuses'] = $this->model_order_order_status->getOrderStatuses();

		$this->load->model('account/customer');
		$this->load->model('account/order');
		$this->load->model('tool/image');

		if ($this->customer->getGroupId() ==  1) {
		    $filter_data = [
		        'filter_customer_id' => $this->customer->getId(),
                'filter_order_status_id' => $filter_order_status_id,
                'filter_section_id' => $filter_section_id,
                'filter_subject_id' => $filter_subject_id,
                'filter_work_type_id' => $filter_work_type_id,
                'filter_customer' => $filter_customer,
                'search' => $search,
                'start' => ($page - 1) * $limit,
                'limit' => $limit,
                'order' => 'DESC'
            ];
        } elseif ($this->customer->getGroupId() == 2) {
            $filter_data = [
                'filter_assigned' => $filter_assigned,
                'filter_order_status_id' => $filter_order_status_id,
                'filter_section_id' => $filter_section_id,
                'filter_subject_id' => $filter_subject_id,
                'filter_work_type_id' => $filter_work_type_id,
                'filter_customer' => $filter_customer,
                'search' => $search,
                'start' => ($page - 1) * $limit,
                'limit' => $limit,
                'order' => 'DESC'
            ];
        } else {
            $filter_data = array();
        }

		$order_total = $this->model_account_order->getTotalOrders($filter_data);
        $orders = $this->model_account_order->getOrders($filter_data);
        $data['text_total'] = sprintf($this->language->get('text_total'), $order_total, num_word($order_total, ['заказ', 'заказа', 'заказов'], false));
        $data['orders'] = array();

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
                    "order_status" => $order['order_status'],
                    "viewed" => num_word($order['viewed'], ['просмотр', 'просмотра', 'просмотров']),
                    "premium" => $order['premium'],
                    "hot" => $order['hot'],
                    "count_offer" => num_word($order['count_offer'], ['отклик', 'отклика', 'откликов']),
                    "exist_offer" => $order['exist_offer'],
                    "description" => htmlspecialchars_decode($order['description']),
                    "price" => $price,
                    "customer" => [
                        "login" => $customer_info['login'],
                        "online" => $customer_info['online'],
                        "image" => $image,
                        "href" => $this->url->link('account/customer', 'customer_id=' . $order['customer_id'], true)
                    ],
                    "href" => $this->url->link('order/order/info', 'order_id=' . $order['order_id'], true),
                ];
            }
        }

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', true);

        $data['limit'] = $limit * $page;
        $data['total_orders'] = $order_total;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$url = '';

        if (isset($this->request->get['filter_assigned'])) {
            $url .= '&filter_assigned=' . (int)$this->request->get['filter_assigned'];
        }

        if (isset($this->request->get['filter_section_id'])) {
            $url .= '&filter_section_id=' . (int)$this->request->get['filter_section_id'];
        }

        if (isset($this->request->get['filter_subject_id'])) {
            $url .= '&filter_subject_id=' . (int)$this->request->get['filter_subject_id'];
        }

        if (isset($this->request->get['filter_work_type_id'])) {
            $url .= '&filter_work_type_id=' . (int)$this->request->get['filter_work_type_id'];
        }

        if (isset($this->request->get['search'])) {
            $url .= '&filter_work_type_id=' . $this->request->get['search'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_customer=' . (int)$this->request->get['filter_order_status_id'];
        }

		$data['account_order'] = $this->url->link('account/order');

        $data['filter_customer'] = $filter_customer;
        $data['filter_section_id'] = $filter_section_id;
        $data['filter_subject_id'] = $filter_subject_id;
        $data['filter_work_type_id'] = $filter_work_type_id;
        $data['filter_order_status_id'] = $filter_order_status_id;
        $data['search'] = $search;

		$data['continue'] = $this->url->link('account/order', $url . '&page=' . ($page + 1), true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/order_list', $data));
	}

	public function info() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			$this->document->setTitle($this->language->get('text_order'));

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, true)
			);

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = (int)$this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
				} else {
					$reorder = '';
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
				);
			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			$data['continue'] = $this->url->link('account/order', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/order_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}

	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($this->config->get('config_encryption'), $order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
	}
}