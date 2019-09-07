<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Attraction_m extends CI_Model {

	function add() {
		$data = array(
			'attraction_name' => $_POST['attraction_name'],
			'attraction_rate' => $_POST['attraction_rate'],
			'attraction_days' => $_POST['attraction_days'],
			'attraction_phone' => $_POST['attraction_phone'],
			'attraction_address' => $_POST['attraction_address'],
			'attraction_website' => $_POST['attraction_website'],
			'attraction_sports_yes_no' => $_POST['attraction_sports_yes_no'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_attraction_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['attraction_name'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$this->db->insert('tbl_attraction_master', $data);
	}

	function edit() {
		$data = array(
			'attraction_name' => $_POST['attraction_name'],
			'attraction_rate' => $_POST['attraction_rate'],
			'attraction_days' => $_POST['attraction_days'],
			'attraction_phone' => $_POST['attraction_phone'],
			'attraction_address' => $_POST['attraction_address'],
			'attraction_website' => $_POST['attraction_website'],
			'attraction_sports_yes_no' => $_POST['attraction_sports_yes_no'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_attraction_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['attraction_name'],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_attraction_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_attraction_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_attraction() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_attraction_master', array('id !=' => $_POST['id'], 'attraction_name' => $_POST['attraction_name']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_attraction', $_POST['attraction_name'] . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_attraction_master');
	}
	function addNote($data = array(),$update_flag = '')
	{
		if($update_flag)
		{
			 $this->db->where('id', $update_flag);
			 $this->db->update('tbl_attraction_notes', $data);
			 $return_flag = true;
		}
		else
		{
			$this->db->insert('tbl_attraction_notes', $data);
			$return_flag = $this->db->insert_id();
			//echo $this->db->last_query();die;
		}
        return $return_flag;
	}
	function delNote($data = array())
	{
	 $update_details = array('status'=>0);
	 $this->db->where('userid', $data['userid']);
	 $this->db->where('attractionid', $data['attractionid']);
	 $this->db->where('cityid', $data['cityid']);
	 $return_flag = $this->db->update('tbl_attraction_notes', $update_details);
	 return $return_flag;
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
