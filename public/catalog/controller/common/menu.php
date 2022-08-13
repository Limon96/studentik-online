<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		$data['categories'] = array();

		return $this->load->view('common/menu', $data);
	}
}
