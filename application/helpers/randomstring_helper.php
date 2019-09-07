<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function getRandomString() {
	$CI = &get_instance();

	$uuid = '';
	if (function_exists('com_create_guid')) {
		$uuid = com_create_guid();
	} else {
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

	if (get_cookie('cartid')) {
		$shopcartId = get_cookie('cartid');
	} else {

		$shopcartId = $uuid;
		//$expire = time() + 60 * 60 * 24 * 30;
		$expire = time() + 43200;
		$cookie = array(
			'name' => 'cartid',
			'value' => $shopcartId,
			'expire' => $expire,
		);

		$CI->input->set_cookie($cookie);

	}
	return $shopcartId;
}

function getRandomNumber() {
	$CI = &get_instance();
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$string = '';
	$max = strlen($characters) - 1;
	for ($i = 0; $i < 5; $i++) {
		$string .= $characters[mt_rand(0, $max)];
	}
	return $string . time();
}

function current_full_url() {
	$CI = &get_instance();
	$url = $CI->config->site_url($CI->uri->uri_string());
	return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}

function getSelectedKeys($arraydecode) {
	//echo "<pre>";print_r($arraydecode);die;
	$storeArray = array();
	foreach ($arraydecode as $key => $list) {
		if ($list['properties']['tag_star'] == 1 || $list['properties']['tag_star'] == 2) {
			$storeArray[] = $arraydecode[$key];
		}
	}

	return $storeArray;
}

function clearHashLink($encryptkey) {
	return $encryptkey;
	//echo substr($encryptkey, 0,88);die;
	//echo substr($encryptkey, 0, strrpos( $encryptkey, '-')+1);die;
	//return substr($encryptkey, 0, strrpos( $encryptkey, '-')+1);
}

function writeTripsInFile() {
	$CI = &get_instance();
	$result = get_all_invited_trip_details(1);
	$data = array();
	$Q = $CI->db->query('select id,trip_type,inputs,country_id,tripname,user_trip_name from tbl_itineraries where user_id="' . $CI->session->userdata('fuserid') . '" order by id desc');
	if ($Q->num_rows() > 0) {
		$data = $Q->result_array();
	}
	if ($result !== FALSE) {
		$data2 = array();
		$ids = implode(",", array_column($result, 'itinerary_id'));
		$Q = $CI->db->query('select id,trip_type,inputs,country_id,tripname,user_trip_name from tbl_itineraries where id in (' . $ids . ') order by id desc');
		if ($Q->num_rows() > 0) {
			$data2 = $Q->result_array();
		}
		$mergedata = array_merge($data, $data2);
	} else {
		$mergedata = $data;
	}
	makeTripsForFile($mergedata);
}

function makeTripsForFile($data) {
	$CI = &get_instance();
	$CI->load->model('Trip_fm');
	$returndata = array();
	foreach ($data as $key => $list) {
		if ($list['trip_type'] != 2) {
			if ($list['trip_type'] == 1) {
				$url = site_url('userSingleCountryTrip') . '/' . string_encode($list['id']);
			} else if ($list['trip_type'] == 3) {
				$url = site_url('userSearchedCityTrip') . '/' . string_encode($list['id']);
			}
			if (isset($list['user_trip_name']) && $list['user_trip_name'] != '') {
				$tripname_main_name = $list['user_trip_name'];
			} else {
				$tripname_main = $CI->Trip_fm->getContinentCountryName($list['country_id']);
				$tripname_main_name = 'Trip ' . $tripname_main['country_name'];
			}

		} else {
			$url = site_url('multicountrytrips') . '/' . string_encode($list['id']);

			if (isset($list['user_trip_name']) && $list['user_trip_name'] != '') {
				$tripname_main_name = $list['user_trip_name'];
			} else {
				$tripname_main = $CI->Trip_fm->getContinentName($list['tripname']);
				$tripname_main_name = 'Trip ' . $tripname_main['country_name'];
			}

		}

		$decodejson = json_decode($list['inputs'], TRUE);
		if ($list['trip_type'] == 3) {
			$sdate = $decodejson['sstart_date'];
			$days = $decodejson['sdays'];

		} else {
			$sdate = $decodejson['start_date'];
			$days = $decodejson['days'];
		}
		$calculatedays = $days - 1;

		$returndata[$key]['title'] = $tripname_main_name;
		$returndata[$key]['url'] = $url;
		$returndata[$key]['start'] = $startdate = implode("-", array_reverse(explode("/", $sdate)));
		$returndata[$key]['end'] = $enddate = date('Y-m-d', strtotime($startdate . "+$days days"));
		$calculatedenddate = date('Y-m-d', strtotime($startdate . "+$calculatedays days"));
		//echo $startdate."=".$calculatedenddate."=".$calculatedays;die;
		if (strtotime($startdate) < strtotime(date('Y-m-d')) && strtotime($calculatedenddate) < strtotime(date('Y-m-d'))) {
			$returndata[$key]['color'] = '#00882f';
		} else if (strtotime($startdate) <= strtotime(date('Y-m-d')) && strtotime($calculatedenddate) >= strtotime(date('Y-m-d'))) {
			$returndata[$key]['color'] = '#591986';
		} else {
			$returndata[$key]['color'] = '#ff6420';
		}
	}

	//Add Dynamic Notes From Database to File - start

	$CI->db->select('id,subject,startdate,enddate,description');
	$CI->db->from('tbl_calendarEvents');
	$CI->db->where('user_id', $CI->session->userdata('fuserid'));
	$Q = $CI->db->get();

	if ($Q->num_rows() > 0) {
		$notes = $Q->result_array();
		$newdata = array();
		foreach ($notes as $k => $note) {
			$newdata[$k]['title'] = $note['subject'];
			$newdata[$k]['id'] = $note['id'];
			$newdata[$k]['start'] = $note['startdate'];
			$newdata[$k]['end'] = $note['enddate'];
			$newdata[$k]['description'] = $note['description'];
			$newdata[$k]['color'] = "#34d3eb";
		}
		$returndata = array_merge($returndata, $newdata);
	}

	//Add Dynamic Notes From Database to File - end

	if (!is_dir(FCPATH . 'userfiles/myaccount/' . $CI->session->userdata('fuserid'))) {
		mkdir(FCPATH . 'userfiles/myaccount/' . $CI->session->userdata('fuserid'), 0777, true);
	}
	$file = fopen(FCPATH . 'userfiles/myaccount/' . $CI->session->userdata('fuserid') . '/trips', 'w');
	fwrite($file, json_encode($returndata));
	fclose($file);
	return $returndata;
}

function time_ago($time) {
	$time_difference = time() - $time;

	if ($time_difference < 1) {return 'less than 1 second ago';}
	$condition = array(12 * 30 * 24 * 60 * 60 => 'year',
		30 * 24 * 60 * 60 => 'month',
		24 * 60 * 60 => 'day',
		60 * 60 => 'hour',
		60 => 'minute',
		1 => 'second',
	);

	foreach ($condition as $secs => $str) {
		$d = $time_difference / $secs;

		if ($d >= 1) {
			$t = round($d);
			return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
		}
	}

}

function checkITIExists($id) {
	$CI = &get_instance();
	$Q = $CI->db->query('select id from tbl_itineraries where user_id="' . $CI->session->userdata('fuserid') . '" and id="' . $id . '"');
	return $Q->num_rows();
}

function multiRequest($data, $options = array()) {

	$curly = array();
	$result = array();

	$mh = curl_multi_init();

	foreach ($data as $id => $d) {

		$curly[$id] = curl_init();

		$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
		curl_setopt($curly[$id], CURLOPT_URL, $url);
		curl_setopt($curly[$id], CURLOPT_HEADER, 0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

		if (is_array($d)) {
			if (!empty($d['post'])) {
				curl_setopt($curly[$id], CURLOPT_POST, 1);
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
	} while ($running > 0);

	foreach ($curly as $id => $c) {
		$result[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	}
	curl_multi_close($mh);
	return $result;
}

function loggedinUser($islogin) {
	$CI = &get_instance();
	if ($islogin == 1) {
		$CI->db->where('id', $CI->session->userdata('fuserid'));
		$CI->db->update('tbl_front_users', array('isloggedin' => 1));
	} else {
		$CI->db->where('id', $CI->session->userdata('fuserid'));
		$CI->db->update('tbl_front_users', array('isloggedin' => 0));
	}
}

function CalculateDistanceForSearch($cities) {
	$CI = &get_instance();
	$requests = array();
	$i = 0;
	$len = count($cities);
	foreach ($cities as $key => $list) {
		if ($i != $len - 1) {
			$start_city = $cities[$key]['rome2rio_name'];
			$end_city = $cities[$key + 1]['rome2rio_name'];
			$requests[$key] = 'https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		}

		$i++;
	}

	$responses = multiRequest($requests);

	$country_response = array();

	foreach ($responses as $key => $list) {
		$json = json_decode($list, TRUE);
		//echo "<pre>";print_r($json['routes']);die;
		if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['totalDuration'] == '') {
			$country_response[$key] = 'na';
		} else {
			$country_response[$key] = $json['routes'][0]['totalDuration'];
		}
	}

	$i = 0;
	foreach ($cities as $key => $list) {
		if ($i != $len - 1) {
			$response = $country_response[$key];
			$hours = floor($response / 60);
			$minutes = $response % 60;
			$cities[$key]['nextdistance'] = formattime($hours, $minutes);
		}

		$i++;
	}
	$cities[$len - 1]['nextdistance'] = '';

	return $cities;
}

function CalculateDistance($cities, $countryid) {
	$CI = &get_instance();
	$requests = array();
	$i = 0;
	$len = count($cities[$countryid]);
	foreach ($cities[$countryid] as $key => $list) {
		if ($i != $len - 1) {
			$start_city = $cities[$countryid][$key]['rome2rio_name'];
			$end_city = $cities[$countryid][$key + 1]['rome2rio_name'];
			$requests[$key] = 'https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		}

		$i++;
	}

	$responses = multiRequest($requests);

	$country_response = array();

	foreach ($responses as $key => $list) {
		$json = json_decode($list, TRUE);
		//echo "<pre>";print_r($json['routes']);die;
		if (!isset($json['routes'][0]['duration']) && $json['routes'][0]['totalDuration'] == '') {
			$country_response[$key] = 'na';
		} else {
			$country_response[$key] = $json['routes'][0]['totalDuration'];
		}
	}

	$i = 0;
	foreach ($cities[$countryid] as $key => $list) {
		if ($i != $len - 1) {
			$response = $country_response[$key];
			$hours = floor($response / 60);
			$minutes = $response % 60;
			$cities[$countryid][$key]['nextdistance'] = formattime($hours, $minutes);
		}

		$i++;
	}
	$cities[$countryid][$len - 1]['nextdistance'] = '';

	return $cities;
}

function removeUnnecessaryFiedsForSingleCountry($cityArray) {
	foreach ($cityArray as $mainkey => $mainlist) {
		foreach ($mainlist as $subkey => $sublist) {

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

function formattime($hours, $minutes) {
	$time = $hours . ' Hrs ' . $minutes . ' Mins';
	if ($hours <= 0) {
		$time = $minutes . ' Mins';
	} else if ($minutes <= 0) {
		$time = $hours . ' Hrs ';
	}
	return $time;
}

function string_encode($str) {
	return strtr(base64_encode($str), '+/=', '-_~');
}

function string_decode($str) {
	return base64_decode(strtr($str, '-_~', '+/='));
}

function get_invited_trip_details($itinerary_id, $userid = null) {
	$CI = &get_instance();
	$CI->db->select('*');
	$CI->db->from('tbl_share_itineraries');
	if ($userid == null) {
		$CI->db->where('invited_user_id', $CI->session->userdata('fuserid'));
	} else {
		$CI->db->where('invited_user_id', $userid);
	}
	$CI->db->where('itinerary_id', $itinerary_id);
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		return $Q->row_array();
	}
	return FALSE;
}

function get_all_invited_trip_details($status = null, $userid = null) {
	$CI = &get_instance();
	$CI->db->select('*');
	$CI->db->from('tbl_share_itineraries');
	if ($userid == null) {
		$CI->db->where('invited_user_id', $CI->session->userdata('fuserid'));
	} else {
		$CI->db->where('invited_user_id', $userid);
	}
	if ($status !== null) {
		$CI->db->where('status', $status);
	}
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		return $Q->result_array();
	}
	return FALSE;
}

function invited_users_updates() {
	$CI = &get_instance();
	$CI->db->select('id,user_id,itinerary_id');
	$CI->db->from('tbl_invited_users');
	$CI->db->where('invited_user_email', $CI->session->userdata('email'));
	$Q = $CI->db->get();
	$rows = $Q->result_array();
	if ($Q->num_rows() > 0) {
		foreach ($rows as $key => $row) {
			$CI->db->where('id', $row['id']);
			$CI->db->update('tbl_invited_users', array('is_register' => 1));
			if (!empty($row['itinerary_id'])) {
				$data = array(
					'user_id' => $row['user_id'],
					'invited_user_id' => $CI->session->userdata('fuserid'),
					'itinerary_id' => $row['itinerary_id'],
					'created' => date('Y-m-d H:i:s'),
					'modified' => date('Y-m-d H:i:s'),
				);
				$CI->db->insert('tbl_share_itineraries', $data);
			}
		}
	}
}

function is_any_co_traveller($iti) {
	$CI = &get_instance();
	$Q = $CI->db->query('select id from tbl_share_itineraries where user_id="' . $CI->session->userdata('fuserid') . '" and itinerary_id="' . $iti . '" and status=1');
	if ($Q->num_rows() > 0) {
		return 1;
	}
	return 0;
}

function getsharedITIdetails() {
	$CI = &get_instance();
	$itis = false;
	$CI->db->select('tbl_itineraries.id,inputs,singlecountry,tbl_itineraries.created,user_trip_name,name,trip_type,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as countryname,citiorcountries,cities,tbl_itineraries.country_id,slug,(select count(id) from tbl_itinerary_questions where tbl_itinerary_questions.itinerary_id=tbl_itineraries.id) as totalquestions,userimage,googleid,facebookid,name,tbl_itineraries.user_id,views,rating');
	$CI->db->from('tbl_itineraries');
	$CI->db->join('tbl_front_users', 'tbl_front_users.id=tbl_itineraries.user_id');
	$CI->db->join('tbl_share_itineraries', 'tbl_share_itineraries.user_id=tbl_itineraries.user_id');
	$CI->db->where('tbl_share_itineraries.itinerary_id=tbl_itineraries.id');
	$CI->db->where('tbl_share_itineraries.invited_user_id', $CI->session->userdata('fuserid'));
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		$itis = $Q->result_array();
	}
	return $itis;
}

function generateToken($length) {
	$key = '';
	$keys = array_merge(range(0, 9), range('A', 'Z'));

	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}

	return $key;
}

function getToken($iti) {
	$CI = &get_instance();
	$CI->db->select('iti_token');
	$CI->db->from('tbl_itineraries');
	$CI->db->where('user_id', $CI->session->userdata('fuserid'));
	$CI->db->where('id', $iti);
	$CI->db->where('iti_token !=', '');
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		$token = $Q->row_array();
		return $token['iti_token'];
	} else {
		$token = generateToken(5);
		$CI->db->where('id', $iti);
		$CI->db->update('tbl_itineraries', array('iti_token' => $token));
		return $token;
	}
}

//Calendar Dynamic Events For Hotels and Activities
function appendDynamicNoteToFile($noteId) {
	$CI = &get_instance();
	$CI->db->select('id,subject,user_id,startdate,enddate,description');
	$CI->db->from('tbl_calendarEvents');
	$CI->db->where('id', $noteId);
	$CI->db->where('user_id', $CI->session->userdata('fuserid'));
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		$noteData = $Q->row();
		$file = FCPATH . 'userfiles/myaccount/' . $CI->session->userdata('fuserid') . '/trips';
		$events = json_decode(file_get_contents($file, true));
		$events[] = array("title" => $noteData->subject, 'start' => $noteData->startdate, 'end' => $noteData->enddate, 'color' => '#34d3eb', 'description' => $noteData->description, 'id' => $noteData->id);
		file_put_contents($file, json_encode($events));
	}
}

function deleteDynamicNoteFromFile($noteId) {
	$CI = &get_instance();
	$CI->db->select('id');
	$CI->db->from('tbl_calendarEvents');
	$CI->db->where('id', $noteId);
	$CI->db->where('user_id', $CI->session->userdata('fuserid'));
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		$file = FCPATH . 'userfiles/myaccount/' . $CI->session->userdata('fuserid') . '/trips';
		$events = json_decode(file_get_contents($file), true);
		//echo "<pre>";print_r($events);die;
		$arr_index = array();

		foreach ($events as $key => $v) {
			if (array_key_exists('id', $v) && $v['id'] == $noteId) {
				$arr_index[] = $key;
			}
		}

		//Delete Data

		foreach ($arr_index as $i) {
			unset($events[$i]);
		}

		//Rebase Array

		$json_array = array_values($events);

		//encode array to json and write to the file
		file_put_contents($file, json_encode($json_array));
		$CI->db->where('id', $noteId);
		$CI->db->delete('tbl_calendarEvents');
		return true;
	} else {
		return false;
	}
}

?>
