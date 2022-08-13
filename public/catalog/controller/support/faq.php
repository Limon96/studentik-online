<?php
class ControllerSupportFAQ extends Controller {
	public function index() {
		$this->load->model('support/support');

		$this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Главная',
			'href' => $this->url->link('common/home')
		);

        $data['breadcrumbs'][] = array(
            'text' => 'FAQ',
            'href' => $this->url->link('support/faq')
        );

        $this->document->setTitle('FAQ – Часто задаваемые вопросы');
        $this->document->setDescription('Часто задаваемые вопросы на бирже ' . $this->config->get('config_name') . '. Здесь вы найдёте ответы на возникающие вопросы.');

        $data['heading_title'] = 'FAQ';

        $last_update = $this->model_support_support->getFAQLastUpdate();

        $data['last_update'] = sprintf("Последнее обновление %s", format_date($last_update, 'full_datetime'));

        $data['faq'] = array();

        $results = $this->model_support_support->getFAQ();

        foreach ($results as $result) {
            $data['faq'][] = array(
                'faq_id'      => $result['faq_id'],
                'question'    => html_entity_decode($result['question'], ENT_QUOTES, 'UTF-8'),
                'answer'      => html_entity_decode($result['answer'], ENT_QUOTES, 'UTF-8'),
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('support/faq', $data));
	}
}
