<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Budget_m extends CI_Model {

	function add() {
		$data = array(
			'budget_hotel_per_night_from' => $_POST['budget_hotel_per_night_from'],
			'budget_hotel_per_night_to' => $_POST['budget_hotel_per_night_to'],
		);
		$this->db->insert('tbl_budget_master', $data);
	}

	function edit() {
		$data = array(
			'budget_hotel_per_night_from' => $_POST['budget_hotel_per_night_from'],
			'budget_hotel_per_night_to' => $_POST['budget_hotel_per_night_to'],
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_budget_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_budget_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_budget($budget_hotel_per_night) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_budget_master', array('id !=' => $_POST['id'], 'budget_hotel_per_night_from' => $budget_hotel_per_night));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_budget', $budget_hotel_per_night . ' as a From price already exists');
			return FALSE;
		}
		return TRUE;
	}

	function check_budget_to($budget_hotel_per_night)
	{
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_budget_master', array('id !=' => $_POST['id'], 'budget_hotel_per_night_to' => $budget_hotel_per_night));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_budget_to', $budget_hotel_per_night . 'as a To Price already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {

		$Q=$this->db->query('select id from tbl_city_master where budget_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Budget.This Budget is in use.');
			redirect('admins/Budgets');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_budget_master');
		}
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