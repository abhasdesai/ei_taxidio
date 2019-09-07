<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Recommendation extends REST_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('webservices_models/recommendation_wm');
		$this->load->helper('app');
    }
    
    function getaccommodations_get()
    {
    	$data=$this->recommendation_wm->getAccomodationType();
    	//$data['tags']=$this->recommendation_wm->getTags();
    	$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
    	$this->set_response($message, REST_Controller::HTTP_OK);
    }

    public function countries_post()
    {
    	//print_r($_POST['tags']);echo "<br>";
    	if(isset($_POST['tags']) && !empty($_POST['tags']))
    	{
	    	$str=$_POST['tags'];
	    	$_POST['tags']=explode(",",$str);
    	}
    	//print_r($_POST['tags']);die;

		$countries= $this->recommendation_wm->getSingleCountries();
		//echo "<pre>";print_r($countries);die;
		$i=0;
		$data['singalcountries']=[];
		if(!empty($countries))
		{
			foreach ($countries as $mainkey => $mainlist)
			{
				$j=0;
				foreach ($mainlist as $subkey => $sublist)
				{
					$data['singalcountries'][$i]['country_id']=$countryID=$countries[$mainkey][$subkey]['country_id'];
					$data['singalcountries'][$i]['noofcities']=getcountrynoofCities($countryID);
					$data['singalcountries'][$i]['country_name']=$countries[$mainkey][$subkey]['country_name'];
					$data['singalcountries'][$i]['slug']=$countries[$mainkey][$subkey]['slug'];
					$data['singalcountries'][$i]['countrylatitude']=$countries[$mainkey][$subkey]['countrylatitude'];
					$data['singalcountries'][$i]['countrylongitude']=$countries[$mainkey][$subkey]['countrylongitude'];
					$data['singalcountries'][$i]['rome2rio_country_name']=$countries[$mainkey][$subkey]['rome2rio_country_name'];
					$data['singalcountries'][$i]['country_conclusion']=$countries[$mainkey][$subkey]['country_conclusion'];
					$data['singalcountries'][$i]['rome2rio_code']=$countries[$mainkey][$subkey]['rome2rio_code'];
					$data['singalcountries'][$i]['countryimage']="";
					if(isset($countries[$mainkey][$subkey]['countryimage']) && !empty($countries[$mainkey][$subkey]['countryimage']))
					{
						$data['singalcountries'][$i]['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
					}
					if(isset($countries[$mainkey][$subkey]['timetoreach']) && !empty($countries[$mainkey][$subkey]['timetoreach']))
					{
						$data['singalcountries'][$i]['timetoreach']=$countries[$mainkey][$subkey]['timetoreach'];
						$data['singalcountries'][$i]['actualtime']=$countries[$mainkey][$subkey]['actualtime'];	
					}
					$data['singalcountries'][$i]['cityData'][$j]['id']=$countries[$mainkey][$subkey]['id'];
					$data['singalcountries'][$i]['cityData'][$j]['totaltags']=$countries[$mainkey][$subkey]['totaltags'];
					$data['singalcountries'][$i]['cityData'][$j]['city_name']=$countries[$mainkey][$subkey]['city_name'];
					$data['singalcountries'][$i]['cityData'][$j]['cityslug']=$countries[$mainkey][$subkey]['cityslug'];
					$data['singalcountries'][$i]['cityData'][$j]['rome2rio_name']=$countries[$mainkey][$subkey]['rome2rio_name'];
					$data['singalcountries'][$i]['cityData'][$j]['latitude']=$countries[$mainkey][$subkey]['latitude'];
					$data['singalcountries'][$i]['cityData'][$j]['longitude']=$countries[$mainkey][$subkey]['longitude'];
					
					if(isset($countries[$mainkey][$subkey]['total_days']) && !empty($countries[$mainkey][$subkey]['total_days']))
					{
						$data['singalcountries'][$i]['cityData'][$j]['total_days']=$countries[$mainkey][$subkey]['total_days'];
					}
					$data['singalcountries'][$i]['cityData'][$j]['code']=$countries[$mainkey][$subkey]['code'];
					
					$data['singalcountries'][$i]['cityData'][$j]['cityimage']='';
					if(isset($countries[$mainkey][$subkey]['cityimage']) && !empty($countries[$mainkey][$subkey]['cityimage']))
					{
						$data['singalcountries'][$i]['cityData'][$j]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
					}
					$data['singalcountries'][$i]['cityData'][$j]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
				  $j++;
				}
				$i++;
			}
		}
		//echo count($data['singalcountries']);die;
		if (isset($_POST['isdomestic']) && $_POST['isdomestic'] == 1) {
			
			$countryrome2rioname = $_POST['ocode'];
			$countryId         = $this->recommendation_wm->getCountryId($countryrome2rioname);
			//echo "<pre>";print_r($countrySlug);die;
			if (count($countryId) < 1 || count($countries) < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No location were found!\nPlease Modify your inputs"
				);
			} else {
				$message['errorcode']= 1;
				$message['data']=array(
				'domesticCities' =>$this->attractions($countries,$countryId['id'])
				);
			}
		}
		else
		{
            $multicountries = $this->recommendation_wm->getMultiCountries();
            //print_r($multicountries);die;
            if (count($multicountries) < 1 && $i < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No countries were found!\nPlease Modify your inputs"
				);
            } else {
            	$data['multicountries']=null;
            	$i=0;
            	if(count($multicountries) > 0)
            	{
            		foreach ($multicountries as $key => $recommendationcountrylist) {
            			if($key!=="countries")
            			{
            				$data['multicountries']['combinations'][$i]["recommendation"]=$recommendationcountrylist;
            				$i++;
            			}
            			else
            			{
            				$data['multicountries']['countries']=$multicountries['countries'];
            			}
            		}
				}
				$message['errorcode']= 1;
				$message['data']=array(
				'singalcountries' =>$data['singalcountries'],
				'multicountries'	=>$data['multicountries']
				);
            }
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
    }

	function attractions($cityData,$countryid)
	{				
		
		$countries=CalculateDistance($cityData,$countryid);
		
		foreach ($countries as $mainkey => $mainlist)
		{
			$i=0;
			foreach ($mainlist as $subkey => $sublist)
			{
				$data['country_id']=$countryID=$countries[$mainkey][$subkey]['country_id'];
				$data['noofcities']=getcountrynoofCities($countryID);
				$data['country_name']=$countries[$mainkey][$subkey]['country_name'];
				$data['slug']=$countries[$mainkey][$subkey]['slug'];
				$data['countrylatitude']=$countries[$mainkey][$subkey]['countrylatitude'];
				$data['countrylongitude']=$countries[$mainkey][$subkey]['countrylongitude'];
				$data['rome2rio_country_name']=$countries[$mainkey][$subkey]['rome2rio_country_name'];
				$data['country_conclusion']=$countries[$mainkey][$subkey]['country_conclusion'];
				$data['rome2rio_code']=$countries[$mainkey][$subkey]['rome2rio_code'];
				$data['countryimage']="";
				if(isset($countries[$mainkey][$subkey]['countryimage']) && !empty($countries[$mainkey][$subkey]['countryimage']))
				{
					$data['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
				}
				if(isset($countries[$mainkey][$subkey]['timetoreach']) && !empty($countries[$mainkey][$subkey]['timetoreach']))
				{
					$data['timetoreach']=$countries[$mainkey][$subkey]['timetoreach'];
					$data['actualtime']=$countries[$mainkey][$subkey]['actualtime'];	
				}
				$data['cityData'][$i]['id']=$cityid=$countries[$mainkey][$subkey]['id'];
				$data['cityData'][$i]['totaltags']=$countries[$mainkey][$subkey]['totaltags'];
				$data['cityData'][$i]['city_name']=$countries[$mainkey][$subkey]['city_name'];
				$data['cityData'][$i]['cityslug']=$countries[$mainkey][$subkey]['cityslug'];
				$data['cityData'][$i]['rome2rio_name']=$countries[$mainkey][$subkey]['rome2rio_name'];
				$data['cityData'][$i]['latitude']=$countries[$mainkey][$subkey]['latitude'];
				$data['cityData'][$i]['longitude']=$countries[$mainkey][$subkey]['longitude'];
				
				//$data['cityData'][$i]['total_days']="";
				if(isset($countries[$mainkey][$subkey]['total_days']) && !empty($countries[$mainkey][$subkey]['total_days']))
				{
					$data['cityData'][$i]['total_days']=$countries[$mainkey][$subkey]['total_days'];
				}
				$data['cityData'][$i]['code']=$countries[$mainkey][$subkey]['code'];
				$data['cityData'][$i]['cityimage']='';
				if(isset($countries[$mainkey][$subkey]['cityimage']) && !empty($countries[$mainkey][$subkey]['cityimage']))
				{
					$data['cityData'][$i]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
				}
				$data['cityData'][$i]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
			  $i++;
			}
		}
		//echo $cityid;die;
		return $data;
	}

    function otherCities_post()
    {
    	$cityids=explode(',', $_POST['cityids']);
    	$data=getOtherCitiesOfThisCountry($_POST['country_id'],$cityids);
    	$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
    }

	function getAutoSuggestion_post()
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
   
	function getSuggestedCities_post()
	{
		$q=$_POST['key'];
		$data = $this->recommendation_wm->getSuggestedCities($q);
		if(!count($data))
		{
			$message=array(
				'errorcode' =>0,
				'message'	=>'No record found!'
				);
		}
		else
		{
			$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}
?>
