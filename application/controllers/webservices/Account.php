<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Account_wm');
		$this->load->helper('app');
		//$this->load->library('form_validation');
	}

	function index_post()
	{
		$this->load->library('session');
		$_SESSION['fuserid']=$_POST['userid'];
		$data['trips']=$this->Account_wm->countUserTripsDashboard();
		$data['recenttrips']=$this->Account_wm->getRecentTrips();
		$calendartrip=$this->Account_wm->getCalendarTrips();
		
		foreach ($calendartrip as $key=>$value) {
			$calendartrip[$key]['id']=string_decode(basename($calendartrip[$key]['url']));
			$calendartrip[$key]['start']=date("d-m-Y",strtotime($calendartrip[$key]['start']));
			$calendartrip[$key]['end']=date("d-m-Y",strtotime($calendartrip[$key]['end']));
			unset($calendartrip[$key]['url']);
			$data['calendartrip'][$key]=$calendartrip[$key];
		}
		
		$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		unset($_SESSION['fuserid']);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function save_itinerary_post()
	{
		//echo time();die;
		$type=$_POST['type'];
		if(isset($_POST['uniqueid']))
		{
			$uniqueid=$_POST['uniqueid'];
		}
		$country_id=$_POST['country_id'];
			if($type==1)
			{
				$result=$this->Account_wm->saveSingleIitnerary($uniqueid,$country_id);
				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.',
					'itinerary_id' => $result
					);
				}
			}
			elseif($type==2) 
			{
				$result=$this->Account_wm->saveMultiIitnerary($country_id);

				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.',
					'itinerary_id' => $result
					);
				}
			}
			elseif($type==3) 
			{
				$result=$this->Account_wm->save_searched_itinerary($uniqueid,$country_id);
				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.',
					'itinerary_id' => $result
					);
				}
			}
			else
			{
				$message=array(
				'errorcode' =>0,
				'message'	=>'Something went wrong.'
				);
			}	
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function update_itinerary_post()
	{
		$type=$_POST['type'];
		
		$country_id=$_POST['country_id'];
			if($type==1)
			{
				$result=$this->Account_wm->update_single_itinerary($country_id);
				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.'
					);
				}
			}
			elseif($type==2) 
			{
				$result=$this->Account_wm->update_multi_itinerary($country_id);
				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.'
					);
				}
			}
			elseif($type==3) 
			{
				$result=$this->Account_wm->update_searched_itinerary($country_id);
				if(!$result)
				{
					$message=array(
					'errorcode' =>0,
					'message'	=>'Something went wrong.'
					);
				}
				else
				{
					$message=array(
					'errorcode' =>1,
					'message'	=>'Your trip has been saved.'
					);
				}
			}
			else
			{
				$message=array(
				'errorcode' =>0,
				'message'	=>'Something went wrong.'
				);
			}	
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}

/* End of file Account.php */
/* Location: ./application/controllers/web_services/Account.php */
