<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hoteltype_m extends CI_Model {

	function add() {
		$data = array(
			'hotel_type' => $_POST['hotel_type'],
		);

		$this->db->insert('tbl_hoteltypes', $data);
	}

	function edit() {
		$data = array(
			'hotel_type' => $_POST['hotel_type'],
		);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_hoteltypes', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_hoteltypes', array('id' => $id));
		return $Q->row_array();
	}

	function check_hoteltype() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_hoteltypes', array('id !=' => $_POST['id'], 'hotel_type' => $_POST['hotel_type']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_hoteltype', $_POST['hotel_type'] . ' Type already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_hoteltypes');
	}

	/*function updateSortOrder()
		{
			$id=explode(',',$_POST['id']);
			$order=explode(',',$_POST['order']);

			$counter=count($id);
			for($i=0;$i<$counter;$i++)
			{
				if($order[$i]==0)
				{
					$order[$i]=999999999;
				}
				$data=array(
					'sortorder'=>$order[$i],
				);

				$this->db->where('id',$id[$i]);
				$this->db->update('tbl_hotels',$data);
			}

		}
	*/

}

/* End of file  */
/* Location: ./application/models/ */
