<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Destination_fm extends CI_Model {
	
	function getAllCountry()
	{
		$data=array();
		$Q=$this->db->query('select id,country_name,slug,countryimage from tbl_country_master where id in(select country_id from tbl_city_master where country_id=tbl_country_master.id) order by continent_id ASC,country_name ASC');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
			
		}
		return $data;
	}


	function getCities($country_id)
	{
		$country_array=array();
		$Q=$this->db->query('select id,city_name,slug from tbl_city_master where  country_id="'.$country_id.'" order by city_name ASC');
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
