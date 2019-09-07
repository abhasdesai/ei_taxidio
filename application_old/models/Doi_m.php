<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Doi_m extends CI_Model {

	function add() {
		$data = array(
			'doi_type' => $_POST['doi_type'],
		);

		$this->db->insert('tbl_doi_master', $data);
	}

	function edit() {
		$data = array(
			'doi_type' => $_POST['doi_type'],
		);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_doi_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_doi_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_doi($doi_type) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_doi_master', array('id !=' => $_POST['id'], 'doi_type' => $doi_type));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_doi', $doi_type . ' DOI already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_doi_master');
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