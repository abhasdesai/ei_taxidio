<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Continent_m extends CI_Model {

	function add() {
		$data = array(
			'continent_name' => $_POST['continent_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_continent_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['continent_name'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$this->db->insert('tbl_continent_master', $data);
	}

	function edit() {
		$data = array(
			'continent_name' => $_POST['continent_name'],
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_continent_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['continent_name'],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_continent_master', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_continent_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_continent() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_continent_master', array('id !=' => $_POST['id'], 'continent_name' => $_POST['continent_name']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_continent', $_POST['continent_name'] . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {

		$Q=$this->db->query('select id from tbl_continent_countries where continent_id="'.$id.'"');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Continent. This Continent is in use.');
			redirect('admins/Continents');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_continent_master');	
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
