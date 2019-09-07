<?php 
	
	function updateCityData($cityid)
	{
		$CI = &get_instance();
		$Q=$CI->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q->result_array();

		
		$Q1=$CI->db->query('select id,latitude,longitude from tbl_city_master where id="'.$cityid.'"'
			);
		$row1=$Q1->row_array();

		updateAttractions($row1['id'],$row1['longitude'],$row1['latitude'],$tags);	
		updateOther($row1['id'],$row1['longitude'],$row1['latitude'],$tags);	

	}


	function updateAttractions($city_id,$longitude,$latitude,$tags)
	{
		$CI = &get_instance();
		$Q=$CI->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,ispaid from tbl_city_paidattractions where city_id="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{  
				$ispaid=0;
				$isplace=1;
				if($row['ispaid']==1)
				{
					$ispaid=1;
				}

				if($row['ispaid']==2)
				{
					$isplace=0;
				}


				$knwofortag=array();
				$knwofortag=explode(',',$row['attraction_known_for']);
				$known_tags='';
				$t=0;
				for($i=0;$i<count($knwofortag);$i++)
				{
					$t++;
					$keytag = array_search($knwofortag[$i], array_column($tags, 'id'));;
					
					if($t==count($knwofortag))
					{
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']);
					}
					else
					{
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']).', ';	
					}
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
						  'getyourguide'=>$row['attraction_getyourguid'],
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						  'isplace'=>$isplace,
						  'ispaid'=>$ispaid,
						  'category'=>1
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$mandatorydata=mandatoryTags($city_id);
			if(count($mandatorydata))
			{
				$att_array=array_merge($data,$mandatorydata);
			}
			else
			{
				$att_array=$data;
			}


			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($att_array));
			fclose($file);
		}
		else
		{
			$mandatorydata=$CI->mandatoryTags($city_id);
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($mandatorydata));
			fclose($file);
		}


	}


	function mandatoryTags($city_id)
	{
		$CI = &get_instance();
		$mand=array();
		$Q1=$CI->db->query('select id,mandatory_dest,mandatory_lat,mandatory_long,(select mandatory_tag from tbl_mandatory_tags where id=city_mandatory_tag_master.mandatory_tag_id) as tag,(select longitude from tbl_city_master where id=city_mandatory_tag_master.city_id) as citylongitude,(select latitude from tbl_city_master where id=city_mandatory_tag_master.city_id) as citylatitude from city_mandatory_tag_master where city_id="'.$city_id.'" order by id DESC');
		if($Q1->num_rows()>0)
		{
			foreach($Q1->result_array() as $k=>$row)
			{
				$mand[$k]['type']='Feature';
				$mand[$k]['geometry']=array(
						'type'=>'Point',
						);
				$mand[$k]['geometry']['coordinates']=array(
						'0'=>$row['mandatory_long'],
						'1'=>$row['mandatory_lat'],
						);
				$mand[$k]['properties']=array(
						  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['mandatory_dest']),
						  'knownfor'=>0,
						  'known_tags'=>$row['tag'],
						  'tag_star'=>1,
						  'getyourguide'=>0,
						  'attractionid'=>'tag_'.$row['id'],
						  'cityid'=>md5($city_id),
						  'isplace'=>0,
						  'ispaid'=>0,
						  'category'=>0
						);
				$mand[$k]['devgeometry']['devcoordinates']=array(
						'0'=>$row['citylongitude'],
						'1'=>$row['citylatitude'],
						);
			}
		}

		return $mand;
	}

	function updateOther($city_id,$longitude,$latitude,$tags)
	{
		$CI = &get_instance();
		$Q=$CI->db->query('select id,ras_name as attraction_name,ras_lat as attraction_lat,ras_long as attraction_long,ras_description as attraction_details,ras_address as attraction_address,ras_contact as attraction_contact,known_for as attraction_known_for,tag_star from tbl_city_relaxationspa where city_id="'.$city_id.'"');

		if($Q->num_rows()>0)
		{
			updateOtherFiles($Q->result_array(),2,'relaxationspa');
		}

		$Q=$CI->db->query('select id,ran_name as attraction_name,ran_lat as attraction_lat,ran_long as attraction_long,ran_description as attraction_details,ran_address as attraction_address,ran_contact as attraction_contact,known_for as attraction_known_for,tag_star from tbl_city_restaurants where city_id="'.$city_id.'"');

		if($Q->num_rows()>0)
		{
			updateOtherFiles($Q->result_array(),3,'restaurant');
		}

		$Q=$CI->db->query('select id,adventure_name as attraction_name,adventure_lat as attraction_lat,adventure_long as attraction_long,adventure_details as attraction_details,adventure_address as attraction_address,adventure_contact as attraction_contact,adventure_known_for as attraction_known_for,tag_star,adventure_getyourguid as attraction_getyourguid,adventure_buy_ticket as ispaid from tbl_city_sports_adventures where city_id="'.$city_id.'"');

		if($Q->num_rows()>0)
		{
			updateOtherFiles($Q->result_array(),5,'sport');
		}

		$Q=$CI->db->query('select id,stadium_name as attraction_name,stadium_lat as attraction_lat,stadium_long as attraction_long,stadium_description as attraction_details,stadium_address as attraction_address,stadium_contact as attraction_contact,tag_star,stadium_get_your_guide as attraction_getyourguid,known_for as attraction_known_for from tbl_city_stadiums where city_id="'.$city_id.'"');

		if($Q->num_rows()>0)
		{
			updateOtherFiles($Q->result_array(),4,'stadium');
		}

	}

	function updateOtherFiles($rdata,$category,$filep)
	{	
			$CI = &get_instance();
			$c=0;
			foreach($rdata as $key=>$row)
			{  
				$gyg=0;
				if(isset($row['attraction_getyourguid']) && $row['attraction_getyourguid']==1)
				{
					$gyg=1;
				}
				$isplace=1;

				$ispaid=0;
				if(isset($row['ispaid']) && $row['ispaid']==1)
				{
					$ispaid=1;
				}
				
				$knwofortag=array();
				$knwofortag=explode(',',$row['attraction_known_for']);
				$known_tags='';
				$t=0;
				for($i=0;$i<count($knwofortag);$i++)
				{
					$t++;
					$keytag = array_search($knwofortag[$i], array_column($tags, 'id'));;
					
					if($t==count($knwofortag))
					{
						$known_tags .=str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$tags[$keytag]['tag_name']);
					}
					else
					{
						$known_tags .=str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$tags[$keytag]['tag_name']).', ';	
					}
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
						  'getyourguide'=>$gyg,
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						  'isplace'=>$isplace,
						  'ispaid'=>$ispaid,
						  'category'=>$category
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$randomstring=md5($city_id);
			$file=fopen(FCPATH."userfiles/$filep/".$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);
	}
	
	

?>