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
		$data['continent']=$this->Destination_fm->getAllContinent();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	public function browse_destinations()
	{
		if($this->input->is_ajax_request())
		{
			$start_row=$this->uri->segment(2);
			if(trim($start_row)=='')
			{
							$start_row=0;
			}
			$this->load->library('pagination');
			$config["base_url"] = site_url('browse-destinations');

			$config["total_rows"] = $this->Destination_fm->countTotalDestinations();
			$config["full_tag_open"] = "<ul class='pagination-custom'>";
			$config["full_tag_close"] = "</ul>";
			$config["num_tag_open"] = "<li class='pagination-item-custom'>";
			$config["num_tag_close"] = "</li>";
			$config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
			$config["cur_tag_close"] = "</a></span></li>";
			$config['prev_link'] = 'Previous';
			$config['next_link'] = 'Next';
			$config["prev_tag_open"] = "<li class='pagination-item-custom first'>";
			$config["prev_tag_close"] = "</li>";
			$config["next_tag_open"] = "<li class='pagination-item-custom last'>";
			$config["next_tag_close"] = "</li>";
			$config["first_link"] = "<li style='float:left'>&lsaquo; First";
			$config["first_link"] = "</li>";
			$config["last_link"] = "<li>Last &rsaquo;";
			$config["last_link"] = "</li>";
			$config['per_page'] = 10;
			$this->pagination->initialize($config);
			$data['country_details']=$this->Destination_fm->getTotalDestinations($config["per_page"],$start_row);
			$data["pagination"]=$this->pagination->create_links();
			$_html=$this->load->view('destination/loadDestinations', $data,TRUE);
			//echo "<pre>";print_r($data['country_details']);die;
			echo $_html;
		}

	}


}
