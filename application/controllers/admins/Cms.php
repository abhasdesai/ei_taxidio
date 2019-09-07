<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index($id) {

		$data['cms']=$this->Cms_m->getContent($id);
		if(count($data['cms'])<1)
		{
			show_404();
		}
		$data['webpagename'] = $data['cms']['webpagename'];
		$data['main'] = 'admins/adminfiles/Cms/index';
		$data['section'] = 'Cms->'.$data['cms']['title'];
		$data['page'] = 'Cms';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function saveContent()
	{
		$this->form_validation->set_rules('content','Content','trim|required');
		if($this->form_validation->run()==FALSE)
		{
			$data['cms']=$this->Cms_m->getContent($_POST['id']);
			$this->session->set_flashdata('error','Transaction Failed');
			if(count($data['cms'])<1)
			{
				show_404();
			}
			$data['webpagename'] = $data['cms']['webpagename'];
			$data['main'] = 'admins/adminfiles/Cms/index';
			$data['section'] = 'Cms->'.$data['cms']['title'];
			$data['page'] = 'Cms';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');		
		}
		else
		{
			$this->Cms_m->saveContent();
			$this->session->set_flashdata('success','Transaction Successful');
			redirect('admins/Cms/index/'.$_POST['id']);
		}

	}
}
?>