<?php
class Form extends Admin_Controller {

	public function index() {
		$data['webpagename'] = 'form';
		$data['main'] = 'admins/adminfiles/form/index';
		$data['section'] = 'Dashboard';
		$data['page'] = 'Products';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

}
?>
