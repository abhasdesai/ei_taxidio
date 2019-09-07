<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/User_wm');
		$this->load->helper('security');
		$this->load->helper('app');
		$this->load->library('form_validation');
	}

	function index_post()
	{
		$message['errorcode']=1;
		$message['data'] = array(
                'user' 		=> $this->User_wm->getUserDetails(),
                'countries' => $this->User_wm->getCountries(),
                //User's Tags
                'user_tags' => $this->User_wm->getAllUserTags(),
	            );
	           // echo "<pre>";print_r($message);die;
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function upgradeDeviceID_post()
	{
		deviceUpdate($_POST['device_udid'],$_POST['device_version'],$_POST['device_type']);
		
		$message = array(
        'errorcode' => 1
        );
        
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function signUP_post()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('useremail','Email','trim|required|valid_email|min_length[5]|max_length[250]|is_unique[tbl_front_users.email]',array('is_unique'=>'This email address already exists.'));
			
		if($this->form_validation->run()==FALSE)
		{
			$message = array(
                'errorcode' => 0,
                'message' 	=> form_error('useremail')
	            );
		}
		else
		{
			$result=$this->User_wm->signupUser();
			
				$message = array(
                'errorcode' => 1,
				'message'	=>"Thank you for signup",
                'data' 	=> $result
	            );
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function signIN_post()
	{
			
			$login=$this->User_wm->signinUser();
			if($login==false)
			{
				$message = array(
                'errorcode' => 0,
                'message' 	=> 'Invalid Email/Password Combination.'
	            );
			}
			else
			{
				$message = array(
                'errorcode' => 1,
				'message'	=>"You've successfully logged in",
                'data' 	=> $login
	            );
			}
			$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function forgotPassword_post()
	{
		//$this->load->library('email');
		//$this->load->library('MY_Email_Other');
		
			$check=$this->User_wm->forgotPassword();
			if($check==false)
			{
				$message = array(
                'errorcode' => 0,
                'message' 	=> 'This Email does not not Exist.'
	            );
			}
			else
			{
				$message = array(
                'errorcode' => 1,
                'message' 	=> 'Email Sended sucessfully.'
	            );
			}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}
	
	function changePassword_post()
	{
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('currentpassword', 'Current Password', 'trim|required|callback_check_current_password');

			if($this->form_validation->run()==FALSE)
			{
				$message = array(
                'errorcode' => 0,
                'message' 	=> form_error('currentpassword')
	            );
			}
			else
			{
				$this->User_wm->changepassword();
				$message = array(
	                'errorcode'	=> 1,
	                'message' 	=> 'Your Password has been updated.'
		        );
			}
            $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function check_current_password()
	{
		return $this->User_wm->check_current_password();
	}

	function googleLogin_post()
	{

			$result=$this->User_wm->googleLogin();
			
				$message = array(
                'errorcode' => 1,
				'message'	=>"You've successfully logged in",
                'data' 	=> $result
	            );
		
			$this->set_response($message, REST_Controller::HTTP_OK);
		
	}

	public function facebookLogin_post()
	{
			$result=$this->User_wm->facebookLogin();
		
			$message = array(
            'errorcode' => 1,
			'message'	=>"You've successfully logged in",
            'data' 	=> $result
            );
		
			$this->set_response($message, REST_Controller::HTTP_OK);
		
	}

	public function check_ask_for_email_post()
	{
			$result=getrowbycondition('isemail','tbl_front_users',"id=".$_POST['userid']);
			
			if($result['isemail']==1)
			{
				$message = array(
	            'errorcode' => 0
	            );
			}
			else
			{
				$message = array(
	            'errorcode' => 1
	            );
			}
		
			$this->set_response($message, REST_Controller::HTTP_OK);
		
	}

	function checkUniqueEmail_post()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('useremail','Email','trim|required|valid_email|min_length[5]|max_length[250]|is_unique[tbl_front_users.email]',array('is_unique'=>'This email address already exists.'));
			
		if($this->form_validation->run()==FALSE)
		{
			$message = array(
                'errorcode' => 0,
                'message' 	=> form_error('useremail')
	            );
		}
		else
		{
				$message = array(
                	'errorcode' => 1
	            );
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function updateProfile_post()
	{
		//echo "<pre>";print_r($_POST);die;
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			if($_POST['issocial']!=1)
			{
				$this->form_validation->set_rules('useremail','useremail','trim|valid_email|min_length[5]|max_length[450]|callback_check_email');
			}
			$this->form_validation->set_rules('dob','dob','trim');
			
			if($this->form_validation->run()==FALSE)
			{
				$message=array(
					'errorcode'=>0,
					'message'	=>form_error('useremail')
				);
			}
			else
			{
				$result=$this->User_wm->editUser();
				if($result)
				{
					$message=array(
					'errorcode'=>1,
					'message'	=>'Your profile has been updated.',
					'data'	=>$result
					);
				}
				else
				{
					$message=array(
					'errorcode'=>0,
					'message'	=>'Your profile has not been updated.'
					);
				}
			}
			$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function updateEmail_post()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		
		$this->form_validation->set_rules('useremail','useremail','trim|valid_email|min_length[5]|max_length[450]|callback_check_email',array('check_email'=>'This email is already registered with another account. Please add another email.'));
		
		if($this->form_validation->run()==FALSE)
		{
			$message=array(
				'errorcode'=>0,
				'message'	=>form_error('useremail')
			);
		}
		else
		{
			$result=$this->User_wm->updateEmail();
			if($result)
			{
				$message=array(
				'errorcode'=>1,
				'message'	=>'Your Email has been Updated.',
				'data'	=>$result
				);
			}
			else
			{
				$message=array(
				'errorcode'=>0,
				'message'	=>'Your Email has not been Updated.'
				);
			}
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function check_email($email)
	{
		return $this->User_wm->check_email($email);
	}
	
	//Code For SOS CRUD - Start
	
	public function getCountryCodes_get()
	{
		$response['errorcode']=1;
		$response['data'] = array(
            'countrycodes'=>$this->User_wm->countrySelector(),
	    );
	           // echo "<pre>";print_r($message);die;
        $this->set_response($response, REST_Controller::HTTP_OK);
	}
	
	public function getAllSOSOfUser_post()
	{
		$response['errorcode']=1;
		$response['data'] = array(
            'user_sos'=>$this->User_wm->getAllSOSOfUser(),
	    );
	           // echo "<pre>";print_r($message);die;
        $this->set_response($response, REST_Controller::HTTP_OK);
	}
	
	public function addSOS_post(){
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		
		$this->form_validation->set_rules('sosemail','Contact Person Email','trim|valid_email|min_length[5]|max_length[450]');
		if($this->form_validation->run()==FALSE)
		{
			$message=array(
				'errorcode'=>0,
				'message'	=>form_error('sosemail')
			);
		}
		else
		{
			$result=$this->User_wm->addSOS();
			if($result===TRUE)
			{
				$message = array(
					'errorcode'=>1,
					'message'=>'SOS Details Added Successfully',
				);
			}
			else
			{
				$message = array(
					'errorcode'=>0,
					'message'=>'Something went wrong',
				);
			}
		}
		
		$this->set_response($message, REST_Controller::HTTP_OK);
	}
	
	public function getSOSById_POST()
	{
		$sosId = $this->input->post('sosId');
		$userId = $this->input->post('userid');
		$sos = $this->User_wm->getSOSById($sosId,$userId);
		
		$response = array(
			'errorcode'=>1,
			'data'=>array('user_sos'=>$sos),
		);
		$this->set_response($response, REST_Controller::HTTP_OK);
	}
	
	public function updateSOS_post()
	{
		$sosId = $this->input->post('sosId');
		$userId = $this->input->post('userid');
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		
		$this->form_validation->set_rules('sosemail','sosemail','trim|valid_email|min_length[5]|max_length[450]');
		if($this->form_validation->run()==FALSE)
		{
			$message=array(
				'errorcode'=>0,
				'message'	=>form_error('sosemail')
			);
		}
		else
		{
			$result=$this->User_wm->editSOS($sosId,$userId);
			if($result===TRUE)
			{
				$message = array(
					'errorcode'=>1,
					'message'=>'SOS Details updated Successfully',
				);
			}
			else
			{
				$message = array(
					'errorcode'=>0,
					'message'=>'Something went wrong',
				);
			}
		}
		
		$this->set_response($message, REST_Controller::HTTP_OK);
	}
	
	public function deleteSOS_post()
	{
		$sosId = $this->input->post('sosId');
		$userId = $this->input->post('userid');
		$this->User_wm->deleteSOS($sosId,$userId);
		$response = array(
			'errorcode'=>1,
			'message'=>'SOS Deleted Successfully',
		);
		
		$this->set_response($response, REST_Controller::HTTP_OK);
	}
	
	//Code For SOS CRUD - End
	

}

/* End of file User.php */
/* Location: ./application/controllers/web_services/User.php */
