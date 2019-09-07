<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hotels_wm extends CI_Model 
{
	function countHotels()
	{
		$cityid=$_POST['cityid'];
		$cityslug=$_POST['cityslug'];
		$city_name=$_POST['city_name'];

		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$cityid);
		$this->db->order_by('total','DESC');
		$QZip=$this->db->get();
		$zipcodeArray=$QZip->result_array();
		$zipcodes = array_column($zipcodeArray, 'zipcode');
		if(count($zipcodes)>0)
		{
			$where='';
			if ($cityslug!='' && $city_name!='') 
			{
				$where="(tbl_hotels.city_unique LIKE '%$cityslug%' OR tbl_hotels.city_hotel LIKE '%$city_name%' OR tbl_hotels.city_preferred LIKE '%$city_name%')";
			}
			
			$this->db->select('id');
			$this->db->from('tbl_hotels');
			$this->db->where_in('zip',$zipcodes);
			if($where!='')
			{
				$this->db->where($where);	
			}
			$Q_hotels=$this->db->get();
			
			return $Q_hotels->num_rows();
		}
	}

	function getHotels($limit,$start)
	{
		$data=array();
		
		$cityid=$_POST['cityid'];
		$cityslug=$_POST['cityslug'];
		$city_name=$_POST['city_name'];
		
		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$cityid);
		$this->db->order_by('total','DESC');
		$QZip=$this->db->get();
		$zipcodeArray=$QZip->result_array();
		$zipcodes = array_column($zipcodeArray, 'zipcode');
		if(count($zipcodes)>0)
		{
			
			$where='';
			if ($cityslug!='' && $city_name!='') 
			{
				$where="(tbl_hotels.city_unique LIKE '%$cityslug%' OR tbl_hotels.city_hotel LIKE '%$city_name%' OR tbl_hotels.city_preferred LIKE '%$city_name%')";
			}	

			$this->db->select('id,hotel_name,zip,photo_url,address,longitude,latitude,minrate,maxrate,city_hotel,hotel_url,description,currencycode');
			$this->db->from('tbl_hotels');
			$this->db->where_in('zip',$zipcodes);
			if($where!='')
			{
				$this->db->where($where);	
			}

			$order = sprintf('FIELD(zip, %s)', "'" . implode("','", $zipcodes) . "'");
			$this->db->order_by($order,null,FALSE); 

			$this->db->limit($limit, $start);
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}

		}
		return $data;
	}

}
?>