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
			$this->session->set_userdata('randomstring',getRandomString());
		}

		if($this->session->userdata('storage'))
		{
			$this->session->unset_userdata('storage');
		}

		$this->session->set_userdata('isnewsearch','1');
		$this->session->unset_userdata('storage');
		$data['meta_title']='Online Travel Itinerary Planner :: Vacation, Holiday, Trip Itinerary Builder';
		$data['meta_keywords']='itinerary planner,travel itinerary planner,itinerary builder,trip itinerary planner,travel itinerary maker,holiday itinerary planner,vacation itinerary planner,online itinerary planner,itinerary trip planner';
		$data['meta_description']='Being your ideal holiday itinerary planner, we focus on simplifying the process of trip planning by using an online itinerary planning tool to customise your vacation itinerary.';


		$data['webpage'] = 'innerleft';
		$data['main'] = 'index';
		$data['tags'] = $this->Home_fm->getTags();
		$data['days'] = $this->Home_fm->getDays();
		//$data['weather'] = $this->Home_fm->getWeathers();
		//$data['budget'] = $this->Home_fm->getBudget();
		$data['blueprints']=$this->Home_fm->getTravelBluePrints();
		if($this->config->item('base_url')=='http://192.168.0.174/taxidio/')
		{
			$data['blogs'] = array();
		}
		else
		{
			$data['blogs'] = $this->Home_fm->getLatestBlogs();
		}
		//$data['traveltime'] = $this->Home_fm->getTravelTimeSlots();
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


		//echo "<pre>";print_r($_GET);die;
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="text-red-error">', '</p>');
		$getData = array(
	        'start_city' => $this->input->get('start_city',TRUE),
	        'days' => $this->input->get('days',TRUE),
	        'start_date' => $this->input->get('start_date',TRUE),
	        'weather' => $this->input->get('weather',TRUE),
	        'accomodation' => $this->input->get('accomodation',TRUE),
	        'budget' => $this->input->get('budget',TRUE),
	        'ocode' => $this->input->get('ocode',TRUE),
	        'traveltime'=>$this->input->get('traveltime',TRUE)
		);
		$this->form_validation->set_data($getData);
		$this->form_validation->set_rules('start_city', 'Start City', 'trim|required');
		$this->form_validation->set_rules('days', 'No Of Days', 'trim|required');
		$this->form_validation->set_rules('traveltime', 'Length Of Journey', 'trim|required');
		$this->form_validation->set_rules('weather', 'Temprature', 'trim|required');
		$this->form_validation->set_rules('accomodation', 'Accomodation Type', 'trim|required');
		$this->form_validation->set_rules('budget', 'Budget', 'trim|required');
		$this->form_validation->set_rules('ocode', 'Country Code', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Date Of Departure', 'trim|required');

		if($this->form_validation->run()==FALSE)
		{
			$this->session->set_userdata('norecordmsg',3);
			$this->index();
		}
		else
		{
			if($this->session->userdata('randomstring')=='')
			{
				$this->session->set_userdata('randomstring',getRandomString());
			}
			$data['webpage'] = 'country_recommendation';
			$data['main'] = 'country_recommendation';
			$data['countries'] = $this->Home_fm->getSingleCountries();
			//echo "<pre>";print_r($data['countries']);die;
			//$data['countries'] = array();
			if(isset($_GET['isdomestic']) && $_GET['isdomestic']==1)
			{
				//$countryrome2rioname=$this->Home_fm->getCountryCode();
				$countryrome2rioname=$_GET['ocode'];
				$countrySlug=$this->Home_fm->getCountrySlug($countryrome2rioname);
				if(count($countrySlug)<1 || count($data['countries'])<1)
				{
					$this->session->set_userdata('norecordmsg',1);
					redirect(site_url());
				}
				redirect(site_url('attractions').'/'.$_GET['token'].'/'.$countrySlug['slug']);
			}
		    //$data['multicountries']=array();
			$data['multicountries'] = $this->Home_fm->getMultiCountries();

			if(count($data['multicountries'])<1 && count($data['countries'])<1 )
			{
				$this->session->set_userdata('norecordmsg',1);
				redirect(site_url());
			}

			$this->session->set_userdata('multicountries',$data['multicountries']);
			$this->load->vars($data);
			$this->load->view('templates/innermaster');
		}




	}

	function getData()
	{

		if($this->input->is_ajax_request())
		{
			$searcharray=array();
			$findid=$_POST['id'];
			$token=$_POST['token'];
			$file_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$token.'/combinations');
			$file_decode=json_decode($file_encode,TRUE);

			$file_city_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$token.'/cities');
			$file_city_decode=json_decode($file_city_encode,TRUE);
			$data['cities']=$file_city_decode[$findid];
			$citids=array_column($data['cities'],'id');
			$data['cityimages']=$this->Home_fm->getCityImages($citids);
			//echo "<pre>";print_r($data['cityimages']);die;
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

	function updateFiles($attractioncountrieswithtime,$returnkey,$folder,$uniqueid)
	{

		 $file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry','r+');
		 $citydata=fgets($file);
		 fclose($file);
		 $cities=json_decode($citydata,TRUE);
		 $oneDimensionalArray = array_map('current', $attractioncountrieswithtime);
		 //echo "<pre>";print_r($attractioncountrieswithtime[$returnkey]);die;
		 $cities[$returnkey]=$attractioncountrieswithtime[$returnkey];
		 //echo "<pre>";print_r($cities);die;
		 $cities_encode=json_encode($cities);
		 $file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry','w+');
		 $citydata=fwrite($file,$cities_encode);
		 fclose($file);
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
				$data['countryid']=$returnkey;
				$cominineCountryidwithcityid=$returnkey.'-'.$firstcityid.'-'.$uniqueid;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


				if(!isset($returnkey) || $returnkey=='')
				{
					redirect(site_url());
				}

				$attractioncountrieswithtime=CalculateDistance($nr,$returnkey);
				$this->updateFiles($attractioncountrieswithtime,$returnkey,'files',$uniqueid);
				$attractioncountries=array();
				if(count($attractioncountrieswithtime[$returnkey]))
				{
					$attractioncountries=$attractioncountrieswithtime[$returnkey];
				}

				$data['webpage'] = 'attraction_listings';
				$data['main'] = 'attraction_listings';
				$data['attractioncities'] = $attractioncountries;
				$data['citypostid']=$cityfile= md5($attractioncountries[0]['id']);
				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data['countryimage']=$basic['countryimage'];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['cityimage']=$basic['cityimage'];
				$data['basiccityname']=$basic['city_name'];
				$data['countryconclusion']=$basic['country_conclusion'];
				$data['countrybanner']=$basic['countrybanner'];
				$countrandtype=$returnkey.'-single-'.$uniqueid;
				$data['secretkey']=string_encode($countrandtype);
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
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|min_length[5]|max_length[250]|is_unique[tbl_front_users.email]',array('is_unique'=>'This email address already exists.'));
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

		if($this->input->is_ajax_request())
		{
			echo $this->Home_fm->signinUser();
		}
		else
		{
			if($this->session->userdata('fuserid'))
			{
				redirect(site_url());
			}
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="text-white">', '</p>');
			$this->form_validation->set_rules('useremail','Email','trim|required');
			$this->form_validation->set_rules('userpassword','Password','trim|required');
			$this->form_validation->set_rules('g-recaptcha-response','g-recaptcha-response','required',array('required' => 'We love the idea of robots. But we need you to be a human to access this website.'));

			if($this->form_validation->run()==FALSE)
			{
				$this->auth();
			}
			else
			{
				$login=$this->Home_fm->signinUser();
				if($login==1)
				{
					if($this->session->userdata('backurl'))
					{
						$redirecturl=$this->session->userdata('backurl');
						$this->session->unset_userdata('backurl');
					}
					else
					{
						$redirecturl=site_url();
					}
					redirect($redirecturl);
				}
				else
				{
					$this->session->set_flashdata('error','Invalid Email/Password Combination.');
					redirect('auth');
				}
			}

		}
	}

	function hotels()
	{
		$data['webpage'] = 'hotels';
		$data['main'] = 'hotels';
		$data['meta_title']='Leading Hotel Search Engine | Holiday Hotel Recommendation';
		$data['meta_keywords']='hotel search engines';
		$data['meta_description']='Find the best hotels using our hotel search engine based on your budget, interests & style. Explore the hotel search engine today and get great discounts!';
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
		$data['meta_title']='Discounted Attraction Tickets For Advance Booking | Taxidio';
		$data['meta_keywords']='attraction tickets,discount attraction tickets,attraction tickets discount';
		$data['meta_description']='Get the best deals on attraction tickets for the destinations you want to visit. We help you get sightseeing & attraction tickets on discount before they sell out.';
		$data['topcities']=$this->Cms_fm->getTopCities();
		$data['topcountries']=$this->Cms_fm->getTopCountries();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function attractions_info($slug)
	{
		$data['webpage'] = 'country';
		$data['main'] = 'attractions_info';
		$data['info']=$info=$this->Home_fm->getAttractionInfo($slug);
		$data['meta_keywords']=$info['meta_keywords'];
		$data['meta_description']=$info['meta_description'];
		$data['meta_title']=$info['meta_title'];
		$keyword=$info['name'];
		$url = 'https://api.getyourguide.com/1/tours?q='.$keyword.'&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp';

		/*
		if(isset($info['latitude']) && $info['latitude']!='' && isset($info['longitude']) && $info['longitude']!='')
		{
			$lat=$info['latitude'];
			$lng=$info['longitude'];
			$url = 'https://api.getyourguide.com/1/tours?cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp&coordinates[lat]='.$lat.'&coordinates[long]='.$lng.'';
			echo $url;die;
		}
		else
		{
			$keyword=$info['name'];
			$url = 'https://api.getyourguide.com/1/tours?q='.$keyword.'&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp';
		}
		*/
		$keyword=$info['name'];
		$url = 'https://api.getyourguide.com/1/tours?q='.$keyword.'&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp';

		$content = file_get_contents($url);
		$json=json_decode($content, true);

		$data['json']=array();
		if(isset($json['data']['tours']) && count($json['data']['tours'])>0)
		{
			$data['json'] = $json['data']['tours'];
		}
		//echo "<pre>";print_r($data['json']);die;
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
		$this->load->library('email');
		$this->load->library('MY_Email_Other');
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
			$data['token']=$token;
			$this->load->vars($data);
			$this->load->view('templates/innermaster');
		}
	}

	function update_reset_password($id,$token)
	{
		if($this->input->post('btnsubmit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[30]');
			$this->form_validation->set_rules('cpassword', 'Password', 'trim|required|min_length[6]|max_length[30]|matches[password]',
        array('rule2' => 'Your new password does not match the one you re-entered'));
			if($this->form_validation->run()==FALSE)
			{
				$data['webpage']='reset_password';
				$data['main']='reset_password';
				$data['id']=$id;
				$data['token']=$token;
				$this->load->vars($data);
				$this->load->view('templates/innermaster');
			}
			else
			{
				$this->Home_fm->updatePassword($id,$token);
				$this->session->set_flashdata('passsuccess','Your password has been updated.');
				redirect('Home');
			}


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

		if (isset($_GET['ocode'])) {
		  $client->authenticate($_GET['ocode']);
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

		if (isset($_GET['ocode'])) {
		  $client->authenticate($_GET['ocode']);
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
			$this->session->unset_userdata('id');
			$this->session->unset_userdata('role_id');
			$user = $service->userinfo->get();
			//$this->session->sess_destroy();
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
					$this->session->unset_userdata('id');
				    $this->session->unset_userdata('role_id');
					$Q=$this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where facebookid="'.$user['id'].'"');
					//$this->session->sess_destroy();
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

	function recommendedformsubmit()
	{
		if($this->input->is_ajax_request())
		{
			$randomstring=$this->session->userdata('randomstring');
			if(!is_dir(FCPATH.'userfiles/files/'.$randomstring))
			{
					mkdir(FCPATH.'userfiles/files/'.$randomstring, 0777,true);
			}
			if(!is_dir(FCPATH.'userfiles/files/'.$randomstring.'/'.$_POST['token']))
			{
					mkdir(FCPATH.'userfiles/files/'.$randomstring.'/'.$_POST['token'], 0777,true);
			}
		}
	}


	function addSubscriber()
	{
		$this->load->library('email');
		$this->load->library('MY_Email');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		if($this->form_validation->run()==FALSE)
		{
			echo "2";
		}
		else
		{
			$returnval=$this->Home_fm->addSubscriber();
			echo $returnval;
		}
	}

	function auth($redirecturl='')
	{
		if($this->session->userdata('fuserid') || $this->session->userdata('id'))
		{
			redirect(site_url());
		}
		$data['webpage'] = 'auth';
		$data['main'] = 'auth';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function rememberUrl()
	{
		$this->session->set_userdata('socialurl',$_POST['url']);
		//echo $this->session->userdata('socialurl');die;
	}

	function getAutoSuggestion()
	{
		$q=$_POST['q'];
		$url = 'https://taxidio.rome2rio.com/api/1.4/json/Autocomplete?key=iWe3aBSN&query=' .$q . '';
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
