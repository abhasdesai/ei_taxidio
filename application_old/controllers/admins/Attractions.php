<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attractions extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	/*function temp1()
	{
		$data=array();
		$Q=$this->db->query('select id,city_id from tbl_city_paidattractions where id not in(select attraction_id from tbl_city_attraction_known_for where attraction_id=tbl_city_paidattractions.id)');
		foreach($Q->result_array() as $row)
		{
			$data[]=$row;
		}
		echo "<pre>";
		print_r($data);die;
	}

	function temp()
	{
		$data=array();
		$Q=$this->db->query('select id,attraction_known_for,city_id from tbl_city_paidattractions');
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $row)
			{
				$ids=explode(',',$row['attraction_known_for']);
				
				$c=$c+count($ids);
				for($i=0;$i<count($ids);$i++)
				{
					if($ids[$i]!='')
					{
						$data[]=$ids[$i];
						$dt=array(
								'tag_id'=>$ids[$i],
								'tag_star'=>0,
								'city_id'=>$row['city_id'],
								'adventure_id'=>$row['id'],
							);

						//$this->db->insert('tbl_city_sportsadventure_known_for',$dt);
					}
				}
			}
			echo "<pre>";print_r($data);die;
		}
	}*/

	public function index() {

		$data['webpagename'] = 'Attractions';
		$data['main'] = 'admins/adminfiles/Attractions/index';
		$data['section'] = 'Attractions';
		$data['page'] = 'Attractions';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'attraction_name', 'attraction_rate', 'attraction_days', 'attraction_address', 'attraction_phone', 'attraction_website', 'id');

		// DB table to use
		$sTable = 'tbl_attraction_master';
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

			$where = "(attraction_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_rate like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_days like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_website like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_phone like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_address like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,attraction_name,attraction_rate,attraction_days,attraction_website,attraction_phone,attraction_address", FALSE);
		$this->db->from('tbl_attraction_master');
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

			$this->form_validation->set_rules('attraction_name', 'Attraction', 'trim|required|max_length[200]|is_unique[tbl_attraction_master.attraction_name]');
			$this->form_validation->set_rules('attraction_rate', 'Attraction Rate', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('attraction_days', 'Attraction Days', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('attraction_phone', 'Attraction Phone', 'trim|max_length[30]');
			$this->form_validation->set_rules('attraction_address', 'Address', 'trim|required|max_length[500]');
			$this->form_validation->set_rules('attraction_website', 'Attraction Website', 'trim|max_length[200]');

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Attractions';
				$data['section'] = 'Add Attraction';
				$data['page'] = 'Attractions';
				$data['main'] = 'admins/adminfiles/Attractions/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Attraction_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Attractions');
			}

		} else {
			$data['webpagename'] = 'Attractions';
			$data['section'] = 'Add Attraction';
			$data['page'] = 'Attractions';
			$data['main'] = 'admins/adminfiles/Attractions/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('attraction_name', 'Attraction', 'trim|required|max_length[200]|callback_check_attraction');
			$this->form_validation->set_rules('attraction_rate', 'Attraction Rate', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('attraction_days', 'Attraction Days', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('attraction_phone', 'Attraction Phone', 'trim|max_length[30]');
			$this->form_validation->set_rules('attraction_address', 'Address', 'trim|required|max_length[500]');
			$this->form_validation->set_rules('attraction_website', 'Attraction Website', 'trim|max_length[200]');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Attractions';
				$data['section'] = 'Edit Attraction';
				$data['page'] = 'Attractions';
				$data['id'] = $id;
				$data['attraction'] = $this->Attraction_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Attractions/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Attraction_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Attractions');
			}

		} else {

			$data['webpagename'] = 'Attractions';
			$data['section'] = 'Edit Attraction';
			$data['page'] = 'Attractions';
			$data['id'] = $id;
			$data['attraction'] = $this->Attraction_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Attractions/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_attraction($attraction_name) {
		return $this->Attraction_m->check_attraction($attraction_name);

	}

	function delete($id) {
		$this->Attraction_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Attractions');

	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Attraction.php */
/* Location: ./application/controllers/admins/Attraction.php */