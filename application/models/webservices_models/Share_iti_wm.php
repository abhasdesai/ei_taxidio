<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Share_iti_wm extends CI_Model {
	function get_iti($iti_id = 0) {
		if ($iti_id === 0) {
			$iti = $_POST['iti_id'];
		} else {
			$iti = $iti_id;
		}
		$Q = $this->db->query('select id,user_trip_name,trip_mode,slug from tbl_itineraries where user_id="' . $_POST['userid'] . '" and id="' . $iti . '"');
		if ($Q->num_rows() > 0) {

			return $Q->row_array();
		}
		return FALSE;
	}

	function check_email_for_member($email) {
		$Q = $this->db->query('select id,name from tbl_front_users where email="' . $email . '" and id!="' . $_POST['userid'] . '"');
		/*echo "<pre>";
		print_r($this->db->last_query());die;*/
		if ($Q->num_rows() > 0) {

			return $Q->row_array();
		}
		return FALSE;
	}

	function is_already_share_email($iti, $email) {
		$Q = $this->db->query('select id from tbl_invited_users where user_id=' . $_POST['userid'] . ' and itinerary_id=' . $iti . ' and invited_user_email like "' . $email . '"');
		//echo $this->db->last_query();die();
		if ($Q->num_rows() > 0) {
			//echo "<pre>";print_r($Q->row_array());die;
			return $Q->row_array();
		}
		return FALSE;
	}

	function iti_share_with_guest($data) {
		$message = $this->load->view('myaccount/share-itinerary-with-guest', $data, true);
		$from = 'noreply@taxidio.com'; //$_SESSION['email'];
		$to = trim($_POST['email']);
		$subject = "Check out our trip itinerary!";
		$this->email->from($from, 'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if ($this->email->send()) {
			$iudata = array(
				'type' => 2,
				'user_id' => $_POST['userid'],
				'itinerary_id' => $data['id'],
				'invited_user_email' => $_POST['email'],
			);
			$this->invited_users_entry($iudata, $userid);
			$this->session->set_flashdata('success', 'Your itinerary link has been shared with your travel companion.');
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		$this->session->set_flashdata('error', 'something went wrong.');
		return FALSE;
	}

	function invited_users_entry($data, $userid) {
		$this->db->select('id');
		$this->db->from('tbl_invited_users');
		$this->db->where('type', $data['type']);
		if (!empty($data['itinerary_id'])) {
			$this->db->where('itinerary_id', $data['itinerary_id']);
		}
		$this->db->where('invited_user_email', $data['invited_user_email']);
		$this->db->where('user_id', $userid);
		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			$row = $Q->row_array();
			$iudata = array(
				'type' => $data['type'],
				'user_id' => $data['user_id'],
				'itinerary_id' => $data['itinerary_id'],
				'invited_user_email' => $data['invited_user_email'],
				'created' => date('Y-m-d H:i:s'),
			);
			$this->db->where('id', $row['id']);
			$this->db->update('tbl_invited_users', $iudata);
		} else {
			$iudata = array(
				'type' => $data['type'],
				'user_id' => $data['user_id'],
				'itinerary_id' => $data['itinerary_id'],
				'invited_user_email' => $data['invited_user_email'],
				'created' => date('Y-m-d H:i:s'),
			);

			$this->db->insert('tbl_invited_users', $iudata);
		}
	}

	function is_already_share($iti, $invited_user_id) {
		$Q = $this->db->query('select id from tbl_share_itineraries where user_id="' . $_POST['userid'] . '" and itinerary_id="' . $iti . '" and invited_user_id="' . $invited_user_id . '"');
		if ($Q->num_rows() > 0) {
			return $Q->row_array();
		}
		return FALSE;
	}

	function iti_share_with_member($data) {
		$dt = array(
			'user_id' => $_POST['userid'],
			'invited_user_id' => $data['invited_user_id'],
			'itinerary_id' => $data['iti_id'],
			'created' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('tbl_share_itineraries', $dt);
		$message = $this->load->view('myaccount/share-itinerary-with-member', $data, true);
		$from = 'noreply@taxidio.com'; //$_SESSION['email'];
		$to = trim($_POST['email']);
		$subject = "Check out our trip itinerary!";
		$this->email->from($from, 'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if ($this->email->send()) {
			$this->session->set_flashdata('success', 'Your itinerary link has been shared with your travel companion.');
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		$this->session->set_flashdata('error', 'something went wrong.');
		return FALSE;
	}

	function getInvitedTrips( /*$limit,$start*/) {
		$data = array();
		$this->db->select('tbl_itineraries.id,tbl_itineraries.trip_mode,status,inputs,singlecountry,tbl_itineraries.created,user_trip_name,name,dob,gender,passport,email,phone,trip_type,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as countryname,citiorcountries,cities,tbl_itineraries.country_id,slug,(select count(id) from tbl_itinerary_questions where tbl_itinerary_questions.itinerary_id=tbl_itineraries.id) as totalquestions,userimage,googleid,facebookid,name,tbl_itineraries.user_id,tripname,views,rating');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_front_users', 'tbl_front_users.id=tbl_itineraries.user_id');
		$this->db->join('tbl_share_itineraries', 'tbl_share_itineraries.user_id=tbl_itineraries.user_id');
		$this->db->where('tbl_share_itineraries.itinerary_id=tbl_itineraries.id');
		$this->db->where('tbl_share_itineraries.invited_user_id', $_POST['userid']);
		//$this->db->limit($limit,$start);
		$this->db->order_by('tbl_share_itineraries.id', 'DESC');
		$Q = $this->db->get();

		//echo $this->db->last_query();die;

		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $i => $row) {
				$data[$i] = $row;
			}
		}
		return $data;
	}

	function get_co_travellers($iti_id) {
		$data = array();
		$result = get_invited_trip_details_wm($iti_id);
		$this->db->select('tbl_front_users.id,itinerary_id,name,dob,gender,passport,email,phone');
		$this->db->from('tbl_front_users');
		$this->db->join('tbl_share_itineraries', 'tbl_share_itineraries.invited_user_id=tbl_front_users.id OR tbl_share_itineraries.user_id=tbl_front_users.id');
		if ($result !== FALSE) {
			$this->db->where('tbl_front_users.id!=', $_POST['userid']);
		} else {
			$this->db->where('tbl_share_itineraries.status', 1);
			$this->db->where('tbl_front_users.id!=', $_POST['userid']);
			$this->db->where('tbl_share_itineraries.user_id', $_POST['userid']);
		}
		$this->db->where('tbl_share_itineraries.itinerary_id', $iti_id);

		$this->db->group_by('tbl_front_users.id');
		//$this->db->order_by('tbl_share_itineraries.id', 'DESC');

		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			$data = $Q->result_array();
		}
		return $data;
	}
}
