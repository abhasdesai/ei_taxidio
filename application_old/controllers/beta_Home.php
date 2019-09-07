<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Home extends Front_Controller {

	public function __construct() {
		parent::__construct();
	}


	
	
	function index() 
	{
		if($this->session->userdata('randomstring')=='')
		{
			$this->session->set_userdata('randomstring',date('Y-m-d').'_'.getRandomString().'_'.rand(10,999999));
		}
		$this->session->set_userdata('isnewsearch','1');
		$this->session->unset_userdata('storage');
		$data['webpage'] = 'innerleft';
		$data['main'] = 'index';
		$data['tags'] = $this->Home_fm->getTags();
		$data['days'] = $this->Home_fm->getDays();
		$data['weather'] = $this->Home_fm->getWeathers();
		$data['budget'] = $this->Home_fm->getBudget();
		$data['traveltime'] = $this->Home_fm->getTravelTimeSlots();
		$data['accomodation'] = $this->Home_fm->getAccomodationType();
		$this->load->vars($data);
		$this->load->view('templates/homemaster');
	}

	

	function deleteAllFolderFiles()
	{
		if(is_dir(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring')))
		{
			$files = glob(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }	
			}
		}
	}

	function country_recommendation() {
		if($this->session->userdata('randomstring')=='')
		{
			$this->session->set_userdata('randomstring',date('Y-m-d').'_'.getRandomString().'_'.rand(10,999999));
		}
		if($this->session->userdata('isnewsearch')==1)
		{
			$this->deleteAllFolderFiles();
			$this->session->set_userdata('isnewsearch',2);
		}
		//$this->session->set_userdata('isnewsearch',1);
		$data['webpage'] = 'country_recommendation';
		$data['main'] = 'country_recommendation';
		$data['countries'] = $this->Home_fm->getSingleCountries();
		if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
		{
			$countryrome2rioname=$this->Home_fm->getCountryCode();
			$countrySlug=$this->Home_fm->getCountrySlug($countryrome2rioname);
			if(count($countrySlug)<1 || count($data['countries'])<1)
			{
				//$this->session->set_flashdata('norecordmsg','Sorry..We dont have any city for you.');
				$this->session->set_userdata('norecordmsg',1);
				redirect(site_url());
			}
			redirect(site_url('attractions/singlecountry').'/'.$countrySlug['slug']);
		}
		$data['multicountries']=array();
		$data['multicountries'] = $this->Home_fm->getMultiCountries();
		
		if(count($data['multicountries'])<1 && count($data['countries'])<1 )
		{
			//$this->session->set_flashdata('norecordmsg','Sorry..We dont have any city for you.');
			$this->session->set_userdata('norecordmsg',1);
			redirect(site_url());
		}

		$this->session->set_userdata('multicountries',$data['multicountries']);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function getData()
	{

		if($this->input->is_ajax_request())
		{
			$searcharray=array();
			$findid=$_POST['id'];
			$file_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/combinations');
			$file_decode=json_decode($file_encode,TRUE);

			$file_city_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities');
			$file_city_decode=json_decode($file_city_encode,TRUE);
			$data['cities']=$file_city_decode[$findid];
			
			foreach($file_decode as $key=>$list)
			{

				foreach($list as $innerkey=>$innerlist)
				{	
					if($innerkey!=='encryptkey')
					{
						if($innerlist['country_id']==$findid)
						{
							$searcharray=$file_decode[$key][$innerkey]; 
							break;
						}	
					}
				}
				
			}
			

			$data["countrydetails"]=$searcharray;
			$output['body'] =$this->load->view('getData_ajax', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));	
		}
	}

	function attractions($uniqueid,$slug)
	{
		if($this->session->userdata('randomstring')=='')
		{
			redirect(site_url());
		}
		else if(!is_dir(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid))
		{
			redirect(site_url());
		}
		else
		{

				
				if($this->session->userdata("singlefirst")!=2)
				{
					$this->session->set_userdata('singlefirst',1);
				}	
				$data['uid']=$uniqueid;
				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry','r');
				$arr=fgets($file);
				fclose($file);
				$nr=json_decode($arr,TRUE);
				$returnkey='';
				$firstcityid='';
				foreach ($nr as $key => $list) 
				{
						  	
					foreach ($list as $keyinner => $innerlist) 
					{
						  if($innerlist['slug']==$slug)
						  {
						     $returnkey=$key;
						  	 $firstcityid=$list[0]['id'];
						  	 foreach($list as $ids)
						  	 {
						  	 	$idsArray[]=$ids['id'];
						  	 }
						  	 break;
						  }
					}
				}

				$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($slug);
				$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($returnkey,$idsArray);
				$data['country']=md5($returnkey);
				$cominineCountryidwithcityid=$returnkey.'-'.$firstcityid.'-'.$uniqueid;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				

				if(!isset($returnkey) || $returnkey=='')
				{
					redirect(site_url());
				}


				$attractioncountries=array();
				if(count($nr[$returnkey]))
				{
					$attractioncountries=$nr[$returnkey];
				}		

				$data['webpage'] = 'attraction_listings';
				$data['main'] = 'attraction_listings';
				$data['attractioncities'] = $attractioncountries;
				$data['countryimage']=$attractioncountries[0]['countryimage'];
				$data['latitude']=$attractioncountries[0]['citylatitude'];
				$data['longitude']=$attractioncountries[0]['citylongitude'];
				$cityfile = md5($attractioncountries[0]['id']);
				$data['citypostid']=$cityfile;
				$countrandtype=$returnkey.'-single-'.$uniqueid;
				$data['secretkey']=string_encode($countrandtype);
				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data['cityimage']=$basic['cityimage'];
				$data['basiccityname']=$basic['city_name'];
				$data['countryconclusion']=$data['countrynm']['country_conclusion'];
				$data['countrybanner']=$basic['countrybanner'];

				$returnflag=$this->Home_fm->getUserRecommededAttractionsForCountry($cityfile,$uniqueid);
				
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();
						foreach($attraction_decode as $k=>$v) 
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$cityfile,'w');


						
						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);			
				}
				$this->load->vars($data);
				$this->load->view('templates/innermaster');

				
		}
		
	}


	function getCityAttractions()
	{
		if($this->input->is_ajax_request())
		{
			$cityfile=$_POST['id'];
			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['basiccityname']=$basic['city_name'];
			if($basic['cityimage']!='')
			{
				$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			}
			else
			{
				$data['cityimage']=site_url('assets/images/cairo.jpg');
			
			}
			//print_r($basic['cityimage']);die;
			$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
			$data['secretkey']=string_encode($countrandtype);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			
			$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($data['basiccityname']).'/'.$data['longitude'].'/'.$data['latitude'];

			$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'].'-'.$_POST['uniqueid'];
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
			$returnflag=$this->Home_fm->getUserRecommededAttractionsForCountry($cityfile,$_POST['uniqueid']);
			if($returnflag==1)
			{
			   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			
			}
			else
			{
				$filestore= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
				$attraction_decodfile=json_decode($filestore,TRUE);
				$attraction_decode=$this->Attraction_fm->haversineGreatCircleDistance($attraction_decodfile);


				$sort = array();
				foreach($attraction_decode as $k=>$v) 
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				}
				
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);

				$data['filestore']=json_encode($attraction_decode);			
			}

			$data['cityid']=$cityfile;
			$output['body'] =$this->load->view('getMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}	
		else
		{
			//redirect(site_url());
		}
		
	}



	function getAllAttractionsOfCity()
	{
		$data['ountrydetails']=array();
		$cityfile=$_POST['id'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
		$data['secretkey']=string_encode($countrandtype);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{

			$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);

			$finalsort = array();
		    foreach($attraction_decode as $k=>$v) 
		    {
			   $finalsort['distance'][$k] = $v['distance'];
		    }
		    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);
			
			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
				
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);	


		}
		else
		{
			$this->Home_fm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
		}
		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}

	function getAttractionData()
	{

		$citydetails=$this->Home_fm->getCityDetails($_POST['attractionid']);
		echo $citydetails;

	}

	function getSuggestedCities()
	{
		if($this->input->is_ajax_request())
		{		
			$q=$_POST['q'];	
			$query = $this->Home_fm->getSuggestedCities($q);
		}
	}

	function haversineGreatCircleDistance(
  		$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		 $rad = M_PI / 180;
        return acos(sin($latitudeTo*$rad) * sin($latitudeFrom*$rad) + cos($latitudeTo*$rad) * cos($latitudeFrom*$rad) * cos($longitudeTo*$rad - $longitudeFrom*$rad)) * 6371;
	
	}


	function signupUser()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name','Name','trim|required|min_length[2]|max_length[150]');
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|min_length[5]|max_length[250]|is_unique[tbl_front_users.email]',array('is_unique'=>'This Email Already Exists.'));
			$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
			$this->form_validation->set_rules('reenterpassword','Confirm Password','trim|required|matches[password]');

			if($this->form_validation->run()==FALSE)
			{
				echo validation_errors();
			}
			else
			{
				$this->Home_fm->signupUser();
				echo "1";die;
			}

		}	
		else
		{
			redirect(site_url());
		}
	}

	function signinUser()
	{
		echo $this->Home_fm->signinUser();
	}

	function hotels()
	{
		$data['webpage'] = 'hotels';
		$data['main'] = 'hotels';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function planneditineraries()
	{
		$data['webpage'] = 'planneditineraries';
		$data['main'] = 'planneditineraries';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	
	function allattractions()
	{
		$data['webpage'] = 'allattractions';
		$data['main'] = 'allattractions';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function city($slug)
	{
		$data['webpage'] = 'city';
		$data['main'] = 'city';
		$data['city']=$this->Home_fm->getCityCMSDetails($slug);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	
	function country($slug){
		$data['webpage'] = 'country';
		$data['main'] = 'country';
		$data['country']=$this->Home_fm->getCountry($slug);
		if(count($data['country'])<1)
		{
			show_404();
		}
		$data['tags']=$this->Home_fm->getCountryTags($data['country']['id']);
		$data['cities']=$this->Home_fm->getCountryCitiesCovered($data['country']['id']);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}
	
	public function forgotPassword()
	{
		$check=$this->Home_fm->forgotPassword();
		echo $check;
	}
	function reset_password($id,$token)
	{
		$check=$this->Home_fm->checkExpireToken($id,$token);
		
		if($check==0)
		{
			show_404();
		}
		else
		{
			$data['webpage']='reset_password';
			$data['main']='reset_password';
			$data['id']=$id;
			$this->load->vars($data);
			$this->load->view('templates/innermaster');
		}
	}
	
	function update_reset_password()
	{
		if($this->input->post('btnsubmit'))
		{
			$this->Home_fm->updatePassword();
			$this->session->set_flashdata('success','Your password has been updated.');
			redirect('Home');
		}
	
	}


	function test1()
	{
		require_once(APPPATH.'libraries/Google/autoload.php');
		$redirect_uri="http://localhost/loginwithgmail/welcome/test";
		$client = new Google_Client();
		$client->setClientId('785302192201-lqaenhlboimp4m5gbs94nnu3iq292jdm.apps.googleusercontent.com');
		$client->setClientSecret('dlDazQOD6yho8FG1Zydws2rl');
		$client->setRedirectUri($redirect_uri);
		$client->addScope("email");
		$client->addScope("profile");
		$service = new Google_Service_Oauth2($client);

		if (isset($_GET['code'])) {
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
			$this->load->view('test',$data);
			
		}
		else
		{
			$user = $service->userinfo->get();
			echo "<pre>";print_r($user);die;
		} 
	}

	function test()
	{
		require_once(APPPATH.'libraries/Google/autoload.php');
		$redirect_uri="http://localhost/taxidio/home/test";
		$client = new Google_Client();
		$client->setClientId('984270153161-vd9jm2dh3kqq1jb9dnr7073kml6ak8gj.apps.googleusercontent.com');
		$client->setClientSecret('Da8AY7xnjjDKmwPs4miaiNOE');
		$client->setRedirectUri($redirect_uri);
		$client->addScope("email");
		$client->addScope("profile");
		$service = new Google_Service_Oauth2($client);

		if (isset($_GET['code'])) {
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
			$this->load->view('tets',$data);
			
		}
		else
		{
			$user = $service->userinfo->get();
			echo "<pre>";print_r($user);die;
		} 

		
	}


	function rfun()
	{

		$redirectURL=site_url();
		if($this->session->userdata('previousurl') && $this->session->userdata('previousurl')!='')
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
			
			$Q=$this->db->query('select id,name,email,last_login,userimage from tbl_front_users where googleid="'.$user['id'].'"');
			if($Q->num_rows()>0)
			{
				$returnData=$Q->row_array();
				$sessionArray=array(
					'fuserid'=>$returnData['id'],
					'name'=>ucwords($returnData['name']),
					'email'=>$returnData['email'],
					'last_login'=>$returnData['last_login'],
					'userimage'=>$user['userimage'],
				);
			}
			else
			{
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
					'userimage'=>'',
				);	
				$this->db->insert('tbl_front_users',$insertdata);
				$userid=$this->db->insert_id();
				$sessionArray=array(
					'fuserid'=>$userid,
					'name'=>ucwords($user['name']),
					'email'=>$user['email'],
					'last_login'=>$datetime,
					'userimage'=>$user['userimage'],
				);
			}
			$this->session->set_userdata($sessionArray);	
			redirect($redirectURL);

			
		}
		
	}

	public function fblogin()
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

	function searchformsubmit()
	{
		if($this->input->is_ajax_request())
		{
			$randomstring=$this->session->userdata('randomstring');
			mkdir(FCPATH.'userfiles/search/'.$randomstring, 0777,true);
			mkdir(FCPATH.'userfiles/search/'.$randomstring.'/'.$_POST['token'], 0777,true);
		}
	}


/*
	public function refresh(){
		$this->load->helper('captcha');
        // Captcha configuration
       $config = array(
	        'img_path'      => 'assets/captcha_images/',
            'img_url'       => site_url('assets/captcha_images').'/',
	        'font_path'     => './path/to/fonts/texb.ttf',
	        'img_width'     => '150',
	        'img_height'    => 30,
	        'expiration'    => 7200,
	        'word_length'   => 4,
	        'font_size'     => 20,
	        'img_id'        => 'Imageid',
	        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyz',

	        // White background and border, black text and red grid
	        'colors'        => array(
	                'background' => array(255, 255, 255),
	                'border' => array(255, 255, 255),
	                'text' => array(0, 0, 0),
	                'grid' => array(255, 40, 40)
	        )
		);
        $captcha = create_captcha($config);
        
        // Unset previous captcha and store new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        
        // Display captcha image
        echo $captcha['image'];
    }

    */

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
