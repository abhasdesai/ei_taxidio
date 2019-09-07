<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = "Orders";
		$data['main'] = 'admins/adminfiles/Orders/index';
		$data['section'] = "Order History";
		$data['page'] = 'Order History';
		$this->load->model('Packages_m');
		$data['orders'] = $this->Packages_m->getAllOrders();
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}
	
	

}
?>
