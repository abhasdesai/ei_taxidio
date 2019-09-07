<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Tags';
		$data['main'] = 'admins/adminfiles/Tags/index';
		$data['section'] = 'Tags';
		$data['page'] = 'Tags';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'tag_name','sortorder','id');

		// DB table to use
		$sTable = 'tbl_tag_master';
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

			$where = "(tag_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR sortorder like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,tag_name,sortorder", FALSE);
		$this->db->from('tbl_tag_master');
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

			$this->form_validation->set_rules('tag_name', 'Tag', 'trim|required|max_length[200]|is_unique[tbl_tag_master.tag_name]');

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Tags';
				$data['section'] = 'Add Tag';
				$data['page'] = 'Tags';
				$data['main'] = 'admins/adminfiles/Tags/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Tag_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Tags');
			}

		} else {
			$data['webpagename'] = 'Tags';
			$data['section'] = 'Add Tag';
			$data['page'] = 'Tags';
			$data['main'] = 'admins/adminfiles/Tags/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('tag_name', 'Tag', 'trim|required|max_length[200]|callback_check_tag');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Tags';
				$data['section'] = 'Edit Tag';
				$data['page'] = 'Tags';
				$data['id'] = $id;
				$data['tag'] = $this->Tag_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Tags/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Tag_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Tags');
			}

		} else {

			$data['webpagename'] = 'Tags';
			$data['section'] = 'Edit Tag';
			$data['page'] = 'Tags';
			$data['id'] = $id;
			$data['tag'] = $this->Tag_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Tags/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_tag($tag_name) {
		return $this->Tag_m->check_tag($tag_name);

	}

	function delete($id) {
		$this->Tag_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Tags');

	}

	
	function updateSortOrder()
    {
		$this->Tag_m->updateSortOrder();
	}
	

}

/* End of file Tag.php */
/* Location: ./application/controllers/admins/Tag.php */