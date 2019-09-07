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

	function index()
	{
	}

	function upgradeDeviceID_post()
	{
		$result=$this->User_wm->deviceUpdate();
		if(!empty($result))
		{
			$message = array(
	        'errorcode' => 1
	        );
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function userprofile_post()
	{		
		$message['errorcode']=1;
		$message['data'] = array(
                'user' 		=> $this->User_wm->getUserDetails(),
                'countries' => $this->User_wm->getCountries()
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
        //$this->load->model('webservices_models/master_model');
        //$this->load->library('session');
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
				//$this->load->library('email');
				//$this->load->library('MY_Email_Other');	
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
/*
		$redirectURL=site_url();


		if($this->session->userdata('socialurl') && $this->session->userdata('socialurl')!='')
		{
			$redirectURL=$this->session->userdata('socialurl');
		}
		else if($this->session->userdata('previousurl') && $this->session->userdata('previousurl')!='')
		{
			$redirectURL=$this->session->userdata('previousurl');
		}


		$googleloginUrl='';
		require_once(APPPATH.'libraries/Google/autoload.php');
		$redirect_uri=site_url("home/rfun");
		$client = new Google_Client();
		$client->setClientId('302599165572-q1lq74hg358g2va7i8p4hqlvhuc1c98i.apps.googleusercontent.com');
		$client->setClientSecret('o8vNEL_H91RHn-T3iLLJOb8h');
		$client->setRedirectUri($redirect_uri);
		$client->addScope("email");
		$client->addScope("profile");
		$service = new Google_Service_Oauth2($client);

		if (isset($_GET['code']))
		{
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		  exit;
		}

		if (isset($_SESSION['access_token']) && $_SESSION['access_token'])
		{
		 	 $client->setAccessToken($_SESSION['access_token']);
		}
		else
		{
			$data['authUrl']= $client->createAuthUrl();
		}


		if (isset($data['authUrl']))
		{
			$googleloginUrl=$data['authUrl'];
			$this->load->view('tets',$data);
		}
		else
		{*/
			//$user = $service->userinfo->get();
			//echo "<pre>";print_r($user);die;
			$result=$this->User_wm->googleLogin();
			
				$message = array(
                'errorcode' => 1,
				'message'	=>"You've successfully logged in",
                'data' 	=> $result
	            );
		
			$this->set_response($message, REST_Controller::HTTP_OK);
			/*}
			$this->session->set_userdata($sessionArray);
			writeTripsInFile();
			loggedinUser(1);
			redirect($redirectURL);


		}*/

	}

	public function facebookLogin_post()
	{
		/*$redirectURL=site_url();
		if($this->session->userdata('socialurl') && $this->session->userdata('socialurl')!='')
		{
			$redirectURL=$this->session->userdata('socialurl');
		}
		else if($this->session->userdata('previousurl') && $this->session->userdata('previousurl')!='')
		{
		   $redirectURL=$this->session->userdata('previousurl');
		}
		if ($this->facebook->is_authenticated())
		{
			// User logged in, get user details
			$user = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture.width(150).height(150)');
			if (!isset($user['error']))
			{*/
			$result=$this->User_wm->facebookLogin();
		
			$message = array(
            'errorcode' => 1,
			'message'	=>"You've successfully logged in",
            'data' 	=> $result
            );
		
			$this->set_response($message, REST_Controller::HTTP_OK);
					/*$this->session->set_userdata($sessionArray);
					writeTripsInFile();
					loggedinUser(1);
					redirect($redirectURL);

				}
			}*/

	}


	public function fblogin1()
	{

		$redirectURL=site_url();
		if($this->session->userdata('previousurl') && $this->session->userdata('previousurl')!='')
		{
		   $redirectURL=$this->session->userdata('previousurl');
		}
		//echo $redirectURL;
		//echo $_SERVER['HTTP_REFERER'];


		include_once(APPPATH.'libraries/facebook_login_with_php/inc/facebook.php');
		$appId = $this->config->item('appId'); //Facebook App ID
		$appSecret = $this->config->item('appSecret'); // Facebook App Secret
		$homeurl = $this->config->item('redirecturl');  //return to home
		$fbPermissions = 'email';  //Required facebook permissions

		$facebook = new Facebook(array(
		  'appId'  => $appId,
		  'secret' => $appSecret

		));
		$fbuser = $facebook->getUser();

		require_once(APPPATH.'libraries/facebook_login_with_php/includes/functions.php');



		if(!$fbuser)
		{
			redirect($redirectURL);
		}
		else
		{

			//echo "hi";die;
			$user = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');

			$Q=$this->db->query('select id,name,email,last_login,userimage from tbl_front_users where facebookid="'.$user['id'].'"');
			if($Q->num_rows()>0)
			{
				$returnData=$Q->row_array();
				$sessionArray=array(
					'fuserid'=>$returnData['id'],
					'name'=>ucwords($returnData['name']),
					'email'=>$returnData['email'],
					'last_login'=>$returnData['last_login'],
					'userimage'=>$user['picture']['data']['url'],
				);
			}
			else
			{
				$datetime=date('Y-m-d H:i:s');

				if(isset($user['email']) && $user['email']!='')
				{
					$uemail=$user['email'];
				}
				else
				{
					$uemail=$user['id'].'@facebook.com';
				}

				if(isset($user['first_name']) && $user['first_name']!='' && isset($user['last_name']) && $user['last_name']!='')
				{
					$uname=ucwords($user['first_name'].' '.$user['last_name']);
				}
				else if(isset($user['first_name']) && $user['first_name']!='' && !isset($user['last_name']))
				{
					$uname=ucwords($user['first_name']);
				}
				else if(isset($user['last_name']) && $user['last_name']!='' && !isset($user['first_name']))
				{
					$uname=ucwords($user['last_name']);
				}
				else
				{
					$uname=$user['id'];
				}

				$insertdata=array(
					'name'=>ucwords($user['first_name'].' '.$user['last_name']),
					'email'=>$uemail,
					'logintype'=>3,
					'isactive'=>1,
					'created'=>$datetime,
					'password'=>'',
					'googleid'=>'',
					'facebookid'=>$user['id'],
					'userimage'=>'',
				);
				$this->db->insert('tbl_front_users',$insertdata);
				$userid=$this->db->insert_id();
				$sessionArray=array(
					'fuserid'=>$userid,
					'name'=>ucwords($user['first_name'].' '.$user['last_name']),
					'email'=>$uemail,
					'last_login'=>$datetime,
					'userimage'=>$user['picture']['data']['url'],
				);
			}
			$this->session->set_userdata($sessionArray);
			redirect($redirectURL);

		}
	}

	function getAutoSuggestion()
	{
		$q=$_POST['q'];
		$url = 'http://free.rome2rio.com/api/1.2/json/Autocomplete?key=xa3wFHMZ&query=' .$q . '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);    // we want headers
		curl_setopt($ch, CURLOPT_URL, "set ur url");
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		echo $result;
	}
}

/* End of file User.php */
/* Location: ./application/controllers/web_services/User.php */
