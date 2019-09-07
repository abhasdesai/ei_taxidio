<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function getRandomString()
{
	$CI = &get_instance();

	$uuid = '';
	if (function_exists('com_create_guid'))
	{
		$uuid = com_create_guid();
	}
	else
	{
		mt_srand((double) microtime() * 10000);
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45); // "-"
		$uuid = chr(123)
		. substr($charid, 0, 8) . $hyphen
		. substr($charid, 8, 4) . $hyphen
		. substr($charid, 12, 4) . $hyphen
		. substr($charid, 16, 4) . $hyphen
		. substr($charid, 20, 12)
		. chr(125); // "}"

	}
	$shopcartId = "";

	if (get_cookie('cartid'))
	{
		$shopcartId = get_cookie('cartid');
	}
	else
	{

		$shopcartId = $uuid;
		//$expire = time() + 60 * 60 * 24 * 30;
		$expire = time()+ 43200;
		$cookie = array(
			'name' => 'cartid',
			'value' => $shopcartId,
			'expire' => $expire,
		);

		$CI->input->set_cookie($cookie);

	}
	return $shopcartId;
}

function getRandomNumber()
{
	$CI = &get_instance();
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $string = '';
	$max = strlen($characters) - 1;
	for ($i = 0; $i < 5; $i++) {
	      $string .= $characters[mt_rand(0, $max)];
	}
	return $string.time();
}

function current_full_url()
{
    $CI =& get_instance();
	$url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

function getSelectedKeys($arraydecode)
{
	//echo "<pre>";print_r($arraydecode);die;
	$storeArray=array();
	foreach($arraydecode as $key=>$list)
	{
		if($list['properties']['tag_star']==1 || $list['properties']['tag_star']==2)
		{
			$storeArray[]=$arraydecode[$key];
		}
	}

	return $storeArray;
}

function clearHashLink($encryptkey)
{
	return $encryptkey;
	//echo substr($encryptkey, 0,88);die;
	//echo substr($encryptkey, 0, strrpos( $encryptkey, '-')+1);die;
	//return substr($encryptkey, 0, strrpos( $encryptkey, '-')+1);
}

function writeTripsInFile()
{
	$CI = &get_instance();
	$data=array();
	$Q=$CI->db->query('select id,trip_type,inputs,country_id,tripname,user_trip_name from tbl_itineraries where user_id="'.$CI->session->userdata('fuserid').'" order by id desc');
	if($Q->num_rows()>0)
	{
		$data=$Q->result_array();
	}
	makeTripsForFile($data);
}

function makeTripsForFile($data)
{
	$CI = &get_instance();
	$CI->load->model('Trip_fm');
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
            if(isset($list['user_trip_name']) && $list['user_trip_name']!='')
            {
                 $tripname_main_name=$list['user_trip_name'];
            }
            else
            {
                 $tripname_main=$CI->Trip_fm->getContinentCountryName($list['country_id']);
                 $tripname_main_name='Trip '.$tripname_main['country_name'];
            }

		}
		else
		{
			$url=site_url('multicountrytrips').'/'.string_encode($list['id']);


			if(isset($list['user_trip_name']) && $list['user_trip_name']!='')
            {
                 $tripname_main_name=$list['user_trip_name'];
            }
            else
            {
                 $tripname_main=$CI->Trip_fm->getContinentName($list['tripname']);
                 $tripname_main_name='Trip '.$tripname_main['country_name'];
            }

		}

		$decodejson=json_decode($list['inputs'],TRUE);
		if($list['trip_type']==3)
		{
			$sdate=$decodejson['sstart_date'];
			$days=$decodejson['sdays'];

		}
		else
		{
			$sdate=$decodejson['start_date'];
			$days=$decodejson['days'];
		}
		$calculatedays=$days-1;


		$returndata[$key]['title']=$tripname_main_name;
		$returndata[$key]['url']=$url;
		$returndata[$key]['start']=$startdate=implode("-", array_reverse(explode("/",$sdate)));
		$returndata[$key]['end']=$enddate=date('Y-m-d',strtotime($startdate . "+$days days"));
		$calculatedenddate=date('Y-m-d',strtotime($startdate . "+$calculatedays days"));
		//echo $startdate."=".$calculatedenddate."=".$calculatedays;die;
		if(strtotime($startdate)<strtotime(date('Y-m-d')) && strtotime($calculatedenddate)<strtotime(date('Y-m-d')))
		{
			$returndata[$key]['color']='#00882f';
		}
		else if(strtotime($startdate)<=strtotime(date('Y-m-d')) && strtotime($calculatedenddate)>=strtotime(date('Y-m-d')))
		{
			$returndata[$key]['color']='#591986';
		}
		else
		{
			$returndata[$key]['color']='#ff6420';
		}


	}

	if(!is_dir(FCPATH.'userfiles/myaccount/'.$CI->session->userdata('fuserid')))
	{
		 mkdir(FCPATH.'userfiles/myaccount/'.$CI->session->userdata('fuserid'), 0777, true);
	}
	$file=fopen(FCPATH.'userfiles/myaccount/'.$CI->session->userdata('fuserid').'/trips','w');
	fwrite($file,json_encode($returndata));
	fclose($file);
	return $returndata;
}

 function time_ago( $time )
{
   $time_difference = time() - $time;

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;

        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }

}


