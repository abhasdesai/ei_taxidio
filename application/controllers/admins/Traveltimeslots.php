<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traveltimeslots extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Traveltimeslots';
		$data['main'] = 'admins/adminfiles/Traveltimeslots/index';
		$data['section'] = 'Travel Time Slots';
		$data['page'] = 'Travel Time Slots';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'timeslot', 'id');

		// DB table to use
		$sTable = 'tbl_travel_time_master';
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

			$where = "(travel_time_slot_from like '%" . $this->db->escape_like_str($sSearch) . "%' OR travel_time_slot_to like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (travel_time_slot_to>99999) THEN concat(travel_time_slot_from,'+') ELSE concat(travel_time_slot_from,' - ',travel_time_slot_to) END) as timeslot", FALSE);
		$this->db->from('tbl_travel_time_master');
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

			$this->form_validation->set_rules('travel_time_slot_from', 'From', 'trim|required|max_length[50]|is_unique[tbl_travel_time_master.travel_time_slot_from]');
			if($_POST['travel_time_slot_to']=='')
			{
				$this->form_validation->set_rules('travel_time_slot_to', 'To', 'trim|max_length[11]|numeric|callback_check_time_slot_addto');
			}
			else
			{
				$this->form_validation->set_rules('travel_time_slot_to', 'To', 'trim|required|max_length[11]|numeric|is_unique[tbl_weather_master.weather_temperature_to]');
			}

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Traveltimeslots';
				$data['section'] = 'Add Travel Time Slot';
				$data['page'] = 'Travel Time Slot';
				$data['main'] = 'admins/adminfiles/Traveltimeslots/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Traveltime_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Traveltimeslots');
			}

		} else {
			$data['webpagename'] = 'Traveltimeslots';
			$data['section'] = 'Add Travel Time Slot';
			$data['page'] = 'Travel Time Slot';
			$data['main'] = 'admins/adminfiles/Traveltimeslots/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('travel_time_slot_from', 'From', 'trim|required|max_length[11]|numeric|callback_check_traveltimeslot_from');
			$this->form_validation->set_rules('travel_time_slot_to', 'To', 'trim|max_length[50]|callback_check_traveltimeslot_to');


			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Traveltimeslots';
				$data['section'] = 'Edit Travel Time Slot';
				$data['page'] = 'Travel Time Slot';
				$data['id'] = $id;
				$data['timeslot'] = $this->Traveltime_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Traveltimeslots/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Traveltime_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Traveltimeslots');
			}

		} else {

			$data['webpagename'] = 'Traveltimeslots';
			$data['section'] = 'Edit Travel Time Slot';
			$data['page'] = 'Travel Time Slot';
			$data['id'] = $id;
			$data['timeslot'] = $this->Traveltime_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Traveltimeslots/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_traveltimeslot_from($travel_time_slot_from) {
		return $this->Traveltime_m->check_traveltimeslot_from($travel_time_slot_from);

	}

	function check_traveltimeslot_to($travel_time_slot_to) {
		return $this->Traveltime_m->check_traveltimeslot_to($travel_time_slot_to);

	}

	function check_time_slot_addto($travel_time_slot_to)
	{
		return $this->Traveltime_m->check_time_slot_addto($travel_time_slot_to);
	}

	function delete($id) {
		$this->Traveltime_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Traveltimeslots');

	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Travel Time Slot.php */
/* Location: ./application/controllers/admins/Travel Time Slot.php */