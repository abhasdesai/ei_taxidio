<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Recommendation extends REST_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('webservices_models/recommendation_wm');
        $this->load->model('webservices_models/master_model');
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
				$data['singalcountries'][$i]['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
				if(isset($countries[$mainkey][$subkey]['timetoreach']) && !empty($countries[$mainkey][$subkey]['timetoreach']))
				{
					$data['singalcountries'][$i]['timetoreach']=$countries[$mainkey][$subkey]['timetoreach'];
					$data['singalcountries'][$i]['actualtime']=$countries[$mainkey][$subkey]['actualtime'];	
				}
 				/*unset($countries[$mainkey][$subkey]['country_id']);
				 unset($cityArray[$mainkey][$subkey]['country_name']);
				 unset($cityArray[$mainkey][$subkey]['slug']);
				 unset($cityArray[$mainkey][$subkey]['countrylatitude']);
				 unset($cityArray[$mainkey][$subkey]['countrylongitude']);
				 unset($cityArray[$mainkey][$subkey]['rome2rio_country_name']);
				 unset($cityArray[$mainkey][$subkey]['country_conclusion']);
				 unset($cityArray[$mainkey][$subkey]['rome2rio_code']);*/
				$data['singalcountries'][$i]['cityData'][$j]['id']=$countries[$mainkey][$subkey]['id'];
				$data['singalcountries'][$i]['cityData'][$j]['totaltags']=$countries[$mainkey][$subkey]['totaltags'];
				$data['singalcountries'][$i]['cityData'][$j]['city_name']=$countries[$mainkey][$subkey]['city_name'];
				$data['singalcountries'][$i]['cityData'][$j]['cityslug']=$countries[$mainkey][$subkey]['cityslug'];
				$data['singalcountries'][$i]['cityData'][$j]['rome2rio_name']=$countries[$mainkey][$subkey]['rome2rio_name'];
				$data['singalcountries'][$i]['cityData'][$j]['latitude']=$countries[$mainkey][$subkey]['latitude'];
				$data['singalcountries'][$i]['cityData'][$j]['longitude']=$countries[$mainkey][$subkey]['longitude'];
				
				//$data['singalcountries'][$i]['cityData'][$j]['total_days']="";
				if(isset($countries[$mainkey][$subkey]['total_days']) && !empty($countries[$mainkey][$subkey]['total_days']))
				{
					$data['singalcountries'][$i]['cityData'][$j]['total_days']=$countries[$mainkey][$subkey]['total_days'];
				}
				$data['singalcountries'][$i]['cityData'][$j]['code']=$countries[$mainkey][$subkey]['code'];
				$data['singalcountries'][$i]['cityData'][$j]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
				$data['singalcountries'][$i]['cityData'][$j]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
			  $j++;
			}
			$i++;
		}
		//echo "<pre>";print_r($data['countries']);die;
		//$country_id=array_column($data['countries'],'country_id');print_r($country_id);die;
		//$this->master_model->setfullpathforfile($countries['country_id'],'countryimage',"userfiles/countries/",$data['countries']['country_id']);
		if (isset($_POST['isdomestic']) && $_POST['isdomestic'] == 1) {
			
			$countryrome2rioname = $_POST['ocode'];
			$countryId         = $this->recommendation_wm->getCountryId($countryrome2rioname);
			//echo "<pre>";print_r($countrySlug);die;
			if (count($countryId) < 1 || count($countries) < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No location were found"
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
            
            if (count($multicountries) < 1 && count($data['singalcountries']) < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No countries were found"
				);
            } else {
            	$data['multicountries']=null;
            	if(count($multicountries) > 0)
            	{
            		foreach ($multicountries as $key => $recommendationcountrylist) {
            			if($key!="countries")
            			{
            				$data['multicountries']['combinations'][]["recommendation"]=$recommendationcountrylist;
            			}
            			else
            			{
            				$data['multicountries']['countries']=$multicountries['countries'];
            			}
            		}
	            	//$data['multicountries']['countries']=$multicountries['countries'];
	            	
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
		/*$returnkey='';
		$firstcityid='';
		foreach ($cityData as $key => $list)
		{
			foreach ($list as $keyinner => $innerlist)
			{
				     $returnkey=$key;
				  	 $firstcityid=$list[0]['id'];
				  	 foreach($list as $ids)
				  	 {
				  	 	 $idsArray[]=$ids['id'];
				  	 }
				  	 //return $slug;
			}
		}*/
		//print_r($cityData);die;
		//$data=$this->recommendation_wm->getcountryData($countryid);
		
		//$data['otherCities']=getOtherCitiesOfThisCountry($returnkey,$idsArray);
		//$data['country']=md5($returnkey);
		//$data['countryid']=$returnkey;
		//$cominineCountryidwithcityid=$returnkey.'-'.$firstcityid;
		//$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


		//~ if(!isset($returnkey) || $returnkey=='')
		//~ {
			//~ return $data['Message']="No Attraction City has been found in your Countries yet.";
		//~ }

		$countries=CalculateDistance($cityData,$countryid);
		//print_r($attractioncountrieswithtime);die;
		//$this->updateFiles($attractioncountrieswithtime,$returnkey,'files',$uniqueid);
		/*$attractioncountries=array();
		if(count($attractioncountrieswithtime[$countryid]))
		{
			$attractioncountries=$attractioncountrieswithtime[$countryid];
		}
		$data['attractioncities'] = $attractioncountries;*/
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
				$data['countryimage']=$countries[$mainkey][$subkey]['countryimage'];
				if(isset($countries[$mainkey][$subkey]['timetoreach']) && !empty($countries[$mainkey][$subkey]['timetoreach']))
				{
					$data['timetoreach']=$countries[$mainkey][$subkey]['timetoreach'];
					$data['actualtime']=$countries[$mainkey][$subkey]['actualtime'];	
				}
 				/*unset($countries[$mainkey][$subkey]['country_id']);
				 unset($cityArray[$mainkey][$subkey]['country_name']);
				 unset($cityArray[$mainkey][$subkey]['slug']);
				 unset($cityArray[$mainkey][$subkey]['countrylatitude']);
				 unset($cityArray[$mainkey][$subkey]['countrylongitude']);
				 unset($cityArray[$mainkey][$subkey]['rome2rio_country_name']);
				 unset($cityArray[$mainkey][$subkey]['country_conclusion']);
				 unset($cityArray[$mainkey][$subkey]['rome2rio_code']);*/
				$data['cityData'][$i]['id']=$countries[$mainkey][$subkey]['id'];
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
				$data['cityData'][$i]['cityimage']=$countries[$mainkey][$subkey]['cityimage'];
				$data['cityData'][$i]['sortorder']=$countries[$mainkey][$subkey]['sortorder'];
			  $i++;
			}
		}
		//$this->master_model->setfullpathforfile($attractioncountries,'countryimage',"userfiles/countries/",$data['attractioncities']);
		//$this->master_model->setfullpathforfile($attractioncountries,'cityimage',"userfiles/cities/",$data['attractioncities']);
		//$data['cityinfo']=getcityinfo($attractioncountries[0]['id']);
		return $data;
	}

    function getattractions_post()
    {
    	$data=getcityinfo($_POST['city_id']);
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
    /*
     
    public function ccountries_post()
    {
   
		$data['countries'] = $this->recommendation_wm->getSingleCountries();
		
		if (isset($_POST['isdomestic']) && $_POST['isdomestic'] == 1) {
			
			$countryrome2rioname = $_POST['ocode'];
			$countrySlug         = $this->recommendation_wm->getCountrySlug($countryrome2rioname);
			
			if (count($countrySlug) < 1 || count($data['countries']) < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No location were found"
				);
			} else {
				$message['errorcode']= 1;
				$message['data']=array(
				'cityData' =>$this->attractions($data['countries'],$countrySlug['slug'])
				);
			}
		}
		else
		{
            $data['multicountries'] = $this->recommendation_wm->getMultiCountries();
            
            if (count($data['multicountries']) < 1 && count($data['countries']) < 1) {
				$message=array(
				'errorcode' =>0,
				'message'	=>"No countries were found"
				);
            } else {
				$message['errorcode']= 1;
				$message['data']=array(
				'countries' =>$data['countries'],
				'multicountries'	=>$data['multicountries']
				);
            }
		}
		$this->set_response($message, REST_Controller::HTTP_OK);
    }
    
    
	function attractions($cityData,$slug)
	{
        $this->load->model('webservices_models/master_model');
				
				$returnkey='';
				$firstcityid='';
				foreach ($cityData as $key => $list)
				{
					foreach ($list as $keyinner => $innerlist)
					{
						     $returnkey=$key;
						  	 $firstcityid=$list[0]['id'];
						  	 foreach($list as $ids)
						  	 {
						  	 	 $idsArray[]=$ids['id'];
						  	 }
						  	 //return $slug;
					}
				}
				//print_r($cityData);die;
				$data['countrynm']=$this->recommendation_wm->getCountryNameFromSlug($slug);
				$data['otherCities']=$this->recommendation_wm->getOtherCitiesOfThisCountry($returnkey,$idsArray);
				$data['country']=md5($returnkey);
				$data['countryid']=$returnkey;
				$cominineCountryidwithcityid=$returnkey.'-'.$firstcityid;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


				//~ if(!isset($returnkey) || $returnkey=='')
				//~ {
					//~ return $data['Message']="No Attraction City has been found in your Countries yet.";
				//~ }

				$attractioncountrieswithtime=CalculateDistance($cityData,$returnkey);
				//$this->updateFiles($attractioncountrieswithtime,$returnkey,'files',$uniqueid);
				$attractioncountries=array();
				if(count($attractioncountrieswithtime[$returnkey]))
				{
					$attractioncountries=$attractioncountrieswithtime[$returnkey];
				}
				$data['attractioncities'] = $attractioncountries;
				$this->master_model->setfullpathforfile($attractioncountries,'countryimage',"userfiles/countries/",$data['attractioncities']);
				$this->master_model->setfullpathforfile($attractioncountries,'cityimage',"userfiles/countries/",$data['attractioncities']);
				
				$data['citypostid']=$cityfile= md5($attractioncountries[0]['id']);
				$data['basic']=$basic=$this->recommendation_wm->getLatandLongOfCity($cityfile);
				$data['countryimage']=$basic['countryimage'];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['cityimage']=$basic['cityimage'];
				$data['basiccityname']=$basic['city_name'];
				$data['countryconclusion']=$basic['country_conclusion'];
				$data['countrybanner']=$basic['countrybanner'];
				$countrandtype=$returnkey.'-single-'.time();
				$data['secretkey']=string_encode($countrandtype);
				$filestore=$this->recommendation_wm->getUserRecommededAttractionsForCountry($cityfile);

						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();
						foreach($attraction_decode as $k=>$v)
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						
					    $data['filestore']=json_encode($attraction_decode);
				return $data;
	}
*/

	function getSuggestedCities_post()
	{
		$q=$_POST['q'];
		$data = $this->recommendation_wm->getSuggestedCities($q);
		$message=array(
			'errorcode' =>1,
			'data'	=>$data
			);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}
?>
