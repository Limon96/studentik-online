<?php

class ControllerExtensionModuleContact extends Controller
{

    public function index()
    {
        $this->load->language('extension/module/contact');

        $data['telephone'] = $this->config->get('config_telephone');
        $data['email'] = $this->config->get('config_email');
        $data['is_logged'] = $this->customer->isLogged();
        $data['customer_group_id'] = $this->customer->getGroupId();
        $data['account'] = $this->url->link('account/customer', 'customer_id=' . $this->customer->getId());
        $data['edit'] = $this->url->link('account/edit');
        $data['finance'] = $this->url->link('account/finance');
        $data['order_add'] = $this->url->link('order/order/add');
        $data['write'] = $this->url->link('information/contact');

        $this->load->model('catalog/information');
        // privacy link
        if ($this->config->get('config_account_id')) {
            $about_us_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            $data['privacy'] = [
                'href' => $this->url->link('information/information', 'information_id=' . $about_us_info['information_id']),
                'title' => $about_us_info['title']
            ];
        } else {
            $data['privacy'] = false;
        }

        // terms link
        if ($this->config->get('config_user_id')) {
            $about_us_info = $this->model_catalog_information->getInformation($this->config->get('config_user_id'));

            $data['terms'] = [
                'href' => $this->url->link('information/information', 'information_id=' . $about_us_info['information_id']),
                'title' => $about_us_info['title']
            ];
        } else {
            $data['terms'] = false;
        }

        return $this->load->view('extension/module/contact', $data);
    }

}