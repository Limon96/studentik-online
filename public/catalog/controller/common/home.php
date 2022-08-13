<?php
class ControllerCommonHome extends Controller {
	public function index() {
        if ($this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('common/home', '', true);

            $this->response->redirect($this->url->link('order/order', '', true));
        }

		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
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

        $this->load->model('order/work_type');
        $data['work_types'] = $this->model_order_work_type->getWorkTypes();

        $data['telephone'] = $this->config->get('config_telephone');
        $data['email'] = $this->config->get('config_email');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header_landing'] = $this->load->controller('common/header_landing');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
