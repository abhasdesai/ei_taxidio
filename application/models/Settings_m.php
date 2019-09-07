<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_m extends CI_Model
{

	function saveContent()
	{
		$data=array(
				'email'=>$_POST['email'],
				'address'=>$_POST['address'],
				'phone_no'=>$_POST['phone_no'],
				'facebook_link'=>$_POST['facebook_link'],
				'twitter_link'=>$_POST['twitter_link'],
				'google_link'=>$_POST['google_link'],
				'linkedin_link'=>$_POST['linkedin_link'],
				'instagram_link'=>$_POST['instagram_link'],
				'pinterest_link'=>$_POST['pinterest_link'],
				'date_modified' => date('Y-m-d h:i:s'),
			); 

		$this->db->where('id',1);
		$this->db->update('tbl_settings',$data);
	}

	
	function getContent()
	{
		$Q=$this->db->query('select * from tbl_settings');
		return $Q->row_array(); 
		
	}

}
