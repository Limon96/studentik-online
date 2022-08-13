<?php

class ControllerAccountSpecialization extends Controller {

    public function index()
    {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/specialization');
        $this->load->model('account/customer');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('../catalog/assets/js/specialization.js', 'footer');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/customer', 'customer_id=' . $this->customer->getId(), true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_specialization'),
            'href' => $this->url->link('account/specialization', '', true)
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $this->load->model('order/section');
        $this->load->model('order/subject');

        $data['sections'] = array();
        $sections = $this->model_order_section->getSections();
        if ($sections) {
            foreach ($sections as $section) {
                $filter_data = array(
                    'filter_section_id' => $section['section_id']
                );

                $subjects = $this->model_order_subject->getSubjects($filter_data);

                $data['sections'][] = [
                    'section_id' => $section['section_id'],
                    'name' => $section['name'],
                    'subjects' => $subjects,
                ];
            }
        }

        $customer_subjects = $this->model_account_customer->getCustomerSubjects($this->customer->getId());
        $data['customer_subjects'] = array();

        if ($customer_subjects) {
            foreach ($customer_subjects as $customer_subject) {
                $data['customer_subjects'][] = $customer_subject['subject_id'];
            }
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/specialization', $data));
    }

    public function save()
    {
        $json = array();
        if (!$this->customer->isLogged()) {
            $json['error'] = $this->language->get('error_auth');
        }

        if (!$json) {
            $this->load->model('account/customer');
            $this->model_account_customer->setCustomerSubjects($this->request->post);
            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
}