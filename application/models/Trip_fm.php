<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Trip_fm extends CI_Model
{
	function getUserRecommededAttractionsForCountry($cityfile,$itineraryid)
	{
		if(!file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$data=array();
			$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);

			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{
				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractions($ids,$cityfile,$itineraryid);

			}
			else
			{
				$this->writeAllUserAttraction($cityfile,$itineraryid);
			}
			return 1;
		}
		return 2;
	}


	function getUserRecommededAttractionsForNewCity($cityfile,$itineraryid)
	{
		if(!file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);
			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{

				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractions($ids,$cityfile,$itineraryid,1);
			}
			else
			{
				$this->writeAllUserAttraction($cityfile,$itineraryid,1);
			}

			return 1;
		}
		return 2;
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

	function getSelectedAttractions($ids,$city_id,$itineraryid,$isnew)
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

				if($isnew==1)
				{
					if (count($intersectionofatt) > 0 && ($attlist['properties']['tag_star']==1 || $attlist['properties']['tag_star']==2))
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
				else
				{
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
		}



		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

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

		//echo "<pre>";print_r($attraction_decode_rel);die;

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

		return $attraction_decode_res;

	}

	function writeAllUserAttraction($city_id,$itineraryid,$isnew)
	{
		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
		/*if(!file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id))
		{
			$this->writeAttractionsInFile($city_id);
		}*/

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

		foreach($attraction_decode as $k=>$v)
		{
			if($isnew==1)
			{
				if($v['properties']['tag_star']==1 || $v['properties']['tag_star']==2)
				{
					$attraction_decode[$k]['isselected']=1;
				}
				else
				{
					$attraction_decode[$k]['isselected']=0;
				}
			}
			else
			{
				$attraction_decode[$k]['isselected']=1;
			}
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}

		//echo "<pre>";print_r($attraction_decode);die;

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
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


	function makeFileForThisCity($cityfile,$itineraryid)
	{

		$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
		{

			$ids=$this->getIDS($inputdecode['tags']);
			$this->getSelectedAttractions($ids,$cityfile,$itineraryid,1);
		}
		else
		{
			$this->writeAllUserAttraction($cityfile,$itineraryid,1);
		}

	}



/* Following Code is for saved multicountries*/

	function getCombinationKey($iti)
	{
		$this->db->select('tbl_itineraries_multicountrykeys.combination_key');
		$this->db->from('tbl_itineraries_multicountrykeys');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_multicountrykeys.itineraries_id');
		$this->db->where('tbl_itineraries.id',$iti);
		$Q=$this->db->get();
		return $Q->row_array();

	}

	function setMultiCountriesMD5($encryptkey,$iti)
	{
		$combinations_encode = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);

		$encryptionkeyArray=array();
		foreach($combinations_decode as $key=>$list)
		{
			if($list['encryptkey']==$encryptkey)
			{
				$encryptionkeyArray=$combinations_decode[$key];

			}

		}

		return $encryptionkeyArray;

	}

	function getCombinationKeyLogin($iti)
	{
		$data='';
		$Q=$this->db->query('select combination_key from tbl_itineraries_multicountrykeys where itineraries_id="'.$iti.'"');
		$getData=$Q->row_array();
		$data=$getData['combination_key'];
		return $data;
	}



	function getContinentCountryName($country_id)
	{
		$Q=$this->db->query('select country_name from tbl_country_master where id="'.$country_id.'"');
		return $Q->row_array();
	}

	function getContinentName($tripname)
	{
		$countrycodes=explode('-',$tripname);
		$this->db->select('id');
		$this->db->from('tbl_country_master');
		$this->db->where_in('rome2rio_code',$countrycodes);
		$Q=$this->db->get();
		$countryids=array_column($Q->result_array(),'id');

		$this->db->select('continent_id');
		$this->db->from('tbl_continent_countries');
		$this->db->where_in('country_id',$countryids);
		$Q1=$this->db->get();
		$continentids=array_column($Q1->result_array(),'continent_id');
		$c=array_count_values($continentids);
		$continent_id = array_search(max($c), $c);


		$co=$this->db->query('select continent_name as country_name from tbl_continent_master where id="'.$continent_id.'"');
		return $co->row_array();


	}

	function deleteTrip($id)
	{

		$tripid=string_decode($id);

		$Q=$this->db->query('select id,trip_type from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'" and id="'.$tripid.'" limit 1');

		if($Q->num_rows()>0)
		{

			$data=$Q->row_array();
			if($data['trip_type']==1)
			{

				$this->deleteSingleCountryTrip($data['id']);
			}
			else if($data['trip_type']==2)
			{
				$this->deleteMultiCountryTrip($data['id']);
			}
			else if($data['trip_type']==3)
			{
				$this->deleteSearchedCityTrip($data['id']);
			}

			$this->db->where('user_id',$this->session->userdata('fuserid'));
			$this->db->where('new_itinerary_id',$data['id']);
			$this->db->delete('tbl_copy_trips');

			$this->db->select('id');
            $this->db->from('tbl_itinerary_questions');
            $this->db->where('itinerary_id',$data['id']);
            
            $Q1=$this->db->get();
            if($Q1->num_rows()>0)
            {
                foreach($Q1->result_array() as $row1)
                {
                    $this->db->where('question_id',$row1['id']);
                    $this->db->delete('tbl_itinerary_answers');
                }
            }
            
            $this->db->where('itinerary_id',$data['id']);
            $this->db->delete('tbl_itinerary_questions');

            $this->db->where('itinerary_id',$data['id']);
            $this->db->delete('tbl_share_itineraries');

			if(is_dir(FCPATH.'userfiles/savedfiles/'.$tripid))
			{
				$files = glob(FCPATH.'userfiles/savedfiles/'.$tripid.'/*');
				foreach($files as $file)
				{
				   if(is_file($file))
				   {
				      unlink($file);
				   }
				}
				rmdir(FCPATH.'userfiles/savedfiles/'.$tripid);
			}


		}
		else
		{
			$this->session->set_flashdata('error', 'Oopp..!Something went wrong..');
			redirect('trips');
		}
	}


	function deleteSingleCountryTrip($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_itineraries');

		$this->db->where('itinerary_id',$id);
		$this->db->delete('tbl_itineraries_cities');

	}

	function deleteSearchedCityTrip($id)
	{

		$this->db->where('id',$id);
		$this->db->delete('tbl_itineraries');

		$this->db->where('itinerary_id',$id);
		$this->db->delete('tbl_itineraries_searched_cities');

	}


	function deleteMultiCountryTrip($id)
	{

		$Q=$this->db->query('select id from tbl_itineraries_multicountrykeys where itineraries_id="'.$id.'"');

		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{

				$Q1=$this->db->query('select id from tbl_itineraries_multicountries where combination_id="'.$row['id'].'"');

				if($Q1->num_rows()>0)
				{


					foreach($Q1->result_array() as $row1)
					{
						$this->db->where('country_combination_id',$row1['id']);
					    $this->db->delete('tbl_itineraries_multicountries_cities');
					}
				}

				$this->db->where('combination_id',$row['id']);
				$this->db->delete('tbl_itineraries_multicountries');
			}
		}

		$this->db->where('id',$id);
		$this->db->delete('tbl_itineraries');

		$this->db->where('itineraries_id',$id);
		$this->db->delete('tbl_itineraries_multicountrykeys');


	}

	function getTripDetails($id)
	{
		$result=get_invited_trip_details($id);
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
        $this->db->group_start();
		if($result!==FALSE){
			$this->db->where('user_id', $result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
		$this->db->where('id',$id);
		$this->db->group_end();
		$this->db->limit(1);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			//echo "<Pre>";print_r($Q->result_array());die;
			return $Q->row_array();
		}
		else
		{
			show_404();
		}

	}


	function updateTrip($id,$result=FALSE)
	{
		$data=array();
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		if($result!==FALSE){
			$this->db->where('user_id',$result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
		$this->db->where('id',$id);
		$this->db->limit(1);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
			$start_date=date('Y-m-d', strtotime(str_replace('/', '-', $_POST['start_date'])));
			$end_date=date('Y-m-d', strtotime(str_replace('/', '-', $_POST['end_date'])));
			$datediff = strtotime($end_date) - strtotime($start_date);
			$days=floor($datediff / (60 * 60 * 24))+1;

			$json=json_decode($data['inputs'],TRUE);

			if($data['trip_type']==3)
			{
				$json['sstart_date']=$_POST['start_date'];
				$json['sdays']=$days;
			}
			else
			{
				$json['start_date']=$_POST['start_date'];
				$json['days']=$days;
			}

			//echo "<pre>";print_r($json);die;
			$newjson=json_encode($json);
			$dataToUpdate=array(
					'inputs'=>$newjson,
					'user_trip_name'=>$_POST['user_trip_name'],
					'trip_mode'=>$_POST['trip_mode'],
					'sort_planned_iti'=>99999,
					'start_date'=>strtotime(str_replace('/', '-', $_POST['start_date'])),
					'end_date'=>strtotime(str_replace('/', '-', $_POST['end_date'])),
					'modified'=>date('Y-m-d H:i:s'),
					'last_modified_by'=>$this->session->userdata('fuserid')
				);

				$config = array(
					'field' => 'slug',
					'slug' => 'slug',
					'table' => 'tbl_itineraries',
					'id' => 'id',
				);
				$this->load->library('slug', $config);
				$slugdata = array(
					'slug' => $_POST['user_trip_name'],
				);
				$slug = $this->slug->create_uri($slugdata,$id);
				$dataToUpdate['slug'] = $slug;

			$this->db->where('id',$id);
			if($result!==FALSE){
				$this->db->where('user_id',$result['user_id']);
			}
			else
			{
				$this->db->where('user_id',$this->session->userdata('fuserid'));
			}
			$this->db->update('tbl_itineraries',$dataToUpdate);

			if(isset($_POST['email']) && $_POST['email']!='')
			{
				$this->db->where('id',$this->session->userdata('fuserid'));
			  $this->db->update('tbl_front_users',array('email'=>$_POST['email']));
				$this->session->set_userdata('askforemail',0);
			}

		}
		else
		{
			show_404();
		}

	}

	function ChangeOrderOfCities($type)
	{
			if($type=='singlecountry')
			{
				$this->orderXForSingleCountrySaved();
			}
			else if($type=='multicountry')
			{
				 $this->OrderXforMultiCountry();
			}
			else
			{
				$this->OrderXforSearch();
			}
	}

	function orderXForSingleCountrySaved()
	{
			$countryid=$_POST['coid'];
			$iti=$_POST['iti'];
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.'singlecountry','r+');
			$citydata=fgets($file);
			fclose($file);
			$cities=json_decode($citydata,TRUE);

			if(array_key_exists($countryid,$cities) && count($cities[$countryid])==count($_POST['drag-x']))
			{
					foreach($_POST['drag-x'] as $key=>$list)
					{
						$cities[$countryid][$list]['sortorder']=$key;
					}

					$finalsort = array();
					foreach($cities[$countryid] as $k=>$v)
					{
						$finalsort['sortorder'][$k] = $v['sortorder'];
					}
					array_multisort($finalsort['sortorder'], SORT_ASC,$cities[$countryid]);

					$cityWithDistance=CalculateDistance($cities,$countryid);

					$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.'singlecountry','w+');
					$citydata=fwrite($file,json_encode($cityWithDistance));
					fclose($file);
			}
			else
			{
				 echo "Not";die;
			}
	}



	function OrderXforSearch()
	{
		// echo "<pre>";print_r($_POST);die;
		 $iti=$_POST['iti'];
		 $filename=$this->getCitiesInFile($iti);
		 $file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filename,'r');
		 $cityarrayinfile=fgets($file);
		 $cityarray=json_decode($cityarrayinfile,TRUE);
		 fclose($file);

		 foreach($_POST['drag-x'] as $key=>$list)
		 {
			 $cityarray[$list]['sortorder']=$key;
		 }

		 $finalsort = array();
		 foreach($cityarray as $k=>$v)
		 {
			 $finalsort['sortorder'][$k] = $v['sortorder'];
		 }
		 array_multisort($finalsort['sortorder'], SORT_ASC,$cityarray);

		 $cityWithDistance=CalculateDistanceForSearch($cityarray);
		 $file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filename,'w+');
		 $citydata=fwrite($file,json_encode($cityWithDistance));
		 fclose($file);
	}

	function getCitiesInFile($iti)
	{
		$cityarray=array();
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		return $filename;

	}

	function OrderXforMultiCountry()
	{
		$countryid=$_POST['coid'];
		$iti=$_POST['iti'];
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/cities','r+');
		$citydata=fgets($file);
		fclose($file);
		$cities=json_decode($citydata,TRUE);
		if(array_key_exists($countryid,$cities) && count($cities[$countryid])==count($_POST['drag-x']))
		{
				foreach($_POST['drag-x'] as $key=>$list)
				{
					$cities[$countryid][$list]['sortorder']=$key;
				}

				$finalsort = array();
				foreach($cities[$countryid] as $k=>$v)
				{
					$finalsort['sortorder'][$k] = $v['sortorder'];
				}
				array_multisort($finalsort['sortorder'], SORT_ASC,$cities[$countryid]);

				$cityWithDistance=CalculateDistance($cities,$countryid);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/cities','w+');
				$citydata=fwrite($file,json_encode($cityWithDistance));
				fclose($file);
		}
		else
		{
			 echo "Not";die;
		}
	}

}


?>
