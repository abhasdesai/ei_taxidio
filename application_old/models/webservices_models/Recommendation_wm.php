<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Recommendation_wm extends CI_Model {

	function getTags() {
		$data = array();
		$this->db->select('id,tag_name');
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

	function getCountryId($countryrome2rioname)
	{
		$selectcol="id";
		$table="tbl_country_master";
		$condition='rome2rio_code ="'.trim($countryrome2rioname).'"';
		return getrowbycondition($selectcol,$table,$condition);
	}

	function getBasicCityDetails($city_id)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where id="'.$city_id.'"');
		return $Q->row_array();
	}

	function getBasicCityDetailsFromName($name)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where city_name="'.$name.'"');
		return $Q->row_array();
	}

	function getSingleCountries()
	{
	
		if (isset($_POST['tags']) && $_POST['tags'] != '')
		{
			return $this->CountriesWithTags();
		}
		else
		{
			return $this->CountriesWithNoTags();
		}
	}

	function getcountryData($countryid)
	{
		$selectcol="id,country_name,country_conclusion,countryimage,slug";
		$table="tbl_country_master";
		$condition="id=$countryid";
		$data['country']=getrowbycondition($selectcol,$table,$condition);
		$data['countrynoofCities']=getcountrynoofCities($data['country']['id']);
		return $data;
	}

	function CountriesWithNoTags()
	{
		//echo "<pre>";print_r($_POST);die;
		//$countryrome2rioname=$this->getCountryCode();
		$countryrome2rioname=$this->input->post('ocode',TRUE);
		$data = array();
		$singlecountry = array();


		//$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_city_master.country_id,tbl_country_master.rome2rio_name as rome2rio_country_name,total_days,tbl_city_master.slug as cityslug,code,cityimage,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags',FALSE);
		$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_city_master.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,country_conclusion,total_days,tbl_city_master.slug as cityslug,code,countryimage,cityimage,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags,rome2rio_code',FALSE);
		 //concat( 'http://192.168.0.148/taxidio2/userfiles/cities/small/', cityimage ) AS cityimage

		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		$this->db->where('tbl_city_master.id in (select city_id from tbl_city_paidattractions where city_id=tbl_city_master.id)');

		if(isset($_POST['isdomestic']) && $_POST['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
			$this->db->where('tbl_city_master.rome2rio_name !=',trim($_POST['start_city']));
			$cityname=explode(',',trim($_POST['start_city']));
			$this->db->where('tbl_city_master.rome2rio_name !=',$cityname[0]);

		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));

		}

		if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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
			$this->db->where('tbl_city_master.total_days <=',$endday);
		}


		$date=implode("-", array_reverse(explode("/",$_POST['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_POST['budget']) && $_POST['budget'] != '')
		{
			$budget=explode('-',$this->input->post('budget'));

			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');


			if ($budget[1]>=500)
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);

			}
			else
			{

				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				//$this->db->where('hoteltype_id',$_POST['accomodation']);
			}
		}

		if (isset($_POST['weather']) && $_POST['weather'] != '')
		{

			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');

			$weather = explode('-',$this->input->post('weather'));

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
				//$data[$key]['uniqueid']=$_POST['token'];
			}
		}

		//echo $this->db->last_query();die;
		//echo "<pre>";print_r($data);die;
		$cityData=array();
		if(count($data))
		{
			if(isset($_POST['isdomestic']) && $_POST['isdomestic']==1)
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

		if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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

			foreach ($country_group as $key => $mainarray)
			{
					foreach($mainarray as $cityKey=>$cityList)
					{
							$country_group[$key][$cityKey]['sortorder']=$cityKey;
					}
	   }

		}

		if($endday>0)
		{
			$country_group=$this->processAdditionalDayAlgorithmForNationalNoTags($data,$startday,$endday);
			foreach ($country_group as $key => $mainarray)
			{
					foreach($mainarray as $cityKey=>$cityList)
					{
							$country_group[$key][$cityKey]['sortorder']=$cityKey;
					}
	   }

		}

		$ArrayToStorInFile=removeUnnecessaryFiedsForSingleCountry($country_group);
		//$this->createFile($ArrayToStorInFile);
		return $country_group;

	}

	function InternationalNoTags($data)
	{
		$country_group = array();
		foreach($data as $arg)
		{
			$country_group[$arg['country_id']][] = $arg;
		}

		foreach ($country_group as $key => $mainarray)
		{
				foreach($mainarray as $cityKey=>$cityList)
				{
						$country_group[$key][$cityKey]['sortorder']=$cityKey;
				}
        }


		if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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

		//$tt=array();
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
							//$tt[]=$combination_founded;
						}

					}
					else
					{
						unset($country_group[$key]);
					}

				}
			}

		}

		//echo "<pre>tt";print_r($tt);die;


		$singlecountry=$this->selectCountries($country_group);
		if(count($singlecountry)<1)
		{
			 return $singlecountry;
		}

		foreach($singlecountry as $mainkey=>$mainlist)
		{
			$singlecountry[$mainkey]['temptime']=$mainlist[0]['actualtime'];
			$singlecountry[$mainkey]['totalcities'] = count($mainlist);
		}

		$country_city_array_sorted=array();
		 foreach ($singlecountry as $key => $value) {
			$country_city_array_sorted['totalcities'][$key]  = $value['totalcities'];
			$country_city_array_sorted['temptime'][$key]  = $value['temptime'];
		}
		array_multisort($country_city_array_sorted['totalcities'], SORT_DESC,$country_city_array_sorted['temptime'], SORT_ASC,$singlecountry);




		$finalarray=array();
		$k=0;
		foreach ($singlecountry as $finalkey => $finalvalue)
		{
			foreach ($finalvalue as $finalinnerkey => $finalinnervalue)
			{
				unset($singlecountry[$finalkey]['totalcities']);
				unset($singlecountry[$finalkey]['temptime']);
				$finalarray[$k]=$finalvalue[0];
				$finalarray[$k]['timetoreach']=$finalvalue[0]['timetoreach'];
			}
			$k++;
		}



		$allcountryarray=array();

		foreach ($singlecountry as $allkey => $allcountries) {
			foreach ($allcountries as $allkeysub => $allcountriessub) {
				$allcountryarray[$allcountriessub['country_id']][$allkeysub]=$allcountriessub;
				//$allcountryarray[$allcountriessub['country_id']][$allkeysub]['countryimage']=site_url("userfiles/countries/".$allcountriessub['countryimage']);
				//$allcountryarray[$allcountriessub['country_id']][$allkeysub]['cityimage']=site_url("userfiles/cities/small/".$allcountriessub['cityimage']);
			}
		}
		
		$ArrayToStorInFile=removeUnnecessaryFiedsForSingleCountry($allcountryarray);
		
    	return $allcountryarray;
	}





	function CountriesWithTags()
	{

		//$countryrome2rioname=$this->getCountryCode();
		$countryrome2rioname=$this->input->post('ocode');

		$data=array();

		$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,country_conclusion,tbl_city_master.slug as cityslug,code,countrycode,countryimage,cityimage,(select count(city_id) from tbl_city_tags where city_id=tbl_city_master.id) as totaltags,rome2rio_code',FALSE);

		$this->db->from('tbl_city_attraction_log');
		$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
		$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		$this->db->where('tbl_city_master.total_attraction_time >',0);
		if(isset($_POST['isdomestic']) && $_POST['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
			$this->db->where('tbl_city_master.rome2rio_name !=',trim($_POST['start_city']));
			$cityname=explode(',',trim($_POST['start_city']));
			$this->db->where('tbl_city_master.rome2rio_name !=',$cityname[0]);
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
		}

		$date=implode("-", array_reverse(explode("/",$_POST['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_POST['budget']) && $_POST['budget'] != '')
		{
			$budget=explode('-',$this->input->post('budget'));

			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');


			if ($budget[1]>=500)
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				//$this->db->where('hoteltype_id',$_POST['accomodation']);

			}
			else
			{

				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				//$this->db->where('hoteltype_id',$_POST['accomodation']);
			}
		}


		if (isset($_POST['weather']) && $_POST['weather'] != '')
		{

			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');

			$weather = explode('-',$this->input->post('weather'));
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
		for ($i = 0; $i < count($_POST['tags']); $i++)
		{
				$tag = $_POST['tags'][$i];
				if (count($_POST['tags']) == 1)
				{
					$sq = '(tag_name="' . $tag . '")';
				}
				else
				{
					if ($i == 0)
					{
						$sq .= '(tag_name="' . $tag . '"';

					}
					else if ($i == count($_POST['tags']) - 1)
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
				//$data[$key]['uniqueid']=$_POST['token'];
			}
		}

		$cityData=array();
		if(count($data))
		{
			if(isset($_POST['isdomestic']) && $_POST['isdomestic']==1)
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



		if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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

			  foreach($finalArray as $mainkey=>$mainlist)
			  {
			  		foreach($mainlist as $subkey=>$sublist)
			  		{
			  			$finalArray[$mainkey][$subkey]['sortorder']=$subkey;
			  		}
			  }

			  $tempArray=$finalArray;
			  $ArrayToStorInFile=removeUnnecessaryFiedsForSingleCountry($tempArray);
			  //$this->createFile($ArrayToStorInFile);
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

					$currentcitysum+=$finalsum['tag_hours'];
					$thiscity++;
					if($thiscity==count($listinsidesum))
					{
						$country_city_array[$key][$key2][0]['thiscitytime']=ceil($currentcitysum/12);
						$sum+=ceil($currentcitysum/12);
					}
				}
			}

			$country_city_array[$key]['totalcitytime']=$sum;
			$country_city_array[$key]['counter']=$counter;

		}


		if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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

			  //echo "<pre>";print_r($country_city_array);die;


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

			  foreach($finalArray as $mainkey=>$mainlist)
			  {
			  		foreach($mainlist as $subkey=>$sublist)
			  		{
			  			$finalArray[$mainkey][$subkey]['sortorder']=$subkey;
			  		}
			  }

				$singlecountry=$this->selectCountries($finalArray);
				if(count($singlecountry)<1)
				{
					 return $singlecountry;
				}

			foreach($singlecountry as $mainkey=>$mainlist)
			{
				$singlecountry[$mainkey]['temptime']=$mainlist[0]['actualtime'];
				$singlecountry[$mainkey]['totalcities'] = count($mainlist);
			}

			$country_city_array_sorted=array();
			 foreach ($singlecountry as $key => $value) {
				$country_city_array_sorted['totalcities'][$key]  = $value['totalcities'];
				$country_city_array_sorted['temptime'][$key]  = $value['temptime'];
			}



			array_multisort($country_city_array_sorted['totalcities'], SORT_DESC,$country_city_array_sorted['temptime'], SORT_ASC,$singlecountry);





			foreach ($singlecountry as $allkey => $allcountries) {
				foreach ($allcountries as $allkeysub => $allcountriessub) {
					$allcountryarray[$allcountriessub['country_id']][]=$allcountriessub;
				}
			}



			foreach ($singlecountry as $finalkey => $finalvalue)
			{
				foreach ($finalvalue as $finalinnerkey => $finalinnervalue)
				{
					unset($singlecountry[$finalkey]['totalcities']);
					unset($singlecountry[$finalkey]['temptime']);

				}
			}


			$allcountryarray=array();

			foreach ($singlecountry as $allkey => $allcountries) {
				foreach ($allcountries as $allkeysub => $allcountriessub) {
					$allcountryarray[$allcountriessub['country_id']][]=$allcountriessub;
				}
			}


			//echo "<pre>hiccf";print_r($allcountryarray);die;

			  $ArrayToStorInFile=removeUnnecessaryFiedsForSingleCountry($allcountryarray);
			  //echo "<pre>";print_r($singlecountry);die;
			  //$this->createFile($ArrayToStorInFile);
			  return $allcountryarray;
		}
		else
		{
			return $country_city_array;
		}
	}

	function selectCountries($data)
	{
		$recommended_countries = array();
		$keys_array = array();


		$traveltime = explode('-',$this->input->post('traveltime'));
		if ($traveltime[1]>25) {
			$approx = 0.20 * $traveltime[1];
			$starttime = round($traveltime[1]-$approx);
			$endtime = 0;

		} else {

			$time = explode('-',$_POST['traveltime']);
			$start_time=$time[0];
			$end_time=$time[1];
			$starttime = round($start_time-($start_time*0.20));
			$endtime = round($end_time+($end_time*0.20));
		}

		$Rome2RioResponse=$this->getTotalTimeToReachFromRome2Rio($_POST['start_city'],$data,1);

		//echo "<pre>";print_r($Rome2RioResponse);die;

		$storage=array();
		$this->session->unset_userdata('storage');



		if (count($data)) {
			//$key = 0;
			foreach ($data as $key => $list)
			{
				$response=$Rome2RioResponse[$key];
				$hours = floor($response / 60);
				$minutes = $response % 60;

				$tempstorage[]=array(
						'country_id'=>$list[0]['country_id'],
						'distance'=>formattime($hours,$minutes),
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

				if ($response == 'na')
				{
					unset($data[$key]);
				}
				else
				{
					$hrs = $response / 60;
					if($endtime==0)
					{
						if($starttime < $hrs)
						{
							unset($data[$key]);
						}
						else
						{
							$hours = floor($response / 60);
							$minutes = $response % 60;

							$data[$key][0]['timetoreach'] = formattime($hours,$minutes);
							$data[$key][0]['actualtime']=$response;
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
						$data[$key][0]['timetoreach'] = formattime($hours,$minutes);
						$data[$key][0]['actualtime']=$response;
					}
				}
				//$key++;
			}
		}
		return $data;
	}


	function getTotalTimeToReachFromRome2Rio($start_city,$data,$issingle)
	{
		 $requests=array();
		 if($issingle==1)
		 {
			 foreach($data as $key=>$list)
			 {
				$requests[$key]='https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($list[0]['rome2rio_country_name']) . '';
			}
		 }
		 else {
			 foreach($data as $key=>$list)
  		 {
				 if($list['actualtime']=='')
				 {
					 	$requests[$key]='https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($list['rome2rio_country_name']) . '';
				 }

  		 }
		 }
		//print_r($requests);die;
		 $responses=multiRequest($requests);

		 $country_response=array();

		 foreach ($responses as $key => $list)
		 {
		 		$json=json_decode($list,TRUE);
				if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['totalDuration'] == '')
				{
					$country_response[$key]='na';
				}
				else
				{
					$country_response[$key]=$json['routes'][0]['totalDuration'];
				}
		}
		return $country_response;


	}


	function getMultiCountries()
	{


		if (isset($_POST['tags']) && $_POST['tags'] != '')
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
		$countryrome2rioname=$this->input->post('ocode',TRUE);

		$data=array();

		$this->db->select('tbl_city_master.id,tbl_city_attraction_log.tag_hours,attraction_id,tbl_continent_countries.continent_id,tbl_continent_countries.country_id,tbl_continent_countries.country_id as countryid,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name, tbl_country_master.country_name,tbl_country_master.latitude AS countrylatitude,tbl_country_master.longitude AS countrylongitude,tbl_country_master.slug,tbl_country_master.rome2rio_name AS rome2rio_country_name,tbl_city_master.latitude AS citylatitude,tbl_city_master.longitude AS citylongitude,country_conclusion,tbl_continent_countries.continent_id,tbl_country_master.rome2rio_name AS country_rome2rio_name,tbl_city_master.max_zipcode,tbl_city_master.slug AS cityslug,code,countrycode,countryimage,cityimage,tbl_city_master.id as city_id,rome2rio_code',FALSE);

		$this->db->from('tbl_city_attraction_log');
		$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
		$this->db->join('tbl_continent_countries','tbl_continent_countries.country_id = tbl_city_attraction_log.country_id');

		$this->db->join('tbl_country_master', 'tbl_country_master.id = tbl_continent_countries.country_id');

		$this->db->join('tbl_city_master', 'tbl_city_attraction_log.city_id = tbl_city_master.id');
		$this->db->where('tbl_city_master.total_attraction_time >',0);

		if(isset($_POST['isdomestic']) && $_POST['isdomestic']==1)
		{
			$this->db->where('tbl_country_master.rome2rio_code',trim($countryrome2rioname));
		}
		else
		{
			$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));
		}

		$date=implode("-", array_reverse(explode("/",$_POST['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_POST['budget']) && $_POST['budget'] != '')
		{
			$budget=explode('-',$this->input->post('budget'));

			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');


			if ($budget[1]>=500)
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_POST['accomodation']);

			}
			else
			{

				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_POST['accomodation']);
			}
		}


		if (isset($_POST['weather']) && $_POST['weather'] != '')
		{

			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');

			$weather = explode('-',$this->input->post('weather'));
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
		for ($i = 0; $i < count($_POST['tags']); $i++)
		{
				$tag = $_POST['tags'][$i];
				if (count($_POST['tags']) == 1)
				{
					$sq = '(tag_name="' . $tag . '")';
				}
				else
				{
					if ($i == 0)
					{
						$sq .= '(tag_name="' . $tag . '"';

					}
					else if ($i == count($_POST['tags']) - 1)
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
				//$data[$key]['uniqueid']=$_POST['token'];
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
					$city_array = array();
					foreach ($listinsidesum as $row )
					{
						$city = $row['city_id'];
						$city_array[$city][] = $row;
					}

					//echo "<pre>";print_r($city_array);die;
					foreach($city_array as $slist)
					{
						$thiscity=0;$temp_sum=0;
						foreach($slist as $k=>$maillist)
						{
							$temp_sum+=$maillist['tag_hours'];
							$thiscity++;
							if($thiscity==count($slist))
							{
								$sum+=ceil($temp_sum);
							}
						}


					}
					//echo round($sum/12);die;

					foreach($listinsidesum as $k=>$finalsum)
					{
						$country_city_array[$key][$key2][$k]['totalcountrytime']=round($sum/12);
					}

				}
			}

			//echo "<pre>";print_r($country_city_array);die;


			//Get start day and end day.
			if (isset($_POST['days']) && $_POST['days'] != '')
			{
				$days = $this->input->post('days',TRUE);
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
		$countryrome2rioname=$this->input->post('ocode',TRUE);
		$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_continent_countries.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,tbl_city_master.latitude as citylatitude,tbl_city_master.longitude as citylongitude,country_conclusion,total_days,country_total_days,tbl_city_master.rome2rio_name as city_rome2rio_name,tbl_country_master.rome2rio_name as country_rome2rio_name,tbl_country_master.id as countryid,tbl_city_master.slug as cityslug,code,countryimage,cityimage,rome2rio_code,tbl_continent_countries.continent_id',FALSE);
		$this->db->from('tbl_city_master');


		//$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');


	  $this->db->join('tbl_continent_countries', 'tbl_continent_countries.country_id=tbl_city_master.country_id');

		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_continent_countries.country_id');

		$this->db->where('tbl_city_master.id in (select city_id from tbl_city_paidattractions where city_id=tbl_city_master.id)');
		$this->db->where('tbl_city_master.total_attraction_time >',0);

		$this->db->where('tbl_country_master.rome2rio_code !=',trim($countryrome2rioname));

   if (isset($_POST['days']) && $_POST['days'] != '')
		{
			$days = $this->input->post('days',TRUE);
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
			$this->db->where('tbl_city_master.total_days <=',$endday);
		}

		$date=implode("-", array_reverse(explode("/",$_POST['start_date'])));
		$month=date('n',strtotime($date));

		if (isset($_POST['budget']) && $_POST['budget'] != '')
		{
			$budget=explode('-',$this->input->post('budget'));

			$this->db->join('tbl_city_hotel_cost_master', 'tbl_city_hotel_cost_master.city_id=tbl_city_master.id');


			if ($budget[1]>=500)
			{
				$start_budget = round($budget[0]-30);
				$this->db->where('cost >=',$start_budget,FALSE);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_POST['accomodation']);

			}
			else
			{

				$start_budget = round($budget[0]-30);
				$end_budget = round($budget[1]+30);

				$wherebudget = "(cost >= $start_budget and cost <=$end_budget)";
				$this->db->where($wherebudget);
				$this->db->where('tbl_city_hotel_cost_master.month_id',$month,FALSE);
				$this->db->where('hoteltype_id',$_POST['accomodation']);
			}
		}


		if (isset($_POST['weather']) && $_POST['weather'] != '')
		{

			$this->db->join('tbl_city_weathers', 'tbl_city_weathers.city_id=tbl_city_master.id');

			$weather = explode('-',$this->input->post('weather'));
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
				//$data[$key]['uniqueid']=$_POST['token'];
			}
		}


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
			if (isset($_POST['days']) && $_POST['days'] != '')
			{
				$days = $this->input->post('days',TRUE);
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

	function getMultiCountriesRome2Rio($start_city,$data)
	{

			$sessionStorage=array();
			$finalStorage=array();
			$sessionStorage=$this->session->userdata('storage');

			foreach ($data as $key => $list)
			{
					if(count($sessionStorage) && $sessionStorage!='')
					{
						 $searchkey = array_search($list['country_id'], array_column($sessionStorage, 'country_id'));
						 if($searchkey!='')
						 {
								$data[$key]['timetoreach']=$sessionStorage[$searchkey]['distance'];
								$data[$key]['actualtime']=$sessionStorage[$searchkey]['actualtime'];
						 }
						 else
						 {
							 $data[$key]['timetoreach']='';
 							 $data[$key]['actualtime']='';
						 }
				 }
				 else
				 {
					 $data[$key]['timetoreach']='';
 					 $data[$key]['actualtime']='';
				 }
			}

			$Rome2RioResponse=$this->getTotalTimeToReachFromRome2Rio($start_city,$data,2);

			$traveltime = explode('-',$this->input->post('traveltime'));
			if ($traveltime[1]>25) {
				$approx = 0.20 * $traveltime[1];
				$starttime = round($traveltime[1]-$approx);
				$endtime = 0;

			}
			else
			{

				$time = explode('-',$_POST['traveltime']);
				$start_time=$time[0];
				$end_time=$time[1];
				$starttime = round($start_time-($start_time*0.20));
				$endtime = round($end_time+($end_time*0.20));
			}
			 if (count($data))
			 {
					//$cnt=0;
					//echo "<pre>";print_r(count($Rome2RioResponse));die;
					foreach ($data as $key => $list)
					{
						  if($list['actualtime']=='')
						  {

									    $response=$Rome2RioResponse[$key];
											//echo $response."<br/>";
											$hours = floor($response / 60);
											$minutes = $response % 60;

											$tempstorage[]=array(
													'country_id'=>$list['country_id'],
													'distance'=>formattime($hours,$minutes),
													'actualtime'=>$response
											);

											if(count($sessionStorage))
											{
												$finalStorage=array_merge($sessionStorage,$tempstorage);
											}
											$this->session->set_userdata('storage',$finalStorage);

											if ($response == 'na')
											{
												$data[$key]['timetoreach'] = 'na';
												$data[$key]['actualtime']='na';
											}
											else
											{
												$data[$key]['timetoreach'] = formattime($hours,$minutes);
												$data[$key]['actualtime']=$response;
											}
											//$cnt++;
							}
						}
				}

				//echo $cnt;die;

				foreach ($data as $key => $list)
				{
					$hrs = $list['actualtime'] / 60;
					if($endtime==0)
					{
						if($starttime < $hrs)
						{
							unset($data[$key]);
						}
					}
					else if(($starttime > $hrs || $endtime < $hrs) && $endtime !=0)
					{
						unset($data[$key]);
					}
				}

				return $data;


	}


	function selectMultiCountriesWithNoTags($data,$startday,$endday,$originalArray)
	{
		$grouped_by_countries=array();
		$grouped_types = $data;
		$neededArray=array();

		foreach($grouped_types as $mainkey=>$mainlist)
		{
			foreach($mainlist as $subkey=>$sublist)
			{
				$neededArray[]=$sublist[0];
			}
		}

		$neededArray=$this->getMultiCountriesRome2Rio($_POST['start_city'],$neededArray);
		if(!count($neededArray))
		{
			return $neededArray;
		}

		foreach($originalArray as $arg)
		{
			$grouped_by_countries[$arg['country_id']][] = $arg;
		}



		foreach ($grouped_by_countries as $key => $mainarray)
		{
				foreach($mainarray as $cityKey=>$cityList)
				{
						$grouped_by_countries[$key][$cityKey]['sortorder']=$cityKey;
				}
        }
        //echo "<pre>";print_r($grouped_by_countries);die;

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
				//$new[$mainkey]['encryptkey']=string_encode($conid);
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
		$countries=$arrayToWrite;
		//print_r($arrayToWrite);die;
		$i=0;
		foreach ($countries as $mainkey => $mainlist)
		{
			$j=0;
			foreach ($mainlist as $subkey => $sublist)
			{
				$new['countries'][$i]['country_id']=$countryID=$countries[$mainkey][$subkey]['country_id'];
				$new['countries'][$i]['noofcities']=getcountrynoofCities($countryID);
				$new['countries'][$i]['country_name']=$countries[$mainkey][$subkey]['country_name'];
				$new['countries'][$i]['slug']=$countries[$mainkey][$subkey]['slug'];
				$new['countries'][$i]['rome2rio_country_name']=$countries[$mainkey][$subkey]['rome2rio_country_name'];
				$new['countries'][$i]['countrylatitude']=$countries[$mainkey][$subkey]['countrylatitude'];
				$new['countries'][$i]['countrylongitude']=$countries[$mainkey][$subkey]['countrylongitude'];
				$new['countries'][$i]['rome2rio_code']=$countries[$mainkey][$subkey]['rome2rio_code'];
				$new['countries'][$i]['country_conclusion']=$countries[$mainkey][$subkey]['country_conclusion'];
				$new['countries'][$i]['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
				$new['countries'][$i]['continent_id']=$countries[$mainkey][$subkey]['continent_id'];
				$new['countries'][$i]['country_total_days']=$countries[$mainkey][$subkey]['country_total_days'];
				/*if($countries[$mainkey][$subkey]['sortorder']==0)
				{
					$rome2rio_name[0]=$_POST['start_city'];
					$rome2rio_name[1]=$countries[$mainkey][$subkey]['rome2rio_name'];
					$timetoreach=getShortestDistance($rome2rio_name);
					$new['countries'][$i]['timetoreach']=$timetoreach;
				}	*/
				
				$new['countries'][$i]['cityData'][$j]['id']=$countries[$mainkey][$subkey]['id'];
				$new['countries'][$i]['cityData'][$j]['city_name']=$countries[$mainkey][$subkey]['city_name'];
				$new['countries'][$i]['cityData'][$j]['cityslug']=$countries[$mainkey][$subkey]['cityslug'];
				$new['countries'][$i]['cityData'][$j]['rome2rio_name']=$countries[$mainkey][$subkey]['rome2rio_name'];
				if(isset($countries[$mainkey][$subkey]['total_days']) && !empty($countries[$mainkey][$subkey]['total_days']))
				{
					$new['countries'][$i]['cityData'][$j]['total_days']=$countries[$mainkey][$subkey]['total_days'];
				}
				$new['countries'][$i]['cityData'][$j]['latitude']=$countries[$mainkey][$subkey]['citylatitude'];
				$new['countries'][$i]['cityData'][$j]['longitude']=$countries[$mainkey][$subkey]['citylongitude'];
				$new['countries'][$i]['cityData'][$j]['total_days']=$countries[$mainkey][$subkey]['total_days'];
				$new['countries'][$i]['cityData'][$j]['code']=$countries[$mainkey][$subkey]['code'];
				$new['countries'][$i]['cityData'][$j]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
				$new['countries'][$i]['cityData'][$j]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
			  $j++;
			}
			$i++;
		}
		//print_r($new['countries']);die;
		//$this->createMultiCountrySearchFile($new,$ArrayToStorInFile);
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


		$neededArray=$this->getMultiCountriesRome2Rio($_POST['start_city'],$neededArray);
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
				//$new[$mainkey]['encryptkey']=string_encode($conid);
			}

		}
		//echo $conid;die;
		//echo "<pre>df";print_r($countryArray);die;

		$arrayToWrite=array();
	   //echo "<pre>Multi";print_r($grouped_by_countries);die;
		foreach($grouped_by_countries as $key=>$list)
		{
			if(in_array($key,$countryArray))
			{
				$arrayToWrite[$key]=$grouped_by_countries[$key];
			}
		}

		if(count($arrayToWrite))
		{
			foreach ($arrayToWrite as $key => $mainarray)
			{
					foreach($mainarray as $cityKey=>$cityList)
					{
							$arrayToWrite[$key][$cityKey]['sortorder']=$cityKey;
					}
	        }

		}
		else
		{
			return $arrayToWrite;
		}
		$countries=$arrayToWrite;
		//print_r($arrayToWrite);die;
		
		$i=0;
		foreach ($countries as $mainkey => $mainlist)
		{
			$j=0;
			foreach ($mainlist as $subkey => $sublist)
			{
				$new['countries'][$i]['country_id']=$countryID=$countries[$mainkey][$subkey]['country_id'];
				$new['countries'][$i]['noofcities']=getcountrynoofCities($countryID);
				$new['countries'][$i]['country_name']=$countries[$mainkey][$subkey]['country_name'];
				$new['countries'][$i]['slug']=$countries[$mainkey][$subkey]['slug'];
				$new['countries'][$i]['rome2rio_country_name']=$countries[$mainkey][$subkey]['rome2rio_country_name'];
				$new['countries'][$i]['countrylatitude']=$countries[$mainkey][$subkey]['countrylatitude'];
				$new['countries'][$i]['countrylongitude']=$countries[$mainkey][$subkey]['countrylongitude'];
				$new['countries'][$i]['rome2rio_code']=$countries[$mainkey][$subkey]['rome2rio_code'];
				$new['countries'][$i]['country_conclusion']=$countries[$mainkey][$subkey]['country_conclusion'];
				$new['countries'][$i]['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
				$new['countries'][$i]['continent_id']=$countries[$mainkey][$subkey]['continent_id'];
				if(isset($countries[$mainkey][$subkey]['country_total_days']) && !empty($countries[$mainkey][$subkey]['country_total_days']))
				{
					$new['countries'][$i]['country_total_days']=$countries[$mainkey][$subkey]['country_total_days'];	
				}
				/*if($countries[$mainkey][$subkey]['sortorder']==0)
				{
					$rome2rio_name[0]=$_POST['start_city'];
					$rome2rio_name[1]=$countries[$mainkey][$subkey]['rome2rio_name'];
					$timetoreach=getShortestDistance($rome2rio_name);
					$new['countries'][$i]['timetoreach']=$timetoreach;
				}*/
				
				$new['countries'][$i]['cityData'][$j]['id']=$countries[$mainkey][$subkey]['id'];
				$new['countries'][$i]['cityData'][$j]['city_name']=$countries[$mainkey][$subkey]['city_name'];
				$new['countries'][$i]['cityData'][$j]['cityslug']=$countries[$mainkey][$subkey]['cityslug'];
				$new['countries'][$i]['cityData'][$j]['rome2rio_name']=$countries[$mainkey][$subkey]['rome2rio_name'];
				if(isset($countries[$mainkey][$subkey]['total_days']) && !empty($countries[$mainkey][$subkey]['total_days']))
				{
					$new['countries'][$i]['cityData'][$j]['total_days']=$countries[$mainkey][$subkey]['total_days'];
				}
				$new['countries'][$i]['cityData'][$j]['latitude']=$countries[$mainkey][$subkey]['citylatitude'];
				$new['countries'][$i]['cityData'][$j]['longitude']=$countries[$mainkey][$subkey]['citylongitude'];
				$new['countries'][$i]['cityData'][$j]['code']=$countries[$mainkey][$subkey]['code'];
				$new['countries'][$i]['cityData'][$j]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
				$new['countries'][$i]['cityData'][$j]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
			  $j++;
			}
			$i++;
		}
		//$this->createMultiCountrySearchFile($new,$ArrayToStorInFile);
		return $new;


	}



	function getLatandLongOfCity($city_id)
	{
		$Q=$this->db->query('select id,latitude as citylatitude,longitude as citylongitude,country_id,cityimage,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countrybanner from tbl_country_master where id=tbl_city_master.country_id) as countrybanner,city_name,travelguide from tbl_city_master where md5(id)="'.$city_id.'"');
		return $Q->row_array();
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

	function getUserRecommededAttractionsForCountry($cityfile)
	{
			if(isset($_POST['tags']) && $_POST['tags']>0)
			{
				$ids=$this->getIDS($_POST['tags']);
				return $this->getSelectedAttractions($ids,$cityfile);

			}
			else
			{
				return $this->writeAllUserAttraction($cityfile);
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

	function writeAllUserAttraction($city_id)
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
		return json_encode($attraction_decode);
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


	function getSelectedAttractions($ids,$city_id)
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

		return json_encode($attraction_decode);

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
		 return $row_set;
	}
	
	/* Below Code is for searched countries */

	function getSearchedCity($searchinput)
	{

		$data=array();
		if(isset($searchinput['searchtags']) && count($searchinput['searchtags'])>0)
		{
			$data=array();
			$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,md5(tbl_city_master.id) as cityid,city_name,tbl_city_master.slug as cityslug,total_days,latitude,longitude,tbl_city_master.country_id,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where tbl_country_master.id=tbl_city_master.country_id) as countryimage,rome2rio_name,code',FALSE);

			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($searchinput['searchtags']); $i++)
			{
					$tag = $searchinput['searchtags'][$i];
					if (count($searchinput['searchtags']) == 1)
					{
						$sq = '(tag_name="' . $tag . '")';
					}
					else
					{
						if ($i == 0)
						{
							$sq .= '(tag_name="' . $tag . '"';

						}
						else if ($i == count($searchinput['searchtags']) - 1)
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
			$this->db->where('rome2rio_name',$searchinput['sdestination']);
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
				return $newdata;
			}

		}
		else
		{
			//echo "<pre>";print_r($_SERVER["QUERY_STRING"])print_r($_REQUEST);die;
			$this->db->select('id,city_name,slug as cityslug,total_days,latitude,longitude,country_id,md5(id) as cityid,city_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,rome2rio_name,code');
			$this->db->from('tbl_city_master');
			$this->db->where('rome2rio_name',$searchinput['sdestination']);
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
			for ($i = 0; $i < count($_POST['searchtags']); $i++)
			{
					$tag = $_POST['searchtags'][$i];
					if (count($_POST['searchtags']) == 1)
					{
						$sq = '(tag_name="' . $tag . '")';
					}
					else
					{
						if ($i == 0)
						{
							$sq .= '(tag_name="' . $tag . '"';

						}
						else if ($i == count($_POST['searchtags']) - 1)
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

				$plus = substr($_POST['sdays'], -1);
				if($plus == '+')
				{
					$traveldays = 0;
					$extra_days='all';
				}
				else
				{

					$enteredDays=(int)$_POST['sdays'];
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
				//$extra_days=$daysTaken-$enteredDays;
				$extra_days=0;
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





	function getAttractionsOfSelectedCity($cityfile)
	{

		if(isset($_POST['searchtags']) && count($_POST['searchtags'])>0)
		{
			$ids=$this->getIDS($inputdecode['tags']);
			$this->getSelectedAttractions($ids,$cityfile);

		}
		else
		{
			$this->writeAllUserAttraction($cityfile);
		}

	}

	function createsearchFile($token,$searchinput)
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
		fwrite($file,json_encode($searchinput));
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
		fwrite($file,json_encode($_POST));
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
		$Q=$this->db->query('select id,country_name,country_conclusion,countryimage,slug from tbl_country_master where slug="'.$slug.'"');
		$data=$Q->row_array();
		return $data;
	}

	function getOtherCitiesOfThisCountry($country_id,$cityArray)
	{
		$data=array();
		$this->db->select('id,city_name,slug as cityslug,rome2rio_name,latitude,longitude,code,cityimage');
		$this->db->from('tbl_city_master');
		$this->db->where('total_attraction_time >',0);
		$this->db->where('country_id',$country_id);
		$this->db->where_not_in('id',$cityArray);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$i=0;
			foreach($Q->result_array() as $row)
			{
				$data[$i]=$row;
				$data[$i]['sortorder']=-1;
				$i++;
			}
		}
		return $data;
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

	    $Q=$DB2->query("SELECT title,post_name,CONCAT(LEFT(image, LENGTH(image) - LOCATE('.', REVERSE(image))),'-1000x600.',SUBSTRING_INDEX(image, '.', -1)) AS image FROM ( SELECT p.post_title AS title,p.post_status AS 'status',p.post_date AS date,p.post_content AS content,p.post_name AS post_name,(SELECT `guid` FROM tx_posts WHERE id = m.meta_value) AS image FROM tx_posts p, tx_postmeta m WHERE p.post_type = 'post' AND p.post_status = 'publish' AND p.id = m.post_id AND m.meta_key = '_thumbnail_id' ORDER BY date DESC LIMIT 3) TT");
	   	$data=$Q->result_array();
	   	return $data;
	   // echo "<pre>";print_r($Q->result_array());die;
	}

	function addSubscriber()
	{
		$Q=$this->db->query('select id from tbl_subscribers where email="'.$_POST['email'].'"');
		if($Q->num_rows()>0)
		{
			return 3;
		}

		$data=array(
				'email'=>$_POST['email']
			);
		$this->db->insert('tbl_subscribers',$data);

		$newdata=array(
				'es_email_name'=>'',
				'es_email_mail'=>$_POST['email'],
				'es_email_status'=>'Confirmed',
				'es_email_created'=>date('Y-m-d H:i:s'),
				'es_email_viewcount'=>0,
				'es_email_group'=>'Public',
				'es_email_guid'=>'',
			);
		$DB2 = $this->load->database('otherdb', TRUE);
		$Q=$DB2->insert('tx_es_emaillist',$newdata);

		$this->sendSubscriberEmail();
		$this->sendSubscriberUserEmail();
		return 1;
	}
	function getSettingsEmail()
	{
		$Q=$this->db->query('select email from tbl_settings where id=1');
		return $Q->row_array();
	}

	function getSettings()
	{
		$Q=$this->db->query('select * from tbl_settings where id=1');
		return $Q->row_array();
	}

	function sendSubscriberEmail()
	{
		$subject='Taxidio Subscriber';
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$message=$this->load->view('subscribertemplate',$data,true);
		$from='info@taxidio.com';
		$to=$adminemail;
		//$to='ei.jinal.php@gmail.com';
		$this->email->from($from);
		$this->email->subject($subject);
		$this->email->reply_to($_POST['email']);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}

	function sendSubscriberUserEmail()
	{
		$subject='Taxidio Subscriber';
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$data['taxidio']=$this->getSettings();
		$message=$this->load->view('sendSubscriberUserEmail',$data,true);
		$from='info@taxidio.com';
		$to=$_POST['email'];
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->reply_to($adminemail);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}

	function getCityImages($cityids)
	{
		$data=array();
		$this->db->select('cityimage,city_name,slug');
		$this->db->from('tbl_city_master');
		$this->db->where_in('id',$cityids);
		$Q=$this->db->get();
		return $Q->result_array();
	}

}

/* End of file Home_m.php */
/* Location: ./application/models/Home_m.php */
