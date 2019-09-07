<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Team_m extends CI_Model {

	function add() 
	{
		$data = array(
			'person' => $_POST['person'],
			'designation' => $_POST['designation'],
			'details' => $_POST['details'],	 
			'facebook' => $_POST['facebook'],
			'linkedin' => $_POST['linkedin'],
			'twitter' => $_POST['twitter'],
			'instagram' => $_POST['instagram'],
			'sortorder' => $_POST['sortorder'],
		);

		$flag1 = true;
		$errormsg = "";
		if ($_FILES['image']['name'] != "") {
			$config['upload_path'] = './userfiles/team/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png';
			$config['max_size'] = '0';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width'] = '';
			$config['max_height'] = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('image')) {
				$flag1 = false;
				$error = array('warning' => $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/Team/add/');
			} 
			else 
			{
				$image = $this->upload->data();
				list($width,$height)=getimagesize(FCPATH.'/userfiles/team/'.$image['file_name']);

				if($width<360 || $height<400)
				{
					$flag1 = false;
					$this->session->set_flashdata('error','image Size must be as specified.');
					unlink(FCPATH.'userfiles/team/'.$image['file_name']);
					redirect('admins/Team/add');

				}
				else
				{
					$flag1 = true;
					if ($image['file_name']) 
					{
						$data['image'] = $image['file_name'];
					}
				}
			}
		}

		if($flag1==true)
		{
			$this->db->insert('tbl_team', $data);	
		}
		
	}

	function edit() 
	{
		$data = array(
			'person' => $_POST['person'],
			'designation' => $_POST['designation'],
			'details' => $_POST['details'],	 
			'facebook' => $_POST['facebook'],
			'linkedin' => $_POST['linkedin'],
			'twitter' => $_POST['twitter'],
			'instagram' => $_POST['instagram'],
			'sortorder' => $_POST['sortorder'],
		);

		$flag1 = true;
		$errormsg = "";
		if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
			$config['upload_path'] = './userfiles/team/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png';
			$config['max_size'] = '0';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width'] = '';
			$config['max_height'] = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('image')) {
				$flag1 = false;
				$error = array('warning' => $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/Team/add/');
			} 
			else 
			{
				$image = $this->upload->data();
				list($width,$height)=getimagesize(FCPATH.'/userfiles/team/'.$image['file_name']);

				if($width<360 || $height<400)
				{
					$flag1 = false;
					$this->session->set_flashdata('error','image Size must be as specified.');
					unlink(FCPATH.'userfiles/team/'.$image['file_name']);
					redirect('admins/Team/edit/'.$_POST['id']);

				}
				else
				{
					$flag1 = true;
					if ($image['file_name']) 
					{
						$data['image'] = $image['file_name'];
					}
					$this->removeImage($_POST['id']);
				}
			}
		}

		if($flag1==true)
		{
			$this->db->where('id',$_POST['id']);
			$this->db->update('tbl_team', $data);	
		}
	}

	function getDetailsById($id) 
	{
		$Q = $this->db->get_where('tbl_team', array('id' => $id));
		return $Q->row_array();
	}

	
	function delete($id) {

		$this->removeImage($id);
		$this->db->where('id', $id);
		$this->db->delete('tbl_team');
	}

	function removeImage($id)
	{
		$Q=$this->db->query('select image from tbl_team where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['image']!='')
		{
			unlink(FCPATH.'userfiles/team/'.$data['image']);
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
				$order[$i]=99999;
			}
			$data=array(
				'sortorder'=>$order[$i],
			);

			$this->db->where('id',$id[$i]);
			$this->db->update('tbl_team',$data);
		}

	}
	

}

/* End of file  */
/* Location: ./application/models/ */
