<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Feedback extends REST_Controller 
{	

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Feedback_wm');
		//$this->load->helper('app');
	}

	public function index_post()
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
			$total_rows = $this->Feedback_wm->countFeedbacks();

			//$data['total_page'] = ceil($total_rows/5);
			$data=$this->Feedback_wm->getFeedbacks(5,$start_row);
			
			$message=array(
				'errorcode' =>1,
				'total_page'=>ceil($total_rows/5),
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function sendFeedback_post()
	{
		$this->load->library('email');
	    $this->load->library('MY_Email');	
		$this->load->helper('app');
		$this->Feedback_wm->sendFeedback();
		$message = array(
            'errorcode'	=> 1,
            'message' => 'Your message has been Sent.'
            );
	
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function deleteFeedback_post()
	{
		$result=$this->Feedback_wm->deleteFeedback();
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
            'message' => 'Your Feedback has been deleted.'
            );
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}
}
?>