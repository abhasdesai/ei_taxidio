<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Traveltime_m extends CI_Model {

	function add() {

		if($_POST['travel_time_slot_to']=='')
		{
			$travel_time_slot_to=999999;	
		}
		else
		{
			$travel_time_slot_to=$_POST['travel_time_slot_to'];		
		}

		$data = array(
			'travel_time_slot_from' => $_POST['travel_time_slot_from'],
			'travel_time_slot_to' => $travel_time_slot_to,
		);
		$this->db->insert('tbl_travel_time_master', $data);
	}

	function edit() {
		if($_POST['travel_time_slot_to']=='')
		{
			$travel_time_slot_to=999999;	
		}
		else
		{
			$travel_time_slot_to=$_POST['travel_time_slot_to'];		
		}
		
		$data = array(
			'travel_time_slot_from' => $_POST['travel_time_slot_from'],
			'travel_time_slot_to' => $travel_time_slot_to,
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_travel_time_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_travel_time_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_traveltimeslot_from($check_traveltimeslot_from) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_travel_time_master', array('id !=' => $_POST['id'], 'travel_time_slot_from' => $check_traveltimeslot_from));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_traveltimeslot_from', $check_traveltimeslot_from . ' as a from already exists');
			return FALSE;
		}
		return TRUE;
	}

	function check_traveltimeslot_to($check_traveltimeslot_to) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_travel_time_master', array('id !=' => $_POST['id'], 'travel_time_slot_to' => $check_traveltimeslot_to));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_traveltimeslot_to', $check_traveltimeslot_to . ' as a To already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_travel_time_master');
	}

	function check_time_slot_addto($check_time_slot_addto)
	{
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_travel_time_master', array('travel_time_slot_to' => 999999));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_time_slot_addto','Blank Time Slot in To field already exists. You can keep only one To field Blank.');
			return FALSE;
		}
		return TRUE;
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