<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cityattractions extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Cityattractions/index';
		$data['city']=$this->Cityattraction_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Attractions';
		$data['page'] = 'Attractions';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','ispaid','attraction_name','attraction_address','attraction_contact','attraction_website','city_id','id');

		// DB table to use
		$sTable = 'tbl_city_paidattractions';
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

			$where = "(attraction_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_address like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_contact like '%" . $this->db->escape_like_str($sSearch) . "%' OR attraction_website like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,attraction_name,attraction_address,attraction_contact,attraction_website,city_id,ispaid", FALSE);
		$this->db->from('tbl_city_paidattractions');
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
		$data['city']=$this->Cityattraction_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('attraction_name', 'Attraction', 'trim|required|max_length[300]|callback_check_attraction_add');
			$this->form_validation->set_rules('attraction_details', 'attraction Details', 'trim');
			if($_POST['ispaid']!=2)
			{
			$this->form_validation->set_rules('attraction_address', 'Attraction Address', 'trim|required|max_length[800]');
			}
			$this->form_validation->set_rules('attraction_lat', 'Attraction Latitude', 'trim|required');
			$this->form_validation->set_rules('attraction_long', 'Attraction Longitude', 'trim|required');
			$this->form_validation->set_rules('attraction_contact', 'Attraction Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('attraction_website', 'Attraction Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('attraction_public_transport', 'Attraction Public Transport', 'trim|max_length[500]');
			if($_POST['ispaid']==1)
			{
			$this->form_validation->set_rules('attraction_admissionfee', 'Attraction Admisison Fee', 'trim|max_length[1000]');
			}
			$this->form_validation->set_rules('attraction_timing', 'Attraction Timing', 'trim|max_length[1000]');
			$this->form_validation->set_rules('attraction_wait_time', 'Attraction Waiting Time', 'trim|max_length[1000]');
			$this->form_validation->set_rules('attraction_time_required', 'Attraction Time Required', 'trim|max_length[1000]');
			if($_POST['tag_star']==2)
			{
				$this->form_validation->set_rules('tag_star', 'Tag Star', 'callback_check_two');
			}	

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['section'] = $data['city']['city_name'].' ->Attractions';
				$data['tags']=$this->City_m->getTags();
				$data['page'] = 'Attractions';
				$data['city_id']=$id;
				$data['default']=$this->Cityattraction_m->getDefaultTag();
				$data['check']=$this->Cityattraction_m->checkTagStarValue($id);
				$data['main'] = 'admins/adminfiles/city/Cityattractions/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Cityattraction_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Cityattractions/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = $data['city']['city_name'].' ->Attraction';
			$data['page'] = 'Attractions';
			$data['tags']=$this->City_m->getTags();
			$data['check']=$this->Cityattraction_m->checkTagStarValue($id);
			$data['city_id']=$id;
			$data['default']=$this->Cityattraction_m->getDefaultTag();
			$data['main'] = 'admins/adminfiles/city/Cityattractions/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function check_two()
	{
		$check=$this->Cityattraction_m->check_two();
		return $check;
	}

	function check_two_edit()
	{
		$check=$this->Cityattraction_m->check_two_edit();
		return $check;
	}

	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('attraction_name', 'Attraction', 'trim|required|max_length[300]|callback_check_attraction_edit');
			$this->form_validation->set_rules('attraction_details', 'attraction Details', 'trim');
			$this->form_validation->set_rules('attraction_known_for', 'Attraction Known For', 'trim|max_length[1200]');
			if($_POST['ispaid']!=2)
			{
			$this->form_validation->set_rules('attraction_address', 'Attraction Address', 'trim|required|max_length[800]');
			}
			$this->form_validation->set_rules('attraction_lat', 'Attraction Latitude', 'trim|required');
			$this->form_validation->set_rules('attraction_long', 'Attraction Longitude', 'trim|required');
			$this->form_validation->set_rules('attraction_contact', 'Attraction Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('attraction_website', 'Attraction Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('attraction_public_transport', 'Attraction Public Transport', 'trim|max_length[500]');
			if($_POST['ispaid']==1)
			{
			$this->form_validation->set_rules('attraction_admissionfee', 'Attraction Admisison Fee', 'trim|max_length[1000]');
			}
			$this->form_validation->set_rules('attraction_timing', 'Attraction Timing', 'trim|max_length[1000]');
			$this->form_validation->set_rules('attraction_wait_time', 'Attraction Waiting Time', 'trim|max_length[1000]');
			$this->form_validation->set_rules('attraction_time_required', 'Attraction Time Required', 'trim|max_length[1000]');
			if($_POST['tag_star']==2)
			{
				$this->form_validation->set_rules('tag_star', 'Tag Star', 'callback_check_two_edit');
			}
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['id']=$id;
				$data['tags']=$this->City_m->getTags();
				$data['attraction']=$this->Cityattraction_m->getDetailsById($id);
				$data['default']=$this->Cityattraction_m->getDefaultTag();
				$data['check']=$this->Cityattraction_m->checkTagStarValue($data['attraction']['city_id']);
				$data['city']=$this->Cityattraction_m->getCityName($data['attraction']['city_id']);
				$data['section'] = $data['city']['city_name'].' ->Edit Attraction';
				$data['page'] = 'Attractions';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['main'] = 'admins/adminfiles/city/Cityattractions/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Cityattraction_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Cityattractions/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['id']=$id;
			$data['tags']=$this->City_m->getTags();
			$data['attraction']=$this->Cityattraction_m->getDetailsById($id);
			$data['default']=$this->Cityattraction_m->getDefaultTag();
			$data['check']=$this->Cityattraction_m->checkTagStarValue($data['attraction']['city_id']);
			$data['city']=$this->Cityattraction_m->getCityName($data['attraction']['city_id']);
			$data['section'] = $data['city']['city_name'].' ->Edit Attraction';
			$data['page'] = 'Attractions';
			if(count($data['city'])<1)
			{
				show_404();
			}
			 $data['main'] = 'admins/adminfiles/city/Cityattractions/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Cityattraction_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Cityattractions/index/'.$city_id);

	}

	function check_attraction_add($attraction_name)
	{
		return $this->Cityattraction_m->check_attraction_add($attraction_name);
	}

	function check_attraction_edit($attraction_name)
	{
		return $this->Cityattraction_m->check_attraction_edit($attraction_name);
	}
}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */