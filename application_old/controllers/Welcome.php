<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Front_Controller {

	public function index()
	{
		$this->output->cache(5);
		$data['webpage']='innerleft';
		$data['main']='demo/index';
		$this->load->vars($data);
		$this->load->view('templates/homemaster');
	}

	function attraction_listings()
	{
		$data['webpage']='attraction_listings';
		$data['main']='demo/attraction_listings';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function country_recommendation()
	{
		$data['webpage']='country_recommendation';
		$data['main']='demo/country_recommendation';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}

	function hotel_bookings()
	{
		$data['webpage']='hotel_bookings';
		$data['main']='demo/hotel_bookings';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}
}
