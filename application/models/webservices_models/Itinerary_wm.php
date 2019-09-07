<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Itinerary_wm extends CI_Model
{

    function countTotalPublicItineraries()
    {
        $this->db->select('id');
        $this->db->from('tbl_itineraries');
        $this->db->where('trip_mode',2);
        if(isset($_POST['country_id']) && $_POST['country_id']>0)
        {
          $countryname=$this->getCountryName($_POST['country_id']);
          if(count($countryname))
          {
            $this->db->group_start()
                      ->where('tbl_itineraries.country_id',$_POST['country_id'])
                      ->or_like('citiorcountries',$countryname['country_name'],'both')
             ->group_end();

          }
        }
        $Q=$this->db->get();
        return $Q->num_rows();
    }

    function getCountryName($countrynm)
    {
      $Q=$this->db->query('select country_name from tbl_country_master where id="'.$_POST['country_id'].'"');
      return $Q->row_array();
    }

    function getTotalPublicItineraries($limit,$start)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,user_trip_name,name,trip_type,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as countryname,citiorcountries,cities,tbl_itineraries.country_id,slug,(select count(id) from tbl_itinerary_questions where tbl_itinerary_questions.itinerary_id=tbl_itineraries.id) as totalquestions,tbl_itineraries.user_id,views,rating');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
        $this->db->where('trip_mode',2);
        //$this->db->where('tbl_itineraries.id',53);
        if(isset($_POST['country_id']) && $_POST['country_id']>0)
        {
          $countryname=$this->getCountryName($_POST['country_id']);
          if(count($countryname))
          {
            $this->db->group_start()
                      ->where('tbl_itineraries.country_id',$_POST['country_id'])
                      ->or_like('citiorcountries',$countryname['country_name'],'both')
             ->group_end();

          }
        }
        $this->db->order_by('id','DESC');
        $this->db->limit($limit,$start);
        $Q=$this->db->get();
        $data=$Q->result_array();
        //echo "<pre>";print_r($data);die;
        foreach($data as $key=>$list)
        {
          $singlecountry_array=array();
          $update_array=array();
          $oneDimensionalArray =array();
          $citiesformulticountry='';

          $input_decode=json_decode($list['inputs'],TRUE);
          if($list['trip_type']!=2)
          {
               $singlecountry=json_decode($list['singlecountry'],TRUE);
               if($list['trip_type']==3)
               {
                     $random_key = array_rand($singlecountry);
                     $random_city_id = $singlecountry[$random_key]['id'];
               }
               else
               {
                     if(count($singlecountry)>1)
                     {
                        $country_id=$list['country_id'];
                        $singlecountry_array[$country_id]=$singlecountry[$country_id];
                        $update_array[$country_id]=$singlecountry[$country_id];
                        $this->db->where('id',$list['id']);
                        $this->db->update('tbl_itineraries',array('singlecountry'=>json_encode($update_array)));
                     }
                     else
                     {
                        $singlecountry_array=$singlecountry;
                     }

                     $oneDimensionalArray = call_user_func_array('array_merge', $singlecountry_array);
                     $random_key = array_rand($oneDimensionalArray);
                     $random_city_id = $oneDimensionalArray[$random_key]['id'];
               }

          }
          else
          {
               $cities=json_decode($list['cities'],TRUE);
               if(count($cities))
               {
                 foreach($cities as $citylist)
                 {
                   //$isnew=0;
                   foreach($citylist as $checkkey=>$finallist)
                   {
                     if($checkkey==count($citylist)-1)
                     {
                       $citiesformulticountry .=$finallist['city_name'];
                     }
                     else {
                       $citiesformulticountry .=$finallist['city_name'].',';
                     }

                   }
                   $citiesformulticountry .='-';


                 }
                 $citiesformulticountry=rtrim($citiesformulticountry,'-');

               }
               $oneDimensionalArray = call_user_func_array('array_merge', $cities);
               //echo "<pre>";print_r($cities);die;
               $random_key = array_rand($oneDimensionalArray);
               $random_city_id = $oneDimensionalArray[$random_key]['id'];
          }
          $data[$key]['citiesformulticountry']=$citiesformulticountry;
          $data[$key]['image']=$this->getCityImage($random_city_id);
          if(isset($input_decode['sstart_date']) && $input_decode['sstart_date']!='')
          {
            $sstart_date=implode("-", array_reverse(explode("/",$input_decode['sstart_date'])));
            $start_date=date('dS M Y',strtotime($sstart_date));
            $days=$input_decode['sdays'];
            $daysoneminus=$days-1;
            $end_date=date('dS M Y',date(strtotime("+$daysoneminus day", strtotime($sstart_date))));

          }
          else
          {

            $sstart_date=implode("-", array_reverse(explode("/",$input_decode['start_date'])));
            $start_date=date('dS M Y',strtotime($sstart_date));
            $days=$input_decode['days'];
            $daysoneminus=$days-1;
            $end_date=date('dS M Y',date(strtotime("+$daysoneminus day", strtotime($sstart_date))));
          }

          $data[$key]['start_date']=$start_date;
          $data[$key]['end_date']=$end_date;
          $data[$key]['days']=$days;

        }
        //echo "<pre>";print_r($data);die;
        return $data;
    }

    function getRandomImage($random_city_id)
    {
           $this->db->select('image');
           $this->db->from('tbl_city_paidattractions');
           $this->db->where('city_id',$random_city_id);
           $this->db->where('image !=','');
           $this->db->order_by('id','RANDOM');
           $QImg=$this->db->get();
           $img=$QImg->row_array();
           if(count($img))
           {
              $data=$img['image'];
           }
           else
           {
             $data=$this->getCityImage($random_city_id);
           }
           return $data;
    }

    function getCityImage($random_city_id)
    {
       $Q=$this->db->query('select cityimage from tbl_city_master where id="'.$random_city_id.'"');
       $data=$Q->row_array();
       return $data['cityimage'];
    }

    function getItineraryInfo($trip)
    {
        $this->db->select('id,trip_type,trip_mode,user_trip_name,user_id');
        $this->db->from('tbl_itineraries');
        $this->db->where('slug',$trip);
        $this->db->where('trip_mode',2);
        $Q=$this->db->get();
        return $Q->row_array();
    }

    function getSingleCountryItineraryDetails($iti)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,trip_type,user_trip_name,city_id,country_id');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itineraries_cities','tbl_itineraries_cities.itinerary_id=tbl_itineraries.id');
        $this->db->where('tbl_itineraries.id',$iti);
        $Q=$this->db->get();
        if($Q->num_rows()>0)
        {
            foreach ($Q->result_array() as $row)
            {
              $data[]=$row;
            }
        }
        //echo "<pre>";print_r($data);die;
        return $data;
    }

    function getCountryNameFromSlug($id)
  	{
  		$data=array();
  		$Q=$this->db->query('select country_name,country_conclusion,countryimage,slug from tbl_country_master where id="'.$id.'"');
  		$data=$Q->row_array();
  		return $data;
  	}


    function getCitiesAttractions($city_id,$itinerary_id,$table)
    {
       $data=array();
       $Q=$this->db->query('select city_attractions from '.$table.' where city_id="'.$city_id.'" and itinerary_id="'.$itinerary_id.'"');
       $data=$Q->row_array();
       return json_decode($data['city_attractions'],true);
    }

    function getSearchedCityItineraryDetails($iti)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,trip_type,user_trip_name,city_id,country_id');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itineraries_searched_cities','tbl_itineraries_searched_cities.itinerary_id=tbl_itineraries.id');
        $this->db->where('tbl_itineraries.id',$iti);
        $Q=$this->db->get();
        if($Q->num_rows()>0)
        {
            foreach ($Q->result_array() as $row)
            {
              $data[]=$row;
            }
        }
        //echo "<pre>";print_r($data);die;
        return $data;
    }

    function getAllMultiCountries1($iti)
    {
        $data=array();
        $this->db->select('tbl_country_master.id,country_name');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries.id');
        $this->db->join('tbl_itineraries_multicountries','tbl_itineraries_multicountries.combination_id=tbl_itineraries_multicountrykeys.id');
        $this->db->join('tbl_country_master','tbl_country_master.id=tbl_itineraries_multicountries.country_id');
        $this->db->where('tbl_itineraries.id',$iti);
        $Q=$this->db->get();
        return $Q->result_array();


    }

    function getAllMultiCountries($iti)
    {
        $data=array();
        $Q=$this->db->query('select trip_type,cities,multicountries from tbl_itineraries where id="'.$iti.'"');
        return $Q->row_array();

    }

    function getCitiesAttractionsMultiCountry($city_id,$itineraryid)
    {

      $data=array();
      $this->db->select('attractions');
      $this->db->from('tbl_itineraries');
      $this->db->join('tbl_itineraries_multicountrykeys','tbl_itineraries_multicountrykeys.itineraries_id=tbl_itineraries.id');
      $this->db->join('tbl_itineraries_multicountries','tbl_itineraries_multicountries.combination_id=tbl_itineraries_multicountrykeys.id');
      $this->db->join('tbl_itineraries_multicountries_cities','tbl_itineraries_multicountries_cities.country_combination_id=tbl_itineraries_multicountries.id');
      $this->db->where('tbl_itineraries_multicountries_cities.city_id',$city_id);
      $this->db->where('tbl_itineraries.id',$itineraryid);
      $Q=$this->db->get();
      $data=$Q->row_array();
      return json_decode($data['attractions'],true);

    }

    function checkItineraryExist($id)
    {
       $Q=$this->db->query('select id from tbl_itineraries where id="'.$id.'" and trip_mode=2 limit 1');
       return $Q->num_rows();
    }

    function countTotalQuestionOfItinerary($iti)
    {
        $Q=$this->db->query('select id from tbl_itinerary_questions where itinerary_id="'.$iti.'"' );
        return $Q->num_rows();
    }

    function getQuestionOfItinerary($iti,$limit,$start)
    {
         $this->db->select('tbl_itinerary_questions.id,question,tbl_itinerary_questions.created,userimage,socialimage,name,(select count(id) from tbl_itinerary_answers where tbl_itinerary_answers.question_id=tbl_itinerary_questions.id) as totalcomments,user_id,posted_by');
        $this->db->from('tbl_itinerary_questions');
        $this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itinerary_questions.itinerary_id');
        $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itinerary_questions.posted_by','LEFT');
        //$this->db->join('tbl_itinerary_answers','tbl_itinerary_answers.question_id=tbl_itinerary_questions.id','LEFT');
        $this->db->where('tbl_itineraries.id',$iti);
        $this->db->order_by('id','DESC');
        $this->db->limit($limit,$start);
        $Q=$this->db->get();
        return $Q->result_array();
    }

    function getAllPublicItinerariesCountry()
    {
        $this->db->select('country_id');
        $this->db->from('tbl_itineraries');
        $this->db->where('trip_mode',2);
        $this->db->where('trip_type !=',2);
        $this->db->distinct();
        $Q=$this->db->get();
        $mode1or3=$Q->result_array();

        $Q1=$this->db->query('select distinct citiorcountries from tbl_itineraries where trip_mode=2 and trip_type=2');
        $countries=array_unique(explode('-',implode('-',array_column($Q1->result_array(),'citiorcountries'))));

        $this->db->select('id');
        $this->db->from('tbl_country_master');
        $this->db->where_in('country_name',$countries);
        $qr=$this->db->get();

        $arr1=array_column($mode1or3,'country_id');
        $arr2=array_column($qr->result_array(),'id');
        $arr3=array();

        if(count($arr1) && count($arr2))
        {
          $arr3=array_values(array_unique(array_merge($arr1,$arr2)));
        }
        else if(count($arr1) && count($arr2)<1)
        {
          $arr3=$arr1;
        }
        else if(count($arr1)<1 && count($arr2))
        {
          $arr3=$arr2;
        }

        $this->db->select('id,country_name');
        $this->db->from('tbl_country_master');
        $this->db->where_in('id',$arr3);
        $this->db->order_by('country_name','ASC');
        $query=$this->db->get();
        return $query->result_array();
    }

    function copy_itinerary()
    {
        $Q=$this->db->query('select * from tbl_itineraries where id not in(select itinerary_id from tbl_copy_trips where tbl_copy_trips.itinerary_id=tbl_itineraries.id and user_id="'.$_POST['userid'].'") and id="'.$_POST['itirnaryid'].'" and trip_mode=2');
        if($Q->num_rows()>0)
        {
            $data=$Q->row_array();
            //  print_r($data);die;
            if($data['trip_type']==1)
            {
               $lastid=$this->copy_SingleCountryItinerary($data);
               if($lastid)
               {
                  return 1;
               }
               return 0;
            }
            else if($data['trip_type']==2)
            {
              $lastid=$this->copy_MultiCountryItinerary($data);
               if($lastid)
               {
                  return 1;
               }
               return 0;
            }
            else if($data['trip_type']==3)
            {
              $lastid=$this->copy_SearchedCityItinerary($data);
               if($lastid)
               {
                  return 1;
               }
               return 0;
            }
            return 0;
        }
        else
        {
           return 0;
        }
    }

    function copy_SingleCountryItinerary($itidata)
    {
          $tripname_main=$this->Trip_wm->getContinentCountryName($itidata['country_id']);
           $slug=$this->Account_wm->generateItiSlug('Trip '.$tripname_main['country_name']);
           $uniqueid=time();
          $inputs_decode=json_decode($itidata['inputs'],TRUE);
           $inputs_decode['token']=$uniqueid;
          $copyitidata=array(
            'user_id'=>$_POST['userid'],
            'sess_id'=>'app',
            'trip_type'=>$itidata['trip_type'],
            'inputs'=>json_encode($inputs_decode),
            'singlecountry'=>$itidata['singlecountry'],
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s'),
            'tripname'=>$itidata['tripname'],
            'country_id'=>$itidata['country_id'],
            'multicountries'=>$itidata['multicountries'],
            'cities'=>$itidata['cities'],
            'uniqueid'=>$uniqueid,
            'user_trip_name'=>'Trip '.$tripname_main['country_name'],
            'citiorcountries'=>$itidata['citiorcountries'],
            'trip_mode'=>1,
            'slug'=>$slug,
            'isblock'=>0
          );
          $this->db->insert('tbl_itineraries',$copyitidata);
          $lastid=$this->db->insert_id();

          $Q=$this->db->query('select * from tbl_itineraries_cities where itinerary_id="'.$itidata['id'].'"');
          foreach ($Q->result_array() as $row)
          {
              $citydata=array(
                'itinerary_id'=>$lastid,
                'city_id'=>$row['city_id'],
                'city_attractions'=>$row['city_attractions'],
              );
              $this->db->insert('tbl_itineraries_cities',$citydata);
          }
         $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$_POST['userid']));
         return $lastid;
    }

    function copy_MultiCountryItinerary($itidata)
    {
          $tripname_main=$this->Trip_wm->getContinentName($itidata['tripname']);
          $slug=$this->Account_wm->generateItiSlug('Trip '.$tripname_main['country_name']);

          $copyitidata=array(
            'user_id'=>$_POST['userid'],
            'sess_id'=>'app',
            'trip_type'=>$itidata['trip_type'],
            'inputs'=>$itidata['inputs'],
            'singlecountry'=>$itidata['singlecountry'],
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s'),
            'tripname'=>$itidata['tripname'],
            'country_id'=>$itidata['country_id'],
            'multicountries'=>$itidata['multicountries'],
            'cities'=>$itidata['cities'],
            'uniqueid'=>time(),
            'user_trip_name'=>'Trip '.$tripname_main['country_name'],
            'citiorcountries'=>$itidata['citiorcountries'],
            'trip_mode'=>1,
            'slug'=>$slug,
            'isblock'=>0
          );
          $this->db->insert('tbl_itineraries',$copyitidata);
          $lastid=$this->db->insert_id();

          $Q=$this->db->query('select * from tbl_itineraries_multicountrykeys where itineraries_id="'.$itidata['id'].'"');
          $combination=$Q->row_array();
          $combination_id=$combination['id'];
          $combinationdata=array(
            'itineraries_id'=>$lastid,
            'combination_key'=>$combination['combination_key'],
          );
          $this->db->insert('tbl_itineraries_multicountrykeys',$combinationdata);
          $last_key_id=$this->db->insert_id();

          $QComb=$this->db->query('select * from tbl_itineraries_multicountries where combination_id="'.$combination_id.'"');

          foreach($QComb->result_array() as $row)
          {
              $multicountries=array(
                'country_id'=>$row['country_id'],
                'combination_id'=>$last_key_id,
              );
              $this->db->insert('tbl_itineraries_multicountries',$multicountries);
              $last_combination_id=$this->db->insert_id();

              $QCities=$this->db->query('select * from tbl_itineraries_multicountries_cities where country_combination_id="'.$row['id'].'"');

              foreach ($QCities->result_array() as $cityrow)
              {
                $cities=array(
                  'city_id'=>$cityrow['city_id'],
                  'attractions'=>$cityrow['attractions'],
                  'country_combination_id'=>$last_combination_id,
                );
                $this->db->insert('tbl_itineraries_multicountries_cities',$cities);
              }

          }
         $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$_POST['userid']));
         return $lastid;
    }

    function copy_SearchedCityItinerary($itidata)
    {
        $tripname_main=$this->Trip_wm->getContinentCountryName($itidata['country_id']);
        $slug=$this->Account_wm->generateItiSlug('Trip '.$tripname_main['country_name']);
       
        $copyitidata=array(
          'user_id'=>$_POST['userid'],
          'sess_id'=>'app',
          'trip_type'=>$itidata['trip_type'],
          'inputs'=>$itidata['inputs'],
          'singlecountry'=>$itidata['singlecountry'],
          'created'=>date('Y-m-d H:i:s'),
          'modified'=>date('Y-m-d H:i:s'),
          'tripname'=>$itidata['tripname'],
          'country_id'=>$itidata['country_id'],
          'multicountries'=>$itidata['multicountries'],
          'cities'=>$itidata['cities'],
          'uniqueid'=>time(),
          'user_trip_name'=>'Trip '.$tripname_main['country_name'],
          'citiorcountries'=>$itidata['citiorcountries'],
          'trip_mode'=>1,
          'slug'=>$slug,
          'isblock'=>0
        );
        $this->db->insert('tbl_itineraries',$copyitidata);
        $lastid=$this->db->insert_id();

        $Q=$this->db->query('select * from tbl_itineraries_searched_cities where itinerary_id="'.$itidata['id'].'"');
        foreach ($Q->result_array() as $row)
        {
            $citydata=array(
              'itinerary_id'=>$lastid,
              'city_id'=>$row['city_id'],
              'city_attractions'=>$row['city_attractions'],
              'ismain'=>$row['ismain'],
            );
            $this->db->insert('tbl_itineraries_searched_cities',$citydata);
        }
       $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$_POST['userid']));
       return $lastid;

    }

    function checkTripSavedStatus($id,$itinerary_userid)
    {
       $Q=$this->db->query('select id from tbl_copy_trips where itinerary_id="'.$id.'" and user_id="'.$_POST['userid'].'" limit 1');
       if($Q->num_rows())
       {
          return 1;
       }
       elseif ($_POST['userid']==$itinerary_userid) 
       {
          return 2;
       }
       return 0;
    }

    function updateViews($iti)
    {
       $this->db->set('views','views+1',FALSE);
       $this->db->where('id',$iti);
       $this->db->update('tbl_itineraries');
    }

}

?>
