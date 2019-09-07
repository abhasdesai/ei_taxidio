<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DestinationOptionTag_m extends CI_Model {
	 
	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function add()
	{
		$Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();

		$data=array(
				'tag_id'=>$_POST['tag_id'],
				'option_id'=>$_POST['option_id'],
				'tag_dest'=>$_POST['tag_dest'],
				'tag_time'=>$_POST['tag_time'],
				'tag_lat'=>$_POST['tag_lat'],
				'tag_long'=>$_POST['tag_long'],
				'dest_id'=>$_POST['dest_id'],
				'tag_star'=>$_POST['tag_star'],
				'city_id' => $_POST['city_id'],
				'country_id'=>$citydata['country_id'],
				'continent_id'=>$citydata['continent_id'],
			);

		$this->db->insert('tbl_city_optionanddestination_tags',$data);
	}

	function edit()
	{
		$data=array(
				'tag_id'=>$_POST['tag_id'],
				'option_id'=>$_POST['option_id'],
				'tag_dest'=>$_POST['tag_dest'],
				'tag_time'=>$_POST['tag_time'],
				'tag_lat'=>$_POST['tag_lat'],
				'tag_long'=>$_POST['tag_long'],
				'dest_id'=>$_POST['dest_id'],
				'tag_star'=>$_POST['tag_star'],
			);

		$this->db->where('id',$_POST['id']);
		$this->db->update('tbl_city_optionanddestination_tags',$data);
	}		

	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_city_optionanddestination_tags');
	}

	function getDetailsById($id)
	{
		$Q=$this->db->query('select * from tbl_city_optionanddestination_tags where id="'.$id.'"');
		return $Q->row_array(); 
	}

	function getTags()
	{
		$data=array();
		$Q=$this->db->query('select id,tag_name from tbl_tag_master order by tag_name asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{	
				$data[]=$row;
			}
		}
		return $data;
	}

	function getOptions()
	{
		$data=array();
		$Q=$this->db->query('select id,tag_type from tbl_tag_options');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{	
				$data[]=$row;
			}
		}
		return $data;
	}

	function check_combination_add($option_id)
	{
		$Q=$this->db->query('select id from tbl_city_optionanddestination_tags where option_id="'.$option_id.'" and tag_id="'.$_POST['option_id'].'" and tag_dest="'.$_POST['tag_dest'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_combination_add','This tag name, tag option and tag dest combination is already exists.');
			return FALSE;
		}
		return TRUE;
	}

	function check_combination_edit($option_id)
	{
		$Q=$this->db->query('select id from tbl_city_optionanddestination_tags where option_id="'.$option_id.'" and tag_id="'.$_POST['tag_id'].'" and tag_dest="'.$_POST['tag_dest'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_combination_edit','This tag name, tag option and tag dest combination is already exists.');
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file DestinationOptionTag_m.php */
/* Location: ./application/models/DestinationOptionTag_m.php */
