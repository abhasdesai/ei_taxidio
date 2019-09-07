<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function deviceUpdate($udid, $version, $device_type) {
	$CI = &get_instance();
	$datetime = date('Y-m-d H:i:s');
	$result = selectcolbycondition('id', 'tbl_device_master', 'udid like "' . $udid . '" and device_type="' . $device_type . '"');

	if ($result == false) {
		$data = array(
			'udid' => $udid,
			'version' => $version,
			'device_type' => $device_type,
			'created' => $datetime,
		);
		$CI->db->insert('tbl_device_master', $data);
		return $CI->db->insert_id();
	} else {
		$data = array(
			'version' => $version,
			'last_access' => $datetime,
		);
		$CI->db->where('id ="' . $result[0]['id'] . '"');
		$CI->db->update('tbl_device_master', $data);
		return $result[0]['id'];
	}
}

function deviceLogin($user_id, $device_id) {
	$CI = &get_instance();
	$data = array(
		'last_login_id' => $user_id,
		'last_access' => date('Y-m-d H:i:s'),
	);
	$CI->db->where('id ="' . $device_id . '"');
	$CI->db->update('tbl_device_master', $data);
}

function selectcolbycondition($selectcol, $table, $condition) {
	$CI = &get_instance();

	$CI->db->select($selectcol);
	$CI->db->from($table);
	if (isset($condition) && !empty($condition)) {
		$CI->db->where($condition);
	}
	$query = $CI->db->get();
	if ($query->num_rows() > 0) {
		return $query->result_array();
	}
	return false;
}

function getrowbycondition($selectcol, $table, $condition) {
	$CI = &get_instance();

	$CI->db->select($selectcol);
	$CI->db->from($table);
	if (isset($condition) && !empty($condition)) {
		$CI->db->where($condition);
	}
	$query = $CI->db->get();
	if ($query->num_rows() > 0) {
		return $query->row_array();
	}
	return false;
}

function getLatandLongOfCity($city_id) {
	$CI = &get_instance();
	$Q = $CI->db->query('select id,latitude as citylatitude,longitude as citylongitude,country_id,cityimage,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countrybanner from tbl_country_master where id=tbl_city_master.country_id) as countrybanner,city_name,travelguide from tbl_city_master where id="' . $city_id . '"');
	return $Q->row_array();
}

function getCountryNameFromSlug($slug) {
	$CI = &get_instance();
	$data = array();
	$Q = $CI->db->query('select id,country_name,country_conclusion,countryimage,slug from tbl_country_master where slug="' . $slug . '"');
	$data = $Q->row_array();
	return $data;
}

function getcountrynoofCities($countryid) {
	$CI = &get_instance();
	$data = array();
	$CI->db->select('id'); //country_id,country_id
	$CI->db->from('tbl_city_master');
	$CI->db->where('country_id', $countryid);
	$Q = $CI->db->get();
	return $Q->num_rows();
}

function getOtherCitiesOfThisCountry($country_id, $cityArray) {
	$CI = &get_instance();
	$data = array();
	$CI->db->select('id,continent_id,city_name,slug as cityslug,rome2rio_name,latitude,longitude,code,cityimage'); //country_id,country_id
	$CI->db->from('tbl_city_master');
	$CI->db->where('total_attraction_time >', 0);
	$CI->db->where('country_id', $country_id);
	$CI->db->where_not_in('id', $cityArray);
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		$i = 0;
		foreach ($Q->result_array() as $row) {
			if (empty($row['cityimage'])) {
				$row['cityimage'] = '';
			}
			$data[$i] = $row;
			$data[$i]['sortorder'] = -1;
			$i++;
		}
	}
	return $data;
}

