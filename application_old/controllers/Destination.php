<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Destination extends Front_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index() 
	{
		$data['webpage'] = 'destination';
		$data['main'] = 'destination';
		$data['meta_title']='Multiple Destination Trip & Travel Planner | Taxidio';
		$data['meta_keywords']='multiple destination trip planner,multiple destination travel planner';
		$data['meta_description']="Discover the world with our multiple destination trip planner. Use our multiple destination travel planner to plan your trip across the globe.";
		$data['destination']=$this->Destination_fm->getAllCountry();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}
}
