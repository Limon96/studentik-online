<?php

class ControllerAccountPartner extends Controller
{
    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/partner', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $referral_code = $this->customer->getPartnerCode();

        if (!$referral_code) {
            $this->session->data['redirect'] = $this->url->link('account/partner', '', true);

            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $this->load->language('account/partner');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->language('account/partner');
        $this->load->model('account/partner');

        $data['total_registrations'] = $this->model_account_partner->getTotalRegistrations($referral_code);
        $data['total_orders'] = $this->model_account_partner->getTotalOrders($referral_code);
        $data['total_orders_in_work'] = $this->model_account_partner->getTotalOrdersInWork($referral_code);
        $data['total_customer_blocked_cash'] = $this->model_account_partner->getTotalCustomerBlockedCash($referral_code);
        $data['total_orders_completed'] = $this->model_account_partner->getTotalOrdersCompleted($referral_code);
        $data['total_sum_completed_orders'] = $this->model_account_partner->getTotalSumCompletedOrders($referral_code);
        $data['total_avg_completed_orders'] = $this->model_account_partner->getTotalAvgCompletedOrders($referral_code);
        $data['customer_amount'] = $this->percent($this->model_account_partner->getTotalCustomerAmount($referral_code), 15);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/partner', $data));
    }

    private function percent($sum, $percent)
    {
        return floor($sum / 100 * $percent);
    }
}
