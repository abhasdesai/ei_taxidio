<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Account_fm extends CI_Model
{
	public function addCalendarEvent()
	{
		$data = array(
			'user_id'=>$this->session->userdata('fuserid'),
			'startdate' =>date("Y-m-d",strtotime($this->input->post('startdate'))),
			'enddate' =>date("Y-m-d",strtotime($this->input->post('enddate'))),
			'subject' =>$this->input->post('subject'),
			'description' =>$this->input->post('description'),
		);
		//echo "<pre>";print_r($data);die();
		
		if($this->db->insert('tbl_calendarEvents',$data)===TRUE)
		{
			$insert_id = $this->db->insert_id();
			appendDynamicNoteToFile($insert_id);
			return $insert_id;
		}
	}
	
	public function getNoteDetailsById($noteId)
	{
		$this->db->select('id,subject,startdate,enddate,description');
		$this->db->from('tbl_calendarEvents');
		$this->db->where('id',$noteId);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->row();
		}
	}
	
	public function deleteCalNote($noteId)
	{
		
		return deleteDynamicNoteFromFile($noteId);
	}
	
	

	function getAllUserTags($user_id)
	{
		$Q=$this->db->query('select tag_id from tbl_user_tags where user_id='.$user_id);
		if($Q->num_rows()>0)
		{
			return $Q->result_array();
		}
		return array();
	}

	function getAllsosDetails()
	{
		$Q=$this->db->query('select * from tbl_sos where user_id='.$this->session->userdata('fuserid'));
		if($Q->num_rows()>0)
		{
			return $Q->result_array();
		}
		return array();
	}

	function check_current_password()
	{
		$Q=$this->db->query('select id from tbl_front_users where id="'.$this->session->userdata('fuserid').'" and password="'.$this->hash($_POST['cpassword']).'" limit 1');
		if($Q->num_rows()<1)
		{
			$this->form_validation->set_message('check_current_password','The current password provided is incorrect. Please try again.');
			return FALSE;
		}
		return TRUE;
	}

	public function hash($string)
	{
			return hash('sha512',$string.config_item('encryption_key'));
	}

	function changepassword()
	{
		$this->db->where('id',$this->session->userdata('fuserid'));
		$this->db->update('tbl_front_users',array('password'=>$this->hash($_POST['newpassword'])));

		$message=$this->load->view('myaccount/changepasspassword_template',$data,true);
		$from='noreply@taxidio.com';
		$to=$this->session->userdata('email');
		$subject='Password Changed';
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}

	function deleteItinerary($itid)
	{
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$files = glob(FCPATH.'userfiles/savedfiles/'.$itid.'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }	
			}
			rmdir(FCPATH.'userfiles/savedfiles/'.$itid);
		}
	}

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

	function logout()
	{
		loggedinUser(0);
		$this->session->sess_destroy();
	}

	function checkUseridAndCountryId($country_id,$uniqueid)
	{
		$Q=$this->db->query('select id from tbl_itineraries where country_id="'.$country_id.'" and user_id="'.$this->session->userdata('fuserid').'" and sess_id="'.$this->session->userdata('randomstring').'" and trip_type=1 and uniqueid="'.$uniqueid.'" limit 1');
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
		$this->db->where('tbl_itineraries.sess_id',$this->session->userdata('randomstring'));
		$this->db->where('tbl_itineraries.user_id',$this->session->userdata('fuserid'));
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
			$input=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$cities_encode=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
			$cities=json_decode($cities_encode,TRUE);
			$singlecountry_required_array[$country_id]=$cities[$country_id];

			$tripname='';
			if(count($itineraryid))
			{

				$oldCities=$this->getoldCitiesOfCountry($country_id,$cities[$country_id]);
				$citiorcountries='';
				foreach($cities[$country_id] as $list)
				{
					$tripname .=$list['code'].'-';
					$citiorcountries .=$list['city_name'].'-';
					if(count($oldCities))
					{
						if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id'])))
						{
							$city_attractions=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id']));
						}
						else
						{
							$city_attractions_default=file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.md5($list['id']));
							$city_attractions_decode=json_decode($city_attractions_default,TRUE);

						}



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
								'itinerary_id'=>$itineraryid['id'],
								'city_id'=>$list['id'],
								'city_attractions'=>$city_attractions
							);

							$this->db->insert('tbl_itineraries_cities',$itinerarydata);
						}
					}
				}

				$data=array(
						'user_id'=>$this->session->userdata('fuserid'),
						'sess_id'=>$this->session->userdata('randomstring'),
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
				$tripname_main=$this->Trip_fm->getContinentCountryName($country_id);

				$data=array(
						'user_id'=>$this->session->userdata('fuserid'),
						'sess_id'=>$this->session->userdata('randomstring'),
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

				$this->db->insert('tbl_itineraries',$data);
				$lastid=$this->db->insert_id();
				$citiorcountries ='';
				foreach($cities[$country_id] as $list)
				{
					$tripname .=$list['code'].'-';
					$citiorcountries .=$list['city_name'].'-';
					if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id'])))
					{
						$city_attractions=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.md5($list['id']));
					}
					else
					{
						$getInputs=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
						$inputdecode=json_decode($getInputs,TRUE);
						$city_attractions_decoded=array();
						if(isset($inputdecode['searchtags']) && $inputdecode['searchtags']>0)
						{
							$ids=$this->getIDS($_GET['searchtags']);
							$city_attractions_decoded=$this->getUserSelectedCityAttractions($ids,md5($list['id']),$token);

						}
						else
						{
							$city_attractions_decoded=$this->writeAllUserAttraction(md5($list['id']),$uniqueid);
						}
						$city_attractions=json_encode($city_attractions_decoded);

					}


					$itinerarydata=array(
								'itinerary_id'=>$lastid,
								'city_id'=>$list['id'],
								'city_attractions'=>$city_attractions
							);

					$this->db->insert('tbl_itineraries_cities',$itinerarydata);
				}

				//echo $citiorcountries;die;

				$slug=$this->generateItiSlug('Trip '.$tripname_main['country_name']);
				$this->db->where('id',$lastid);
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



	function saveMultiIitnerary($uniqueid,$secretkeyid)
	{
		$foldername=string_decode($secretkeyid);
		$itineraryid=$this->checkUseridAndCountryIdForMultiCountry($secretkeyid,$uniqueid);

		$input=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
		$combinations=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/combinations');


		//$c=json_decode($combinations,TRUE);
		//echo "<pre>";print_r($c);die;
		$cities=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities');
		$cities_decode=json_decode($cities,TRUE);
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
				$this->deleteOldCities($mainitineraryid,$newCityArray);

				for($i=0;$i<count($countries);$i++)
				{
					//echo "<pre>";
					//print_r($cities_decode);
					//print_r($countries);die;
					$co_id=$countries[$i]['country_id'];
					//echo $co_id;die;
					//echo "<pre>";print_r($cities_decode[$co_id]);die;
					$tripname .=$cities_decode[$co_id][0]['rome2rio_code'].'-';
					$citiorcountries .=$cities_decode[$co_id][0]['country_name'].'-';
					foreach($cities_decode[$countries[$i]['country_id']] as $list)
					{

						if(in_array($list['id'], $currentcities))
						{
							$attractions=$this->getAttractionsOfCities($list['id'],$uniqueid,$foldername);
							$city_data=array(
									'attractions'=>$attractions
								);

							$this->db->where('country_combination_id',$countries[$i]['id']);
							$this->db->update('tbl_itineraries_multicountries_cities',$city_data);

						}
						else
						{
							$attractions=$this->getAttractionsOfCities($list['id'],$uniqueid,$foldername);
							$city_data=array(
									'city_id'=>$list['id'],
									'country_combination_id'=>$countries[$i]['id'],
									'attractions'=>$attractions
								);

							$this->db->insert('tbl_itineraries_multicountries_cities',$city_data);

						}

					}
				}

				$data=array(
					'inputs'=>$input,
					'modified'=>date('Y-m-d H:i:s'),
					'multicountries'=>$combinations,
					'cities'=>$cities,
					'tripname'=>substr($tripname,0,-1),
					'citiorcountries'=>substr($citiorcountries,0,-1)
				);
				$this->db->where('id',$mainitineraryid);
				$this->db->update('tbl_itineraries',$data);

				return $mainitineraryid;
				//echo "1";die;
		}
		else
		{
				$tripname='';
				$countries=explode('-',string_decode($secretkeyid));


				$data=array(
					'user_id'=>$this->session->userdata('fuserid'),
					'sess_id'=>$this->session->userdata('randomstring'),
					'trip_type'=>2,
					'trip_mode'=>1,
					'inputs'=>$input,
					'singlecountry'=>'',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'tripname'=>'',
					'country_id'=>0,
					'multicountries'=>$combinations,
					'cities'=>$cities,
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

						$attractions=$this->getAttractionsOfCities($list['id'],$uniqueid,$foldername);
						$city_data=array(
								'city_id'=>$list['id'],
								'country_combination_id'=>$lastCountryId,
								'attractions'=>$attractions
							);

						$this->db->insert('tbl_itineraries_multicountries_cities',$city_data);
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
		// $this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries_multicountries.combination_id');
		// $this->db->where('itineraries_id',$itineraries_id);
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
		$result=get_invited_trip_details($iti);
		$data=array();
		$this->db->select('tbl_itineraries_cities.id,tbl_itineraries_cities.city_id');
		$this->db->from('tbl_itineraries_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_cities.itinerary_id');
		if($result!==FALSE){
			$this->db->where('user_id', $result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
		$this->db->where('tbl_itineraries.id',$iti);
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
        $result=get_invited_trip_details($iti);
        $this->db->select('id,country_id');
        $this->db->from('tbl_itineraries');
        if($result!==FALSE){
            $this->db->where('user_id',$result['user_id']);
        }
        else
        {
            $this->db->where('user_id',$this->session->userdata('fuserid'));
        }
        $this->db->where('id',$iti);
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
						'citiorcountries'=>substr($citiorcountries,0,-1),
						'last_modified_by'=>$this->session->userdata('fuserid')
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
				'citiorcountries'=>substr($citiorcountries,0,-1),
				'last_modified_by'=>$this->session->userdata('fuserid')
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
		$Q=$this->db->query('select id,name,code from tbl_worlds_countries order by name asc');
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
		$user_id=$this->session->userdata('fuserid');
		$this->db->where('id',$user_id);
		$this->db->update('tbl_front_users',$data);

		$this->db->where('user_id', $user_id);
		$this->db->delete('tbl_user_tags');

		$this->db->where('user_id', $user_id);
		$this->db->delete('tbl_sos');

		for($i=0;$i<count($_POST['tag_id']);$i++)
		{
			$tagdata=array(
					'user_id'=>$user_id,
					'tag_id'=>$_POST['tag_id'][$i]
				);
			$this->db->insert('tbl_user_tags',$tagdata);
		}

		if(isset($_POST['sos_name']) && $_POST['sos_name']!='')
		{
			$sosdata=array(
					'name'=>$_POST['sos_name'],
					'relation'=>$_POST['sos_relation'],
					'country_code'=>$_POST['sos_country_code'],
					'phone'=>$_POST['sos_phone'],
					'email'=>$_POST['sos_email'],
					'user_id'=>$user_id
				);
			$this->db->insert('tbl_sos',$sosdata);
		}
		if(isset($_POST['sos_name2']) && $_POST['sos_name2']!='')
		{
			$sosdata=array(
					'name'=>$_POST['sos_name2'],
					'relation'=>$_POST['sos_relation2'],
					'country_code'=>$_POST['sos_country_code2'],
					'phone'=>$_POST['sos_phone2'],
					'email'=>$_POST['sos_email2'],
					'user_id'=>$user_id
				);
			$this->db->insert('tbl_sos',$sosdata);
		}

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

	function countTrips($type)
	{
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		if ($type==1) {
			$this->db->where('start_date <=',time());
			$this->db->where('end_date >=',time());
        }else if($type==2){
			$this->db->where('start_date >',time());
        }else if($type==3){
			$this->db->where('end_date <',time());
		}
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function getUserTrips($type,$limit,$start)
	{
		$data=array();
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		if ($type==1) {
			$this->db->where('start_date <=',time());
			$this->db->where('end_date >=',time());
        }else if($type==2){
			$this->db->where('start_date >',time());
        }else if($type==3){
			$this->db->where('end_date <',time());
		}
		$this->db->limit($limit,$start);
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $i=>$row)
			{
				$data[$i]=$row;
				$data[$i]['is_co_traveller']=is_any_co_traveller($row['id']);
			}
		}
		return $data;
	}

	function update_startdate_N_days()
	{
		$this->db->select('id,inputs,start_date,end_date,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				if(empty($row['start_date']) || empty($row['end_date']))
				{
	                $json=json_decode($row['inputs'],TRUE);
	                if($row['trip_type']==1 || $row['trip_type']==2)
	                {
	                    $startdate=$json['start_date'];
	                    $ttldays=$json['days']-1;
	                }
	                else if($row['trip_type']==3)
	                {
	                    $startdate=$json['sstart_date'];
	                    $ttldays=$json['sdays']-1;
	                }
	                 $startdateformat=explode('/',$startdate);
	                 $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];
					$data = array(
					'start_date' => strtotime($startdateymd),
					'end_date'=>strtotime($startdateymd. " + $ttldays days")
					);
					$this->db->where('id',$row['id']);
					$this->db->update('tbl_itineraries', $data);
				}
			}
		}
	}

	// function countTrips($type)
	// {
	// 	$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
	// 	$this->db->from('tbl_itineraries');
	// 	$this->db->where('user_id',$this->session->userdata('fuserid'));
	// 	$this->db->order_by('id','DESC');
	// 	$Q=$this->db->get();
	// 	$data=[];
	// 	if($Q->num_rows()>0)
	// 	{//echo "<pre>"; print_r($Q->result_array());die;
	// 		foreach($Q->result_array() as $row)
	// 		{
 //                $json=json_decode($row['inputs'],TRUE);
 //                if($row['trip_type']==1 || $row['trip_type']==2)
 //                {
 //                    $startdate=$json['start_date'];
 //                    $ttldays=$json['days']-1;
 //                }
 //                else if($row['trip_type']==3)
 //                {
 //                    $startdate=$json['sstart_date'];
 //                    $ttldays=$json['sdays']-1;
 //                }
 //                //echo $startdate;die;
 //                 $startdateformat=explode('/',$startdate);
 //                 $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];

 //                 $st = strtotime($startdateymd);
 //                 $en = strtotime($startdateymd. " + $ttldays days");
 //                if ($type==1 && $st <= time() && time() <= $en) {
	// 				$data[]=$row;
 //                }else if($type==2 && $st > time()){
	// 				$data[]=$row;
 //                }else if($type==3 && $en < time()){
	// 				$data[]=$row;
	// 			}
	// 		}
	// 	}
	// 	return count($data);
	// }

	// function getUserTrips($type,$limit,$start)
	// {
	// 	$data=array();
	// 	$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
	// 	$this->db->from('tbl_itineraries');
	// 	$this->db->where('user_id',$this->session->userdata('fuserid'));
	// 	$this->db->limit($limit,$start);
	// 	$this->db->order_by('id','DESC');
	// 	$Q=$this->db->get();
	// 	if($Q->num_rows()>0)
	// 	{//echo "<pre>"; print_r($Q->result_array());die;
	// 		foreach($Q->result_array() as $row)
	// 		{
 //                $json=json_decode($row['inputs'],TRUE);
 //                if($row['trip_type']==1 || $row['trip_type']==2)
 //                {
 //                    $startdate=$json['start_date'];
 //                    $ttldays=$json['days']-1;
 //                }
 //                else if($row['trip_type']==3)
 //                {
 //                    $startdate=$json['sstart_date'];
 //                    $ttldays=$json['sdays']-1;
 //                }
 //                //echo $startdate;die;
 //                 $startdateformat=explode('/',$startdate);
 //                 $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];

 //                 $st = strtotime($startdateymd);
 //                 $en = strtotime($startdateymd. " + $ttldays days");
 //                if ($type==1 && $st <= time() && time() <= $en) {
	// 				$data[]=$row;
 //                }else if($type==2 && $st > time()){
	// 				$data[]=$row;
 //                }else if($type==3 && $en < time()){
	// 				$data[]=$row;
	// 			}
	// 		}
	// 	}
	// 	return $data;
	// }


	function resetTrip($id)
	{
		$result=get_invited_trip_details(string_decode($id));
		$data=$this->getTripDetails($id,$result);
		$this->makeSession($data['sess_id'],$data['singlecountry'],$data['inputs'],$data['id'],$data['trip_type'],$cities='',$combinations='');
		if($data['trip_type']==1)
		{
			$slug=$this->getCountrySlugAndId($id,$result);
		}
		else if($data['trip_type']==2)
		{
			$slug=$this->getCountryKeyAndId($id,$result);
		}
		return $slug;
	}

	function getTripDetails($id,$result=FALSE)
	{
		$data=array();
		$plainid=string_decode($id);
	    $this->db->select('*');
	    $this->db->from('tbl_itineraries');
		if($result!==FALSE){
			$this->db->where('user_id',$result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
	    $this->db->where('id',$plainid);
	    $Q=$this->db->get();
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

	function getCountrySlugAndId($id,$result=FALSE)
	{
		$data=array();
		$this->db->select('tbl_country_master.slug,tbl_itineraries.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_country_master','tbl_itineraries.country_id=tbl_country_master.id');
		$this->db->where('tbl_itineraries.id',string_decode($id));
		if($result!==FALSE){
			$this->db->where('user_id',$result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
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

	function getCountryKeyAndId($id,$result=FALSE)
	{
		$data=array();
		$this->db->select('tbl_itineraries_multicountrykeys.combination_key,tbl_itineraries.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries.id');
		$this->db->where('tbl_itineraries.id',string_decode($id));
		if($result!==FALSE){
			$this->db->where('user_id',$result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
		$Q=$this->db->get();
		//echo $this->db->last_query();die;
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
		}
		else
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect('trips');
		}
		//echo "<pre>";print_r($data);die;
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
		$result=get_invited_trip_details(string_decode($id));
		$data=$this->getTripDetails($id,$result);
		$this->makeSession($data['sess_id'],$data['singlecountry'],$data['inputs'],$data['id'],$data['trip_type'],$data['cities'],$data['multicountries']);
		$slug=$this->getCountryKeyAndId($id,$result);
		//echo "slug<pre>";print_r($slug);die;
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
        $result=get_invited_trip_details($iti);
        $this->db->select('id');
        $this->db->from('tbl_itineraries');
        if($result!==FALSE){
            $this->db->where('user_id',$result['user_id']);
        }
        else
        {
            $this->db->where('user_id',$this->session->userdata('fuserid'));
        }
        $this->db->where('id',$iti);
        $Q=$this->db->get();
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
     //echo '<pre>'; print_r($newCityArray);die;
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
			'tripname'=>substr($tripname,0,-1),
			'last_modified_by'=>$this->session->userdata('fuserid')
		);
		$this->db->where('id',$iti);
		$this->db->update('tbl_itineraries',$data);

	}


/* Searched City Itineraries */



	function getoldSearchedCitiesOfCountryIti($country_id,$cities,$iti)
	{
		$result=get_invited_trip_details($iti);
		$data=array();
		$this->db->select('tbl_itineraries_searched_cities.id,tbl_itineraries_searched_cities.city_id');
		$this->db->from('tbl_itineraries_searched_cities');
		$this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itineraries_searched_cities.itinerary_id');
		if($result!==FALSE){
			$this->db->where('user_id', $result['user_id']);
		}
		else
		{
			$this->db->where('user_id',$this->session->userdata('fuserid'));
		}
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
		$this->db->select('tbl_itineraries_searched_cities.id,tbl_itineraries_searched_cities.city_id');
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
		$result=get_invited_trip_details(string_decode($id));
		$data=$this->getTripDetails($id,$result);
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
		if(isset($inputdecode['searchtags']) && !empty($inputdecode['searchtags']))
		{
			$ids=$this->getIDS($inputdecode['searchtags']);
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

		//echo "<pre>";print_r($attraction_decode);die;
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
		
		$stadium_decode=array();
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

	function checkCityExist($cityid,$iti)
	{

		$getInputs=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['searchtags']) && !empty($inputdecode['searchtags']))
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
		//echo $filedata;die;
		//echo FCPATH.'userfiles/myaccount/'.$this->session->userdata('fuserid').'/trips';die;
		return $filedata;


	}

	function new_invited_trips($type=1)
	{
		$Q=$this->db->query('select id from tbl_share_itineraries where invited_user_id="'.$this->session->userdata('fuserid').'" and is_viewed=0');
		if($type===1)
		{
			return $Q->num_rows();
		}
		else
		{
			if($Q->num_rows()>0)
			{
				return $Q->result_array();
			}
		}
	}

	function notification_viewed()
	{
		$ids=$this->new_invited_trips(2);
		if($ids)
		{
			foreach ($ids as $value) {
				$this->db->where('id',$value['id']);
				$this->db->update('tbl_share_itineraries',array('is_viewed'=>1));
			}
			echo "1";
		}
	}

	function countrySelector(){
	    $countryArray = array(
					'AD'=>array('name'=>'ANDORRA','code'=>'376'),
					'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
					'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
					'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
					'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
					'AL'=>array('name'=>'ALBANIA','code'=>'355'),
					'AM'=>array('name'=>'ARMENIA','code'=>'374'),
					'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
					'AO'=>array('name'=>'ANGOLA','code'=>'244'),
					'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
					'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
					'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
					'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
					'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
					'AW'=>array('name'=>'ARUBA','code'=>'297'),
					'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
					'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
					'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
					'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
					'BE'=>array('name'=>'BELGIUM','code'=>'32'),
					'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
					'BG'=>array('name'=>'BULGARIA','code'=>'359'),
					'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
					'BI'=>array('name'=>'BURUNDI','code'=>'257'),
					'BJ'=>array('name'=>'BENIN','code'=>'229'),
					'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
					'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
					'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
					'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
					'BR'=>array('name'=>'BRAZIL','code'=>'55'),
					'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
					'BT'=>array('name'=>'BHUTAN','code'=>'975'),
					'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
					'BY'=>array('name'=>'BELARUS','code'=>'375'),
					'BZ'=>array('name'=>'BELIZE','code'=>'501'),
					'CA'=>array('name'=>'CANADA','code'=>'1'),
					'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
					'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
					'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
					'CG'=>array('name'=>'CONGO','code'=>'242'),
					'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
					'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
					'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
					'CL'=>array('name'=>'CHILE','code'=>'56'),
					'CM'=>array('name'=>'CAMEROON','code'=>'237'),
					'CN'=>array('name'=>'CHINA','code'=>'86'),
					'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
					'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
					'CU'=>array('name'=>'CUBA','code'=>'53'),
					'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
					'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
					'CY'=>array('name'=>'CYPRUS','code'=>'357'),
					'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
					'DE'=>array('name'=>'GERMANY','code'=>'49'),
					'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
					'DK'=>array('name'=>'DENMARK','code'=>'45'),
					'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
					'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
					'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
					'EC'=>array('name'=>'ECUADOR','code'=>'593'),
					'EE'=>array('name'=>'ESTONIA','code'=>'372'),
					'EG'=>array('name'=>'EGYPT','code'=>'20'),
					'ER'=>array('name'=>'ERITREA','code'=>'291'),
					'ES'=>array('name'=>'SPAIN','code'=>'34'),
					'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
					'FI'=>array('name'=>'FINLAND','code'=>'358'),
					'FJ'=>array('name'=>'FIJI','code'=>'679'),
					'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
					'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
					'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
					'FR'=>array('name'=>'FRANCE','code'=>'33'),
					'GA'=>array('name'=>'GABON','code'=>'241'),
					'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
					'GD'=>array('name'=>'GRENADA','code'=>'1473'),
					'GE'=>array('name'=>'GEORGIA','code'=>'995'),
					'GH'=>array('name'=>'GHANA','code'=>'233'),
					'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
					'GL'=>array('name'=>'GREENLAND','code'=>'299'),
					'GM'=>array('name'=>'GAMBIA','code'=>'220'),
					'GN'=>array('name'=>'GUINEA','code'=>'224'),
					'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
					'GR'=>array('name'=>'GREECE','code'=>'30'),
					'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
					'GU'=>array('name'=>'GUAM','code'=>'1671'),
					'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
					'GY'=>array('name'=>'GUYANA','code'=>'592'),
					'HK'=>array('name'=>'HONG KONG','code'=>'852'),
					'HN'=>array('name'=>'HONDURAS','code'=>'504'),
					'HR'=>array('name'=>'CROATIA','code'=>'385'),
					'HT'=>array('name'=>'HAITI','code'=>'509'),
					'HU'=>array('name'=>'HUNGARY','code'=>'36'),
					'ID'=>array('name'=>'INDONESIA','code'=>'62'),
					'IE'=>array('name'=>'IRELAND','code'=>'353'),
					'IL'=>array('name'=>'ISRAEL','code'=>'972'),
					'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
					'IN'=>array('name'=>'INDIA','code'=>'91'),
					'IQ'=>array('name'=>'IRAQ','code'=>'964'),
					'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
					'IS'=>array('name'=>'ICELAND','code'=>'354'),
					'IT'=>array('name'=>'ITALY','code'=>'39'),
					'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
					'JO'=>array('name'=>'JORDAN','code'=>'962'),
					'JP'=>array('name'=>'JAPAN','code'=>'81'),
					'KE'=>array('name'=>'KENYA','code'=>'254'),
					'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
					'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
					'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
					'KM'=>array('name'=>'COMOROS','code'=>'269'),
					'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
					'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
					'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
					'KW'=>array('name'=>'KUWAIT','code'=>'965'),
					'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
					'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
					'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
					'LB'=>array('name'=>'LEBANON','code'=>'961'),
					'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
					'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
					'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
					'LR'=>array('name'=>'LIBERIA','code'=>'231'),
					'LS'=>array('name'=>'LESOTHO','code'=>'266'),
					'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
					'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
					'LV'=>array('name'=>'LATVIA','code'=>'371'),
					'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
					'MA'=>array('name'=>'MOROCCO','code'=>'212'),
					'MC'=>array('name'=>'MONACO','code'=>'377'),
					'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
					'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
					'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
					'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
					'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
					'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
					'ML'=>array('name'=>'MALI','code'=>'223'),
					'MM'=>array('name'=>'MYANMAR','code'=>'95'),
					'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
					'MO'=>array('name'=>'MACAU','code'=>'853'),
					'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
					'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
					'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
					'MT'=>array('name'=>'MALTA','code'=>'356'),
					'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
					'MV'=>array('name'=>'MALDIVES','code'=>'960'),
					'MW'=>array('name'=>'MALAWI','code'=>'265'),
					'MX'=>array('name'=>'MEXICO','code'=>'52'),
					'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
					'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
					'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
					'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
					'NE'=>array('name'=>'NIGER','code'=>'227'),
					'NG'=>array('name'=>'NIGERIA','code'=>'234'),
					'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
					'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
					'NO'=>array('name'=>'NORWAY','code'=>'47'),
					'NP'=>array('name'=>'NEPAL','code'=>'977'),
					'NR'=>array('name'=>'NAURU','code'=>'674'),
					'NU'=>array('name'=>'NIUE','code'=>'683'),
					'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
					'OM'=>array('name'=>'OMAN','code'=>'968'),
					'PA'=>array('name'=>'PANAMA','code'=>'507'),
					'PE'=>array('name'=>'PERU','code'=>'51'),
					'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
					'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
					'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
					'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
					'PL'=>array('name'=>'POLAND','code'=>'48'),
					'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
					'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
					'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
					'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
					'PW'=>array('name'=>'PALAU','code'=>'680'),
					'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
					'QA'=>array('name'=>'QATAR','code'=>'974'),
					'RO'=>array('name'=>'ROMANIA','code'=>'40'),
					'RS'=>array('name'=>'SERBIA','code'=>'381'),
					'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
					'RW'=>array('name'=>'RWANDA','code'=>'250'),
					'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
					'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
					'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
					'SD'=>array('name'=>'SUDAN','code'=>'249'),
					'SE'=>array('name'=>'SWEDEN','code'=>'46'),
					'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
					'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
					'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
					'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
					'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
					'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
					'SN'=>array('name'=>'SENEGAL','code'=>'221'),
					'SO'=>array('name'=>'SOMALIA','code'=>'252'),
					'SR'=>array('name'=>'SURINAME','code'=>'597'),
					'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
					'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
					'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
					'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
					'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
					'TD'=>array('name'=>'CHAD','code'=>'235'),
					'TG'=>array('name'=>'TOGO','code'=>'228'),
					'TH'=>array('name'=>'THAILAND','code'=>'66'),
					'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
					'TK'=>array('name'=>'TOKELAU','code'=>'690'),
					'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
					'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
					'TN'=>array('name'=>'TUNISIA','code'=>'216'),
					'TO'=>array('name'=>'TONGA','code'=>'676'),
					'TR'=>array('name'=>'TURKEY','code'=>'90'),
					'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
					'TV'=>array('name'=>'TUVALU','code'=>'688'),
					'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
					'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
					'UA'=>array('name'=>'UKRAINE','code'=>'380'),
					'UG'=>array('name'=>'UGANDA','code'=>'256'),
					'US'=>array('name'=>'UNITED STATES','code'=>'1'),
					'UY'=>array('name'=>'URUGUAY','code'=>'598'),
					'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
					'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
					'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
					'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
					'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
					'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
					'VN'=>array('name'=>'VIET NAM','code'=>'84'),
					'VU'=>array('name'=>'VANUATU','code'=>'678'),
					'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
					'WS'=>array('name'=>'SAMOA','code'=>'685'),
					'XK'=>array('name'=>'KOSOVO','code'=>'381'),
					'YE'=>array('name'=>'YEMEN','code'=>'967'),
					'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
					'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
					'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
					'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
				);
	    
		
		return $countryArray; // or echo $output; to print directly
	}
	
	function getAllTripsofTime($user_id='')
	{
		$now = date('Y-m-d');
		$newDate = date('Y-m-d', strtotime("+1 days"));
		$newDate = strtotime(date('Y-m-d', strtotime("+1 days")));

		
		$data=array();
		/*$sql = "select *,JSON_UNQUOTE(json_extract(inputs, '$.days')) as days,STR_TO_DATE(JSON_UNQUOTE( JSON_EXTRACT(it.inputs, '$.start_date')),'%d/%m/%Y') as from_date from tbl_itineraries as it INNER JOIN tbl_front_users  user ON user.id = it.user_id where STR_TO_DATE(JSON_UNQUOTE( JSON_EXTRACT(it.inputs, '$.start_date')),'%d/%m/%Y') = '$newDate'";*/
		$sql = "select *,from_unixtime(it.start_date,'%Y-%m-%d') as from_date,from_unixtime(it.end_date,'%Y-%m-%d') as end_date from tbl_itineraries as it INNER JOIN tbl_front_users user ON user.id = it.user_id where it.start_date = '$newDate'";



		/*$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		if ($type==1) {
			$this->db->where('start_date <=',time());
			$this->db->where('end_date >=',time());
        }else if($type==2){
			$this->db->where('start_date >',time());
        }else if($type==3){
			$this->db->where('end_date <',time());
		}
		$this->db->limit($limit,$start);
		$this->db->order_by('id','DESC');*/



/*
			select *,from_unixtime(it.start_date,"%Y-%m-%d") as started from tbl_itineraries as it INNER JOIN tbl_front_users user ON user.id = it.user_id where from_unixtime(it.start_date,"%Y-%m-%d") = '2018-08-30' */
		
		if(!empty($user_id))
		{
		  $sql .=" and user.id=".$user_id;
		}
		$Q = $this->db->query($sql);  
		//echo $sql;die;
		if($Q->num_rows()>0)
		{
			$data=$Q->result_array();
			
			//return $this->makeRecentTrip($data);
		}
		return $data;
	}
	function sendUpcomingTripEmails($admin_email = '',$settings=array(),$details = array())
	{
		
		$getadminemail = $admin_email;
		$adminemail = $getadminemail['email'];/*
		echo "***************";
		echo $adminemail;
		echo '<pre>';print_r($settings);
		echo "***************";
		echo '<pre>';print_r($details);die;*/
		$data['taxidio'] = $settings;
		
		//$subject = 'We’ve received your data.';
		$subject = '1 day to go for your trip!';
		$from = 'varsha.anabhavane@taxidio.com';
		foreach ($details as $key => $value) 
        {
        	
        	//$to = 'ei.bhrugesh.vadhankar@gmail.com';
        	$data['trip_name'] = "Trip - ".$value['tripname'];
        	$to = $value['email'];
        	$data['name'] = $value['name'];
        	
        	$data['from_date'] =  date('m-d-Y', strtotime($value['from_date']. ' + 1 days'));
        	//$data['days'] = $value['days'];
    		$data['to_date'] =  date('m-d-Y', strtotime($value['end_date']. ' + 1 days'));
    		$message = $this->load->view('emailtemplate_notification',$data,true);
    		$this->email->from($from, 'Taxidio Travel India Pvt. Ltd.');
			$this->email->to($to);
			//echo '<pre>';print_r($message);die;
		//$this->email->reply_to($adminemail);
			$this->email->subject($subject);
			$this->email->message($message);
			
			$this->email->send();
			
        }

		
	}
	function updateCityPdf($pdf_info = array())
	{
		//echo "<pre>";print_r($pdf_info);die;
		$this->db->update_batch('tbl_city_master', $pdf_info,'id');
	}
	function sendCronMail($admin_email = '',$settings=array(),$details = array())
	{
		$this->load->library('email');
        $this->load->library('MY_Email');
		
		/*$getadminemail = $admin_email;
		$adminemail = $getadminemail['email'];*/
		/*
		echo "***************";
		echo $adminemail;
		echo '<pre>';print_r($settings);
		echo "***************";
		echo '<pre>';print_r($details);die;*/
		//$data['taxidio'] = $settings;
		
		
		$subject = 'Cron successfully executed.';
		$from = 'varsha.anabhavane@taxidio.com';
    	$to = 'ei.aneesh.chandran@gmail.com';
    	$data['name'] = 'Aneesh';
		$message = $this->load->view('emailtemplate_cron_pdf',$data,true);

		$this->email->from($from, 'Taxidio travel guide PDF cron.');
		
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();	
	}
	function referFriend($data = array(),$admin_email = '' ,$additional_data = array())
	{
		$this->db->select('*');
		$this->db->from('tbl_refer_friend');
		$this->db->where('refer_email',$data['refer_email']);
		$this->db->where('user_id',$data['user_id']);
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return false;
		}
		else if($this->db->insert('tbl_refer_friend',$data)===TRUE)
		{
			$insert_id = $this->db->insert_id();
			$additional_data['refere_id'] = $data['refer_code'];
			//return $insert_id;
			//$adminemail = $admin_email;
			/*
			echo "***************";
			echo $adminemail;
			echo '<pre>';print_r($settings);
			echo "***************";
			echo '<pre>';print_r($details);die;*/
			//$data['taxidio'] = $settings;
			
			
			//$subject = 'Refer a friend';
			$subject = 'Plan your trip with Taxidio';
			$from = 'varsha.anabhavane@taxidio.com';
	    	$to = 'ei.aneesh.chandran@gmail.com';
	    	//$data['name'] = 'Aneesh';
			$message = $this->load->view('email_template_referfriend',$additional_data,true);
			$this->email->from($from, 'Refer a friend');
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($message);
			$flag = $this->email->send();	
			if($flag)
			{
				return true;
			}
			return false;
		}
	}
}
?>
