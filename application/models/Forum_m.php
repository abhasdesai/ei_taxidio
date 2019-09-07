<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Forum_m extends CI_Model
{

	function countMyQuestions()
	{
		$this->db->select('tbl_itinerary_questions.id');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id','LEFT');
		$this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
		$this->db->where('trip_mode',2);
		$this->db->where('posted_by',$this->session->userdata('fuserid'));

		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function getMyQuestions($limit,$start)
	{
		$this->db->select('tbl_itinerary_questions.id,question,tbl_itinerary_questions.created,user_trip_name,name,(select count(id) from tbl_itinerary_answers where tbl_itinerary_answers.question_id=tbl_itinerary_questions.id) as totalcomments');
		$this->db->from('tbl_itineraries');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id','LEFT');
		$this->db->join('tbl_itinerary_questions','tbl_itinerary_questions.itinerary_id=tbl_itineraries.id');
		$this->db->where('trip_mode',2);
		$this->db->where('posted_by',$this->session->userdata('fuserid'));
		$this->db->order_by('id','DESC');
		$this->db->limit($limit,$start);
		$Q=$this->db->get();
		//echo $Q->num_rows();die;
		return $Q->result_array();
	}

	function countTotalQuestions()
	{
		$Q=$this->db->query('select id from tbl_forums');
		return $Q->num_rows();
	}

	function getTotalQuestions($limit,$start)
	{
		$this->db->select('tbl_forums.id,subject,tbl_forums.created,slug,name,question,userimage,googleid,facebookid');
		$this->db->from('tbl_forums');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_forums.user_id','LEFT');
		$this->db->order_by('id','DESC');
		$this->db->limit($limit,$start);
		$Q=$this->db->get();
		return $Q->result_array();
	}

	function getQuestionDetails($slug)
	{
		$this->db->select('tbl_forums.id,subject,tbl_forums.created,slug,name,question,userimage,googleid,facebookid');
		$this->db->from('tbl_forums');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_forums.user_id','LEFT');
		$this->db->where('slug',$slug);
		$Q=$this->db->get();
		return $Q->row_array();
	}

	function addQuestion($id)
	{
		$data=array(
			'question'=>nl2br($_POST['question']),
			'created'=>date('Y-m-d H:i:s'),
			'totalcomments'=>0,
			'itinerary_id'=>$id,
			'posted_by'=>$this->session->userdata('fuserid')
			);

		$this->db->insert('tbl_itinerary_questions',$data);
		$lastid=$this->db->insert_id();
		$this->sendEmailToItineraryOwner($id,$lastid);
	}

	function sendEmailToItineraryOwner($id,$lastid)
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
			  $QForum=$this->db->query('select id,question from tbl_itinerary_questions where id="'.$lastid.'"');
				$data=$QForum->row_array();
			 	$useremail=$resultdata['email'];
				$subject=$this->session->userdata('name').' has posted new question on your itinerary';
				$message=$this->load->view('itineraries/sendEmailToItineraryOwner',$data,true);
				$from='noreply@taxidio.com';
				$this->email->from($from,'Taxidio');
				$this->email->subject($subject);
				/*if($this->session->userdata('isemail')!=1)
				{
					$this->email->reply_to($this->session->userdata('email'));
				}*/
				$this->email->to($useremail);
				$this->email->message($message);
				$this->email->send();
		 }

	}

	function countTotalComments($id)
	{
		$Q=$this->db->query('select id from tbl_itinerary_answers where question_id="'.$id.'"');
		return $Q->num_rows();
	}

	function getAllComments($slug)
	{
		$data=array();
		$this->db->select('tbl_forum_comments.id,sender_id,name,userimage,tbl_forum_comments.created,comment,googleid,facebookid');
		$this->db->from('tbl_forum_comments');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_forum_comments.sender_id','LEFT');
		$this->db->join('tbl_forums','tbl_forums.id=tbl_forum_comments.question_id');
		$this->db->where('tbl_forums.slug',$slug);
		$Q=$this->db->get();
		return $Q->result_array();

	}

	function deleteComment()
	{
		$Q=$this->db->query('select id from tbl_forum_comments where id="'.$_POST['id'].'" and sender_id="'.$this->session->userdata('fuserid').'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->db->where('id',$_POST['id']);
			$this->db->where('sender_id',$this->session->userdata('fuserid'));
			$this->db->delete('tbl_forum_comments');
		}
		else
		{
			$res=array(
					'status'=>'fail',
				);
			echo json_encode($res);
		}

	}


	function editcomment()
	{
		$Q=$this->db->query('select id from tbl_forum_comments where id="'.$_POST['id'].'" and sender_id="'.$this->session->userdata('fuserid').'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->db->where('id',$_POST['id']);
			$this->db->where('sender_id',$this->session->userdata('fuserid'));
			$this->db->update('tbl_forum_comments',array('comment'=>$_POST['comment']));
		}
		else
		{
			$res=array(
					'status'=>'fail',
				);
			echo json_encode($res);
		}
	}

	function postComment()
	{
		$Q=$this->db->query('select user_id from tbl_forums where id="'.$_POST['conversation_id'].'" and slug="'.$_POST['slug'].'" limit 1');
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
			$sender_id=$this->session->userdata('fuserid');
			$receiver_id=$data['user_id'];
			$datetime=date('Y-m-d H:i:s');
			$postdata=array(
					'sender_id'=>$sender_id,
					'receiver_id'=>$receiver_id,
					'comment'=>$_POST['usercomment'],
					'question_id'=>$_POST['conversation_id'],
					'created'=>$datetime
				);
			$this->db->insert('tbl_forum_comments',$postdata);

			$res=$this->getLatestComments();

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

	function getLatestComments()
	{
		$data=array();
		$data=array();
		$this->db->select('tbl_forum_comments.id,name,sender_id,userimage,tbl_forum_comments.created,comment,googleid,facebookid');
		$this->db->from('tbl_forum_comments');
		$this->db->join('tbl_front_users','tbl_front_users.id=tbl_forum_comments.sender_id','LEFT');
		$this->db->join('tbl_forums','tbl_forums.id=tbl_forum_comments.question_id');
		$this->db->where('tbl_forums.id ',$_POST['conversation_id']);
		if(isset($_POST['conversation_lastid']) && $_POST['conversation_lastid']!='')
		{
			$this->db->where('tbl_forum_comments.id >',$_POST['conversation_lastid']);
		}

		$Q=$this->db->get();
		return $Q->result_array();
	}

	function getItineraryInfo($id)
	{
		$Q=$this->db->query('select id,user_trip_name from tbl_itineraries where id="'.$id.'"');
		return $Q->row_array();

	}

}

?>
