<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['settings']=$this->Settings_m->getContent();
		if(count($data['settings'])<1)
		{
			show_404();
		}
		$data['webpagename'] = $data['settings']['webpagename'];
		$data['main'] = 'admins/adminfiles/Settings/index';
		//$data['section'] = 'Settings->'.$data['settings']['email'];
		$data['page'] = 'Settings';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function saveContent()
	{
		$this->form_validation->set_rules('email','Email','trim|required');
		if($this->form_validation->run()==FALSE)
		{
			$data['settings']=$this->Settings_m->getContent();
			$this->session->set_flashdata('success','Transaction Failed');
			if(count($data['settings'])<1)
			{
				show_404();
			}
			$data['webpagename'] = $data['settings']['webpagename'];
			$data['main'] = 'admins/adminfiles/Settings/index';
			//$data['section'] = 'Settings->'.$data['settings']['title'];
			$data['page'] = 'Settings';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');		
		}
		else
		{
			$this->Settings_m->saveContent();
			$this->session->set_flashdata('success','Transaction Successful');
			redirect('admins/Settings/index/1');
		}

	}
}
?>
