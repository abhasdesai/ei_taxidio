<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Tag_m extends CI_Model {

	function add() {
		$data = array(
			'tag_name' => $_POST['tag_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_tag_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['tag_name'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$this->db->insert('tbl_tag_master', $data);
	}

	function edit() {
		$data = array(
			'tag_name' => $_POST['tag_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_tag_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['tag_name'],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_tag_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_tag_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_tag() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_tag_master', array('id !=' => $_POST['id'], 'tag_name' => $_POST['tag_name']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_tag', $_POST['tag_name'] . ' Tag already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {

		$Q=$this->db->query('select id from tbl_city_tags where tag_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$Q1=$this->db->query('select id from tbl_city_attraction_log where tag_id="'.$id.'" limit 1');
			if($Q1->num_rows()>0)
			{
				$this->session->set_flashdata('error','You can not delete this Tag.This Tag is in use.');
				redirect('admins/Tags');
			}
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_tag_master');	
		}
		
	}

	function updateSortOrder()
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
				$this->db->update('tbl_tag_master',$data);
			}

		}
	

}

/* End of file  */
/* Location: ./application/models/ */
