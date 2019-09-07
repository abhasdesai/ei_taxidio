<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class Attraction_fm extends CI_Model
{

	function getAllAttractions($city_id)
	{
		$data=array();
		$Q=$this->db->select('id,attraction_name,attraction_details')
				 ->from('tbl_city_paidattractions')
				 ->where('city_id',$city_id)
				 ->order_by('attraction_name','ASC')
				 ->get();
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) {
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAttractionsOfSelectedCity($cityid,$token,$isnew)
	{
		if(isset($_GET['searchtags']) && count($_GET['searchtags'])>0)
		{
			$ids=$this->getIDS($_GET['searchtags']);
			$this->getUserSelectedCityAttractions($ids,$cityid,$token,$isnew);

		}
		else
		{
			$this->writeAllUserAttraction($cityid,$token,$isnew);
		}

	}

	function writeAllUserAttraction($city_id,$token,$isnew)
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

		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}

	function writeAllUserAttractionForSingleCountry($city_id,$uniqueid,$isnew)
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
		$attraction_decode=$this->haversineGreatCircleDistance($attraction_decode,$dummyarr=array());


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

		$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$city_id,'w');
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

	function getUserRecommededAttractionsForsearchedCity($cityfile)
	{

		if(!file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data=array();
			$getInputs=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/inputs');

			$inputdecode=json_decode($getInputs,TRUE);
			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{
				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractions($ids,$cityfile);
			}
			else
			{
				$this->writeAllUserAttractionForSingleCountrySearch($cityfile);
			}
			return 1;
		}
		return 2;

		/* do if else if you do not want to saver state. */

	}


	function getUserSelectedCityAttractions($ids,$city_id,$token,$isnew)
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
		//echo "<pre>";print_r($attraction_decode);die;


		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}




	/* Multicountries */

	function setMultiCountries($encryptkey,$uniqueid)
	{
		$this->copyCitiesInsideMainFolder($encryptkey,$uniqueid);
		$this->copyCombinationsInsideMainFolder($encryptkey,$uniqueid);
		$directories=$this->makeDirectoryandFiles($encryptkey,$uniqueid);
		return $directories;
	}

	function makeDirectoryandFiles($encryptkey,$uniqueid)
	{

		$randomstring=$this->session->userdata('randomstring');
		$foldername=string_decode($encryptkey);
		if (!is_dir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid)) {
			mkdir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid, 0777,true);
		}


		$combinations_encode = file_get_contents(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);

		$encryptionkeyArray=array();

		foreach($combinations_decode as $key=>$list)
		{

			if($list['encryptkey']==$encryptkey)
			{
				$encryptionkeyArray=$combinations_decode[$key];
			}
			else if(string_decode($list['encryptkey'])==string_decode($encryptkey))
			{
				$combinations_decode[$key]['encryptkey']=$encryptkey;
				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations','w');
				fwrite($file,json_encode($combinations_decode));
				fclose($file);

				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/combinations','r');
				$userfiledata_encode=fgets($file);
				//fwrite($file,json_encode($combinations_decode));
				fclose($file);

				$userfiledata_decode=json_decode($userfiledata_encode,TRUE);
				//echo FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/combinations';
				//echo "<pre>";print_r($userfiledata_decode);die;
				$userfiledata_decode[0]['encryptkey']=$encryptkey;

				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/combinations','w+');
				$userfiledata_encode=fgets($file);
				fwrite($file,json_encode($userfiledata_decode));
				fclose($file);

				$encryptionkeyArray=$combinations_decode[$key];


			}
		}



		if(!count($encryptionkeyArray))
		{
			show_404();
		}
		return $encryptionkeyArray;
	}

	function copyCitiesInsideMainFolder($encryptkey,$uniqueid)
	{
		$randomstring=$this->session->userdata('randomstring');
		$foldername=string_decode($encryptkey);
		//echo $foldername;die;
		if (!is_dir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername)) {
			mkdir(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername, 0777,true);
		}


			$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/cities','r');
			$cities_encoded=fgets($file);
			fclose($file);

			$cities_decoded=json_decode($cities_encoded,TRUE);

			$cityArray=array();
			$explodecoids=explode('-',$foldername);
			for($i=0;$i<count($explodecoids);$i++)
			{

				$countryid=$explodecoids[$i];
				//echo "<pre>";print_r($countryid);die;
				$cityWithDistance=CalculateDistanceForSearch($cities_decoded[$countryid]);
				$cityArray[$countryid]=$cityWithDistance;
			}

			//echo "<pre>";print_r($cityArray);die;

			if(!file_exists(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/cities'))
			{

				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/cities','w+');
				fwrite($file,json_encode($cityArray));
				fclose($file);

			}

	}

	function copyCombinationsInsideMainFolder($encryptkey,$uniqueid)
	{
		$randomstring=$this->session->userdata('randomstring');
		$foldername=string_decode($encryptkey);

		$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.'combinations','r');
		$combination_encoded=fgets($file);
		fclose($file);

		$combinations_decode=json_decode($combination_encoded,TRUE);
		//echo "<pre>";print_r($usercombination);die;
		$usercombination=array();
		foreach($combinations_decode as $key=>$list)
		{
			if($list['encryptkey']==$encryptkey)
			{
				$usercombination[]=$combinations_decode[$key];
				break;
			}
			else if(string_decode($encryptkey)==string_decode($list['encryptkey']))
			{
				$combinations_decode[$key]['encryptkey']=$encryptkey;
				$usercombination[]=$combinations_decode[$key];
				break;
			}
		}

		$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/combinations','w+');
		fwrite($file,json_encode($usercombination));
		fclose($file);

	}


	function setMultiCountriesMD5($encryptkey,$uniqueid)
	{
		$randomstring=$this->session->userdata('randomstring');

		$combinations_encode = file_get_contents(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);

		$encryptionkeyArray=array();
		foreach($combinations_decode as $key=>$list)
		{
			//echo $encryptkey;die;
			if($list['encryptkey']==$encryptkey)
			{
				$encryptionkeyArray=$combinations_decode[$key];

			}
			else if(string_decode($list['encryptkey'])==string_decode($encryptkey))
			{
				$combinations_decode[$key]['encryptkey']=$encryptkey;
				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations','w');
				fwrite($file,json_encode($combinations_decode));
				fclose($file);
				$encryptionkeyArray=$combinations_decode[$key];

			}



		}
   	   return $encryptionkeyArray;

	}

	function getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername)
	{

		if(!file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{
			$data=array();
			$waypointsstr='';
			$waypointsjson=array();
			$getInputs=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);

			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{
				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractionsMultiCountry($ids,$cityfile,$uniqueid,0,$foldername);

			}
			else
			{
				$this->writeAllUserAttractionMultiCountry($cityfile,$uniqueid,0,$foldername);
			}
			return 1;
		}
		return 2;

		/* do if else if you do not want to saver state. */

	}



	function writeAllUserAttractionMultiCountry($city_id,$uniqueid,$isnew,$foldername)
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

		$attraction_decode_mer=$this->mergeOtherAttractions($attractionarr_decode,$city_id);
		$attraction_decode=$this->haversineGreatCircleDistance($attraction_decode_mer);




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

		$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}



	function getSelectedAttractionsMultiCountry($ids,$city_id,$uniqueid,$isnew,$foldername)
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

		$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

	}


	function makeCityArray($cityid)
	{

		$this->db->select('tbl_city_master.id,city_name,tbl_city_master.slug as cityslug,tbl_city_master.latitude,tbl_city_master.longitude,tbl_city_master.rome2rio_name,tbl_city_master.country_id,tbl_country_master.country_name,tbl_country_master.latitude as countrylatitude,tbl_country_master.longitude as countrylongitude,tbl_country_master.continent_id,tbl_country_master.slug,tbl_country_master.rome2rio_name as rome2rio_country_name,total_days,country_total_days,tbl_city_master.rome2rio_name as city_rome2rio_name,tbl_country_master.rome2rio_name as country_rome2rio_name,tbl_country_master.id as countryid,code,countrycode,rome2rio_code',FALSE);
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master', 'tbl_country_master.id=tbl_city_master.country_id');
		$this->db->where('tbl_city_master.id',$cityid);
		$Q=$this->db->get();
		$data=$Q->row_array();
		return $data;
	}



	function makeFileForThisCity($cityfile,$uniqueid)
	{

		$getInputs=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
		{

			$ids=$this->getIDS($inputdecode['tags']);
			$this->getSelectedAttractions($ids,$cityfile,$uniqueid,1);
		}
		else
		{
			$this->writeAllUserAttractionForSingleCountry($cityfile,$uniqueid,1);
		}

	}


	function getSelectedAttractions($ids,$city_id,$uniqueid,$isnew)
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



		$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

	}


	function makeFileForThisCityMulti($cityfile,$uniqueid,$foldername)
	{


		if(!file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile))
		{
			$data=array();
			$waypointsstr='';
			$waypointsjson=array();
			$getInputs=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/inputs');
			$inputdecode=json_decode($getInputs,TRUE);

			if(isset($inputdecode['tags']) && $inputdecode['tags']>0)
			{
				$ids=$this->getIDS($inputdecode['tags']);
				$this->getSelectedAttractionsMultiCountry($ids,$cityfile,$uniqueid,1,$foldername);

			}
			else
			{
				$this->writeAllUserAttractionMultiCountry($cityfile,$uniqueid,1,$foldername);
			}
			return 1;
		}
		return 2;

		/*
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

		$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
		*/
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

	function createfileForFilename($token,$searchinput)
	{
		$randomstring=$this->session->userdata('randomstring');
		$Q=$this->db->query('select id from tbl_city_master where rome2rio_name="'.$searchinput['sdestination'].'"');
		$cityidata=$Q->row_array();

		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','w');
		fwrite($file,$cityidata['id']);
		fclose($file);

	}

	function checkCityExist($cityid,$token)
	{

		$getInputs=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/inputs');
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


	function addExtraCity($citydetails,$token)
	{

		$this->createExtraCityFile($citydetails,$token);

	}

	function createExtraCityFile($citydetails,$token)
	{

		$randomstring=$this->session->userdata('randomstring');

		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'r');
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
			$length=count($filedata)-1;
			$filedata[$length]['sortorder']=$length;

			$cityWithDistance=CalculateDistanceForSearch($filedata);
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'w');
			fwrite($file,json_encode($cityWithDistance));
			fclose($file);
		}

		$this->createAttractionFileForExtraSearchCity($_POST['cityid'],$token);

	}

	function createAttractionFileForExtraSearchCity($cityfile,$token)
	{
		$getInputs=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/inputs');
		$inputdecode=json_decode($getInputs,TRUE);
		if(isset($inputdecode['searchtags']) && !empty($inputdecode['searchtags']))
		{
			$ids=$this->getIDS($inputdecode['searchtags']);
			$this->getSelectedAttractionsSearch($ids,$cityfile,$token);
		}
		else
		{
			$this->writeAllUserAttractionForSingleCountrySearch($cityfile,$token);
		}


	}

	function writeAllUserAttractionForSingleCountrySearch($city_id,$token)
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
		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}


	function getSelectedAttractionsSearch($ids,$city_id,$token)
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


		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$city_id,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	}

	/* Below code is to change order of cities */

	function ChangeOrderOfCities($type,$foldername='')
	{
			if($type=='singlecountry')
			{
				$this->orderXForSingleCountry();
			}
			else if($type=='multicountry')
			{
			    $this->OrderXforMultiCountry($foldername);
			}
			else
			{
				$this->OrderXforSearch();
			}
	}

	function orderXForSingleCountry()
	{
			$countryid=$_POST['coid'];
			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/singlecountry','r+');
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

					$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/singlecountry','w+');
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
		  $token=$_POST['uniqueid'];
			$filename=$this->getCitiesInFile($_POST['uniqueid']);
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filename,'r');
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
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$filename,'w+');
			$citydata=fwrite($file,json_encode($cityWithDistance));
			fclose($file);

	}

	function getCitiesInFile($token)
	{
		$cityarray=array();
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		return $filename;

	}


	function OrderXforMultiCountry($foldername)
	{
		$countryid=$_POST['coid'];
		$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$foldername.'/cities','r+');
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

				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$foldername.'/cities','w+');
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
