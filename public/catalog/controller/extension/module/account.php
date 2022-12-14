<?php

class ControllerExtensionModuleAccount extends Controller
{

    public function index()
    {
        $this->load->language('extension/module/account');
        $this->load->model('localisation/order_status');
        $this->load->model('account/order');
        $this->load->model('claim/claim');

        $data['is_logged'] = $this->customer->isLogged();
        $data['customer_group_id'] = $this->customer->getGroupId();
        $data['account'] = $this->url->link('account/customer', 'customer_id=' . $this->customer->getId());
        $data['edit'] = $this->url->link('account/edit');
        $data['finance'] = $this->url->link('account/finance');
        $data['order_add'] = $this->url->link('order/order/add');
        $data['promo_code']        = $this->url->link('account/promo_code');


        $data['show_create_order'] = (bool)preg_match('/(\/create-order).*/i', $this->request->server['REQUEST_URI']);


        if ($this->customer->isLogged()) {

            if ($this->customer->getGroupId() == 1) {
                /* Мои работы Заказчик */
                $filter_data = [
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $data['total_orders'] = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'] = [];
                // open order status

                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_open_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // progress order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_progress_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // awaiting order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_awaiting_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // pending order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_pending_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // verification order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_verification_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // revision order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_revision_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // complete order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_complete_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // canceled order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_canceled_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                /* Мои работы Заказчик */
                $filter_data = [
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $data['total_claims'] = $this->model_claim_claim->getTotalClaims($filter_data);

                $filter_data = [
                    'filter_status_on' => 1,
                ];

                $data['total_claims_open'] = $this->model_claim_claim->getTotalClaims($filter_data);
                $data['total_claims_open_href'] = $this->url->link('claim/claim', 'filter_status_on=1');

                $filter_data = [
                    'filter_status_off' => 1,
                ];

                $data['total_claims_close'] = $this->model_claim_claim->getTotalClaims($filter_data);
                $data['total_claims_close_href'] = $this->url->link('claim/claim', 'filter_status_off=1');

            } elseif ($this->customer->getGroupId() == 2) {

                /* Мои работы Заказчик */
                $filter_data = [
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $data['total_orders'] = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'] = [];
                // open order status

                /*$order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_open_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];*/

                // progress order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_progress_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // awaiting order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_awaiting_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // pending order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_pending_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // verification order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_verification_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // revision order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_revision_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // complete order status
                $order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_complete_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];

                // canceled order status
                /*$order_status = $this->model_localisation_order_status->getOrderStatus($this->config->get('config_canceled_order_status_id'));

                $filter_data = [
                    'filter_order_status_id' => $order_status['order_status_id'],
                    'filter_offer_id' => $this->customer->getId(),
                ];

                $total_orders = $this->model_account_order->getTotalOrders($filter_data);

                $data['orders'][] = [
                    'order_status_id' => $order_status['order_status_id'],
                    'name' => $order_status['name'],
                    'total_orders' => $total_orders,
                    'href' => $this->url->link('account/order', 'filter_order_status_id=' . $order_status['order_status_id'])
                ];*/

                /* Мои Отклики Автор */



                $filter_data = [
                    'filter_customer_id' => $this->customer->getId(),
                ];

                $data['total_claims'] = $this->model_claim_claim->getTotalClaims($filter_data);

                $filter_data = [
                    'filter_status_on' => 1,
                ];

                $data['total_claims_open'] = $this->model_claim_claim->getTotalClaims($filter_data);
                $data['total_claims_open_href'] = $this->url->link('claim/claim', 'filter_status_on=1');

                $filter_data = [
                    'filter_status_off' => 1,
                ];

                $data['total_claims_close'] = $this->model_claim_claim->getTotalClaims($filter_data);
                $data['total_claims_close_href'] = $this->url->link('claim/claim', 'filter_status_off=1');
            }

        }


        return $this->load->view('extension/module/account', $data);
    }

}
