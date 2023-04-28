<?php

require_once DIR_SYSTEM . 'library/yookassa/yookassa.php';

class ControllerExtensionPaymentYookassa extends Controller {


    private $white_list = ['bank_card', 'yoo_money', 'webmoney', 'qiwi', 'sbp'];

    public function index()
    {
        if ($this->customer->isLogged()) {
            if (isset($this->request->get['type']) && in_array($this->request->get['type'], $this->white_list)) {
                $type = $this->request->get['type'];
            } else {
                $type = 'bank_card';
            }

            if (isset($this->request->get['amount'])) {
                $amount = (int)$this->request->get['amount'];
            } else {
                $amount = '';
            }

            if (isset($this->request->get['name'])) {
                $name = $this->request->get['name'];
            } else {
                $name = $this->language->get('text_payment_name');
            }

            if (isset($this->request->get['thumb'])) {
                $thumb = $this->request->get['thumb'];
            } else {
                $thumb = 'catalog/assets/img/yuyu.png';
            }

            if (isset($this->request->get['commission'])) {
                $commission = $this->request->get['commission'];
            } else {
                $commission = $this->language->get('text_unknown');
            }

            $data['amount'] = $amount;
            $data['name'] = $name;
            $data['commission'] = $commission;
            $data['thumb'] = $thumb;
            $data['action'] = str_replace('&amp;', '&', $this->url->link('extension/payment/yookassa/create', 'type=' . $type));

            $this->response->setOutput($this->load->view('extension/payment/yookassa', $data));
        } else {
            $this->load->language('extension/payment/error');

            $data['text_error'] = $this->language->get('error_auth');

            $data['action'] = $this->url->link('account/login', '', true);
            $data['button_name'] = $this->language->get('button_login');

            $this->response->setOutput($this->load->view('extension/payment/error', $data));
        }
    }

    public function create()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('extension/payment/yookassa', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('extension/payment/yookassa');

        if (isset($this->request->get['amount'])) {
            $amount = (int)$this->request->get['amount'];
        } else {
            $amount = 0;
        }

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], $this->white_list)) {
            $type = $this->request->get['type'];
        } else {
            $type = 'bank_card';
        }

        $json = [];

        if ($amount < $this->config->get('config_payment_min')) {
            $json['error'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_payment_min'), $this->config->get('config_currency')));
        } else {

            $this->load->model('payment/payment');

            $payment_id = $this->model_payment_payment->addPayment([
                'customer_id' => $this->customer->getId(),
                'payment_method' => $type,
                'amount' => $amount,
                'currency' => $this->config->get('config_currency'),
                'payment_status_id' => 1, // Статус оплаты: Ожидает оплаты
                'ip' => $this->request->server['HTTP_X_REAL_IP']
            ]);

            $yookassa = new YooKassa($this->config->get('yoomoney_kassa_shop_id'), $this->config->get('yoomoney_kassa_password'));

            switch ($type) {
                case 'bank_card':
                    $percent = 1.03;
                    break;
                case 'yoo_money':
                    $percent = 1.04;
                    break;
                case 'sbp':
                    $percent = 1.01;
                    break;
                /* case 'webmoney':
                    $percent = 1.06;
                    break;
                case 'qiwi':
                    $percent = 1.06;
                    break;*/
                default:
                    $percent = 1.06;
            }

            $response = $yookassa->create(
                array(
                    'amount' => array(
                        'value' => ceil($amount * $percent) . '.00',
                        'currency' => $this->config->get('config_currency'),
                    ),
                    'payment_method_data' => array(
                        'type' => $type,
                    ),
                    'capture' => true,
                    'confirmation' => array(
                        'type' => 'redirect',
                        'return_url' => str_replace('&amp;', '&', $this->url->link('extension/payment/yookassa/confirm', 'payment_id=' . $payment_id)),
                    ),
                    'description' => sprintf($this->language->get('text_payment_id'), $payment_id)
                )
            );

            $this->model_payment_payment->setPlatformPaymentId($payment_id, $response['yookassa_payment_id']);

            $this->session->data['payment_id'] = $payment_id;
            $this->session->data['yookassa_payment_id'] = $response['yookassa_payment_id'];

            $json['redirect'] = $response['redirect_url'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function confirm()
    {
        $this->load->model('account/customer');
        $this->load->model('payment/payment');

        if (!$this->customer->isLogged()) {
            //$this->session->data['redirect'] = $this->url->link('extension/payment/yookassa/confirm', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['payment_id'])) {
            $payment_id = (int)$this->request->get['payment_id'];
        } else {
            $payment_id = 0;
        }

        if ($payment_id) {
            $payment_info = $this->model_payment_payment->getPayment($payment_id);

            if ($payment_info['payment_status_id'] == 2) {
                $this->response->redirect($this->url->link('account/finance/success', '', true));
            } elseif ($payment_info['payment_status_id'] == 3) {
                $this->response->redirect($this->url->link('account/finance/canceled', '', true));
            }
        }

        if ($payment_id == $this->session->data['payment_id']) {
            if (isset($this->session->data['yookassa_payment_id'])) {
                $paymentId = $this->session->data['yookassa_payment_id'];

                $yookassa = new YooKassa($this->config->get('yoomoney_kassa_shop_id'), $this->config->get('yoomoney_kassa_password'));

                $paymentInfo = $yookassa->getPaymentInfo($paymentId);

                $payment_info = $this->model_payment_payment->getPayment($payment_id);

                if ($payment_info && $paymentInfo->status == 'succeeded') {
                    $this->model_account_customer->setBalance($payment_info['customer_id'], $payment_info['amount']);

                    $this->model_payment_payment->setPaymentStatus($payment_id, 2); // Статус оплаты: Успешно

                    unset($this->session->data['yookassa_payment_id']);
                    unset($this->session->data['payment_id']);

                    $this->response->redirect($this->url->link('account/finance/success', 'stat=2', true));
                } elseif ($payment_info && $paymentInfo->status == 'canceled') {
                    $this->model_payment_payment->setPaymentStatus($payment_id, 3); // Статус оплаты: Отменен

                    unset($this->session->data['yookassa_payment_id']);
                    unset($this->session->data['payment_id']);
                    $this->response->redirect($this->url->link('account/finance/canceled', 'stat=3', true));
                } elseif ($payment_info && $paymentInfo->status == 'pending') {
                    $this->model_payment_payment->setPaymentStatus($payment_id, 1); // Статус оплаты: Отменен

                    $this->response->redirect($this->url->link('account/finance/pending', 'stat=1', true));
                }
            } else {

                unset($this->session->data['yookassa_payment_id']);
                unset($this->session->data['payment_id']);
                $this->model_payment_payment->setPaymentStatus($payment_id, 4); // Статус оплаты: Ошибка
                $this->response->redirect($this->url->link('account/finance/canceled', 'stat=4', true));
            }

        } else {

            unset($this->session->data['yookassa_payment_id']);
            unset($this->session->data['payment_id']);
            $this->model_payment_payment->setPaymentStatus($payment_id, 4);  // Статус оплаты: Ошибка
        }

        $this->response->redirect($this->url->link('account/finance/canceled', 'stat=5', true));
    }

}
