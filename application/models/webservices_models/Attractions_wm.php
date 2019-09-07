<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Attractions_wm extends CI_Model {

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

	/* Below Code is for searched countries */

	function getSearchedCity()
	{

		$data=array();
		if(isset($_POST['searchtags']) && !empty($_POST['searchtags']))
		{
			$data=array();
			$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,city_name,tbl_city_master.slug as cityslug,total_days,latitude,longitude,tbl_city_master.country_id,md5(tbl_city_master.id) as cityid,cityimage,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where tbl_country_master.id=tbl_city_master.country_id) as countryimage,rome2rio_name,code',FALSE);

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
			$this->db->where('rome2rio_name',$_POST['sdestination']);
			$this->db->group_by('attraction_id');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					if(empty($row['cityimage']))
					{
						$row['cityimage']='';
					}
					if(empty($row['countryimage']))
					{
						$row['countryimage']='';
					}
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
			$this->db->select('id,city_name,slug as cityslug,total_days,latitude,longitude,country_id,md5(tbl_city_master.id) as cityid,cityimage,city_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,rome2rio_name,code');
			$this->db->from('tbl_city_master');
			$this->db->where('rome2rio_name',$_POST['sdestination']);
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					if(empty($row['cityimage']))
					{
						$row['cityimage']='';
					}
					if(empty($row['countryimage']))
					{
						$row['countryimage']='';
					}
					$data[]=$row;
				}
			}


		}
		return $data;
	}

	function getSearchedCityOther($maincityarray,$isadd=0)
	{

		if (isset($_POST['searchtags']) && !empty($_POST['searchtags']))
		{
			return $this->getSearchedCityOtherWithTags($maincityarray,$isadd);
		}
		else
		{
			return $this->getSearchedCityOtherWithNoTags($maincityarray,$isadd);
		}

	}

	function getSearchedCityOtherWithTags($maincityarray,$isadd)
	{
		$data=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForTags($maincityarray,$isadd);
		//echo $extra_days;die;
		if($extra_days===0)
		{
			return $data;
		}

			$cityids=array();
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
			}

			$data=array();
			$citytotaldays=$maincityarray[0]['total_days'];
			$lat=$maincityarray[0]['latitude'];
			$lng=$maincityarray[0]['longitude'];
			$rome2rio_name=$maincityarray[0]['rome2rio_name'];

			$this->db->select('tbl_city_master.id,tbl_city_attraction_log.id as log_id, tbl_city_attraction_log.country_id, tbl_city_attraction_log.attraction_id, tbl_city_attraction_log.tag_id, tbl_city_attraction_log.tag_hours,city_name,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.slug,tbl_city_master.rome2rio_name,total_days,md5(tbl_city_master.id) as cityid,cityimage,code,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance',FALSE);
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
					if(empty($row['cityimage']))
					{
						$row['cityimage']='';
					}
					$data[]=$row;
				}
			}

			if(count($data))
			{
				foreach($data as $arg)
				{
					$grouped_types[$arg['id']][] = $arg;
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
				$neededArray1=array();

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
						$totaldaysneededOfOriginalDestionation=$maincityarray[0]['totaldaysneeded'];
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
							$neededArray1[]=$neededArray[$key];
						}
						else
						{
							unset($neededArray[$key]);
						}

					}
				}

				return $neededArray1;

			}


			return $data;
		

	}

	function getSearchedCityOtherWithNoTags($maincityarray,$isadd)
	{
		$cityids=array();
		$extra_days=$this->getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$isadd);
