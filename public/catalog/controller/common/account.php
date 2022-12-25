<?php
class ControllerCommonAccount extends Controller {
	public function index() {
		$this->load->language('common/account');

        $data['is_logged']  = $this->customer->isLogged();
        $data['login']      = HTTPS_SERVER . 'sign_in';
        $data['register']      = $this->url->link('account/login','act=register');
        $data['logout']     = HTTPS_SERVER . 'logout';
        $data['finance']    = $this->url->link('account/finance');
        $data['edit']    = $this->url->link('account/edit');
        $data['event']    = $this->url->link('account/event');
        $data['promo_code']    = $this->url->link('account/promo_code');
        $data['password']    = $this->url->link('account/password');
        $data['pro']    = $this->url->link('sale/pro');
        $data['chat']    = $this->url->link('message/chat');

        if ($this->customer->isLogged()) {
            $this->load->model('tool/image');
            if ($this->customer->getImage()) {
                $image = $this->model_tool_image->resize($this->customer->getImage(), 80, 80);
            } else {
                $image = $this->model_tool_image->resize('profile.png', 80, 80);
            }

            $data['customer'] = [
                "customer_id" => $this->customer->getId(),
                "firstname" => $this->customer->getFirstname(),
                "login" => $this->customer->getLogin(),
                "balance" => $this->currency->format($this->customer->getBalance(), $this->config->get('config_currency')),
                "image" => $image,
                "href" => $this->url->link('account/customer', 'customer_id=' . $this->customer->getId(), true),
            ];

            $this->load->model('message/chat');

            $data['total_message_unread'] = $this->model_message_chat->getTotalUnreadMessages($this->customer->getId());

            $this->load->model('tool/notification');

            $data['total_notification_unread'] = $this->model_tool_notification->getTotalUnreadNotifications($this->customer->getId());

            $data['customer_group_id'] = $this->customer->getGroupId();
        } else {
            $data['account']    =  $this->url->link('account/account');

            $data['total_message_unread'] = 0;
            $data['total_notification_unread'] = 0;
            $data['customer_group_id'] = 0;
        }

		return $this->load->view('common/account', $data);
	}
}
