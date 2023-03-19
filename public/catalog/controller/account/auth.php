<?php

class ControllerAccountAuth extends Controller
{

    public function index()
    {
        $access_token = '';

        if (isset($this->request->get['access_token'])) {
            $access_token = $this->request->get['access_token'];
        }

        $redirectTo = '/';

        if (isset($this->request->get['redirect_to'])) {
            $redirectTo = $this->request->get['redirect_to'];
        }

        $accessTokenInfo = $this->getAccessTokenInfo($access_token);

        if ($accessTokenInfo && isset($accessTokenInfo['user_id'])) {
            $this->customer->logout();
            $this->cart->clear();

            unset($this->session->data['order_id']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);

            $customer_info = $this->getCustomerInfo($accessTokenInfo['user_id']);

            $this->customer->login($customer_info['email'], '', true);

            $redirect = $redirectTo;
        } else {
            $redirect = '/';
        }

        $this->response->redirect($redirect);
    }

    public function log_out()
    {
        $redirectTo = '/';

        if (isset($this->request->get['redirect_to'])) {
            $redirectTo = $this->request->get['redirect_to'];
        }

        $this->customer->logout();
        $this->cart->clear();

        unset($this->session->data['order_id']);
        unset($this->session->data['payment_address']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['shipping_address']);
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['comment']);
        unset($this->session->data['coupon']);
        unset($this->session->data['reward']);
        unset($this->session->data['voucher']);
        unset($this->session->data['vouchers']);

        $this->response->redirect($redirectTo);
    }

    private function getCustomerInfo($customer_id)
    {
        $this->load->model('account/customer');

        return $this->model_account_customer->getCustomer($customer_id);
    }

    private function getAccessTokenInfo($access_token)
    {
        $this->load->model('account/auth');

        return $this->model_account_auth->get($access_token);
    }

}
