<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Attractions extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('webservices_models/attractions_wm');
		$this->load->helper('app');
		//$this->load->library('form_validation');
	}

	public function index()
	{

	}

    function getAttractions_post()
    {
    	$tags=array();
    	if(isset($_POST['tags']) && !empty($_POST['tags']))
    	{
    		$tags=explode(',', $_POST['tags']);
    	}
    	
    	$json_decode=json_decode($_POST['countryidwithcityid'],true);

    	if(count($json_decode))
    	{
    		foreach ($json_decode as $key => $value) {
	    		$data[$key]['country_id']=$value['country_id'];
	    		$cityid=explode(',',$value['cityid']);
	    		foreach ($cityid as $i => $id) {
		    		$data[$key]['attractions'][$i]['cityid']=$id;
		    		$data[$key]['attractions'][$i]['filestore'] = getUserRecommededAttractionsForCity(md5($id),$tags);
	    		}
    		}
	    	$message=array(
					'errorcode' =>1,
					'data'	=>$data
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
    
	function getAttractionData_post()
	{
		$citydetails=$this->attractions_wm->getCityDetails($_POST['attractionid']);
		$message=array(
				'errorcode' =>1,
				'data'	=>$citydetails
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function timeToreach_post()
	{
		$json_decode=json_decode($_POST['rome2rio_name'],true);
		$rome2rio_name=array_column($json_decode, 'name');

		$data=getShortestDistance($rome2rio_name);
		$message = array(
	            'errorcode'	=> 1,
	            'data' 	=> $data
	            );
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function tripTodestination_post()
	{
		//echo "asfd";print_r($_POST);die;

		if(isset($_POST['searchtags']) && !empty($_POST['searchtags']))
    	{
	    	$str=$_POST['searchtags'];
	    	$_POST['searchtags']=explode(",",$str);
    	}
    	else
    	{
    		$_POST['searchtags']=[];
    	}
    	
		$flag=1;
		$other=1;
			$searchcity=$data = $this->attractions_wm->getSearchedCity();
			if(count($data)<1)
			{
				$message = array(
	            'errorcode'	=> 0,
	            'message' 	=> 'No City Found'
	            );
			}
			else
			{
			$cityid=$data[0]['id'];
			$cityarray=$data;

				$length=count($cityarray)-1;
				$cityarray[$length]['sortorder']=0;
				$temparray=$cityarray;
				foreach ($temparray as $key => $list)
				{
					if(isset($list['city_conclusion']) && $list['city_conclusion']!='')
					{
						unset($temparray[$key]['city_conclusion']);
					}
					if(isset($list['country_conclusion']) && $list['country_conclusion']!='')
					{
						unset($temparray[$key]['country_conclusion']);
					}
				}

			
			$inputs=$_POST;//$this->getInputs($token);
			$ttldays=0;
			$other=0;
			$data[0]['message']="";
			$data[0]['modifyparameter']=0;
				if(isset($data[0]['totaldaysneeded']) && $data[0]['totaldaysneeded']!='')
				{
					foreach($searchcity as $list)
					{
						$ttldays=$list['totaldaysneeded'];
					}
					if($inputs['sdays']<$ttldays)
					{
						$data[0]['modifyparameter']=1;
						$data[0]['message']="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
						$data[0]['totaldaysneeded']=$ttldays;
						$other=0;
					}
					else
					{
						$other=1;
					}
				}
				else
				{
					foreach($searchcity as $list)
					{
						$ttldays=$list['total_days'];
					}
					
					if($inputs['sdays']<$ttldays)
					{
						$data[0]['modifyparameter']=1;
						$data[0]['message']="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
						$data[0]['totaldaysneeded']=$ttldays;
						$other=0;
					}
					else {
						$data[0]['totaldaysneeded']=0;
						$other=1;
					}
				}
				//echo $data[0]['message'];die;
			$cityfile=$data[0]['id'];
			$citymd5id=md5($cityfile);
						
			if($other==1)
			{
				$data[0]['othercities'] = $this->attractions_wm->getSearchedCityOther($searchcity);
			}
			else {
				$data[0]['othercities'] = array();
			}

			if($data[0]['othercities'])
			{
				$data[0]['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
			}

			$data[0]['filestore'] = getUserRecommededAttractionsForCity($citymd5id,$_POST['searchtags']);
			//print_r($data[0]['othercities']);
			$message = array(
	            'errorcode'	=> 1,
	            'data' 	=> $data[0]
	            );
			}
		
       $this->set_response($message, REST_Controller::HTTP_OK);

	}

	function getOtherCitiesforSearchedCity_post()
	{
		$inputs=json_decode($_POST['inputs'],TRUE);
		$_POST['sdestination']=$inputs["sdestination"];
		if(isset($inputs['searchtags'])){
			$_POST['searchtags']=json_decode($inputs['searchtags'],TRUE);
		}
		//echo $inputs['searchtags'];
		//print_r($_POST['searchtags']);die;
		$_POST['sdays']=$inputs['sdays'];
		
		$data = $this->attractions_wm->getSearchedCityOther(json_decode($_POST['cities'],TRUE),1);
		
		if(!empty($data))
		{
			$message = array(
		            'errorcode'	=> 1,
		            'data' 	=> $data
		            );
		}
		else
		{
			$message = array(
		            'errorcode'	=> 0,
		            'message' 	=> "No Extra City found"
		            );
		}
		
		 $this->set_response($message, REST_Controller::HTTP_OK);
	}

	public function checkCityCanBeVisited_post()
	{
		//$randomstring=$this->session->userdata('randomstring');
		$data['searchcity'] = $this->attractions_wm->getSearchedCity();
		$inputs=$_POST;
		$ttldays=0;
		$data['message']=array();
		if(count($data['searchcity']))
		{
			$msg='';
			if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
			{
				foreach($data['searchcity'] as $list)
				{
					$ttldays=$list['totaldaysneeded'];
				}

				if($inputs['sdays']<$ttldays)
				{
					//$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
					$citynameback=$data['searchcity'][0]['city_name'];
					$msg="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
				}
			}
			else
			{
				foreach($data['searchcity'] as $list)
				{
					$ttldays=$list['total_days'];
				}
				$inputs['sdays']=str_replace("+","",$inputs['sdays']);//substr($inputs['sdays'],0,2)
				if($inputs['sdays']<$ttldays)
				{
					//$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
					$citynameback=$data['searchcity'][0]['city_name'];
					$msg="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
				}
			}
		}
			
			$arr=array(
					'msg'=>$msg
			);
			$far=json_encode($arr);
			echo $far;
	}


}

/* End of file Attractions.php */
/* Location: ./application/controllers/Attractions.php */
