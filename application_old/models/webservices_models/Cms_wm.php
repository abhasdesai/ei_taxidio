<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cms_wm extends CI_Model 
{

	function getAllFaqs()
	{
		$data=array();
		$categoryAarry=selectcolbycondition('id,category','tbl_faq_category','');
		if(count($categoryAarry))
		{
			$data = array();
			foreach($categoryAarry as $i=>$arg)
			{
				$data[$i]['category'] = $categoryAarry[$i]['category'];
				$data[$i]['qa'] = selectcolbycondition('id,question,answer','tbl_faq',"category_id='".$arg['id']."'");
			}
		}
		return $data;
	}

	function getCms($id)
	{
		$Q=$this->db->query('select * from tbl_cms where id="'.$id.'"');
		return $Q->row_array();
	}

	function getAllCountry()
	{
		$data=array();
		$Q=$this->db->query('select id,country_name,slug,countryimage from tbl_country_master where id in(select country_id from tbl_city_master where country_id=tbl_country_master.id) order by country_name ASC');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $i=>$row) 
			{
				$data[$i]=$row;
				$data[$i]['cities']=selectcolbycondition('id,city_name,slug','tbl_city_master',"country_id='".$data[$i]['id']."' order by city_name ASC");
			}
			
		}
		return $data;
	}

}
?>