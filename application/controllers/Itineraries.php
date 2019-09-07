<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Itineraries extends Front_Controller
{

  	public function __construct()
    {
  		  parent::__construct();
  	}

    public function planned_itineraries()
    {

			$data['webpage'] = 'Forum';
			$data['meta_title'] = 'Find Pre-Planned Travel Itineraries | Taxidio';
			$data['meta_description']='Taxidio is the best travel itinerary planner for all your needs. Find pre-planned suitable travel itineraries or plan you own custom travel itinerary today.';
			$data['main'] = 'itineraries/planned_itineraries';
			$data['countries']=$this->Itinerary_fm->getAllPublicItinerariesCountry();
			$this->load->vars($data);
			$this->load->view('templates/innermaster');

		}


		public function browse_itinerary()
		{
				//if($this->input->is_ajax_request())
				//{
						$start_row=$this->uri->segment(2);
						if(trim($start_row)=='')
						{
										$start_row=0;
						}
						$this->load->library('pagination');
						$config["base_url"] = site_url('browse-itinerary');

						$config["total_rows"] = $this->Itinerary_fm->countTotalPublicItineraries();
						$config["full_tag_open"] = "<ul class='pagination-custom'>";
						$config["full_tag_close"] = "</ul>";
						$config["num_tag_open"] = "<li class='pagination-item-custom'>";
						$config["num_tag_close"] = "</li>";
						$config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
						$config["cur_tag_close"] = "</a></span></li>";
						$config['prev_link'] = 'Previous';
						$config['next_link'] = 'Next';
						$config["prev_tag_open"] = "<li class='pagination-item-custom first'>";
						$config["prev_tag_close"] = "</li>";
						$config["next_tag_open"] = "<li class='pagination-item-custom last'>";
						$config["next_tag_close"] = "</li>";
						$config["first_link"] = "<li style='float:left'>&lsaquo; First";
						$config["first_link"] = "</li>";
						$config["last_link"] = "<li>Last &rsaquo;";
						$config["last_link"] = "</li>";
						$config['per_page'] = 10;
						$this->pagination->initialize($config);
						$data['public_itineraries']=$this->Itinerary_fm->getTotalPublicItineraries($config["per_page"],$start_row);
						$data["pagination"]=$this->pagination->create_links();
						$_html=$this->load->view('itineraries/loadItineraries', $data,TRUE);
						//echo "<pre>";print_r($data['public_itineraries']);die;
						echo $_html;

				//}

		}

		public function planned_itinerary_forum($trip)
		{
				 $data['reviews']=$this->Itinerary_fm->getItineraryInfo($trip);
				 $trip_mode=$data['reviews']['trip_mode'];
				 $user_trip_name=$data['reviews']['user_trip_name'];
				 if(count($data['reviews'])<1)
				 {
					 show_404();
				 }
				 $this->Itinerary_fm->updateViews($data['reviews']['id']);

				 if($data['reviews']['trip_type']==1)
				 {
					 	$this->loadSingleCountryItinerary($data['reviews']['id'],$trip_mode,$user_trip_name,$data['reviews']['user_id']);
				 }
				 else if($data['reviews']['trip_type']==2)
				 {
					  $this->loadMultiCountryItinerary($data['reviews']['id'],$trip_mode,$user_trip_name,$data['reviews']['user_id']);
				 }
				 else
				 {
					  $this->loadSearchedCityItinerary($data['reviews']['id'],$trip_mode,$user_trip_name,$data['reviews']['user_id']);
				 }

		 }


			function loadSingleCountryItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
			{
					$data['owner_id']=$user_id;
					$data['trip_mode']=$trip_mode;
				  $data['itineraryid']=$itineraryid;
					$data['meta_title']=$user_trip_name;
					$singlecountryinfo=$this->Itinerary_fm->getSingleCountryItineraryDetails($itineraryid);
					$country_id=$singlecountryinfo[0]['country_id'];
					$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($country_id);
					$data['country']=md5($country_id);
					$allCities=json_decode($singlecountryinfo[0]['singlecountry'],TRUE);
					$attractioncountries=$allCities[$country_id];
					//echo "<pre>";print_r($attractioncountries);die;
					$data['webpage'] = 'planneditineraries';
					$data['main'] = 'itineraries/loadSingleCountryItinerary';
					$data['attractioncities'] = $attractioncountries;
					$data['citypostid']=$cityfile = $attractioncountries[0]['id'];
					$returnkey=$attractioncountries[0]['country_id'];
					$countrandtype=$returnkey.'-single';
					$data['secretkey']=string_encode($itineraryid);
					$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($attractioncountries[0]['id']));
					$data['countryid']=$basic['country_id'];
					$data['cityimage']=$basic['cityimage'];
					$data['basiccityname']=$basic['city_name'];
					$data['countryname']=$basic['country_name'];
					$data['countryconclusion']=$basic['country_conclusion'];
					$data['countrybanner']=$basic['countrybanner'];
					$data['latitude']=$basic['citylatitude'];
					$data['longitude']=$basic['citylongitude'];
					$data['filestore']=$this->Itinerary_fm->getCitiesAttractions($cityfile,$itineraryid,'tbl_itineraries_cities');
					$data['userrating']=array();
					if($this->session->userdata('fuserid')!='')
					{
						$data['userrating']=$this->Itinerary_fm->getUserRating($itineraryid);
					}
					$this->load->vars($data);
					$this->load->view('templates/innermaster');
			}

			function getPublicSavedCityAttractions()
			{

				if($this->input->is_ajax_request())
				{
						$cityfile=$_POST['cityid'];
						$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
						if(count($data['basic'])<1)
						{
								echo "false";die;
						}
						$data['itineraryid']=$itineraryid=$_POST['iti'];
						$data['basiccityname']=$basic['city_name'];
						if($basic['cityimage']!='')
						{
							$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
						}
						else
						{
							$data['cityimage']=site_url('assets/images/cairo.jpg');

						}
						$data['latitude']=$basic['citylatitude'];
						$data['longitude']=$basic['citylongitude'];

						$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($data['basiccityname']).'/'.$data['longitude'].'/'.$data['latitude'];
						$data['filestore']=$this->Itinerary_fm->getCitiesAttractions($cityfile,$itineraryid,'tbl_itineraries_cities');

				}

				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('itineraries/getPublicSavedCityAttractions',$data,true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}

		function loadSearchedCityItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
		{
			  $data['owner_id']=$user_id;
				$data['trip_mode']=$trip_mode;
			  $data['webpage'] = 'planneditineraries';
				$data['meta_title']=$user_trip_name;
				$data['main'] = 'itineraries/loadSearchedCityItinerary';
				$data['itineraryid']=$itineraryid;
				$searchedcityinfo=$this->Itinerary_fm->getSearchedCityItineraryDetails($itineraryid);
				if(count($searchedcityinfo)<1)
				{
					show_404();
				}
				//echo "<pre>";print_r(json_decode($searchedcityinfo[0]['singlecountry']));die;
				$data['attractioncities']=$allCities=json_decode($searchedcityinfo[0]['singlecountry'],TRUE);
				$cityfile=$allCities[0]['id'];
				//echo $cityfile;die;
				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
				$data['city']=$basic['city_name'];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['cityname']=$basic['city_name'];
				$data['countryname']=$basic['country_name'];
				$data['countryconclusion']=$basic['country_conclusion'];
				$data['countryimage']=$basic['countryimage'];
				$data['cityimage']=$basic['cityimage'];
				$data['countrybanner']=$basic['countrybanner'];
				$data['userrating']=array();
				if($this->session->userdata('fuserid')!='')
				{
					$data['userrating']=$this->Itinerary_fm->getUserRating($itineraryid);
				}
				$data['filestore']=$this->Itinerary_fm->getCitiesAttractions($cityfile,$itineraryid,'tbl_itineraries_searched_cities');
				$this->load->vars($data);
				$this->load->view('templates/innermaster');

		}


		function getPublicSavedSingleCityAttractions()
		{
				if($this->input->is_ajax_request())
				{

					$data['itineraryid']=$itineraryid=$_POST['iti'];
					$cityfile=$_POST['cityid'];
					$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
					if($basic['cityimage']!='')
					{
						$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
					}
					else
					{
						$data['cityimage']=site_url('assets/images/cairo.jpg');

					}

					$data['latitude']=$basic['citylatitude'];
					$data['longitude']=$basic['citylongitude'];
					$data['param']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$data['longitude'].'/'.$data['latitude'];
					$data['filestore']=$this->Itinerary_fm->getCitiesAttractions($cityfile,$itineraryid,'tbl_itineraries_searched_cities');
					$data['cityid']=$cityfile;
					$output['body'] =$this->load->view('itineraries/getPublicSavedSingleCityAttractions',$data,true);
					$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
  	}

		function loadMultiCountryItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
		{
			  $data['owner_id']=$user_id;
				$data['itineraryid']=$itineraryid;
				$data['trip_mode']=$trip_mode;
				$data['meta_title']=$user_trip_name;
				$itiinfo=$this->Itinerary_fm->getAllMultiCountries($itineraryid);
				$countries=json_decode($itiinfo['multicountries'],TRUE);
				$data['countries']=reset($countries);
				$cities=json_decode($itiinfo['cities'],TRUE);
				$firstkey=key($cities);
				//echo "<pre>";print_r($cities[$firstkey]);die;

				$data['countryid']=$countryid=$cities[$firstkey][0]['countryid'];

				$cityid=$cities[$firstkey][0]['id'];
				//$data['countryid']=$cities[$firstkey][0]['id'];
				$cityfile = $cities[$firstkey][0]['id'];
				$data['citypostid']=$cityfile;
				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['basiccityname']=$basic['city_name'];
				$data['countrybanner']=$basic['countrybanner'];
				$data['countryid']=$basic['country_id'];
				$data['cityimage']=$basic['cityimage'];
				$data['countryimage']=$basic['countryimage'];
				$data['countryconclusion']=$basic['country_conclusion'];
				$data['countryname']=$basic['country_name'];
				$data['attractioncities']=$cities=$cities[$firstkey];
				$data['userrating']=array();
				if($this->session->userdata('fuserid')!='')
				{
					$data['userrating']=$this->Itinerary_fm->getUserRating($itineraryid);
				}
				$data['filestore']=$this->Itinerary_fm->getCitiesAttractionsMultiCountry($cityfile,$itineraryid);
				$data['webpage'] = 'planneditineraries';
				$data['main'] = 'itineraries/loadMultiCountryItinerary';
				$this->load->vars($data);
				$this->load->view('templates/innermaster');

		}

		function getPublicSavedMulticountryCityAttractions()
		{
			if($this->input->is_ajax_request())
			{
					$data['itineraryid']=$itineraryid=$_POST['iti'];
					$cityfile=$_POST['cityid'];
					$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
					if($basic['cityimage']!='')
					{
						$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
					}
					else
					{
						$data['cityimage']=site_url('assets/images/cairo.jpg');
					}
					$data['latitude']=$basic['citylatitude'];
					$data['longitude']=$basic['citylongitude'];
					$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
					$data['filestore']=$this->Itinerary_fm->getCitiesAttractionsMultiCountry($cityfile,$itineraryid);
					$data['cityid']=$cityfile;
					$output['body'] =$this->load->view('itineraries/getPublicSavedMulticountryCityAttractions', $data, true);
					$this->output->set_content_type('application/json')->set_output(json_encode($output));
			  }

		}

		public function getNewCountryDataFromitinerary()
		{
				if($this->input->is_ajax_request())
				{

					$firstkey=$country_id=$_POST['countryid'];
					$data['itineraryid']=$itineraryid=$_POST['iti'];
					$itiinfo=$this->Itinerary_fm->getAllMultiCountries($itineraryid);
					$countries=json_decode($itiinfo['multicountries'],TRUE);
					$data['countries']=reset($countries);
					$cities=json_decode($itiinfo['cities'],TRUE);
					$cityfile = $cities[$firstkey][0]['id'];
					$data['citypostid']=$cityfile;
					$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
					$data['latitude']=$basic['citylatitude'];
					$data['longitude']=$basic['citylongitude'];
					$data['basiccityname']=$basic['city_name'];
					$data['countrybanner']=$basic['countrybanner'];
					$data['countryid']=$basic['country_id'];
					$data['country_name']=$basic['country_name'];
					$data['countryimage']=$basic['countryimage'];
					$data['countryconclusion']=$basic['country_conclusion'];
					$data['attractioncities']=$cities=$cities[$firstkey];
  					if($basic['cityimage']!='')
					  {
						  $data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
					  }
					  else
					  {
						  $data['cityimage']=site_url('assets/images/cairo.jpg');
					  }

 					  if($basic['countrybanner']!='')
					  {
							 $data['countrybanner']=site_url('userfiles/countries/banner').'/'.$basic['countrybanner'];
					  }
					  else
					  {
							 $data['countrybanner']=site_url('assets/images/countrynoimage.jpg');
					  }

						$data['filestore']=$this->Itinerary_fm->getCitiesAttractionsMultiCountry($cityfile,$itineraryid);
					  $output['body'] =$this->load->view('itineraries/getNewCountryDataFromitinerary', $data, true);
					  $this->output->set_content_type('application/json')->set_output(json_encode($output));
				}

		}



		public function loadQuestions()
		{

			if($this->input->is_ajax_request())
			{

				$data['iti']=$iti=$_POST['iti'];
				$checkiti=$this->Itinerary_fm->checkItineraryExist($iti);
				if($checkiti>0)
				{
					  $start_row=$this->uri->segment(3);
			          if(trim($start_row)=='')
			          {
			                  $start_row=0;
			          }

					  $this->load->library('pagination');
			          $config["base_url"] = site_url('Itineraries/loadQuestions');
			  		  $config["total_rows"] = $this->Itinerary_fm->countTotalQuestionOfItinerary($iti);
					  $config["full_tag_open"] = "<ul class='pagination-custom'>";
			          $config["full_tag_close"] = "</ul>";
			          $config["num_tag_open"] = "<li class='pagination-item-custom'>";
			          $config["num_tag_close"] = "</li>";
			          $config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
			          $config["cur_tag_close"] = "</a></span></li>";
			          $config['prev_link'] = 'Previous';
			          $config['next_link'] = 'Next';
			          $config["prev_tag_open"] = "<li class='pagination-item--wide first'>";
			          $config["prev_tag_close"] = "</li>";
			          $config["next_tag_open"] = "<li class='pagination-item--wide last'>";
			          $config["next_tag_close"] = "</li>";
			          $config["first_link"] = "<li style='float:left'>&lsaquo; First";
			          $config["first_link"] = "</li>";
			          $config["last_link"] = "<li>Last &rsaquo;";
			          $config["last_link"] = "</li>";
			          $config['per_page'] = 10;
			          $this->pagination->initialize($config);
			          $data["questions"] = $this->Itinerary_fm->getQuestionOfItinerary($iti,$config["per_page"],$start_row);
					  //echo $this->db->last_query();die;
					  //echo "<pre>";print_r($data["questions"]);die;
					  $data["pagination"]=$this->pagination->create_links();
			          $_html=$this->load->view('itineraries/loadQuestions', $data,TRUE);
			          echo $_html;
				}
				else
				{
					echo "1";
				}

			}

		}

		public function itinerary_discussion($id)
		{
				$data['webpage'] = 'Forum';
				$data['main'] = 'itineraries/itinerary_discussion';
				$data['question']=$this->Itinerary_fm->getQuestionDetails($id);
				if(count($data['question'])<1)
				{
					show_404();
				}
				$checkiti=$this->Itinerary_fm->checkItineraryExist($data['question']['itinerary_id']);
				if(count($checkiti)<1)
				{
					 show_404();
				}
				$data['comments']=$this->Itinerary_fm->getAllComments($id);
				$data['totalcomments']=$this->Itinerary_fm->countTotalComments($id);
				$this->load->vars($data);
				$this->load->view('templates/innermaster');
		}

		function postComment()
		{
				if($this->input->is_ajax_request())
				{
					$data['comments']=$this->Itinerary_fm->postComment();
					$output['body'] =$this->load->view('postComment_ajax', $data, true);
					$this->output->set_content_type('application/json')->set_output(json_encode($output));
				}
		}


		function deleteComment()
		{
			if($this->input->is_ajax_request())
			{
				$data['comments']=$this->Itinerary_fm->deleteComment();
				$output['body'] =$this->load->view('postComment_ajax', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
		}

		function editComment()
		{
			if($this->input->is_ajax_request())
			{
				$data=$this->Itinerary_fm->editcomment();

			}
		}

		function deleteQuestion()
		{
			if($this->input->is_ajax_request())
			{
				$data=$this->Itinerary_fm->deleteQuestion();
				echo $data;
			}
		}

		function copy_itinerary()
		{
				$data=$this->Itinerary_fm->copy_itinerary();
				echo json_encode($data);

		}

		function store_rating()
		{
				if($this->input->is_ajax_request())
				{
					$this->Itinerary_fm->store_rating();
				}

		}

}
