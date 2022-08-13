<?php

class ControllerOrderSubject extends Controller {

    public function autocomplete()
    {
        $this->load->language('order/order');

        if (isset($this->request->get['filter_section_id'])) {
            $filter_section_id = $this->request->get['filter_section_id'];
        } else {
            $filter_section_id = 0;
        }
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = 0;
        }

        if ($filter_section_id) {
            $filter_data = [
                "filter_section_id" => $filter_section_id,
                "filter_name" => $filter_name,
            ];

            $this->load->model('order/subject');
            $json['subject'] = $this->model_order_subject->getSubjects($filter_data);
        } else {
            $json['subject'] = array();
        }

        $json['text_all_subject'] = $this->language->get('text_all_subject');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}