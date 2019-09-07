<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Day_m extends CI_Model {

	function add() {
		$flag_plus=0;
		$days_range=$_POST['days_range'];
		if(substr($_POST['days_range'],-1)=='+')
		{
			$flag_plus=1;
			$days_range=rtrim($_POST['days_range'],'+');
		}
		$data = array(
			'days_range' => $days_range,
			'flag_plus'=>$flag_plus
		);
		$this->db->insert('tbl_days_master', $data);
	}

	function edit() {

		$flag_plus=0;
		$days_range=$_POST['days_range'];
		if(substr($_POST['days_range'],-1)=='+')
		{
			$flag_plus=1;
			$days_range=rtrim($_POST['days_range'],'+');
		}
		$data = array(
			'days_range' => $days_range,
			'flag_plus'=>$flag_plus
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_days_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_days_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_daysrange($days_range) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_days_master', array('id !=' => $_POST['id'], 'days_range' => $days_range));
		if ($Q->num_rows() > 0) 
		{
			return FALSE;
			
		}
		else
		{
			if($this->check_plus_edit($days_range)==FALSE)
			{
				$this->form_validation->set_message('check_daysrange','You can use + only for one value.');
				return FALSE;
			}
			return TRUE;	
		}
		
	}

	function delete($id) {
		$Q=$this->db->query('select id from tbl_city_master where days_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Day.This Day is in use.');
			redirect('admins/Days');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_days_master');
		}
	}

	function check_plus($days_range)
	{
		$lastplus=substr($days_range,-1);
		if($lastplus=='+')
		{
			$Q=$this->db->select('id')
		 			->from('tbl_days_master')
		 			->where('flag_plus',1)
					->get();
			if($Q->num_rows()>0)
			{
				$this->form_validation->set_message('check_plus','You can use + only for one value.');
				return FALSE;
			}
			return TRUE;
		
		}
		return TRUE;		
	}

	function check_plus_edit($days_range)
	{
		$lastplus=substr($days_range,-1);
		if($lastplus=='+')
		{
			$Q=$this->db->select('id')
		 			->from('tbl_days_master')
		 			->where('flag_plus',1)
		 			->where('id !=',$_POST['id'])
					->get();

			if($Q->num_rows()>0)
			{
				return FALSE;
			}
			return TRUE;
			
		}
		return TRUE;		
	}				

}

/* End of file  */
/* Location: ./application/models/ */