<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Days extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Days';
		$data['main'] = 'admins/adminfiles/Days/index';
		$data['section'] = 'Days Range';
		$data['page'] = 'Days Range';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'days_range', 'id');

		// DB table to use
		$sTable = 'tbl_days_master';
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

			$where = "(days_range like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (flag_plus=1) THEN concat(days_range,'+') ELSE days_range END) as days_range", FALSE);
		$this->db->from('tbl_days_master');
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

			$this->form_validation->set_rules('days_range', 'Day Range', 'trim|required|max_length[11]|is_unique[tbl_days_master.days_range]|callback_check_plus');

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Days';
				$data['section'] = 'Add Days Range';
				$data['page'] = 'Days Range';
				$data['main'] = 'admins/adminfiles/Days/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Day_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Days');
			}

		} else {
			$data['webpagename'] = 'Days';
			$data['section'] = 'Add Days Range';
			$data['page'] = 'Days Range';
			$data['main'] = 'admins/adminfiles/Days/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('days_range', 'Days Range', 'trim|required|max_length[11]|callback_check_daysrange');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Days';
				$data['section'] = 'Edit Days Range';
				$data['page'] = 'Days Range';
				$data['id'] = $id;
				$data['range'] = $this->Day_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Days/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Day_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Days');
			}

		} else {

			$data['webpagename'] = 'Days';
			$data['section'] = 'Edit Days Range';
			$data['page'] = 'Days Range';
			$data['id'] = $id;
			$data['range'] = $this->Day_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Days/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_daysrange($days_range) {
		return $this->Day_m->check_daysrange($days_range);

	}

	function delete($id) {
		$this->Day_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Days');

	}

	function check_plus($days_range)
	{
		return $this->Day_m->check_plus($days_range);
	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Days Range.php */
/* Location: ./application/controllers/admins/Days Range.php */