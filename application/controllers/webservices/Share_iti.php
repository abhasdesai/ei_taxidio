<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . 'libraries/REST_Controller.php';

class Share_iti extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webservices_models/Share_iti_wm');
		$this->load->model('webservices_models/Account_wm');
		$this->load->model('webservices_models/User_wm');
		$this->load->helper('security');
		$this->load->helper('app');
		$this->load->library('form_validation');
	}

	public function share_iti_with_member_post() {
		$email = $this->input->post('email');

		//Get Iti info
		$iti = $this->Share_iti_wm->get_iti();
		$userdetails = $this->User_wm->getUserDetails();

		//echo "<pre>";print_r($iti);die;
		if (empty($iti)) {
			$message = array(
				'errorcode' => 0,
				'message' => 'Something went wrong.',
			);
		} else if ($email == $userdetails['useremail']) {
			$message = array(
				'errorcode' => 0,
				'message' => 'You can not share trip with yourself.',
			);
		} else {
			$this->load->library('email');
			$this->load->library('MY_Email_Other');

			$result = $this->Share_iti_wm->check_email_for_member($email);

			if ($result === FALSE) {
				$result2 = $this->Share_iti_wm->is_already_share_email($iti['id'], $email);
				//var_dump($result2);die();

				if ($result2 === FALSE) {
					$share_result = $this->Share_iti_wm->iti_share_with_guest($iti);
					// var_dump($share_result);
					// die;
					if ($share_result === true) {
						$message = array(
							'errorcode' => 1,
							'message' => 'Your itinerary link has been shared with your travel companion.',
						);
					} else {
						$message = array(
							'errorcode' => 0,
							'message' => 'Something Went Wrong.',
						);
					}
				} else {
					$message = array(
						'errorcode' => 0,
						'message' => 'You have already share this trip with ' . $email . '.',
					);
				}
			} else {
				$result3 = $this->Share_iti_wm->is_already_share($iti['id'], $result['id']);
				if ($result3 === FALSE) {
					$val = array('invited_user_id' => $result['id'], 'iti_id' => $iti['id'], 'name' => $result['name'], 'user_trip_name' => $iti['user_trip_name']);
					$share_result = $this->Share_iti_wm->iti_share_with_member($val);

					if ($share_result === TRUE) {
						$message = array(
							'errorcode' => 1,
							'message' => 'Your itinerary link has been shared with your travel companion.',
						);
					} else {
						$message = array(
							'errorcode' => 0,
							'message' => 'Something went wrong.',
						);
					}
				} else {
					$message = array(
						'errorcode' => 0,
						'message' => 'You have already share this trip with ' . ucwords($result['name']) . '.',
					);
				}
			}
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function invited_trips_post() {
		$trips = $this->Share_iti_wm->getInvitedTrips();

		if (count($trips) > 0) {
			//echo "<pre>";print_r($trips);die;

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

					if (isset($list['user_trip_name']) && $list['user_trip_name'] != '') {
						$tripname_main_name = $list['user_trip_name'];
					} else {
						$tripname_main = $this->Trip_wm->getContinentCountryName($list['country_id']);
						$tripname_main_name = 'Trip ' . $tripname_main['country_name'];
					}
				} else if ($list['trip_type'] == 2) {
					$startdate = $json['start_date'];
					$ttldays = $json['days'] - 1;

					if (isset($list['user_trip_name']) && $list['user_trip_name'] != '') {
						$tripname_main_name = $list['user_trip_name'];
					} else {
						$tripname_main = $this->Trip_wm->getContinentName($list['tripname']);
						$tripname_main_name = 'Trip ' . $tripname_main['country_name'];
					}
				}

				$data[$i]['id'] = ($list['id'] != '') ? $list['id'] : '';
				$data[$i]['status'] = ($list['status'] != '') ? $list['status'] : '';
				$data[$i]['inputs'] = ($list['inputs'] != '') ? $list['inputs'] : '';
				$data[$i]['trip_type'] = ($list['trip_type'] != '') ? $list['trip_type'] : '';
				$data[$i]['tripname'] = ($list['tripname'] != '') ? $list['tripname'] : '';
				$data[$i]['country_id'] = ($list['country_id'] != '') ? $list['country_id'] : '';
				$data[$i]['user_trip_name'] = $tripname_main_name;
				$data[$i]['citiorcountries'] = ($list['citiorcountries'] != '') ? $list['citiorcountries'] : '';
				$data[$i]['slug'] = ($list['slug'] != '') ? $list['slug'] : '';
				$data[$i]['views'] = ($list['views'] != '') ? $list['views'] : '';
				$data[$i]['rating'] = ($list['rating'] != '') ? $list['rating'] : '';
				$data[$i]['countryname'] = ($list['countryname'] != '') ? $list['countryname'] : '';
				$data[$i]['total'] = ($list['totalquestions'] != '') ? $list['totalquestions'] : '';
				$data[$i]['trip_mode'] = ($list['trip_mode'] != '') ? $list['trip_mode'] : '';
				$startdateformat = explode('/', $startdate);
				$startdateymd = $startdateformat[2] . '-' . $startdateformat[1] . '-' . $startdateformat[0];
				$data[$i]['start_date'] = date('d-M-Y', strtotime($startdateymd));
				$data[$i]['end_date'] = date('d-M-Y', strtotime($startdateymd . " + $ttldays days"));
				$co_travellers = $this->Share_iti_wm->get_co_travellers($list['id']);
				if (!empty($co_travellers)) {
					foreach ($co_travellers as $key => $co_traveller) {

						$data[$i]['co_travellers'][$key]['id'] = ($co_traveller['id'] != '') ? $co_traveller['id'] : '';
						$data[$i]['co_travellers'][$key]['itinerary_id'] = ($co_traveller['itinerary_id'] != '') ? $co_traveller['itinerary_id'] : '';
						$data[$i]['co_travellers'][$key]['name'] = ($co_traveller['name'] != '') ? $co_traveller['name'] : '';
						$data[$i]['co_travellers'][$key]['dob'] = ($co_traveller['dob'] != '') ? $co_traveller['dob'] : '';
						$data[$i]['co_travellers'][$key]['gender'] = ($co_traveller['gender'] != '') ? $co_traveller['gender'] : '';
						$data[$i]['co_travellers'][$key]['email'] = ($co_traveller['email'] != '') ? $co_traveller['email'] : '';
						# code...
					}

				} else {
					$data[$i]['co_travellers'] = $co_travellers;
				}

			}

			$message = array(
				'errorcode' => 1,
				'data' => $data,
			);
		} else {
			$message = array(
				'errorcode' => 0,
				'message' => 'Something Went Wrong.',
			);
		}

		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function new_invited_trips_post() {

		$data = $this->Account_wm->newInvitedTrips();
		if ($data > 0) {
			$message = array(
				'errorcode' => 1,
				'message' => 'you have ' . $data . ' new invited trip!',
			);
		} else {
			$message = array(
				'errorcode' => 0,
				'message' => '0',
			);
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function notification_viewed_post() {
		if ($this->Account_wm->notificationViewed()) {
			$message = array(
				'errorcode' => 1,
				'message' => 'success',
			);
		} else {
			$message = array(
				'errorcode' => 1,
				'message' => 'Something Went Wrong',
			);
		}

		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}
