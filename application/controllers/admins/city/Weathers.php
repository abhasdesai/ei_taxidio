<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weathers extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Weathers/index';
		$data['city']=$this->Weather_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['months']=$this->Weather_m->getAllMonths();
		$data['section'] = $data['city']['city_name'].' -> Weathers';
		$data['page'] = 'Weathers';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function saveData()
	{
		if($this->input->post('btnsubmit'))
		{
			$this->Weather_m->saveData();
			$this->session->set_flashdata('success', 'Transaction Successful.');
			redirect('admins/city/Weathers/index/'.$_POST['city_id']);
		}
	}

}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */