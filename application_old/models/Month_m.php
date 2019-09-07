<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Month_m extends CI_Model {

	function add() {
		$data = array(
			'month_name' => $_POST['month_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_month_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['month_name'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$this->db->insert('tbl_month_master', $data);
	}

	function edit() {
		$data = array(
			'month_name' => $_POST['month_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_month_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['month_name'],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_month_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_month_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_month() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_month_master', array('id !=' => $_POST['id'], 'month_name' => $_POST['month_name']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_month', $_POST['month_name'] . ' Month already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_month_master');
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
