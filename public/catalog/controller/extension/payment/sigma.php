<?php

class ControllerExtensionPaymentSigma extends Controller
{
    private $white_list = ['card', 'qiwi', 'sbp'];

    public function index()
    {
        if ($this->customer->isLogged()) {
            if (isset($this->request->get['type']) && in_array($this->request->get['type'], $this->white_list)) {
                $type = $this->request->get['type'];
            } else {
                $type = 'card';
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
                $thumb = '/catalog/assets/img/sigma-logo.png';
            }

            if (isset($this->request->get['commission'])) {
                $commission = $this->request->get['commission'];
            } else {
                $commission = $this->language->get('text_unknown');
            }

            $data['telephone'] = $this->customer->getTelephone();
            $data['type'] = $type;
            $data['amount'] = $amount;
            $data['name'] = $name;
            $data['commission'] = $commission;
            $data['thumb'] = $thumb;
            $data['action'] = str_replace('&amp;', '&', $this->url->link('extension/payment/sigma/create', 'type=' . $type));

            $this->response->setOutput($this->load->view('extension/payment/sigma', $data));
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

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode([
                'error' => 'Access denied'
            ]));

            return;
        }

        $this->load->language('extension/payment/sigma');

        if (isset($this->request->get['amount'])) {
            $amount = (int)$this->request->get['amount'];
        } else {
            $amount = 0;
        }

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], $this->white_list)) {
            $type = $this->request->get['type'];
        } else {
            $type = 'card';
        }

        $json = [];

        if ($amount < $this->config->get('config_payment_min')) {
            $json['error'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_payment_min'), $this->config->get('config_currency')));
        }

        if ($type == 'qiwi' && (!isset($this->request->get['telephone']) || trim($this->request->get['telephone']) == '')) {
            $json['error'] = $this->language->get('error_telephone');
        }

        if (!$json) {
            $this->load->model('payment/payment');

            $payment_id = $this->model_payment_payment->addPayment([
                'customer_id' => $this->customer->getId(),
                'payment_method' => $type,
                'amount' => $this->calcAmountWithPercent($amount, $type),
                'currency' => $this->config->get('config_currency'),
                'payment_status_id' => 1, // Статус оплаты: Ожидает оплаты
                'ip' => $this->request->server['HTTP_X_REAL_IP']
            ]);

            if ($this->request->get['type'] == 'qiwi') {
                $json['redirect'] = HTTPS_SERVER . 'payment/sigma/create?pid=' . (int)$payment_id . '&cid=' . $this->customer->getId() . '&telephone=' . $this->request->get['telephone'];
            } else {
                $json['redirect'] = HTTPS_SERVER . 'payment/sigma/create?pid=' . (int)$payment_id . '&cid=' . $this->customer->getId();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function calcAmountWithPercent($amount, $payment_method)
    {
        switch ($payment_method){
            case "card":
                return $amount * 1.035;
            case "qiwi":
                return $amount * 1.06;
            case "sbp":
                return $amount * 1.015;

            default:
                return 0;
        }
    }

}