function getShortestDistance($rome2rio_name) {
	$CI = &get_instance();
	$CI->load->helper('randomstring');
	$requests = array();
	$i = 0;
	$len = count($rome2rio_name);

	foreach ($rome2rio_name as $key => $list) {
		if ($i != $len - 1) {
			$start_city = $rome2rio_name[$key];
			$end_city = $rome2rio_name[$key + 1];
			$requests[$key] = 'https://taxidio.rome2rio.com/api/1.4/json/Search?key=iWe3aBSN&oName=' . urlencode($start_city) . '&dName=' . urlencode($end_city) . '';
		}
		$i++;
	}

	//print_r($requests);die;
	$responses = multiRequest($requests);
	//print_r($responses);die;

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
//print_r($country_response);die;

	$i = 0;
	$cities = "";
	foreach ($rome2rio_name as $key => $list) {
		if ($i != $len - 1) {
			$response = $country_response[$key];
			$hours = floor($response / 60);
			$minutes = $response % 60;
			$cities[$key]['nextdistance'] = formattime($hours, $minutes);
		}

		$i++;
	}
	//$cities[$len-1]['nextdistance']='';

	return $cities;
}

function getUserRecommededAttractionsForCity($cityfile, $tags = array()) {
	$CI = &get_instance();
	$isnew = 0;
	if (isset($_POST['isnew']) && $_POST['isnew'] == 1) {
		$isnew = 1;
	}

	if (isset($tags) && !empty($tags)) {
		$ids = getIDS($tags);
		return getSelectedAttractions($ids, $cityfile, $isnew);
	} else {
		return writeAllUserAttraction($cityfile, $isnew);
	}
}

function getIDS($ids) {
	$CI = &get_instance();
	$data = array();
	$CI->db->select('id');
	$CI->db->from('tbl_tag_master');
	for ($i = 0; $i < count($ids); $i++) {
		$CI->db->or_where('tag_name', trim($ids[$i]));
	}
	$Q = $CI->db->get();
	if ($Q->num_rows() > 0) {
		foreach ($Q->result_array() as $row) {
			$data[] = $row;
		}
	}
	$array = array_column($data, 'id');
	return $array;
}

function getSelectedAttractions($ids, $city_id, $isnew) {
	$CI = &get_instance();
	$c = 0;
	$key2array = array();
	$key2key = '';

	$attraction_json = file_get_contents(FCPATH . 'userfiles/attractionsfiles_taxidio/' . $city_id);
	$attractionarr_decode = json_decode($attraction_json, TRUE);
	$attraction_decode = otherAttractions($ids, $attractionarr_decode, $city_id);

	$attraction_decode = haversineGreatCircleDistance($attraction_decode);

	$finalsort = array();
	foreach ($attraction_decode as $k => $v) {
		$finalsort['distance'][$k] = $v['distance'];
		$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
	}
	array_multisort($finalsort['distance'], SORT_ASC, $finalsort['tag_star'], SORT_DESC, $attraction_decode);

	if (count($attraction_decode)) {
		foreach ($attraction_decode as $key => $attlist) {
			$ints = explode(',', $attlist['properties']['knownfor']);
			$intersectionofatt = array_intersect($ids, $ints);

			if ($isnew === 1) {
				if (count($intersectionofatt) > 0 && ($attlist['properties']['tag_star'] == 1 || $attlist['properties']['tag_star'] == 2)) {
					$attraction_decode[$key]['isselected'] = 1;
					$attraction_decode[$key]['order'] = $key;
				} else {
					$attraction_decode[$key]['isselected'] = 0;
					$attraction_decode[$key]['order'] = 99999;
				}
				$attraction_decode[$key]['tempremoved'] = 0;
			} else {
				if (count($intersectionofatt) > 0 || $attlist['properties']['tag_star'] == 1 || $attlist['properties']['tag_star'] == 2) {
					$attraction_decode[$key]['isselected'] = 1;
					$attraction_decode[$key]['order'] = $key;
					$attraction_decode[$key]['tempremoved'] = 0;
				} else {
					$attraction_decode[$key]['isselected'] = 0;
					$attraction_decode[$key]['order'] = 99999;
					$attraction_decode[$key]['tempremoved'] = 0;
				}
			}
		}

	}
//print_r($attraction_decode);die;
	return $attraction_decode; //json_encode($attraction_decode);

}

function writeAllUserAttraction($city_id, $isnew) {
	$CI = &get_instance();
	$c = 0;
	$key2array = array();
	$key2key = '';

	$attraction_json = file_get_contents(FCPATH . 'userfiles/attractionsfiles_taxidio/' . $city_id);
	$attractionarr_decode = json_decode($attraction_json, TRUE);

	$attraction_decode = mergeOtherAttractions($attractionarr_decode, $city_id);

	$attraction_decode = haversineGreatCircleDistance($attraction_decode);

	$finalsort = array();
	foreach ($attraction_decode as $k => $v) {
		$finalsort['distance'][$k] = $v['distance'];
		$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
	}
	array_multisort($finalsort['distance'], SORT_ASC, $finalsort['tag_star'], SORT_DESC, $attraction_decode);

	//print_r($attraction_decode);die;

	foreach ($attraction_decode as $k => $v) {
		if ($isnew === 1) {
			if ($v['properties']['tag_star'] == 1 || $v['properties']['tag_star'] == 2) {
				$attraction_decode[$k]['isselected'] = 1;
			} else {
				$attraction_decode[$k]['isselected'] = 0;
			}
		} else {
			$attraction_decode[$k]['isselected'] = 1;
		}
		$attraction_decode[$k]['tempremoved'] = 0;
		$attraction_decode[$k]['order'] = $k;
	}
	//return $attraction_decode;//json_encode($attraction_decode);
	$sort = array();
	foreach ($attraction_decode as $k => $v) {
		$sort['isselected'][$k] = $v['isselected'];
		$sort['order'][$k] = $v['order'];
		$sort['tag_star'][$k] = $v['properties']['tag_star'];
	}
	array_multisort($sort['isselected'], SORT_DESC, $sort['order'], SORT_ASC, $attraction_decode);

	return $attraction_decode;
}

function otherAttractions($ids, $attraction_decode, $city_id) {
	$attraction_decode_rel = $attraction_decode;

	/* Start Relaxation and spa */

	$relaxation_decode = array();
	$relax_decode = array();
	if (file_exists(FCPATH . 'userfiles/relaxationspa/' . $city_id)) {
		$relaxation_json = file_get_contents(FCPATH . 'userfiles/relaxationspa/' . $city_id);
		$relax_decode = json_decode($relaxation_json, TRUE);
	}

	if (in_array(17, $ids)) {
		$relaxation_decode = $relax_decode;
	} else {
		if (count($relax_decode)) {
			$relaxation_decode = getSelectedKeys($relax_decode);
		}
	}

	if (count($relaxation_decode)) {
		$attraction_decode_rel = array_merge($attraction_decode, $relaxation_decode);
	}
	/* End Of Relaxation and spa */

	$attraction_decode_spo = $attraction_decode_rel;

	/* Start Sport and Adventures and Stadiums */
	$sport_decode = array();
	$stadium_decode = array();
	$adv_decode = array();
	$adv_decode_temp = array();

	if (file_exists(FCPATH . 'userfiles/sport/' . $city_id)) {
		$sport_json = file_get_contents(FCPATH . 'userfiles/sport/' . $city_id);
		$sport_decode = json_decode($sport_json, TRUE);
	}
	if (file_exists(FCPATH . 'userfiles/stadium/' . $city_id)) {
		$stadium_json = file_get_contents(FCPATH . 'userfiles/stadium/' . $city_id);
		$stadium_decode = json_decode($stadium_json, TRUE);
	}

	if (count($sport_decode) && count($stadium_decode)) {
		$adv_decode_temp = array_merge($sport_decode, $stadium_decode);
	} else if (count($sport_decode) && !count($stadium_decode)) {
		$adv_decode_temp = $sport_decode;
	} else if (!count($sport_decode) && count($stadium_decode)) {
		$adv_decode_temp = $stadium_decode;
	}

	if (in_array(12, $ids)) {
		$adv_decode = $adv_decode_temp;
	} else {
		$adv_decode = getSelectedKeys($adv_decode_temp);
	}
	if (count($adv_decode)) {
		$attraction_decode_spo = array_merge($attraction_decode_rel, $adv_decode);
	}
	$attraction_decode_res = $attraction_decode_spo;

	/* End Sport and Adventures and Stadiums */

	/* Start Restaurant */

	$restaurant_decode = array();
	$res_decode = array();
	if (file_exists(FCPATH . 'userfiles/restaurant/' . $city_id)) {
		$restaurant_json = file_get_contents(FCPATH . 'userfiles/restaurant/' . $city_id);
		$res_decode = json_decode($restaurant_json, TRUE);
	}

	if (in_array(15, $ids)) {
		$restaurant_decode = $res_decode;
	} else {
		if (count($relax_decode)) {
			$restaurant_decode = getSelectedKeys($res_decode);
		}
	}

	if (count($restaurant_decode)) {
		$attraction_decode_res = array_merge($attraction_decode_spo, $restaurant_decode);
	}

	/* End Restaurant */
	return $attraction_decode_res;

}

function haversineGreatCircleDistance($attraction_decode) {
	require_once FCPATH . 'travel/tsp.php';
	$tsp = TspBranchBound::getInstance();
	foreach ($attraction_decode as $key => $list) {

		$lat = $list['geometry']['coordinates'][1];
		$lng = $list['geometry']['coordinates'][0];
		$tsp->addLocation(array('id' => $key, 'latitude' => $lat, 'longitude' => $lng));
	}

	$sortedArray = $tsp->solve();
	$sortkeys = array();
	foreach ($sortedArray as $value) {

		foreach ($value as $key => $list) {
			$sortkeys[] = $list[0];
		}
	}

	//print_r($attraction_decode);die;
	//print_r($sortkeys);die;
	$finalarray = array();
	foreach ($sortkeys as $key => $list) {
		$finalarray[] = $attraction_decode[$list];
		$finalarray[$key]['distance'] = $key;
	}
	return $finalarray;

}

function mergeOtherAttractions($attraction_decode, $city_id) {
	$CI = &get_instance();

	$relaxation_decode = array();

	$attraction_decode_rel = array_values($attraction_decode);
	$relaxation_decode = array();
	if (file_exists(FCPATH . 'userfiles/relaxationspa/' . $city_id)) {
		$relaxation_json = file_get_contents(FCPATH . 'userfiles/relaxationspa/' . $city_id);
		$relaxation_decode = json_decode($relaxation_json, TRUE);
	}

	if (count($relaxation_decode)) {
		$attraction_decode_rel = array_merge($attraction_decode, $relaxation_decode);
	}

	$attraction_decode_spo = $attraction_decode_rel;
	$sport_decode = array();
	$stadium_decode = array();
	$adv_decode = array();
	if (file_exists(FCPATH . 'userfiles/sport/' . $city_id)) {
		$sport_json = file_get_contents(FCPATH . 'userfiles/sport/' . $city_id);
		$sport_decode = json_decode($sport_json, TRUE);
	}

	if (file_exists(FCPATH . 'userfiles/stadium/' . $city_id)) {
		$stadium_json = file_get_contents(FCPATH . 'userfiles/stadium/' . $city_id);
		$stadium_decode = json_decode($stadium_json, TRUE);
	}

	if (count($sport_decode) && count($stadium_decode)) {
		$adv_decode = array_merge($sport_decode, $stadium_decode);

	} else if (count($sport_decode) && !count($stadium_decode)) {
		$adv_decode = $sport_decode;
	} else if (!count($sport_decode) && count($stadium_decode)) {
		$adv_decode = $stadium_decode;
	}

	if (count($adv_decode)) {
		$attraction_decode_spo = array_merge($attraction_decode_rel, $adv_decode);
	}

	$attraction_decode_res = $attraction_decode_spo;
	$restaurant_decode = array();
	if (file_exists(FCPATH . 'userfiles/restaurant/' . $city_id)) {
		$restaurant_json = file_get_contents(FCPATH . 'userfiles/restaurant/' . $city_id);
		$restaurant_decode = json_decode($restaurant_json, TRUE);
	}
	if (count($restaurant_decode)) {
		$attraction_decode_res = array_merge($attraction_decode_spo, $restaurant_decode);
	}

	return $attraction_decode_res;

}

function changedateformate($date, $format) {
	$date = str_replace('/', '-', $date);
	return date($format, strtotime($date));
}
function get_invited_trip_details_wm($itinerary_id) {
	$CI = &get_instance();
	$CI->db->select('*');
	$CI->db->from('tbl_share_itineraries');
	$CI->db->where('invited_user_id', $_POST['userid']);
	$CI->db->where('itinerary_id', $itinerary_id);
	$Q = $CI->db->get();

	if ($Q->num_rows() > 0) {
		return $Q->row_array();
	}
	return FALSE;
}

?>