<?php

class Itinerary_fm extends CI_Model
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

    function getTotalPublicItineraries($limit,$start,$is_homepage=FALSE)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,tbl_itineraries.created,user_trip_name,name,trip_type,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as countryname,citiorcountries,cities,tbl_itineraries.country_id,slug,(select count(id) from tbl_itinerary_questions where tbl_itinerary_questions.itinerary_id=tbl_itineraries.id) as totalquestions,userimage,googleid,facebookid,name,tbl_itineraries.user_id,views,rating');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
        $this->db->where('trip_mode',2);
        //$this->db->where('tbl_itineraries.id',53);
        if(isset($_POST['country_id']) && $_POST['country_id']>0)
        {
          //echo $_POST['country_id'];die;
          $countryname=$this->getCountryName($_POST['country_id']);
          if(count($countryname))
          {
            $this->db->group_start()
                      ->where('tbl_itineraries.country_id',$_POST['country_id'])
                      ->or_like('citiorcountries',$countryname['country_name'],'both')
             ->group_end();

          }
        }
        
        if($is_homepage===FALSE)
        {
          $this->db->order_by('id','DESC');
        }
        else
        {
          $this->db->order_by("sort_planned_iti asc,id desc");
        }

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

    /*function getRandomImage($random_city_id)
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
    }*/

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
        if(!$this->session->userdata('role_id') && $this->session->userdata('role_id')!=1)
        {
          $this->db->where('trip_mode',2);
        }
        $Q=$this->db->get();
        return $Q->row_array();
    }

    function getSingleCountryItineraryDetails($iti)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,user_trip_name,city_id,country_id');
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
       return $data['city_attractions'];
    }

    function getSearchedCityItineraryDetails($iti)
    {
        $data=array();
        $this->db->select('tbl_itineraries.id,inputs,singlecountry,user_trip_name,city_id,country_id');
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
        $Q=$this->db->query('select cities,multicountries from tbl_itineraries where id="'.$iti.'"');
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
      $Q=$this->db->get();
      $data=$Q->row_array();
      return $data['attractions'];

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

    function getQuestionDetails($id)
  	{
      $this->db->select('tbl_itinerary_questions.id,question,tbl_itinerary_questions.created,userimage,name,itinerary_id,socialimage');
      $this->db->from('tbl_itinerary_questions');
      $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itinerary_questions.posted_by','LEFT');
      $this->db->where('tbl_itinerary_questions.id',$id);
      $Q=$this->db->get();
  		return $Q->row_array();
  	}

    function getAllComments($id)
  	{
  		$data=array();
  		$this->db->select('tbl_itinerary_answers.id,sender_id,name,userimage,tbl_itinerary_answers.created,comment,socialimage,user_id as owner_id');
  		$this->db->from('tbl_itinerary_answers');
  		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_itinerary_answers.sender_id','LEFT');
  		$this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.id=tbl_itinerary_answers.question_id');
      $this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itinerary_questions.itinerary_id');
  		$this->db->where('tbl_itinerary_questions.id',$id);
  		$Q=$this->db->get();
      return $Q->result_array();

  	}

    function countTotalComments($id)
  	{
  		$Q=$this->db->query('select id from tbl_itinerary_answers where question_id="'.$id.'"');
  		return $Q->num_rows();
  	}


    /*Add/Edit/Delete Comments*/

    function postComment()
  	{
  		$Q=$this->db->query('select posted_by,(select user_id from tbl_itineraries where id=tbl_itinerary_questions.itinerary_id) as iti_owner from tbl_itinerary_questions where id="'.$_POST['conversation_id'].'" and id="'.$_POST['iti'].'" limit 1');
  		if($Q->num_rows()>0)
  		{
  			$data=$Q->row_array();
  			$sender_id=$this->session->userdata('fuserid');
      	$receiver_id=$data['posted_by'];
  			$datetime=date('Y-m-d H:i:s');
  			$postdata=array(
  					'sender_id'=>$sender_id,
  					'receiver_id'=>$receiver_id,
  					'comment'=>$_POST['usercomment'],
            'iti_owner'=>$data['iti_owner'],
  					'question_id'=>$_POST['conversation_id'],
  					'created'=>$datetime,
        );
  			$this->db->insert('tbl_itinerary_answers',$postdata);
        $lastid=$this->db->insert_id();
        $res=$this->getLatestComments();
        $this->sendEmailToItiownerForComment($lastid);

  			return $res;
  		}
  		else
  		{
  			$res=array(
  					'status'=>'fail',
  				);
  			echo json_encode($res);
  		}



  	}

    function sendEmailToItiownerForComment($lastid)
    {
        $this->load->library('email');
        $this->load->library('MY_Email_Other');
        $this->db->select('question,comment,user_id,tbl_itinerary_questions.id,email');
        $this->db->from('tbl_itinerary_answers');
        $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.id=tbl_itinerary_answers.question_id');
        $this->db->join('tbl_itineraries','tbl_itineraries.id=tbl_itinerary_questions.itinerary_id');
        $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
        $this->db->where('tbl_itinerary_answers.id',$lastid);
        $Q=$this->db->get();
        $data=$Q->row_array();
        if($data['user_id']!=$this->session->userdata('fuserid'))
        {

          $subject=$this->session->userdata('name').' commented to '. word_limiter($data['question'],60);
          $message=$this->load->view('itineraries/sendEmailToItiownerForComment',$data,true);
          $from='noreply@taxidio.com';
          $this->email->from($from,'Taxidio');
          $this->email->subject($subject);
          /*
          if($this->session->userdata('isemail')!=1)
          {
            $this->email->reply_to($this->session->userdata('email'));
          }*/
          $this->email->to($data['email']);
          $this->email->message($message);
          $this->email->send();
        }

    }

    function getLatestComments()
  	{
  		$data=array();
  		$this->db->select('tbl_itinerary_answers.id,name,sender_id,userimage,tbl_itinerary_answers.created,comment,socialimage');
  		$this->db->from('tbl_itinerary_answers');
  		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_itinerary_answers.sender_id','LEFT');
  		$this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.id=tbl_itinerary_answers.question_id');
  		$this->db->where('tbl_itinerary_questions.id ',$_POST['conversation_id']);
  		if(isset($_POST['conversation_lastid']) && $_POST['conversation_lastid']!='')
  		{
  			$this->db->where('tbl_itinerary_answers.id >',$_POST['conversation_lastid']);
  		}

  		$Q=$this->db->get();
  		return $Q->result_array();
  	}

    function deleteComment()
  	{

      if($this->session->userdata('id')!='')
      {
           $this->db->where('id',$_POST['id']);
           $this->db->delete('tbl_itinerary_answers');
      }
      else
      {
          $iti_owner=$this->itineraryOwner();
          $Q=$this->db->query('select id from tbl_itinerary_answers where id="'.$_POST['id'].'" and sender_id="'.$this->session->userdata('fuserid').'" or receiver_id="'.$iti_owner.'" limit 1');
          if($Q->num_rows()>0)
          {
            $this->db->where('id',$_POST['id']);
            $this->db->group_start()
                      ->where('sender_id',$this->session->userdata('fuserid'))
                      ->or_like('iti_owner',$this->session->userdata('fuserid'))
             ->group_end();
            $this->db->delete('tbl_itinerary_answers');
          }
          else
          {
            $res=array(
                'status'=>'fail',
              );
            echo json_encode($res);
          }
      }
    }

    function deleteQuestion()
    {
        $iti_owner=$this->itineraryOwner();
        if($iti_owner!=0 || $this->session->userdata('id'))
        {
            $this->db->where('id',$_POST['id']);
            $this->db->delete('tbl_itinerary_questions');

            $this->db->where('question_id',$_POST['id']);
            $this->db->delete('tbl_itinerary_answers');
        }
        else
        {
            $res=array(
                'status'=>'fail',
              );
            echo json_encode($res);
        }

    }

    function itineraryOwner_comment()
    {
        $this->db->select('user_id');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
        $this->db->join('tbl_itinerary_answers','tbl_itinerary_answers.question_id=tbl_itinerary_questions.id');
        $this->db->where('tbl_itinerary_answers.id',$_POST['id']);
        $Q=$this->db->get();
        if($Q->num_rows()>0)
        {
           $data=$Q->row_array();
           return $data['user_id'];
        }
        return '0';
    }

    function itineraryOwner()
    {
        $this->db->select('user_id');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
        $this->db->where('tbl_itinerary_questions.id',$_POST['id']);
        $Q=$this->db->get();
        if($Q->num_rows()>0)
        {
           $data=$Q->row_array();
           return $data['user_id'];
        }
        return '0';
    }



  	function editcomment()
  	{

        if($this->session->userdata('id')!='' && $this->session->userdata('role_id')!='')
        {
            $this->db->where('id',$_POST['id']);
            $this->db->update('tbl_itinerary_answers',array('comment'=>$_POST['comment']));
        }
        else
        {
              $Q=$this->db->query('select id from tbl_itinerary_answers where id="'.$_POST['id'].'" and sender_id="'.$this->session->userdata('fuserid').'" limit 1');
              if($Q->num_rows()>0)
              {
                $this->db->where('id',$_POST['id']);
                $this->db->where('sender_id',$this->session->userdata('fuserid'));
                $this->db->update('tbl_itinerary_answers',array('comment'=>$_POST['comment']));
              }
              else
              {
                $res=array(
                    'status'=>'fail',
                  );
                echo json_encode($res);
              }
        }

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
        $this->db->order_by('continent_id','ASC');
        $this->db->order_by('country_name','ASC');
        $query=$this->db->get();
        return $query->result_array();
    }

    function copy_itinerary()
    {
        $Q=$this->db->query('select * from tbl_itineraries where id not in(select itinerary_id from tbl_copy_trips where tbl_copy_trips.itinerary_id=tbl_itineraries.id and user_id="'.$this->session->userdata('fuserid').'") and id="'.$_POST['iti'].'" and trip_mode=2');
        if($Q->num_rows()>0)
        {
            $data=$Q->row_array();
          //  print_r($data);die;
            if($data['trip_type']==1)
            {
               $lastid=$this->copy_SingleCountryItinerary($data);
               $jsonarray=array(
       						'error'=>0,
                   'tripurl'=>site_url('userSearchedCityTrip').'/'.string_encode($lastid),
                   'accounturl'=>site_url('trips')
       				);
               return $jsonarray;
            }
            else if($data['trip_type']==2)
            {
              $lastid=$this->copy_MultiCountryItinerary($data);
              $jsonarray=array(
      						'error'=>0,
                  'tripurl'=>site_url('multicountrytrips').'/'.string_encode($lastid),
                  'accounturl'=>site_url('trips')
      				);
              return $jsonarray;
            }
            else if($data['trip_type']==3)
            {
              $lastid=$this->copy_SearchedCityItinerary($data);
              $jsonarray=array(
      						'error'=>0,
                  'tripurl'=>site_url('userSearchedCityTrip').'/'.string_encode($lastid),
                  'accounturl'=>site_url('trips')
      				);
              return $jsonarray;
            }
            return $jsonarray=array(
                    'error'=>1,
                    'message'=>'Something went wrong.'
                  );

        }
        else
        {
          return $jsonarray=array(
                  'error'=>1,
                  'message'=>'Something went wrong.'
                );
        }
    }

    function copy_SingleCountryItinerary($itidata)
    {
          $this->load->model('Trip_fm');
          $tripname_main=$this->Trip_fm->getContinentCountryName($itidata['country_id']);
          $slug=$this->Account_fm->generateItiSlug('Trip '.$tripname_main['country_name']);
          
          $copyitidata=array(
            'user_id'=>$this->session->userdata('fuserid'),
            'sess_id'=>$this->session->userdata('randomstring'),
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
         $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$this->session->userdata('fuserid')));
         return $lastid;
    }

    function copy_MultiCountryItinerary($itidata)
    {
          $this->load->model('Trip_fm');
          $tripname_main=$this->Trip_fm->getContinentName($itidata['tripname']);
          $slug=$this->Account_fm->generateItiSlug('Trip '.$tripname_main['country_name']);
          $copyitidata=array(
            'user_id'=>$this->session->userdata('fuserid'),
            'sess_id'=>$this->session->userdata('randomstring'),
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
         $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$this->session->userdata('fuserid')));
         return $lastid;
    }

    function copy_SearchedCityItinerary($itidata)
    {
        $this->load->model('Trip_fm');
        $tripname_main=$this->Trip_fm->getContinentCountryName($itidata['country_id']);
        $slug=$this->Account_fm->generateItiSlug('Trip '.$tripname_main['country_name']);
        $copyitidata=array(
          'user_id'=>$this->session->userdata('fuserid'),
          'sess_id'=>$this->session->userdata('randomstring'),
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
       $this->db->insert('tbl_copy_trips',array('new_itinerary_id'=>$lastid,'itinerary_id'=>$itidata['id'],'user_id'=>$this->session->userdata('fuserid')));
       return $lastid;

    }

    function checkTripSavedStatus($id)
    {
       $Q=$this->db->query('select id from tbl_copy_trips where itinerary_id="'.$id.'" and user_id="'.$this->session->userdata('fuserid').'" limit 1');
       return $Q->num_rows();
    }

    function updateViews($iti)
    {
       $this->db->set('views','views+1',FALSE);
       $this->db->where('id',$iti);
       $this->db->update('tbl_itineraries');
    }

    function store_rating()
    {
        $Q=$this->db->query('select id from tbl_ratings where user_id="'.$this->session->userdata('fuserid').'" and itinerary_id="'.$_POST['iti'].'" limit 1');
        $rating=1;
        if(isset($_POST['rating']) && $_POST['rating']>5)
        {
          $rating=5;
        }
        else if(isset($_POST['rating']))
        {
          $rating=$_POST['rating'];
        }
        if($Q->num_rows()>0)
        {
          $data=$Q->row_array();
          $this->db->where('id',$data['id']);
          $this->db->update('tbl_ratings',array('rating'=>$rating));
        }
        else
        {
          $data=$Q->row_array();
          $this->db->insert('tbl_ratings',array('rating'=>$rating,'itinerary_id'=>$_POST['iti'],'user_id'=>$this->session->userdata('fuserid')));
        }

        $avgQ=$this->db->query('select avg(rating) as totaltating from tbl_ratings where itinerary_id="'.$_POST['iti'].'"');
        $resultdata=$avgQ->row_array();
        $this->db->where('id',$_POST['iti']);
        $this->db->update('tbl_itineraries',array('rating'=>$resultdata['totaltating']));
    }

    function getUserRating($itineraryid)
    {
       $Q=$this->db->query('select rating from tbl_ratings where user_id="'.$this->session->userdata('fuserid').'" and itinerary_id="'.$itineraryid.'"');
       return $Q->row_array();
    }
}

?>
