<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clothes extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Clothes/index';
		$data['city']=$this->Cloth_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['months']=$this->Cloth_m->getAllMonths();
		$data['section'] = $data['city']['city_name'].' -> Clothes';
		$data['page'] = 'Clothes';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function saveData()
	{
		if($this->input->post('btnsubmit'))
		{
			$this->Cloth_m->saveData();
			$this->session->set_flashdata('success', 'Transaction Successful.');
			redirect('admins/city/Clothes/index/'.$_POST['city_id']);
		}
	}

}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */