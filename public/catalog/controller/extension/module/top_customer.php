<?php

class ControllerExtensionModuleTopCustomer extends Controller
{

    public function index()
    {
        $this->load->language('extension/module/top_customer');

        $this->load->model('account/customer');

        $filter_data = [
            'filter_customer_group_id' => $this->config->get('module_top_customer_customer_group_id'),
            'limit' => $this->config->get('module_top_customer_limit')
        ];

        $customers = $this->model_account_customer->getTopCustomers($filter_data);

        $data['customers'] = array();

        if ($customers) {

            $this->load->model('tool/image');

            foreach ($customers as $customer) {
                if ($customer['image']) {
                    $image = $this->model_tool_image->resize($customer['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('profile.png', 80, 80);
                }

                $data['customers'][] = [
                    "customer_id" => $customer['customer_id'],
                    "firstname" => $customer['firstname'],
                    "login" => $customer['login'],
                    "rating" => $customer['rating'],
                    "new_rating" => $customer['new_rating'],
                    "pro" => $customer['pro'],
                    "total_reviews_positive" => $customer['total_reviews_positive'],
                    "total_reviews_negative" => $customer['total_reviews_negative'],
                    "image" => $image,
                    "date_added" => $customer['date_added'],
                    "href" => $this->url->link('account/customer', 'customer_id=' . $customer['customer_id'], true),
                ];
            }
        }

        $data['experts'] = $this->url->link('expert/expert');

        return $this->load->view('extension/module/top_customer', $data);
    }

}