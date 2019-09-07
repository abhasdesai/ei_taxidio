<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . 'libraries/REST_Controller.php';

class Itineraries extends REST_Controller
{

  	public function __construct()
    {
  		  parent::__construct();
        $this->load->model('webservices_models/Itinerary_wm');
		$this->load->helper('app');
  	}

    public function index_post()
    {
    	$pageno=1;
    	if(isset($_POST['pageno']))
    	{
    		$pageno=$_POST['pageno'];
    	}
		
		if(empty((trim($pageno))))
		{
			$start_row=0;
		}
		else
		{
			$start_row=($pageno*5)-5;
		}
		$total_rows = $this->Itinerary_wm->countTotalPublicItineraries();

		$data=$this->Itinerary_wm->getTotalPublicItineraries(5,$start_row);
		foreach ($data as $key => $value) {
			if($data[$key]['trip_type']==2)
			{
				$citiorcountries=explode('-',$data[$key]['citiorcountries']);
				$citiesformulticountry=explode('-',$data[$key]['citiesformulticountry']);
				foreach ($citiorcountries as $i => $val) {
					$data[$key]['multi'][$i]['country']=$val;
					$data[$key]['multi'][$i]['city']=$citiesformulticountry[$i];
				}				
			}
			else
			{
				$data[$key]['multi']=array();//$data[$key]['']
			}
			if(isset($_POST['userid']) && !empty($_POST['userid']))
			{
				$data[$key]['issave']=$this->Itinerary_wm->checkTripSavedStatus($data[$key]['id'],$data[$key]['user_id']);
			}
			else
			{
				$data[$key]['issave']=0;
			}
			
		}
		
		$message=array(
				'errorcode' =>1,
				'total_page' => ceil($total_rows/5),
				'data'		=>$data
				);
		if($start_row==0)
		{
			$message['countries']=$this->Itinerary_wm->getAllPublicItinerariesCountry();
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function get_planned_itinerary_details_post()
	{
		 //$trip=$_POST['trip'];
		 $itinerary['reviews']=$this->Itinerary_wm->getItineraryInfo($_POST['trip']);
		 $trip_mode=$itinerary['reviews']['trip_mode'];
		 $user_trip_name=$itinerary['reviews']['user_trip_name'];
		 //echo "<pre>";print_r($itinerary);die;
		 if(count($itinerary['reviews'])<1)
		 {
			 $message=array(
				'errorcode' =>0,
				'message'	=>'Something went wrong.'
				);
		 }
		 else
		 {
			 $this->Itinerary_wm->updateViews($itinerary['reviews']['id']);

			 if($itinerary['reviews']['trip_type']==1)
			 {
				  $data=$this->loadSingleCountryItinerary($itinerary['reviews']['id'],$trip_mode,$user_trip_name,$itinerary['reviews']['user_id']);
			 }
			 else if($itinerary['reviews']['trip_type']==2)
			 {
				  $data=$this->loadMultiCountryItinerary($itinerary['reviews']['id'],$trip_mode,$user_trip_name,$itinerary['reviews']['user_id']);
			 }
			 else
			 {
				  $data=$this->loadSearchedCityItinerary($itinerary['reviews']['id'],$trip_mode,$user_trip_name,$itinerary['reviews']['user_id']);
			 }
			 
				$message=array(
					'errorcode' =>1,
					'data'	=>$data
				);
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	 }

		function loadSingleCountryItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
		{
				$data['owner_id']=$user_id;
				$data['trip_mode']=$trip_mode;
			  	$data['itineraryid']=$itineraryid;
				$singlecountryinfo=$this->Itinerary_wm->getSingleCountryItineraryDetails($itineraryid);
				$data['trip_type']=$singlecountryinfo[0]['trip_type'];
				$country_id=$singlecountryinfo[0]['country_id'];
				
				$allCities=json_decode($singlecountryinfo[0]['singlecountry'],TRUE);
				$attractioncountries=$allCities[$country_id];
								
				$data['cityid']=$cityfile = $attractioncountries[0]['id'];
				$returnkey=$attractioncountries[0]['country_id'];
				$countrandtype=$returnkey.'-single';
				//$data['secretkey']=string_encode($itineraryid);
				$basic=getLatandLongOfCity($attractioncountries[0]['id']);
				$data['countryid']=$basic['country_id'];
				$data['cityimage']=$basic['cityimage']?$basic['cityimage']:'';
				$data['basiccityname']=$basic['city_name'];
				$data['country_name']=$basic['country_name'];
				$data['country_conclusion']=$basic['country_conclusion'];
				$data['countryimage']=$basic['countryimage']?$basic['countryimage']:'';
				$data['countrybanner']=$basic['countrybanner'];
				//echo "<pre>";print_r($attractioncountries);die;
				$data['singlecountry'] = $attractioncountries;
				foreach ($attractioncountries as $key => $cityid) {
					$city=getLatandLongOfCity($attractioncountries[$key]['id']);
					$data['singlecountry'][$key]['cityimage']=$city['cityimage']?$city['cityimage']:'';
					$data['singlecountry'][$key]['latitude']=$city['citylatitude'];
					$data['singlecountry'][$key]['longitude']=$city['citylongitude'];
					$data['singlecountry'][$key]['filestore']=$this->Itinerary_wm->getCitiesAttractions($attractioncountries[$key]['id'],$itineraryid,'tbl_itineraries_cities');
				}
				
				return $data;
		}

		function loadSearchedCityItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
		{
			  	$data['owner_id']=$user_id;
				$data['trip_mode']=$trip_mode;
				$data['itineraryid']=$itineraryid;
				$searchedcityinfo=$this->Itinerary_wm->getSearchedCityItineraryDetails($itineraryid);
				$data['trip_type']=$searchedcityinfo[0]['trip_type'];
				
				$cityfile=$searchedcityinfo[0]['city_id'];
				//echo $cityfile;die;
				$basic=getLatandLongOfCity($cityfile);
				$data['city']=$basic['city_name'];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['cityname']=$basic['city_name'];
				$data['country_name']=$basic['country_name'];
				$data['country_conclusion']=$basic['country_conclusion'];
				$data['countryimage']=$basic['countryimage']?$basic['countryimage']:'';
				$data['cityimage']=$basic['cityimage']?$basic['cityimage']:'';
				$data['countrybanner']=$basic['countrybanner'];
				$data['singlecountry']=$allCities=json_decode($searchedcityinfo[0]['singlecountry'],TRUE);
				foreach ($allCities as $key => $cityid) {
					$city=getLatandLongOfCity($allCities[$key]['id']);
					$data['singlecountry'][$key]['cityimage']=$city['cityimage']?$city['cityimage']:'';
					$data['singlecountry'][$key]['latitude']=$city['citylatitude'];
					$data['singlecountry'][$key]['longitude']=$city['citylongitude'];
					$cityid=$allCities[$key]['id'];
					$condition="city_id=$cityid and itinerary_id=$itineraryid";
					$sdata=getrowbycondition('ismain,city_attractions','tbl_itineraries_searched_cities',$condition);
					$data['singlecountry'][$key]['ismain']=$sdata['ismain'];
					$data['singlecountry'][$key]['filestore']=json_decode($sdata['city_attractions'],TRUE);
				}
				//echo "<pre>";print_r($data);die;
				return $data;
		}

		function loadMultiCountryItinerary($itineraryid,$trip_mode,$user_trip_name,$user_id)
		{
			  	$data['owner_id']=$user_id;
				$data['itineraryid']=$itineraryid;
				$data['trip_mode']=$trip_mode;
				$itiinfo=$this->Itinerary_wm->getAllMultiCountries($itineraryid);
				$data['trip_type']=$itiinfo['trip_type'];
				$countries=json_decode($itiinfo['multicountries'],TRUE);
				unset($countries[0]['encryptkey']);
				//echo "<pre>";print_r($itiinfo);die;
				$data['multicountries']=$multicountries=reset($countries);
				$cities=json_decode($itiinfo['cities'],TRUE);
				$firstkey=key($cities);
				//echo "<pre>";print_r($cities[$firstkey]);die;

				$data['countryid']=$countryid=$cities[$firstkey][0]['countryid'];

				$cityid=$cities[$firstkey][0]['id'];
				//$data['countryid']=$cities[$firstkey][0]['id'];
				$cityid = $cities[$firstkey][0]['id'];
				//$data['citypostid']=$cityfile;
				$basic=getLatandLongOfCity($cityid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['basiccityname']=$basic['city_name'];
				$data['countrybanner']=$basic['countrybanner']?$basic['countrybanner']:'';
				$data['countryid']=$basic['country_id'];
				$data['cityimage']=$basic['cityimage']?$basic['cityimage']:'';
				$data['countryimage']=$basic['countryimage']?$basic['countryimage']:'';
				$data['country_conclusion']=$basic['country_conclusion'];
				$data['country_name']=$basic['country_name'];
				foreach ($multicountries as $key => $country) {
					$data['multicountries'][$key]['cityData']=$cityData=$cities[$multicountries[$key]['country_id']];
					foreach ($cityData as $i => $city) {
						$city=getLatandLongOfCity($city['id']);
						$data['multicountries'][$key]['cityData'][$i]['cityimage']=$city['cityimage']?$city['cityimage']:'';
						$data['multicountries'][$key]['cityData'][$i]['latitude']=$city['citylatitude'];
						$data['multicountries'][$key]['cityData'][$i]['longitude']=$city['citylongitude'];
						$data['multicountries'][$key]['cityData'][$i]['filestore']=$this->Itinerary_wm->getCitiesAttractionsMultiCountry($city['id'],$itineraryid);
					}
				}
				return $data;
		}

		function copy_itinerary_post()
		{
          		$this->load->model('webservices_models/Trip_wm');
          		$this->load->model('webservices_models/Account_wm');
				$data=$this->Itinerary_wm->copy_itinerary();
				if($data!=1)
				{
					$message = array(
			        'errorcode'	=> 0,
			        'message' => 'Oopp..!Something went wrong.'
			        );
				}
				else
				{
					$message = array(
			        'errorcode'	=> 1,
			        'message' => 'Trip has been saved to your account.'
			        );
				}
			    $this->set_response($message, REST_Controller::HTTP_OK);
		}

}
