<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotels extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		//phpinfo();
	}

	public function index() {

		$data['webpagename'] = 'Hotels';
		$data['main'] = 'admins/adminfiles/Hotels/index';
		$data['section'] = 'Hotels';
		$data['page'] = 'Hotels';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'hotel_name', 'id');

		// DB table to use
		$sTable = 'tbl_hotels';
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

			$where = "(hotel_name like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,hotel_name", FALSE);
		$this->db->from('tbl_hotels');
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

	function add() 
	{
		if ($this->input->post('btnsubmit')) 
		{
			$this->Hotel_m->add();
			$this->session->set_flashdata('success', 'Transaction Successful.');
			redirect('admins/Hotels');
		} 
		else 
		{
			$data['webpagename'] = 'Hotels';
			$data['section'] = 'Add Hotels';
			$data['continents']=$this->Hotel_m->getAllContinents();
			$data['page'] = 'Hotels';
			$data['main'] = 'admins/adminfiles/Hotels/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	
}

/* End of file Budget Per Hotel.php */
/* Location: ./application/controllers/admins/Budget Per Hotel.php */