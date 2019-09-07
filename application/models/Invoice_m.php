<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends CI_Model {

		public function __construct()
		{
			parent::__construct();
		}

		public function invoice_detail($payment_id)
		{
			$this->db->select('*');
			$this->db->from('tbl_payment_mst');
			$this->db->join('tbl_travelPackages_mst','tbl_travelPackages_mst.id=tbl_payment_mst.package_id');
			$this->db->where('tbl_payment_mst.id',$payment_id);

			$query = $this->db->get();	
			return $query->row_array();

		}
}
