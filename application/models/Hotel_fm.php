<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hotel_fm extends CI_Model 
{

	function countHotels($countryid,$cityid)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();

		$encryptid=explode('-',string_decode($countryid));
		$countryid=$encryptid[0];
		$uniqueid=$encryptid[2];
		$filecheck=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
		$filecheck_decode=json_decode($filecheck,TRUE);
		if(isset($filecheck_decode[$countryid]))
		{
			foreach ($filecheck_decode[$countryid] as $key => $list) 
			{
				if(md5($list['id'])==$cityid)
				{
					$cityids=$list['id'];
					$city_name=$list['city_name'];
					$cityslug=$list['cityslug'];
				}
			}
		}
		else
		{
			return 0;
		}

		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$cityids);
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
		return 0;

	}



	function getAllHotels($recommendation,$cityid,$limit,$offset)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();

		$encryptid=explode('-',string_decode($recommendation));
		$countryid=$encryptid[0];
		$uniqueid=$encryptid[2];
		$filecheck=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
		$filecheck_decode=json_decode($filecheck,TRUE);
		if(isset($filecheck_decode[$countryid]))
		{
			foreach ($filecheck_decode[$countryid] as $key => $list) 
			{
				if(md5($list['id'])==$cityid)
				{
					$cityids=$list['id'];
					$city_name=$list['city_name'];
					$cityslug=$list['cityslug'];
				}
			}
		}
		else
		{
			return $data;
		}

		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$cityids);
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

			$this->db->limit($limit, $offset);
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

	function getCountryId($cityid)
	{
		$Q=$this->db->query('select country_id from tbl_city_master where id="'.$cityid.'"');
		return $Q->row_array(); 
	}


	function countMultiCountryHotels($countryid,$city_id)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();
		$uniqueid=$_POST['uniqueid'];
		$filecheck=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/cities');
		$filecheck_decode=json_decode($filecheck,TRUE);
		if(isset($filecheck_decode[$countryid]))
		{
			foreach ($filecheck_decode[$countryid] as $key => $list) 
			{
				if($list['id']==$city_id)
				{
					$city_name=$list['city_name'];
					$cityslug=$list['cityslug'];
				}
			}
		}
		else
		{
			return 0;
		}
		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$city_id);
		$this->db->order_by('total','DESC');
		$QZip=$this->db->get();
		//echo $this->db->last_query();die;
		$zipcodeArray=$QZip->result_array();
		$zipcodes = array_column($zipcodeArray, 'zipcode');

		if(count($zipcodes)>0)
		{
			$where='';
			if ($cityslug!='' && $city_name!='') 
			{
				$where="(tbl_hotels.city_unique LIKE '%$cityslug%' OR tbl_hotels.city_hotel LIKE '%$city_name%')";
			}
			
			$this->db->select('id,booking_hotel_id');
			$this->db->from('tbl_hotels');
			$this->db->where_in('zip',$zipcodes);
			if($where!='')
			{
				$this->db->where($where);	
			}
			$this->db->group_by('booking_hotel_id');
			$Q_hotels=$this->db->get();
			return $Q_hotels->num_rows();
		}
		return 0;


	}

	function getAllMultiCountryHotels($countryid,$city_id,$limit,$offset)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();
		$uniqueid=$_POST['uniqueid'];

		$filecheck=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/cities');
		$filecheck_decode=json_decode($filecheck,TRUE);
		if(isset($filecheck_decode[$countryid]))
		{
			foreach ($filecheck_decode[$countryid] as $key => $list) 
			{
				if($list['id']==$city_id)
				{
					$city_ids=$list['id'];
					$city_name=$list['city_name'];
					$cityslug=$list['cityslug'];
				}
			}
		}
		else
		{
			return $data;
		}

		$this->db->select('zipcode');
		$this->db->from('tbl_city_zipcodes');
		$this->db->where('city_id',$city_id);
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

			$this->db->select('id,hotel_name,zip,photo_url,address,longitude,latitude,minrate,maxrate,city_hotel,hotel_url,description,currencycode,booking_hotel_id');
			$this->db->from('tbl_hotels');
			$this->db->where_in('zip',$zipcodes);
			if($where!='')
			{
				$this->db->where($where);	
			}
			$this->db->group_by('booking_hotel_id');
			$order = sprintf('FIELD(zip, %s)', "'" . implode("','", $zipcodes) . "'");
			$this->db->order_by($order,null,FALSE);  
			$this->db->limit($limit, $offset);
			$Q=$this->db->get();
			//echo $this->db->last_query();die;
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


	function getSingleCountryName($recommendation)
	{
		$data='';
		$encryptid=explode('-',string_decode($recommendation));
		$country_id=$encryptid[0];
		$uniqueid=$encryptid[2];
		$filecheck=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
		$filecheck_decode=json_decode($filecheck,TRUE);
		if(isset($filecheck_decode[$country_id][0]['country_name']))
		{
			$data['country_name']=$filecheck_decode[$country_id][0]['country_name'];
		}
		return $data;

	}

	function getCountryName($countrid)
	{
		$Q=$this->db->query('select country_name from tbl_country_master where id="'.$countrid.'" limit 1');
		$data=$Q->row_array();
		return $data;

	}


	function getAllCities($countryid)
	{
		$cities=array();
		$encryptid=explode('-',string_decode($countryid));
		$country_id=$encryptid[0];
		$uniqueid=$encryptid[2];
		$filecheck=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
		$filecheck_decode=json_decode($filecheck,TRUE);
		
		if(isset($filecheck_decode[$country_id]))
		{
			foreach ($filecheck_decode[$country_id] as $key => $list) 
			{
				$cities[$key]['id']=md5($list['id']);
				$cities[$key]['city_name']=$list['city_name'];
			}
		}

		return $cities;

	}

	function getAllMultiCountryCities($encryptid,$uniqueid)
	{
		$cities=array();
		$filecheck=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/cities');
		$filecheck_decode=json_decode($filecheck,TRUE);
		$k=0;
		for($i=0;$i<count($encryptid);$i++)
		{
			//echo "<pre>";
			//print_r($filecheck_decode[31]);
			//print_r($filecheck_decode[61]);die;

			if(isset($filecheck_decode[$encryptid[$i]]))
			{
				foreach ($filecheck_decode[$encryptid[$i]] as $key => $list) 
				{
					$cities[$k]['id']=($list['country_id'].'-'.$list['id']);
					$cities[$k]['city_name']=$list['city_name'];
					$cities[$k]['country_name']=$list['country_name'];
					$k++;
				}
			}


		}

		if(count($cities)<1)
		{
			return $cities;
		}

		$country_group = array();
		foreach($cities as $arg)
		{
			$country_group[$arg['country_name']][] = $arg;
		}
		
		return $country_group;
	}

	function getSearchedCityName($filename,$cityid,$uniqueid)
	{
		$data='';
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename))
		{
			$filecheck=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename);
			$filecheck_decode=json_decode($filecheck,TRUE);

			$key = array_search($cityid,array_column($filecheck_decode, 'id'));

			$data['country_name']=$filecheck_decode[$key]['city_name'];
		}

		return $data;
	}

	function getAllSearchedCities($filename,$cityid,$uniqueid)
	{

		$cities=array();
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename))
		{
			$filecheck=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename);
			$filecheck_decode=json_decode($filecheck,TRUE);
			
			foreach ($filecheck_decode as $key => $list) 
			{
				$cities[$key]['id']=md5($list['id']);
				$cities[$key]['plainid']=$list['id'];
				$cities[$key]['city_name']=$list['city_name'];
			}

			return $cities;
		}
		return $cities;
		

	}

	function getKey($filename,$cityid,$uniqueid)
	{
		$key='';
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename))
		{
			$filecheck=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filename);
			$filecheck_decode=json_decode($filecheck,TRUE);
			$key = array_search($cityid,array_column($filecheck_decode, 'id'));
		}
		return $key;
	}


	function countSearchedHotels($filenm,$cityid,$uniqueid)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filenm))
		{
			$filecheck=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filenm);
			$filecheck_decode=json_decode($filecheck,TRUE);
			
			foreach($filecheck_decode as $list)
			{
				if($list['id']==$cityid)
				{
					$city_name=$list['city_name'];
				    $cityslug=$list['cityslug'];
				    break;
				}
				
			}


			$this->db->select('zipcode');
			$this->db->from('tbl_city_zipcodes');
			$this->db->where('city_id',$cityid);
			$this->db->order_by('total','DESC');
			$QZip=$this->db->get();
			$zipcodeArray=$QZip->result_array();
			$zipcodes = array_column($zipcodeArray, 'zipcode');
			if(count($zipcodes)>0)
			{
				$this->db->select('id,booking_hotel_id');
				$this->db->from('tbl_hotels');
				$this->db->where_in('zip',$zipcodes);
				$where='';
				if ($cityslug!='' && $city_name!='') 
				{
					$where="(tbl_hotels.city_unique LIKE '%$cityslug%' OR tbl_hotels.city_hotel LIKE '%$city_name%' OR tbl_hotels.city_preferred LIKE '%$city_name%')";
				}
				if($where!='')
				{
					$this->db->where($where);	
				}
				$this->db->group_by('booking_hotel_id');
				$Q_hotels=$this->db->get();
				return $Q_hotels->num_rows();
			}


		}
		return 0;


	}

	function getSearchedHotels($filenm,$cityid,$limit,$offset,$uniqueid)
	{
		$data=array();
		$city_name='';
		$cityslug='';
		$zipcodes=array();

		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filenm))
		{
			$filecheck=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$filenm);
			$filecheck_decode=json_decode($filecheck,TRUE);

			foreach($filecheck_decode as $list)
			{
				if($list['id']==$cityid)
				{
					$city_name=$list['city_name'];
				    $cityslug=$list['cityslug'];
				    break;
				}
				
			}

			$this->db->select('zipcode');
			$this->db->from('tbl_city_zipcodes');
			$this->db->where('city_id',$cityid);
			$this->db->order_by('total','DESC');
			$QZip=$this->db->get();
			$zipcodeArray=$QZip->result_array();
			$zipcodes = array_column($zipcodeArray, 'zipcode');
			if(count($zipcodes)>0)
			{

				$this->db->select('id,hotel_name,zip,photo_url,address,longitude,latitude,minrate,maxrate,city_hotel,hotel_url,description,currencycode,booking_hotel_id');
				$this->db->from('tbl_hotels');
				$this->db->where_in('zip',$zipcodes);
				$where='';
				if ($cityslug!='' && $city_name!='') 
				{
					$where="(tbl_hotels.city_unique LIKE '%$cityslug%' OR tbl_hotels.city_hotel LIKE '%$city_name%' OR tbl_hotels.city_preferred LIKE '%$city_name%')";
				}
				if($where!='')
				{
					$this->db->where($where);	
				}
				$this->db->group_by('booking_hotel_id');
				$order = sprintf('FIELD(zip, %s)', "'" . implode("','", $zipcodes) . "'");
			    $this->db->order_by($order,null,FALSE); 
				$this->db->limit($limit, $offset);
				$Q=$this->db->get();
				//echo $this->db->last_query();die;
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
		return $data;
	}

	function getPostCountryName($countryid)
	{
		$Q=$this->db->query('select country_name from tbl_country_master where id="'.$countryid.'" limit 1');
		return $Q->row_array();

	}

	function getCityBasic($city_id)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where id="'.$city_id.'"');
		return $Q->row_array();
	}

	function getCityBasicmd5($city_id)
	{
		$Q=$this->db->query('select id,city_name,citybanner,city_conclusion,slug from tbl_city_master where md5(id)="'.$city_id.'"');
		return $Q->row_array();
	}
}

?>
