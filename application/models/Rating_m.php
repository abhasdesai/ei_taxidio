<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Rating_m extends CI_Model {

	function add() {
		$data = array(
			'rating_type' => $_POST['rating_type'],
		);
		$this->db->insert('tbl_rating_master', $data);
	}

	function edit() {
		$data = array(
			'rating_type' => $_POST['rating_type'],
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_rating_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_rating_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_rating() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_rating_master', array('id !=' => $_POST['id'], 'rating_type' => $_POST['rating_type']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_rating', $_POST['rating_type'] . ' Rating already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {

		$Q=$this->db->query('select id from tbl_city_ratings where rating_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Rating.This Rating is in use.');
			redirect('admins/Ratings');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_rating_master');
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
