<?php

class ControllerSupportContact extends Controller
{

    public function index()
    {
        $this->document->setTitle('Служба поддержки');
        $this->document->setDescription('Служба поддержки пользователей проекта ' . $this->config->get('config_name'));

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('support/contact', $data));
    }

}