<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Account_wm');
		$this->load->helper('app');
		//$this->load->library('form_validation');
	}

	function index()
	{
	}

	function save_itinerary_post()
	{
		//echo time();die;
		$type=$_POST['type'];
		$uniqueid=$_POST['uniqueid'];
			if($type==1)
			{
				$lastid=$this->Account_wm->saveSingleIitnerary($_POST['country_id'],$uniqueid);
				$message=array(
				'errorcode' =>1,
				'message'	=>'Your trip has been saved.'
				);
			}
			elseif($type==2) 
			{
				$lastid=$this->Account_wm->saveMultiIitnerary($uniqueid,$_POST['country_id']);
				$message=array(
				'errorcode' =>1,
				'message'	=>'Your trip has been saved.'
				);
			}
			elseif($type==3) 
			{
				//$lastid=$this->Account_wm->saveMultiIitnerary($uniqueid,$_POST['country_id']);
				$message=array(
				'errorcode' =>1,
				'message'	=>'Your trip has been saved.'
				);
			}
			else
			{
				$message=array(
				'errorcode' =>0,
				'message'	=>'Something went wrong.'
				);
			}	
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function save_multi_itinerary($uniqueid,$secretkey)
	{
		
		$this->checkuniqueid($uniqueid,$searchtype='multicountries');
		$secretkeyid=string_decode($secretkey);
		//echo "<pre>";print_r($secretkeyid);die;	
		if(strpos($secretkeyid,'-')==FALSE)
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$lastid=$this->Account_wm->saveMultiIitnerary($uniqueid,$secretkey);
		$encodeid=string_encode($lastid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('multicountrytrips/'.$encodeid);	
		
	}

	function update_single_itinerary($iti)
	{
		$itid=string_decode($iti);
		$this->Account_wm->update_single_itinerary($itid);
		$this->deleteItinerary($itid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSingleCountryTrip/'.$iti);
	}

	function save_searched_itinerary($secretkey)
	{
		$secretkeyid=string_decode($secretkey);
		$type=explode('-',$secretkeyid);
		$secretkey=$type[0];
		$uniqueid=$type[1];
		$this->checkuniqueid($uniqueid,$searchtype='search');
		$lastid=$this->Account_wm->save_searched_itinerary($secretkey,$uniqueid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSearchedCityTrip/'.string_encode($lastid));
	}   

	function update_searched_itinerary($secretkey)
	{
		$iti_encode=explode('-',string_decode($secretkey));
		$secretkey=$iti_encode[0];
		$iti=$iti_encode[1];
		$this->Account_wm->update_searched_itinerary($secretkey,$iti);
		$this->deleteItinerary($iti);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSearchedCityTrip/'.string_encode($iti));
	}

	function updatesave_multi_itinerary($iti)
	{
		$itid=string_decode($iti);
		$this->Account_wm->updatesave_multi_itinerary($itid);
		$this->deleteItinerary($itid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('multicountrytrips/'.$iti);
	}


	function deleteItinerary($itid)
	{
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$files = glob(FCPATH.'userfiles/savedfiles/'.$itid.'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }	
			}
			rmdir(FCPATH.'userfiles/savedfiles/'.$itid);
		}
	}

	function userProfile_get()
	{
		$this->form_validation->set_error_delimiters('', ''); //('<p class="text-red-error">', '</p>');
        $getData = array(
            'userid' 	=> $this->input->get('userid', TRUE)
            );
        $this->form_validation->set_data($getData);
        $this->form_validation->set_rules('userid', 'User Id', 'trim|required',array('required'=>'Please Login.'));
        if ($this->form_validation->run() == FALSE) {
			$message['status']=false;
            $message['message'] = array(
                'userid' 	=> form_error('userid')
            );
        } else {
			$message['status']=true;
			$message['message'] = array(
	                'user' 		=> $this->User_wm->getUserDetails(),
	                'countries' => $this->User_wm->getCountries()
		            );
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function signupUser_post()
	{
			$this->form_validation->set_error_delimiters('', '');//('<p class="text-white">', '</p>');
			$this->form_validation->set_rules('name','Name','trim|required|min_length[2]|max_length[150]|xss_clean');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|min_length[5]|max_length[250]|is_unique[tbl_front_users.email]',array('is_unique'=>'This email address already exists.'));
			$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]|xss_clean');
			$this->form_validation->set_rules('reenterpassword','Confirm Password','trim|required|matches[password]|xss_clean');
	
			if($this->form_validation->run()==FALSE)
			{
				$message['status']=false;
				$message['message'] = array(
                'name' 				=> form_error('name'),
                'email' 			=> form_error('email'),
                'password' 			=> form_error('password'),
                'reenterpassword' 	=> form_error('reenterpassword')
	            );
			}
			else
			{
				$login=$this->User_wm->signupUser();
					$message = array(
	                'status' 	=> true,
	                'message' 	=> $login
		            );
			}
			$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function signinUser_post()
	{
			$this->form_validation->set_error_delimiters('', '');//('<p class="text-white">', '</p>');
			$this->form_validation->set_rules('useremail','Email','trim|valid_email|required');
			$this->form_validation->set_rules('userpassword','Password','trim|required|xss_clean');
			
			if($this->form_validation->run()==FALSE)
			{
				$message['status']=false;
				$message['message'] = array(
                'useremail' 	=> form_error('useremail'),
                'userpassword' 	=> form_error('userpassword')
	            );
			}
			else
			{
				$login=$this->User_wm->signinUser();
				if($login==false)
				{
					$message = array(
	                'status' 	=> false,
	                'message' 	=> 'Invalid Email/Password Combination.'
		            );
				}
				else
				{
					$message = array(
	                'status' 	=> true,
	                'message' 	=> $login
		            );
				}
			}
			$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function forgotPassword_post()
	{
		$this->load->library('email');
		$this->load->library('MY_Email_Other');
		$this->form_validation->set_error_delimiters('', '');//('<p class="text-white">', '</p>');
		$this->form_validation->set_rules('email','Email','trim|valid_email|required');
		if($this->form_validation->run()==FALSE)
		{
			$message['status']=false;
			$message['message'] = array(
			'email' => form_error('email')
            );
		}
		else
		{
			$check=$this->User_wm->forgotPassword();
			if($check==false)
				{
					$message = array(
	                'status' 	=> false,
	                'message' 	=> 'This Email does not not Exist.'
		            );
				}
				else
				{
					$message = array(
	                'status' 	=> true,
	                'message' 	=> 'Email Sended sucessfully.'
		            );
				}
		}
        $this->set_response($message, REST_Controller::HTTP_OK);
	}
	
	function changepassword_post()
	{
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('userid', 'User ID', 'trim|required|xss_clean');
			$this->form_validation->set_rules('cpassword', 'Current Password', 'trim|required|min_length[6]|callback_check_current_password');
			$this->form_validation->set_rules('newpassword', 'New Password', 'trim|required|min_length[6]|xss_clean');
			$this->form_validation->set_rules('rnewpassword', 'Confirm Password', 'trim|required|matches[newpassword]');

			if($this->form_validation->run()==FALSE)
			{
				$message['status']=false;
				$message['message'] = array(
				'userid' 	=> form_error('userid'),
				'cpassword' 	=> form_error('cpassword'),
				'newpassword' 	=> form_error('newpassword'),
				'rnewpassword' 	=> form_error('rnewpassword')
	            );
			}
			else
			{
				$this->load->library('email');
				$this->load->library('MY_Email_Other');	
				$this->User_wm->changepassword();
				$message = array(
	                'status' 	=> true,
	                'message' 	=> 'Your Password has been updated.'
		        );
			}
            $this->set_response($message, REST_Controller::HTTP_OK);
	}

	function check_current_password()
	{
		return $this->User_wm->check_current_password();
	}

	function rfun()
	{

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
		{
			$user = $service->userinfo->get();
			//echo "<pre>";print_r($user);die;
			$Q=$this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where googleid="'.$user['id'].'"');
			if($Q->num_rows()>0)
			{
				$returnData=$Q->row_array();
				$this->db->where('id',$returnData['id']);
				$this->db->update('tbl_front_users',array('userimage'=>$user['picture'],'last_login'=>date('Y-m-d H:i:s')));
				$sessionArray=array(
					'fuserid'=>$returnData['id'],
					'name'=>ucwords($returnData['name']),
					'email'=>$returnData['email'],
					'last_login'=>$returnData['last_login'],
					'userimage'=>$user['picture'],
					'issocial'=>1,
					'askforemail'=>$user['isemail']
				);
			}
			else
			{

				$gender=0;
				if(isset($user['gender']) && $user['gender']!='')
				{
					if($user['gender']=='male' || $user['gender']=='Male')
					{
						$gender=1;
					}
					else if($user['gender']=='female' || $user['gender']=='Female')
					{
						$gender=2;
					}
				}
				$datetime=date('Y-m-d H:i:s');
				$insertdata=array(
					'name'=>$user['name'],
					'email'=>$user['email'],
					'logintype'=>2,
					'isactive'=>1,
					'created'=>$datetime,
					'password'=>'',
					'googleid'=>$user['id'],
					'facebookid'=>'',
					'userimage'=>$user['picture'],
					'phone'=>'',
					'gender'=>$gender,
					'logintype'=>1,
					'country_id'=>0,
					'dob'=>'',
					'isemail'=>0,
					'isloggedin'=>1
				);
				$this->db->insert('tbl_front_users',$insertdata);
				$userid=$this->db->insert_id();
				$sessionArray=array(
					'fuserid'=>$userid,
					'name'=>ucwords($user['name']),
					'email'=>$user['email'],
					'last_login'=>$datetime,
					'userimage'=>$user['picture'],
					'issocial'=>1,
					'askforemail'=>0
				);
			}
			$this->session->set_userdata($sessionArray);
			writeTripsInFile();
			loggedinUser(1);
			redirect($redirectURL);


		}

	}

	public function fblogin()
	{
		$redirectURL=site_url();
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
			{
					$Q=$this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where facebookid="'.$user['id'].'"');
					if($Q->num_rows()>0)
					{
						$returnData=$Q->row_array();

						if(isset($user['picture']['data']['url']) && $user['picture']['data']['url']!='')
						{
							$this->db->where('id',$returnData['id']);
							$this->db->update('tbl_front_users',array('userimage'=>$user['picture']['data']['url'],'last_login'=>date('Y-m-d H:i:s')));
						}


						$sessionArray=array(
							'fuserid'=>$returnData['id'],
							'name'=>ucwords($returnData['name']),
							'email'=>$returnData['email'],
							'last_login'=>$returnData['last_login'],
							'userimage'=>$user['picture']['data']['url'],
							'issocial'=>1,
							'askforemail'=>$user['isemail']
						);
					}
					else
					{
						$datetime=date('Y-m-d H:i:s');

						if(isset($user['email']) && $user['email']!='')
						{
							$uemail=$user['email'];
							$isemail=0;
						}
						else
						{
							$uemail=$user['id'].'@facebook.com';
							$isemail=1;
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

						$gender=0;
						if(isset($user['gender']) && $user['gender']!='')
						{
							if($user['gender']=='male' || $user['gender']=='Male')
							{
								$gender=1;
							}
							else if($user['gender']=='female' || $user['gender']=='Female')
							{
								$gender=2;
							}
						}

						$user_image='';
						if(isset($user['picture']['data']['url']) && $user['picture']['data']['url']!='')
						{
							$user_image=$user['picture']['data']['url'];
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
							'userimage'=>$userimage,
							'phone'=>'',
							'gender'=>$gender,
							'logintype'=>1,
							'country_id'=>0,
							'dob'=>'',
							'isemail'=>$isemail,
							'isloggedin'=>1
						);
						$this->db->insert('tbl_front_users',$insertdata);
						$userid=$this->db->insert_id();

						$sessionArray=array(
							'fuserid'=>$userid,
							'name'=>ucwords($user['first_name'].' '.$user['last_name']),
							'email'=>$uemail,
							'last_login'=>$datetime,
							'userimage'=>$user_image,
							'issocial'=>1,
							'askforemail'=>$isemail
						);
					}
					$this->session->set_userdata($sessionArray);
					writeTripsInFile();
					loggedinUser(1);
					redirect($redirectURL);

				}
			}

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
