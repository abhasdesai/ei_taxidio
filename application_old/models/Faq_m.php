<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq_m extends CI_Model
{

	function add()
	{
		$data=array(
				'category_id'=>$_POST['category_id'],
				'question'=>$_POST['question'],
				'answer'=>$_POST['answer'],
			); 

		$this->db->insert('tbl_faq',$data);
	}

	function edit()
	{
		$data=array(
				'category_id'=>$_POST['category_id'],
				'question'=>$_POST['question'],
				'answer'=>$_POST['answer'],
			); 

		$this->db->where('id',$this->input->post('id'));
		$this->db->update('tbl_faq',$data);
	}

	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_faq');
		
	}

	function getDetailsById($id)
	{
		$Q=$this->db->query('select * from tbl_faq where id="'.$id.'"');
		return $Q->row_array();
	}


	function check_category_edit($category)
	{
		$Q=$this->db->query('select id from tbl_faq_category where category="'.$category.'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_category_edit',$category.' Category already exist.');
			return FALSE;
		}
		return TRUE;
	}

	function getCategoryDetailsById($id)
	{
		$Q=$this->db->query('select * from tbl_faq_category where id="'.$id.'"');
		return $Q->row_array();	
	}


	function category_add()
	{
		$data=array(
				'category'=>$_POST['category'],
			); 

		$this->db->insert('tbl_faq_category',$data);
	}

	function category_edit()
	{
		$data=array(
				'category'=>$_POST['category'],
			); 

		$this->db->where('id',$_POST['id']);
		$this->db->update('tbl_faq_category',$data);
	}

	function category_delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_faq_category');

		$this->db->where('category_id',$id);
		$this->db->delete('tbl_faq');
	}


	function getAllCategories()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_faq_category order by category ASC');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
		}
		return $data;

	}

}
		