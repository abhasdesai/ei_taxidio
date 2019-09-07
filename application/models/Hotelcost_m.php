<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotelcost_m extends CI_Model {

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function getAllHotelTypes()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_hoteltypes order by hotel_type asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
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

	function getMonthWiseCost($month_id,$city_id,$hoteltype_id)
	{	
		$Q=$this->db->query('select * from tbl_city_hotel_cost_master where month_id="'.$month_id.'" and city_id="'.$city_id.'" and hoteltype_id="'.$hoteltype_id.'" ');
		return $Q->row_array();
	}

	function saveData()
	{
			for($i=0;$i<count($_POST['month_id']);$i++)
	        {
	       		if(isset($_POST['cost'][$i]) && $_POST['cost'][$i]!='')
				{
					$cost=$_POST['cost'][$i];
				}
				else
				{
					$cost=0;	
				}
				
	      		$Q=$this->db->query('select id from tbl_city_hotel_cost_master where month_id="'.$_POST['month_id'][$i].'" and city_id="'.$_POST['city_id'].'" and hoteltype_id="'.$_POST['hoteltype_id'].'"');	
	      		if($Q->num_rows()>0)
	      		{
	      			$this->db->where('city_id',$_POST['city_id']);
	      			$this->db->where('month_id',$_POST['month_id'][$i]);
	      			$this->db->where('hoteltype_id',$_POST['hoteltype_id']);
	      			$this->db->update('tbl_city_hotel_cost_master',array('cost'=>$cost));
	      		}
	      		else
	      		{
	      			$this->db->insert('tbl_city_hotel_cost_master',array('cost'=>$cost,'month_id'=>$_POST['month_id'][$i],'city_id'=>$_POST['city_id'],'hoteltype_id'=>$_POST['hoteltype_id']));
	      		}
	      } 
	}

}

/* End of file Hotelcost_m.php */
/* Location: ./application/models/Hotelcost_m.php */