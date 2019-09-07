<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dois extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Dois';
		$data['main'] = 'admins/adminfiles/Dois/index';
		$data['section'] = 'Dois';
		$data['page'] = 'Dois';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'doi_type', 'id');

		// DB table to use
		$sTable = 'tbl_doi_master';
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

			$where = "(doi_type like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,doi_type", FALSE);
		$this->db->from('tbl_doi_master');
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

			$this->form_validation->set_rules('doi_type', 'Doi', 'trim|required|max_length[50]|is_unique[tbl_doi_master.doi_type]');

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Dois';
				$data['section'] = 'Add Doi';
				$data['page'] = 'Dois';
				$data['main'] = 'admins/adminfiles/Dois/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Doi_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Dois');
			}

		} else {
			$data['webpagename'] = 'Dois';
			$data['section'] = 'Add Doi';
			$data['page'] = 'Dois';
			$data['main'] = 'admins/adminfiles/Dois/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('doi_type', 'Doi Type', 'trim|required|max_length[50]|callback_check_doi');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Dois';
				$data['section'] = 'Edit Doi';
				$data['page'] = 'Dois';
				$data['id'] = $id;
				$data['doi'] = $this->Doi_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Dois/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Doi_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Dois');
			}

		} else {

			$data['webpagename'] = 'Dois';
			$data['section'] = 'Edit Doi';
			$data['page'] = 'Dois';
			$data['id'] = $id;
			$data['doi'] = $this->Doi_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Dois/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_doi($doi_type) {
		return $this->Doi_m->check_doi($doi_type);

	}

	function delete($id) {
		$this->Doi_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Dois');

	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Doi.php */
/* Location: ./application/controllers/admins/Doi.php */