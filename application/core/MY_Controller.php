<?php
class MY_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
	}
}

class Admin_Controller extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('citydata');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('User_m');
		$this->load->model('Continent_m');
		$this->load->model('Country_m');
		$this->load->model('Rating_m');
		$this->load->model('Tag_m');
		$this->load->model('Attraction_m');
		$this->load->model('Month_m');
		$this->load->model('Hoteltype_m');
		$this->load->model('Weather_m');
		$this->load->model('Accomodation_m');
		$this->load->model('Traveler_m');
		$this->load->model('Doi_m');
		$this->load->model('Traveltime_m');
		$this->load->model('Budget_m');
		$this->load->model('Day_m');
		$this->load->model('City_m');
		$this->load->model('Cityattraction_m');
		$this->load->model('Relaxationspa_m');
		$this->load->model('Restaurant_m');
		$this->load->model('Stadium_m');
		$this->load->model('Cloth_m');
		$this->load->model('Report_m');
		$this->load->model('Parameter_m');
		$this->load->model('Adventures_m');
		$this->load->model('Destinationoptiontag_m');
		$this->load->model('Mandatorytag_m');
		$this->load->model('Mandatorytagmaster_m');
		$this->load->model('Hotelcost_m');
		$this->load->model('Faq_m');
		$this->load->model('Cms_m');
		$this->load->model('Hotel_m');
		$this->load->model('Team_m');
		$this->load->model('Settings_m');
		$this->load->model('Dashboard_m');
		$this->load->model('Seo_m');
		$this->load->model('Planneditinerary_m');
		$this->load->model('Travelblueprint_m');				
		$this->form_validation->set_error_delimiters('<p class="text-red">', '</p>');
		if ($this->User_m->loggedin() == 0) {
			redirect('admin');
		}

	}

}

class Front_Controller extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('text');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Home_fm');
		$this->load->model('Attraction_fm');
		$this->load->model('Cms_fm');
		$this->load->model('Destination_fm');
		$this->load->model('Account_fm');
		$this->load->helper('randomstring');
		$this->load->library('encrypt');
		$this->load->model('Forum_m');
		$this->load->model('Itinerary_fm');
		$this->load->library('facebook');
		$this->load->helper('googlelogin');
		if($this->router->fetch_method()!='rfun' && $this->router->fetch_method()!='fblogin' && !$this->input->is_ajax_request())
		{
			$this->session->set_userdata('previousurl',current_full_url());
		}

	}

}

class User_Controller extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('text');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Home_fm');
		$this->load->model('Attraction_fm');
		$this->load->model('Account_fm');
		$this->load->model('Hotel_fm');
		$this->load->model('Trip_fm');
		$this->load->model('Triphotels_fm');
		$this->load->model('Feedback_fm');
		$this->load->helper('randomstring');
		$this->load->library('encrypt');
		//$this->load->helper('facebooklib_helper');
		$this->load->helper('googlelogin');
		$this->load->library('facebook');


		$this->load->model('Account_fm');
		$data['user']=$this->Account_fm->getUserDetails();
        	$trip_details = $this->Account_fm->getAllTripsofTime($data['user']['id']);

        	$this->data['trip_details'] = $trip_details;
		if ($this->session->userdata('fuserid') == '') {
			if($this->input->is_ajax_request())
			{
				$arr=array('login'=>0);
				echo json_encode($arr);die;
			}
			else
			{
				$this->session->set_userdata('backurl',current_url());
				$this->session->set_flashdata('error','Please login to access that page.');
				redirect('auth');
			}
		}
		else if($this->input->is_ajax_request())
		{
			if(isset($_POST['iti']) && $_POST['iti']!='')
			{
				
				$result=get_invited_trip_details($_POST['iti']);
				if($result!==FALSE){
					$tripdelete=1;
				}
				else
				{
					$tripdelete=checkITIExists($_POST['iti']);
				}
				
				if($tripdelete<1)
				{
					$this->session->set_flashdata('error','You have deleted that trip.');
					$arr=array('tripdelete'=>1);
					echo json_encode($arr);die;
				}
			}
		}

	}

}

?>
