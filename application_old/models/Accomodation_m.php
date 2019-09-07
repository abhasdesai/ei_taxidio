<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Accomodation_m extends CI_Model {

	function add() {
		$data = array(
			'accomodation_type' => $_POST['accomodation_type'],
		);
		$this->db->insert('tbl_accomodation_master', $data);
	}

	function edit() {
		$data = array(
			'accomodation_type' => $_POST['accomodation_type'],
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_accomodation_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_accomodation_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_accomodation($accomodation_type) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_accomodation_master', array('id !=' => $_POST['id'], 'accomodation_type' => $accomodation_type));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_accomodation', $accomodation_type . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$Q=$this->db->query('select id from tbl_city_master where accomodation_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Accomodation Type.This Accomodation Type is in use.');
			redirect('admins/Accomodations');
		}
		else
		{

			$this->db->where('id', $id);
			$this->db->delete('tbl_accomodation_master');
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
