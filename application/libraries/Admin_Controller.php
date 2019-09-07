<?php
class Admin_Controller extends MY_controller
{

	function __construct()
	{
		$this->load->helper('form');
		$this->load->helper('form_validation');
		$this->load->helper('session');
		$this->load->helper('cookie');
		$this->load->model('user_m');
	}
	
}
?>
