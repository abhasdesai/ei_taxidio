<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cms_m extends CI_Model
{

	function saveContent()
	{
		$data=array(
				'title'=>$_POST['title'],
				'content'=>$_POST['content'],
			); 

		$this->db->where('md5(id)',$_POST['id']);
		$this->db->update('tbl_cms',$data);
	}

	
	function getContent($id)
	{

		$Q=$this->db->query('select * from tbl_cms where md5(id)="'.$id.'"');
		return $Q->row_array(); 
		
	}

}