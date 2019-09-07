<?php
class Report_m extends CI_Model {

	function getAllCities()
	{
		$data=array();
		$this->db->select('tbl_city_master.id,city_name,country_name,continent_name');
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master','tbl_country_master.id=tbl_city_master.country_id');
		$this->db->join('tbl_continent_master','tbl_continent_master.id=tbl_city_master.continent_id');
		//$this->db->order_by('');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		foreach ( $data as $row ) 
      	{
	        $continent_name = trim($row['continent_name']);
	        $country_name = trim($row['country_name']);
	        $new_structure[ $continent_name ][ $country_name ][] = $row;
   		}

   		//echo "<pre>";
   		//print_r($new_structure);die;
   		return $new_structure;
		
	}

	function getCityReport()
	{
		$data=array();
		$this->db->select('tbl_city_master.*,tbl_country_master.country_name,tbl_budget_master.*,tbl_accomodation_master.*,tbl_doi_master.*,tbl_days_master.*,tbl_weather_master.*,tbl_travel_time_master.*,tbl_continent_master.continent_name');
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master','tbl_country_master.id=tbl_city_master.country_id');
		$this->db->join('tbl_continent_master','tbl_continent_master.id=tbl_city_master.continent_id');
		$this->db->join('tbl_budget_master','tbl_budget_master.id=tbl_city_master.budget_id');
		$this->db->join('tbl_accomodation_master','tbl_accomodation_master.id=tbl_city_master.accomodation_id');
		$this->db->join('tbl_doi_master','tbl_doi_master.id=tbl_city_master.doi_id');
		$this->db->join('tbl_days_master','tbl_days_master.id=tbl_city_master.days_id');
		$this->db->join('tbl_weather_master','tbl_weather_master.id=tbl_city_master.weather_id');
		$this->db->join('tbl_travel_time_master','tbl_travel_time_master.id=tbl_city_master.travel_time_id');
		$this->db->where('tbl_city_master.id',$_POST['city_id']);
		$this->db->limit(1);
		$Q=$this->db->get();
		$data=$Q->row_array();
		return $data;
	}

	function getCityTags()
	{
		$data=array();
		$this->db->select('tag_name');
		$this->db->from('tbl_city_tags');
		$this->db->join('tbl_tag_master','tbl_tag_master.id=tbl_city_tags.tag_id');
		$this->db->where('city_id',$_POST['city_id']);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;	
			}
		}
		$imp=implode(', ', array_column($data, 'tag_name'));
		return $imp;
	}

	function getCityRatings()
	{
		$data=array();
		$this->db->select('rating_type,rating');
		$this->db->from('tbl_city_ratings');
		$this->db->join('tbl_rating_master','tbl_rating_master.id=tbl_city_ratings.rating_id');
		$this->db->where('city_id',$_POST['city_id']);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;	
			}
		}

		//echo "<pre>";
		//print_r($data);die;
		return $data;

	}

}

/* End of file Report_m.php */
/* Location: ./application/models/Report_m.php */