function checkITIExists($id)
{
	$CI = &get_instance();
	$Q=$CI->db->query('select id from tbl_itineraries where user_id="'.$CI->session->userdata('fuserid').'" and id="'.$id.'"');
	return $Q->num_rows();
}

function multiRequest($data, $options = array()) {

  $curly = array();
  $result = array();

  $mh = curl_multi_init();

  foreach ($data as $id => $d) {

  $curly[$id] = curl_init();

  $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
  curl_setopt($curly[$id], CURLOPT_URL,            $url);
  curl_setopt($curly[$id], CURLOPT_HEADER,         0);
  curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

  if (is_array($d))
	{
      if (!empty($d['post']))
			{
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
   }

  if (!empty($options)) {
    curl_setopt_array($curly[$id], $options);
  }

    curl_multi_add_handle($mh, $curly[$id]);
  }

  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);


  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
   curl_multi_close($mh);
   return $result;
}

function loggedinUser($islogin)
{
	$CI = &get_instance();
	if($islogin==1)
	{
		$CI->db->where('id',$CI->session->userdata('fuserid'));
		$CI->db->update('tbl_front_users',array('isloggedin'=>1));
	}
	else
	{
		$CI->db->where('id',$CI->session->userdata('fuserid'));
		$CI->db->update('tbl_front_users',array('isloggedin'=>0));
	}
}

function CalculateDistanceForSearch($cities)
{
		$CI = &get_instance();
		$requests=array();
		$i = 0;
		$len = count($cities);
		foreach($cities as $key=>$list)
		{
				if($i != $len-1)
				{
					$start_city=$cities[$key]['rome2rio_name'];
					$end_city=$cities[$key+1]['rome2rio_name'];
					$requests[$key]='https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
				}

			$i++;
		}

		$responses=multiRequest($requests);

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


	 $i = 0;
	 foreach($cities as $key=>$list)
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
	 $cities[$len-1]['nextdistance']='';

	 return $cities;
}


function CalculateDistance($cities,$countryid)
{
			$CI = &get_instance();
			$requests=array();
			$i = 0;
			$len = count($cities[$countryid]);
			foreach($cities[$countryid] as $key=>$list)
			{
					if($i != $len-1)
					{
						$start_city=$cities[$countryid][$key]['rome2rio_name'];
						$end_city=$cities[$countryid][$key+1]['rome2rio_name'];
						$requests[$key]='https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
					}

				$i++;
			}

			$responses=multiRequest($requests);

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


		 $i = 0;
		 foreach($cities[$countryid] as $key=>$list)
		 {
				 if($i != $len-1)
				 {
					 $response=$country_response[$key];
					 $hours = floor($response / 60);
					 $minutes = $response % 60;
					 $cities[$countryid][$key]['nextdistance']=formattime($hours, $minutes);
				 }

			 $i++;
		 }
		 $cities[$countryid][$len-1]['nextdistance']='';

		 return $cities;
}


function removeUnnecessaryFiedsForSingleCountry($cityArray)
{
	foreach ($cityArray as $mainkey => $mainlist)
	{
		foreach ($mainlist as $subkey => $sublist)
		{

			 unset($cityArray[$mainkey][$subkey]['latitude']);
			 unset($cityArray[$mainkey][$subkey]['longitude']);
			 unset($cityArray[$mainkey][$subkey]['countrylatitude']);
			 unset($cityArray[$mainkey][$subkey]['countrylongitude']);
			 unset($cityArray[$mainkey][$subkey]['country_conclusion']);
			 unset($cityArray[$mainkey][$subkey]['countryimage']);
			 unset($cityArray[$mainkey][$subkey]['cityimage']);

		}
	}
	//echo "<pre>";print_r($cityArray);die;
	return $cityArray;

}

function formattime($hours, $minutes)
{
	$time=$hours . ' Hrs ' . $minutes . ' Mins';
	if($hours<=0)
	{
		$time= $minutes . ' Mins';
	}
	else if($minutes<=0)
	{
		$time= $hours . ' Hrs ';
	}
	return $time;
}


function string_encode($str)
{
	return strtr(base64_encode($str), '+/=', '-_~');
}

function string_decode($str)
{
	return base64_decode(strtr($str, '-_~', '+/='));
}



?>
