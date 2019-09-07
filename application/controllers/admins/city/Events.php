<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Events/index';
		$data['city']=$this->City_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Events';
		$data['page'] = 'Events';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
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


	function add($id) {
		$data['city']=$this->City_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('event_name', 'Event Name', 'trim|required|max_length[300]|callback_check_event_month_add');
			$this->form_validation->set_rules('event_description', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('month_id', 'Month', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['section'] = $data['city']['city_name'].' ->Add Events';
				$data['page'] = 'Events';
				$data['city_id']=$id;
				$data['months']=$this->City_m->getAllMonths();
				$data['main'] = 'admins/adminfiles/city/Events/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->add_event();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Events/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = $data['city']['city_name'].' ->Add Events';
			$data['page'] = 'Events';
			$data['city_id']=$id;
			$data['months']=$this->City_m->getAllMonths();
			$data['main'] = 'admins/adminfiles/city/Events/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		$data['city']=$this->City_m->getCityName($id);
		if ($this->input->post('btnsubmit')) 
		{

			$this->form_validation->set_rules('event_name', 'Event Name', 'trim|required|max_length[300]|callback_check_event_month_edit');
			$this->form_validation->set_rules('event_description', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('month_id', 'Month', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['months']=$this->City_m->getAllMonths();
				$data['event']=$this->City_m->getEventDetails($id);
				$data['city']=$this->Cityattraction_m->getCityName($data['event']['city_id']);
				$data['section'] = $data['city']['city_name'].' ->Edit Event';
				$data['page'] = 'Events';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['main'] = 'admins/adminfiles/city/Events/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->City_m->edit_event();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Events/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['months']=$this->City_m->getAllMonths();
			$data['event']=$this->City_m->getEventDetails($id);
			$data['city']=$this->Cityattraction_m->getCityName($data['event']['city_id']);
			$data['section'] = $data['city']['city_name'].' ->Edit Event';
			$data['page'] = 'Events';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['main'] = 'admins/adminfiles/city/Events/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function delete($id,$city_id) {
		$this->City_m->delete_event($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Events/index/'.$city_id);

	}

	function check_event_month_add($event_name)
	{
		return $this->City_m->check_event_month_add($event_name);
	}

	function check_event_month_edit($event_name)
	{
		return $this->City_m->check_event_month_edit($event_name);
	}



}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */