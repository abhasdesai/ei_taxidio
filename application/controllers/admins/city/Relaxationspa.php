<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relaxationspa extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Relaxationspa/index';
		$data['city']=$this->Relaxationspa_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Relaxation & Spa';
		$data['page'] = 'Relaxation & Spa';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','ras_name','ras_address','ras_contact','ras_website','city_id','id');

		// DB table to use
		$sTable = 'tbl_city_relaxationspa';
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

			$where = "(ras_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR ras_address like '%" . $this->db->escape_like_str($sSearch) . "%' OR ras_contact like '%" . $this->db->escape_like_str($sSearch) . "%' OR ras_website like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,ras_name,ras_address,ras_contact,ras_website,city_id", FALSE);
		$this->db->from('tbl_city_relaxationspa');
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
		$data['city']=$this->Relaxationspa_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('ras_name', 'Relaxation & Spa name', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('ras_address', 'Relaxation & Spa Address', 'trim|required|max_length[800]');
			$this->form_validation->set_rules('ras_lat', 'Relaxation & Spa Latitude', 'trim|required');
			$this->form_validation->set_rules('ras_long', 'Relaxation & Spa Longitude', 'trim|required');
			$this->form_validation->set_rules('ras_contact', 'Relaxation & Spa Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('ras_website', 'Relaxation & Spa Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('ras_timing', 'Relaxation & Spa Public Transport', 'trim|max_length[1000]');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['default']=$this->Relaxationspa_m->getDefaultTag();
				$data['tags']=$this->City_m->getTags();
				$data['section'] = $data['city']['city_name'].' ->Relaxation & Spa ';
				$data['page'] = 'Paid Relaxation & Spa';
				$data['city_id']=$id;
				$data['main'] = 'admins/adminfiles/city/Relaxationspa/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Relaxationspa_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Relaxationspa/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['section'] = $data['city']['city_name'].' ->Relaxation & Spa ';
			$data['page'] = 'Paid Relaxation & Spa';
			$data['tags']=$this->City_m->getTags();
			$data['city_id']=$id;
			$data['default']=$this->Relaxationspa_m->getDefaultTag();
			$data['main'] = 'admins/adminfiles/city/Relaxationspa/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('ras_name', 'Relaxation & Spa name', 'trim|required|max_length[200]');
			$this->form_validation->set_rules('ras_address', 'Relaxation & Spa Address', 'trim|required|max_length[800]');
			$this->form_validation->set_rules('ras_lat', 'Relaxation & Spa Latitude', 'trim|required');
			$this->form_validation->set_rules('ras_long', 'Relaxation & Spa Longitude', 'trim|required');
			$this->form_validation->set_rules('ras_contact', 'Relaxation & Spa Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('ras_website', 'Relaxation & Spa Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('ras_timing', 'Relaxation & Spa Public Transport', 'trim|max_length[1000]');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['id']=$id;
				$data['tags']=$this->City_m->getTags();
				$data['relaxation']=$this->Relaxationspa_m->getDetailsById($id);
				$data['city']=$this->Relaxationspa_m->getCityName($data['relaxation']['city_id']);
				$data['section'] = $data['city']['city_name'].' ->Edit Relaxation & Spa ';
				$data['page'] = 'Paid Relaxation & Spa';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['default']=$this->Relaxationspa_m->getDefaultTag();
				$data['main'] = 'admins/adminfiles/city/Relaxationspa/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Relaxationspa_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Relaxationspa/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['id']=$id;
			$data['relaxation']=$this->Relaxationspa_m->getDetailsById($id);
			$data['city']=$this->Relaxationspa_m->getCityName($data['relaxation']['city_id']);
			$data['section'] = $data['city']['city_name'].' ->Edit Relaxation & Spa ';
			$data['page'] = 'Paid Relaxation & Spa';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['default']=$this->Relaxationspa_m->getDefaultTag();
			$data['tags']=$this->City_m->getTags();
			$data['main'] = 'admins/adminfiles/city/Relaxationspa/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Relaxationspa_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Relaxationspa/index/'.$city_id);

	}
}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */