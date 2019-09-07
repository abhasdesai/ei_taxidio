<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Forum_wm extends CI_Model 
{

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
        $this->db->select('tbl_itinerary_questions.id,question,tbl_itinerary_questions.created,IFNULL(userimage,"") as userimage,IFNULL(socialimage,"") as socialimage,name,(select count(id) from tbl_itinerary_answers where tbl_itinerary_answers.question_id=tbl_itinerary_questions.id) as totalcomments,user_id as owner_id,posted_by');
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

    function getUserRating($itineraryid)
    {
       $Q=$this->db->query('select rating from tbl_ratings where user_id="'.$_POST['userid'].'" and itinerary_id="'.$itineraryid.'"');
       if($Q->num_rows()>0)
       {
          $data=$Q->row_array();
          return $data['rating'];
       }
      return 0;
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

    function store_rating()
    {
        $Q=$this->db->query('select id from tbl_ratings where user_id="'.$_POST['userid'].'" and itinerary_id="'.$_POST['itirnaryid'].'" limit 1');
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
          $this->db->insert('tbl_ratings',array('rating'=>$rating,'itinerary_id'=>$_POST['itirnaryid'],'user_id'=>$_POST['userid']));
        }

        $avgQ=$this->db->query('select avg(rating) as totaltating from tbl_ratings where itinerary_id="'.$_POST['itirnaryid'].'"');
        $resultdata=$avgQ->row_array();
        $this->db->where('id',$_POST['itirnaryid']);
        $this->db->update('tbl_itineraries',array('rating'=>$resultdata['totaltating']));
    }

  function getItineraryInfo()
  {
    $Q=$this->db->query('select id,user_trip_name from tbl_itineraries where id="'.$_POST['itirnaryid'].'"');
    return $Q->row_array();

  }

  function countMyQuestions()
  {
    $this->db->select('tbl_itinerary_questions.id');
    $this->db->from('tbl_itineraries');
    $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id','LEFT');
    $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
    $this->db->where('trip_mode',2);
    $this->db->where('posted_by',$_POST['userid']);

    $Q=$this->db->get();
    return $Q->num_rows();
  }

  function getMyQuestions($limit,$start)
  {
    $this->db->select('tbl_itinerary_questions.id,tbl_itineraries.id as itirnaryid,question,tbl_itinerary_questions.created,user_trip_name,name,(select count(id) from tbl_itinerary_answers where tbl_itinerary_answers.question_id=tbl_itinerary_questions.id) as totalcomments');
    $this->db->from('tbl_itineraries');
    $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id','LEFT');
    $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
    $this->db->where('trip_mode',2);
    $this->db->where('posted_by',$_POST['userid']);
    $this->db->order_by('id','DESC');
    $this->db->limit($limit,$start);
    $Q=$this->db->get();
    //echo $Q->num_rows();die;
    return $Q->result_array();
  }

  function addQuestion($id)
  {
    $data=array(
      'question'=>nl2br($_POST['question']),
      'created'=>date('Y-m-d H:i:s'),
      'totalcomments'=>0,
      'itinerary_id'=>$id,
      'posted_by'=>$_POST['userid']
      );

    $this->db->insert('tbl_itinerary_questions',$data);
    $questionid=$this->db->insert_id();
    $this->sendEmailToItineraryOwner($id,$questionid);
    return $questionid;
  }

    function itineraryOwnerandSender()
    {
        $this->db->select('user_id,posted_by');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
        $this->db->where('tbl_itinerary_questions.id',$_POST['question_id']);
        $Q=$this->db->get();
        if($Q->num_rows()>0)
        {
           $data=$Q->row_array();
           return $data;
        }
        return 0;
    }

    function deleteQuestion()
    {
        $iti_owner=$this->itineraryOwnerandSender();
        if($iti_owner!=0 && ($iti_owner['user_id']==$_POST['userid'] || $iti_owner['posted_by']==$_POST['userid']))
        {
            $this->db->where('id',$_POST['question_id']);
            $this->db->delete('tbl_itinerary_questions');

            $this->db->where('question_id',$_POST['question_id']);
            $this->db->delete('tbl_itinerary_answers');
            return 1;
        }
        else
        {
            return 0;
        }
    }

  function sendEmailToItineraryOwner($id,$questionid)
  {
     $this->load->library('email');
     $this->load->library('MY_Email_Other');

     $this->db->select('email,isemail');
     $this->db->from('tbl_front_users');
     $this->db->join('tbl_itineraries','tbl_itineraries.user_id=tbl_front_users.id');
     $this->db->where('tbl_itineraries.id',$id);
     $Q=$this->db->get();
     $resultdata=$Q->row_array();

     if($resultdata['isemail']!=1)
     {
        $username=getrowbycondition('name','tbl_front_users','id='.$_POST['userid']);
        $QForum=$this->db->query('select id,question from tbl_itinerary_questions where id="'.$questionid.'"');
        $data=$QForum->row_array();
        $useremail=$resultdata['email'];
        $subject=$username['name'].' has posted new question on your itinerary';
        $message=$this->load->view('itineraries/sendEmailToItineraryOwner',$data,true);
        $from='noreply@taxidio.com';
        $this->email->from($from,'Taxidio');
        $this->email->subject($subject);
        
        $this->email->to($useremail);
        $this->email->message($message);
        $this->email->send();
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
        if($data['user_id']!=$_POST['userid'])
        {
          $username=getrowbycondition('name','tbl_front_users','id='.$_POST['userid']);
          $subject=$username['name'].' commented to '. word_limiter($data['question'],60);
          $message=$this->load->view('itineraries/sendEmailToItiownerForComment',$data,true);
          $from='noreply@taxidio.com';
          $this->email->from($from,'Taxidio');
          $this->email->subject($subject);
          
          $this->email->to($data['email']);
          $this->email->message($message);
          $this->email->send();
        }
    }

    function postComment()
    {
      $Q=$this->db->query('select posted_by,(select user_id from tbl_itineraries where id=tbl_itinerary_questions.itinerary_id) as iti_owner from tbl_itinerary_questions where id="'.$_POST['question_id'].'" and itinerary_id="'.$_POST['itirnaryid'].'" limit 1');
      if($Q->num_rows()>0)
      {
        $data=$Q->row_array();
        $sender_id=$_POST['userid'];
        $receiver_id=$data['posted_by'];
        $datetime=date('Y-m-d H:i:s');
        $postdata=array(
            'sender_id'=>$sender_id,
            'receiver_id'=>$receiver_id,
            'comment'=>$_POST['usercomment'],
            'iti_owner'=>$data['iti_owner'],
            'question_id'=>$_POST['question_id'],
            'created'=>$datetime,
        );
        $this->db->insert('tbl_itinerary_answers',$postdata);
        $lastid=$this->db->insert_id();
        $res=$this->getLatestComments($lastid);
        $this->sendEmailToItiownerForComment($lastid);
        return $res;
      }
      else
      {
        return 0;
      }
    }

    function getLatestComments($id)
    {
      $this->db->select('tbl_itinerary_answers.id,name,sender_id,userimage,tbl_itinerary_answers.created,comment,socialimage');
      $this->db->from('tbl_itinerary_answers');
      $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itinerary_answers.sender_id','LEFT');
      $this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.id=tbl_itinerary_answers.question_id');
      $this->db->where('tbl_itinerary_questions.id',$_POST['question_id']);
      $this->db->where('tbl_itinerary_answers.id',$id);
      $Q=$this->db->get();
      return $Q->row_array();
    }

    function editcomment()
    {
        $Q=$this->db->query('select id from tbl_itinerary_answers where id="'.$_POST['answer_id'].'" and sender_id="'.$_POST['userid'].'" limit 1');
        if($Q->num_rows()>0)
        {
          $this->db->where('id',$_POST['answer_id']);
          $this->db->where('sender_id',$_POST['userid']);
          $this->db->update('tbl_itinerary_answers',array('comment'=>$_POST['usercomment']));
          return $this->getLatestComments($_POST['answer_id']);
        }
        else
        {
          return 0;
        }
    }

    function deleteComment()
    {
        $iti_owner=$this->itineraryOwnerandSender();
        $Q=$this->db->query('select id from tbl_itinerary_answers where id="'.$_POST['answer_id'].'" and sender_id="'.$_POST['userid'].'" or receiver_id="'.$iti_owner['user_id'].'" limit 1');
        if($Q->num_rows()>0)
        {
          $this->db->where('id',$_POST['answer_id']);
          $this->db->group_start()
                    ->where('sender_id',$_POST['userid'])
                    ->or_like('iti_owner',$_POST['userid'])
           ->group_end();
          $this->db->delete('tbl_itinerary_answers');
          return 1;
        }
        else
        {
          return 0;
        }
    }

}
?>