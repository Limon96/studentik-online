<?php

class ControllerSalePro extends Controller {

    public function index()
    {
        $this->load->language('sale/pro');
        $this->load->model('sale/pro');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/pro', '', true)
        );

        $data['pro'] = array();

        $pro = $this->model_sale_pro->getPros();

        if ($pro) {
            foreach ($pro as $item) {
                $data['pro'][] = array(
                    "pro_id" => $item['pro_id'],
                    "title" => $item['title'],
                    "description" => $item['description'],
                    "price" => $this->currency->format($item['price'], $this->config->get('config_currency'))
                );
            }
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('sale/pro', $data));
    }

}