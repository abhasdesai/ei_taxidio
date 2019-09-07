<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Parameter_m extends CI_Model {

	function add() {

        $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();

		$data = array(
			'budget_id' => $_POST['budget_id'],
			'accomodation_id' => $_POST['accomodation_id'],
			'doi_id' => $_POST['doi_id'],
			'days_id' => $_POST['days_id'],
			'weather_id' => $_POST['weather_id'],
			'traveler_id' => $_POST['traveler_id'],
			'travel_time_id' => $_POST['travel_time_id'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
		);

		$this->db->insert('tbl_primary_parameter_master', $data);
	}

	function edit() {

      $data = array(
			'budget_id' => $_POST['budget_id'],
			'accomodation_id' => $_POST['accomodation_id'],
			'doi_id' => $_POST['doi_id'],
			'days_id' => $_POST['days_id'],
			'weather_id' => $_POST['weather_id'],
			'traveler_id' => $_POST['traveler_id'],
			'travel_time_id' => $_POST['travel_time_id'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
		);
        $this->db->where('id',$_POST['id']);
		$this->db->update('tbl_primary_parameter_master', $data);
	}


	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_primary_parameter_master', array('id' => $id));
		return $Q->row_array();
	}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_primary_parameter_master');
	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}
    

    function getBudget()
	{
		$data=array();
		$Q=$this->db->query('select id,(case when (budget_hotel_per_night_to<1) then concat(budget_hotel_per_night_from," + ") else concat(budget_hotel_per_night_from," - ",budget_hotel_per_night_to) end) as budget from tbl_budget_master order by budget_hotel_per_night_from asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAccomodation()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_accomodation_master order by id desc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getDOI()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_doi_master order by id desc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getDaysMaster()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_days_master order by id desc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getTravellerAge()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_traveler_master order by id desc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}


	function getTravellerTime()
	{
		$data=array();
		$Q=$this->db->query('select id,(case when (travel_time_slot_to>99999) then concat(travel_time_slot_from," + ") else concat(travel_time_slot_from," - ",travel_time_slot_to) end) as traveltime from tbl_travel_time_master order by travel_time_slot_from ASC');

		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getTravelerAge()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_traveler_master order by id desc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getWeather()
	{
		$data=array();
	    $Q=$this->db->query('select id,(case when (weather_temperature_to>99999) then concat(weather_temperature_from," + ") else concat(weather_temperature_from," - ",weather_temperature_to) end) as weather from tbl_weather_master order by weather_temperature_from asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}
}

/* End of file  */
/* Location: ./application/models/ */
