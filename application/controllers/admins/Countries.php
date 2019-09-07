<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Countries extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Countries';
		$data['main'] = 'admins/adminfiles/Countries/index';
		$data['section'] = 'Countries';
		$data['page'] = 'Countries';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'country_name','country_capital', 'country_tourist_rating', 'country_no_of_annual_tourists', 'id');

		// DB table to use
		$sTable = 'tbl_country_master';
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

			$where = "(country_name like '%" . $this->db->escape_like_str($sSearch) . "%' OR country_tags like '%" . $this->db->escape_like_str($sSearch) . "%' OR country_capital like '%" . $this->db->escape_like_str($sSearch) . "%' OR country_tourist_rating like '%" . $this->db->escape_like_str($sSearch) . "%' OR country_no_of_annual_tourists like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS tbl_country_master.id,country_name,country_tags,country_tourist_rating,country_capital,country_tourist_rating,country_no_of_annual_tourists,", FALSE);
		$this->db->from('tbl_country_master');
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

			//$this->form_validation->set_rules('continent_id', 'Continent', 'trim|required');
			$this->form_validation->set_rules('country_name', 'Country Name', 'trim|min_length[2]|max_length[100]|callback_check_combination_add');
			$this->form_validation->set_rules('country_capital', 'Country Capital', 'trim|min_length[2]|max_length[100]');
			//$this->form_validation->set_rules('country_neighbours', 'Country Neighbour', 'trim|min_length[2]|max_length[500]');
			$this->form_validation->set_rules('country_currency', 'Country Currency', 'trim|min_length[2]|max_length[40]');
			$this->form_validation->set_rules('timezone', 'Time Zone', 'trim|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('country_economic', 'Country Economic', 'trim');
			$this->form_validation->set_rules('country_history', 'Country History', 'trim');
			$this->form_validation->set_rules('country_national_carrier', 'Country National Career', 'trim|max_length[3000]');
			$this->form_validation->set_rules('country_cultural_identity', 'Country Cultural History', 'trim|max_length[3000]');
			$this->form_validation->set_rules('country_tourist_rating', 'Country Tourist Rating', 'trim|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('country_no_of_annual_tourists', 'Country Annual Tourists', 'trim|max_length[20]|numeric');
			$this->form_validation->set_rules('country_natural_cultural_resources', 'Country Natural Cultural Resources', 'trim|max_length[3000]');
			$this->form_validation->set_rules('country_best_season_visit', 'Country Best Season Visit', 'trim|min_length[1]|max_length[500]');
			//$this->form_validation->set_rules('tag_id', 'Tag', 'trim');
			$this->form_validation->set_rules('latitude', 'Country Latitude', 'trim|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('longitude', 'Country longitude', 'trim|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('country_conclusion', 'Country Conclusion', 'trim');
			$this->form_validation->set_rules('countrycode', 'Country Code', 'trim|callback_check_code_add');


			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Countries';
				$data['webpage'] = 'country_add_edit';
				$data['section'] = 'Add Country';
				$data['page'] = 'Countries';
				$data['continents'] = $this->Country_m->getAllContinents();
				$data['tags']=$this->City_m->getTags();
				$data['main'] = 'admins/adminfiles/Countries/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Country_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Countries');
			}

		} else {
			$data['webpagename'] = 'Countries';
			$data['webpage'] = 'country_add_edit';
			$data['section'] = 'Add Country';
			$data['page'] = 'Countries';
			$data['continents'] = $this->Country_m->getAllContinents();
			$data['tags']=$this->City_m->getTags();
			$data['main'] = 'admins/adminfiles/Countries/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			//$this->form_validation->set_rules('continent_id', 'Continent', 'trim');
			$this->form_validation->set_rules('country_name', 'Country Name', 'trim|min_length[2]|max_length[100]|callback_check_country');
			$this->form_validation->set_rules('country_capital', 'Country Capital', 'trim|min_length[2]|max_length[100]');
			//$this->form_validation->set_rules('country_neighbours', 'Country Neighbour', 'trim|min_length[2]|max_length[500]');
			$this->form_validation->set_rules('country_currency', 'Country Currency', 'trim|min_length[2]|max_length[40]');
			$this->form_validation->set_rules('timezone', 'Time Zone', 'trim|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('country_national_carrier', 'Country National Career', 'trim|max_length[200]');
			$this->form_validation->set_rules('country_economic', 'Country Economic', 'trim|max_length[2000]');
			$this->form_validation->set_rules('country_history', 'Country History', 'trim|max_length[2000]');
			$this->form_validation->set_rules('country_cultural_identity', 'Country Cultural History', 'trim|max_length[3000]');
			$this->form_validation->set_rules('country_tourist_rating', 'Country Tourist Rating', 'trim|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('country_no_of_annual_tourists', 'Country Annual Tourists', 'trim|max_length[20]|numeric');
			$this->form_validation->set_rules('country_natural_cultural_resources', 'Country Natural Cultural Resources', 'trim|max_length[500]');
			$this->form_validation->set_rules('country_best_season_visit', 'Country Best Season Visit', 'trim|min_length[1]|max_length[500]');
			//$this->form_validation->set_rules('tag_id', 'Tag', 'trim');
			$this->form_validation->set_rules('latitude', 'Country Latitude', 'trim|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('longitude', 'Country longitude', 'trim|min_length[1]|max_length[50]');
			$this->form_validation->set_rules('country_conclusion', 'Country Conclusion', 'trim');
			$this->form_validation->set_rules('countrycode', 'Country Code', 'trim|callback_check_code_edit');


			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Countries';
				$data['webpage'] = 'country_add_edit';
				$data['section'] = 'Edit Country';
				$data['page'] = 'Countries';
				$data['id'] = $id;
				$data['tags']=$this->City_m->getTags();
				$data['country'] = $this->Country_m->getDetailsById($id);
				$data['countrytags']=$this->Country_m->getAllCountryTags($id);
				$data['continents'] = $this->Country_m->getAllContinents();
				$data['alternativenames'] = $this->Country_m->getAllAlternativeNames($id);
				$data['main'] = 'admins/adminfiles/Countries/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Country_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Countries');
			}

		} else {

			$data['webpagename'] = 'Countries';
			$data['webpage'] = 'country_add_edit';
			$data['section'] = 'Edit Country';
			$data['page'] = 'Countries';
			$data['id'] = $id;
			$data['tags']=$this->City_m->getTags();
			$data['country'] = $this->Country_m->getDetailsById($id);
			$data['countrytags']=$this->Country_m->getAllCountryTags($id);
			$data['attractions'] = $this->Country_m->getAllAttractionbycountry($id);
			$data['countryattractions'] = $this->Country_m->getAllCountryattractions($id);
			$data['continents'] = $this->Country_m->getAllContinents();
			$data['selectedcontinents'] = $this->Country_m->getAllSelectedContinents($id);
			$data['alternativenames'] = $this->Country_m->getAllAlternativeNames($id);
			$data['main'] = 'admins/adminfiles/Countries/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_code_edit($countrycode)
	{
		return $this->Country_m->check_code_edit($countrycode);
	}

	function check_code_add($countrycode)
	{
		return $this->Country_m->check_code_add($countrycode);
	}

	function check_country($country_name) {
		return $this->Country_m->check_country($country_name);

	}

	function delete($id) {
		$this->Country_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Countries');

	}

	function check_combination_add($country_name)
	{
		return $this->Country_m->check_combination_add($country_name);		
	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Continent.php */
/* Location: ./application/controllers/admins/Continent.php */