<?php
class ControllerCommonCart extends Controller {
	public function index() {
		$this->load->language('common/cart');


		return $this->load->view('common/cart');
	}

	public function info() {
		$this->response->setOutput($this->index());
	}
}