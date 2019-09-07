<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}


	function deviceLogin($user_id,$device_id)
	{
		$CI = &get_instance();
	    $data=array(
				'last_login_id' => $user_id,
				'last_access'	=> date('Y-m-d H:i:s')
					);
		$CI->db->where('id ="'.$device_id.'"');
		$CI->db->update('tbl_device_master',$data);
	}

	function selectcolbycondition($selectcol,$table,$condition)
	{
		$CI = &get_instance();

		$CI->db->select($selectcol);
		$CI->db->from($table);
		if(isset($condition) && !empty($condition))
		{
			$CI->db->where($condition);
		}
	    $query = $CI->db->get();
	    if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function getrowbycondition($selectcol,$table,$condition)
	{
		$CI = &get_instance();
		
		$CI->db->select($selectcol);
		$CI->db->from($table);
		if(isset($condition) && !empty($condition))
		{
			$CI->db->where($condition);
		}
	    $query = $CI->db->get();
	    if ( $query->num_rows() > 0 )
		{
			return $query->row_array();
		}
		return false;
	}

	function getLatandLongOfCity($city_id)
	{
		$CI = &get_instance();
		$Q=$CI->db->query('select id,latitude as citylatitude,longitude as citylongitude,country_id,cityimage,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countrybanner from tbl_country_master where id=tbl_city_master.country_id) as countrybanner,city_name,travelguide from tbl_city_master where id="'.$city_id.'"');
		return $Q->row_array();
	}

	function getCountryNameFromSlug($slug)
	{
		$CI = &get_instance();
		$data=array();
		$Q=$CI->db->query('select id,country_name,country_conclusion,countryimage,slug from tbl_country_master where slug="'.$slug.'"');
		$data=$Q->row_array();
		return $data;
	}

	function getcountrynoofCities($countryid)
	{
		$CI = &get_instance();
		$data=array();
		$CI->db->select('id');//country_id,country_id
		$CI->db->from('tbl_city_master');
		$CI->db->where('country_id',$countryid);
		$Q=$CI->db->get();
		return $Q->num_rows();
	}

	function getOtherCitiesOfThisCountry($country_id,$cityArray)
	{
		$CI = &get_instance();
		$data=array();
		$CI->db->select('id,city_name,slug as cityslug,rome2rio_name,latitude,longitude,code,cityimage');//country_id,country_id
		$CI->db->from('tbl_city_master');
		$CI->db->where('total_attraction_time >',0);
		$CI->db->where('country_id',$country_id);
		$CI->db->where_not_in('id',$cityArray);
		$Q=$CI->db->get();
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

	function getShortestDistance($rome2rio_name) {
			$CI = &get_instance();
			$CI->load->helper('randomstring');
			$requests=array();
			$i = 0;
			$len = count($rome2rio_name);

			foreach($rome2rio_name as $key=>$list)
			{
					if($i != $len-1)
					{
						$start_city=$rome2rio_name[$key];
						$end_city=$rome2rio_name[$key+1];
						$requests[$key]='https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
					}
				$i++;
			}
			
		//print_r($requests);die;
			$responses=multiRequest($requests);
		//print_r($responses);die;

			$country_response=array();

			foreach ($responses as $key => $list)
			{
				 $json=json_decode($list,TRUE);
				 //echo "<pre>";print_r($json['routes']);die;
				 if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['totalDuration'] == '')
				 {
					 $country_response[$key]='na';
				 }
				 else
				 {
					 $country_response[$key]=$json['routes'][0]['totalDuration'];
				 }
		 	}
//print_r($country_response);die;

		 $i = 0;
		 $cities="";
		 foreach($rome2rio_name as $key=>$list)
		 {
				 if($i != $len-1)
				 {
					 $response=$country_response[$key];
					 $hours = floor($response / 60);
					 $minutes = $response % 60;
					 $cities[$key]['nextdistance']=formattime($hours, $minutes);
				 }

			 $i++;
		 }
		 //$cities[$len-1]['nextdistance']='';

		 return $cities;
	}

	function getcityinfo($cityid)
	{
		$CI = &get_instance();
		$cityfile= md5($cityid);//$data['citypostid']=
		$data['basic']=$basic=getLatandLongOfCity($cityid);
		//$data['basic']['countryimage']=site_url("userfiles/countries/".$basic['countryimage']);
		//$data['basic']['cityimage']=site_url("userfiles/cities/".$basic['cityimage']);
		//$data['basic']['countrybanner']=site_url("userfiles/countries/banner/".$basic['countrybanner']);
		$data['countryimage']=$data['basic']['countryimage'];
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['cityimage']=$data['basic']['cityimage'];
		$data['basiccityname']=$basic['city_name'];
		$data['countryconclusion']=$basic['country_conclusion'];
		$data['countrybanner']=$basic['countrybanner'];
		//$countrandtype=$returnkey.'-single-'.time();
		//$data['secretkey']=string_encode($countrandtype);
		$filestore=getUserRecommededAttractionsForCountry($cityfile);

				$attraction_decode=json_decode($filestore,TRUE);
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				    $sort['tag_star'][$k] = $v['properties']['tag_star'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
				
			    $data['filestore']=json_encode($attraction_decode);
			    return $data;
	}

	function getUserRecommededAttractionsForCity($cityfile,$tags=array())
	{
		$CI = &get_instance();
			if(isset($tags) && !empty($tags))
			{
				$ids=getIDS($tags);
				return getSelectedAttractions($ids,$cityfile);
			}
			else
			{
				return writeAllUserAttraction($cityfile);
			}
	}

	function getIDS($ids)
	{
		$CI = &get_instance();
		$data=array();
		$CI->db->select('id');
		$CI->db->from('tbl_tag_master');
		for($i=0;$i<count($ids);$i++)
		{
			$CI->db->or_where('tag_name',$ids[$i]);
		}
		$Q=$CI->db->get();
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

	function getSelectedAttractions($ids,$city_id)
	{
		$CI = &get_instance();
		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
		if(!file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id))
		{
			$CI->writeAttractionsInFile($city_id);

		}

		$attraction_json = file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id);
		$attractionarr_decode = json_decode($attraction_json,TRUE);
		$attraction_decode=otherAttractions($ids,$attractionarr_decode,$city_id);

		$attraction_decode=haversineGreatCircleDistance($attraction_decode);

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

		return $attraction_decode;//json_encode($attraction_decode);

	}
	
	function writeAllUserAttraction($city_id)
	{
		$CI = &get_instance();
		$c=0;
		$key2array=array();
		$key2key='';
		//$waypointsstr='';
		if(!file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id))
		{
			$CI->writeAttractionsInFile($city_id);
		}

		$attraction_json = file_get_contents(FCPATH.'userfiles/attractionsfiles_taxidio/'.$city_id);
		$attractionarr_decode = json_decode($attraction_json,TRUE);

		$attraction_decode=mergeOtherAttractions($attractionarr_decode,$city_id);

		$attraction_decode=haversineGreatCircleDistance($attraction_decode);

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
		return $attraction_decode;//json_encode($attraction_decode);
	}

	function writeAttractionsInFile($city_id)
	{
		$CI = &get_instance();
		$data=array();

		$Q1=$CI->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q1->result_array();


		$Q=$CI->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,(select longitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylongitude,(select latitude from tbl_city_master where id=tbl_city_paidattractions.city_id) as citylatitude from tbl_city_paidattractions where md5(city_id)="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
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
		require_once(FCPATH.'travel/tsp.php');
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

	function mergeOtherAttractions($attraction_decode,$city_id)
	{
		$CI = &get_instance();

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


	// common func
	function getalluserdatabyid($id)
	{
		$column="`id`, `name`, `designation`, `image`, `company_name`, `address`, 
		`website`, `logo`, `token`, `device_id`, `version`, `device_type`";
		$this->db->select($column);
		$this->db->from("tbl_user_mst");
		$this->db->where("id='".$id."'");
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$result['data']=$query->row_array();
			
			if(isset($result['data']['image']) && $result['data']['image']!="")
			{
				$result['data']['image']=site_url("uploads/users/".$result['data']['image']);
			}
			else
			{
				$result['data']['image']="";
			}
			
			if(isset($result['data']['logo']) && $result['data']['logo']!="")
			{
				$result['data']['logo']=site_url("uploads/logo/".$result['data']['logo']);
			}
			else
			{
				$result['data']['logo']="";
			}
			//for contact no details
			$this->db->select("`type`,`contact_no`, `is_primary`");
			$this->db->from("tbl_contact_nos");
			$this->db->where("user_id='".$id."'");
			$query2=$this->db->get();
			$result['data']['contactno_details']=$query2->result_array();
			
			//for email details
			$this->db->select("`email`");
			$this->db->from("tbl_emails");
			$this->db->where("user_id='".$id."'");
			$query3=$this->db->get();
			$email=$query3->row_array();
			
			$result['data']['email']="";
			if(isset($email['email']))
			{
				$result['data']['email']=$email['email'];
			}
			
			//for social media link details
			$this->db->select("`link`");
			$this->db->from("tbl_social_media_link");
			$this->db->where("user_id='".$id."'");
			$query3=$this->db->get();
			$social_media_link=$query3->row_array();
			
			$result['data']['social_media_link']="";
			if(isset($social_media_link['link']))
			{
				$result['data']['social_media_link']=$social_media_link['link'];
			}
			
			 return $result['data'];					 
		}
		return false;
	}
	
	function getdatabyuser($user_id)
	{
		$column="u.id,u.name,e.email,c.contact_no,u.company_name,u.designation,u.address,u.image,u.token,u.device_id,u.version,u.device_type";
		$this->db->select($column);
		$this->db->from("tbl_user_mst as u");
		$this->db->where("u.id='".$user_id."'");
		$this->db->join('tbl_emails as e', 'e.user_id = u.id','left');
		$this->db->join('tbl_contact_nos as c', 'c.user_id = u.id','left');
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$data=$query->row_array();
			if(isset($data['image']) && $data['image']!="")
			{
				$data['image']=site_url("uploads/users/".$data['image']);
			}
			else
			{
				$data['image']="";
			}
			return $data;
		}
		return false;
	
	}
	
	function getallcontactbyuser($user_id)
	{
		$this->db->select("`id`, `name`, `designation`,`image`");
		$this->db->from("tbl_contacts");
		$this->db->where("user_id='".$user_id."'");
		if(isset($_POST['tag_id']) && $_POST['tag_id']!="")
		{
			$this->db->where("user_id='".$user_id."' and (tag_ids like '%".$_POST['tag_id']."%' || tag_ids like '%,".$_POST['tag_id']."%' || tag_ids like '%".$_POST['tag_id'].",%')");
		}
		$this->db->order_by("name", "asc");
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function getcontactbyid($user_id)
	{
		$column="`id`, `tag_ids`, `name`, `designation`, `company_name`, `address`, `image`,`notes`";
		$this->db->select($column);
		$this->db->from("tbl_contacts");
		$this->db->where("id='".$_POST['contact_id']."' and user_id='".$user_id."'");
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$result['data']=$query->row_array();
			foreach($result as $key=>$r)
			 {
			  $result[$key]['image']=site_url("uploads/contacts/".$r['image']);
			 }
			 
			//for tag details
			if ($result['data']['tag_ids'])
			{
				$this->db->select("`tag`");
				$this->db->from("tbl_tags");
				$this->db->where("id in (".$result['data']['tag_ids'].")");
				$query1=$this->db->get();
				$result['data']['tag_details']=$query1->result_array();
			}
			else
			{
				$result['data']['tag_details']=[];
			}
			
			//for contact no details
			$this->db->select("`type`,`contact_no`, `is_primary`");
			$this->db->from("tbl_contact_nos");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query2=$this->db->get();
			$result['data']['contactno_details']=$query2->result_array();
			
			//for email details
			$this->db->select("`email`, `is_primary`");
			$this->db->from("tbl_emails");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query3=$this->db->get();
			$result['data']['email_details']=$query3->result_array();
			
			//for social media link details
			$this->db->select("`social_media`, `link`");
			$this->db->from("tbl_social_media_link");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query3=$this->db->get();
			$result['data']['social_media_link_details']=$query3->result_array();
			 return $result;					 
		}
		return false;
	}
	
	function getdatabytoken($token)
	{
		$this->db->select("id");
		$this->db->from("tbl_user_mst");
		$this->db->where("token='".$token."'");
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$row=$query->row_array();
			return $this->getdatabyuser($row["id"]);
		}
		return false;
	}
	
	function selectcolbytable($column,$table)
	{
		$this->db->select($column);
		$query=$this->db->get($table);
		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function deletedata($condition,$table)
	{
		 $this->db->where($condition);
		  
		if(!$this->db->delete($table))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function mail_exists($condition,$table)
	{
		$this->db->where($condition);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}
		
	function sendEmail($email,$sub,$msg)
	{
		$this->load->library('email');
	    $this->email->clear();
		$this->email->from('ei.muniruddin.malek@gmail.com', 'Test Mail');
		$this->email->to($email);
		$this->email->subject($sub);
		$this->email->message($msg);
	
	    if ($this->email->send()) {
	        return true;
	    } else {
	        show_error($this->email->print_debugger());
	    }
	}
	
	function forgotPassword()
	{
			$Q=$this->db->query('select user_id from tbl_emails where email="'.$_POST['email'].'" and is_primary=1 and contact_id=0');
			if($Q->num_rows()>0)
			{
				$row=$Q->row_array();
				$user_id=$row['user_id'];
				$newpassword=$this->rand_Pass();
				$data = array(
		            'password' 				=> md5($newpassword),
		            'modified_datetime'		=> date('Y-m-d h:i:s'),
		            'token'					=> md5($user_id."$<!&".time())
		        );
				$this->db->where('id',$user_id);
				$this->db->set($data);
				$this->db->update("tbl_user_mst");
				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='color: #484343;'>
					<tr>
						<td class='mail_title' style='background:#f76237;color:#fff;font-weight:bold;padding:3px;'>
							Your Bussiness Card App Password has been change
						</td>
					</tr>
					<tr>
						<td style='border-width: 1px; border-color: #e5e5e5; border-style: solid;'>
						<table cellpadding='5' cellspacing='0'  width='600px' align='center' style='font-family: Arial; background : url(../image/back_body.jpg);'>
							<tr>
								<td colspan='2'><p style='font-size:14;'>We've received a request to reset the password for this email address.</p></td>
							</tr>
							<tr>
								<td colspan='2'><p style='font-size:14;'>Your New Password: ".$newpassword."</p></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						
					</tr>
				</table>";

				//echo $message;die;

				$config = array(
					'mailtype' => 'html',
					'charset' => 'utf-8',
					'smtp_host'=>'ssl://smtp.googlemail.com',
					'smtp_user'=>'ei.muniruddin.malek@gmail.com',
					'smtp_pass'=>'munir@2017',
					'smtp_port'=>465,
					'crlf'     =>"\r\n",
					'newline'  => "\r\n",
					'wordwrap' => TRUE
				);
				$this->load->library('email');
				$this->email->initialize($config);
				$subject='Password Reset';
				$to=$_POST['email'];
				$from='ei.muniruddin.malek@gmail.com';
				$this->email->from($from);
				$this->email->subject($subject);
				$this->email->to($to);
				$this->email->message($message);
				$this->email->send();
				return true;
			}
			else
			{
				return false;
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

	function updatePassword($id)
	{
		$data = array(
			'password' => md5($_POST['password'])
		);
		$this->db->where('md5(id)', $id);
		$this->db->update('tbl_user_mst', $data);
		$this->db->where('md5(user_id)',$id);
		$this->db->delete('tbl_tokens');
	}

	function changedateformate($date,$format)
	{
		$date = str_replace('/', '-', $date);
		return date($format, strtotime($date));
	}
	
	function value_exists($condition,$table)
	{
		$this->db->where($condition);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        $row=$query->row_array();
			return $row["id"];
	    }
	        return false;
	}



?>