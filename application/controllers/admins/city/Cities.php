<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Cities/index';
		$data['section'] = 'Cities';
		$data['page'] = 'Cities';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'city_name','country_name','id','id','id','id','id','id','id','id','id','id','id');

		// DB table to use
		$sTable = 'tbl_city_master';
		//

		$iDisplayStart = $this->input->get_post('iDisplayStart', true);
		$iDisplayLength = $this->input->get_post('iDisplayLength', true);
		$iSortCol_0 = $this->input->get_post('iSortCol_0', true);
		$iSortingCols = $this->input->get_post('iSortingCols', true);
		$sSearch = $this->input->get_post('sSearch', true);
		$sEcho = $this->input->get_post('sEcho', true);

		// Paging
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
		}

		// Ordering
		if (isset($iSortCol_0)) {
			for ($i = 0; $i < intval($iSortingCols); $i++) {
				$iSortCol = $this->input->get_post('iSortCol_' . $i, true);
				$bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
				$sSortDir = $this->input->get_post('sSortDir_' . $i, true);

				if ($bSortable == 'true') {
					$this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
				}
			}
		}

		/*
			         * Filtering
			         * NOTE this does not match the built-in DataTables filtering which does it
			         * word by word on any field. It's possible to do here, but concerned about efficiency
			         * on very large tables, and MySQL's regex functionality is very limited
		*/
		if (isset($sSearch) && !empty($sSearch)) {

			$where = "(city_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR country_name like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_city_master.id,city_name,country_name", FALSE);
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master','tbl_country_master.id=tbl_city_master.country_id');
		//$this->db->join('tbl_continent_master','tbl_continent_master.id=tbl_city_master.continent_id');
		$rResult = $this->db->get();

		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		$iFilteredTotal = $this->db->get()->row()->found_rows;

		// Total data set length
		$iTotal = $this->db->count_all($sTable);

		// Output
		$output = array(
			'sEcho' => intval($sEcho),
			'iTotalRecords' => $iTotal,
			'iTotalDisplayRecords' => $iFilteredTotal,
			'aaData' => array(),
		);

		foreach ($rResult->result_array() as $aRow) {
			$row = array();

			foreach ($aColumns as $col) {
				$row[] = $aRow[$col];
			}

			$output['aaData'][] = $row;
		}

		echo json_encode($output);
	}

	function add() {

		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('city_name', 'City', 'trim|required|max_length[200]|callback_check_combination');
			$this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[4]|callback_check_code_add');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Cities';
				$data['section'] = 'Add City';
				$data['page'] = 'Cities';
				$data['ratings']=$this->City_m->getAllRatings();
				$data['tags']=$this->City_m->getTags();
				$data['countries']=$this->City_m->getAllCountries();
				$data['budget']=$this->Parameter_m->getBudget();
				$data['accomodation']=$this->Parameter_m->getAccomodation();
				//$data['doi']=$this->Parameter_m->getDOI();
				$data['days']=$this->Parameter_m->getDaysMaster();
				//$data['time']=$this->Parameter_m->getTravellerTime();
				$data['weather']=$this->Parameter_m->getWeather();
				$data['main'] = 'admins/adminfiles/city/Cities/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Cities');
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = 'Add City';
			$data['page'] = 'Cities';
			$data['ratings']=$this->City_m->getAllRatings();
			$data['tags']=$this->City_m->getTags();
			$data['countries']=$this->City_m->getAllCountries();
			$data['budget']=$this->Parameter_m->getBudget();
			$data['accomodation']=$this->Parameter_m->getAccomodation();
			//$data['doi']=$this->Parameter_m->getDOI();
			$data['days']=$this->Parameter_m->getDaysMaster();
			//$data['time']=$this->Parameter_m->getTravellerTime();
			$data['weather']=$this->Parameter_m->getWeather();
			$data['main'] = 'admins/adminfiles/city/Cities/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('city_name', 'City', 'trim|required|max_length[200]|callback_check_city');
			$this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[4]|callback_check_code_edit');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Cities';
				$data['section'] = 'Edit City';
				$data['page'] = 'Cities';
				$data['id'] = $id;
				$data['city'] = $this->City_m->getDetailsById($id);
				$data['tags']=$this->City_m->getTags();
				$data['countries']=$this->City_m->getAllCountries();
				$data['citytags']=$this->City_m->getAllCityTags($id);
				$data['ratings']=$this->City_m->getAllRatings();
				$data['selectedratings']=$this->City_m->getAllSelectedRatings($id);
				$data['budget']=$this->Parameter_m->getBudget();
				$data['accomodation']=$this->Parameter_m->getAccomodation();
				//$data['doi']=$this->Parameter_m->getDOI();
				$data['days']=$this->Parameter_m->getDaysMaster();
				//$data['time']=$this->Parameter_m->getTravellerTime();
				$data['weather']=$this->Parameter_m->getWeather();
				//$data['country']=$this->City_m->getAllCountries($data['city']['continent_id']);
				$data['main'] = 'admins/adminfiles/city/Cities/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Cities');
			}

		} else {

			$data['webpagename'] = 'Cities';
			$data['section'] = 'Edit City';
			$data['page'] = 'Cities';
			$data['id'] = $id;
			$data['city'] = $this->City_m->getDetailsById($id);
			$data['tags']=$this->City_m->getTags();
			$data['citytags']=$this->City_m->getAllCityTags($id);
			$data['ratings']=$this->City_m->getAllRatings();
			$data['selectedratings']=$this->City_m->getAllSelectedRatings($id);
			$data['countries']=$this->City_m->getAllCountries();
			$data['budget']=$this->Parameter_m->getBudget();
			$data['accomodation']=$this->Parameter_m->getAccomodation();
			//$data['doi']=$this->Parameter_m->getDOI();
			$data['days']=$this->Parameter_m->getDaysMaster();
			//$data['time']=$this->Parameter_m->getTravellerTime();
			$data['weather']=$this->Parameter_m->getWeather();
			//$data['country']=$this->City_m->getAllCountries($data['city']['continent_id']);
			//echo "<pre>";print_r($data['country']);die;
			$data['main'] = 'admins/adminfiles/city/Cities/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_code_add($code)
	{
		return $this->City_m->check_code_add($code);
	}

	function check_code_edit($code)
	{
		return $this->City_m->check_code_edit($code);
	}


	function check_city($city_name) {
		return $this->City_m->check_city($city_name);
	}

	function check_combination($city_name)
	{
		return $this->City_m->check_combination($city_name);
	}

	function delete($id) {
		$this->City_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Cities');

	}

	function getCountries()
	{
		$country=$this->City_m->getCountries();
		if(count($country))
		{
			echo "<option value=''>Select Country</option>";
			foreach($country as $list)
			{
				echo "<option value=".$list['id'].">".$list['country_name']."</option>";	
			}
		}
	}


	function events($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Events/index';
		$data['section'] = 'Cities';
		$data['page'] = 'Cities';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getEventsTable($id)
	{
		$aColumns = array('id', 'event_name','event_description','month_name','city_id','id');

		// DB table to use
		$sTable = 'tbl_city_events';
		//

		$iDisplayStart = $this->input->get_post('iDisplayStart', true);
		$iDisplayLength = $this->input->get_post('iDisplayLength', true);
		$iSortCol_0 = $this->input->get_post('iSortCol_0', true);
		$iSortingCols = $this->input->get_post('iSortingCols', true);
		$sSearch = $this->input->get_post('sSearch', true);
		$sEcho = $this->input->get_post('sEcho', true);

		// Paging
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
		}

		// Ordering
		if (isset($iSortCol_0)) {
			for ($i = 0; $i < intval($iSortingCols); $i++) {
				$iSortCol = $this->input->get_post('iSortCol_' . $i, true);
				$bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
				$sSortDir = $this->input->get_post('sSortDir_' . $i, true);

				if ($bSortable == 'true') {
					$this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
				}
			}
		}

		/*
			         * Filtering
			         * NOTE this does not match the built-in DataTables filtering which does it
			         * word by word on any field. It's possible to do here, but concerned about efficiency
			         * on very large tables, and MySQL's regex functionality is very limited
		*/
		if (isset($sSearch) && !empty($sSearch)) {

			$where = "(event_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR event_description like '%" . $this->db->escape_like_str($sSearch) . "%' OR month_name like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_city_events.id,event_name,LEFT(event_description , 150) as event_description,month_name,city_id", FALSE);
		$this->db->from('tbl_city_events');
		$this->db->join('tbl_month_master','tbl_month_master.id=tbl_city_events.month_id');
		$this->db->where('city_id',$id);
		$rResult = $this->db->get();

		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		$iFilteredTotal = $this->db->get()->row()->found_rows;

		// Total data set length
		$iTotal = $this->db->count_all($sTable);

		// Output
		$output = array(
			'sEcho' => intval($sEcho),
			'iTotalRecords' => $iTotal,
			'iTotalDisplayRecords' => $iFilteredTotal,
			'aaData' => array(),
		);

		foreach ($rResult->result_array() as $aRow) {
			$row = array();

			foreach ($aColumns as $col) {
				$row[] = $aRow[$col];
			}

			$output['aaData'][] = $row;
		}

		echo json_encode($output);
	}


	function add_event($id) {

		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('event_name', 'Event Name', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('event_description', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('month_id', 'Month', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['section'] = 'Add City';
				$data['page'] = 'Cities';
				$data['city_id']=$id;
				$data['months']=$this->City_m->getAllMonths();
				$data['main'] = 'admins/adminfiles/city//Events/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->add_event();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Cities/events/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = 'Add City';
			$data['page'] = 'Cities';
			$data['city_id']=$id;
			$data['months']=$this->City_m->getAllMonths();
			$data['main'] = 'admins/adminfiles/city//Events/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit_event($id) {

		if ($this->input->post('btnsubmit')) 
		{

			$this->form_validation->set_rules('event_name', 'Event Name', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('event_description', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('month_id', 'Month', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['section'] = 'Add City';
				$data['page'] = 'Cities';
				$data['city_id']=$id;
				$data['event']=$this->City_m->getEventDetails($id);
				$data['months']=$this->City_m->getAllMonths();
				$data['main'] = 'admins/adminfiles/city//Events/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->edit_event();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Events/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = 'Add City';
			$data['page'] = 'Cities';
			$data['city_id']=$id;
			$data['months']=$this->City_m->getAllMonths();
			$data['event']=$this->City_m->getEventDetails($id);
			$data['main'] = 'admins/adminfiles/city//Events/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function delete_event($id,$city_id) {
		$this->City_m->delete_event($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city//Events/'.$city_id);

	}




	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */