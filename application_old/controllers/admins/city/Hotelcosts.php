<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotelcosts extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Hotelcosts/index';
		$data['city']=$this->Hotelcost_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['hoteltypes']=$this->Hotelcost_m->getAllHotelTypes();
		$data['section'] = $data['city']['city_name'].' -> Hotel Costs';
		$data['page'] = 'Hotel Costs';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	function getData()
	{
		$data["months"]=$this->Hotelcost_m->getAllMonths();
		$output['body'] =$this->load->view('admins/adminfiles/city/Hotelcosts/loadData', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));	
	}

	function saveData()
	{
		
		$this->Hotelcost_m->saveData();
		
		
	}

}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */