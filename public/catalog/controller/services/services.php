<?php

class ControllerServicesServices extends Controller
{

    public function index()
    {
        $this->load->language('services/services');

        $this->document->setTitle('Студенческие работы на заказ — помощь студентам с написанием курсовых, контрольных и других работ в онлайн-сервисе ' . $this->config->get('config_name'));
        $this->document->setDescription('Заказать выполнение студенческих работ — консультация, гарантия качества и бесплатные доработки от экспертов биржи ' . $this->config->get('config_name') . '. Узнать стоимость бесплатно.');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('services/services', $data));
    }

}