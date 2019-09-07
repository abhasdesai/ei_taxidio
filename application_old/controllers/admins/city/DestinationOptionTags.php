<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DestinationOptionTags extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'DestinationOptionTags';
		$data['main'] = 'admins/adminfiles/city/DestinationOptionTags/index';
		$data['city']=$this->Destinationoptiontag_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Destination/Option Tag';
		$data['page'] = 'Destination/Option Tag';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','tag_type','tag_name','tag_dest','tag_time','city_id','id');

		// DB table to use
		$sTable = 'tbl_city_optionanddestination_tags';
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

			$where = "(tag_type like '%" . $this->db->escape_like_str($sSearch) . "%' OR tag_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR tag_dest like '%" . $this->db->escape_like_str($sSearch) . "%' OR tag_time like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_city_optionanddestination_tags.id,tag_type,tag_name,tag_dest,tag_time,city_id", FALSE);
		$this->db->from('tbl_city_optionanddestination_tags');
		$this->db->join('tbl_tag_master','tbl_tag_master.id=tbl_city_optionanddestination_tags.tag_id');
		$this->db->join('tbl_tag_options','tbl_tag_options.id=tbl_city_optionanddestination_tags.option_id');
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
		$data['city']=$this->Destinationoptiontag_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('tag_id', 'Tag name', 'trim|required|numeric');
			$this->form_validation->set_rules('tag_dest', 'Tag Dest', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('tag_time', 'Tag Time', 'trim|max_length[20]');
			$this->form_validation->set_rules('tag_lat', 'Latitude', 'trim|required|max_length[11]');
			$this->form_validation->set_rules('tag_long', 'Longitude', 'trim|required|max_length[11]');
			$this->form_validation->set_rules('dest_id', 'Dest id', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('option_id', 'Destination/Option Tag Public Transport', 'trim|numeric|required|callback_check_combination_add');

			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'DestinationOptionTags';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['section'] = $data['city']['city_name'].' ->Destination/Option Tag ';
				$data['page'] = 'Paid Destination/Option Tag';
				$data['city_id']=$id;
				$data['tags']=$this->Destinationoptiontag_m->getTags();
				$data['options']=$this->Destinationoptiontag_m->getOptions();
				$data['main'] = 'admins/adminfiles/city/DestinationOptionTags/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Destinationoptiontag_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/DestinationOptionTags/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'DestinationOptionTags';
			$data['section'] = $data['city']['city_name'].' ->Destination/Option Tag ';
			$data['tags']=$this->Destinationoptiontag_m->getTags();
			$data['options']=$this->Destinationoptiontag_m->getOptions();
			$data['page'] = 'Paid Destination/Option Tag';
			$data['city_id']=$id;
			$data['main'] = 'admins/adminfiles/city/DestinationOptionTags/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('tag_id', 'Tag name', 'trim|required|numeric');
			$this->form_validation->set_rules('tag_dest', 'Tag Dest', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('tag_time', 'Tag Time', 'trim|max_length[20]');
			$this->form_validation->set_rules('tag_lat', 'Latitude', 'trim|required|max_length[11]');
			$this->form_validation->set_rules('tag_long', 'Longitude', 'trim|required|max_length[11]');
			$this->form_validation->set_rules('dest_id', 'Dest id', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('option_id', 'Destination/Option Tag Public Transport', 'trim|numeric|required|callback_check_combination_edit');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'DestinationOptionTags';
				$data['id']=$id;
				$data['desoptag']=$this->Destinationoptiontag_m->getDetailsById($id);
				$data['city']=$this->Destinationoptiontag_m->getCityName($data['desoptag']['city_id']);
				$data['tags']=$this->Destinationoptiontag_m->getTags();
				$data['options']=$this->Destinationoptiontag_m->getOptions();
				$data['section'] = $data['city']['city_name'].' ->Edit Destination/Option Tag ';
				$data['page'] = 'Paid Destination/Option Tag';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['main'] = 'admins/adminfiles/city/DestinationOptionTags/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Destinationoptiontag_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/DestinationOptionTags/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'DestinationOptionTags';
			$data['id']=$id;
			$data['desoptag']=$this->Destinationoptiontag_m->getDetailsById($id);
			$data['city']=$this->Destinationoptiontag_m->getCityName($data['desoptag']['city_id']);
			$data['tags']=$this->Destinationoptiontag_m->getTags();
			$data['options']=$this->Destinationoptiontag_m->getOptions();
			$data['section'] = $data['city']['city_name'].' ->Edit Destination/Option Tag ';
			$data['page'] = 'Paid Destination/Option Tag';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['main'] = 'admins/adminfiles/city/DestinationOptionTags/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Destinationoptiontag_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/DestinationOptionTags/index/'.$city_id);

	}


	function check_combination_add($option_id)
	{
		return $this->Destinationoptiontag_m->check_combination_add($option_id);
	}

	function check_combination_edit($option_id)
	{
		return $this->Destinationoptiontag_m->check_combination_edit($option_id);
	}
}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */
