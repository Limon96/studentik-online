<?php
class ControllerStartupStartup extends Controller {
	public function index() {
		// Store
		if ($this->request->server['HTTPS']) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
		}

		if (isset($this->request->get['store_id'])) {
			$this->config->set('config_store_id', (int)$this->request->get['store_id']);
		} else if ($query->num_rows) {
			$this->config->set('config_store_id', $query->row['store_id']);
		} else {
			$this->config->set('config_store_id', 0);
		}

		if (!$query->num_rows) {
			$this->config->set('config_url', HTTP_SERVER);
			$this->config->set('config_ssl', HTTPS_SERVER);
		}

		// Settings
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$this->config->set($result['key'], $result['value']);
			} else {
				$this->config->set($result['key'], json_decode($result['value'], true));
			}
		}

		// Theme
		$this->config->set('template_cache', $this->config->get('developer_theme'));

		// Url
		$this->registry->set('url', new Url($this->config->get('config_url'), $this->config->get('config_ssl')));

		// Language
		$code = '';

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		if (isset($this->session->data['language'])) {
			$code = $this->session->data['language'];
		}

		if (isset($this->request->cookie['language']) && !array_key_exists($code, $languages)) {
			$code = $this->request->cookie['language'];
		}

		// Language Detection
		if (!empty($this->request->server['HTTP_ACCEPT_LANGUAGE']) && !array_key_exists($code, $languages)) {
			$detect = '';

			$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);

			// Try using local to detect the language
			foreach ($browser_languages as $browser_language) {
				foreach ($languages as $key => $value) {
					if ($value['status']) {
						$locale = explode(',', $value['locale']);

						if (in_array($browser_language, $locale)) {
							$detect = $key;
							break 2;
						}
					}
				}
			}

			if (!$detect) {
				// Try using language folder to detect the language
				foreach ($browser_languages as $browser_language) {
					if (array_key_exists(strtolower($browser_language), $languages)) {
						$detect = strtolower($browser_language);

						break;
					}
				}
			}

			$code = $detect ? $detect : '';
		}

		if (!array_key_exists($code, $languages)) {
			$code = $this->config->get('config_language');
		}

        if (isset($this->session->data)) {
            $this->session->data['language'] = 'ru-ru';
        } else {
            $this->session->data = [];
            $this->session->data['language'] = 'ru-ru';
        }

		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}

		// Overwrite the default language object
		$language = new Language($code);
		$language->load($code);

		$this->registry->set('language', $language);

		// Set the config language_id
		$this->config->set('config_language_id', $languages[$code]['language_id']);

		// Customer
        $this->loadCustomer();



        // Set time zone
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            date_default_timezone_set('Europe/Moscow');
            // Sync PHP and DB time zones.
            $this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
        } elseif ($this->customer->getTimeZone()) {
            date_default_timezone_set($this->customer->getTimeZone());

            // Sync PHP and DB time zones.
            $this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
        } else {
            if ($this->config->get('config_timezone')) {
                date_default_timezone_set($this->config->get('config_timezone'));

                // Sync PHP and DB time zones.
                $this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
            }
        }



		// Tracking Code
		if (isset($this->request->get['tracking'])) {
			setcookie('tracking', $this->request->get['tracking'], time() + 3600 * 24 * 1000, '/');

			$this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($this->request->get['tracking']) . "'");
		}

		// Currency
		$code = '';

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		if (isset($this->session->data['currency'])) {
			$code = $this->session->data['currency'];
		}

		if (isset($this->request->cookie['currency']) && !array_key_exists($code, $currencies)) {
			$code = $this->request->cookie['currency'];
		}

		if (!array_key_exists($code, $currencies)) {
			$code = $this->config->get('config_currency');
		}

		if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
			$this->session->data['currency'] = $code;
		}

		if (!isset($this->request->cookie['currency']) || $this->request->cookie['currency'] != $code) {
			setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}

		$this->registry->set('currency', new Cart\Currency($this->registry));

		// Tax
		$this->registry->set('tax', new Cart\Tax($this->registry));

		// PHP v7.4+ validation compatibility.
		if (isset($this->session->data['shipping_address']['country_id']) && isset($this->session->data['shipping_address']['zone_id'])) {
			$this->tax->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if (isset($this->session->data['payment_address']['country_id']) && isset($this->session->data['payment_address']['zone_id'])) {
			$this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		// Weight
		$this->registry->set('weight', new Cart\Weight($this->registry));

		// Length
		$this->registry->set('length', new Cart\Length($this->registry));

		// Cart
		$this->registry->set('cart', new Cart\Cart($this->registry));

		// Encryption
		$this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));

		// TaskManager
        $this->registry->set('taskManager', new TaskManager($this->db, $this->config));

        if ($this->customer->isLogged()) {

            $this->load->model('payment/payment');
            $payments = $this->model_payment_payment->getPendingPayments($this->customer->getId());

            if ($payments) {

                require_once DIR_SYSTEM . 'library/yookassa/yookassa.php';
                $this->load->model('account/customer');


                foreach ($payments as $payment_info) {

                    $paymentId = $payment_info['platform_payment_id'];
                    $payment_id = $payment_info['payment_id'];

                    if ($payment_info['payment_status_id'] == 1) {
                        if ($payment_info['platform_payment_id'] && $payment_info['date_added'] > date('Y-m-d H:i:s', time() - 86400)) {


                            $yookassa = new YooKassa($this->config->get('yoomoney_kassa_shop_id'), $this->config->get('yoomoney_kassa_password'));

                            try{
                                $paymentInfo = $yookassa->getPaymentInfo($paymentId);
                            } catch (Exception $e) {
                                $this->log->write('Error in payment: ' . print_r($e, true));
                                $paymentInfo = false;
                            }

                            if ($paymentInfo) {

                                if ($paymentInfo->status == 'succeeded') {
                                    $this->model_account_customer->setBalance($payment_info['customer_id'], $payment_info['amount']);

                                    $this->model_payment_payment->setPaymentStatus($payment_id, 2); // Статус оплаты: Успешно

                                    $this->loadCustomer();

                                    if (isset($this->session->data['offer_id'])) {
                                        $offer_id = (int)$this->session->data['offer_id'];
                                    } else {
                                        $offer_id = 0;
                                    }

                                    if (isset($this->session->data['order_id'])) {
                                        $order_id = (int)$this->session->data['order_id'];
                                    } else {
                                        $order_id = 0;
                                    }

                                    if ($offer_id && $order_id) {
                                        $this->load->model('order/offer');
                                        $assigned_offer_result = $this->model_order_offer->assignedOffer($offer_id, $order_id);
                                        if (isset($assigned_offer_result['success'])) {
                                            $this->session->data['success'] = $assigned_offer_result['success'];
                                        }
                                    }

                                    unset($this->session->data['yookassa_payment_id']);
                                    unset($this->session->data['payment_id']);
                                } elseif ($paymentInfo->status == 'canceled') {
                                    $this->model_payment_payment->setPaymentStatus($payment_id, 3); // Статус оплаты: Отменен

                                    unset($this->session->data['yookassa_payment_id']);
                                    unset($this->session->data['payment_id']);
                                } elseif ($paymentInfo->status == 'pending') {
                                    $this->model_payment_payment->setPaymentStatus($payment_id, 1); // Статус оплаты: Ожидается
                                }
                            }
                        } else {
                            $this->model_payment_payment->setPaymentStatus($payment_id, 8); // Статус оплаты: отменён
                        }
                    }
                }
            }

            // OLD Payment check
           /* if (isset($this->session->data['yookassa_payment_id']) && isset($this->session->data['payment_id'])) {


                require_once DIR_SYSTEM . 'library/yookassa/yookassa.php';

                $this->load->model('account/customer');
                $this->load->model('payment/payment');

                $paymentId = $this->session->data['yookassa_payment_id'];
                $payment_id = $this->session->data['payment_id'];

                $yookassa = new YooKassa($this->config->get('yoomoney_kassa_shop_id'), $this->config->get('yoomoney_kassa_password'));

                try{
                    $paymentInfo = $yookassa->getPaymentInfo($paymentId);
                } catch (Exception $e) {
                    $this->log->write('Error in payment: ' . print_r($e, true));
                    $paymentInfo = false;
                }
                $this->log->write("paymentInfo" . print_r($paymentInfo, true));

                $payment_info = $this->model_payment_payment->getPayment($payment_id);

                $this->log->write("payment_info" . print_r($payment_info, true));

                if ($paymentInfo) {

                    if ($payment_info && $payment_info['payment_status_id'] == 1 && $payment_info['date_added'] > date('Y-m-d H:i:s', time() - 86400)) {

                        if ($paymentInfo->status == 'succeeded') {
                            $this->model_account_customer->setBalance($payment_info['customer_id'], $payment_info['amount']);

                            $this->model_payment_payment->setPaymentStatus($payment_id, 2); // Статус оплаты: Успешно

                            $this->loadCustomer();

                            if (isset($this->session->data['offer_id'])) {
                                $offer_id = (int)$this->session->data['offer_id'];
                            } else {
                                $offer_id = 0;
                            }

                            if (isset($this->session->data['order_id'])) {
                                $order_id = (int)$this->session->data['order_id'];
                            } else {
                                $order_id = 0;
                            }

                            if ($offer_id && $order_id) {
                                $this->load->model('order/offer');
                                $assigned_offer_result = $this->model_order_offer->assignedOffer($offer_id, $order_id);
                                $this->log->write("assigned_offer_result" . print_r($assigned_offer_result, true));
                                if (isset($assigned_offer_result['success'])) {
                                    $this->session->data['success'] = $assigned_offer_result['success'];
                                }
                            }
                            $this->log->write("Result operation: succeeded");

                            unset($this->session->data['yookassa_payment_id']);
                            unset($this->session->data['payment_id']);
                        } elseif ($paymentInfo->status == 'canceled') {
                            $this->model_payment_payment->setPaymentStatus($payment_id, 3); // Статус оплаты: Отменен

                            $this->log->write("Result operation: canceled");
                            unset($this->session->data['yookassa_payment_id']);
                            unset($this->session->data['payment_id']);
                        } elseif ($paymentInfo->status == 'pending') {
                            $this->model_payment_payment->setPaymentStatus($payment_id, 1); // Статус оплаты: Ожидается
                            $this->log->write("Result operation: pending");
                        }
                    }
                }
            }*/
        }

	}

    private function loadCustomer()
    {
        $customer = new Cart\Customer($this->registry);
        $this->registry->set('customer', $customer);

        // Customer Group
        if (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
            // For API calls
            $this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
        } elseif ($this->customer->isLogged()) {
            // Logged in customers
            $this->config->set('config_customer_group_id', $this->customer->getGroupId());
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
            $this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
        } else {
            $this->config->set('config_customer_group_id', $this->config->get('config_customer_group_id'));
        }
    }
}
