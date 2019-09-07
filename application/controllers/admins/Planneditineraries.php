<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planneditineraries extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Planned itineraries';
		$data['main'] = 'admins/adminfiles/Planneditineraries/index';
		$data['section'] = 'Planned itineraries';
		$data['page'] = 'Planned itineraries';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable()
	{
		$aColumns = array('id','slug','user_trip_name','name','trip_type','trip_mode','created','pid');

		// DB table to use
		$sTable = 'tbl_itineraries';
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

			$where = "(user_trip_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR name like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_itineraries.id,user_trip_name,name,trip_mode,isblock,slug,trip_type,tbl_itineraries.created,CONCAT(trip_mode, '-',sort_planned_iti) as pid", FALSE);
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
		$this->db->where('slug is NOT NULL', NULL, FALSE);
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

	function updateSortOrder()
    {
		$this->Planneditinerary_m->updateSortOrder();
	}
	
	public function delete($id)
	{
			$this->Planneditinerary_m->deleteTrip($id);
			$this->session->set_flashdata('success', 'Transaction Successful.');
			redirect('admins/planneditineraries');

	}

}

/* End of file Continent.php */
/* Location: ./application/controllers/admins/Continent.php */
