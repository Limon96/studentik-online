<?php

class ControllerAccountUnsubscribe extends Controller {

    public function index()
    {
        if (isset($this->request->get['key']) && $this->validate()) {
            (new \Model\Customer($this->db))->unsubscribe($this->request->get['key']);
        }

        $this->load->language("account/unsubscribe");

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->customer->isLogged()) {
            $data['button_continue'] = $this->language->get('button_setting');
            $data['continue'] = $this->url->link('account/edit', '', true);
        } else {
            $data['button_continue'] = $this->language->get('button_login');
            $data['continue'] = $this->url->link('account/login', '', true);
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/unsubscribe', $data));
    }

    private function validate()
    {
        if (!isset($this->request->get['key']) || utf8_strlen($this->request->get['key']) != 64) {
            return false;
        }

        return true;
    }

}