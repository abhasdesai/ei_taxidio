<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budgets extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Budgets';
		$data['main'] = 'admins/adminfiles/Budgets/index';
		$data['section'] = 'Hotel Budget Per night';
		$data['page'] = 'Hotel Budget Per night';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'budget', 'id');

		// DB table to use
		$sTable = 'tbl_budget_master';
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

			$where = "(budget_hotel_per_night_from like '%" . $this->db->escape_like_str($sSearch) . "%' OR budget_hotel_per_night_to like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (budget_hotel_per_night_to <1) THEN concat(budget_hotel_per_night_from,'+') ELSE concat(budget_hotel_per_night_from,' - ',budget_hotel_per_night_to) END) as budget", FALSE);
		$this->db->from('tbl_budget_master');
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

			$this->form_validation->set_rules('budget_hotel_per_night_from', 'From', 'trim|required|max_length[11]|numeric|is_unique[tbl_budget_master.budget_hotel_per_night_from]');
			$this->form_validation->set_rules('budget_hotel_per_night_to', 'To', 'trim|required|max_length[11]|numeric|is_unique[tbl_budget_master.budget_hotel_per_night_to]');

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Budgets';
				$data['section'] = 'Add Hotel Budget Per night';
				$data['page'] = 'Hotel Budget Per night';
				$data['main'] = 'admins/adminfiles/Budgets/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Budget_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Budgets');
			}

		} else {
			$data['webpagename'] = 'Budgets';
			$data['section'] = 'Add Hotel Budget Per night';
			$data['page'] = 'Hotel Budget Per night';
			$data['main'] = 'admins/adminfiles/Budgets/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('budget_hotel_per_night_from', 'From', 'trim|required|max_length[11]|numeric|callback_check_budget');
			$this->form_validation->set_rules('budget_hotel_per_night_to', 'To', 'trim|required|max_length[11]|numeric|callback_check_budget_to');


			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Budgets';
				$data['section'] = 'Edit Hotel Budget Per night';
				$data['page'] = 'Budgets';
				$data['id'] = $id;
				$data['budget'] = $this->Budget_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Budgets/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Budget_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Budgets');
			}

		} else {

			$data['webpagename'] = 'Budgets';
			$data['section'] = 'Edit Hotel Budget Per night';
			$data['page'] = 'Budgets';
			$data['id'] = $id;
			$data['budget'] = $this->Budget_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Budgets/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_budget($budget_hotel_per_night_from) {
		return $this->Budget_m->check_budget($budget_hotel_per_night_from);

	}

	function check_budget_to($budget_hotel_per_night_to) {
		return $this->Budget_m->check_budget_to($budget_hotel_per_night_to);

	}


	function delete($id) {
		$this->Budget_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Budgets');

	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Budget Per Hotel.php */
/* Location: ./application/controllers/admins/Budget Per Hotel.php */