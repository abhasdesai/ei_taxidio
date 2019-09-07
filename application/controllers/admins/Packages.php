<?php 
	class Packages extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Packages_m');
			$this->load->helper('security');
		}
		
		public function index()
		{
			$data['webpagename'] = 'Packages';
			$data['main'] = 'admins/adminfiles/Packages/index';
			$data['section'] = 'Travel Packages Management';
			$data['page'] = 'Travel Packages Management';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
		
		public function getTable() {
			$aColumns = array('id', 'package_name', 'package_qty', 'package_price', 'id');

			// DB table to use
			$sTable = 'tbl_travelPackages_mst';
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

				$where = "(budget_hotel_per_night_from like '%" . $this->db->escape_like_str($sSearch) . "%' OR budget_hotel_per_night_to like '%" . $this->db->escape_like_str($sSearch) . "%')";
				$this->db->where($where);

			}

			// Select Data
			$this->db->select("SQL_CALC_FOUND_ROWS id,package_name,package_qty,package_price", FALSE);
			$this->db->from('tbl_travelPackages_mst');
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
		
		public function add()
		{
			if($this->input->post('btnsubmit'))
			{
				$this->form_validation->set_rules('name','Package Name','required|xss_clean|trim|is_unique[tbl_travelPackages_mst.package_name]');
				$this->form_validation->set_rules('qty','Package Quantity','required|xss_clean|numeric');
				$this->form_validation->set_rules('price','Package Price','required|xss_clean|numeric');
				if($this->form_validation->run() === TRUE)
				{
					$this->Packages_m->add();
					$this->session->set_flashdata('success', 'Package Created Successfully');
					redirect('admins/Packages');
				}
			}
			$data['webpagename'] = 'Packages';
			$data['section'] = 'Travel Packages Management';
			$data['page'] = 'Travel Packages Management';
			$data['main'] = 'admins/adminfiles/Packages/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
		public function chk_name($name)
		{
			return $this->Packages_m->chk_name($name);
		}
		
		public function edit($id)
		{
			if($this->input->post('btnsubmit'))
			{
				$this->form_validation->set_rules('name','Package Name','required|xss_clean|trim|callback_chk_name');
				$this->form_validation->set_rules('qty','Package Quantity','required|xss_clean|numeric');
				$this->form_validation->set_rules('price','Package Price','required|xss_clean|numeric');
				if($this->form_validation->run() === TRUE)
				{
					$this->Packages_m->edit();
					$this->session->set_flashdata('success', 'Package Updated Successfully');
					redirect('admins/Packages');
				}
			}
			$data['webpagename'] = 'Packages';
			$data['section'] = 'Travel Packages Management';
			$data['page'] = 'Travel Packages Management';
			$data['id'] = $id;
			$data['package'] = $this->Packages_m->getDetailsById($id);
			$data['main'] = 'admins/adminfiles/Packages/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
		public function delete($id)
		{
			$this->Packages_m->delete($id);
			$this->session->set_flashdata('success',"Package Deleted Successfully");
			redirect('admins/packages');
		}
	}
?>