//echo $extra_days."===";
		if($extra_days===0)
		{
			return $cityids;
		}
		foreach($maincityarray as $list)
		{
			$cityids[]=$list['id'];
		}
		
		$citytotaldays=$maincityarray[0]['total_days'];

		$data=array();
		$lat=$maincityarray[0]['latitude'];
		$lng=$maincityarray[0]['longitude'];
		$rome2rio_name=$maincityarray[0]['rome2rio_name'];
		$this->db->select('id,city_name,tbl_city_master.slug,latitude,longitude,total_days,cityimage,md5(id) as cityid,rome2rio_name,total_days,code,( 3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( latitude ) ) ) ) AS distance');
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
				if(empty($row['cityimage']))
				{
					$row['cityimage']='';
				}
				$data[$key]=$row;
				$data[$key]['totaldaysneeded']=0;				
			}
		}
		return $data;
	}

	function getTimeNeedToTravelCurrentCityForTags($maincityarray,$isadd)
	{

			$cityids=array();
			$daysTaken=0;
			foreach($maincityarray as $list)
			{
				$cityids[]=$list['id'];
				$daysTaken+=$list['totaldaysneeded'];
			}
			//echo $daysTaken;die;
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
					//echo $enteredDays;die;
					if($enteredDays<$daysTaken && $isadd==1)
					{
						//$extra_days=$daysTaken-$enteredDays;
						$extra_days=0;
					}
					else if($enteredDays>$daysTaken)
					{
						$extra_days=$enteredDays-$daysTaken;
					}
				}
			}
			return $extra_days;
	}

	function getTimeNeedToTravelCurrentCityForNoTags($maincityarray,$isadd)
	{
			$totaldaystaken=0;
			$extra_days=0;
			foreach($maincityarray as $list)
			{
				$totaldaystaken+=$list['total_days'];
			}
			//echo $totaldaystaken;die;

			$plus = substr($_POST['sdays'], -1);
			if($plus == '+')
			{
				$traveldays = 0;
				$extra_days='all';
			}
			else
			{
				$traveldays = (int)$_POST['sdays'];
				//echo $traveldays."=".count($maincityarray);die;
				if($traveldays > $totaldaystaken)
				{
					$extra_days=$traveldays-$totaldaystaken;
				}
				else if($traveldays < $totaldaystaken && $isadd==1)
				{
					//$extra_days=$totaldaystaken-$traveldays;
					$extra_days=0;
				}
			}

		return $extra_days;

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
			return $arr;//json_encode($arr);
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
				'name'=>$data[0]['attraction_name'],
				'details'=>$data[0]['attraction_details'],
				'attraction_address'=>nl2br($data[0]['attraction_address']),
				'attraction_contact'=>$data[0]['attraction_contact'],
				'attraction_website'=>$data[0]['attraction_website'],
				'attraction_public_transport'=>$data[0]['attraction_public_transport'],
				'attraction_timing'=>nl2br($data[0]['attraction_timing']),
				'attraction_time_required'=>$data[0]['attraction_time_required'],
				'attraction_wait_time'=>$data[0]['attraction_wait_time'],
				'attraction_buy_ticket'=>$data[0]['attraction_buy_ticket'],
				'attraction_admissionfee'=>nl2br($data[0]['attraction_admissionfee']),
				'image'=>$data[0]['image'],
				'tag_name'=>$data[0]['known'],

			);
		}
		else
		{
			$arr=array(
						'name'=>'Nothing To Show',
						'details'=>'Nothing To Show',
					);
		}

		return $arr;//json_encode($arr);
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
			$this->db->select('tbl_city_stadiums.id,image,stadium_name as attraction_name,stadium_description as attraction_details,stadium_address as attraction_address,stadium_contact as attraction_contact,stadium_website as attraction_website,	stadium_timing as attraction_timing');
			$this->db->from('tbl_city_stadiums');
		}
		else if($_POST['category']==5)
		{
			$known = 'Sports & Adventure';
			$this->db->select('tbl_city_sports_adventures.id,image,adventure_name as attraction_name,adventure_details as attraction_details,adventure_address as attraction_address,adventure_contact as attraction_contact,adventure_website as attraction_website,adventure_open_close_timing as attraction_timing,adventure_time_required as attraction_time_required,adventure_wait_time as attraction_wait_time,adventure_buy_ticket as attraction_buy_ticket,adventure_nearest_public_transport as attraction_public_transport,adventure_admissionfee as attraction_admissionfee');
			$this->db->from('tbl_city_sports_adventures');
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
				'name'=>$data['attraction_name'],
				'details'=>$data['attraction_details'],
				'attraction_address'=>nl2br($data['attraction_address']),
				'attraction_contact'=>$data['attraction_contact'],
				'attraction_website'=>$data['attraction_website'],
				'attraction_public_transport'=>$attraction_public_transport,
				'attraction_timing'=>nl2br($data['attraction_timing']),
				'attraction_time_required'=>$attraction_time_required,
				'attraction_wait_time'=>$attraction_wait_time,
				'attraction_buy_ticket'=>$attraction_buy_ticket,
				'attraction_admissionfee'=>$attraction_admissionfee,
				'image'=>$data['image'],
				'tag_name'=>$known,
			);
		}
		else
		{
			$arr=array(
						'name'=>'Nothing To Show',
						'details'=>'Nothing To Show',
					);
		}
		return $arr;//json_encode($arr);
	}


}

?>
