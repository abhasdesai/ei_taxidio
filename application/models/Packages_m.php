<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Packages_m extends CI_Model {
	
	private $table = "tbl_travelPackages_mst";

	function add() {
		$data = array(
			'package_name' => $_POST['name'],
			'package_qty' => $_POST['qty'],
			'package_price' => $_POST['price'],
			'description' => trim($_POST['description']),
			'status' => $_POST['status'],
			'Created_at' => date('Y-m-d H:i:s'),
			'Updated_at' => date('Y-m-d H:i:s'),
			
		);
		$this->db->insert($this->table, $data);
	}

	function edit() {
		$data = array(
			'package_name' => $_POST['name'],
			'package_qty' => $_POST['qty'],
			'package_price' => $_POST['price'],
			'description' => trim($_POST['description']),
			'status' => $_POST['status'],
			'Created_at' => date('Y-m-d H:i:s'),
			'Updated_at' => date('Y-m-d H:i:s'),
			
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update($this->table, $data);
	}
	
	public function chk_name($name)
	{
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_travelPackages_mst', array('id !=' => $_POST['id'], 'package_name' => $name));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('chk_name', $name . ' package already exists');
			return FALSE;
		}
		return TRUE;
	}
	
	function getDetailsById($id) {
		$Q = $this->db->get_where($this->table, array('id' => $id));
		return $Q->row_array();
	}

	function delete($id) {
		/*Check if the package is in use or not*/
	}
	
	public function getAllOrders()
	{
		$this->db->select('tbl_payment_mst.date as purchsed_date,amount,tbl_payment_mst.id as orderid,T1.package_name,T1.package_qty,T1.package_price,T3.name,T3.email');
		$this->db->from('tbl_payment_mst');
		$this->db->join($this->table .' as T1' ,'T1.id = tbl_payment_mst.package_id');
		$this->db->join('tbl_front_users as T3' ,'T3.id = tbl_payment_mst.user_id');

		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->result();
		}
		return NULL;
	}

}

/* End of file  */
/* Location: ./application/models/ */
