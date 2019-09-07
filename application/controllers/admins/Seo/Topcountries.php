<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topcountries extends Admin_Controller {

  	public function __construct()
    {
  		parent::__construct();
  	}

    public function index() {

  		$data['webpagename'] = 'Seo';
  		$data['main'] = 'admins/adminfiles/Seo/Topcountries/index';
  		$data['section'] = 'Top Countries';
  		$data['page'] = 'Top Countries';
  		$this->load->vars($data);
  		$this->load->view('admins/templates/innermaster');
  	}

  	public function getTable() {
  		$aColumns = array('id', 'country_name','sortorder','id');

  		// DB table to use
  		$sTable = 'tbl_seo_cities_countries';
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

  			$where = "(country_name like '%" . $this->db->escape_like_str($sSearch) . "%' or sortorder like '%" . $this->db->escape_like_str($sSearch) . "%')";
  			$this->db->where($where);

  		}

  		// Select Data
  		$this->db->select("SQL_CALC_FOUND_ROWS tbl_seo_cities_countries.id,country_name,sortorder", FALSE);
      $this->db->from('tbl_seo_cities_countries');
      $this->db->join('tbl_country_master','tbl_country_master.id=tbl_seo_cities_countries.country_id');
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

  			$this->form_validation->set_rules('country_id', 'Country', 'trim|required|is_unique[tbl_seo_cities_countries.country_id]');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|max_length[500]');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim|max_length[1500]');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|max_length[5000]');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|max_length[500]|is_unique[tbl_seo_cities_countries.original_title]');

  			if ($this->form_validation->run() == FALSE) {
  				$data['webpagename'] = 'Seo';
  				$data['section'] = 'Add Top Country';
  				$data['page'] = 'Top Countries';
          $data['countries']=$this->Seo_m->getAllCountries();
  				$data['main'] = 'admins/adminfiles/Seo/Topcountries/add';
  				$this->load->vars($data);
  				$this->load->view('admins/templates/innermaster');
  			} else {
  				$this->Seo_m->add($iscity=0);
  				$this->session->set_flashdata('success', 'Transaction Successful.');
  				redirect('admins/Seo/Topcountries');
  			}

  		} else {
  			$data['webpagename'] = 'Seo';
  			$data['section'] = 'Add Top Country';
  			$data['page'] = 'Top Countries';
        $data['countries']=$this->Seo_m->getAllCountries();
  			$data['main'] = 'admins/adminfiles/Seo/Topcountries/add';
  			$this->load->vars($data);
  			$this->load->view('admins/templates/innermaster');
  		}

  	}

  	function edit($id) {
  		if ($this->input->post('btnsubmit')) {

  			$this->form_validation->set_rules('country_id', 'Country', 'trim|required|callback_check_country');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim|max_length[1500]');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|max_length[5000]');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|max_length[500]|callback_check_slug');

  			if ($this->form_validation->run() == FALSE) {
  				$this->session->set_flashdata('error', 'Transaction Failed.');
  				$data['webpagename'] = 'Seo';
  				$data['section'] = 'Edit Top Country';
  				$data['page'] = 'Top Countries';
  				$data['id'] = $id;
          $data['topcountries'] = $this->Seo_m->getDetailsById($id);
          $data['countries']=$this->Seo_m->getAllCountriesEdit($data['topcountries']['country_id']);
  				$data['main'] = 'admins/adminfiles/Seo/Topcountries/edit';
  				$this->load->vars($data);
  				$this->load->view('admins/templates/innermaster');
  			} else {
  				$this->Seo_m->edit($iscity=0);
  				$this->session->set_flashdata('success', 'Transaction Successful.');
  				redirect('admins/Seo/Topcountries');
  			}

  		} else {

  			$data['webpagename'] = 'Seo';
  			$data['section'] = 'Edit Top Country';
  			$data['page'] = 'Top Countries';
  			$data['id'] = $id;
  			$data['topcountries'] = $this->Seo_m->getDetailsById($id);
        $data['countries']=$this->Seo_m->getAllCountriesEdit($data['topcountries']['country_id']);
  			$data['main'] = 'admins/adminfiles/Seo/Topcountries/edit';
  			$this->load->vars($data);
  			$this->load->view('admins/templates/innermaster');
  		}
  	}

  	function check_country($country_id) {
  		return $this->Seo_m->check_country($country_id);

  	}

    function check_slug($slug)
    {
      return $this->Seo_m->check_slug($slug);
    }

  	function delete($id) {
  		$this->Seo_m->delete($id);
  		$this->session->set_flashdata('success', 'Transaction Successful.');
  		redirect('admins/Seo/Topcountries');

  	}

    function updateSortOrder()
      {
  		$this->Seo_m->updateSortOrder();
  	}

}
