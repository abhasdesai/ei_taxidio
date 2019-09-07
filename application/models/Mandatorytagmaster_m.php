<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Mandatorytagmaster_m extends CI_Model {

	function add() {
		$data = array(
			'mandatory_tag' => $_POST['mandatory_tag'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_mandatory_tags',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['mandatory_tag'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$this->db->insert('tbl_mandatory_tags', $data);
	}

	function edit() {
		$data = array(
			'mandatory_tag' => $_POST['mandatory_tag'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_mandatory_tags',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['mandatory_tag'],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_mandatory_tags', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_mandatory_tags', array('id' => $id));
		return $Q->row_array();
	}

	function check_tag() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_mandatory_tags', array('id !=' => $_POST['id'], 'mandatory_tag' => $_POST['mandatory_tag']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_tag', $_POST['mandatory_tag'] . ' already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$Q=$this->db->query('select id from city_mandatory_tag_master where mandatory_tag_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Mandatory Tag.This Mandatory Tag is in use.');
			redirect('admins/Mandatorytagmaster');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_mandatory_tags');
		}
	}

	
}

/* End of file  */
/* Location: ./application/models/ */
