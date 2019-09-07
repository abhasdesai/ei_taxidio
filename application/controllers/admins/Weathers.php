<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weathers extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Weathers';
		$data['main'] = 'admins/adminfiles/Weathers/index';
		$data['section'] = 'Weathers';
		$data['page'] = 'Weathers';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id', 'weather', 'id');

		// DB table to use
		$sTable = 'tbl_weather_master';
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

			$where = "(weather_temperature_from like '%" . $this->db->escape_like_str($sSearch) . "%' OR weather_temperature_to like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (weather_temperature_to>99999) THEN concat(weather_temperature_from,'+') ELSE concat(weather_temperature_from,' - ',weather_temperature_to) END) as weather", FALSE);
		$this->db->from('tbl_weather_master');
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

			$this->form_validation->set_rules('weather_temperature_from', 'Weather', 'trim|required|max_length[11]|is_unique[tbl_weather_master.weather_temperature_from]');
			if($_POST['weather_temperature_to']=='')
			{
				$this->form_validation->set_rules('weather_temperature_to', 'Weather', 'trim|max_length[11]|callback_check_weather_addto');
			}
			else
			{
				$this->form_validation->set_rules('weather_temperature_to', 'Weather', 'trim|required|max_length[11]|is_unique[tbl_weather_master.weather_temperature_to]');
			}

			if ($this->form_validation->run() == FALSE) {
				$data['webpagename'] = 'Weathers';
				$data['section'] = 'Add Weather';
				$data['page'] = 'Weathers';
				$data['main'] = 'admins/adminfiles/Weathers/add';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Weather_m->add();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Weathers');
			}

		} else {
			$data['webpagename'] = 'Weathers';
			$data['section'] = 'Add Weather';
			$data['page'] = 'Weathers';
			$data['main'] = 'admins/adminfiles/Weathers/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}

	}

	function edit($id) {
		if ($this->input->post('btnsubmit')) {

			$this->form_validation->set_rules('weather_temperature_from', 'Weather', 'trim|required|max_length[100]|callback_check_weather');
			$this->form_validation->set_rules('weather_temperature_to', 'Weather', 'trim|max_length[100]|callback_check_weather_to');


			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Transaction Failed.');
				$data['webpagename'] = 'Weathers';
				$data['section'] = 'Edit Weather';
				$data['page'] = 'Weathers';
				$data['id'] = $id;
				$data['weather'] = $this->Weather_m->getDetailsById($id);
				$data['main'] = 'admins/adminfiles/Weathers/edit';
				$this->load->vars($data);
				$this->load->view('admins/templates/innermaster');
			} else {
				$this->Weather_m->edit();
				$this->session->set_flashdata('success', 'Transaction Successful.');
				redirect('admins/Weathers');
			}

		} else {

			$data['webpagename'] = 'Weathers';
			$data['section'] = 'Edit Weather';
			$data['page'] = 'Weathers';
			$data['id'] = $id;
			$data['weather'] = $this->Weather_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Weathers/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}

	function check_weather($weather_temperature_from) {
		return $this->Weather_m->check_weather($weather_temperature_from);

	}


	function check_weather_to($weather_temperature_to) {
		return $this->Weather_m->check_weather_to($weather_temperature_to);

	}

	function delete($id) {
		$this->Weather_m->delete($id);
		$this->session->set_flashdata('success', 'Transaction Successful.');
		redirect('admins/Weathers');

	}

	function check_weather_addto($weather_temperature_to)
	{
		return $this->Weather_m->weather_addto($weather_temperature_to);	
	}

	/*
			function updateSortOrder()
		    {
				$this->Hotels_m->updateSortOrder();
			}
	*/

}

/* End of file Weather.php */
/* Location: ./application/controllers/admins/Weather.php */