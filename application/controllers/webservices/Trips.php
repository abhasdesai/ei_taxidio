<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require APPPATH . 'libraries/REST_Controller.php';
class Trips extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webservices_models/Trip_wm');
		$this->load->helper('app');
	}

	/*function index_post()
			{
				//$this->load->model('webservices_models/Account_wm');
		   		$pageno=$_POST['pageno'];
				if(empty((trim($pageno))))
				{
					$start_row=0;
				}
				else
				{
					$start_row=($pageno*5)-5;
				}
				$total_rows = $this->Trip_wm->countTrips();

				$data=$trips=$this->Trip_wm->getUserTrips(5,$start_row);
				foreach($trips as $i=>$list){

		            $json=json_decode($list['inputs'],TRUE);
		            $codes=explode('-',$list['citiorcountries']);
		            $tripname_main='';
		            if($list['trip_type']==1 || $list['trip_type']==3)
		            {
		                if($list['trip_type']==1)
		                {
		                    $startdate=$json['start_date'];
		                    $ttldays=$json['days']-1;
		                }
		                else if($list['trip_type']==3)
		                {
		                    $startdate=$json['sstart_date'];
		                    $ttldays=$json['sdays']-1;
		                }
		            }
		            else if($list['trip_type']==2)
		            {
		                $startdate=$json['start_date'];
		                $ttldays=$json['days']-1;
		            }

		             $startdateformat=explode('/',$startdate);
		             $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];
		             $data[$i]['start_date']=date('d-M-Y',strtotime($startdateymd));
		             $data[$i]['end_date']=date('d-M-Y', strtotime($startdateymd. " + $ttldays days"));
				}

				$message=array(
					'errorcode' =>1,
					'total_page'=>ceil($total_rows/5),
					'data'	=>$data
					);
				$this->set_response($message, REST_Controller::HTTP_OK);
	*/

	function index_post() {
		//$this->load->model('webservices_models/Account_wm');

		$data = $trips = $this->Trip_wm->getUserTrips();

		$currenttrips = array();
		$upcommingtrips = array();
		$completedtrips = array();

		foreach ($trips as $i => $list) {

			$json = json_decode($list['inputs'], TRUE);
			$codes = explode('-', $list['citiorcountries']);
			$tripname_main = '';
			if ($list['trip_type'] == 1 || $list['trip_type'] == 3) {
				if ($list['trip_type'] == 1) {
					$startdate = $json['start_date'];
					$ttldays = $json['days'] - 1;
				} else if ($list['trip_type'] == 3) {
					$startdate = $json['sstart_date'];
					$ttldays = $json['sdays'] - 1;
				}
			} else if ($list['trip_type'] == 2) {
				$startdate = $json['start_date'];
				$ttldays = $json['days'] - 1;
			}
			$this->load->model('webservices_models/Share_iti_wm');
			$startdateformat = explode('/', $startdate);
			$startdateymd = $startdateformat[2] . '-' . $startdateformat[1] . '-' . $startdateformat[0];
			$data[$i]['start_date'] = date('d-M-Y', strtotime($startdateymd));
			$data[$i]['end_date'] = date('d-M-Y', strtotime($startdateymd . " + $ttldays days"));
			if (strtotime($startdateymd) <= time() && strtotime($startdateymd . " + $ttldays days") >= time()) {
				$currenttrips[$i] = $data[$i];

				$co_travellers = $this->Share_iti_wm->get_co_travellers($data[$i]['id']);
				if (!empty($co_travellers)) {

					foreach ($co_travellers as $key => $co_traveller) {

						$currenttrips[$i]['co_travellers'][$key]['id'] = ($co_traveller['id'] != '') ? $co_traveller['id'] : '';
						$currenttrips[$i]['co_travellers'][$key]['itinerary_id'] = ($co_traveller['itinerary_id'] != '') ? $co_traveller['itinerary_id'] : '';
						$currenttrips[$i]['co_travellers'][$key]['name'] = ($co_traveller['name'] != '') ? $co_traveller['name'] : '';
						$currenttrips[$i]['co_travellers'][$key]['dob'] = ($co_traveller['dob'] != '') ? $co_traveller['dob'] : '';
						$currenttrips[$i]['co_travellers'][$key]['gender'] = ($co_traveller['gender'] != '') ? $co_traveller['gender'] : '';
						$currenttrips[$i]['co_travellers'][$key]['email'] = ($co_traveller['email'] != '') ? $co_traveller['email'] : '';
						# code...
					}
				} else {
					$currenttrips[$i]['co_traveller'] = $co_travellers;
				}
			} else if (strtotime($startdateymd) > time()) {
				$upcommingtrips[$i] = $data[$i];
				//$upcommingtrips[$i]['co_traveller'] = $data[$i]['id'];

				$co_travellers = $this->Share_iti_wm->get_co_travellers($data[$i]['id']);
				if (!empty($co_travellers)) {

					foreach ($co_travellers as $key => $co_traveller) {

						$upcommingtrips[$i]['co_travellers'][$key]['id'] = ($co_traveller['id'] != '') ? $co_traveller['id'] : '';
						$upcommingtrips[$i]['co_travellers'][$key]['itinerary_id'] = ($co_traveller['itinerary_id'] != '') ? $co_traveller['itinerary_id'] : '';
						$upcommingtrips[$i]['co_travellers'][$key]['name'] = ($co_traveller['name'] != '') ? $co_traveller['name'] : '';
						$upcommingtrips[$i]['co_travellers'][$key]['dob'] = ($co_traveller['dob'] != '') ? $co_traveller['dob'] : '';
						$upcommingtrips[$i]['co_travellers'][$key]['gender'] = ($co_traveller['gender'] != '') ? $co_traveller['gender'] : '';
						$upcommingtrips[$i]['co_travellers'][$key]['email'] = ($co_traveller['email'] != '') ? $co_traveller['email'] : '';
						# code...
					}
				} else {
					$upcommingtrips[$i]['co_traveller'] = $co_travellers;
				}

			} else if (strtotime($startdateymd . " + $ttldays days") < time()) {
				$completedtrips[$i] = $data[$i];

				$co_travellers = $this->Share_iti_wm->get_co_travellers($data[$i]['id']);
				if (!empty($co_travellers)) {

					foreach ($co_travellers as $key => $co_traveller) {

						$completedtrips[$i]['co_travellers'][$key]['id'] = ($co_traveller['id'] != '') ? $co_traveller['id'] : '';
						$completedtrips[$i]['co_travellers'][$key]['itinerary_id'] = ($co_traveller['itinerary_id'] != '') ? $co_traveller['itinerary_id'] : '';
						$completedtrips[$i]['co_travellers'][$key]['name'] = ($co_traveller['name'] != '') ? $co_traveller['name'] : '';
						$completedtrips[$i]['co_travellers'][$key]['dob'] = ($co_traveller['dob'] != '') ? $co_traveller['dob'] : '';
						$completedtrips[$i]['co_travellers'][$key]['gender'] = ($co_traveller['gender'] != '') ? $co_traveller['gender'] : '';
						$completedtrips[$i]['co_travellers'][$key]['email'] = ($co_traveller['email'] != '') ? $co_traveller['email'] : '';
						# code...
					}
				} else {
					$completedtrips[$i]['co_traveller'] = $co_travellers;
				}
			}
		}

		$message = array(
			'errorcode' => 1,
			'data' => array(
				'currenttrips' => array_values($currenttrips),
				'upcommingtrips' => array_values($upcommingtrips),
				'completedtrips' => array_values($completedtrips),
			),
		);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function getTrip_post() {
		$data = $trips = $this->Trip_wm->getUserTrip();
		if ($data) {
			$message = array(
				'errorcode' => 1,
				'data' => $data,
			);
		} else {
			$message = array(
				'errorcode' => 0,
				'message' => 'This trip does not exist.',
			);
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function updateTrip_post() {

		$data = $this->Trip_wm->updateTrip();
		//writeTripsInFile();
		if ($data == 1) {
			$message = array(
				'errorcode' => 1,
				'message' => 'Your trip has been updated.',
			);
		} else {
			$message = array(
				'errorcode' => 0,
				'message' => 'Oopp..!Something went wrong..',
			);
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function deleteTrip_post() {
		$this->load->library('session');
		$_SESSION['fuserid'] = $_POST['userid'];
		$data = $this->Trip_wm->deleteTrip($_POST['itirnaryid']);
		//writeTripsInFile();
		if ($data != 1) {
			$message = array(
				'errorcode' => 0,
				'message' => 'Oopp..!Something went wrong..',
			);
		} else {
			$message = array(
				'errorcode' => 1,
				'message' => 'Your trip has been deleted.',
			);
		}
		unset($_SESSION['fuserid']);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}

?>
