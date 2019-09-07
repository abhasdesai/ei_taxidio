<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameters extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Parameters/index';
		$data['city']=$this->Parameter_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Parameters';
		$data['page'] = 'Parameter';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','budget_hotel_per_night','accomodation_type','doi_type','days_range','traveler_age','travel_time_slot','id');

		// DB table to use
		$sTable = 'tbl_primary_parameter_master';
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

			$where = "(budget_hotel_per_night like '%" . $this->db->escape_like_str($sSearch) . "%' OR accomodation_type like '%" . $this->db->doi_type($sSearch) . "%' OR days_range like '%" . $this->db->escape_like_str($sSearch) . "%' OR traveler_age like '%" . $this->db->escape_like_str($sSearch) . "%' OR travel_time_slot like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_primary_parameter_master.id,budget_hotel_per_night,accomodation_type,days_range,traveler_age,travel_time_slot,doi_type", FALSE);
		$this->db->from('tbl_primary_parameter_master');
		$this->db->join('tbl_accomodation_master','tbl_accomodation_master.id=tbl_primary_parameter_master.accomodation_id','LEFT');
		$this->db->join('tbl_doi_master','tbl_doi_master.id=tbl_primary_parameter_master.doi_id','LEFT');
		$this->db->join('tbl_days_master','tbl_days_master.id=tbl_primary_parameter_master.days_id','LEFT');
		$this->db->join('tbl_traveler_master','tbl_traveler_master.id=tbl_primary_parameter_master.traveler_id','LEFT');
		$this->db->join('tbl_travel_time_master','tbl_travel_time_master.id=tbl_primary_parameter_master.travel_time_id','LEFT');
		$this->db->join('tbl_budget_master',' tbl_budget_master.id=tbl_primary_parameter_master.budget_id','LEFT');
		$this->db->where('city_id',$id);
		$rResult = $this->db->get();
		// echo $this->db->last_query();die;

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


	function add($id) {
		$data['city']=$this->Parameter_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('budget_id', 'Budget', 'required');
			$this->form_validation->set_rules('accomodation_id', 'Accomodation Type', 'required');
			$this->form_validation->set_rules('doi_id', 'DOI', 'required');
			$this->form_validation->set_rules('days_id', 'Days', 'required');
			$this->form_validation->set_rules('weather_id', 'Weather', 'required');
			$this->form_validation->set_rules('traveler_id', 'Traveller', 'required');
			$this->form_validation->set_rules('travel_time_id', 'Travel Time', 'required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['section'] = $data['city']['city_name'].' ->Parameter ';
				$data['page'] = 'Parameter';
				$data['city_id']=$id;
				$data['budget']=$this->Parameter_m->getBudget();
				$data['accomodation']=$this->Parameter_m->getAccomodation();
				$data['doi']=$this->Parameter_m->getDOI();
				$data['days']=$this->Parameter_m->getDaysMaster();
				$data['age']=$this->Parameter_m->getTravelerAge();
				$data['time']=$this->Parameter_m->getTravellerTime();
				$data['weather']=$this->Parameter_m->getWeather();
				$data['main'] = 'admins/adminfiles/city/Parameters/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Parameter_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Parameters/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = $data['city']['city_name'].' ->Parameter ';
			$data['page'] = 'Parameter';
			$data['city_id']=$id;
			$data['budget']=$this->Parameter_m->getBudget();
			$data['accomodation']=$this->Parameter_m->getAccomodation();
			$data['doi']=$this->Parameter_m->getDOI();
			$data['days']=$this->Parameter_m->getDaysMaster();
			$data['age']=$this->Parameter_m->getTravelerAge();
			$data['time']=$this->Parameter_m->getTravellerTime();
			$data['weather']=$this->Parameter_m->getWeather();
			$data['main'] = 'admins/adminfiles/city/Parameters/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('stadium_name', 'Parameter name', 'trim|required|max_length[250]|callback_check_stadium_edit');
			$this->form_validation->set_rules('stadium_address', 'Parameter Address', 'trim|required|max_length[800]');
			$this->form_validation->set_rules('stadium_lat', 'Parameter Latitude', 'trim|required');
			$this->form_validation->set_rules('stadium_long', 'Parameter Longitude', 'trim|required');
			$this->form_validation->set_rules('stadium_contact', 'Parameter Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('stadium_website', 'Parameter Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('stadium_timing', 'Parameter Public Transport', 'trim|max_length[200]');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['id']=$id;
				$data['Parameter']=$this->Parameter_m->getDetailsById($id);
				$data['city']=$this->Parameter_m->getCityName($data['Parameter']['city_id']);
				$data['section'] = $data['city']['city_name'].' ->Edit Parameter ';
				$data['page'] = 'Parameter';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['main'] = 'admins/adminfiles/city/Parameters/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Parameter_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Parameters/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['id']=$id;
			$data['Parameter']=$this->Parameter_m->getDetailsById($id);
			$data['city']=$this->Parameter_m->getCityName($data['Parameter']['city_id']);
			$data['section'] = $data['city']['city_name'].' ->Edit Parameter ';
			$data['page'] = 'Parameter';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['main'] = 'admins/adminfiles/city/Parameters/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Parameter_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Parameters/index/'.$city_id);

	}

	function check_stadium_add($stadium_name)
	{
		return $this->Parameter_m->check_stadium_add($stadium_name);
	}

	function check_stadium_edit($stadium_name)
	{
		return $this->Parameter_m->check_stadium_edit($stadium_name);
	}


}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */