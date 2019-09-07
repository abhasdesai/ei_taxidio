<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Account_wm extends CI_Model
{

	function deleteUserSavedFiles()
	{
		$Q=$this->db->query('select id from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'"');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row)
			{
				if(is_dir(FCPATH.'userfiles/savedfiles/'.$row['id']))
				{
					$files = glob(FCPATH.'userfiles/savedfiles/'.$row['id'].'/*');
					foreach($files as $file)
					{
					   if(is_file($file))
					   {
					      unlink($file);
					   }
					}
					rmdir(FCPATH.'userfiles/savedfiles/'.$row['id']);
				}

			}
		}
	}

	function deleteMyAccountFiles()
	{
		if(is_dir(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid')))
		{
			$files = glob(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid').'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }
			}
			rmdir(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid'));
		}
	}

	function checkUseridAndCountryId($country_id,$uniqueid)
	{
		$Q=$this->db->query('select id from tbl_itineraries where country_id="'.$country_id.'" and user_id="'.$_POST['userid'].'" and sess_id="app" and trip_type=1 and uniqueid="'.$uniqueid.'" limit 1');
		$data=$Q->row_array();
		return $data;
	}

	function checkUseridAndCountryIdForSearch($country_id,$uniqueid)
	{
		$Q=$this->db->query('select id from tbl_itineraries where country_id="'.$country_id.'" and user_id="'.$this->session->userdata('fuserid').'" and sess_id="'.$this->session->userdata('randomstring').'" and trip_type=3 and uniqueid="'.$uniqueid.'" limit 1');
		$data=$Q->row_array();
		return $data;
	}

	function checkUseridAndCountryIdForMultiCountry($secretkeyid,$uniqueid)
	{
		$this->db->select('tbl_itineraries.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries.id');
		$this->db->where('tbl_itineraries.sess_id','app');
		$this->db->where('tbl_itineraries.user_id',$_POST['userid']);
		$this->db->where('tbl_itineraries.uniqueid',$uniqueid);
	    $this->db->where('tbl_itineraries.trip_type',2);
		$this->db->where('tbl_itineraries_multicountrykeys.combination_key',$secretkeyid);
		$Q=$this->db->get();
		$data=$Q->row_array();
		return $data;
	}

	function getUserSelectedCityAttractions($ids,$city_id,$token)
	{

		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
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
		//echo "<pre>";print_r($attraction_decode);die;

		return $attraction_decode;

	}


	function writeAllUserAttraction($city_id,$token)
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

			$attraction_decode[$k]['isselected']=1;
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}

		return $attraction_decode;
	}



	function otherAttractions($ids,$attraction_decode,$city_id)
	{

		$attraction_decode_rel=$attraction_decode;

		/* Start Relaxation and spa */

		$relaxation_decode=array();
		$relax_decode=array();
		if(file_exists(FCPATH.'userfiles/relaxationspa/'.$city_id))
		{
			$relaxation_json = file_get_contents(FCPATH.'userfiles/relaxationspa/'.$city_id);
			$relax_decode=json_decode($relaxation_json,TRUE);
		}


		if(in_array(17,$ids))
		{
			$relaxation_decode =  $relax_decode;
		}
		else
		{
			if(count($relax_decode))
			{
				$relaxation_decode = getSelectedKeys($relax_decode);
			}
		}

		if(count($relaxation_decode))
		{
			$attraction_decode_rel=array_merge($attraction_decode,$relaxation_decode);
		}
		/* End Of Relaxation and spa */

		$attraction_decode_spo=$attraction_decode_rel;

		/* Start Sport and Adventures and Stadiums */
		$sport_decode=array();
		$stadium_decode=array();
		$adv_decode=array();
		$adv_decode_temp=array();

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
			$adv_decode_temp=array_merge($sport_decode,$stadium_decode);
		}
		else if(count($sport_decode) && !count($stadium_decode))
		{
			$adv_decode_temp=$sport_decode;
		}
		else if(!count($sport_decode) && count($stadium_decode))
		{
			$adv_decode_temp=$stadium_decode;
		}

		if(in_array(12,$ids))
		{
			$adv_decode=$adv_decode_temp;
		}
		else
		{
			$adv_decode=getSelectedKeys($adv_decode_temp);
		}
		if(count($adv_decode))
		{
			$attraction_decode_spo=array_merge($attraction_decode_rel,$adv_decode);
		}
		$attraction_decode_res=$attraction_decode_spo;

		/* End Sport and Adventures and Stadiums */


		/* Start Restaurant */

		$restaurant_decode=array();
		$res_decode=array();
		if(file_exists(FCPATH.'userfiles/restaurant/'.$city_id))
		{
			$restaurant_json = file_get_contents(FCPATH.'userfiles/restaurant/'.$city_id);
			$res_decode=json_decode($restaurant_json,TRUE);
		}

		if(in_array(15,$ids))
		{
			$restaurant_decode =  $res_decode;
		}
		else
		{
			if(count($relax_decode))
			{
				$restaurant_decode = getSelectedKeys($res_decode);
			}
		}

		if(count($restaurant_decode))
		{
			$attraction_decode_res=array_merge($attraction_decode_spo,$restaurant_decode);
		}

		/* End Restaurant */
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


	function saveSingleIitnerary($country_id,$uniqueid)
	{
			$itineraryid=$this->checkUseridAndCountryId($country_id,$uniqueid);
			//$encodeid=string_encode($itineraryid);

			$userid=$_POST['userid'];
			$input=$_POST['inputs'];
			$cities_encode=$_POST['countryidwithcities'];
			$city_attractions=json_decode($_POST['city_attractions'],TRUE);
			$cities=json_decode($cities_encode,TRUE);
			$singlecountry_required_array[$country_id]=$cities[$country_id];
			//echo "<pre>".$itineraryid;print_r($singlecountry_required_array[$country_id]);die;
			$tripname='';
			$citiorcountries ='';
			if(count($itineraryid))
			{

				$oldCities=$this->getoldCitiesOfCountry($country_id,$cities[$country_id]);
				foreach($cities[$country_id] as $list)
				{
					$tripname .=$list['code'].'-';
					$citiorcountries .=$list['city_name'].'-';
					if(count($oldCities))
					{
						$key = array_search($list['id'], array_column($oldCities, 'city_id'));

						if($key !== FALSE)
						{
							$itinerarydata=array(
								'city_attractions'=>json_encode($city_attractions[$list['id']])
							);

							$this->db->where('id',$oldCities[$key]['id']);
							$this->db->update('tbl_itineraries_cities',$itinerarydata);
						}
						else
						{
							$itinerarydata=array(
								'itinerary_id'=>$itineraryid['id'],
								'city_id'=>$list['id'],
								'city_attractions'=>json_encode($city_attractions[$list['id']])
							);

							$this->db->insert('tbl_itineraries_cities',$itinerarydata);
						}
					}
				}

				$data=array(
						'user_id'=>$userid,
						'sess_id'=>'app',
						'trip_type'=>1,
						'inputs'=>$input,
						'singlecountry'=>json_encode($singlecountry_required_array),
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s'),
						'tripname'=>substr($tripname,0,-1),
						'citiorcountries'=>substr($citiorcountries,0,-1),
						'country_id'=>$country_id
					);

				$this->db->where('id',$itineraryid['id']);
				$this->db->update('tbl_itineraries',$data);
				$lastid=$itineraryid['id'];
			}
			else
			{
				$tripname_main=getrowbycondition('country_name','tbl_country_master',"id=$country_id");
				
				$data=array(
						'user_id'=>$userid,
						'sess_id'=>'app',
						'trip_type'=>1,
						'trip_mode'=>1,
						'inputs'=>$input,
						'singlecountry'=>json_encode($singlecountry_required_array),
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s'),
						'tripname'=>time(),
						'country_id'=>$country_id,
						'uniqueid'=>$uniqueid,
						'user_trip_name'=>'Trip '.$tripname_main['country_name'],
						'citiorcountries'=>'',
						'isblock'=>0,
						'views'=>0,
						'rating'=>0
					);
				//print_r($data);
				//die;
				$this->db->insert('tbl_itineraries',$data);
				$lastid=$this->db->insert_id();
				//$city_attractions=json_decode($_POST['city_attractions'],TRUE);
				//print_r($city_attractions);die;
				foreach($cities[$country_id] as $list)
				{
					if(array_key_exists($list['id'],$city_attractions))
					{
						$tripname .=$list['code'].'-';
						$citiorcountries .=$list['city_name'].'-';
						$itinerarydata=array(
								'itinerary_id'=>$lastid,
								'city_id'=>$list['id'],
								'city_attractions'=>json_encode($city_attractions[$list['id']])
							);

						$this->db->insert('tbl_itineraries_cities',$itinerarydata);
					}
				}

				//return $citiorcountries;die;

				$slug=$this->generateItiSlug('Trip '.$tripname_main['country_name']);
				$this->db->where('country_id',$country_id);
				$this->db->where('user_id',$userid);
				$this->db->where('sess_id','app');
				$this->db->update('tbl_itineraries',array('slug'=>$slug,'tripname'=>substr($tripname,0,-1),'citiorcountries'=>substr($citiorcountries,0,-1)));

			}
			//echo $lastid;die;
			return $lastid;
	}

	function generateItiSlug($tripname)
	{
			$config = array(
					'field' => 'slug',
					'slug' => 'slug',
					'table' => 'tbl_itineraries',
					'id' => 'id',
			);
			$this->load->library('slug', $config);
			$slugdata = array(
				'slug' => $tripname,
			);
			$slug = $this->slug->create_uri($slugdata);
			return $slug;
	}



	function saveMultiIitnerary($uniqueid,$countryid)
	{
		$secretkeyid=string_encode($countryid);
		$foldername=string_decode($secretkeyid);
		$itineraryid=$this->checkUseridAndCountryIdForMultiCountry($secretkeyid,$uniqueid);
	
		$userid=$_POST['userid'];
		$input=$_POST['inputs'];
		$cities_encode=$_POST['countryidwithcities'];
		$cities_decode=json_decode($cities_encode,TRUE);
		$city_attractions=json_decode($_POST['city_attractions'],TRUE);
		$combinations=$_POST['combinations'];

		$currentcombination=$secretkeyid;

		$tripname='';
		$citiorcountries='';
		if(count($itineraryid))
		{
				//echo "<pre>";print_r($itineraryid);die;
				$mainitineraryid=$itineraryid['id'];

				$currentcities=$this->getCurrentCities($secretkeyid,$mainitineraryid);

				$countries=$this->getMultiCountries($mainitineraryid);


				$countriesAgain=explode('-',string_decode($secretkeyid));
				$newCityArray=array();
				for($i=0;$i<count($countriesAgain);$i++)
				{
					foreach($cities_decode[$countriesAgain[$i]] as $list)
					{
						$newCityArray[]=$list['id'];
					}
				}
				//echo "<pre>";print_r($newCityArray);die;
				$this->deleteOldCities($mainitineraryid,$newCityArray);

				for($i=0;$i<count($countries);$i++)
				{
					$co_id=$countries[$i]['country_id'];
					$tripname .=$cities_decode[$co_id][0]['rome2rio_code'].'-';
					//$citiorcountries .=$cities_decode[$co_id][0]['country_name'].'-';
					foreach($cities_decode[$countries[$i]['country_id']] as $list)
					{

						if(in_array($list['id'], $currentcities))
						{
							$city_data=array(
									'attractions'=>json_encode($city_attractions[$list['id']])
								);

							$this->db->where('country_combination_id',$countries[$i]['id']);
							$this->db->update('tbl_itineraries_multicountries_cities',$city_data);
						}
						else
						{
							$city_data=array(
									'city_id'=>$list['id'],
									'country_combination_id'=>$countries[$i]['id'],
									'attractions'=>json_encode($city_attractions[$list['id']])
								);

							$this->db->insert('tbl_itineraries_multicountries_cities',$city_data);

						}

					}
				}

				$data=array(
					'inputs'=>$input,
					'modified'=>date('Y-m-d H:i:s'),
					'multicountries'=>$combinations,
					'cities'=>$cities_encode,
					'tripname'=>substr($tripname,0,-1),
					//'citiorcountries'=>substr($citiorcountries,0,-1)
				);
				$this->db->where('id',$mainitineraryid);
				$this->db->update('tbl_itineraries',$data);

				return $mainitineraryid;
				//echo "1";die;
		}
		else
		{
			$this->load->model('Trip_fm');
				$tripname='';
				$countries=explode('-',string_decode($secretkeyid));
				//print_r($countries);die;

				$data=array(
					'user_id'=>$userid,
					'sess_id'=>'app',
					'trip_type'=>2,
					'trip_mode'=>1,
					'inputs'=>$input,
					'singlecountry'=>'',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'tripname'=>'',
					'country_id'=>0,
					'multicountries'=>$combinations,
					'cities'=>$cities_encode,
					'uniqueid'=>$uniqueid,
					'user_trip_name'=>'',
					'citiorcountries'=>'',
					'isblock'=>0,
					'views'=>0,
					'rating'=>0
				);


				$this->db->insert('tbl_itineraries',$data);
				$lastid=$this->db->insert_id();

				$keydata=array(
						'combination_key'=>$secretkeyid,
						'itineraries_id'=>$lastid
					);

				$this->db->insert('tbl_itineraries_multicountrykeys',$keydata);
				$lastcombinationid=$this->db->insert_id();

				//echo "<pre>";
				//print_r($countries);
				//print_r($cities_decode);die;
				for($i=0;$i<count($countries);$i++)
				{
					//echo "<pre>";print_r($countries);die;

					$countryArray=array(
							'country_id'=>$countries[$i],
							'combination_id'=>$lastcombinationid
						);
					$this->db->insert('tbl_itineraries_multicountries',$countryArray);
					$lastCountryId=$this->db->insert_id();


					$co_id=$countries[$i];
					//echo "<pre>";print_r($cities_decode[$co_id]);die;
					$tripname .=$cities_decode[$co_id][0]['rome2rio_code'].'-';
					$tripname_main=$this->Trip_fm->getContinentName(substr($tripname,0,-1));
					$citiorcountries .=$cities_decode[$co_id][0]['country_name'].'-';
					foreach($cities_decode[$countries[$i]] as $list)
					{
						if(array_key_exists($list['id'],$city_attractions))
						{
							$city_data=array(
									'city_id'=>$list['id'],
									'country_combination_id'=>$lastCountryId,
									'attractions'=>json_encode($city_attractions[$list['id']])
								);

							$this->db->insert('tbl_itineraries_multicountries_cities',$city_data);
						}
					}
				}


				$slug=$this->generateItiSlug('Trip '.$tripname_main['country_name']);
				$this->db->where('id',$lastid);
				$this->db->update('tbl_itineraries',array('slug'=>$slug,'tripname'=>substr($tripname,0,-1),'user_trip_name'=>'Trip '.$tripname_main['country_name'],'citiorcountries'=>substr($citiorcountries,0,-1)));

				return $lastid;

		}




	}

	function getAttractionsOfCities($city_id,$uniqueid,$foldername)
	{
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.md5($city_id)))
		{
			$attractions=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.md5($city_id));
		}
		else
		{
			$getInputs=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);
			$city_attractions_decoded=array();
			if(isset($inputdecode['searchtags']) && $inputdecode['searchtags']>0)
			{
				$ids=$this->getIDS($_GET['searchtags']);
				$city_attractions_decoded=$this->getUserSelectedCityAttractions($ids,md5($city_id),$uniqueid);

			}
			else
			{
				$city_attractions_decoded=$this->writeAllUserAttraction(md5($city_id),$uniqueid);
			}
			$attractions=json_encode($city_attractions_decoded);
		}

		return $attractions;
	}

	function getAttractionsOfCitiesSaved($city_id,$iti)
	{
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.md5($city_id)))
		{
			$attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.md5($city_id));
		}
		else
		{
			$attractions=file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.md5($city_id));
		}

		return $attractions;
	}



	function getCurrentCities($secretkeyid,$mainitineraryid)
	{
		$cities=array();
		$this->db->select('city_id');
		$this->db->from('tbl_itineraries_multicountries');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.id=tbl_itineraries_multicountries.combination_id');
		$this->db->join('tbl_itineraries_multicountries_cities','tbl_itineraries_multicountries_cities.country_combination_id=tbl_itineraries_multicountries.id');
		$this->db->where('combination_key',$secretkeyid);
		$this->db->where('itineraries_id',$mainitineraryid);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$cities = array_map('current', $Q->result_array());
		}

		return $cities;
	}

	function getMultiCountries($itineraries_id)
	{
		$data=array();
		$this->db->select('tbl_itineraries_multicountries.id,tbl_itineraries_multicountries.country_id');
		$this->db->from('tbl_itineraries_multicountries');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.id=tbl_itineraries_multicountries.combination_id');
		$this->db->where('itineraries_id',$itineraries_id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$data=$Q->result_array();
		}
		return $data;
	}

	function deleteOldCities($itineraries_id,$newCityArray)
	{
		$data=array();
		$this->db->select('tbl_itineraries_multicountries_cities.id,city_id');
		$this->db->from('tbl_itineraries_multicountries_cities');
		$this->db->join('tbl_itineraries_multicountries','tbl_itineraries_multicountries.id=tbl_itineraries_multicountries_cities.country_combination_id');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.id=tbl_itineraries_multicountries.combination_id');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_multicountrykeys.itineraries_id');
		$this->db->where('tbl_itineraries.id',$itineraries_id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		foreach($data as $list)
		{
			if(!in_array($list['city_id'],$newCityArray))
			{
				$this->db->where('id',$list['id']);
				$this->db->delete('tbl_itineraries_multicountries_cities');
			}

		}

	}

	function getoldCitiesOfCountry($country_id,$cities)
	{
		$data=array();
		$this->db->select('tbl_itineraries_cities.id,city_id');
		$this->db->from('tbl_itineraries_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_cities.itinerary_id');
		$this->db->where('country_id',$country_id);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->where('sess_id',$this->session->userdata('randomstring'));
		$this->db->where('trip_type',1);
		$Q=$this->db->get();
		$data=$Q->result_array();

		foreach($data as $k=>$list)
		{
			$key = array_search($list['city_id'], array_column($cities, 'id'));
			if($key !== FALSE){
			}
			else
			{
				//echo "else".$key."<br/>";
				$this->db->where('id',$list['id']);
				$this->db->delete('tbl_itineraries_cities');
				unset($data[$k]);
			}
		}

		return $data;
	}

	function getoldCitiesOfIti($iti,$cities)
	{
		$data=array();
		$this->db->select('tbl_itineraries_cities.id,city_id');
		$this->db->from('tbl_itineraries_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_cities.itinerary_id');
		$this->db->where('tbl_itineraries.id',$iti);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q=$this->db->get();
		$data=$Q->result_array();

		foreach($data as $k=>$list)
		{
			$key = array_search($list['city_id'], array_column($cities, 'id'));
			if($key !== FALSE){
			}
			else
			{
				$this->db->where('id',$list['id']);
				$this->db->delete('tbl_itineraries_cities');
				unset($data[$k]);
			}
		}

		return $data;
	}

	function checkUseridAndItiId($iti)
	{
		$data=array();
		$Q=$this->db->query('select id,country_id from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'" and id="'.$iti.'" limit 1');
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();

		}
		else
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}

		return $data;

	}


	function update_single_itinerary($iti)
	{
			$itinerarydata=array();
			$itineraryid=$this->checkUseridAndItiId($iti);
			$input=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
			$cities_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/singlecountry');
			$cities=json_decode($cities_encode,TRUE);
			$tripname='';
			$country_id=$itineraryid['country_id'];
			if(count($itineraryid))
			{

				$oldCities=$this->getoldCitiesOfIti($iti,$cities[$country_id]);
				$citiorcountries='';
				foreach($cities[$country_id] as $list)
				{
					$tripname .=$list['code'].'-';
					$citiorcountries .=$list['city_name'].'-';
					if(count($oldCities))
					{
						$city_attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.md5($list['id']));


						$key = array_search($list['id'], array_column($oldCities, 'city_id'));

						if($key !== FALSE)
						{

							$itinerarydata=array(
								'city_attractions'=>$city_attractions
							);

							$this->db->where('id',$oldCities[$key]['id']);
							$this->db->update('tbl_itineraries_cities',$itinerarydata);



						}
						else
						{
							$itinerarydata=array(
								'itinerary_id'=>$iti,
								'city_id'=>$list['id'],
								'city_attractions'=>$city_attractions
							);

							$this->db->insert('tbl_itineraries_cities',$itinerarydata);


						}


					}


				}

				$data=array(
						'singlecountry'=>$cities_encode,
						'modified'=>date('Y-m-d H:i:s'),
						'tripname'=>substr($tripname,0,-1),
						'citiorcountries'=>substr($citiorcountries,0,-1)
					);

				$this->db->where('id',$iti);
				$this->db->update('tbl_itineraries',$data);

			}
	}


	function update_searched_itinerary($secretkey,$iti)
	{
		$input=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
		$cities_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$secretkey);
		$cities=json_decode($cities_encode,TRUE);
		$country_id=$cities[0]['country_id'];
		$tripname='';

		$oldCities=$this->getoldSearchedCitiesOfCountryIti($country_id,$cities,$iti);
		//echo "<pre>";print_r($oldCities);die;
		$citiorcountries='';
		foreach($cities as $list)
		{
			$tripname .=$list['code'].'-';
			$citiorcountries .=$list['city_name'].'-';
			if(count($oldCities))
			{
				if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.md5($list['id'])))
				{
					$city_attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.md5($list['id']));
				}


				$key = array_search($list['id'], array_column($oldCities, 'city_id'));

				if($key !== FALSE)
				{

					$itinerarydata=array(
						'city_attractions'=>$city_attractions
					);

					$this->db->where('id',$oldCities[$key]['id']);
					$this->db->update('tbl_itineraries_searched_cities',$itinerarydata);



				}
				else
				{
					$itinerarydata=array(
						'itinerary_id'=>$iti,
						'city_id'=>$list['id'],
						'city_attractions'=>$city_attractions
					);

					$this->db->insert('tbl_itineraries_searched_cities',$itinerarydata);


				}


			}


		}

		$data=array(
				'inputs'=>$input,
				'singlecountry'=>$cities_encode,
				'modified'=>date('Y-m-d H:i:s'),
				'tripname'=>substr($tripname,0,-1),
				'citiorcountries'=>substr($citiorcountries,0,-1)
			);

		//$this->db->where('country_id',$country_id);
		$this->db->where('id',$iti);
		$this->db->update('tbl_itineraries',$data);

	}

	function getUserDetails()
	{
		$Q=$this->db->query('select * from tbl_front_users where id="'.$this->session->userdata('fuserid').'"');
		return $Q->row_array();
	}

	function getCountries()
	{
		$data=array();
		$Q=$this->db->query('select id,name from tbl_worlds_countries order by name asc');
		return $Q->result_array();
	}

	function editUser()
	{
		$datetime=date('Y-m-d H:i:s');
		$dob = implode("-", array_reverse(explode("/", $_POST['dob'])));
		if($this->session->userdata('issocial')!=1)
		{
			$data=array(
				'name'=>ucwords($_POST['name']),
				'email'=>$_POST['email'],
				'passport'=>$_POST['passport'],
				'country_id'=>$_POST['country_id'],
				'dob'=>$dob,
				'logintype'=>1,
				'phone'=>$_POST['phone'],
				'gender'=>$_POST['gender']
			);
		}
		else
		{
			$data=array(
				'passport'=>$_POST['passport'],
				'country_id'=>$_POST['country_id'],
				'dob'=>$dob,
				'logintype'=>1,
				'phone'=>$_POST['phone'],
				'gender'=>$_POST['gender']
			);
		}

		$this->db->where('id',$this->session->userdata('fuserid'));
		$this->db->update('tbl_front_users',$data);


		$sessionArray=array(
					'name'=>ucwords($_POST['name']),
					'email'=>$_POST['email'],
					'issocial'=>$this->session->userdata('issocial')
				);


		$this->session->set_userdata($sessionArray);
	}



	/*
	function uploadImage()
	{
			$img_nm='';
			$nm=time().''.rand(1,999999);
			$config['upload_path'] = './userfiles/userimages/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] =$nm;
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('userimage'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('myprofile');
			}
			else
			{
				$upload_data = $this->upload->data();
				$source_img = $upload_data['full_path']; //Defining the Source Image
				$img_nm=$this->create_thumb_gallery($upload_data,$upload_data['file_name']);
				return $img_nm;
			}
	}



	function create_thumb_gallery($upload_data,$nm)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/userimages/'.$nm;
		$config['new_image'] = './userfiles/userimages/medium/';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['quality'] = '100%';
		$config['width'] = 250;
		$config['height'] = 250;
		$config['file_name'] = $nm;
		$dim = (intval($upload_data['image_width']) / intval($upload_data['image_height'])) - ($config['width'] / $config['height']);
		$config['master_dim'] = ($dim > 0)? 'height' : 'width';

		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config

		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/userimages/'.$nm;
		$config['new_image'] = './userfiles/userimages/small/';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['quality'] = '100%';
		$config['width'] = 150;
		$config['height'] = 150;
		$config['file_name'] = $nm;
		$dim = (intval($upload_data['image_width']) / intval($upload_data['image_height'])) - ($config['width'] / $config['height']);
		$config['master_dim'] = ($dim > 0)? 'height' : 'width';
		$this->image_lib->clear();
		$this->image_lib->initialize($config);
		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config

		return $nm;
	}
	*/

	function check_email($email)
	{
		$Q=$this->db->query('select id from tbl_front_users where email="'.$email.'" and id!="'.$this->session->userdata('fuserid').'" and googleid="" and facebookid=""');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_email','That Email already exists.');
			return FALSE;
		}
		return TRUE;
	}

	function countTrips()
	{
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function getUserTrips($limit,$start)
	{
		$data=array();
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->limit($limit,$start);
		$this->db->order_by('id','DESC');
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


	function resetTrip($id)
	{
		$data=$this->getTripDetails($id);
		$this->makeSession($data['sess_id'],$data['singlecountry'],$data['inputs'],$data['id'],$data['trip_type'],$cities='',$combinations='');
		if($data['trip_type']==1)
		{
			$slug=$this->getCountrySlugAndId($id);
		}
		else if($data['trip_type']==2)
		{
			$slug=$this->getCountryKeyAndId($id);
		}

		return $slug;
	}

	function getTripDetails($id)
	{
		$data=array();
		$plainid=string_decode($id);
		$Q=$this->db->query('select * from tbl_itineraries where id="'.$plainid.'" and user_id="'.$this->session->userdata('fuserid').'" limit 1');
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
		}
		else
		{
			redirect(site_url('trips'));
		}
		return $data;
	}

	function makeSession($sess_id,$singlecountry,$inputs,$itirnaryid,$trip_type,$cities,$combinations)
	{
		if($trip_type==1)
		{

			if (!is_dir(FCPATH.'userfiles/savedfiles/'.$itirnaryid))
			{
				mkdir(FCPATH.'userfiles/savedfiles/'.$itirnaryid, 0777,true);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/singlecountry','w');
				fwrite($file,$singlecountry);
				fclose($file);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/inputs','w');
				fwrite($file,$inputs);
				fclose($file);

				$this->makeSingleAttractionFiles($itirnaryid,1);
			}

		}
		else if($trip_type==2)
		{
			if (!is_dir(FCPATH.'userfiles/savedfiles/'.$itirnaryid))
			{
				mkdir(FCPATH.'userfiles/savedfiles/'.$itirnaryid, 0777,true);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/combinations','w');
				fwrite($file,$combinations);
				fclose($file);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/cities','w');
				fwrite($file,$cities);
				fclose($file);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/inputs','w');
				fwrite($file,$inputs);
				fclose($file);

				$this->makeSingleAttractionFiles($itirnaryid,2);
			}
		}
		else if($trip_type==3)
		{
			$Q=$this->db->query('select city_id from tbl_itineraries_searched_cities where itinerary_id="'.$itirnaryid.'" and ismain=1  limit 1');
			$data=$Q->row_array();
			if (!is_dir(FCPATH.'userfiles/savedfiles/'.$itirnaryid))
			{
				mkdir(FCPATH.'userfiles/savedfiles/'.$itirnaryid, 0777,true);
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/inputs','w');
				fwrite($file,$inputs);
				fclose($file);

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/mainfile','w');
				fwrite($file,$data['city_id']);
				fclose($file);


				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/'.$data['city_id'],'w');
				fwrite($file,$singlecountry);
				fclose($file);
			}

			$this->makeSingleAttractionFiles($itirnaryid,3);

		}

	}





	function makeSingleAttractionFiles($itirnaryid,$triptype)
	{
		$data=array();
		if($triptype==1)
		{
		$Q=$this->db->query('select md5(city_id) as city_id,city_attractions from tbl_itineraries_cities where itinerary_id="'.$itirnaryid.'"');
		}
		else if ($triptype==3) {
			$Q=$this->db->query('select md5(city_id) as city_id,city_attractions from tbl_itineraries_searched_cities where itinerary_id="'.$itirnaryid.'"');
		}
		else if($triptype==2)
		{
			$this->db->select('md5(tbl_itineraries_multicountries_cities.city_id) as city_id,attractions as city_attractions,combination_key');
			$this->db->from('tbl_itineraries_multicountries_cities');
			$this->db->join('tbl_itineraries_multicountries','tbl_itineraries_multicountries.id=tbl_itineraries_multicountries_cities.country_combination_id');

			$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.id=tbl_itineraries_multicountries.combination_id');

			$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_multicountrykeys.itineraries_id');
			$this->db->where('tbl_itineraries.id',$itirnaryid);
			$Q=$this->db->get();

		}

		//echo "<Pre>";print_r($Q->result_array());die;
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itirnaryid.'/'.$row['city_id'],'w');
				fwrite($file,$row['city_attractions']);
				fclose($file);

			}
		}
	}

	function getCountrySlugAndId($id)
	{
		$data=array();
		$this->db->select('tbl_country_master.slug,tbl_itineraries.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_country_master','tbl_itineraries.country_id=tbl_country_master.id');
		$this->db->where('tbl_itineraries.id',string_decode($id));
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
		}
		else
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}
		return $data;
	}

	function getCountryKeyAndId($id)
	{
		$data=array();
		$this->db->select('tbl_itineraries_multicountrykeys.combination_key,tbl_itineraries.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries.id');
		$this->db->where('tbl_itineraries.id',string_decode($id));
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
		}
		else
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}
		return $data;
	}


	function makeFileForThisCity($city_id,$itineraryid)
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
		$attraction_decode = json_decode($attraction_json,TRUE);



		$attraction_decode[0]['distance']=0;
		for($i=1;$i<count($attraction_decode);$i++)
		{
			$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);
			$attraction_decode[$i]['distance']=$distance;
		}

		$finalsort = array();
		foreach($attraction_decode as $k=>$v)
		{
			$finalsort['distance'][$k] = $v['distance'];
			$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
		}
		array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);

		foreach($attraction_decode as $k=>$v)
		{
			$attraction_decode[$k]['isselected']=1;
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}






	function writeAttractionsInFile($city_id)
	{
		$data=array();
		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,(select longitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylongitude,(select latitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylatitude from tbl_city_paidattractions where md5(city_id)="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{
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


	function resetMultiTrip($id)
	{
		$data=$this->getTripDetails($id);
		$this->makeSession($data['sess_id'],$data['singlecountry'],$data['inputs'],$data['id'],$data['trip_type'],$data['cities'],$data['multicountries']);
		$slug=$this->getCountryKeyAndId($id);
		return $slug;
	}


	// Multicountries


	function setMultiCountries($encryptkey,$iti)
	{
		$directories=$this->makeDirectoryandFiles($encryptkey,$iti);
		return $directories;
	}

	function makeDirectoryandFiles($encryptkey,$iti)
	{
		$combinations_encode = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);
		$encryptionkeyArray=array();


		foreach($combinations_decode as $key=>$list)
		{
			/*echo "======";
			echo "<pre>";
			print_r($list['encryptkey']);
			print_r($encryptkey);
			echo "======";
			echo string_decode($list['encryptkey'])."==".string_decode($encryptkey);
			echo "<br/><br/><br/>";*/
			//echo $encryptkey;die;

			if($list['encryptkey']==$encryptkey)
			{
				$encryptionkeyArray=$combinations_decode[$key];
			}
			else if(string_decode($list['encryptkey'])==string_decode($encryptkey))
			{
				$combinations_decode[$key]['encryptkey']=$encryptkey;
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations','w');
				fwrite($file,json_encode($combinations_decode));
				fclose($file);
				$encryptionkeyArray=$combinations_decode[$key];

			}

	    }
	    if(!count($encryptionkeyArray))
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}
		return $encryptionkeyArray;
	}


	function makeFileForThisCityMultiSaved($city_id,$iti)
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
		$attraction_decode = json_decode($attraction_json,TRUE);



		$attraction_decode[0]['distance']=0;
		for($i=1;$i<count($attraction_decode);$i++)
		{
			$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);
			$attraction_decode[$i]['distance']=$distance;
		}

		$finalsort = array();
		foreach($attraction_decode as $k=>$v)
		{
			$finalsort['distance'][$k] = $v['distance'];
			$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
		}
		array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);

		foreach($attraction_decode as $k=>$v)
		{
			$attraction_decode[$k]['isselected']=1;
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}





	function checkUseridAndCountryIdForMultiCountrySaved($iti)
	{
		$Q=$this->db->query('select id from tbl_itineraries where id="'.$iti.'" and user_id="'.$this->session->userdata('fuserid').'"');
		if($Q->num_rows()<1)
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}
	}

	function getCurrentCitiesExist($iti)
	{
		$this->db->select('tbl_itineraries_multicountries_cities.city_id');
		$this->db->from('tbl_itineraries_multicountries_cities');
		$this->db->join('tbl_itineraries_multicountries','tbl_itineraries_multicountries.id=tbl_itineraries_multicountries_cities.country_combination_id');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.id=tbl_itineraries_multicountries.combination_id');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_multicountrykeys.itineraries_id');
		$this->db->where('tbl_itineraries.id',$iti);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$cities = array_map('current', $Q->result_array());
		}
		//echo "<pre>";print_r($cities);die;
		return $cities;

	}

	function updatesave_multi_itinerary($iti)
	{
		$this->checkUseridAndCountryIdForMultiCountrySaved($iti);
		$itineraryid=$iti;
		$input=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
		$combinations=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations');
		$cities=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
		$cities_decode=json_decode($cities,TRUE);

		$tripname='';
		$currentcities=$this->getCurrentCitiesExist($iti);


		$countries=$this->getMultiCountries($iti);


		//echo "<pre>";
		//print_r($countries);
		//print_r($cities_decode);die;

		$newCityArray=array();

		for($i=0;$i<count($countries);$i++)
		{
			foreach($cities_decode[$countries[$i]['country_id']] as $list)
			{
				$newCityArray[]=$list['id'];
			}
		}

		$this->deleteOldCities($iti,$newCityArray);

		for($i=0;$i<count($countries);$i++)
		{
			//echo "<pre>";print_r($countries);die;
			$co_id=$countries[$i]['country_id'];
			//echo "<pre>";print_r($co_id);die;
			//echo "<pre>";print_r($cities_decode);die;
			$tripname .=$cities_decode[$co_id][0]['rome2rio_code'].'-';
			//echo "<pre>";print_r($cities_decode[$countries[$i]['country_id']]);die;
			foreach($cities_decode[$co_id] as $list)
			{
				//echo "<pre>";print_r($list);die;
				if(in_array($list['id'], $currentcities))
				{
					$attractions=$this->getAttractionsOfCitiesSaved($list['id'],$iti);
					//echo $countries[$i]['id']."<br/>";
					//echo "<pre>";print_r($attractions)."<br/>=======";
					$city_data=array(
							'attractions'=>$attractions
						);

					$this->db->where('city_id',$list['id']);
					$this->db->where('country_combination_id',$countries[$i]['id']);
					$this->db->update('tbl_itineraries_multicountries_cities',$city_data);

				}
				else
				{
					$attractions=$this->getAttractionsOfCitiesSaved($list['id'],$iti);
					$city_data=array(
							'city_id'=>$list['id'],
							'country_combination_id'=>$countries[$i]['id'],
							'attractions'=>$attractions
						);

					$this->db->insert('tbl_itineraries_multicountries_cities',$city_data);

				}

			}
		}

		//echo $tripname;die;

		//die;
		$data=array(
			'inputs'=>$input,
			'modified'=>date('Y-m-d H:i:s'),
			'multicountries'=>$combinations,
			'cities'=>$cities,
			'tripname'=>substr($tripname,0,-1)
		);
		$this->db->where('id',$iti);
		$this->db->update('tbl_itineraries',$data);

	}


/* Searched City Itineraries */



function getoldSearchedCitiesOfCountryIti($country_id,$cities,$iti)
	{
		$data=array();
		$this->db->select('tbl_itineraries_searched_cities.id,city_id');
		$this->db->from('tbl_itineraries_searched_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_searched_cities.itinerary_id');
		//$this->db->where('country_id',$country_id);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->where('tbl_itineraries.id',$iti);
		$this->db->where('trip_type',3);
		$Q=$this->db->get();
		$data=$Q->result_array();

		foreach($data as $k=>$list)
		{
			$key = array_search($list['city_id'], array_column($cities, 'id'));
			if($key !== FALSE){
			}
			else
			{
				$this->db->where('id',$list['id']);
				$this->db->delete('tbl_itineraries_searched_cities');
				unset($data[$k]);
			}
		}

		return $data;
	}







	function getoldSearchedCitiesOfCountry($country_id,$cities,$uniqueid)
	{
		$data=array();
		$this->db->select('tbl_itineraries_searched_cities.id,city_id');
		$this->db->from('tbl_itineraries_searched_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_searched_cities.itinerary_id');
		//$this->db->where('country_id',$country_id);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->where('sess_id',$this->session->userdata('randomstring'));
		$this->db->where('trip_type',3);
		$Q=$this->db->get();
		$data=$Q->result_array();

		foreach($data as $k=>$list)
		{
			$key = array_search($list['city_id'], array_column($cities, 'id'));
			if($key !== FALSE){
			}
			else
			{
				$this->db->where('id',$list['id']);
				$this->db->delete('tbl_itineraries_searched_cities');
				unset($data[$k]);
			}
		}

		return $data;
	}


	function save_searched_itinerary($secretkey,$uniqueid)
	{
		$input=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
		$cities_encode=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$secretkey);
		$cities=json_decode($cities_encode,TRUE);
		$country_id=$cities[0]['country_id'];
		$itineraryid=$this->checkUseridAndCountryIdForSearch($country_id,$uniqueid);
		$tripname='';

		if(count($itineraryid))
		{
			//echo $itineraryid['id'];die;
			$oldCities=$this->getoldSearchedCitiesOfCountry($country_id,$cities,$uniqueid);
			$citiorcountries='';
			foreach($cities as $list)
			{
				$tripname .=$list['code'].'-';
				$citiorcountries .=$list['city_name'].'-';
				if(count($oldCities))
				{
					if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id'])))
					{
						$city_attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id']));
					}


					$key = array_search($list['id'], array_column($oldCities, 'city_id'));

					if($key !== FALSE)
					{

						$itinerarydata=array(
							'city_attractions'=>$city_attractions
						);

						$this->db->where('id',$oldCities[$key]['id']);
						$this->db->update('tbl_itineraries_searched_cities',$itinerarydata);



					}
					else
					{
						$itinerarydata=array(
							'itinerary_id'=>$itineraryid['id'],
							'city_id'=>$list['id'],
							'city_attractions'=>$city_attractions
						);

						$this->db->insert('tbl_itineraries_searched_cities',$itinerarydata);


					}


				}


			}

			$data=array(
					'user_id'=>$this->session->userdata('fuserid'),
					'sess_id'=>$this->session->userdata('randomstring'),
					'trip_type'=>3,
					'inputs'=>$input,
					'singlecountry'=>$cities_encode,
					'modified'=>date('Y-m-d H:i:s'),
					'tripname'=>substr($tripname,0,-1),
					'citiorcountries'=>substr($citiorcountries,0,-1),
					'uniqueid'=>$uniqueid
				);

			//$this->db->where('country_id',$country_id);
			/*$this->db->where('uniqueid',$uniqueid);
			$this->db->where('user_id',$this->session->userdata('fuserid'));
			$this->db->where('sess_id',$this->session->userdata('randomstring'));*/
			$this->db->where('id',$itineraryid['id']);
			$this->db->update('tbl_itineraries',$data);

			return $itineraryid['id'];
			//$this->db->where('id',$lastid);
			//$this->db->update('tbl_itineraries',array('tripname'=>substr($tripname,0,-1)));

		}
		else
		{
			$tripname_main=$this->Trip_fm->getContinentCountryName($country_id);
			$data=array(
						'user_id'=>$this->session->userdata('fuserid'),
						'sess_id'=>$this->session->userdata('randomstring'),
						'trip_type'=>3,
						'trip_mode'=>1,
						'inputs'=>$input,
						'singlecountry'=>$cities_encode,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s'),
						'tripname'=>time(),
						'country_id'=>$country_id,
						'uniqueid'=>$uniqueid,
						'citiorcountries'=>'',
						'user_trip_name'=>'Trip '.$tripname_main['country_name'],
						'isblock'=>0,
						'views'=>0,
						'rating'=>0
					);
			$this->db->insert('tbl_itineraries',$data);
			$lastid=$this->db->insert_id();
			$citiorcountries='';
			foreach($cities as $list)
			{

				$tripname .=$list['code'].'-';
				$citiorcountries .=$list['city_name'].'-';
				$city_attractions='';
				if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id'])))
				{
					$city_attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id']));
				}

				$ismain=0;
				if($secretkey==$list['id'])
				{
					$ismain=1;
				}

				$data=array(
						'itinerary_id'=>$lastid,
						'city_id'=>$list['id'],
						'city_attractions'=>$city_attractions,
						'ismain'=>$ismain
					);

				$this->db->insert('tbl_itineraries_searched_cities',$data);
			}

			$slug=$this->generateItiSlug('Trip '.$tripname_main['country_name']);
			$this->db->where('id',$lastid);
			$this->db->update('tbl_itineraries',array('slug'=>$slug,'tripname'=>substr($tripname,0,-1),'citiorcountries'=>substr($citiorcountries,0,-1)));
			return $lastid;
		}
	}



	/*  Searched City  */



  function resetSearchedCityTrips($id)
	{
		$data=$this->getTripDetails($id);
		$this->makeSession($data['sess_id'],$data['singlecountry'],$data['inputs'],$data['id'],$data['trip_type'],$data['cities'],$data['multicountries']);
		return $data;
	}

	function getInputFileData($itid)
	{
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/inputs','r');
		$fileinputdata=json_decode(fgets($file),TRUE);
		fclose($file);
		return $fileinputdata;
	}

	function getSearchedCityOther($maincityarray,$itid)
	{
		$inputdata=$this->getInputFileData($itid);
		if (isset($inputdata['searchtags']) && $inputdata['searchtags'] != '')
		{
			return $this->getSearchedCityOtherWithTags($maincityarray,'1',$isadd=1,$inputdata['sdays'],$itid,$inputdata['searchtags']);

		}
		else
		{
			return $this->getSearchedCityOtherWithNoTags($maincityarray,'1',$isadd=1,$inputdata['sdays'],$itid);
		}

	}

	function getSearchedCityOtherFromFile($maincityarray,$isadd,$iti)
	{
		$data=array();

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs','r');
		$fileinputs_encoded=fgets($file);
		$fileinputs=json_decode($fileinputs_encoded,TRUE);
		fclose($file);

		if (isset($fileinputs['searchtags']) && count($fileinputs['searchtags']))
		{
			return $this->getSearchedCityOtherWithTags($maincityarray,'2',$isadd,$fileinputs['sdays'],$iti,$fileinputs['searchtags']);
		}
		else
		{
			return $this->getSearchedCityOtherWithNoTags($maincityarray,'2',$isadd,$fileinputs['sdays'],$iti);
		}

	}


	function getSearchedCityOtherWithNoTags($maincityarray,$check,$isadd,$sdays,$itid)
	{
		$cityids=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$check,$isadd,$itid,$sdays);
		if($extra_days===0)
		{
			return $cityids;
		}
		foreach($maincityarray as $list)
		{
			$cityids[]=$list['id'];
		}
		$latlng=$this->getLatLongOfMainCity($itid);
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


	function getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$check,$isadd='',$itid,$sdays)
	{
		if($check==1)
		{
			$totaldaystaken=0;
			$extra_days=0;
			foreach($maincityarray as $list)
			{
				$totaldaystaken+=$list['total_days'];
			}

			$plus = substr($sdays, -1);
			if($plus == '+')
			{
				$traveldays = 0;
				$extra_days='all';
			}
			else
			{
				$traveldays = (int)$sdays;
				if($traveldays > $totaldaystaken)
				{
					$extra_days=$traveldays-$totaldaystaken;
				}
				else if($traveldays < $totaldaystaken && count($maincityarray)==1)
				{
					$extra_days=-1;
				}
			}

		}
		else
		{
			$totaldaystaken=0;
			$extra_days=0;

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/'.$filename,'r');
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
					//$extra_days=-1;
					$extra_days=0;
				}
			}
		}
		return $extra_days;

	}



	function getSearchedCityOtherWithTags($maincityarray,$check,$isadd,$sdays,$itid,$tags)
	{
		$data=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForTags($maincityarray,$check,$isadd,$sdays,$itid);
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

			$latlng=$this->getLatLongOfMainCity($itid);
			$lat=$latlng['latitude'];
			$lng=$latlng['longitude'];

			$this->db->select('tbl_city_attraction_log.*,city_name,total_days,md5(tbl_city_master.id) as cityid,cityimage,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance',FALSE);
			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
		    $this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($tags); $i++)
			{
					$tag = $tags[$i];
					if (count($tags) == 1)
					{
						$sq = '(tag_name="' . $tag . '")';
					}
					else
					{
						if ($i == 0)
						{
							$sq .= '(tag_name="' . $tag . '"';

						}
						else if ($i == count($tags) - 1)
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
						$totaldaysneededOfOriginalDestionation=$this->getTotaldaysneededOfOriginalDestionation($itid);
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
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);


			$cityids=array();
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
			}
			$latlng=$this->getLatLongOfMainCity($itid);
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
						$totaldaysneededOfOriginalDestionation=$this->getTotaldaysneededOfOriginalDestionation($itid);
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


	}

	function getTotaldaysneededOfOriginalDestionation($itid)
	{
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		//$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/'.$filename,'r');
		$maincityarray_encode=fgets($file);
		$maincityarray=json_decode($maincityarray_encode,TRUE);
		fclose($file);
		return $maincityarray[0]['totaldaysneeded'];
	}


	function getTimeNeedToTravelCurrentCityForTags($maincityarray,$check,$isadd='',$sdays,$itid)
	{
		if($check==1)
		{
			$randomstring=$this->session->userdata('randomstring');
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);


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

				$plus = substr($fileinputs['sdays'], -1);
				if($plus == '+')
				{
					$traveldays = 0;
					$extra_days='all';
				}
				else
				{

					$enteredDays=(int)$fileinputs['sdays'];
					$extra_days=0;

					if($enteredDays<$daysTaken && count($maincityarray)==1)
					{
						$extra_days=-1;
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
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/inputs','r');
			$fileinputs_encoded=fgets($file);
			$fileinputs=json_decode($fileinputs_encoded,TRUE);
			fclose($file);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/mainfile','r');
			$filename=fgets($file);
			fclose($file);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/'.$filename,'r');
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
				//$extra_days=-1;
				$extra_days=0;
			}
			else if($enteredDays>$daysTaken)
			{
				$extra_days=$enteredDays-$daysTaken;
			}


			return $extra_days;
		}
	}

	function getLatLongOfMainCity($itid)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$itid.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$Q=$this->db->query('select latitude,longitude,rome2rio_name,total_days from tbl_city_master where id="'.$filename.'" limit 1');
		return $Q->row_array();
	}


	function addExtraCity($citydetails,$iti)
	{

		$this->createExtraCityFile($citydetails,$iti);

	}

	function createExtraCityFile($citydetails,$iti)
	{

		$randomstring=$this->session->userdata('randomstring');

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filename,'r');
		$filedata=json_decode(fgets($file),TRUE);

		$checkSearch = array_search($_POST['cityid'],array_column($filedata,'cityid'));

		if($checkSearch===false)
		{
			$countkey=count($filedata);
			$filedata[$countkey]=$citydetails;
		}
		else
		{

		}
		fclose($file);
		if(count($filedata))
		{
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filename,'w');
			fwrite($file,json_encode($filedata));
			fclose($file);
		}

		$this->createAttractionFileForExtraSearchCity($_POST['cityid'],$iti);

	}


	function createAttractionFileForExtraSearchCity($cityfile,$iti)
	{
		$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
		{

			$ids=$this->getIDS($inputdecode['tags']);
			$this->getSelectedAttractionsSearch($ids,$cityfile,$iti);
		}
		else
		{
			$this->writeAllUserAttractionForSingleCountrySearch($cityfile,$iti);
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


	function getSelectedAttractionsSearch($ids,$city_id,$iti)
	{
		$c=0;
		$key2array=array();
		$key2key='';


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



		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

	}

	function writeAllUserAttractionForSingleCountrySearch($city_id,$iti)
	{
		$c=0;
		$key2array=array();
		$key2key='';


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
			$attraction_decode[$k]['isselected']=1;
			$attraction_decode[$k]['tempremoved']=0;
			$attraction_decode[$k]['order']=$k;
		}


		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$city_id,'w');
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
		if(file_exists(FCPATH.'userfiles/sport/'.$city_id))
		{

			$sport_json = file_get_contents(FCPATH.'userfiles/sport/'.$city_id);
			$sport_decode = json_decode($sport_json,TRUE);
		}
		if(count($sport_decode))
		{
			$attraction_decode_spo=array_merge($attraction_decode_rel,$sport_decode);
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

	function checkCityExist($cityid,$iti)
	{

		$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['searchtags']) && $inputdecode['searchtags']>0)
		{
				$data=array();
				$this->db->select('tbl_city_attraction_log.*,md5(tbl_city_master.id) as cityid,tbl_city_master.id,city_name,tbl_city_master.slug as cityslug,total_days,latitude,longitude,tbl_city_master.country_id,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where tbl_country_master.id=tbl_city_master.country_id) as countryimage,rome2rio_name,code',FALSE);

				$this->db->from('tbl_city_attraction_log');
				$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			    $this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

				$sq = '';
				for ($i = 0; $i < count($inputdecode['searchtags']); $i++)
				{
						$tag = $inputdecode['searchtags'][$i];
						if (count($inputdecode['searchtags']) == 1)
						{
							$sq = '(tag_name="' . $tag . '")';
						}
						else
						{
							if ($i == 0)
							{
								$sq .= '(tag_name="' . $tag . '"';

							}
							else if ($i == count($inputdecode['searchtags']) - 1)
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
				$this->db->where_in('md5(tbl_city_master.id)',$cityid);
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

					return $data[0];
				}

				return $data;

		}
		else
		{
			$Q=$this->db->query('select id,city_name,slug as cityslug,total_days,latitude,longitude,country_id,md5(id) as cityid,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,rome2rio_name,code from tbl_city_master where md5(id)="'.$cityid.'" limit 1');
			return $Q->row_array();

		}

	}


	/*Image Upload*/

	function img_save_to_file_profile() {
		$flag1 = true;
		$errormsg = "";
		if ($_FILES['img']['name'] != "") {
			$config['upload_path'] = './userfiles/storage/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width'] = '';
			$config['max_height'] = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config);
			//$this->upload->do_upload('img');

			if (!$this->upload->do_upload('img'))
			{
				 $response = array(
					"status" => 'failed',
					);
			}
			else
			{
				$image = $this->upload->data();
				$imgdir = FCPATH;

				if ($image['file_name']) {
					$data['image'] = $image['file_name'];
				}

				$imagePath = site_url('userfiles/storage') . '/' . $data['image'];

				$response = array(
					"status" => 'success',
					"url" => $imagePath,
					"width" => $image['image_width'],
					"height" => $image['image_height'],
					"image_name" => $image['file_name'],
				);
			}

			print json_encode($response);
		}

	}

	function img_crop_to_file_profile() {
		$this->load->library('upload');
		$this->load->library('image_lib');
		$imgUrl = $_POST['imgUrl'];
		$imgInitW = $_POST['imgInitW'];
		$imgInitH = $_POST['imgInitH'];
		$imgW = $_POST['imgW'];
		$imgH = $_POST['imgH'];
		$imgY1 = $_POST['imgY1'];
		$imgX1 = $_POST['imgX1'];
		$cropW = $_POST['cropW'];
		$cropH = $_POST['cropH'];
		$angle = $_POST['rotation'];
		$jpeg_quality = 100;
		$fname = substr($imgUrl, strrpos($imgUrl, '/') + 1);
		$newFileName = substr($fname, 0, (strrpos($fname, ".")));
		$ext = explode('.', $fname);
		$output_filename = FCPATH . "/userfiles/storage/medium/" . $newFileName;
		$what = getimagesize($imgUrl);
		switch (strtolower($what['mime'])) {
		case 'image/png':
			$img_r = imagecreatefrompng($imgUrl);
			$source_image = imagecreatefrompng($imgUrl);
			$type = '.' . $ext[1];
			break;
		case 'image/jpeg':
			$img_r = imagecreatefromjpeg($imgUrl);
			$source_image = imagecreatefromjpeg($imgUrl);
			error_log("jpg");
			$type = '.' . $ext[1];
			break;
		case 'image/gif':
			$img_r = imagecreatefromgif($imgUrl);
			$source_image = imagecreatefromgif($imgUrl);
			$type = '.' . $ext[1];
			break;
		default:die('image type not supported');
		}
		if (!is_writable(dirname($output_filename))) {
			$response = Array(
				"status" => 'error',
				"message" => 'Can`t write cropped File',
			);
		} else {

			$resizedImage = imagecreatetruecolor($imgW, $imgH);
			imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
			$rotated_image = imagerotate($resizedImage, -$angle, 0);
			$rotated_width = imagesx($rotated_image);
			$rotated_height = imagesy($rotated_image);
			$dx = $rotated_width - $imgW;
			$dy = $rotated_height - $imgH;
			$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
			imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
			imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
			$final_image = imagecreatetruecolor($cropW, $cropH);
			imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
			imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
			imagejpeg($final_image, $output_filename . $type, $jpeg_quality);
			$response = Array(
				"status" => 'success',
				"url" => site_url('userfiles/storage/medium') . '/' . $newFileName . $type,
			);

			$data['image'] = $newFileName . $type;
			$config['image_library'] = 'gd2';
			$config['source_image'] = './userfiles/storage/medium/' . $data['image'];
			$config['new_image'] = './userfiles/storage/small/';
			$config['maintain_ratio'] = TRUE;
			$config['overwrite'] = false;
			$config['width'] = 150;
			$config['height'] = 150;
			$config['master_dim'] = 'width';
			$config['file_name'] = time();
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			$this->load->library('image_lib', $config); //load library
			$this->image_lib->resize(); //do whatever specified in config

			/*
			$data['image'] = $newFileName . $type;
			$config['image_library'] = 'gd2';
			$config['source_image'] = './userfiles/userimages/temp/' . $data['image'];
			$config['new_image'] = './userfiles/userimages/medium/';
			$config['maintain_ratio'] = TRUE;
			$config['overwrite'] = false;
			$config['width'] = 143;
			$config['height'] = 143;
			$config['master_dim'] = 'width';
			$config['file_name'] = time();
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			$this->load->library('image_lib', $config); //load library
			$this->image_lib->resize(); //do whatever specified in config*/
		}

		print json_encode($response);
	}

	function removeProfileImage() {
		if (file_exists(FCPATH . "userfiles/userimages/small/" . $_POST['imagenm'])) {
			unlink(FCPATH . '/userfiles/userimages/small/' . $_POST['imagenm']);
		}
		if (file_exists(FCPATH . "userfiles/userimages/tiny/" . $_POST['imagenm'])) {
			unlink(FCPATH . '/userfiles/userimages/tiny/' . $_POST['imagenm']);
		}
		if (file_exists(FCPATH . "userfiles/userimages/" . $userimage['image'])) {
			unlink(FCPATH . '/userfiles/userimages/' . $userimage['image']);
		}
		if (file_exists(FCPATH . "userfiles/userimages/users/" . $userimage['image'])) {
			unlink(FCPATH . '/userfiles/userimages/users/' . $userimage['image']);
		}

	}

	function uploadImage()
	{
		if ($_POST['image'] != '')
		{

			$data = array(
				'userimage' => $_POST['image'],
			);

			$this->moveImages();
			$this->removeImage();


			$this->db->where('id', $this->session->userdata('fuserid'));
			$this->db->update('tbl_front_users', $data);
			return 1;
		}
		else
		{
			return 2;
		}
	}

	function moveImages()
	{
		if(file_exists(FCPATH.'userfiles/storage/'.$_POST['image']))
		{
			rename(FCPATH.'userfiles/storage/'.$_POST['image'],FCPATH.'userfiles/userimages/'.$_POST['image']);
		}
		if(file_exists(FCPATH.'userfiles/storage/medium/'.$_POST['image']))
		{
			rename(FCPATH.'userfiles/storage/medium/'.$_POST['image'],FCPATH.'userfiles/userimages/medium/'.$_POST['image']);
		}
		if(file_exists(FCPATH.'userfiles/storage/small/'.$_POST['image']))
		{
			rename(FCPATH.'userfiles/storage/small/'.$_POST['image'],FCPATH.'userfiles/userimages/small/'.$_POST['image']);
		}

	}

	function removeProfileImageFromStorage()
	{
			$image=$_POST['imagenm'];
			if(file_exists(FCPATH.'userfiles/storage/'.$image))
			{
				unlink(FCPATH.'userfiles/storage/'.$image);
			}

			if(file_exists(FCPATH.'userfiles/storage/medium/'.$image))
			{
				unlink(FCPATH.'userfiles/storage/medium/'.$image);
			}


			if(file_exists(FCPATH.'userfiles/storage/small/'.$image))
			{
				unlink(FCPATH.'userfiles/storage/small/'.$image);
			}
	}


	function removeImage()
	{


		$Q=$this->db->query('select userimage from tbl_front_users where id="'.$this->session->userdata('fuserid').'" limit 1');

		$imagedata=$Q->row_array();
		if($imagedata['userimage']!='')
		{
			if(file_exists(FCPATH.'userfiles/userimages/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/'.$imagedata['userimage']);
			}

			if(file_exists(FCPATH.'userfiles/userimages/medium/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/medium/'.$imagedata['userimage']);
			}


			if(file_exists(FCPATH.'userfiles/userimages/small/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/small/'.$imagedata['userimage']);
			}
		}
	}

	function getPic()
	{
		$Q = $this->db->query('select userimage from tbl_front_users where id="' . $this->session->userdata('fuserid') . '"');
		return $Q->row_array();
	}

	function countUserTripsDashboard()
	{
		$data=array();
		$Q=$this->db->query('select inputs from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'"');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $key=>$row)
			{

				$decodejson=json_decode($row['inputs'],TRUE);
				if(isset($decodejson['sstart_date']) && $decodejson['sstart_date']!='')
				{
					$sdate=$decodejson['sstart_date'];
					$days=$decodejson['sdays'];
				}
				else
				{
					$sdate=$decodejson['start_date'];
					$days=$decodejson['days'];
				}
				$data[$key]['startdate']=$startdate=implode("-", array_reverse(explode("/",$sdate)));
				$data[$key]['enddate']=date('Y-m-d',strtotime($startdate . "+$days days"));

			}
		}

		if(count($data))
		{
			return $this->countEachTrips($data);
		}
		else
		{
			$data['completed']=0;
			$data['inprogress']=0;
			$data['upcoming']=0;
		}
		return $data;

	}

	function countEachTrips($data)
	{
		$completed=0;$inprogress=0;$upcoming=0;
		$tripData=array();
		foreach($data as $list)
		{
			if(strtotime($list['startdate'])<strtotime(date('Y-m-d')) && strtotime($list['enddate'])<strtotime(date('Y-m-d')))
			{
				$completed++;
			}
			else if(strtotime($list['startdate'])<=strtotime(date('Y-m-d')) && strtotime($list['enddate'])>=strtotime(date('Y-m-d')))
			{
				$inprogress++;
			}
			else
			{
				$upcoming++;
			}
		}

		$tripData['completed']=$completed;
		$tripData['inprogress']=$inprogress;
		$tripData['upcoming']=$upcoming;

		return $tripData;
	}


	function getRecentTrips()
	{
		$data=array();
		$Q=$this->db->query('select id,trip_type,inputs,country_id,tripname from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'" order by id desc limit 6');
		if($Q->num_rows()>0)
		{
			$data=$Q->result_array();
			return $this->makeRecentTrip($data);
		}
		return $data;
	}

	function makeRecentTrip($data)
	{
		$returndata=array();
		foreach($data as $key=>$list)
		{
			if($list['trip_type']!=2)
			{
				 if($list['trip_type']==1)
                {
                    $url=site_url('userSingleCountryTrip').'/'.string_encode($list['id']);
                }
                else if($list['trip_type']==3)
                {
                    $url=site_url('userSearchedCityTrip').'/'.string_encode($list['id']);
                }
				$tripname_main=$this->Trip_fm->getContinentCountryName($list['country_id']);
			}
			else
			{
				$url=site_url('multicountrytrips').'/'.string_encode($list['id']);
				$tripname_main=$this->Trip_fm->getContinentName($list['tripname']);
			}

			$decodejson=json_decode($list['inputs'],TRUE);
			if($list['trip_type']==3)
			{
				$sdate=$decodejson['sstart_date'];
				$days=$decodejson['sdays']-1;
			}
			else
			{
				$sdate=$decodejson['start_date'];
				$days=$decodejson['days']-1;
			}
			$returndata[$key]['tripname']=$tripname_main['country_name'];
			$returndata[$key]['startdate']=$startdate=implode("-", array_reverse(explode("/",$sdate)));
			$returndata[$key]['enddate']=date('Y-m-d',strtotime($startdate . "+$days days"));
			$returndata[$key]['url']=$url;
		}

		return $returndata;
	}

	function getCalendarTrips()
	{
		if(!is_dir(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid')))
		{
			 writeTripsInFile();
		}
		else if(!file_exists(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid').'/trips'))
		{
			writeTripsInFile();
		}
		$filedata=file_get_contents(FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid').'/trips');
		return $filedata;


	}

}
?>
