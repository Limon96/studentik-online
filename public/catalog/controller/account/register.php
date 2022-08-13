<?php
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/account', '', true);
		} else {
            $this->load->language('account/register');
            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $customer_id = $this->model_account_customer->addCustomer($this->request->post);

                // Clear any previous login attempts for unregistered accounts.
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                $this->customer->login($this->request->post['email'], $this->request->post['password']);

                unset($this->session->data['guest']);
                $json['success'] = $this->language->get('text_success');
                $json['redirect'] = $this->url->link('account/success');
            } else {
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }

                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = '';
                }

                if (isset($this->error['password'])) {
                    $json['error_password'] = $this->error['password'];
                } else {
                    $json['error_password'] = '';
                }

                if (isset($this->error['customer_group_id'])) {
                    $json['error_customer_group_id'] = $this->error['customer_group_id'];
                } else {
                    $json['error_customer_group_id'] = '';
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function guestOrder()
    {
        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('account/account', '', true);
        } else {
            $this->load->language('account/register');
            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRegister()) {

                $customer_id = $this->model_account_customer->addCustomerFromOrder($this->request->post);

                $this->session->data['order'] = [
                    'title' => $this->request->post['title'],
                    'subject' => $this->request->post['subject'] ?? 0,
                    'work_type' => $this->request->post['work_type'] ?? 0,
                    'date_end' => $this->request->post['date_end'] ?? '0000-00-00',
                ];

                // Clear any previous login attempts for unregistered accounts.
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                unset($this->session->data['guest']);
                $json['success'] = $this->language->get('text_quest_order_success');
                $json['redirect'] = str_replace('&amp', '&', $this->url->link('order/order/add', '', true));
                //$json['redirect'] = str_replace('&amp', '&', $this->url->link('account/success', '', true));
            } else {
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }

                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = '';
                }

                if (isset($this->error['work_type'])) {
                    $json['error_work_type'] = $this->error['work_type'];
                } else {
                    $json['error_work_type'] = '';
                }

                if (isset($this->error['title'])) {
                    $json['error_title'] = $this->error['title'];
                } else {
                    $json['error_title'] = '';
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function landingOrder()
    {
        if ($this->customer->isLogged()) {
            $json['redirect'] = $this->url->link('account/account', '', true);
        } else {
            $this->load->language('account/register');
            $this->load->model('account/customer');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateLanding()) {

                $customer_id = $this->model_account_customer->addCustomerFromOrder($this->request->post);

                $this->session->data['order'] = [
                    'title' => $this->request->post['title'],
                ];

                // Clear any previous login attempts for unregistered accounts.
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                unset($this->session->data['guest']);
                $json['success'] = $this->language->get('text_quest_order_success');
                $json['redirect'] = str_replace('&amp', '&', $this->url->link('order/order/add', '', true));
                //$json['redirect'] = str_replace('&amp', '&', $this->url->link('account/success', '', true));
            } else {
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }

                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = '';
                }

                if (isset($this->error['title'])) {
                    $json['error_title'] = $this->error['title'];
                } else {
                    $json['error_title'] = '';
                }

                if (isset($this->error['agree'])) {
                    $json['error_agree'] = $this->error['agree'];
                } else {
                    $json['error_agree'] = '';
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function approval()
    {
        $this->load->model('account/customer');
        $this->load->language('account/register');

        if ($this->validateApproval()) {
            $this->model_account_customer->approvalCustomer($this->request->get['customer_token']);

            if($this->customer->isLogged() && $this->customer->getGroupId() == 1) {
                $this->response->redirect($this->url->link('order/order/add'));
            } elseif($this->customer->isLogged() && $this->customer->getGroupId() == 2) {
                $this->response->redirect($this->url->link('account/specialization'));
            } else {
                $this->response->redirect($this->url->link('order/order'));
            }

            $data['text_message'] = $this->language->get('text_success_approval');
        } else {
            $data['text_message'] = $this->error['invalid'];
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/success')
        );

        $data['continue'] = $this->url->link('order/order', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/success', $data));
    }

	private function validateApproval()
    {
        if (!isset($this->request->get['customer_token'])) {
            $this->error['invalid'] = 'Произошла неизвестная ошибка';
            return false;
        }

        $customer_info = $this->model_account_customer->getCustomerByApprovalToken($this->request->get['customer_token']);

        if (!$customer_info) {
            $this->error['invalid'] = 'Токен не верный или устарел. Обратитесь к администрации сервиса';
            return false;
        }

        return true;
    }

	private function validate() {

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

        if ($this->checkDomainEmailInBlackList($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email_black_list');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
        if (!$customer_group_info) {
            $this->error['customer_group_id'] = $this->language->get('error_customer_group_id');
        }

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

    private function validateRegister() {

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->checkDomainEmailInBlackList($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email_black_list');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if (!isset($this->request->post['title']) || utf8_strlen($this->request->post['title']) < 3 || utf8_strlen($this->request->post['title']) > 150) {
            $this->error['title'] = $this->language->get('error_title');
        }

       /* if (!isset($this->request->post['work_type']) || $this->request->post['work_type'] < 1) {
            $this->error['work_type'] = $this->language->get('error_work_type');
        }*/

        return !$this->error;
    }

    private function validateLanding() {

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->checkDomainEmailInBlackList($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email_black_list');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if (!isset($this->request->post['title']) || utf8_strlen($this->request->post['title']) < 3 || utf8_strlen($this->request->post['title']) > 150) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (!isset($this->request->post['agree'])) {
            $this->error['agree'] = sprintf($this->language->get('error_agree'), ' политикой конфиденциальности');
        }

        return !$this->error;
    }

    public function checkDomainEmailInBlackList($email)
    {
        if ($this->config->has('config_mail_black_list')) {
            $black_list = array_map(function($value){
                return trim($value);
            }, explode(',', $this->config->get('config_mail_black_list')));

            $domain = $this->getEmailDomain($email);

            return in_array($domain, $black_list);
        }

        return false;
    }

    private function getEmailDomain($email)
    {
        return substr($email, strpos($email, '@') + 1, strlen($email));
    }

}
