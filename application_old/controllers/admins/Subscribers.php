<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribers extends Admin_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['webpagename'] = 'Subscribers';
		$data['main'] = 'admins/adminfiles/Subscribers/index';
		$data['section'] = 'Subscribers';
		$data['page'] = 'Subscribers';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}

	public function getTable() {
		$aColumns = array('id','email','id');

		// DB table to use
		$sTable = 'tbl_subscribers';
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

			$where = "(email like '%" . $this->db->escape_like_str($sSearch) . "%')";
			$this->db->where($where);

		}

		// Select Data
		$this->db->select("SQL_CALC_FOUND_ROWS id,email", FALSE);
		$this->db->from('tbl_subscribers');
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



  function delete($id)
  {
     $Q=$this->db->query('select email from tbl_subscribers where id="'.$id.'" limit 1');
     $data=$Q->row_array();

		 $DB2 = $this->load->database('otherdb', TRUE);
		 $DB2->where('es_email_mail',$data['email']);
		 $DB2->delete('tx_es_emaillist');

		 $this->db->where('id',$id);
     $this->db->delete('tbl_subscribers');

		 $this->session->set_flashdata('success','Subscriber has been deleted.');
     redirect('admins/Subscribers');
  }

	function export_subscribers()
	{

				$Q=$this->db->query('select email from tbl_subscribers order by id desc');
				ob_start();
				echo "No \t Email  \r\n";
				$cnt = 0;

				foreach ($Q->result_array() as $key => $list) {

					$cnt++;

					echo $cnt;
					echo "\t";

					echo $list['email'];
					echo "\t";


				echo "\t\r\n";
				}
				$filename = 'Subscribers.xls';
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename=' . $filename);
				header("Pragma: no-cache");
				header("Expires: 0");
				exit;

	}


}

/* End of file Days Range.php */
/* Location: ./application/controllers/admins/Days Range.php */
