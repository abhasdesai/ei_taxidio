<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adventures extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	function index($id)
	{
		$data['webpagename'] = 'Cities';
		$data['main'] = 'admins/adminfiles/city/Adventures/index';
		$data['city']=$this->Adventures_m->getCityName($id);
		if(count($data['city'])<1)
		{
			show_404();
		}
		$data['section'] = $data['city']['city_name'].' -> Sport & Adventures';
		$data['page'] = 'Sport & Adventures';
		$data['id']=$id;
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}


	function getTable($id)
	{
		$aColumns = array('id','adventure_name','adventure_address','adventure_contact','adventure_website','city_id','id');

		// DB table to use
		$sTable = 'tbl_city_sports_adventures';
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

			$where = "(adventure_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR adventure_address like '%" . $this->db->escape_like_str($sSearch) . "%' OR adventure_contact like '%" . $this->db->escape_like_str($sSearch) . "%' OR adventure_website like '%" . $this->db->escape_like_str($sSearch) . "%' )";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,adventure_name,adventure_address,adventure_contact,adventure_website,city_id", FALSE);
		$this->db->from('tbl_city_sports_adventures');
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
		$data['city']=$this->Adventures_m->getCityName($id);
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('adventure_name', 'Adventure', 'trim|required|max_length[300]|callback_check_adventure_add');
			$this->form_validation->set_rules('adventure_details', 'Adventure Details', 'trim');
			$this->form_validation->set_rules('adventure_known_for', 'Adventure Known For', 'trim|max_length[1200]');
			$this->form_validation->set_rules('adventure_address', 'Adventure Address', 'trim|required|max_length[800]');
			$this->form_validation->set_rules('adventure_lat', 'Adventure Latitude', 'trim|required');
			$this->form_validation->set_rules('adventure_long', 'Adventure Longitude', 'trim|required');
			$this->form_validation->set_rules('adventure_contact', 'Adventure Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('adventure_website', 'Adventure Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('adventure_public_transport', 'Adventure Public Transport', 'trim|max_length[500]');
			$this->form_validation->set_rules('adventure_admissionfee', 'Adventure Admisison Fee', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_open_close_timing', 'Adventure Timing', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_wait_time', 'Adventure Waiting Time', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_time_required', 'Adventure Time Required', 'trim|max_length[1000]');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['default']=$this->Adventures_m->getDefaultTag();
				$data['section'] = $data['city']['city_name'].' ->Adventure';
				$data['tags']=$this->City_m->getTags();
				$data['country_id']=$this->Cityattraction_m->getCountryId($id);
				$data['page'] = 'Sport & Adventures';
				$data['city_id']=$id;
				$data['main'] = 'admins/adminfiles/city/Adventures/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Adventures_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Adventures/index/'.$id);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['tags']=$this->City_m->getTags();
			$data['section'] = $data['city']['city_name'].' ->Adventure';
			$data['page'] = 'Sport & Adventures';
			$data['city_id']=$id;
			$data['country_id']=$this->Cityattraction_m->getCountryId($id);
			$data['default']=$this->Adventures_m->getDefaultTag();
			$data['main'] = 'admins/adminfiles/city/Adventures/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function edit($id) {
		
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('adventure_name', 'Adventure', 'trim|required|max_length[300]|callback_check_Adventure_edit');
			$this->form_validation->set_rules('adventure_details', 'Adventure Details', 'trim');
			$this->form_validation->set_rules('adventure_known_for', 'Adventure Known For', 'trim|max_length[1200]');
			$this->form_validation->set_rules('adventure_address', 'Adventure Address', 'trim|required|max_length[800]');
			$this->form_validation->set_rules('adventure_lat', 'Adventure Latitude', 'trim|required');
			$this->form_validation->set_rules('adventure_long', 'Adventure Longitude', 'trim|required');
			$this->form_validation->set_rules('adventure_contact', 'Adventure Contact', 'trim|max_length[100]');
			$this->form_validation->set_rules('adventure_website', 'Adventure Website', 'trim|max_length[300]');
			$this->form_validation->set_rules('adventure_public_transport', 'Adventure Public Transport', 'trim|max_length[500]');
			$this->form_validation->set_rules('adventure_admissionfee', 'Adventure Admisison Fee', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_open_close_timing', 'Adventure Timing', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_wait_time', 'Adventure Waiting Time', 'trim|max_length[1000]');
			$this->form_validation->set_rules('adventure_time_required', 'Adventure Time Required', 'trim|max_length[1000]');
			
			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Cities';
				$data['tags']=$this->City_m->getTags();
				$data['id']=$id;
				$data['adventure']=$this->Adventures_m->getDetailsById($id);
				$data['city']=$this->Adventures_m->getCityName($data['adventure']['city_id']);
				$data['section'] = $data['city']['city_name'].' ->Edit Sport & Adventure';
				$data['page'] = 'Sport & Adventures';
				if(count($data['city'])<1)
				{
					show_404();
				}
				$data['default']=$this->Adventures_m->getDefaultTag();
				$data['main'] = 'admins/adminfiles/city/Adventures/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Adventures_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/city/Adventures/index/'.$_POST['city_id']);
			}

		} else {
			$data['webpagename'] = 'Cities';
			$data['id']=$id;
			$data['tags']=$this->City_m->getTags();
			$data['adventure']=$this->Adventures_m->getDetailsById($id);
			$data['city']=$this->Adventures_m->getCityName($data['adventure']['city_id']);
			$data['section'] = $data['city']['city_name'].' ->Edit Sport & Adventure';
			$data['page'] = 'Sport & Adventures';
			if(count($data['city'])<1)
			{
				show_404();
			}
			$data['default']=$this->Adventures_m->getDefaultTag();
			$data['main'] = 'admins/adminfiles/city/Adventures/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}


	function delete($id,$city_id) {
		$this->Adventures_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/city/Adventures/index/'.$city_id);

	}

	function check_Adventure_add($adventure_name)
	{
		return $this->Adventures_m->check_adventure_add($adventure_name);
	}

	function check_Adventure_edit($Adventure_name)
	{
		return $this->Adventures_m->check_Adventure_edit($Adventure_name);
	}
}

/* End of file City.php */
/* Location: ./application/controllers/admins/City.php */