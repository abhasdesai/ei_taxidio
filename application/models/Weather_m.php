<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Weather_m extends CI_Model {

	function add() {
		
		if($_POST['weather_temperature_to']=='')
		{
			$weather_temperature_to=999999;	
		}
		else
		{
			$weather_temperature_to=$_POST['weather_temperature_to'];		
		}

		$data = array(
			'weather_temperature_from' => $_POST['weather_temperature_from'],
			'weather_temperature_to' => $weather_temperature_to,
		);
		$this->db->insert('tbl_weather_master', $data);
	}

	function edit() {

		if($_POST['weather_temperature_to']=='')
		{
			$weather_temperature_to=999999;	
		}
		else
		{
			$weather_temperature_to=$_POST['weather_temperature_to'];		
		}

		$data = array(
			'weather_temperature_from' => $_POST['weather_temperature_from'],
			'weather_temperature_to' => $weather_temperature_to,
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_weather_master', $data);
		//echo $this->db->last_query();die;
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_weather_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_weather($weather_temperature) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_weather_master', array('id !=' => $_POST['id'], 'weather_temperature_from' => $weather_temperature));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_weather', $_POST['weather_temperature_to'] . ' Whether already exists');
			return FALSE;
		}
		return TRUE;
	}

	function check_weather_to($weather_temperature) {
		if($weather_temperature=='')
		{
			$weather_temperature=999999;
		}
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_weather_master', array('id !=' => $_POST['id'], 'weather_temperature_to' => $weather_temperature));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_weather_to', $_POST['weather_temperature_to'] . ' Whether already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$Q=$this->db->query('select id from tbl_city_master where weather_id="'.$id.'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Weather.This Weather is in use.');
			redirect('admins/Weathers');
		}
		else
		{
			$this->db->where('id', $id);
			$this->db->delete('tbl_weather_master');
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

	function getMonthWiseWeather($id,$city)
	{
		$Q=$this->db->query('select * from tbl_city_weathers where month_id="'.$id.'" and city_id="'.$city.'"');
		return $Q->row_array();
	}

	function saveData() {
	  $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
	  $citydata=$Q->row_array();
			
      for($i=0;$i<count($_POST['month_id']);$i++)
      {
      		$Q=$this->db->query('select id from tbl_city_weathers where month_id="'.$_POST['month_id'][$i].'" and city_id="'.$_POST['city_id'].'"');
      		$data=array(
      					'month_id'=>$_POST['month_id'][$i],
      					'weather_high'=>$_POST['weather_high'][$i],
      					'weather_low'=>$_POST['weather_low'][$i],
      					'weather_avg'=>$_POST['weather_avg'][$i],
      					'weather_precipitation'=>$_POST['weather_precipitation'][$i],
      					'weather_days_rain'=>$_POST['weather_days_rain'][$i],
      					'weather_days_snow'=>$_POST['weather_days_snow'][$i],
      					'weather_sunrise'=>$_POST['weather_sunrise'][$i],
      					'weather_sunset'=>$_POST['weather_sunset'][$i],
      					'country_id'=>$citydata['country_id'],
      					'continent_id'=>$citydata['continent_id'],
      					'city_id'=>$_POST['city_id'],

      				);	
      		if($Q->num_rows()>0)
      		{
				$this->db->where('city_id',$_POST['city_id']);
      			$this->db->where('month_id',$_POST['month_id'][$i]);
      			$this->db->update('tbl_city_weathers',$data);
      		}
      		else
      		{
      			$this->db->insert('tbl_city_weathers',$data);
      		}
      } 
	}

	function weather_addto($weatheradd)
	{
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_weather_master', array('weather_temperature_to' => 999999.00));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_weather_addto','Blank whether in To field already exists. You can keep only one To field Blank.');
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file  */
/* Location: ./application/models/ */