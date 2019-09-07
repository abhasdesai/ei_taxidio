<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Home_fm extends CI_Model {

	function getTags() {
		$data = array();
		$this->db->select('id,tag_name,slug');
		$this->db->order_by('sortorder', 'ASC');
		$Q = $this->db->get('tbl_tag_master');
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;

	}

	function getAccomodationType()
	{
		$data = array();
		$Q=$this->db->query('select * from tbl_accomodation_master where id!=7');
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function getTravelTimeSlots()
	{
		$data = array();
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (travel_time_slot_to=999999) THEN concat(travel_time_slot_from,'+') ELSE concat(travel_time_slot_from,'-',travel_time_slot_to) END) as travel_time", FALSE);
		$this->db->from('tbl_travel_time_master');
		$this->db->order_by('travel_time_slot_from', 'ASC');
		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function getCountryCode()
	{
		$countrycode=$_GET['start_city'];
		$comma=substr_count($_GET['start_city'],',');
		if($comma>0)
		{
			$countryarr=explode(',',$_GET['start_city']);
			$countrycode=$countryarr[count($countryarr) - 1];
		}
		return $countrycode;
	}

	function getCountrySlug($countryrome2rioname)
	{
		$Q=$this->db->query('select slug from tbl_country_master where rome2rio_code ="'.trim($countryrome2rioname).'"');
		return $Q->row_array();
	}

	function getSingleCountries() 
	{

		if (isset($_GET['tags']) && $_GET['tags'] != '') 
		{
			return $this->CountriesWithTags();	
		}
		else
		{
			return $this->CountriesWithNoTags();	
		}
	}


	function CountriesWithNoTags()
	{
		//echo "<pre>";print_r($_GET);die;
		//$countryrome2rioname=$this->getCountryCode();
		$countryrome2rioname=$this->input->get('ocode',TRUE);
		$data = array();
		$singlecountry = array();
		
		/*$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_city_master.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.continent_id,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,tbl_city_master.latitude as citylatitude,tbl_city_master.longitude as citylongitude,country_conclusion,total_days',FALSE);*/

		/*Developer*/
		$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_city_master.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.continent_id,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,tbl_city_master.latitude as citylatitude,tbl_city_master.longitude as citylongitude,country_conclusion,total_days,tbl_city_master.slug as cityslug,code,countrycode,countryimage,cityimage,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags,rome2rio_code',FALSE);

		//$this->db->select('tbl_city_master.id,city_name,total_days,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags',FALSE);
		
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		$this->db->where('tbl_city_master.id in (select city_id from tbl_city_paidattractions where city_id=tbl_city_master.id)');

		if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
			$this->db->where('tbl_city_master.rome2rio_name !=',trim($_GET['start_city']));
			$cityname=explode(',',trim($_GET['start_city']));
			$this->db->where('tbl_city_master.rome2rio_name !=',$cityname[0]);
			
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
			
		}

		/*
		if (isset($_GET['budget']) && $_GET['budget'] != '') 
		{
			$budget=explode('-',$this->input->get('budget'));	
			
			$this->db->join('tbl_budget_master', 'tbl_budget_master.id=tbl_city_master.budget_id');
			

			if ($budget[1]>400) 
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('budget_hotel_per_night_from >=',$start_budget,FALSE);

			}
			else
			{
				
				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(budget_hotel_per_night_from <= $end_budget AND budget_hotel_per_night_from>=$start_budget or ($start_budget between budget_hotel_per_night_from and budget_hotel_per_night_to))";
				$this->db->where($wherebudget);
			}
		}*/

		$date=implode("-", array_reverse(explode("/",$_GET['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_GET['budget']) && $_GET['budget'] != '') 
		{
			$budget=explode('-',$this->input->get('budget'));	
			
			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');
			

			if ($budget[1]>=500) 
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);

			}
			else
			{
				
				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);
			}
		}
		
		if (isset($_GET['weather']) && $_GET['weather'] != '') 
		{
			
			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');	
			
			$weather = explode('-',$this->input->get('weather'));
			if(count($weather)==4)
			{
				$startweather=-$weather[1];
				$endweather=-$weather[3];
			}
			else if(count($weather)==3)
			{
				$startweather=-$weather[1];
				$endweather=$weather[2];
			}
			else
			{
				$startweather=$weather[0];
				$endweather=$weather[1];
			}

			if($endweather<33)
			{
				$whereweather = "(weather_avg BETWEEN $startweather AND $endweather and tbl_city_weathers.month_id=$month)";	
			}
			else
			{
				$whereweather = "(weather_avg >= $startweather AND tbl_city_weathers.month_id=$month)";
			}
			$this->db->where($whereweather);
		}

		
		$this->db->distinct();
		$this->db->group_by('tbl_city_master.id');
		$this->db->order_by('totaltags', 'DESC');
		$Q = $this->db->get();
		//echo $this->db->last_query();die;
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $key=>$row) {
				$data[] = $row;
				$data[$key]['uniqueid']=$_GET['token'];
			}
		}
		//echo $this->db->last_query();die;
		$cityData=array();
		if(count($data))
		{
			if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
			{
				$cityData=$this->domesticNoTags($data);
			
			}
			else
			{
				$cityData=$this->InternationalNoTags($data);
			}
		}
		return $cityData;
	}


	function domesticNoTags($data)
	{
		$country_group=array();

		if (isset($_GET['days']) && $_GET['days'] != '') 
		{
			$days = $this->input->get('days',TRUE);
			if ($days>32) 
			{
				$startday = (int)($days-2);
				$endday=0;
			} 
			else 
			{
				$startday = (int)($days-2);
				$endday = (int)($days+2);
				
			}
		}

		$totaldays=0;
		if($endday==0)
		{
			foreach($data as $innerlist)
			{
				$totaldays+=ceil($innerlist['total_days']);
			}
		}

		$totaldays=0;
		if($endday==0)
		{
			foreach($data as $innerlist)
			{
				$totaldays+=ceil($innerlist['total_days']);
			}
		}

		if($endday==0 && $totaldays>=$startday)
		{
			$country_group=array();
			foreach($data as $arg)
			{
				$country_group[$arg['country_id']][] = $arg;
			}
		}


		if($endday>0)
		{
			$country_group=$this->processAdditionalDayAlgorithmForNationalNoTags($data,$startday,$endday);
			//echo "<pre>";print_r($country_group);die;
		}


		$this->createFile($country_group);	
		return $country_group;

	}

	function InternationalNoTags($data)
	{
		$country_group = array();
		foreach($data as $arg)
		{
			$country_group[$arg['country_id']][] = $arg;
		}

		//echo "<pre>";print_r($country_group);die;

		if (isset($_GET['days']) && $_GET['days'] != '') 
		{
			$days = $this->input->get('days',TRUE);
			if ($days>32) 
			{
				$startday = (int)($days-2);
				$endday=0;
			} 
			else 
			{
				$startday = (int)($days-2);
				$endday = (int)($days+2);
				
			}
		}

		$tt=array();
		foreach($country_group as $key=>$list)
		{
			$totaldays=0;
			foreach($list as $innerlist)
			{
				$totaldays+=ceil($innerlist['total_days']);
			}

			if($endday<1)
			{
				if($totaldays<$startday)
				{
					unset($country_group[$key]);
				}
			}
			else
			{

				if($startday <= $totaldays && $endday >= $totaldays)
				{
					
				}
				else
				{
					if($totaldays>$endday)
					{
						$combination_founded=$this->processAdditionalDayAlgorithm($country_group[$key],$startday,$endday);
						if(!count($combination_founded))
						{
							unset($country_group[$key]);		
						}
						else
						{
							$country_group[$key]=$combination_founded;
							$tt[]=$combination_founded;
						}
						
					}
					else
					{
						unset($country_group[$key]);	
					}
					
				}
			}

		}

		//echo "<pre>";print_r($country_group);die;		
		
		foreach ($country_group as $ckey => $clist) {
			$country_group[$ckey]['totalcities'] = count($clist);	

		}
		//echo "<pre>";print_r($country_group);die;
		
		$country_city_array_sorted=array();
		 foreach ($country_group as $key => $value) {
			$country_city_array_sorted[$key]  = $value['totalcities'];
		}
		array_multisort($country_city_array_sorted, SORT_DESC,SORT_NUMERIC, $country_group);

		
		$singlecountry=$this->selectCountries($country_group);
		
		//echo "<pre>";print_r($country_group);die;
		
		$finalarray=array();
		$k=0;
		foreach ($singlecountry as $finalkey => $finalvalue) 
		{
			foreach ($finalvalue as $finalinnerkey => $finalinnervalue) 
			{
				unset($singlecountry[$finalkey]['totalcities']);
				$finalarray[$k]=$finalvalue[0];
				$finalarray[$k]['timetoreach']=$finalvalue[0]['timetoreach'];
			}
			$k++;
		}

		
		$allcountryarray=array();
		
		foreach ($singlecountry as $allkey => $allcountries) {
			foreach ($allcountries as $allkeysub => $allcountriessub) {
				$allcountryarray[$allcountriessub['country_id']][]=$allcountriessub;	
			}
		}

		
		$this->createFile($allcountryarray);	

		//echo "<pre>";print_r($allcountryarray);die;
			return $allcountryarray;
	}





	function CountriesWithTags()
	{
		
		//$countryrome2rioname=$this->getCountryCode();
		$countryrome2rioname=$this->input->get('ocode',TRUE);

		$data=array();

		$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.continent_id,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,tbl_city_master.latitude as citylatitude,tbl_city_master.longitude as citylongitude,country_conclusion,tbl_city_master.max_zipcode,tbl_city_master.slug as cityslug,code,countrycode,countryimage,cityimage,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags,rome2rio_code',FALSE);

		$this->db->from('tbl_city_attraction_log');
		$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
		$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		$this->db->where('tbl_city_master.total_attraction_time >',0);
		if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
			$this->db->where('tbl_city_master.rome2rio_name !=',trim($_GET['start_city']));
			$cityname=explode(',',trim($_GET['start_city']));
			$this->db->where('tbl_city_master.rome2rio_name !=',$cityname[0]);
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
		}

		$date=implode("-", array_reverse(explode("/",$_GET['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_GET['budget']) && $_GET['budget'] != '') 
		{
			$budget=explode('-',$this->input->get('budget'));	
			
			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');
			

			if ($budget[1]>=500) 
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);

			}
			else
			{
				
				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);
			}
		}
		
		
		if (isset($_GET['weather']) && $_GET['weather'] != '') 
		{
			
			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');	
			
			$weather = explode('-',$this->input->get('weather'));
			if(count($weather)==4)
			{
				$startweather=-$weather[1];
				$endweather=-$weather[3];
			}
			else if(count($weather)==3)
			{
				$startweather=-$weather[1];
				$endweather=$weather[2];
			}
			else
			{
				$startweather=$weather[0];
				$endweather=$weather[1];
			}

			if($endweather<33)
			{
				$whereweather = "(weather_avg BETWEEN $startweather AND $endweather and tbl_city_weathers.month_id=$month)";	
			}
			else
			{
				$whereweather = "(weather_avg >= $startweather AND tbl_city_weathers.month_id=$month)";
			}
			$this->db->where($whereweather);
		}


		$sq = '';
		for ($i = 0; $i < count($_GET['tags']); $i++)
		{
				$tag = $_GET['tags'][$i];
				if (count($_GET['tags']) == 1) 
				{
					$sq = '(tag_name="' . $tag . '")';
				} 
				else 
				{
					if ($i == 0) 
					{
						$sq .= '(tag_name="' . $tag . '"';

					} 
					else if ($i == count($_GET['tags']) - 1) 
					{
						$sq .= ' OR tag_name="' . $tag . '")';
					} 
					else 
					{
						$sq .= ' OR tag_name="' . $tag . '"';
					}
				}
		}
		$this->db->where($sq);
		$this->db->group_by('attraction_id');
		$this->db->order_by('totaltags', 'DESC');
		$Q=$this->db->get();

		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{
				$data[]=$row;
				$data[$key]['uniqueid']=$_GET['token'];
			}
		}

		$cityData=array();
		if(count($data))
		{
			if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
			{
				$cityData=$this->domesticTags($data);
			}
			else
			{
				$cityData=$this->InternationalTags($data);
			}
		}
		return $cityData;

	}

	function domesticTags($data)
	{
		$country_city_array = array();
		foreach ( $data as $row ) 
		{
			$country = trim($row['country_id']);
			$city = trim($row['city_id']);
			$country_city_array[ $country ][ $city ][] = $row;
		}

	  
		foreach($country_city_array as $key=>$listsum)
		{
			$sum=0;$counter=count($listsum);
			foreach($listsum as $key2=>$listinsidesum)
			{
				$thiscity=0;$currentcitysum=0;
				foreach($listinsidesum as $k=>$finalsum)
				{
					$sum+=$finalsum['tag_hours'];
					$thiscity++;
					$currentcitysum+=$finalsum['tag_hours'];
					if($thiscity==count($listinsidesum))
					{
						$country_city_array[$key][$key2][0]['thiscitytime']=ceil($currentcitysum/12);
					}
				}
			}

			$country_city_array[$key]['totalcitytime']=ceil($sum/12);
			$country_city_array[$key]['counter']=$counter;

		}


	   
		if (isset($_GET['days']) && $_GET['days'] != '') 
		{
			$days = $this->input->get('days',TRUE);
			if ($days>32) 
			{
				$startday = (int)($days-2);
				$endday=0;
			} 
			else 
			{
				$startday = (int)($days-2);
				$endday = (int)($days+2);
				
			}
		}


		if($endday!=0)
		{
			foreach ($country_city_array as $key => $value) 
			{

				if($startday <= $value['totalcitytime'] && $endday >= $value['totalcitytime'])
				{
				
				}
				else
				{
					if($value['totalcitytime']>$endday)
					{
						$combination_founded=$this->processAdditionalDayAlgorithmForTags($country_city_array[$key],$startday,$endday);
						if(!count($combination_founded))
						{
							unset($country_city_array[$key]);		
						}
						else
						{
							//echo "<pre>11";print_r($combination_founded);die;
							$country_city_array[$key]=$combination_founded;
							//echo "<pre>";print_r($country_city_array);die;
						}
						//$country_city_array[$key]=$combination_founded;
					}
					else
					{
						unset($country_city_array[$key]);	
					}
				}
			}
		}
		else
		{
			foreach ($country_city_array as $key => $value) 
			{
				if($value['totalcitytime']<$startday)
				{
					unset($country_city_array[$key]);
				}
			}
		}

		


		if(count($country_city_array))
		{
				$country_city_array_sorted=array();
				// echo "<pre>";print_r($country_city_array);die;
				
				foreach ($country_city_array as $key => $value) {
					$country_city_array_sorted[$key]  = $value['counter'];
				}
			   array_multisort($country_city_array_sorted, SORT_DESC,SORT_NUMERIC, $country_city_array);


				$finalArray=array();
				foreach($country_city_array as $key=>$finallist)
				{

					foreach($finallist as $secondkey=>$secondlist)
					{
						if($secondkey!='totalcitytime' && $secondkey!='counter')
						{
							$country=$secondlist[0]['country_id'];
							$finalArray[$country][]=$secondlist[0];
							$finalArray[$country][0]['totaltimeneeds']=$finallist['totalcitytime'];

						}
					}

				}

			  
			  $this->createFile($finalArray);	
			  return $finalArray;
		}
		else
		{
			return $country_city_array;
		}

	   
	}

	function InternationalTags($data)
	{
		$country_city_array = array();
		foreach ( $data as $row ) 
		{
			$country = trim($row['country_id']);
			$city = trim($row['city_id']);
			$country_city_array[ $country ][ $city ][] = $row;
		}


		foreach($country_city_array as $key=>$listsum)
		{
			$sum=0;$counter=count($listsum);
			foreach($listsum as $key2=>$listinsidesum)
			{
				$thiscity=0;$currentcitysum=0;
				//echo "<pre>";print_r($listinsidesum);die;
				foreach($listinsidesum as $k=>$finalsum)
				{
					$sum+=$finalsum['tag_hours'];
					$currentcitysum+=$finalsum['tag_hours'];
					$thiscity++;
					if($thiscity==count($listinsidesum))
					{
						$country_city_array[$key][$key2][0]['thiscitytime']=ceil($currentcitysum/12);
					}
				}
			}

			$country_city_array[$key]['totalcitytime']=ceil($sum/12);
			$country_city_array[$key]['counter']=$counter;

		}

		//echo "<pre>";print_r($country_city_array);die;
	  

		if (isset($_GET['days']) && $_GET['days'] != '') 
		{
			$days = $this->input->get('days',TRUE);
			if ($days>32) 
			{
				$startday = (int)($days-2);
				$endday=0;
			} 
			else 
			{
				$startday = (int)($days-2);
				$endday = (int)($days+2);
				
			}
		}

		if($endday!=0)
		{
			foreach ($country_city_array as $key => $value) 
			{

				if($startday <= $value['totalcitytime'] && $endday >= $value['totalcitytime'])
				{
				
				}
				else
				{
					
					if($value['totalcitytime']>$endday)
					{
						$combination_founded=$this->processAdditionalDayAlgorithmForTags($country_city_array[$key],$startday,$endday);
						if(!count($combination_founded))
						{
							unset($country_city_array[$key]);		
						}
						else
						{

							$country_city_array[$key]=$combination_founded;
						}
						
					}
					else
					{
						unset($country_city_array[$key]);	
					}
					

					
				}
			}
		}
		else
		{
			foreach ($country_city_array as $key => $value) 
			{
				if($value['totalcitytime']<$startday)
				{
					unset($country_city_array[$key]);
				}
			}
		}

		

		if(count($country_city_array))
		{
				$country_city_array_sorted=array();
				
				foreach ($country_city_array as $key => $value) {

					$country_city_array_sorted[$key]  = $value['counter'];
				}
			   array_multisort($country_city_array_sorted, SORT_DESC,SORT_NUMERIC, $country_city_array);


				$finalArray=array();
				foreach($country_city_array as $key=>$finallist)
				{

					foreach($finallist as $secondkey=>$secondlist)
					{
						if($secondkey!='totalcitytime' && $secondkey!='counter')
						{
							$country=$secondlist[0]['country_id'];
							$finalArray[$country][]=$secondlist[0];
							$finalArray[$country][0]['totaltimeneeds']=$finallist['totalcitytime'];

						}
					}

				}

			  //echo "<pre>";print_r($finalArray);die;

			  $singlecountry=$this->selectCountries($finalArray);

			  $this->createFile($singlecountry);	
			  return $singlecountry;
		}
		else
		{
			return $country_city_array;
		}
	}	


	function createFile($selected_country_group)
	{
		$uniqueid='';
		if(count($selected_country_group))
		{
			$keys=array_keys($selected_country_group);
			$mainkey=$keys[0];
			$uniqueid=$selected_country_group[$mainkey][0]['uniqueid'];
		}	
		
		$randomstring=$this->session->userdata('randomstring');
		if (!is_dir(FCPATH.'userfiles/files/'.$randomstring.'/'.$uniqueid)) {
			mkdir(FCPATH.'userfiles/files/'.$randomstring.'/'.$uniqueid, 0777,true);         
		}	
		$file=fopen(FCPATH.'userfiles/files/'.$randomstring.'/'.$uniqueid.'/singlecountry','w');
		fwrite($file,json_encode($selected_country_group));
		fclose($file);

		$file=fopen(FCPATH.'userfiles/files/'.$randomstring.'/'.$uniqueid.'/inputs','w');
		fwrite($file,json_encode($_GET));
		fclose($file);
	}

	function selectCountries($data) {
		$recommended_countries = array();
		$keys_array = array();

		
		$traveltime = explode('-',$this->input->get('traveltime'));
		//echo "<pre>";print_r($traveltime);die;
		if ($traveltime[1]>25) {
			$approx = 0.20 * $traveltime[1];
			$starttime = round($traveltime[1]-$approx);
			$endtime = 0;
			
		} else {
			
			$time = explode('-',$_GET['traveltime']);
			$start_time=$time[0];
			$end_time=$time[1];
			$starttime = round($start_time-($start_time*0.20));
			$endtime = round($end_time+($end_time*0.20));
		}

		//echo $starttime." == ".$endtime;die;

		$storage=array();
		if($this->session->userdata('storage'))
		{
			$storage=$this->session->userdata('storage');
		}



		if (count($data)) {
			$key = 0;
			//echo "<pre>";print_r($data);die;
			foreach ($data as $key => $list) {
				//echo "<Pre>";echo $key;print_r($list);die;
				/*if(!isset($list[0]['rome2rio_country_name']))
				{
					echo "<Pre>";print_r($list);
				}*/
				//echo "<pre>";print_r($list[0]['rome2rio_country_name']);die;
				$response = $this->getShortestDistance($key, $_GET['start_city'], $list[0]['rome2rio_country_name']);

				$hours = floor($response / 60);
				$minutes = $response % 60;
					
				$tempstorage[]=array(
						'country_id'=>$list[0]['country_id'],
						'distance'=>$hours . ' Hrs ' . $minutes . ' Mins',
						'actualtime'=>$response
				);

				if(count($storage))
				{
					$finalStorage=array_merge($storage,$tempstorage);
				}
				else
				{
					$finalStorage=$tempstorage;
				}
				$this->session->set_userdata('storage',$finalStorage);
				if ($response == 'na') {
					unset($data[$key]);
				} else {
					$hrs = $response / 60;
					if($endtime==0)
					{
						//echo $starttime ."<". $hrs;die;
						if($starttime < $hrs)
						{
							unset($data[$key]);		
						}
						else 
						{
							$hours = floor($response / 60);
							$minutes = $response % 60;
							$data[$key][0]['timetoreach'] = $hours . ' Hrs ' . $minutes . ' Mins';
							$data[$key][0]['actualtime']=$response;
							//echo "<pre>";print_r($data);die;
						}
					}
					else if(($starttime > $hrs || $endtime < $hrs) && $endtime !=0) 
					{
						unset($data[$key]);
					} 
					else 
					{

						$hours = floor($response / 60);
						$minutes = $response % 60;
						$data[$key][0]['timetoreach'] = $hours . ' Hrs ' . $minutes . ' Mins';
						$data[$key][0]['actualtime']=$response;
						//echo "<pre>jp";print_r($data);die;
					}
				}
				$key++;
			}
		}
		return $data;
	}

	function getShortestDistance1($key, $start_city, $end_city) {
		$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['duration'] == '') {
			return 'na';
		} else {
			return $json['routes'][0]['duration'];
		}
	}

	function getShortestDistance($key, $start_city, $end_city) {

		$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result, true);
		//echo "<Pre>ss";print_r($json);die;
		if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['totalDuration'] == '') {
			return 'na';
		} else {
			return $json['routes'][0]['totalDuration'];
		}
	}

	function getMultiCountries() 
	{


		if (isset($_GET['tags']) && $_GET['tags'] != '') 
		{
			return $this->MultiCountriesWithTags();
		}
		else
		{
			return $this->MultiCountriesWithNoTags();	
		}

	}

	function MultiCountriesWithTags()
	{
		$countryrome2rioname=$this->input->get('ocode',TRUE);

		$data=array();

		$this->db->select('tbl_city_master.id,tbl_city_attraction_log.tag_hours,attraction_id,tbl_continent_countries.continent_id,tbl_continent_countries.country_id,tbl_continent_countries.country_id as countryid,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name, tbl_country_master.country_name,tbl_country_master.latitude AS countrylatitude,tbl_country_master.longitude AS countrylongitude,tbl_country_master.slug,tbl_country_master.rome2rio_name AS rome2rio_country_name,tbl_city_master.latitude AS citylatitude,tbl_city_master.longitude AS citylongitude,country_conclusion,tbl_continent_countries.continent_id,tbl_country_master.rome2rio_name AS country_rome2rio_name,tbl_city_master.max_zipcode,tbl_city_master.slug AS cityslug,code,countrycode,countryimage,cityimage,tbl_city_master.id as city_id,rome2rio_code',FALSE);

		$this->db->from('tbl_city_attraction_log');
		$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
		$this->db->join('tbl_continent_countries','tbl_continent_countries.country_id = tbl_city_attraction_log.country_id');
		
		$this->db->join('tbl_country_master', 'tbl_country_master.id = tbl_continent_countries.country_id');
		
		$this->db->join('tbl_city_master', 'tbl_city_attraction_log.city_id = tbl_city_master.id');
		$this->db->where('tbl_city_master.total_attraction_time >',0);
		
		if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
		}

		$date=implode("-", array_reverse(explode("/",$_GET['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_GET['budget']) && $_GET['budget'] != '') 
		{
			$budget=explode('-',$this->input->get('budget'));	
			
			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');
			

			if ($budget[1]>=500) 
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);

			}
			else
			{
				
				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);
			}
		}
		
		
		if (isset($_GET['weather']) && $_GET['weather'] != '') 
		{
			
			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');	
			
			$weather = explode('-',$this->input->get('weather'));
			if(count($weather)==4)
			{
				$startweather=-$weather[1];
				$endweather=-$weather[3];
			}
			else if(count($weather)==3)
			{
				$startweather=-$weather[1];
				$endweather=$weather[2];
			}
			else
			{
				$startweather=$weather[0];
				$endweather=$weather[1];
			}

			if($endweather<33)
			{
				$whereweather = "(weather_avg BETWEEN $startweather AND $endweather and tbl_city_weathers.month_id=$month)";	
			}
			else
			{
				$whereweather = "(weather_avg >= $startweather AND tbl_city_weathers.month_id=$month)";
			}
			$this->db->where($whereweather);
		}



		$sq = '';
		for ($i = 0; $i < count($_GET['tags']); $i++)
		{
				$tag = $_GET['tags'][$i];
				if (count($_GET['tags']) == 1) 
				{
					$sq = '(tag_name="' . $tag . '")';
				} 
				else 
				{
					if ($i == 0) 
					{
						$sq .= '(tag_name="' . $tag . '"';

					} 
					else if ($i == count($_GET['tags']) - 1) 
					{
						$sq .= ' OR tag_name="' . $tag . '")';
					} 
					else 
					{
						$sq .= ' OR tag_name="' . $tag . '"';
					}
				}
		}
		$this->db->where($sq);
		$this->db->order_by('country_id','ASC');
		//$this->db->group_by('attraction_id');
		$Q=$this->db->get();
	    //echo $this->db->last_query();die;
		// Get All records
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{
				$data[]=$row;
				$data[$key]['uniqueid']=$_GET['token'];
			}
		}

		
		if(count($data))
		{
			$defaultArray=$data;
			

			$usedVals = array();
			$outArray = array();
			foreach ($defaultArray as $arrayItem)
			{
				if (!in_array($arrayItem['city_id'],$usedVals))
				{
					$outArray[] = $arrayItem; //array with all elements
					$usedVals[] = $arrayItem['city_id'];// get array of all city id's
				}
			}

			
			$originalArray=$outArray;
			

			// Group array by continent->country->city
			$country_city_array = array();
			foreach ( $data as $row ) 
			{
				$country = trim($row['country_id']);
				$continent = trim($row['continent_id']);
				$country_city_array[ $continent ][ $country ][] = $row;
			}


			// Sum total time needed for country
			foreach($country_city_array as $key=>$listsum)
			{
				
				foreach($listsum as $key2=>$listinsidesum)
				{
					$sum=0;
					foreach($listinsidesum as $k=>$finalsum)
					{
						$sum+=$finalsum['tag_hours'];
					}
					$totalcountrytime=ceil($sum/12);
					foreach($listinsidesum as $k=>$finalsum)
					{
						$country_city_array[$key][$key2][$k]['totalcountrytime']=$totalcountrytime;
					}

				}
			}

			//echo "<pre>";print_r($country_city_array);die;
		

			//Get start day and end day.
			if (isset($_GET['days']) && $_GET['days'] != '') 
			{
				$days = $this->input->get('days',TRUE);
				if ($days>32) 
				{
					$startday = (int)($days-2);
					$endday=0;
				} 
				else 
				{
					$startday = (int)($days-2);
					$endday = (int)($days+2);
					
				}
			}

		   
			//call function for multicountry logic
			$multiplecontries = $this->selectMultiCountriesWithTags($country_city_array,$startday,$endday,$originalArray);
			return $multiplecontries;

		}
		else
		{
			return $data;
		}


	}

	function MultiCountriesWithNoTags()
	{
		$data = array();
		$multiplecontries=array();
		$countryrome2rioname=$this->input->get('ocode',TRUE);
		$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_continent_countries.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_continent_countries.continent_id,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,tbl_city_master.latitude as citylatitude,tbl_city_master.longitude as citylongitude,country_conclusion,total_days,country_total_days,tbl_city_master.rome2rio_name as city_rome2rio_name,tbl_country_master.rome2rio_name as country_rome2rio_name,tbl_country_master.id as countryid,tbl_city_master.max_zipcode,tbl_city_master.slug as cityslug,code,countrycode,countryimage,cityimage,rome2rio_code',FALSE);
		$this->db->from('tbl_city_master');
		

		//$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		
		
	    $this->db->join('tbl_continent_countries', 'tbl_continent_countries.country_id=tbl_city_master.country_id');
		
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_continent_countries.country_id');
			
		$this->db->where('tbl_city_master.id in (select city_id from tbl_city_paidattractions where city_id=tbl_city_master.id)');
		$this->db->where('tbl_city_master.total_attraction_time >',0);
		
		$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));

		/*
		if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
		}
		*/
		

		$date=implode("-", array_reverse(explode("/",$_GET['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_GET['budget']) && $_GET['budget'] != '') 
		{
			$budget=explode('-',$this->input->get('budget'));	
			
			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');
			

			if ($budget[1]>=500) 
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);

			}
			else
			{
				
				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_GET['accomodation']);
			}
		}
		
	
		if (isset($_GET['weather']) && $_GET['weather'] != '') 
		{
			
			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');	
			
			$weather = explode('-',$this->input->get('weather'));
			if(count($weather)==4)
			{
				$startweather=-$weather[1];
				$endweather=-$weather[3];
			}
			else if(count($weather)==3)
			{
				$startweather=-$weather[1];
				$endweather=$weather[2];
			}
			else
			{
				$startweather=$weather[0];
				$endweather=$weather[1];
			}

			if($endweather<33)
			{
				$whereweather = "(weather_avg BETWEEN $startweather AND $endweather and tbl_city_weathers.month_id=$month)";	
			}
			else
			{
				$whereweather = "(weather_avg >= $startweather AND tbl_city_weathers.month_id=$month)";
			}
			$this->db->where($whereweather);
		}


		$this->db->distinct();
		//$this->db->group_by('tbl_city_master.id');
		//$this->db->order_by('count(tbl_city_master.country_id)', 'DESC');
		$Q = $this->db->get();
		//echo $this->db->last_query();die;

		
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $key=>$row) {
				$data[] = $row;
				$data[$key]['uniqueid']=$_GET['token'];
			}
		}

		//echo "<pre>";print_r($data);die;

		if(count($data))
		{

			$defaultArray=$data;
			

			$usedVals = array();
			$outArray = array();
			foreach ($defaultArray as $arrayItem)
			{
				if (!in_array($arrayItem['id'],$usedVals))
				{
					$outArray[] = $arrayItem;
					$usedVals[] = $arrayItem['id'];
				}
			}
			
			$originalArray=$outArray;

			
			
			$country_city_array = array();
			foreach ( $data as $row ) 
			{
				$country = trim($row['country_id']);
				$continent = trim($row['continent_id']);
				$country_city_array[ $continent ][ $country ][] = $row;
			}

			
				
			foreach($country_city_array as $key=>$listsum)
			{
				foreach($listsum as $key2=>$listinsidesum)
				{
					$sum=0;
					foreach($listinsidesum as $k=>$finalsum)
					{
						$sum+=ceil($finalsum['total_days']);
					}
					$totalcountrytime=ceil($sum);
					foreach($listinsidesum as $k=>$finalsum)
					{
						$country_city_array[$key][$key2][$k]['totalcountrytime']=$totalcountrytime;
					}

				}
			}
			if (isset($_GET['days']) && $_GET['days'] != '') 
			{
				$days = $this->input->get('days',TRUE);
				if ($days>32) 
				{
					$startday = (int)($days-2);
					$endday=0;
				} 
				else 
				{
					$startday = (int)($days-2);
					$endday = (int)($days+2);
					
				}
			}


			$multiplecontries = $this->selectMultiCountriesWithNoTags($country_city_array,$startday,$endday,$originalArray);
			return $multiplecontries;

		}
		else
		{
			return $data;
		}



	}


	function selectMultiCountriesWithNoTags($data,$startday,$endday,$originalArray)
	{
		$grouped_by_countries=array();
		$grouped_types = $data;
		
		
		foreach($grouped_types as $mainkey=>$mainlist)
		{

			foreach($mainlist as $subkey=>$sublist)
			{
				$neededArray[]=$sublist[0];					
			}

		}




		foreach($originalArray as $arg)
		{
			$grouped_by_countries[$arg['country_id']][] = $arg;
		}


		foreach($neededArray as $k=>$l)
		{	


			$sessionStorage=$this->session->userdata('storage');
			if(count($sessionStorage) && $sessionStorage!='')
			{	

				 $searchkey = array_search($l['country_id'], array_column($sessionStorage, 'country_id'));

				
				if($searchkey!='')
				{

					$neededArray[$k]['timetoreach']=$sessionStorage[$searchkey]['distance'];
					$neededArray[$k]['actualtime']=$sessionStorage[$searchkey]['actualtime'];
					
						
				}
				else
				{
					$storage=array();
					if($this->session->userdata('storage'))
					{
						$storage=$this->session->userdata('storage');
					}

					$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($_GET['start_city']) . '&dName=' . urlencode($l['rome2rio_country_name']) . '';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_URL,$url);
					$result=curl_exec($ch);
					curl_close($ch);
					$json = json_decode($result, true);
					$neededArray[$k]['actualtime']=$json['routes'][0]['totalDuration'];
					$hours = floor($json['routes'][0]['totalDuration'] / 60);
					$minutes = $json['routes'][0]['totalDuration'] % 60;
						
					$tempstorage[]=array(
							'country_id'=>$l['country_id'],
							'distance'=>$hours . ' Hrs ' . $minutes . ' Mins',
							'actualtime'=>$json['routes'][0]['totalDuration']
					);
					$neededArray[$k]['timetoreach']=$hours . ' Hrs ' . $minutes . ' Mins';
					

					if(count($storage))
					{
						$finalStorage=array_merge($storage,$tempstorage);
					}
					else
					{
						$finalStorage=$tempstorage;
					}
					$this->session->set_userdata('storage',$finalStorage);

				}
			}
			else
			{
				$storage=array();
				if($this->session->userdata('storage'))
				{
					$storage=$this->session->userdata('storage');
				}




				$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($_GET['start_city']) . '&dName=' . urlencode($l['rome2rio_country_name']) . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL,$url);
				$result=curl_exec($ch);
				curl_close($ch);
				$json = json_decode($result, true);
					
				//echo $json['routes'][0]['totalDuration']."<br/>";

				$hours = floor($json['routes'][0]['totalDuration'] / 60);
				$minutes = $json['routes'][0]['totalDuration'] % 60;
				$neededArray[$k]['actualtime']=$json['routes'][0]['totalDuration'];
				$neededArray[$k]['timetoreach']=$hours . ' Hrs ' . $minutes . ' Mins';

				$tempstorage[]=array(
						'country_id'=>$l['country_id'],
						'distance'=>$hours . ' Hrs ' . $minutes . ' Mins',
						'actualtime'=>$json['routes'][0]['totalDuration'],
				);

				if(count($storage))
				{
					$finalStorage=array_merge($storage,$tempstorage);
				}
				else
				{
					$finalStorage=$tempstorage;
				}
				$this->session->set_userdata('storage',$finalStorage);


			}
		}	

		
		
		
		$traveltime = explode('-',$this->input->get('traveltime'));

		if ($traveltime[1]>25) {
			$approx = 0.20 * $traveltime[1];
			$starttime = round($traveltime[1]-$approx);
			$endtime = 0;
			
		} else {
			
			$time = explode('-',$_GET['traveltime']);
			$start_time=$time[0];
			$end_time=$time[1];
			$starttime = round($start_time-($start_time*0.20));
			$endtime = round($end_time+($end_time*0.20));
		}

		foreach($neededArray as $needkey=>$needlist)
		{
				$hrs = round($needlist['actualtime']/60);
				if($endtime==0)
				{
					
					if($starttime < $hrs)
					{
						unset($neededArray[$needkey]);		
					}
				}
				else if(($starttime > $hrs || $endtime < $hrs) && $endtime !=0) 
				{
					unset($neededArray[$needkey]);
				} 

				//echo $starttime."-->".$hrs."-->".$endtime."<br/>";
		}



		if(!count($neededArray))
		{
			return $neededArray;
		}
		
		
		foreach($neededArray as $arg)
		{
			$neededArray1[$arg['continent_id']][] = $arg;
		}

		
		$mainArray=$grouped_types;
		$copyArray=$grouped_types;
		
		$multiple_arr = array();
		
		/* Following code will make various combinations of countries  */
		$new=array();
		$key=0;

		//echo "<pre>";print_r($neededArray1);die;

		foreach($neededArray1 as $arg1)
		{
			$num = count($arg1);
			$total = pow(2, $num);
			// initially $key=0 was here. But i changed the code and place it before main loop.
			for ($i = 0; $i < $total; $i++) 
			{  
				for ($j = 0; $j < $num; $j++) 
				{ 
					 if (pow(2, $j) & $i)
					 { 
						$new[$key][]=$arg1[$j];
					 }
				} 

				$key++;
			}

		}

		//echo "<pre>";print_r($new);die;
		

		$maxcombination=0;
		
	
		foreach($new as $finalkey=>$finallist)
		{
			if(count($new[$finalkey])<2)
			{
				unset($new[$finalkey]);	
			}
			else
			{
				$totalsum=0;
				
				foreach($finallist as $sumlist)
				{
					$totalsum+=ceil($sumlist['totalcountrytime']);
				}
				if($endday==0)
				{
					if($totalsum < $startday)
					{
						unset($new[$finalkey]);
					}
					else
					{
						$new[$finalkey]['counter']=count($finallist);
						if(count($finallist)>$maxcombination)
						{
							$maxcombination=count($finallist);
						}
					}

				}
				else if($totalsum<= $startday || $totalsum>=$endday)
				{
					unset($new[$finalkey]);
				}	
				else
				{
					$new[$finalkey]['counter']=count($finallist);
					if(count($finallist)>$maxcombination)
					{
						$maxcombination=count($finallist);
					}
					
				}

			}	
		}

		

		if(!count($new))
		{
			return $new;
		}

		
		$finalsort = array();
		foreach($new as $k=>$v) 
		{
			$finalsort['counter'][$k] = $v['counter'];
		}
		array_multisort($finalsort['counter'], SORT_DESC,$new);

		
		
		
		$finalsort = array();
		$keyToRemove=array();
		
		for($i=0;$i<count($new);$i++)
		{
			if(isset($new[$i]['counter']))
			{
				unset($new[$i]['counter']);
			}

			for($j=count($new)-1;$j>$i;$j--)
			{

				if(isset($new[$j]['counter']))
				{
					unset($new[$j]['counter']);
				}

				if(isset($new[$j]) && $new[$i])
				{

				

				$c=count(array_intersect(array_column($new[$i], 'country_id'),array_column($new[$j], 'country_id')));	
				
				if($c==count($new[$j]))
				{
					$keyToRemove[]=$j;
					continue;
				}

			}
				
			}

		}


		$countryArray=array();
		foreach($new as $mainkey=>$mainlist)
		{
			if(in_array($mainkey,$keyToRemove))
			{
				unset($new[$mainkey]);
			}
			else
			{
				unset($new[$mainkey]['counter']);
				$conid='';$c=0;
				foreach($mainlist as $subkey=>$sublist)
				{

					$countryArray[]=$sublist['country_id'];
					if($c==count($mainlist)-1)
					{
						$conid .=$sublist['country_id'];
					}
					else
					{
						$conid .=$sublist['country_id'].'-';	
					}
					$c++;
				}	
				$new[$mainkey]['encryptkey']=string_encode($conid);
			}

		}

		$arrayToWrite=array();
		foreach($grouped_by_countries as $key=>$list)
		{
			if(in_array($key,$countryArray))
			{
				$arrayToWrite[$key]=$grouped_by_countries[$key];
			}
		}

		$this->createMultiCountrySearchFile($new,$arrayToWrite);
		return $new;
	}

	function selectMultiCountriesWithTags($data,$startday,$endday,$originalArray)
	{
		//echo "<pre>";print_r($data);die;
		$grouped_by_countries=array();
		//Origincal array
		$grouped_types = $data;
		
		// following loop with take every first element and store it in neededArray.The purpose of this function is to get rome2rio time.
		foreach($grouped_types as $mainkey=>$mainlist)
		{

			foreach($mainlist as $subkey=>$sublist)
			{
				$neededArray[]=$sublist[0];					
			}

		}

			//echo "<pre>";print_r($neededArray);die;

		// following loop will group country id and respective cities.
		foreach($originalArray as $arg)
		{
			$grouped_by_countries[$arg['country_id']][] = $arg;
		}


		// Following loop will make rome2rio call.If combination of destination and origin alreay exists in session it will goes in IF otherwise will go in ELSE.
		foreach($neededArray as $k=>$l)
		{	
			$sessionStorage=$this->session->userdata('storage');
			
			if(count($sessionStorage) && $sessionStorage!='')
			{	

				 $searchkey = array_search($l['country_id'], array_column($sessionStorage, 'country_id'));

				
				if($searchkey!='')
				{

					$neededArray[$k]['timetoreach']=$sessionStorage[$searchkey]['distance'];
					$neededArray[$k]['actualtime']=$sessionStorage[$searchkey]['actualtime'];
					
						
				}
				else
				{
					$storage=array();
					if($this->session->userdata('storage'))
					{
						$storage=$this->session->userdata('storage');
					}

					$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($_GET['start_city']) . '&dName=' . urlencode($l['rome2rio_country_name']) . '';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_URL,$url);
					$result=curl_exec($ch);
					curl_close($ch);
					$json = json_decode($result, true);
					$neededArray[$k]['actualtime']=$json['routes'][0]['totalDuration'];
					$hours = floor($json['routes'][0]['totalDuration'] / 60);
					$minutes = $json['routes'][0]['totalDuration'] % 60;
						
					$tempstorage[]=array(
							'country_id'=>$l['country_id'],
							'distance'=>$hours . ' Hrs ' . $minutes . ' Mins',
							'actualtime'=>$json['routes'][0]['totalDuration']
					);
					$neededArray[$k]['timetoreach']=$hours . ' Hrs ' . $minutes . ' Mins';
					

					if(count($storage))
					{
						$finalStorage=array_merge($storage,$tempstorage);
					}
					else
					{
						$finalStorage=$tempstorage;
					}
					$this->session->set_userdata('storage',$finalStorage);

				}
			}
			else
			{
				$storage=array();
				if($this->session->userdata('storage'))
				{
					$storage=$this->session->userdata('storage');
				}


				$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($_GET['start_city']) . '&dName=' . urlencode($l['rome2rio_country_name']) . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL,$url);
				$result=curl_exec($ch);
				curl_close($ch);
				$json = json_decode($result, true);
				

				$hours = floor($json['routes'][0]['totalDuration'] / 60);
				$minutes = $json['routes'][0]['totalDuration'] % 60;
				$neededArray[$k]['actualtime']=$json['routes'][0]['totalDuration'];
				$neededArray[$k]['timetoreach']=$hours . ' Hrs ' . $minutes . ' Mins';

				$tempstorage[]=array(
						'country_id'=>$l['country_id'],
						'distance'=>$hours . ' Hrs ' . $minutes . ' Mins',
						'actualtime'=>$json['routes'][0]['totalDuration'],
				);

				if(count($storage))
				{
					$finalStorage=array_merge($storage,$tempstorage);
				}
				else
				{
					$finalStorage=$tempstorage;
				}
				$this->session->set_userdata('storage',$finalStorage);


			}
		}	

		/* At this stage neededarray will contains 2 addition key. 1) actualtime and 2) timetoreach */
		
		
		$traveltime = explode('-',$this->input->get('traveltime'));

		if ($traveltime[1]>25) {
			$approx = 0.20 * $traveltime[1];
			$starttime = round($traveltime[1]-$approx);
			$endtime = 0;
			
		} else {
			
			$time = explode('-',$_GET['traveltime']);
			$start_time=$time[0];
			$end_time=$time[1];
			$starttime = round($start_time-($start_time*0.20));
			$endtime = round($end_time+($end_time*0.20));
		}


		//this loop will remove countries which are not reachable in a given intervals.

		foreach($neededArray as $needkey=>$needlist)
		{
				$hrs = round($needlist['actualtime']/60);
				if($endtime==0)
				{
					
					if($starttime < $hrs)
					{
						unset($neededArray[$needkey]);		
					}
				}
				else if(($starttime > $hrs || $endtime < $hrs) && $endtime !=0) 
				{
					unset($neededArray[$needkey]);
				} 

				//echo $starttime."-->".$hrs."-->".$endtime."<br/>";
		}

		// if all countries are removed then blank array will be removed.
		if(!count($neededArray))
		{
			return $neededArray;
		}
		
		

		// following loop will create group by continent.
		foreach($neededArray as $arg)
		{
			$neededArray1[$arg['continent_id']][] = $arg;
		}

		
		$mainArray=$grouped_types;
		$copyArray=$grouped_types;
		
		$multiple_arr = array();
		
		/* Following code will make various combinations of countries  */
		$new=array();
		$key=0;

		// Following loop will make unique combination

		foreach($neededArray1 as $arg1)
		{
			$num = count($arg1);
			$total = pow(2, $num);
			// initially $key=0 was here. But i changed the code and place it before main loop.
			for ($i = 0; $i < $total; $i++) 
			{  
				for ($j = 0; $j < $num; $j++) 
				{ 
					 if (pow(2, $j) & $i)
					 { 
						$new[$key][]=$arg1[$j];
					 }
				} 

				$key++;
			}

		}


		$maxcombination=0;

		foreach($new as $finalkey=>$finallist)
		{
			if(count($new[$finalkey])<2)
			{
				unset($new[$finalkey]);	
			}
			else
			{
				$totalsum=0;
				foreach($finallist as $sumlist)
				{
					$totalsum+=ceil($sumlist['totalcountrytime']);
				}
				 if($endday==0)
				{
					if($totalsum < $startday)
					{
						unset($new[$finalkey]);
					}
					else
					{
						$new[$finalkey]['counter']=count($finallist);
						if(count($finallist)>$maxcombination)
						{
							$maxcombination=count($finallist);
						}
					}

				}
				else if($totalsum<= $startday || $totalsum>=$endday)
				{
					unset($new[$finalkey]);
				}	
				else
				{
					$new[$finalkey]['counter']=count($finallist);
					if(count($finallist)>$maxcombination)
					{
						$maxcombination=count($finallist);
					}
				}

			}	
		}



		if(!count($new))
		{
			return $new;
		}


		$finalsort = array();
		foreach($new as $k=>$v) 
		{
			$finalsort['counter'][$k] = $v['counter'];
		}
		array_multisort($finalsort['counter'], SORT_DESC,$new);

		
		
		
		$finalsort = array();
		$keyToRemove=array();
		
		for($i=0;$i<count($new);$i++)
		{
			if(isset($new[$i]['counter']))
			{
				unset($new[$i]['counter']);
			}

			for($j=count($new)-1;$j>$i;$j--)
			{

				if(isset($new[$j]['counter']))
				{
					unset($new[$j]['counter']);
				}

				if(isset($new[$j]) && $new[$i])
				{

				

				$c=count(array_intersect(array_column($new[$i], 'country_id'),array_column($new[$j], 'country_id')));	
				
				if($c==count($new[$j]))
				{
					$keyToRemove[]=$j;
					continue;
				}

			}
				
			}

		}


		$countryArray=array();
		foreach($new as $mainkey=>$mainlist)
		{
			if(in_array($mainkey,$keyToRemove))
			{
				unset($new[$mainkey]);
			}
			else
			{
				unset($new[$mainkey]['counter']);
				$conid='';$c=0;
				foreach($mainlist as $subkey=>$sublist)
				{
					$countryArray[]=$sublist['country_id'];
					if($c==count($mainlist)-1)
					{
						$conid .=$sublist['country_id'];
					}
					else
					{
						$conid .=$sublist['country_id'].'-';	
					}
					$c++;
				}	
				$new[$mainkey]['encryptkey']=string_encode($conid);
			}

		}

		//echo "<pre>df";print_r($countryArray);die;

		$arrayToWrite=array();
		//echo "<pre>";print_r($grouped_by_countries);die;
		foreach($grouped_by_countries as $key=>$list)
		{
			if(in_array($key,$countryArray))
			{
				$arrayToWrite[$key]=$grouped_by_countries[$key];
			}
		}

		//echo "<pre>";print_r($arrayToWrite);die;

		$this->createMultiCountrySearchFile($new,$arrayToWrite);
		return $new;
			
		
	}


	
	function getShortestDistanceForMultiple($start_city, $end_city) 
	{
		$url = 'http://free.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&query&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		$content = file_get_contents($url);
		$json = json_decode($content, true);

		if (!isset($json['routes'][0]['totalDuration']) && $json['routes'][0]['totalDuration'] == '') {
			return 'na';
		} else {
			return $json['routes'][0]['totalDuration'];
		}
	}

	


	function getDays() {
		$data = array();
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (flag_plus=1) THEN concat(days_range,'+') ELSE days_range END) as days_range", FALSE);
		$this->db->from('tbl_days_master');
		$this->db->order_by('CAST(days_range AS UNSIGNED)', 'ASC');
		$Q = $this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function getWeathers()
	{
		$data=array();
		$this->db->select("SQL_CALC_FOUND_ROWS id,(case when (weather_temperature_to=999999.00) THEN concat(weather_temperature_from,'+') ELSE concat(weather_temperature_from,' - ',weather_temperature_to) END) as weather", FALSE);
		$Q=$this->db->get('tbl_weather_master');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getBudget()
	{
		$data=array();
		$this->db->select("id,(case when (budget_hotel_per_night_to <1) THEN concat('$',budget_hotel_per_night_from,'+') ELSE concat('$',budget_hotel_per_night_from,' - ','$',budget_hotel_per_night_to) END) as budget", FALSE);
		$this->db->from('tbl_budget_master');
		$this->db->where('tbl_budget_master.id in (select budget_id from tbl_city_master where budget_id=tbl_budget_master.id)');
		$this->db->order_by('budget_hotel_per_night_from','ASC');
		$Q = $this->db->get();
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
		}
		return $data;
	}


	function getLatandLongOfCity($city_id)
	{
		$Q=$this->db->query('select id,latitude as citylatitude,longitude as citylongitude,country_id,cityimage,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countrybanner from tbl_country_master where id=tbl_city_master.country_id) as countrybanner,city_name,travelguide from tbl_city_master where md5(id)="'.$city_id.'"');
		return $Q->row_array();
	}

	function getBasicCityDetails($city_id)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where md5(id)="'.$city_id.'"');
		return $Q->row_array();
	}

	function getBasicCityDetailsFromName($name)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where city_name="'.$name.'"');
		return $Q->row_array();
	}

	

	function writeAttractionsInFile($city_id)
	{
		$data=array();
		
		$Q1=$this->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q1->result_array();


		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,(select longitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylongitude,(select latitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylatitude from tbl_city_paidattractions where md5(city_id)="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{

				$knwofortag=array();
				$knwofortag=explode(',',$row['attraction_known_for']);
				$known_tags='';
				for($i=0;$i<count($knwofortag);$i++)
				{
					$key = array_search($knwofortag[$i], array_column($tags, 'id'));
					$known_tags .=$tags[$key]['tag_name'].',';
				}
				if($known_tags!='')	
				{
					$known_tags=substr($known_tags, 0,-1);
				}

				$data[$key]['type']='Feature';
				$data[$key]['geometry']=array(
						'type'=>'Point',
						);
				$data[$key]['geometry']['coordinates']=array(
						'0'=>$row['attraction_long'],
						'1'=>$row['attraction_lat'],
						);
				$data[$key]['properties']=array(
						  'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$row['attraction_name']),
						  'knownfor'=>$row['attraction_known_for'],
						  'known_tags'=>$known_tags,
						  'tag_star'=>$row['tag_star'],
						  //'address'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_address']),
						  'getyourguide'=>str_replace(array("\n", "\r","'"),'',$row['attraction_getyourguid']),
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$row['citylongitude'],
						'1'=>$row['citylatitude'],
						);

			}
			
			$randomstring=$city_id;
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);
		}

	}

	function writeAttractionsInFilemd5($city_id)
	{

		$data=array();
		$Q1=$this->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q1->result_array();

		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,(select longitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylongitude,(select latitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylatitude from tbl_city_paidattractions where md5(city_id)="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			$c=0;
			//$waypointsstr='';
			foreach($Q->result_array() as $key=>$row)
			{
				
				$knwofortag=array();
				$knwofortag=explode(',',$row['attraction_known_for']);
				$known_tags='';
				for($i=0;$i<count($knwofortag);$i++)
				{
					$key = array_search($knwofortag[$i], array_column($tags, 'id'));
					$known_tags .=$tags[$key]['tag_name'].',';
				}
				if($known_tags!='')	
				{
					$known_tags=substr($known_tags, 0,-1);
				}

				$data[$key]['type']='Feature';
				$data[$key]['geometry']=array(
						'type'=>'Point',
						);
				$data[$key]['geometry']['coordinates']=array(
						'0'=>$row['attraction_long'],
						'1'=>$row['attraction_lat'],
						);
				$data[$key]['properties']=array(
						 'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$row['attraction_name']),
						  'knownfor'=>$row['attraction_known_for'],
						  'known_tags'=>$known_tags,
						  'tag_star'=>$row['tag_star'],
						  //'address'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_address']),
						  'getyourguide'=>str_replace(array("\n", "\r","'"),'',$row['attraction_getyourguid']),
						  'attractionid'=>md5($row['id']),
						  'cityid'=>$city_id,
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$row['citylongitude'],
						'1'=>$row['citylatitude'],
						);

			}
			$randomstring=$city_id;
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);

			
		}

	}

	function getUserRecommededAttractionsForNewCity($cityfile,$uniqueid)
	{
		if(!file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile))
		{
			$getInputs=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);
			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{

				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractions($ids,$cityfile,$uniqueid);
			}
			else
			{
				$this->writeAllUserAttraction($cityfile,$uniqueid);
			}
			
			return 1;
		}
		return 2;
	}

	function getUserRecommededAttractionsForCountry($cityfile,$uniqueid)
	{
		if(!file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile))
		{
			$data=array();
			$getInputs=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);
			
			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{
				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractions($ids,$cityfile,$uniqueid);
			
			}
			else
			{
				$this->writeAllUserAttraction($cityfile,$uniqueid);
			}
			return 1;
		}
		return 2;

		/* do if else if you do not want to saver state. */
	
	}


	function getUserRecommededAttractionsWayPoints()
	{
		if(!file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/waypoints_'.$cityfile))
		{

		}
	}


	function getIDS($ids)
	{
		$data=array();
		$this->db->select('id');
		$this->db->from('tbl_tag_master');
		for($i=0;$i<count($ids);$i++)
		{
			$this->db->or_where('tag_name',$ids[$i]);
		}
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
		}
		$array = array_column($data, 'id');
		return $array;
	}

	function getCityDetails($attractionid)
	{
		$data=array();

		if($_POST['category']>5 && $_POST['category']<1)
		{
			$arr=array(
						'name'=>'Nothing To Show',
						'details'=>'Nothing To Show',
					);	
			return json_encode($arr);
		}
		else
		{
			if($_POST['category']==1)
			{
				return $this->getAttractionDetailsOfCity($attractionid);
			}
			else
			{
				return $this->getOtherDetailsOfCity($attractionid);	
			}
		}

		

	}

	function getAttractionDetailsOfCity($attractionid)
	{
		$this->db->select('tbl_city_paidattractions.id,image,attraction_name,attraction_details,attraction_address,attraction_contact,attraction_website,attraction_public_transport,attraction_timing,attraction_time_required,attraction_wait_time,attraction_buy_ticket,tag_name,attraction_admissionfee');
		$this->db->from('tbl_city_paidattractions');
		$this->db->join('tbl_city_attraction_log','tbl_city_attraction_log.attraction_id=tbl_city_paidattractions.id');
		$this->db->join('tbl_tag_master','tbl_tag_master.id=tbl_city_attraction_log.tag_id');	
		$this->db->where('md5(tbl_city_paidattractions.id)',$attractionid);
		$this->db->where('md5(tbl_city_paidattractions.city_id)',$_POST['cityid']);
		$Q=$this->db->get();
		$data=$Q->result_array();
		$known='';
		foreach($data as $row)
		{
			$known .=$row['tag_name'].', ';
		}

		$data[0]['known']=substr($known,0,-2);
		
		if(count($data))
		{
			 $arr=array(
				'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_name']),
				'details'=>$data[0]['attraction_details'],
				'attraction_address'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),nl2br($data[0]['attraction_address'])),
				'attraction_contact'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_contact']),
				'attraction_website'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_website']),
				'attraction_public_transport'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_public_transport']),
				'attraction_timing'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),nl2br($data[0]['attraction_timing'])),
				'attraction_time_required'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_time_required']),
				'attraction_wait_time'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_wait_time']),
				'attraction_buy_ticket'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['attraction_buy_ticket']),
				'attraction_admissionfee'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),nl2br($data[0]['attraction_admissionfee'])),				
				'image'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['image']),
				'tag_name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data[0]['known']),
			);	
		}
		else
		{
			$arr=array(
						'name'=>'Nothing To Show',
						'details'=>'Nothing To Show',
					);	
		}

		return json_encode($arr);
	}

	function getOtherDetailsOfCity($attractionid)
	{
		$known='';
		if($_POST['category']==2)
		{
			$known = 'Relaxation & Spa';
			$this->db->select('tbl_city_relaxationspa.id,image,ras_name as attraction_name,ras_description as attraction_details,ras_address as attraction_address,ras_contact as attraction_contact,ras_website as attraction_website,ras_timing as attraction_timing');
			$this->db->from('tbl_city_relaxationspa');
		}
		else if($_POST['category']==3)
		{
			$known = 'Food & Nightlife';
			$this->db->select('tbl_city_restaurants.id,image,ran_name as attraction_name,ran_description as attraction_details,ran_address as attraction_address,ran_contact as attraction_contact,ran_website as attraction_website,ran_timing as attraction_timing');
			$this->db->from('tbl_city_restaurants');
		}
		else if($_POST['category']==4)
		{
			$known = 'Sports & Adventure';
			$this->db->select('tbl_city_sports_adventures.id,image,adventure_name as attraction_name,adventure_details as attraction_details,adventure_address as attraction_address,adventure_contact as attraction_contact,adventure_website as attraction_website,adventure_open_close_timing as attraction_timing,adventure_time_required as attraction_time_required,adventure_wait_time as attraction_wait_time,adventure_buy_ticket as attraction_buy_ticket,adventure_nearest_public_transport as attraction_public_transport,adventure_admissionfee as attraction_admissionfee');
			$this->db->from('tbl_city_sports_adventures');
		}
		else if($_POST['category']==5)
		{
			$known = 'Sports & Adventure';
			$this->db->select('tbl_city_stadiums.id,image,stadium_name as attraction_name,stadium_description as attraction_details,stadium_address as attraction_address,stadium_contact as attraction_contact,stadium_website as attraction_website,	stadium_timing as attraction_timing');
			$this->db->from('tbl_city_stadiums');
		}
		$this->db->where('md5(id)',$attractionid);
		$this->db->where('md5(city_id)',$_POST['cityid']);
		$Q=$this->db->get();
		$data=$Q->row_array();
		if($_POST['category']==4)
		{
			
			$attraction_public_transport=$data['attraction_public_transport'];
			$attraction_time_required=$data['attraction_time_required'];
			$attraction_wait_time=$data['attraction_wait_time'];
			$attraction_buy_ticket=$data['attraction_buy_ticket'];
			$attraction_admissionfee=nl2br($data['attraction_admissionfee']);
		}
		else
		{
			$attraction_public_transport='';
			$attraction_time_required='';
			$attraction_wait_time='';
			$attraction_buy_ticket='';
			$attraction_admissionfee='';
		}
		
		if(count($data))
		{
			$arr=array(
				'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data['attraction_name']),
				'details'=>$data['attraction_details'],
				'attraction_address'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),nl2br($data['attraction_address'])),
				'attraction_contact'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data['attraction_contact']),
				'attraction_website'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data['attraction_website']),
				'attraction_public_transport'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$attraction_public_transport),
				'attraction_timing'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),nl2br($data['attraction_timing'])),
				'attraction_time_required'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$attraction_time_required),
				'attraction_wait_time'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$attraction_wait_time),
				'attraction_buy_ticket'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$attraction_buy_ticket),
				'attraction_admissionfee'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$attraction_admissionfee),
				'image'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$data['image']),
				'tag_name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$known),
			);	

			
		}
		else
		{
			$arr=array(
						'name'=>'Nothing To Show',
						'details'=>'Nothing To Show',
					);	
		}
		return json_encode($arr);
	}

	function writeAllUserAttraction($city_id,$uniqueid)
	{
		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
		if(!file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id))
		{
			$this->writeAttractionsInFile($city_id);
		}
		
		$attraction_json = file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id);
		$attractionarr_decode = json_decode($attraction_json,TRUE);
		
		$attraction_decode=$this->mergeOtherAttractions($attractionarr_decode,$city_id);
		
		$attraction_decode=$this->haversineGreatCircleDistance($attraction_decode);

		/*
		$attraction_decode[0]['distance']=0;
		for($i=1;$i<count($attraction_decode);$i++)
		{
			$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
			$attraction_decode[$i]['distance']=$distance;
		}
		*/
		$finalsort = array();
		foreach($attraction_decode as $k=>$v) 
		{
			$finalsort['distance'][$k] = $v['distance'];
			$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
		}
		array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);

		//echo "<pre>";print_r($attraction_decode);die;

		foreach($attraction_decode as $k=>$v) 
		{
			$attraction_decode[$k]['isselected']=1;
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}

		$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}

	/*function haversineGreatCircleDistance(
		$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		 $rad = M_PI / 180;
		return acos(sin($latitudeTo*$rad) * sin($latitudeFrom*$rad) + cos($latitudeTo*$rad) * cos($latitudeFrom*$rad) * cos($longitudeTo*$rad - $longitudeFrom*$rad)) * 6371;
	
	}*/

	function haversineGreatCircleDistance($attraction_decode)
	{
		require_once('travel/tsp.php');
		$tsp = TspBranchBound::getInstance();
		foreach($attraction_decode as $key=>$list)
		{
			
			$lat=$list['geometry']['coordinates'][1];
			$lng=$list['geometry']['coordinates'][0];
			$tsp->addLocation(array('id'=>$key, 'latitude'=>$lat, 'longitude'=>$lng));
		}

		$sortedArray = $tsp->solve();
		$sortkeys=array();
		foreach ($sortedArray as $value) {
			
			foreach($value as $key=>$list)
			{
				$sortkeys[]=$list[0];
			}
		}
		
		$finalarray=array();
		foreach($sortkeys as $key=>$list)
		{
			$finalarray[]=$attraction_decode[$list];
			$finalarray[$key]['distance']=$key;
		}
		return $finalarray;

	}


	function haversineGreatCircleDistance_old($attraction_decode,$dummyarr=array())
	{
		static $statCnt=0;
		
		$finalarr=array();
		if(count($dummyarr)<1)
		{
			$dummyarr[]=$attraction_decode[0];
			$dummyarr[0]['distance']=$statCnt;
			unset($attraction_decode[0]);
			$attraction_decode=array_values($attraction_decode);
		}
		$statCnt++;
		$olddis=0;
		$currentkey=count($dummyarr);
		$lastkey=count($dummyarr)-1;
		$prevlat=$dummyarr[$lastkey]['geometry']['coordinates'][1];
		$prevlng=$dummyarr[$lastkey]['geometry']['coordinates'][0];
			
		for($i=0;$i<count($attraction_decode);$i++)
		{
			$currentlat=$attraction_decode[$i]['geometry']['coordinates'][1];
			$currentlng=$attraction_decode[$i]['geometry']['coordinates'][0];
			
			$dis=$this->formula($prevlat,$prevlng,$currentlat,$currentlng);

			if($i==0)
			{
				$dummyarr[$currentkey]=$attraction_decode[$i];
				$olddis=$dis;
				$removekey=$i;
			}
			else
			{
				if($dis<=$olddis)
				{
					$dummyarr[$currentkey]=$attraction_decode[$i];
					$olddis=$dis;
					$removekey=$i;
				}
			}

		}
		unset($attraction_decode[$removekey]);
		
		if(count($attraction_decode)<1)
		{
			$dummyarr[$currentkey]['distance']=$statCnt;
			return $dummyarr;
		}
		else
		{
			$dummyarr[$currentkey]['distance']=$statCnt;
			return $this->haversineGreatCircleDistance(array_values($attraction_decode),$dummyarr);
		}	
	}

	function formula($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		$rad = M_PI / 180;
		return acos(sin($latitudeTo*$rad) * sin($latitudeFrom*$rad) + cos($latitudeTo*$rad) * cos($latitudeFrom*$rad) * cos($longitudeTo*$rad - $longitudeFrom*$rad)) * 6371;
	}


	function getSelectedAttractions($ids,$city_id,$uniqueid)
	{
		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
		if(!file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id))
		{
			$this->writeAttractionsInFile($city_id);

		}

		$attraction_json = file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id);
		$attractionarr_decode = json_decode($attraction_json,TRUE);
		$attraction_decode=$this->otherAttractions($ids,$attractionarr_decode,$city_id);

		$attraction_decode=$this->haversineGreatCircleDistance($attraction_decode);

		/*
		$attraction_decode[0]['distance']=0;
		for($i=1;$i<count($attraction_decode);$i++)
		{
			$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
			$attraction_decode[$i]['distance']=$distance;
		}*/

		$finalsort = array();
		foreach($attraction_decode as $k=>$v) 
		{
			$finalsort['distance'][$k] = $v['distance'];
			$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
		}
		array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);

		if(count($attraction_decode))
		{
			foreach($attraction_decode as $key=>$attlist)
			{

				$ints=explode(',',$attlist['properties']['knownfor']);
				$intersectionofatt=array_intersect($ids,$ints);			
				if (count($intersectionofatt) > 0 || $attlist['properties']['tag_star']==1 || $attlist['properties']['tag_star']==2) 
				{
					$attraction_decode[$key]['isselected']=1;
					$attraction_decode[$key]['order']=$key;
				}
				else
				{
					$attraction_decode[$key]['isselected']=0;
					$attraction_decode[$key]['order']=99999;
				}
				$attraction_decode[$key]['tempremoved']=0;
			}
		}



		$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

	}
	
	function getSuggestedCities($q) 
	{
		$row_set=array();
		$q = trim($q);
		//$this->db->select('concat(city_name,",",country_name) as name');
		$this->db->select('tbl_city_master.rome2rio_name as name');
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master','tbl_country_master.id=tbl_city_master.country_id');
		$this->db->join('tbl_country_alternative_names','tbl_country_alternative_names.country_id=tbl_country_master.id','LEFT');
		
		//$this->db->where('tbl_city_master.id in (select city_id from tbl_city_paidattractions where city_id=tbl_city_master.id)');

		$where="(`city_name` LIKE '%".$q."%' ESCAPE '!' OR `country_name` LIKE '%".$q."%' ESCAPE '!' OR `name` LIKE '%".$q."%' ESCAPE '!')";
		$this->db->where($where);
		$this->db->distinct();
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
		   foreach ($query->result_array() as $key=>$row)
		   {
				 $row_set[$key]['name'] = stripslashes($row['name']);
		   }
		  
		}
		 echo json_encode($row_set);
	}



	/* Below Code is for searched countries */
	
	function getSearchedCity($token)
	{
		$data=array();
		if(isset($_GET['searchtags']) && count($_GET['searchtags'])>0)
		{
			$data=array();
			$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,md5(tbl_city_master.id) as cityid,city_name,tbl_city_master.slug as cityslug,total_days,latitude,longitude,tbl_city_master.country_id,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where tbl_country_master.id=tbl_city_master.country_id) as countryimage,rome2rio_name,code',FALSE);

			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($_GET['searchtags']); $i++)
			{
					$tag = $_GET['searchtags'][$i];
					if (count($_GET['searchtags']) == 1) 
					{
						$sq = '(tag_name="' . $tag . '")';
					} 
					else 
					{
						if ($i == 0) 
						{
							$sq .= '(tag_name="' . $tag . '"';

						} 
						else if ($i == count($_GET['searchtags']) - 1) 
						{
							$sq .= ' OR tag_name="' . $tag . '")';
						} 
						else 
						{
							$sq .= ' OR tag_name="' . $tag . '"';
						}
					}
			}
			$this->db->where($sq);
			$this->db->where('rome2rio_name',$_GET['sdestination']);
			$this->db->group_by('attraction_id');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}

			if(count($data))
			{
				$sum=0;
				foreach($data as $key=>$list)
				{
					$sum+=$list['tag_hours'];
				}
				$data[0]['totaldaysneeded']=ceil($sum/12);
				$newdata[]=$data[0];
				$this->createsearchFile($token);
				return $newdata;
			}

		}
		else
		{
			$this->db->select('id,city_name,slug as cityslug,total_days,latitude,longitude,country_id,md5(id) as cityid,city_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,rome2rio_name,code');
			$this->db->from('tbl_city_master');
			$this->db->where('rome2rio_name',$_GET['sdestination']);
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}
			
		}


		$this->createsearchFile($token);
		return $data; 
	}

	function getSearchedCityOther($maincityarray,$token)
	{

		if (isset($_GET['searchtags']) && $_GET['searchtags'] != '') 
		{
			return $this->getSearchedCityOtherWithTags($maincityarray,'1',$isadd=1,$token);	
		}
		else
		{
			return $this->getSearchedCityOtherWithNoTags($maincityarray,'1',$isadd=1,$token);	
		}

	}

	function getSearchedCityOtherFromFile($maincityarray,$isadd,$token)
	{
		$data=array();

		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','r');
		$fileinputs_encoded=fgets($file);
		$fileinputs=json_decode($fileinputs_encoded,TRUE);
		fclose($file);

		if (isset($fileinputs['searchtags']) && count($fileinputs['searchtags'])) 
		{
			return $this->getSearchedCityOtherWithTags($maincityarray,'2',$isadd,$token);	
		}
		else
		{
			return $this->getSearchedCityOtherWithNoTags($maincityarray,'2',$isadd,$token);	
		}

	}


	function getSearchedCityOtherWithTags($maincityarray,$check,$isadd,$token)
	{
		$data=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForTags($maincityarray,$check,$isadd,$token);
		if($extra_days===0)
		{
			return $data;
		}


		if($check==1)
		{
			$cityids=array();
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
			}

			$data=array();

			$latlng=$this->getLatLongOfMainCity($token);
			$lat=$latlng['latitude'];
			$lng=$latlng['longitude'];
			
			$this->db->select('tbl_city_attraction_log.*,city_name,total_days,md5(tbl_city_master.id) as cityid,cityimage,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance',FALSE);
			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($_GET['searchtags']); $i++)
			{
					$tag = $_GET['searchtags'][$i];
					if (count($_GET['searchtags']) == 1) 
					{
						$sq = '(tag_name="' . $tag . '")';
					} 
					else 
					{
						if ($i == 0) 
						{
							$sq .= '(tag_name="' . $tag . '"';

						} 
						else if ($i == count($_GET['searchtags']) - 1) 
						{
							$sq .= ' OR tag_name="' . $tag . '")';
						} 
						else 
						{
							$sq .= ' OR tag_name="' . $tag . '"';
						}
					}
			}
			$this->db->where($sq);
			if(count($cityids))
			{
				$this->db->where_not_in('tbl_city_master.id',$cityids);
			}
			$this->db->where('tbl_city_master.id !=',$maincityarray[0]['id']);
			$this->db->group_by('attraction_id');
			$this->db->where('tbl_city_master.country_id',$maincityarray[0]['country_id']);
			$this->db->order_by('distance','ASC');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}
			
			if(count($data))
			{
				foreach($data as $arg)
				{
					$grouped_types[$arg['city_id']][] = $arg;
				}



				foreach($grouped_types as $key=>$list)
				{
					$sum=0;
					foreach($list as $innerkey=>$innerlist)
					{	
						$sum+=$innerlist['tag_hours'];
					}	
					$grouped_types[$key][0]['totaldaysneeded']=ceil($sum/12);
				}




				$neededArray=array();

				foreach ($grouped_types as $key => $list) 
				{
					$neededArray[]=$list[0];
				}

				if($extra_days=='all')
				{
					return $neededArray;
				}
				else
				{
						
					if($extra_days<0)
					{
						$totaldaysneededOfOriginalDestionation=$this->getTotaldaysneededOfOriginalDestionation($token);
						//echo $totaldaysneededOfOriginalDestionation;die;
						$startday=$totaldaysneededOfOriginalDestionation-1;
						$endday=$totaldaysneededOfOriginalDestionation+1;	
					}
					else
					{
						$startday=$extra_days-1;
						$endday=$extra_days+1;	
					}
					
					foreach($neededArray as $key=>$list)
					{
						if($startday <= $list['totaldaysneeded'] && $endday >= $list['totaldaysneeded'])
						{
						
						}
						else
						{
							unset($neededArray[$key]);
						}

					}
				}

				
				return $neededArray;
			
			}

		
			return $data;
		}
		else
		{

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			//$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);


			$cityids=array();
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
			}
			$latlng=$this->getLatLongOfMainCity($token);
			$lat=$latlng['latitude'];
			$lng=$latlng['longitude'];

			$data=array();
			
			$this->db->select('tbl_city_attraction_log.*,city_name,total_days,md5(tbl_city_master.id) as cityid,cityimage,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance',FALSE);
			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($fileinputs['searchtags']); $i++)
			{
					$tag = $fileinputs['searchtags'][$i];
					if (count($fileinputs['searchtags']) == 1) 
					{
						$sq = '(tag_name="' . $tag . '")';
					} 
					else 
					{
						if ($i == 0) 
						{
							$sq .= '(tag_name="' . $tag . '"';

						} 
						else if ($i == count($fileinputs['searchtags']) - 1) 
						{
							$sq .= ' OR tag_name="' . $tag . '")';
						} 
						else 
						{
							$sq .= ' OR tag_name="' . $tag . '"';
						}
					}
			}
			$this->db->where($sq);
			if(count($cityids))
			{
				$this->db->where_not_in('tbl_city_master.id',$cityids);
			}
			$this->db->where('tbl_city_master.id !=',$maincityarray[0]['id']);
			$this->db->group_by('attraction_id');
			$this->db->where('tbl_city_master.country_id',$maincityarray[0]['country_id']);
			$this->db->order_by('distance','ASC');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}

			if(count($data))
			{
				foreach($data as $arg)
				{
					$grouped_types[$arg['city_id']][] = $arg;
				}



				foreach($grouped_types as $key=>$list)
				{
					$sum=0;
					foreach($list as $innerkey=>$innerlist)
					{	
						$sum+=$innerlist['tag_hours'];
					}	
					$grouped_types[$key][0]['totaldaysneeded']=ceil($sum/12);
				}




				$neededArray=array();

				foreach ($grouped_types as $key => $list) 
				{
					$neededArray[]=$list[0];
				}

				if($extra_days=='all')
				{
					return $neededArray;
				}
				else
				{

					if($extra_days<0)
					{
						$totaldaysneededOfOriginalDestionation=$this->getTotaldaysneededOfOriginalDestionation($token);
						//echo $totaldaysneededOfOriginalDestionation;die;
						$startday=$totaldaysneededOfOriginalDestionation-1;
						$endday=$totaldaysneededOfOriginalDestionation+1;	
					}
					else
					{
						$startday=$extra_days-1;
						$endday=$extra_days+1;	
					}

					foreach($neededArray as $key=>$list)
					{
						if($startday <= $list['totaldaysneeded'] && $endday >= $list['totaldaysneeded'])
						{
						
						}
						else
						{
							unset($neededArray[$key]);
						}

					}
				}

				//echo "<pre>";print_r($neededArray);die;

				return $neededArray;
			
			}

		
			return $data;

		}
		

	}

	function getTimeNeedToTravelCurrentCityForTags($maincityarray,$check,$isadd='',$token)
	{

		if($check==1)
		{
			$cityids=array();
			$daysTaken=0;
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
				$daysTaken+=$list['totaldaysneeded'];
			}

			$data=array();
			$extra_days=0;
			
			$this->db->select('tbl_city_attraction_log.*,city_name,total_days,md5(tbl_city_master.id) as cityid,cityimage',FALSE);
			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($_GET['searchtags']); $i++)
			{
					$tag = $_GET['searchtags'][$i];
					if (count($_GET['searchtags']) == 1) 
					{
						$sq = '(tag_name="' . $tag . '")';
					} 
					else 
					{
						if ($i == 0) 
						{
							$sq .= '(tag_name="' . $tag . '"';

						} 
						else if ($i == count($_GET['searchtags']) - 1) 
						{
							$sq .= ' OR tag_name="' . $tag . '")';
						} 
						else 
						{
							$sq .= ' OR tag_name="' . $tag . '"';
						}
					}
			}
			$this->db->where($sq);
			if(count($cityids))
			{
				$this->db->where_in('tbl_city_master.id',$cityids);
			}
			$this->db->group_by('attraction_id');
			$this->db->where('tbl_city_master.country_id',$maincityarray[0]['country_id']);
			$this->db->order_by('total_days','ASC');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}


			if(count($data))
			{
				foreach($data as $arg)
				{
					$grouped_types[$arg['city_id']][] = $arg;
				}
				
				foreach($grouped_types as $key=>$list)
				{
					$sum=0;
					foreach($list as $innerkey=>$innerlist)
					{	
						$sum+=$innerlist['tag_hours'];
					}	

					$grouped_types[$key][0]['totaldaysneeded']=ceil($sum/12);
				}

				

				$neededArray=array();

				foreach ($grouped_types as $key => $list) 
				{
					$neededArray[]=$list[0];
				}
				$totaldaystaken=0;
				foreach ($neededArray as $list) 
				{
					$totaldaystaken+=$list['totaldaysneeded'];
				}

				$plus = substr($_GET['sdays'], -1);
				if($plus == '+') 
				{
					$traveldays = 0;
					$extra_days='all';
				} 
				else 
				{
					
					$enteredDays=(int)$_GET['sdays'];
					$extra_days=0;
					
					if($enteredDays<$daysTaken && count($maincityarray)==1)
					{
						$extra_days=$daysTaken-$enteredDays;
					}
					else if($enteredDays>$daysTaken)
					{
						$extra_days=$enteredDays-$daysTaken;
					}

				}
				return $extra_days;


			}
			return $extra_days;
		}
		else
		{
			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			//$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);

			//$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'r');
			$citiesinfile_encode=fgets($file);
			$citiesinfile=json_decode($citiesinfile_encode,TRUE);
			fclose($file);

			$daysTaken=0;
			$enteredDays=$fileinputs['sdays'];

			foreach($citiesinfile as $list)
			{
				$daysTaken+=$list['totaldaysneeded'];
			}

			
			$extra_days=0;
			if($enteredDays<$daysTaken && $isadd<1)
			{
				$extra_days=$daysTaken-$enteredDays;
			}
			else if($enteredDays>$daysTaken)
			{
				$extra_days=$enteredDays-$daysTaken;
			}

			return $extra_days;
		}
	}

	function getTotaldaysneededOfOriginalDestionation($token)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		//$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'r');
		$maincityarray_encode=fgets($file);
		$maincityarray=json_decode($maincityarray_encode,TRUE);
		fclose($file);
		return $maincityarray[0]['totaldaysneeded'];
	}

	function getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$check,$isadd='',$token)
	{
		if($check==1)
		{
			$totaldaystaken=0;
			$extra_days=0;
			foreach($maincityarray as $list)
			{
				$totaldaystaken+=$list['total_days'];
			}
			
			$plus = substr($_GET['sdays'], -1);
			if($plus == '+') 
			{
				$traveldays = 0;
				$extra_days='all';
			} 
			else 
			{
				$traveldays = (int)$_GET['sdays'];
				if($traveldays > $totaldaystaken)
				{
					$extra_days=$traveldays-$totaldaystaken;
				}
				else if($traveldays < $totaldaystaken && count($maincityarray)==1)
				{
					$extra_days=$totaldaystaken-$traveldays;
				}
			}

		}
		else
		{
			$totaldaystaken=0;
			$extra_days=0;

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			//$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);

			//$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'r');
			$maincityarray_encode=fgets($file);
			$maincityarray=json_decode($maincityarray_encode,TRUE);
			fclose($file);
			


			foreach($maincityarray as $list)
			{
				$totaldaystaken+=$list['total_days'];
			}
			
			$plus = substr($fileinputs['sdays'], -1);
			if($plus == '+') 
			{
				$traveldays = 0;
				$extra_days='all';
			} 
			else 
			{
				$traveldays = (int)$fileinputs['sdays'];
				if($traveldays > $totaldaystaken)
				{
					$extra_days=$traveldays-$totaldaystaken;
				}
				else if($traveldays < $totaldaystaken && $isadd<1)
				{
					$extra_days=$totaldaystaken-$traveldays;
				}
			}
		}
		return $extra_days;
	
	}


	function getSearchedCityOtherWithNoTags($maincityarray,$check,$isadd,$token)
	{
		$cityids=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$check,$isadd,$token);
		if($extra_days===0)
		{
			return $cityids;
		}
		foreach($maincityarray as $list)
		{
			$cityids[]=$list['id'];
		}
		$latlng=$this->getLatLongOfMainCity($token);
		$lat=$latlng['latitude'];
		$lng=$latlng['longitude'];
		$rome2rio_name=$latlng['rome2rio_name'];
		$citytotaldays=$latlng['total_days'];

		//$this->db->cache_on();
		$data=array();
		//$lat=$maincityarray[0]['latitude'];
		//$lng=$maincityarray[0]['longitude'];
		//$rome2rio_name=$maincityarray[0]['rome2rio_name'];
		$this->db->select('id,city_name,latitude,longitude,total_days,cityimage,md5(id) as cityid,rome2rio_name,total_days,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance');
		$this->db->from('tbl_city_master');
		
		if($extra_days==='all')
		{
			$startday=$extra_days+1;
			$this->db->where('total_attraction_time >', 0,FALSE);
		}
		else
		{
			if($extra_days<0)
			{
				$startday = $citytotaldays - 1;
				$endday = $citytotaldays + 1;
				$wheretotaldays='(tbl_city_master.total_days >= '.(float)$startday.' and tbl_city_master.total_days <='.(float)$endday.' and total_attraction_time!=0)';
			}
			else
			{
				$startday = $extra_days - 1;
				$endday = $extra_days + 1;
				$wheretotaldays='(tbl_city_master.total_days >= '.(float)$startday.' and tbl_city_master.total_days <='.(float)$endday.' and total_attraction_time!=0)';
			}
			$this->db->where($wheretotaldays);
		}
		$this->db->where('country_id',$maincityarray[0]['country_id']);
		if(count($cityids))
		{
			$this->db->where_not_in('id',$cityids);
		}
		$this->db->order_by('distance','ASC');

		$Q=$this->db->get();
		//echo $this->db->last_query();die;
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{
				$data[]=$row;
				/*$timetoreach=$this->getShortestDistance(1,$rome2rio_name,$row['rome2rio_name']);
				$hours = floor($timetoreach / 60);
				$minutes = $timetoreach % 60;
				$distance=$hours . ' Hrs ' . $minutes . ' Mins';
				$data[$key]['timetoreach']=$distance;
				*/	
				
	
			}
		}
		return $data;
	}	





	function getAttractionsOfSelectedCity($cityfile)
	{
		
		if(isset($_GET['searchtags']) && count($_GET['searchtags'])>0)
		{
			$ids=$this->getIDS($inputdecode['tags']);
			$this->getSelectedAttractions($ids,$cityfile);
			
		}
		else
		{
			$this->writeAllUserAttraction($cityfile);
		}

	}

	function createsearchFile($token)
	{
		$randomstring=$this->session->userdata('randomstring');
		if (!is_dir(FCPATH.'userfiles/search/'.$randomstring)) 
		{
			mkdir(FCPATH.'userfiles/search/'.$randomstring, 0777,true);         
		}		
		if (!is_dir(FCPATH.'userfiles/search/'.$randomstring.'/'.$token)) 
		{
			mkdir(FCPATH.'userfiles/search/'.$randomstring.'/'.$token, 0777,true);         
		}
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','w');
		fwrite($file,json_encode($_GET));
		fclose($file);


	}

	function createMultiCountrySearchFile($combinations,$arrayToWrite)
	{
		$uniqueid='';
		if(count($arrayToWrite))
		{
			$keys=array_keys($arrayToWrite);
			$mainkey=$keys[0];
			$uniqueid=$arrayToWrite[$mainkey][0]['uniqueid'];
		}	

		$randomstring=$this->session->userdata('randomstring');
		if (!is_dir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid)) 
		{
			mkdir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid, 0777,true);         
		}		
		
		$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/inputs','w');
		fwrite($file,json_encode($_GET));
		fclose($file);

		$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations','w');
		fwrite($file,json_encode($combinations));
		fclose($file);

		$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/cities','w');
		fwrite($file,json_encode($arrayToWrite));
		fclose($file);

	}

	function getCountryNameFromSlug($slug)
	{
		$data=array();
		$Q=$this->db->query('select country_name,country_conclusion,countryimage,slug from tbl_country_master where slug="'.$slug.'"');
		$data=$Q->row_array();
		return $data;
	}

	function getOtherCitiesOfThisCountry($country_id,$cityArray)
	{
		$data=array();
		$this->db->select('id,city_name,country_id,country_id');
		$this->db->from('tbl_city_master');
		$this->db->where('total_attraction_time >',0);		
		$this->db->where('country_id',$country_id);
		$this->db->where_not_in('id',$cityArray);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function signupUser()
	{
		$datetime=date('Y-m-d H:i:s');
		$data=array(
				'name'=>ucwords($_POST['name']),
				'email'=>$_POST['email'],
				'password'=>$this->hash($_POST['password']),
				'isactive'=>1,
				'created'=>$datetime,
				'last_login'=>$datetime,
				'userimage'=>'',
			);

		$this->db->insert('tbl_front_users',$data);
		
		$sessionArray=array(
					'fuserid'=>$this->db->insert_id(),
					'name'=>ucwords($_POST['name']),
					'email'=>$_POST['email'],
					'last_login'=>$datetime,
					'userimage'=>'',
				);


		$this->session->set_userdata($sessionArray);
		
	}

	function signinUser()
	{
		$Q=$this->db->query('select * from tbl_front_users where email="'.$_POST['useremail'].'" and password="'.$this->hash($_POST['userpassword']).'" limit 1');
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
			$sessionArray=array(
					'fuserid'=>$data['id'],
					'name'=>$data['name'],
					'email'=>$data['email'],
					'last_login'=>$data['last_login'],
				);

			$this->session->set_userdata($sessionArray);		
			
				if(isset($_POST['rememberme']) && $_POST['rememberme']!='')
				{
					setcookie('fusernamelogin',$_POST['useremail']);
					setcookie('fpasswordlogin',$_POST['userpassword']);		
				}
				else
				{
					setcookie('fusernamelogin','');
					setcookie('fpasswordlogin','');
				}

				$this->Account_fm->deleteUserSavedFiles();

				
			return 1;
		}
		return 2;
	}

	public function hash($string)
	{
			return hash('sha512',$string.config_item('encryption_key'));
	}

	function getCityCMSDetails($slug)
	{
		$Q=$this->db->query('select * from tbl_city_master where slug="'.$slug.'"');
		return $Q->row_array(); 

	}

	function getCountry($slug){
		$Q=$this->db->query('select id,country_name,country_capital,country_neighbours, country_conclusion,countrybanner from tbl_country_master where slug="'.$slug.'"');
		return $Q->row_array(); 
	}

	function getCountryTags($id)
	{
		$data=array();
		$this->db->select('tag_name');
		$this->db->from('tbl_tag_master');
		$this->db->join('tbl_country_tags','tbl_country_tags.tag_id=tbl_tag_master.id');
		$this->db->where('tbl_country_tags.country_id',$id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		return $data;
	}
	
	function getCountryCitiesCovered($id)
	{
		$data=array();
		$this->db->select('city_name,slug');
		$this->db->from('tbl_city_master');
		$this->db->where('country_id',$id);
		$this->db->order_by('city_name','ASC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		return $data;
	}
	
	function forgotPassword()
		{
				$Q=$this->db->query('select id from tbl_front_users where email="'.$_POST['email'].'"');
				if($Q->num_rows()>0)
				{
					$uniq=uniqid();
					$data=$Q->row_array();
					$udata=array(
						'user_id'=>$data['id'],
						'expire'=>strtotime("+12 hour"),
						'token'=>$uniq
					);
					
					$this->db->where('user_id',$data['id']);
					$this->db->delete('tbl_tokens');
					$this->db->insert('tbl_tokens',$udata);
					$emailurl=site_url('Home/reset_password').'/'.md5($data['id']).'/'.md5($uniq);
					$message = "<table width='600' border='0' align='center' cellpadding='10' cellspacing='0'>
					  <tr>
						<td align='left' valign='top' style='height:100px;background-color:#ECECEC;text-align:center;'>
							<img src='".base_url()."assets/images/logo.png' width='200' height='80' >
						</td>
					  </tr>
					  
					  <tr>
							<td align='center' valign='top' bgcolor='#f58634' style='background-color:#f58634; font-size:13px; color:#fff; padding:10px;'>We've received a request to reset the password for this email address.</td>
					  </tr>
						<tr>
							<td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f58634; font-size:13px; color:#fff; padding:10px;'>To reset the password please click on this link or copy and paste this link into your browser.(link expires in 12 hour).</td>
						</tr>
						<tr>
							<td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f58634; font-size:13px; color:#fff; padding:10px;'><a href='".$emailurl."' target='_blank' style='color:#fff;'>".$emailurl."</a></td>
						</tr>
						<tr>
							<td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f58634; font-size:13px; color:#fff; padding:10px;'>This link takes you to a secure page where you can change your password.</td>
						</tr>
						<tr>
							<td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f58634;font-size:13px; color:#fff; padding:10px;'>If you dont want to reset your password then ignore this email Your password will not be reset.</td>
						</tr>
					  <tr>
						<td align='center' style='background-color:#ECECEC;color:#5a4371;font-weight:bold;'>
							  Phone: 1234-567-890 <br>
							  Email: <a href='mailto:ei.anita.gupta@gmail.com' style='color:#5a4371;font-weight:bold; text-decoration:none;'>ei.anita.gupta@gmail.com </a><br>
							  Website: <a href='http://reesort.com/taxidioreesort/' target='_blank' style='color:#5a4371;font-weight:bold; text-decoration:none;'>http://reesort.com/taxidioreesort/</a>
						 </td>
					  </tr>
					</table>";
					
					//echo $message;die;
				
					$config = array(
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'smtp_host'=>'smtp.emailsrvr.com',
						'smtp_user'=>'system@alakik.net',
						'smtp_pass'=>'TimeDesk',
						'smtp_port'=>'25',
						'crlf'     =>"\r\n",
						'newline'  => "\r\n",
						'wordwrap' => TRUE
					);									  
					$this->load->library('email');
					$this->email->initialize($config);
					$subject='Password Reset Help';
					$to=$_POST['email'];
					$from='ei.anita.gupta@gmail.com';
					$this->email->from($from);
					$this->email->subject($subject);
					$this->email->to($to); 
					$this->email->message($message);
					$this->email->send();
					return 1;
				}
				else
				{
					return 0;
				}
		}
		
		function checkExpireToken($id,$token)
		{
			$Q=$this->db->query('select expire from tbl_tokens where md5(user_id)="'.$id.'" and md5(token)="'.$token.'"');
			$data=$Q->row_array();
			if($data['expire']>time())
			{
				return 1;
			}
			else
			{
				$this->db->where('md5(user_id)',$id);
				$this->db->delete('tbl_tokens');
				return 0;
			}
		}
		
		function updatePassword()
		{
			$data = array(
				'password' => $this->hash($_POST['password'])
			);
			$this->db->where('md5(id)', $this->input->post('user_id'));
			$this->db->update('tbl_front_users', $data); 	
			$this->db->where('md5(id)',$this->input->post('user_id'));
			$this->db->delete('tbl_tokens');
		}	


		function otherAttractions($ids,$attraction_decode,$city_id)
	    {

		$relaxation_decode=array();

		$attraction_decode_rel=$attraction_decode;	
		if(in_array(17,$ids))
		{
			$relaxation_decode =array();
			if(file_exists(FCPATH.'userfiles/relaxationspa/'.$city_id))
			{
				$relaxation_json = file_get_contents(FCPATH.'userfiles/relaxationspa/'.$city_id);
				$relaxation_decode = json_decode($relaxation_json,TRUE);
			}

			if(count($relaxation_decode))
			{
				$attraction_decode_rel=array_merge($attraction_decode,$relaxation_decode);
			}
		}


		$attraction_decode_spo=$attraction_decode_rel;
		if(in_array(12,$ids))
		{
			//echo FCPATH.'userfiles/sport/'.$city_id;die;
			$sport_decode=array();
			$stadium_decode=array();
			$adv_decode=array();

			if(file_exists(FCPATH.'userfiles/sport/'.$city_id))
			{

				$sport_json = file_get_contents(FCPATH.'userfiles/sport/'.$city_id);
				$sport_decode = json_decode($sport_json,TRUE);
			}

			if(file_exists(FCPATH.'userfiles/stadium/'.$city_id))
			{
				$stadium_json = file_get_contents(FCPATH.'userfiles/stadium/'.$city_id);
				$stadium_decode = json_decode($stadium_json,TRUE);
			}

			if(count($sport_decode) && count($stadium_decode))
			{
				$adv_decode=array_merge($sport_decode,$stadium_decode);
				
			}
			else if(count($sport_decode) && !count($stadium_decode))
			{
				$adv_decode=$sport_decode;
			}
			else if(!count($sport_decode) && count($stadium_decode))
			{
				$adv_decode=$stadium_decode;
			}
			
			if(count($adv_decode))
			{
				$attraction_decode_spo=array_merge($attraction_decode_rel,$adv_decode);
			}
		}



		$attraction_decode_res=$attraction_decode_spo;
		if(in_array(15,$ids))
		{	
			$restaurant_decode=array();
			if(file_exists(FCPATH.'userfiles/restaurant/'.$city_id))
			{
				$restaurant_json = file_get_contents(FCPATH.'userfiles/restaurant/'.$city_id);
				$restaurant_decode = json_decode($restaurant_json,TRUE);
			}
			if(count($restaurant_decode))
			{
				$attraction_decode_res=array_merge($attraction_decode_spo,$restaurant_decode);
			}
		}
		//echo "<pre>";print_r($attraction_decode_res);die;

		//echo "<pre>";print_r($attraction_decode_res);die;
		
		return $attraction_decode_res;

	}

	function mergeOtherAttractions($attraction_decode,$city_id)
	{

		$relaxation_decode=array();

		$attraction_decode_rel=$attraction_decode;	
		$relaxation_decode =array();
		if(file_exists(FCPATH.'userfiles/relaxationspa/'.$city_id))
		{
			$relaxation_json = file_get_contents(FCPATH.'userfiles/relaxationspa/'.$city_id);
			$relaxation_decode = json_decode($relaxation_json,TRUE);
		}


		if(count($relaxation_decode))
		{
			$attraction_decode_rel=array_merge($attraction_decode,$relaxation_decode);
		}


		$attraction_decode_spo=$attraction_decode_rel;
		$sport_decode=array();
		$stadium_decode=array();
		$adv_decode=array();
		if(file_exists(FCPATH.'userfiles/sport/'.$city_id))
		{
			$sport_json = file_get_contents(FCPATH.'userfiles/sport/'.$city_id);
			$sport_decode = json_decode($sport_json,TRUE);
		}

		if(file_exists(FCPATH.'userfiles/stadium/'.$city_id))
		{
			$stadium_json = file_get_contents(FCPATH.'userfiles/stadium/'.$city_id);
			$stadium_decode = json_decode($stadium_json,TRUE);
		}

		if(count($sport_decode) && count($stadium_decode))
		{
			$adv_decode=array_merge($sport_decode,$stadium_decode);
			
		}
		else if(count($sport_decode) && !count($stadium_decode))
		{
			$adv_decode=$sport_decode;
		}
		else if(!count($sport_decode) && count($stadium_decode))
		{
			$adv_decode=$stadium_decode;
		}

		if(count($adv_decode))
		{
			$attraction_decode_spo=array_merge($attraction_decode_rel,$adv_decode);
		}



		$attraction_decode_res=$attraction_decode_spo;
		$restaurant_decode=array();
		if(file_exists(FCPATH.'userfiles/restaurant/'.$city_id))
		{
			$restaurant_json = file_get_contents(FCPATH.'userfiles/restaurant/'.$city_id);
			$restaurant_decode = json_decode($restaurant_json,TRUE);
		}
		if(count($restaurant_decode))
		{
			$attraction_decode_res=array_merge($attraction_decode_spo,$restaurant_decode);
		}


		return $attraction_decode_res;

	}

	function getLatLongOfMainCity($token)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$Q=$this->db->query('select latitude,longitude,rome2rio_name,total_days from tbl_city_master where id="'.$filename.'" limit 1');
		return $Q->row_array();
	}

	function permutation(array $arr)
	{
		$collection=array();
		while($ele=array_shift($arr))
	    {
	        $x=$ele;
	        //echo $x."<br/>";
	        $collection[]=$x;
	        foreach($arr as $rest)
	        {
	            $x.=",$rest";
	            $collection[]=$x;
	            //echo $x."<br/>";
	        }
	    }

		return $collection;
	}

	function processAdditionalDayAlgorithm($neededArray,$startday,$endday) 
	{
		
		for($k=1;$k<=count($neededArray);$k++)
		{
			$oneDimArray[]=$k;
		}
		
		$allcombinations=$this->permutation($oneDimArray);
		
		for($i=0;$i<count($allcombinations);$i++)
		{
			$tempStore=array();
			$commaString=explode(',',$allcombinations[$i]);
			$sum=0;
			for($j=0;$j<count($commaString);$j++)
			{
				$tempStore[]=$neededArray[$j];
				$sum+=ceil($neededArray[$j]['total_days']);
			}
			if($startday<=$sum && $sum<=$endday)
			{
				break;
			}
			else
			{
				$tempStore=array();
			}

		}
		return $tempStore;
				
   }


   function processAdditionalDayAlgorithmForNationalNoTags($neededArray,$startday,$endday)
   {

   		for($k=1;$k<=count($neededArray);$k++)
		{
			$oneDimArray[]=$k;
		}
		
		$allcombinations=$this->permutation($oneDimArray);
		//echo "<pre>";print_r($allcombinations);die;
		for($i=0;$i<count($allcombinations);$i++)
		{
			$tempStore=array();
			$commaString=explode(',',$allcombinations[$i]);
			$sum=0;
			for($j=0;$j<count($commaString);$j++)
			{
				$tempStore[]=$neededArray[$j];
				$sum+=ceil($neededArray[$j]['total_days']);
			}

			
			if($startday<=$sum && $sum<=$endday)
			{
				break;
			}
			else
			{
				$tempStore=array();
			}

		}

		if(count($tempStore))
		{
			foreach($tempStore as $arg)
			{
				$country_group[$arg['country_id']][] = $arg;
			}
			return $country_group;
		}
		return $tempStore;
		
   }


   function processAdditionalDayAlgorithmForTags($data,$startday,$endday)
   {

   		$neededArray=array();
		foreach($data as $key=>$list)
		{
			if($key!='totalcitytime' && $key!='counter')
			{
				$neededArray[]=$data[$key][0];
			}
		}

		//echo "<pre>";print_r($neededArray);die;

   		for($k=1;$k<=count($neededArray);$k++)
		{
			$oneDimArray[]=$k;
		}

		//echo "<pre>";print_r($f);die;

		$allcombinations=$this->permutation($oneDimArray);
		

		for($i=0;$i<count($allcombinations);$i++)
		{
			$tempStore=array();
			$commaString=explode(',',$allcombinations[$i]);
			$sum=0;
			for($j=0;$j<count($commaString);$j++)
			{
				$tempStore[]=$neededArray[$j];
				$sum+=ceil($neededArray[$j]['thiscitytime']);
			}

			//echo $startday."<=".$sum ."&&". $sum."<=".$endday."<br/>";
			if($startday<=$sum && $sum<=$endday)
			{
				break;
			}
			else
			{
				$tempStore=array();
			}

		}

		if(count($tempStore)<0)
		{
			return $tempStore;
		}
		return $this->resetArrayForTags($tempStore,$data);

   }

	function resetArrayForTags($finalcountry_combination,$data)
	{
		$newArray=array();$counter=0;$sum=0;
		foreach($finalcountry_combination as $key=>$list)
		{
			$newKey=$list['id'];
			$newArray[$newKey]=$data[$newKey];
		}

		foreach($newArray as $key=>$list)
		{
			$sum+=$list[0]['thiscitytime'];
		}
		$newArray['totalcitytime']=ceil($sum);
		$newArray['counter']=count($finalcountry_combination);
		//echo "<pre>nnnnnnnn";print_r($newArray);die;
		return $newArray;
					
	}


	function processAdditionalDayAlgorithmForNationalTags($country_city_array,$startday,$endday)
	{
		$originalarray=$country_city_array;

		foreach ($variable as $key => $value) {
			# code...
		}
	}

	function getLatestBlogs()
	{
		$data=array();
		$DB2 = $this->load->database('otherdb', TRUE);
		  
	    $Q=$DB2->query("SELECT title,post_name,CONCAT(LEFT(image, LENGTH(image) - LOCATE('.', REVERSE(image))),'-450x270.',SUBSTRING_INDEX(image, '.', -1)) AS image FROM ( SELECT p.post_title AS title,p.post_status AS 'status',p.post_date AS date,p.post_content AS content,p.post_name AS post_name,(SELECT `guid` FROM tx_posts WHERE id = m.meta_value) AS image FROM tx_posts p, tx_postmeta m WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.id = m.post_id AND m.meta_key = '_thumbnail_id' ORDER BY date DESC LIMIT 3) TT");
	   	$data=$Q->result_array();
	   	return $data;
	   // echo "<pre>";print_r($Q->result_array());die;	
	}

}

/* End of file Home_m.php */
/* Location: ./application/models/Home_m.php */