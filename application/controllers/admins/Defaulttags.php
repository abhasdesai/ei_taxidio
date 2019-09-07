<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Defaulttags extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'defaulttags';
		$data['main'] = 'admins/adminfiles/Defaulttags/index';
		$data['section'] = 'Default Tags';
		$data['tags']=$this->City_m->getTags();
		$data['stadium']=$this->City_m->getDefaultStadiumTags();
		$data['attraction']=$this->City_m->getDefaultAttractionTags();
		$data['restaurant']=$this->City_m->getDefaultRestaurantTags();
		$data['relaxation']=$this->City_m->getDefaultRelaxationTags();
		$data['adventure']=$this->City_m->getDefaultAdventureTags();
		$data['page'] = 'Default Tags';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function add()
	{
		if($this->input->post('btnsubmit'))
		{
			$data['tags']=$this->City_m->addDefaultTags();
			$this->session->set_flashdata('success', 'Transaction Successful.');
			redirect('admins/Defaulttags/');
		}
		else
		{
			$data['webpagename'] = 'defaulttags';
			$data['main'] = 'admins/adminfiles/Defaulttags/index';
			$data['section'] = 'Default Tags';
			$data['tags']=$this->City_m->getTags();
			$data['page'] = 'Default Tags';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	
}

/* End of file Days Range.php */
/* Location: ./application/controllers/admins/Days Range.php */