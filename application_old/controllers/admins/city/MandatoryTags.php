<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MandatoryTags extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'MandatoryTags';
		$data['main'] = 'admins/adminfiles/city/MandatoryTags/index';
		$data['city']=$this->Mandatorytag_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Mandatory Tags';
		$data['page'] = 'Mandatory Tags';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','mandatory_tag','mandatory_dest','mandatory_lat','mandatory_long','city_id','id');

		// DB table to use
		$sTable = 'city_mandatory_tag_master';
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

			$where = "(mandatory_tag like '%" . $this->db->escape_like_str($sSearch) . "%' OR mandatory_dest like '%" . $this->db->escape_like_str($sSearch) . "%' OR mandatory_lat like '%" . $this->db->escape_like_str($sSearch) . "%' OR mandatory_long like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS city_mandatory_tag_master.id,mandatory_tag,mandatory_dest,mandatory_lat,mandatory_long,city_id", FALSE);
		$this->db->from('city_mandatory_tag_master');
		$this->db->join('tbl_mandatory_tags','tbl_mandatory_tags.id=city_mandatory_tag_master.mandatory_tag_id');
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
		$data['city']=$this->Mandatorytag_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('mandatory_tag_id', 'Tag name', 'trim|required|numeric|callback_check_combination_add');
			$this->form_validation->set_rules('mandatory_dest', 'Tag Dest', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('mandatory_lat', 'Tag Time', 'trim|required');
			$this->form_validation->set_rules('mandatory_long', 'Latitude', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['webpagename'] = 'MandatoryTags';
				$data['section'] = $data['city']['city_name'].' ->Mandatory Tags ';
				$data['tags']=$this->Mandatorytag_m->getMandatoryTagsFromMaster();
				$data['page'] = 'Mandatory Tags';
				$data['city_id']=$id;
				$data['main'] = 'admins/adminfiles/city/MandatoryTags/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Mandatorytag_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/MandatoryTags/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'MandatoryTags';
			$data['section'] = $data['city']['city_name'].' ->Mandatory Tags ';
			$data['tags']=$this->Mandatorytag_m->getMandatoryTagsFromMaster();
			$data['page'] = 'Mandatory Tags';
			$data['city_id']=$id;
			$data['main'] = 'admins/adminfiles/city/MandatoryTags/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('mandatory_tag_id', 'Tag name', 'trim|required|numeric|callback_check_combination_edit');
			$this->form_validation->set_rules('mandatory_dest', 'Tag Dest', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('mandatory_lat', 'Tag Time', 'trim|required');
			$this->form_validation->set_rules('mandatory_long', 'Latitude', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'MandatoryTags';
				$data['id']=$id;
				$data['mantag']=$this->Mandatorytag_m->getDetailsById($id);
				$data['city']=$this->Mandatorytag_m->getCityName($data['mantag']['city_id']);
				$data['tags']=$this->Mandatorytag_m->getMandatoryTagsFromMaster();
				$data['section'] = $data['city']['city_name'].' ->Edit Mandatory Tags ';
				$data['page'] = 'Mandatory Tags';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['main'] = 'admins/adminfiles/city/MandatoryTags/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Mandatorytag_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/MandatoryTags/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'MandatoryTags';
			$data['id']=$id;
			$data['mantag']=$this->Mandatorytag_m->getDetailsById($id);
			$data['city']=$this->Mandatorytag_m->getCityName($data['mantag']['city_id']);
			$data['tags']=$this->Mandatorytag_m->getMandatoryTagsFromMaster();
			$data['section'] = $data['city']['city_name'].' ->Edit Mandatory Tags ';
			$data['page'] = 'Mandatory Tags';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['main'] = 'admins/adminfiles/city/MandatoryTags/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Mandatorytag_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/MandatoryTags/index/'.$city_id);

	}


	function check_combination_add($option_id)
	{
		return $this->Mandatorytag_m->check_combination_add($option_id);
	}

	function check_combination_edit($option_id)
	{
		return $this->Mandatorytag_m->check_combination_edit($option_id);
	}
}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */

