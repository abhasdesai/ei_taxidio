<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Trip_wm extends CI_Model
{
	function countTrips()
	{
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$_POST['userid']);
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function getUserTrips($limit,$start)
	{
		$data=array();
		//$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->select('tbl_itineraries.id,inputs,trip_type,tripname,country_id,user_trip_name,citiorcountries,slug,isblock,views,rating,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$_POST['userid']);
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

	function getUserTrip()
	{
		$data=array();
		$userid=$_POST['userid'];
		$itirnaryid=$_POST['itirnaryid'];
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$userid);
		$this->db->where('id',$itirnaryid);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$row=$Q->row_array();
			
			$row['inputs']=json_decode($row['inputs'],TRUE);
			$row['singlecountry']=json_decode($row['singlecountry'],TRUE);
			$row['multicountries']=json_decode($row['multicountries'],TRUE);
			if(count($row['singlecountry']))
			{
				if($row['trip_type']==1)
				{
					if(isset($row['inputs']['tags']) && !empty($row['inputs']['tags'])){
						$row['inputs']['tags']=json_encode($row['inputs']['tags']);
					}
					else
					{
						$row['inputs']['tags']=json_encode(array());
					}
					foreach ($row['singlecountry'] as $key => $city) {
						$row['singlecountry']=$city;
						//unset($row['singlecountry'][$key]);
						foreach ($city as $key => $value) {

							$cityid = $value['id'];
							$city=getLatandLongOfCity($cityid);
							$row['singlecountry'][$key]['cityimage']=$city['cityimage'];
							$row['singlecountry'][$key]['latitude']=$city['citylatitude'];
							$row['singlecountry'][$key]['longitude']=$city['citylongitude'];
							$condition="city_id=$cityid and itinerary_id=$itirnaryid";
							$data=getrowbycondition('city_attractions','tbl_itineraries_cities',$condition);
							$row['singlecountry'][$key]['filestore']=json_decode($data['city_attractions'],TRUE);							
						}
					}
					//print_r($row['singlecountry'][2]['filestore']);die;
				}
				elseif ($row['trip_type']==3) 
				{
					$row['search_city_inputs']=$row['inputs'];
					
					if(isset($row['inputs']['searchtags']) && !empty($row['inputs']['searchtags'])){
						$row['search_city_inputs']['searchtags']=json_encode($row['inputs']['searchtags']);
					}
					else
					{
						$row['search_city_inputs']['searchtags']=json_encode(array());
					}
					unset($row['inputs']);
					foreach ($row['singlecountry'] as $key => $city) {
						$cityid = $city['id'];
						$city=getLatandLongOfCity($cityid);
						$row['singlecountry'][$key]['cityimage']=$city['cityimage'];
						$row['singlecountry'][$key]['latitude']=$city['citylatitude'];
						$row['singlecountry'][$key]['longitude']=$city['citylongitude'];
						$condition="city_id=$cityid and itinerary_id=$itirnaryid";
						$data=getrowbycondition('ismain,city_attractions','tbl_itineraries_searched_cities',$condition);
						//echo $this->db->last_query();
						$row['singlecountry'][$key]['ismain']=$data['ismain'];
						$row['singlecountry'][$key]['filestore']=json_decode($data['city_attractions'],TRUE);
					}
				}
				$selectcol="country_conclusion,countryimage,slug";
				$country_id=$row['country_id'];
				$country_details=getrowbycondition($selectcol,"tbl_country_master","id=$country_id");
				$row['noofcities']=getcountrynoofCities($country_id);
				$row['slug']=$country_details['slug'];
				$row['countryimage']=$country_details['countryimage'];
				$row['country_conclusion']=$country_details['country_conclusion'];
			}
			elseif (count($row['multicountries']) && !empty($row['cities'])) 
			{
					if(isset($row['inputs']['tags']) && !empty($row['inputs']['tags'])){
						$row['inputs']['tags']=json_encode($row['inputs']['tags']);
					}
					else
					{
						$row['inputs']['tags']=json_encode(array());
					}
				$this->load->model('webservices_models/Itinerary_wm');
				$countries=$row['multicountries'];
				$row['encryptkey']=$countries[0]['encryptkey'];
				unset($countries[0]['encryptkey']);
				ksort($countries[0]);
				//print_r($countries[0]);die;
				$cities=json_decode($row['cities'],TRUE);
				//$i=0;
				foreach($countries[0] as $key=>$value)
				{
						$countries[$key]=$value;
						$country_id=$value['country_id'];
						$countries[$key]['noofcities']=getcountrynoofCities($country_id);
						if(array_key_exists($country_id,$cities))
						{
							foreach ($cities[$country_id] as $j => $city) {
								$countries[$key]['cityData'][$j]=$city;
								$cityid=$city['id'];
								$city=getLatandLongOfCity($cityid);
								$countries[$key]['cityData'][$j]['cityimage']=$city['cityimage'];
								$countries[$key]['cityData'][$j]['latitude']=$city['citylatitude'];
								$countries[$key]['cityData'][$j]['longitude']=$city['citylongitude'];
								$city_attractions=$this->Itinerary_wm->getCitiesAttractionsMultiCountry($cityid,$itirnaryid);
								$countries[$key]['cityData'][$j]['filestore']=$city_attractions;
							}
						}
				}
				$row['multicountries']=$countries;
				unset($row['cities']);
			}
		}
		return $row;
	}
/*
	function getOnlySelectedAttractions($data)
	{
		$arr_result=[];
		foreach($data as $key => $value){
		    if($value['isselected'] === 1){
		        $arr_result[] = $data[$key];
		    }
		}
		return $arr_result;
	}
*/
	function deleteTrip($tripid)
	{
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
			return 1;
		}
		else
		{
			return 0;
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
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->where('id',$id);
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

	function getContinentCountryName($country_id)
	{
		$Q=$this->db->query('select country_name from tbl_country_master where id="'.$country_id.'"');
		return $Q->row_array();
	}

	function updateTrip()
	{
		$data=array();
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->where('user_id',$_POST['userid']);
		$this->db->where('id',$_POST['itirnaryid']);
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
					'start_date'=>strtotime(str_replace('/', '-', $_POST['start_date'])),
					'end_date'=>strtotime(str_replace('/', '-', $_POST['end_date'])),
					'sort_planned_iti'=>99999
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
			$slug = $this->slug->create_uri($slugdata,$_POST['itirnaryid']);
			$dataToUpdate['slug'] = $slug;

			$this->db->where('id',$_POST['itirnaryid']);
			$this->db->where('user_id',$_POST['userid']);
			$this->db->update('tbl_itineraries',$dataToUpdate);
			return 1;
		}
		else
		{
			return 0;
		}

	}

}


?>
