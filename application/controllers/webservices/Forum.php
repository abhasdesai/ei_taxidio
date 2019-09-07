<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Forum extends REST_Controller 
{	

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Forum_wm');
		//$this->load->helper('app');
	}

	public function index_post()
	{
		$checkiti=$this->Forum_wm->checkItineraryExist($_POST['itirnaryid']);
		if(count($checkiti)>0)
		{
			$pageno=$_POST['pageno'];
			if(empty((trim($pageno))))
			{
				$start_row=0;
			}
			else
			{
				$start_row=($pageno*5)-5;
			}
			$total_rows = $this->Forum_wm->countTotalQuestionOfItinerary($_POST['itirnaryid']);
			$data = $this->Forum_wm->getQuestionOfItinerary($_POST['itirnaryid'],5,$start_row);
			
			foreach ($data as $key => $value) {
				$data[$key]['created']=time_ago(strtotime($value['created']));
			}
			
			if(count($data)>0)
			{
				$message = array(
		            'errorcode'	=> 1,
		            'total_page'=>ceil($total_rows/5),
		            'data' 		=> $data
	            );
			}
			else
			{
				$message = array(
		            'errorcode'	=> 0,
		            'message' 	=> 'There are no questions asked for this itinerary.'
	            );	
			}
			if(isset($_POST['userid']) && !empty($_POST['userid']))
			{
				$message['userrating']=$this->Forum_wm->getUserRating($_POST['itirnaryid']);
			}
		}
		else
		{
			 $message = array(
	            'errorcode'	=> 0,
	            'message' => 'This Itinerary does not Exist.'
	            );				
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function store_rating_post()
	{
		$this->Forum_wm->store_rating();
		$message = array(
            'errorcode'	=> 1,
            'message' => 'Thank you for your rating.'
            );
	
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function myQuestions_post()
	{
		if(empty((trim($_POST['pageno']))))
		{
			$start_row=0;
		}
		else
		{
			$start_row=($_POST['pageno']*5)-5;
		}
       	$total_rows = $this->Forum_wm->countMyQuestions();
        $data=$this->Forum_wm->getMyQuestions(5,$start_row);

        
		foreach ($data as $key => $value) {
			$data[$key]['date']=date('dS, M Y',strtotime($value['created']));
			$data[$key]['created']=time_ago(strtotime($value['created']));
		}
			
        if(count($data)>0)
		{
			$message = array(
	            'errorcode'	=> 1,
	            'total_page'=>ceil($total_rows/5),
	            'data' 		=> $data
            );
		}
		else
		{
			$message = array(
	            'errorcode'	=> 0,
	            'message' 	=> 'Nothing To Show.'
            );	
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function addQuestion_post()
	{
		$this->load->helper('app');
		$itineraryinfo=$this->Forum_wm->getItineraryInfo();
		if(count($itineraryinfo)<1)
		{
			$message = array(
	            'errorcode'	=> 0,
	            'message' => 'This Itinerary does not Exist.'
	            );	
		}
		else
		{
			 $result=$this->Forum_wm->addQuestion($_POST['itirnaryid']);
			 if(!empty($result))
			 {
			 	$message = array(
	            	'errorcode'	=> 1,
	            	'message' 	=> 'Your question has been submitted.',
	            	'data'		=> $this->Forum_wm->getMyQuestions(5,0)
            	);
			 }
			 else
			 {
			 	$message = array(
	            	'errorcode'	=> 0,
	            	'message' 	=> 'Oopp..!Something went wrong..'
            	);
			 }
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function deleteQuestion_post()
	{
		$result=$this->Forum_wm->deleteQuestion();
		if($result!=1)
		{
			$message = array(
            'errorcode'	=> 0,
            'message' => 'Oopp..!Something went wrong..'
            );
		}
		else
		{
			$message = array(
            'errorcode'	=> 1,
            'message' => 'Your Question has been deleted.'
            );
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function itinerary_discussion_post()
	{
		$id=$_POST['question_id'];
		$data=$this->Forum_wm->getQuestionDetails($id);
		if(count($data)<1)
		{
			 $message = array(
	            'errorcode'	=> 0,
	            'message' => 'This Question dose not Exist.'
	            );
		}
		else
		{
			$checkiti=$this->Forum_wm->checkItineraryExist($data['itinerary_id']);
			if(count($checkiti)>0)
			{
				$data['comments']=$this->Forum_wm->getAllComments($id);

				if(count($data)>0)
				{
					$message = array(
			            'errorcode'	=> 1,
			            'data' => $data['comments']
		            );
				}
				else
				{
					$message = array(
			            'errorcode'	=> 0,
			            'message' => 'Oopp..!Something went wrong..'
		            );	
				}
			}
			else
			{
				 $message = array(
		            'errorcode'	=> 0,
		            'message' => 'This Itinerary does not Exist.'
		            );				
			}
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function postComment_post()
	{
        $this->load->helper('app');
        $this->load->helper('text');
		$result=$this->Forum_wm->postComment();

		//$result['created']=time_ago(strtotime($result['created']));
				
		 if(!empty($result))
		 {
		 	$message = array(
            	'errorcode'	=> 1,
            	'data'		=> $result
        	);
		 }
		 else
		 {
		 	$message = array(
            	'errorcode'	=> 0,
            	'message' 	=> 'Oopp..!Something went wrong..'
        	);
		 }
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function editComment_post()
	{
		$result=$this->Forum_wm->editcomment();

		//$result['created']=time_ago(strtotime($result['created']));
		
		 if(!empty($result))
		 {
		 	$message = array(
            	'errorcode'	=> 1,
            	'data'		=> $result
        	);
		 }
		 else
		 {
		 	$message = array(
            	'errorcode'	=> 0,
            	'message' 	=> 'Oopp..!Something went wrong..'
        	);
		 }
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function deleteComment_post()
	{
		$result=$this->Forum_wm->deleteComment();
		if($result!=1)
		{
			$message = array(
            'errorcode'	=> 0,
            'message' => 'Oopp..!Something went wrong..'
            );
		}
		else
		{
			$message = array(
            'errorcode'	=> 1,
            //'message' => 'Your Comment has been deleted.'
            );
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

}
?>