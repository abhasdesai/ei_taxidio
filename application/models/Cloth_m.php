<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cloth_m extends CI_Model {

	function saveData() {
	  $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
			
      for($i=0;$i<count($_POST['month_id']);$i++)
      {
      		$Q=$this->db->query('select id from tbl_city_clothes where month_id="'.$_POST['month_id'][$i].'" and city_id="'.$_POST['city_id'].'"');	
      		if($Q->num_rows()>0)
      		{
      			$this->db->where('city_id',$_POST['city_id']);
      			$this->db->where('month_id',$_POST['month_id'][$i]);
      			$this->db->update('tbl_city_clothes',array('clothes'=>$_POST['clothes'][$i]));
      		}
      		else
      		{
      			$this->db->insert('tbl_city_clothes',array('clothes'=>$_POST['clothes'][$i],'month_id'=>$_POST['month_id'][$i],'city_id'=>$_POST['city_id'],'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id']));
      		}
      } 
	}

	function getAllMonths()
	{
		$data=array();
		$Q=$this->db->query('select id,month_name from tbl_month_master order by id asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}	
		return $data;
	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function getMonthWiseClothes($id,$city)
	{
		$Q=$this->db->query('select clothes from tbl_city_clothes where month_id="'.$id.'" and city_id="'.$city.'"');
		return $Q->row_array();
		
	}
}

/* End of file  */
/* Location: ./application/models/ */