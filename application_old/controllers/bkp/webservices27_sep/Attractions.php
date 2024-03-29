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

	function attractionsFromGYG_post()
	{
		$cityid=md5($_POST['cityid']);
		$longitude=$_POST['longitude'];
		$latitude=$_POST['latitude'];
		$gyg2="https://api.getyourguide.com/1/tours?cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp&coordinates[lat]=".$latitude."&coordinates[long]=".$longitude."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $gyg2);
		$result=curl_exec($ch);
		$response = json_decode($result,true);
		curl_close($ch);

		$message['citydetails']=$this->attractions_wm->getBasicCityDetails($cityid);
		$message['response']=$response;

		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function cityAttractionFromGYG_post()
	{
        //$this->load->model('webservices_models/attractions_wm');
		$keyword=$_POST['city_name'];
		$longitude=$_POST['longitude'];
		$latitude=$_POST['latitude'];
		$gyg2="https://api.getyourguide.com/1/tours?q=".$keyword."&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp&coordinates[lat]=".$latitude."&coordinates[long]=".$longitude."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $gyg2);
		$result=curl_exec($ch);
		$response = json_decode($result,true);
		curl_close($ch);

		$cityname=urldecode($keyword);

		$message['citydetails']=$this->attractions_wm->getBasicCityDetailsFromName($cityname);
		$message['response']=$response;

		$this->set_response($message, REST_Controller::HTTP_OK);
	}

    function getAttractions_post()
    {
    	$tags=array();
    	if(isset($_POST['tags']) && !empty($_POST['tags']))
    	{
    		$tags=explode(',', $_POST['tags']);
    	}
    	$selectcol='id,country_id';
    	$table='tbl_city_master';
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
		$flag=1;
		$other=1;
			$data['searchcity'] = $this->attractions_wm->getSearchedCity();
			//print_r($data);die;
			if(count($data['searchcity'])<1)
			{
				$message = array(
	            'errorcode'	=> 0,
	            'message' 	=> 'No City Found'
	            );
			}
			else
			{
			$cityid=$data['searchcity'][0]['id'];
			$cityarray=$data['searchcity'];

				$length=count($cityarray)-1;
				$cityarray[$length]['sortorder']=0;
				$temparray=$cityarray;
				$temparray=$cityarray;
				foreach ($temparray as $key => $list)
				{
					if(isset($list['city_conclusion']) && $list['city_conclusion']!='')
					{
						unset($temparray[$key]['city_conclusion']);
					}
					if(isset($list['country_conclusion']) && $list['country_conclusion']!='')
					{
						unset($temparray[$key]['city_conclusion']);
					}
				}

			
			$inputs=$_POST;//$this->getInputs($token);
			$ttldays=0;
			$other=0;
			//$data['message']=array();
			//if(count($data['searchcity']))
			//{
				if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
				{
					foreach($data['searchcity'] as $list)
					{
						$ttldays=$list['totaldaysneeded'];
					}
					//echo $ttldays;die;
					/*if($inputs['sdays']<$ttldays)
					{
						$data['message']='';
						$other=0;
					}
					else
					{
						$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						$other=1;
					}*/
				}
				else
				{
					foreach($data['searchcity'] as $list)
					{
						$ttldays=$list['total_days'];
					}
					/*
					if($inputs['sdays']<$ttldays)
					{
						$data['message']='';
						$other=0;
					}
					else {
						$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						$other=1;
					}*/

				}


			

			$cityfile=$data['searchcity'][0]['id'];
			$citymd5id=md5($cityfile);
						
			if($other==1)
			{
				//$data['othercities'] = $this->attractions_wm->getSearchedCityOther($data['searchcity']);
			}
			else {
				//$data['othercities'] = array();
			}
			
			$data['filestore'] = getUserRecommededAttractionsForCity($citymd5id,$_POST['searchtags']);
			//$data=$data['searchcity'];
			$message = array(
	            'errorcode'	=> 1,
	            'data' 	=> $data
	            );
			}
		
       $this->set_response($message, REST_Controller::HTTP_OK);

	}

	function alterMainAttraction()
	{
		$cityfile=$_POST['cityid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
		$data['secretkey']=string_encode($countrandtype);
		$this->updateMainFile($cityfile,$_POST['attractionid']);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];

		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{

			$filestore = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);

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
		else
		{
			$this->attractions_wm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
		}
		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('getMainMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function updateMainFile($cityfile,$attractionid)
	{
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {

					if($value['properties']['attractionid']==$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;
						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							}
						}
						break;

					}

				}


			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}

	}


	function alterAttraction()
	{
		$cityfile=$_POST['cityid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
		$data['secretkey']=string_encode($countrandtype);
		$this->updateFile($cityfile,$_POST['attractionid']);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];

		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{

			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$attraction_decode=json_decode($data['filestore'],TRUE);

			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['distance'][$k] = $v['distance'];
			}

			array_multisort($sort['distance'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);


			$data['filestore'] = json_encode($attraction_decode);
		}

		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function updateFile($cityfile,$attractionid)
	{

		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {

					if($value['properties']['attractionid']==$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;
						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							}
						}

						break;
					}

				}


			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
	}




	function saveAllOrder()
	{
		$cityfile=$_POST['cityid'];
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$orders = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);

			/*echo "<pre>";
			print_r($_POST);
			print_r($decodeorders);die;*/

			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);
		}
	}

	function checkcityid($cityid)
	{
		$exp=explode(',',$this->session->userdata('checkcityid'));
		if(in_array($cityid,$exp))
		{
			return 1;
		}
		else
		{
			return 2;
		}
	}

	function addNewActivity()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->getAllActivities();
			}
			else
			{
				$this->getselectedActivities();
			}
		}
	}

	function getAllActivities()
	{
			$data=array();
			$cityfile=$_POST['citypostid'];
			if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
			{
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);


				$filedata= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);
				$location=explode(',',$_POST['location']);
				if($this->checkExistLocation($filedata_decode,$location[0])==1)
				{
					$newkey=count($filedata_decode)-1;
					$data[$newkey]['type']='Feature';
					$data[$newkey]['geometry']=array(
							'type'=>'Point',
							);
					$data[$newkey]['geometry']['coordinates']=array(
							'0'=>$_POST['exlong'],
							'1'=>$_POST['exlat'],
							);
					$data[$newkey]['properties']=array(
							  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
							  'tag_star'=>0,
							  'knownfor'=>0,
							  'known_tags'=>0,
							  //'address'=>'',
							  'getyourguide'=>0,
							  'attractionid'=>getRandomNumber(),
							  'useractivity'=>1,
							  'cityid'=>$_POST['citypostid'],
							  'isplace'=>1,
							  'category'=>0
							);
					$data[$newkey]['devgeometry']['devcoordinates']=array(
							'0'=>$basic['citylongitude'],
							'1'=>$basic['citylatitude'],
							);
					$data[$newkey]['isselected']=1;
					$data[$newkey]['order']=count($filedata_decode)+1;
					$data[$newkey]['tempremoved']=0;
					$data[$newkey]['distance']=99999999999;

					$filedata_decode_merge=array_merge($filedata_decode,$data);

					//echo "<pre>";
					//print_r($filedata_decode_merge);die;

					$sort = array();
					foreach($filedata_decode_merge as $k=>$v)
					{
						$sort['isselected'][$k] = $v['isselected'];
						$sort['order'][$k] = $v['order'];
					}
					array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);
				}
				else
				{
					$filedata_decode_merge=$filedata_decode;
				}

				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);
				$data=array();
				$data['basic']=$basic;
				$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
				$data['secretkey']=string_encode($countrandtype);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function getselectedActivities()
	{
			$data=array();
			$cityfile=$_POST['citypostid'];
			if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
			{
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);

				$filedata= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);
				$location=explode(',',$_POST['location']);

				if($this->checkExistLocation($filedata_decode,$location[0])==1)
				{
						$newkey=count($filedata_decode)-1;
						$data[$newkey]['type']='Feature';
						$data[$newkey]['geometry']=array(
								'type'=>'Point',
								);
						$data[$newkey]['geometry']['coordinates']=array(
								'0'=>$_POST['exlong'],
								'1'=>$_POST['exlat'],
								);
						$data[$newkey]['properties']=array(
								  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
								  'tag_star'=>0,
								  'knownfor'=>0,
								  'known_tags'=>0,
								  'address'=>'',
								  'getyourguide'=>0,
								  'attractionid'=>getRandomNumber(),
							      'useractivity'=>1,
								  'cityid'=>$_POST['citypostid'],
								  'isplace'=>1,
								  'category'=>0
								);
						$data[$newkey]['devgeometry']['devcoordinates']=array(
								'0'=>$basic['citylongitude'],
								'1'=>$basic['citylatitude'],
								);
						$data[$newkey]['isselected']=1;
						$data[$newkey]['order']=count($filedata_decode)+1;
						$data[$newkey]['tempremoved']=0;
						$data[$newkey]['distance']=99999999999;


						$filedata_decode_merge=array_merge($filedata_decode,$data);


						$sort = array();
						foreach($filedata_decode_merge as $k=>$v)
						{
							$sort['isselected'][$k] = $v['isselected'];
							$sort['order'][$k] = $v['order'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);
				}
				else
				{
					$filedata_decode_merge=$filedata_decode;
				}


				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
			    $data['secretkey']=string_encode($countrandtype);
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

				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));


			}
			else
			{
				echo "1";
			}
	}

	function checkExistLocation($arr,$loc)
	{
		$flag=1;
		foreach($arr as $list)
		{
			if($list['properties']['name']==$loc)
			{
				$flag=2;
				break;
			}

		}

		return $flag;
	}

	function deleteAllFolderFiles($token)
	{
		if(is_dir(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token))
		{
			$files = glob(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }
			}
			rmdir(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token);
		}


	}

	function searchformsubmit()
	{
		if($this->input->is_ajax_request())
		{
			$qs=$_SERVER["QUERY_STRING"];
		  parse_str($qs,$searchinput);
			$randomstring=$this->session->userdata('randomstring');
			if(!is_dir(FCPATH.'userfiles/search/'.$randomstring))
			{
				mkdir(FCPATH.'userfiles/search/'.$randomstring, 0777,true);
				mkdir(FCPATH.'userfiles/search/'.$randomstring.'/'.$searchinput['token'], 0777,true);
			}
			$this->checkCityCanBeVisited($searchinput['token'],$searchinput);
		}
	}


	public function checkCityCanBeVisited($token,$searchinput)
	{
		$flag=1;
		$data['uid']=$token;

		$randomstring=$this->session->userdata('randomstring');
		$data['searchdata'] = $this->attractions_wm->getSearchedCity($token,$searchinput);
		if(count($data['searchdata'])<1)
		{
			$this->session->set_userdata('norecordmsg',1);
			$status=false;
			$url='';
			$staytocurrenturl=1;
		}

		$cityid=$data['searchdata'][0]['id'];
		$cityarray=$data['searchdata'];

		if(!file_exists(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$cityid))
		{
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
					unset($temparray[$key]['city_conclusion']);
				}
			}
			$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$cityid,'w');
			fwrite($file,json_encode($temparray));
			fclose($file);
		}
		$this->createfileForFilename($token,$searchinput);


		$data['searchcity']=$this->getCitiesInFile($token);
		$inputs=$this->getInputs($token);
		$ttldays=0;
		$data['message']=array();
		//echo "<pre>";print_r($data['searchcity']);die;
		if(count($data['searchcity']))
		{
			if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
			{
				foreach($data['searchcity'] as $list)
				{
					$ttldays=$list['totaldaysneeded'];
				}
				$sq='';
				for($i=0;$i<count($inputs['searchtags']);$i++)
				{
					$sq .='&searchtags='.$inputs['searchtags'][$i];
				}
				$status=true;
				$queryString="sdestination=".$inputs['sdestination']."&sdays=".$inputs['sdays']."&sstart_date=".$inputs['sstart_date']."&token=".$token."".$sq;
				$url=site_url("cityAttractions?$queryString");
				$staytocurrenturl=2;
				$msg='';

				if($inputs['sdays']<$ttldays)
				{
					$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
					$citynameback=$data['searchcity'][0]['city_name'];
				  $sq='';


					$status=true;
					$queryString="sdestination=".$inputs['sdestination']."&sdays=".$inputs['sdays']."&sstart_date=".$inputs['sstart_date']."&token=".$token."".$sq;
					$url=site_url("cityAttractions?$queryString");
					$staytocurrenturl=1;
					//$msg="Your preferred number of days is less than the time suggested to tour the chosen destination(s). We recommend $ttldays number of days.";
					$msg="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
					//$this->deleteAllFolderFiles($token);
				}
				else
				{
					$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
				}
			}
			else
			{
				$status=true;
				$queryString="sdestination=".$inputs['sdestination']."&sdays=".$inputs['sdays']."&sstart_date=".$inputs['sstart_date']."&token=".$token;
				$url=site_url("cityAttractions?$queryString");
				$staytocurrenturl=2;
				$msg='';
				foreach($data['searchcity'] as $list)
				{
					$ttldays=$list['total_days'];
				}
				if($inputs['sdays']<$ttldays)
				{
					$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
					$citynameback=$data['searchcity'][0]['city_name'];
					//$this->deleteAllFolderFiles($token);
					$status=true;
					$queryString="sdestination=".$inputs['sdestination']."&sdays=".$inputs['sdays']."&sstart_date=".$inputs['sstart_date']."&token=".$token;
					$url=site_url("cityAttractions?$queryString");
					$staytocurrenturl=1;
					$msg="Based on the parameters selected, we think ". $inputs['sdestination'] ." can be explored well in ".$ttldays." days.";
				}
				else
				{
					$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
				}
			}
		}
			else {
				$status=true;
				$url='';
				$staytocurrenturl=2;
				$msg='';
			}
			$arr=array(
					'status'=>$status,
					'url'=>$url,
					'staytocurrenturl'=>$staytocurrenturl,
					'msg'=>$msg
			);
			$far=json_encode($arr);
			echo $far;
	}



	function getCitiesInFile($token)
	{

		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/'.$filename,'r');
		$cityarrayinfile=fgets($file);
		$cityarray=json_decode($cityarrayinfile,TRUE);
		fclose($file);

		return $cityarray;

	}

	function getInputs($token)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/inputs','r');
		$inputs=json_decode(fgets($file),TRUE);
		fclose($file);
		return $inputs;
	}

	function createfileForFilename($token,$searchinput)
	{
		$this->Attraction_fm->createfileForFilename($token,$searchinput);
	}

	function createsearchSearchFile($cityid,$cityarray)
	{
		$randomstring=$this->session->userdata('randomstring');
		/*
		if (!is_dir(FCPATH.'userfiles/search/'.$randomstring))
		{
			mkdir(FCPATH.'userfiles/search/'.$randomstring, 0777,true);
		}*/
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$cityid,'w');
		fwrite($file,json_encode($cityarray));
		fclose($file);
	}



	/* Single City */

	function getAllAttractionsOfSingleCity()
	{

		$cityfile=$_POST['id'];
		$data['uid']=$token=$_POST['uniqueid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['filenm']=$filenm=$this->getCitiesInFileName($token);
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile))
		{


			    $filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
				$attraction_decode=json_decode($filestore,TRUE);


				$finalsort = array();
			    foreach($attraction_decode as $k=>$v)
			    {
				   $finalsort['distance'][$k] = $v['distance'];
			    }
			    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);

			   // echo "<pre>";print_r($attraction_decode);die;

				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);
				$data['filestore'] =json_encode($attraction_decode);
		}

		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('attractions/singlecountry/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function getSingleCityAttractions()
	{
		$cityfile=$_POST['id'];
		$data['uid']=$token=$_POST['uniqueid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
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
		$filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);
		$sort = array();
		foreach($attraction_decode as $k=>$v)
		{
		    $sort['isselected'][$k] = $v['isselected'];
		    $sort['order'][$k] = $v['order'];
		}

		array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

		$data['filenm']=$filenm=$this->getCitiesInFileName($token);
		$data['countryid_encrypt']=string_encode($filenm.'-'.$basic['id'].'-'.$token);


		$data['filestore']=json_encode($attraction_decode);
		$data['cityid']=$cityfile;
		$output['body'] =$this->load->view('attractions/singlecountry/getMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function getUserAttractionsOfSingleCountry()
	{

		$cityfile=$_POST['id'];
		$data['uid']=$token=$_POST['uniqueid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		if($basic['cityimage']!='')
		{
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
		}
		else
		{
			$data['cityimage']=site_url('assets/images/cairo.jpg');

		}
		$data['filenm']=$filenm=$this->getCitiesInFileName($token);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['param']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$data['longitude'].'/'.$data['latitude'];

		$filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);

		$sort = array();
		foreach($attraction_decode as $k=>$v)
		{
		    $sort['isselected'][$k] = $v['isselected'];
		    $sort['order'][$k] = $v['order'];
		}

	    array_multisort($sort['order'], SORT_ASC,$attraction_decode);

		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
		$data['filestore']=json_encode($attraction_decode);
		$data['cityid']=$cityfile;
		$output['body'] =$this->load->view('attractions/singlecountry/getMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}

	function alterMainAttractionSingle()
	{
		$cityfile=$_POST['cityid'];
		$data['uid']=$token=$_POST['uniqueid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMainFileSingle($cityfile,$_POST['attractionid'],$token);
		}
		else
		{
			$this->updateFileSingle($cityfile,$_POST['attractionid'],$token);
		}
		$data['filenm']=$filenm=$this->getCitiesInFileName($token);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);

			/*
			$attraction_decode[0]['distance']=0;
			for($i=1;$i<count($attraction_decode);$i++)
			{
				$distance=$this->Attraction_fm->Attraction_fm->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);
				$attraction_decode[$i]['distance']=$distance;
			}

			$finalsort = array();
			foreach($attraction_decode as $k=>$v)
			{
				$finalsort['distance'][$k] = $v['distance'];
				$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
			}
			array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);

			*/



			if($_POST['ismain']==0)
			{
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['distance'][$k] = $v['distance'];
				}

				array_multisort($sort['distance'], SORT_ASC,$attraction_decode);
			}
			else
			{
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				}

				array_multisort($sort['isselected'], SORT_ASC,$sort['order'], SORT_ASC,$attraction_decode);
			}





			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

		}

		$data['cityid']=$cityfile;

		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$output['body']=$this->load->view('attractions/singlecountry/getMainMap', $data, true);
		}
		else
		{
			$output['body']=$this->load->view('attractions/singlecountry/getMap_All', $data, true);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function updateMainFileSingle($cityfile,$attractionid,$token)
	{

		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			//echo "<pre>";print_r($filestore);die;
			foreach ($filestore as $key => $value) {

					//if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					if($value['properties']['attractionid']==$attractionid)
					{
						//echo $value['properties']['attractionid'].'=='.$attractionid;
						//echo $value['properties']['name'];die;
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;
						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							}

						}
					}

				}
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateFileSingle($cityfile,$attractionid,$token)
	{

		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {

					if($value['properties']['attractionid']==$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;
						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							    $filestore[$key]['order']=99999;
							}

						}
					}

				}


			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
	}


	function saveOrder()
	{
		$cityfile=$_POST['cityid'];
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);


			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
			$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];
			$data['secretkey']=string_encode($countrandtype);

			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$filestore = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

			//echo "<pre>";
			//print_r($_POST);
			//print_r($attraction_decode);die;

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$_POST['uniqueid'].'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}

	}


	function saveOrderSingle()
	{
		$cityfile=$_POST['cityid'];
		$data['uid']=$token=$_POST['uniqueid'];
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);

			$data['filenm']=$filenm=$this->getCitiesInFileName($token);
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$filestore = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$output['body']=$this->load->view('attractions/singlecountry/getMap_All', $data, true);
			}
			else
			{
				$output['body']=$this->load->view('attractions/singlecountry/getMainMap', $data, true);
			}

			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}
	}



	function addNewActivityMulti()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->getAllActivitiesMulti();
			}
			else
			{
				$this->getselectedActivitiesMulti();
			}
		}
	}


	function getAllActivitiesMulti()
	{
			$data=array();
			$uniqueid=$_POST['uniqueid'];
			$cityfile=$_POST['citypostid'];
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$datasecretkey=$secretkey[$lastKey];
			$foldername=string_decode($datasecretkey);

			if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
			{
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);


				$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);

				$newkey=count($filedata_decode)-1;
				$location=explode(',',$_POST['location']);
				if($this->checkExistLocation($filedata_decode,$location[0])==1)
				{

					$data[$newkey]['type']='Feature';
					$data[$newkey]['geometry']=array(
							'type'=>'Point',
							);
					$data[$newkey]['geometry']['coordinates']=array(
							'0'=>$_POST['exlong'],
							'1'=>$_POST['exlat'],
							);
					$data[$newkey]['properties']=array(
							  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
							  'tag_star'=>0,
							  'knownfor'=>0,
							  'known_tags'=>0,
							  //'address'=>'',
							  'getyourguide'=>0,
							  'attractionid'=>getRandomNumber(),
							  'useractivity'=>1,
							  'cityid'=>$_POST['citypostid'],
							  'isplace'=>1,
							  'category'=>0
							);
					$data[$newkey]['devgeometry']['devcoordinates']=array(
							'0'=>$basic['citylongitude'],
							'1'=>$basic['citylatitude'],
							);
					$data[$newkey]['isselected']=1;
					$data[$newkey]['order']=count($filedata_decode)+1;
					$data[$newkey]['tempremoved']=0;
					$data[$newkey]['distance']=99999999999;

					$filedata_decode_merge=array_merge($filedata_decode,$data);


					$sort = array();
					foreach($filedata_decode_merge as $k=>$v)
					{
						$sort['isselected'][$k] = $v['isselected'];
						 $sort['order'][$k] = $v['order'];

					}
					array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);
				}
				else
				{
					$filedata_decode_merge=$filedata_decode;
				}

				$data=array();

				$data['basic']=$basic;

				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$foldername.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);


				$data['secretkey']=$datasecretkey;
				$data['uid']=$uniqueid;
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('attractions/multicountries/getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function getselectedActivitiesMulti()
	{
			$data=array();
			$cityfile=$_POST['citypostid'];
			$uniqueid=$_POST['uniqueid'];
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$datasecretkey=$secretkey[$lastKey];
			$foldername=string_decode($datasecretkey);

			if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
			{

				$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);
				$location=explode(',',$_POST['location']);
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
				if($this->checkExistLocation($filedata_decode,$location[0])==1)
				{

						$newkey=count($filedata_decode)-1;
						$data[$newkey]['type']='Feature';
						$data[$newkey]['geometry']=array(
								'type'=>'Point',
								);
						$data[$newkey]['geometry']['coordinates']=array(
								'0'=>$_POST['exlong'],
								'1'=>$_POST['exlat'],
								);
						$data[$newkey]['properties']=array(
								  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
								  'tag_star'=>0,
								  'knownfor'=>0,
								  'known_tags'=>0,
								  //'address'=>'',
								  'getyourguide'=>0,
								  'attractionid'=>getRandomNumber(),
							      'useractivity'=>1,
								  'cityid'=>$_POST['citypostid'],
								  'isplace'=>1,
								  'category'=>0
								);
						$data[$newkey]['devgeometry']['devcoordinates']=array(
								'0'=>$basic['citylongitude'],
								'1'=>$basic['citylatitude'],
								);
						$data[$newkey]['isselected']=1;
						$data[$newkey]['order']=count($filedata_decode)+1;
						$data[$newkey]['tempremoved']=0;
						$data[$newkey]['distance']=99999999999;

						$filedata_decode_merge=array_merge($filedata_decode,$data);


						$sort = array();
						foreach($filedata_decode_merge as $k=>$v)
						{
							$sort['isselected'][$k] = $v['isselected'];
							$sort['order'][$k] = $v['order'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);
						$countrandtype=$basic['country_id'].'-single-'.$_POST['uniqueid'];

						//echo "<pre>";print_r($filedata_decode_merge);die;
				}
				else
				{
					$filedata_decode_merge=$filedata_decode;
				}

				$data['basic']=$basic;
				$data['uid']=$uniqueid;
				$data['secretkey']=$datasecretkey;
				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);


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
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
				$output['body'] =$this->load->view('attractions/multicountries/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "1";
			}
	}







	function addNewActivitySingle()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->getAllActivitiesSingle();
			}
			else
			{
				$this->getselectedActivitiesSingle();
			}
		}
	}





	function getAllActivitiesSingle()
	{
			$cityfile=$_POST['citypostid'];
			$data['uid']=$uid=$_POST['uniqueid'];
			if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile))
			{
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);

				$newkey=count($filedata_decode)-1;
				$location=explode(',',$_POST['location']);

				$data[$newkey]['type']='Feature';
				$data[$newkey]['geometry']=array(
						'type'=>'Point',
						);
				$data[$newkey]['geometry']['coordinates']=array(
						'0'=>$_POST['exlong'],
						'1'=>$_POST['exlat'],
						);
				$data[$newkey]['properties']=array(
						  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
						  'tag_star'=>0,
						  'knownfor'=>0,
						  'known_tags'=>0,
						  //'address'=>'',
						  'getyourguide'=>0,
						  'attractionid'=>getRandomNumber(),
						  'useractivity'=>1,
						  'cityid'=>$_POST['citypostid'],
						  'isplace'=>1,
						  'category'=>0
						);
				$data[$newkey]['devgeometry']['devcoordinates']=array(
						'0'=>$basic['citylongitude'],
						'1'=>$basic['citylatitude'],
						);
				$data[$newkey]['isselected']=1;
				$data[$newkey]['order']=count($filedata_decode)+1;
				$data[$newkey]['tempremoved']=0;
				$data[$newkey]['distance']=99999999999;

				$filedata_decode_merge=array_merge($filedata_decode,$data);

				$finalsort = array();
			    foreach($filedata_decode_merge as $k=>$v)
			    {
				   $finalsort['distance'][$k] = $v['distance'];
			    }
			    array_multisort($finalsort['distance'], SORT_ASC,$filedata_decode_merge);

				/*
				$sort = array();
				foreach($filedata_decode_merge as $k=>$v)
				{
					$sort['isselected'][$k] = $v['isselected'];
					$sort['order'][$k] = $v['order'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);*/

				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$data=array();
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('attractions/singlecountry/getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function getselectedActivitiesSingle()
	{
			$cityfile=$_POST['citypostid'];
			$data['uid']=$uid=$_POST['uniqueid'];


			if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile))
			{
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile);
				$filedata_decode=json_decode($filedata,TRUE);
				$location=explode(',',$_POST['location']);
				$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
				if($this->checkExistLocation($filedata_decode,$location[0])==1)
				{
						$newkey=count($filedata_decode)-1;
						$data[$newkey]['type']='Feature';
						$data[$newkey]['geometry']=array(
								'type'=>'Point',
								);
						$data[$newkey]['geometry']['coordinates']=array(
								'0'=>$_POST['exlong'],
								'1'=>$_POST['exlat'],
								);
						$data[$newkey]['properties']=array(
								  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$location[0]),
								  'tag_star'=>0,
								  'knownfor'=>0,
								  'known_tags'=>0,
								  //'address'=>'',
								  'getyourguide'=>0,
								  'attractionid'=>getRandomNumber(),
							      'useractivity'=>1,
								  'cityid'=>$_POST['citypostid'],
								  'isplace'=>1,
						  		  'category'=>0
								);
						$data[$newkey]['devgeometry']['devcoordinates']=array(
								'0'=>$basic['citylongitude'],
								'1'=>$basic['citylatitude'],
								);
						$data[$newkey]['isselected']=1;
						$data[$newkey]['order']=count($filedata_decode)+1;
						$data[$newkey]['tempremoved']=0;
						$data[$newkey]['distance']=99999999999;

						$filedata_decode_merge=array_merge($filedata_decode,$data);


						$sort = array();
						foreach($filedata_decode_merge as $k=>$v)
						{
							$sort['isselected'][$k] = $v['isselected'];
							$sort['order'][$k] = $v['order'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$filedata_decode_merge);
				}
				else
				{
					$filedata_decode_merge=$filedata_decode;
				}

				//echo "<pre>";print_r($filedata_decode_merge);die;
				$data['basic']=$basic;

				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$uid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

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
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				$output['body'] =$this->load->view('attractions/singlecountry/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));


			}
			else
			{
				echo "1";
			}
	}



	/* Following code is for multiccountries  */

	function multicountries($uniqueid,$encryptkey)
	{

		if($this->session->userdata('randomstring')=='')
		{
			redirect(site_url());
		}

		$encryptkey=clearHashLink($encryptkey);
		$foldername=string_decode($encryptkey);
		$randomstring=$this->session->userdata('randomstring');
		$data['flagpage']=1;
		if($randomstring=='')
		{
			redirect(site_url());
		}
		$data['uid']=$uniqueid;
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'attractions/multicountries/attraction_listings';
		$countries=$data['countries']=$this->Attraction_fm->setMultiCountries($encryptkey,$uniqueid);


		$mergecountryids='';
		foreach($countries as $k=>$list)
		{
			if($k!=='encryptkey')
			{
				$mergecountryids .= $list['country_id'].'-';
			}
		}

		if($this->session->userdata("multifirst")!=2)
		{
			$this->session->set_userdata('multifirst',1);
		}

		$cityid=$countries[0]['id'];
		$data['countryid']=$countryid=$countries[0]['country_id'];
		$slug=$countries[0]['slug'];
		$data['countrynm']=$this->attractions_wm->getCountryNameFromSlug($slug);
		$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/cities');
		$filedata_decode=json_decode($filedata,TRUE);
		$idsArray=array();
		foreach($filedata_decode[$countryid] as $key=>$list)
		{
			$idsArray[]=$list['id'];
		}

		$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);
		$data['latitude']=$countries[0]['latitude'];
		$data['longitude']=$countries[0]['longitude'];
		$data['countryimage']=$countries[0]['countryimage'];
		$data['basiccityname']=$countries[0]['city_name'];
		$cityfile = md5($countries[0]['id']);
		$data['citypostid']=$cityfile;
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);


		$data['countrybanner']=$basic['countrybanner'];
		$data['cityimage']=$basic['cityimage'];
		$data['countryconclusion']=$data['countrynm']['country_conclusion'];

		$cominineCountryidwithcityid=$mergecountryids.''.$countries[0]['id'].'-'.$uniqueid;
		$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

		$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$randomstring,$uniqueid);
		//echo "<pre>";print_r($data['attractioncities']);die;

		$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername);
		if($returnflag==1)
		{
		   		$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
		}
		else
		{
				$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
				$attraction_decode=json_decode($filestore,TRUE);
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				    $sort['tag_star'][$k] = $v['properties']['tag_star'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);
			    $data['filestore']=json_encode($attraction_decode);
		}

		$this->load->vars($data);
		$this->load->view('templates/innermaster');

	}


	function getCitiesFromFile($countryid,$encryptkey,$randomstring,$uniqueid)
	{
		$foldername=string_decode($encryptkey);
		$cities_encode = file_get_contents(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/'.$foldername.'/cities');
		$cities_decode = json_decode($cities_encode,TRUE);
		//echo "<pre>";print_r($cities_decode[$countryid]);die;
		return $cities_decode[$countryid];

	}

	function getMultiCountriesFromFile($encryptkey,$uniqueid)
	{
		$encryptkey=clearHashLink($encryptkey);
		$randomstring=$this->session->userdata('randomstring');
		$combinations_encode = file_get_contents(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);
		$data['uid']=$uniqueid;
		$encryptionkeyArray=array();
		foreach($combinations_decode as $key=>$list)
		{
			if($list['encryptkey']==$encryptkey)
			{
				$encryptionkeyArray=$combinations_decode[$key];
			}
			else if(string_decode($list['encryptkey'])==string_decode($encryptkey))
			{
				$combinations_decode[$key]['encryptkey']=$encryptkey;
				$file=fopen(FCPATH.'userfiles/multicountries/'.$randomstring.'/'.$uniqueid.'/combinations','w');
				fwrite($file,json_encode($combinations_decode));
				fclose($file);
				$encryptionkeyArray=$combinations_decode[$key];

			}
		}

		return $encryptionkeyArray;
	}

	function multicity_attractions_ajax()
	{
		if($this->input->is_ajax_request())
		{
			$data['uid']=$uniqueid=$_POST['uniqueid'];
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];
			$foldername=string_decode($secretkey[$lastKey]);

			$cityfile=$_POST['id'];
			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
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

			$countries=$this->getMultiCountriesFromFile($secretkey[$lastKey],$uniqueid);

			$mergecountryids='';
			foreach($countries as $k=>$list)
			{
				if($k!=='encryptkey')
				{
					$mergecountryids .= $list['country_id'].'-';
				}
			}


			$cominineCountryidwithcityid=$mergecountryids.'-'.$basic['id'].'-'.$uniqueid;
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

			$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername);
			if($returnflag==1)
			{
			   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			}
			else
			{
					$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
					$attraction_decode=json_decode($filestore,TRUE);
					//$attraction_decode=$this->Attraction_fm->haversineGreatCircleDistance($attraction_decodfile,$dummyarr=array());


					$sort = array();
					foreach($attraction_decode as $k=>$v)
					{
					    $sort['isselected'][$k] = $v['isselected'];
					    $sort['order'][$k] = $v['order'];
					}



					array_multisort($sort['isselected'],SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

					$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
					fwrite($file,json_encode($attraction_decode));
					fclose($file);

				$data['filestore']=json_encode($attraction_decode);
			}

			$data['cityid']=$cityfile;
			$output['body'] =$this->load->view('attractions/multicountries/getMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
		else
		{
			//redirect(site_url());
		}

	}

	function getDataForNewCountry()
	{
		if($this->input->is_ajax_request())
		{
			$data['uid']=$uniqueid=$_POST['uniqueid'];
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];
			$foldername=string_decode($secretkey[$lastKey]);

			$md5country_id=$_POST['countryid'];
			$randomstring=$this->session->userdata('randomstring');
			$encryptkey=clearHashLink($_POST['key']);
			$countryArray=$data['countries']=$this->Attraction_fm->setMultiCountriesMD5($encryptkey,$uniqueid);

			$countries=array();

			foreach($countryArray as $newKey=>$newlist)
			{
				if(md5($newlist['country_id'])==$_POST['countryid'])
				{
					$countries = $countryArray[$newKey];
					break;
				}
			}




			$data['countrynm']=$this->attractions_wm->getCountryNameFromSlug($countries['slug']);
			$data['country_name']=$data['countrynm']['country_name'];
			$cityid=$countries['id'];
			$data['cityid']=md5($cityid);
			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity(md5($cityid));
			$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
			if($basic['cityimage']!='')
			{
				$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			}
			else
			{
				$data['cityimage']=site_url('assets/images/cairo.jpg');
			}
			//print_r($data['countrynm']);die;
			$url=site_url('country').'/'.$data['countrynm']['slug'];

			//$data['countryconclusion']=word_limiter($data['countrynm']['country_conclusion'],110).'<span class="citycon"><a class="readmore" href="'.$url.'" target="_blank">Read More</a></span>';

			$data['countryconclusion']=$data['countrynm']['country_conclusion'];

			// print_r($data['countryconclusion']);die;
			 $data['countryimage']=$data['countrynm']['countryimage'];
			 if($basic['countrybanner']!='')
			 {
				$data['countrybanner']=site_url('userfiles/countries/banner').'/'.$basic['countrybanner'];
			 }
			 else
			 {
				$data['countrybanner']=site_url('assets/images/countrynoimage.jpg');
			 }


			 $countryid=$countries['country_id'];


			$data['select']=0;

			$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities');
			$filedata_decode=json_decode($filedata,TRUE);
			$idsArray=array();
			foreach($filedata_decode[$countryid] as $key=>$list)
			{
				$idsArray[]=$list['id'];
			}


			$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);
			if(count($otherCities))
			{
				$options='<option value="">Select City</option>';
				foreach($otherCities as $list)
				{
					$combination=string_encode($list['country_id']."-".$list['id']);

					$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';

				}
			}
			else
			{
				$options=1;
			}

			$data['options']=$options;

			$data['latitude']=$countries['latitude'];
			$data['longitude']=$countries['longitude'];
			$cityfile = md5($countries['id']);
			$data['citypostid']=$cityfile;

			$countries=$this->getMultiCountriesFromFile($secretkey[$lastKey],$uniqueid);

			$mergecountryids='';
			foreach($countries as $k=>$list)
			{
				if($k!=='encryptkey')
				{
					$mergecountryids .= $list['country_id'].'-';
				}
			}


			$cominineCountryidwithcityid=$mergecountryids.'-'.$basic['id'].'-'.$uniqueid;
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

			$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$randomstring,$uniqueid);
			$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername);
			if($returnflag==1)
			{
			   		$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);

			}
			else
			{
					$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
					$attraction_decode=json_decode($filestore,TRUE);
					$sort = array();
					foreach($attraction_decode as $k=>$v)
					{
					    $sort['isselected'][$k] = $v['isselected'];
					    $sort['order'][$k] = $v['order'];
					    $sort['tag_star'][$k] = $v['properties']['tag_star'];
					}

					array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
					$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
					fwrite($file,json_encode($attraction_decode));
					fclose($file);
				    $data['filestore']=json_encode($attraction_decode);
			}

		    $output['body'] =$this->load->view('attractions/multicountries/getNewCountryMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));

		}
	}


	function saveMultiOrder()
	{
		$cityfile=$_POST['cityid'];
		$data['uid']=$uniqueid=$_POST['uniqueid'];
		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		$foldername=string_decode($secretkey[$lastKey]);

		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{

			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);


			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$filestore = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);


			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('attractions/multicountries/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}

	}

	function alterMultiAttraction()
	{
		$data['uid']=$uniqueid=$_POST['uniqueid'];
		$cityfile=$_POST['cityid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		$foldername=string_decode($secretkey[$lastKey]);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMultiMainFile($cityfile,$_POST['attractionid'],$uniqueid,$foldername);
		}
		else
		{
			$this->updateMultiFile($cityfile,$_POST['attractionid'],$uniqueid,$foldername);
		}



		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


			if($_POST['ismain']==1)
			{
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				}

				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);


			}
			else
			{
				$finalsort = array();
			    foreach($attraction_decode as $k=>$v)
			    {
				   $finalsort['distance'][$k] = $v['distance'];
			    }
			    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);

			}



			$finalsort = array();
			foreach($attraction_decode as $k=>$v)
			{
				$finalsort['distance'][$k] = $v['distance'];
				$finalsort['tag_star'][$k] = $v['properties']['tag_star'];
			}
			array_multisort($finalsort['distance'], SORT_ASC,$finalsort['tag_star'], SORT_DESC,$attraction_decode);


			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

		}

		$data['cityid']=$cityfile;

		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$output['body']=$this->load->view('attractions/multicountries/getMainMap', $data, true);
		}
		else
		{
			$output['body']=$this->load->view('attractions/multicountries/getMap_All', $data, true);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function updateMultiMainFile($cityfile,$attractionid,$uniqueid,$foldername)
	{
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {

					if($value['properties']['attractionid']===$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;

						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							}

						}
						break;
					}

				}
			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateMultiFile($cityfile,$attractionid,$uniqueid,$foldername)
	{

		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {

					if($value['properties']['attractionid']===$attractionid )
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;
						}
						else
						{
							if(isset($value['properties']['useractivity']) && $value['properties']['useractivity']==1)
							{
								unset($filestore[$key]);
								array_values($filestore);
							}
							else
							{
								$filestore[$key]['isselected']=0;
							    $filestore[$key]['tempremoved']=1;
							}
						}
						break;
					}

				}

			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
	}

	function getAllAttractionsOfMultiCity()
	{
		$cityfile=$_POST['id'];
		$data['uid']=$uniqueid=$_POST['uniqueid'];
		$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);

		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		$foldername=string_decode($secretkey[$lastKey]);
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile))
		{

			$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


			$finalsort = array();
		    foreach($attraction_decode as $k=>$v)
		    {
			   $finalsort['distance'][$k] = $v['distance'];
		    }
		    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);

		    //echo "<pre>";print_r($attraction_decode);die;

			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');

			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);


		}
		else
		{
			$this->attractions_wm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
		}
		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('attractions/multicountries/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}


	function alterCity()
	{
		if($this->input->is_ajax_request())
		{
			$postid=explode('-',string_decode($_POST['cityname']));
			$countryid=$postid[0];
			$cityid=$postid[1];
			$addordelete=$_POST['addordelete'];
			$uniqueid=$_POST['uniqueid'];
			$this->alterSingleCityFile($countryid,$cityid,$addordelete,$uniqueid);

		}
	}

	function alterSingleCityFile($countryid,$cityid,$addordelete,$uniqueid)
	{
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry'))
		{
			$cityfile=$cityid;
			$data['uid']=$uniqueid;
			$file_encode=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countryid]))
			{
					foreach($file_decode[$countryid] as $key=>$list)
					{

						if($addordelete==0)
						{
							if($list['id']==$cityid)
							{
								 unset($file_decode[$countryid][$key]);
								 $file_decode[$countryid]=array_values($file_decode[$countryid]);
								 $file_decode=CalculateDistance($file_decode,$countryid);
								 $cityfile=$file_decode[$countryid][0]['id'];
								 foreach($file_decode[$countryid] as $ids)
							  	 {
							  	 	$idsArray[]=$ids['id'];
							  	 }
							  	 break;
							}

						}
						else if($addordelete==1)
						{
							$cityData=$this->Attraction_fm->makeCityArray($cityid);
							if(!count($cityData))
							{
								echo "2";die;
							}
							$count=count($file_decode[$countryid]);
							$this->Attraction_fm->makeFileForThisCity(md5($cityid),$uniqueid);
							$file_decode[$countryid][]=$cityData;
							$file_decode=CalculateDistance($file_decode,$countryid);
							$length=count($file_decode[$countryid]);
							$cities[$countryid][$length-1]['sortorder']=$length-1;
							$cities[$countryid][$length-1]['nextdistance']='';

							foreach($file_decode[$countryid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}

					}

				$data['select']=$addordelete;
				$data['countryid']=$countryid;
				$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);
				if(count($otherCities))
				{
					$options='<option value="">Select City</option>';
					foreach($otherCities as $list)
					{
						$combination=string_encode($list['country_id']."-".$list['id']);

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}


				$data['options']=$options;

				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);


				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity(md5($cityfile));


				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

				$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'].'-'.$uniqueid;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

				$countrandtype=$basic['country_id'].'-single-'.$uniqueid;
			    $data['secretkey']=string_encode($countrandtype);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);

				$returnflag=$this->attractions_wm->getUserRecommededAttractionsForNewCity($cityfile,$uniqueid);
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

			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}

	}

	function alterMultiCountryCity()
	{
		if($this->input->is_ajax_request())
		{
			$postid=explode('-',string_decode($_POST['cityname']));
			$countryid=$postid[0];
			$cityid=$postid[1];
			$addordelete=$_POST['addordelete'];
			$uniqueid=$_POST['uniqueid'];
			$this->alterMultiCountryCityFile($countryid,$cityid,$addordelete,$uniqueid);

		}
	}

	function alterMultiCountryCityFile($countryid,$cityid,$addordelete,$uniqueid)
	{
		$data['uid']=$uniqueid;
		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		$foldername=string_decode($secretkey[$lastKey]);
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities'))
		{

			$cityfile=$cityid;
			$file_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countryid]))
			{
					foreach($file_decode[$countryid] as $key=>$list)
					{

						if($addordelete==0)
						{
							if($list['id']==$cityid)
							{

								unset($file_decode[$countryid][$key]);
								$file_decode[$countryid]=array_values($file_decode[$countryid]);
								//unset($file_decode[$countryid]);
								$file_decode=CalculateDistance($file_decode,$countryid);
								$cityfile=$file_decode[$countryid][0]['id'];
								 foreach($file_decode[$countryid] as $ids)
							  	 {
							  	 	$idsArray[]=$ids['id'];
							  	 }
							  	 break;
							}
						}
						else if($addordelete==1)
						{
							$cityData=$this->Attraction_fm->makeCityArray($cityid);

							if(!count($cityData))
							{
								echo "2";die;
							}
							$count=count($file_decode[$countryid]);
							$this->Attraction_fm->makeFileForThisCityMulti(md5($cityid),$uniqueid,$foldername);
							$file_decode[$countryid][]=$cityData;
							$file_decode=CalculateDistance($file_decode,$countryid);
							$length=count($file_decode[$countryid]);
							$cities[$countryid][$length-1]['sortorder']=$length-1;
							$cities[$countryid][$length-1]['nextdistance']='';
							foreach($file_decode[$countryid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}

					}

				//echo "<pre>";print_r($file_decode);die;

				$data['select']=$addordelete;
				$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);

				if(count($otherCities))
				{
					$options='<option value="">Select City</option>';
					foreach($otherCities as $list)
					{
						$combination=string_encode($list['country_id']."-".$list['id']);

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}


				$data['options']=$options;


				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);



				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity(md5($cityfile));
				$data['country_name']=$basic["country_name"];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];

				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');
				}

				$countries=$this->getMultiCountriesFromFile($secretkey[$lastKey],$uniqueid);

				$mergecountryids='';
				foreach($countries as $k=>$list)
				{
					if($k!=='encryptkey')
					{
						$mergecountryids .= $list['country_id'].'-';
					}
				}

				$cominineCountryidwithcityid=$mergecountryids.'-'.$basic['id'].'-'.$uniqueid;
			    $data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

				 $data['countryconclusion']=$basic['country_conclusion'];
				 $data['countryimage']=$basic['countryimage'];
				 if($data['countryimage']!='')
				 {
					$data['countryimage']=site_url('userfiles/countries/banner').'/'.$data['countryimage'];
				 }
				 else
				 {
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				 }



				$data['citypostid']=$cityfile = md5($cityfile);

				$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername);
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();


						foreach($attraction_decode as $k=>$v)
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');



						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);
				}

			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('attractions/multicountries/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}
	}

	function saveAttractions($secretkey)
	{

	}

	function seachAttractionsFromGYG()
	{
		$exp=explode(',',$_POST['keyword']);
		$keyword=urlencode($exp[0]);
		$url = 'https://api.getyourguide.com/1/tours?q='.$keyword.'&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp';

		if(isset($_POST['locallatitude']) && $_POST['locallatitude']!='' && isset($_POST['locallongitude']) && $_POST['locallongitude']!='')
		{
			$lat=$_POST['locallatitude'];
			$lng=$_POST['locallongitude'];
			$url = 'https://api.getyourguide.com/1/tours?cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp&coordinates[lat]='.$lat.'&coordinates[long]='.$lng.'';
		}
		else
		{
			$exp=explode(',',$_POST['keyword']);
			$keyword=urlencode($exp[0]);
			$url = 'https://api.getyourguide.com/1/tours?q='.$keyword.'&cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp';
		}

		$content = file_get_contents($url);
		$json=json_decode($content, true);

		$data['json']=array();
		if(isset($json['data']['tours']) && count($json['data']['tours'])>0)
		{
			$data['json'] = $json['data']['tours'];
		}

		$output['body']=$this->load->view('seachAttractionsFromGYG', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function getCitiesInFileName($token)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$token.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		return $filename;
	}

	function addExtraCity()
	{

		if($this->input->is_ajax_request())
		{
			$cityid=$_POST['cityid'];
			$data['uid']=$token=$_POST['uniqueid'];
			$citydetails=$this->Attraction_fm->checkCityExist($cityid,$token);
			if(count($citydetails))
			{

				$this->Attraction_fm->addExtraCity($citydetails,$token);
				$data['searchcity']=$this->getCitiesInFile($token);
				$inputs=$this->getInputs($token);
				$ttldays=0;
				$data['message']=array();
				//echo "<pre>";print_r($data['searchcity']);die;
				if(count($data['searchcity']))
				{
					if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
					{
						foreach($data['searchcity'] as $list)
						{
							//echo "<pre>";print_r($list);die;
							$ttldays=$list['totaldaysneeded'];
						}

						if($inputs['sdays']<$ttldays)
						{
							$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
						}
						else
						{
							$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
					}
					else
					{
						foreach($data['searchcity'] as $list)
						{
							$ttldays=$list['total_days'];
						}

						if($inputs['sdays']<$ttldays)
						{
							$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
						}
						else
						{
							$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
					}


				}
				$data['filenm']=$filenm=$this->getCitiesInFileName($token);
				$data['isaddordelete']=1;
				$lastkey=count($data['searchcity'])-1;
				$data['city']=$data['searchcity'][$lastkey]['city_name'];
				$data['latitude']=$data['searchcity'][$lastkey]['latitude'];
				$data['longitude']=$data['searchcity'][$lastkey]['longitude'];
				$data['cityname']=$data['searchcity'][$lastkey]['city_name'];
				$data['countryname']=$data['searchcity'][$lastkey]['country_name'];
				$data['countryconclusion']=$data['searchcity'][$lastkey]['country_conclusion'];
				$data['countryimage']=$data['searchcity'][$lastkey]['countryimage'];
				$data['citymd5id']=$cityfile = $data['searchcity'][$lastkey]['cityid'];
				//echo "<pre>";print_r($data['searchcity']);die;
				$data['othercities'] = $this->attractions_wm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=1,$token);
				//$data['othercities'] = array();
				$cityfile=$data['searchcity'][$lastkey]['cityid'];
				$data['citypostid']=$cityfile;
				$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
				$data['cityimage']=$basic['cityimage'];
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
				$data['countrybanner']=$basic['countrybanner'];
				$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][$lastkey]['id'].'-'.$token);
				if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$data['searchcity'][$lastkey]['cityid']))
				{

					$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);

					$data['attractioncities']=json_decode($attractioncities,TRUE);

					$attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$data['searchcity'][$lastkey]['cityid']);
					$attractioncities_decode=json_decode($attractions,TRUE);
					$sort = array();
					foreach($attractioncities_decode as $k=>$v)
					{
						$sort['isselected'][$k] = $v['isselected'];
						$sort['order'][$k] = $v['order'];
						$sort['distance'][$k] = $v['distance'];
					}
					array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);
					$data['filestore'] = json_encode($attractioncities_decode);
				}
				else
				{
					$this->Attraction_fm->getAttractionsOfSelectedCity($data['searchcity'][$lastkey]['cityid'],$token,1);
					$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);
					$data['attractioncities']=json_decode($attractioncities,TRUE);
					$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
				}
				$data['cityid']=$cityfile;
				$output['body']=$this->load->view('attractions/singlecountry/addExtraCity', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				//city not exists
			}

		}


	}


	function removeExtraCity()
	{
		if($this->input->is_ajax_request())
		{
			$data['uid']=$token=$_POST['uniqueid'];
			$data['filenm']=$filenm=$this->getCitiesInFileName($token);
			$this->removeThisCityFromFile($_POST['cityid'],$filenm,$token);
			$data['searchcity']=$this->getCitiesInFile($token);
			$inputs=$this->getInputs($token);
				$ttldays=0;
				$data['message']=array();
				//echo "<pre>";print_r($data['searchcity']);die;
				if(count($data['searchcity']))
				{
					if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
					{
						foreach($data['searchcity'] as $list)
						{
							$ttldays=$list['totaldaysneeded'];
						}

						if($inputs['sdays']<$ttldays)
						{
							$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
						}
						else
						{
							$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
					}
					else
					{
						foreach($data['searchcity'] as $list)
						{
							$ttldays=$list['total_days'];
						}

						if($inputs['sdays']<$ttldays)
						{
							$data['message']='Your preferred number of days is less than the time suggested to tour the chosen destination(s). Would you like to extend your stay?';
						}
						else
						{
							$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
					}


				}
			$data['city']=$data['searchcity'][0]['city_name'];
			$data['latitude']=$data['searchcity'][0]['latitude'];
			$data['longitude']=$data['searchcity'][0]['longitude'];
			$data['cityname']=$data['searchcity'][0]['city_name'];
			$data['countryname']=$data['searchcity'][0]['country_name'];
			$data['countryconclusion']=$data['searchcity'][0]['country_conclusion'];
			$data['countryimage']=$data['searchcity'][0]['countryimage'];
			$data['citymd5id']=$cityfile = $data['searchcity'][0]['cityid'];
			//echo "<pre>";print_r($data['searchcity']);die;
			$data['othercities'] = $this->attractions_wm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=0,$token);
			$data['isaddordelete']=0;
			$cityfile=$data['searchcity'][0]['cityid'];
			$data['citypostid']=$cityfile;
			$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
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
			$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][0]['id'].'-'.$token);

			if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm))
			{

				$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);
				$data['attractioncities']=json_decode($attractioncities,TRUE);

				$attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$data['searchcity'][0]['cityid']);
				$attractioncities_decode=json_decode($attractions,TRUE);
				$sort = array();
				foreach($attractioncities_decode as $k=>$v)
				{
					$sort['isselected'][$k] = $v['isselected'];
					$sort['order'][$k] = $v['order'];
					$sort['distance'][$k] = $v['distance'];
				}
				array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);
				$data['filestore'] = json_encode($attractioncities_decode);
			}
			else
			{
				$this->Attraction_fm->getAttractionsOfSelectedCity($data['searchcity'][0]['cityid'],$token,1);
				$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);
				$data['attractioncities']=json_decode($attractioncities,TRUE);
				$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
				//$attractioncities_decode=json_decode($data['filestore'],TRUE);
				//echo "<Pre>";print_r($attractioncities_decode);die;
			}

			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('attractions/singlecountry/addExtraCity', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));

		}
	}


	function removeThisCityFromFile($cityid,$filenm,$token)
	{

		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm,'r');
		$fileencodedata=fgets($file);
		$filedata=json_decode($fileencodedata,TRUE);
		$key = array_search($cityid, array_column($filedata,'cityid'));
		if($key!==false && $filedata[$key]['id']!=$filenm)
		{
			unset($filedata[$key]);
		}
	    $arr = array_values($filedata);
	    fclose($file);

	    $file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm,'w+');
	    fwrite($file,json_encode($arr));
	    fclose($file);

		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityid))
		{
			unlink(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityid);
		}

	}

	public function saveSingleCountryXOrder()
	{
				$this->Attraction_fm->ChangeOrderOfCities($type='singlecountry');
				$data['uid']=$uniqueid=$_POST['uniqueid'];
				$data['countryid']=$countryid=$_POST['coid'];
				if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry'))
				{
					$file_encode=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry');
					$file_decode=json_decode($file_encode,TRUE);
					if(count($file_decode[$countryid]))
					{
							foreach($file_decode[$countryid] as $key=>$list)
							{
								 $file_decode[$countryid]=array_values($file_decode[$countryid]);
								 $cityfile=$file_decode[$countryid][0]['id'];
							}
							foreach($file_decode[$countryid] as $ids)
							{
							 	$idsArray[]=$ids['id'];
							}

							$data['select']=0;
							$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);
							if(count($otherCities))
							{
								$options='<option value="">Select City</option>';
								foreach($otherCities as $list)
								{
									$combination=string_encode($list['country_id']."-".$list['id']);

									$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
								}
							}
							else
							{
								$options=1;
							}


							$data['options']=$options;

						$data['options']=$options;
						$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/singlecountry','w');
						fwrite($file,json_encode($file_decode));
						fclose($file);


						$data['attractioncities'] = $file_decode[$countryid];
						$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity(md5($cityfile));
						if($basic['cityimage']!='')
						{
							$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
						}
						else
						{
							$data['cityimage']=site_url('assets/images/cairo.jpg');

						}
						$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

						$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'].'-'.$uniqueid;
						$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

						$countrandtype=$basic['country_id'].'-single-'.$uniqueid;
					  $data['secretkey']=string_encode($countrandtype);
						$data['latitude']=$basic['citylatitude'];
						$data['longitude']=$basic['citylongitude'];
						$data['select']=0;
						$data['cityid']=$data['citypostid']=$cityfile=md5($cityfile);

						$returnflag=$this->attractions_wm->getUserRecommededAttractionsForNewCity($cityfile,$uniqueid);
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
						$output['body']=$this->load->view('getNewCountryMap', $data, true);
				    $this->output->set_content_type('application/json')->set_output(json_encode($output));
					}
					else
					{
						echo "2";
					}

				}
	}

	function saveSearchCityXOrder()
	{
		 $this->Attraction_fm->ChangeOrderOfCities($type='searchcity');
		 $data['uid']=$token=$_POST['uniqueid'];
		 $data['filenm']=$filenm=$this->getCitiesInFileName($token);
		 $data['searchcity']=$this->getCitiesInFile($token);
		 $inputs=$this->getInputs($token);
		 $other=0;
		 $ttldays=0;
			 $data['message']=array();
			 if(count($data['searchcity']))
			 {
				 if(isset($data['searchcity'][0]['totaldaysneeded']) && $data['searchcity'][0]['totaldaysneeded']!='')
				 {
					 foreach($data['searchcity'] as $list)
					 {
						 $ttldays=$list['totaldaysneeded'];
					 }

					 if($inputs['sdays']<$ttldays)
					 {
						 $other=0;
					 }
					 else
					 {
						 $data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
					 }
				 }
				 else
				 {
					 foreach($data['searchcity'] as $list)
					 {
						 $ttldays=$list['total_days'];
					 }

					 if($inputs['sdays']<$ttldays)
					 {
						 $other=0;
					 }
					 else
					 {
						 $data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
					 }
				 }


			 }
		 $data['city']=$data['searchcity'][0]['city_name'];
		 $data['latitude']=$data['searchcity'][0]['latitude'];
		 $data['longitude']=$data['searchcity'][0]['longitude'];
		 $data['cityname']=$data['searchcity'][0]['city_name'];
		 $data['countryname']=$data['searchcity'][0]['country_name'];
		 $data['countryconclusion']=$data['searchcity'][0]['country_conclusion'];
		 $data['countryimage']=$data['searchcity'][0]['countryimage'];
		 $data['citymd5id']=$cityfile = $data['searchcity'][0]['cityid'];
		 //echo "<pre>";print_r($data['searchcity']);die;
		 if($other==1)
		 {
		 $data['othercities'] = $this->attractions_wm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=0,$token);
		 }
		 else
		 {
		 	$data['othercities']=array();
		 }
		 $data['isaddordelete']=0;
		 $cityfile=$data['searchcity'][0]['cityid'];
		 $data['citypostid']=$cityfile;
		 $data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity($cityfile);
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
		 $data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][0]['id'].'-'.$token);

		 if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$data['searchcity'][0]['cityid']))
		 {

			 $attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);
			 $data['attractioncities']=json_decode($attractioncities,TRUE);
			 //echo $data['searchcity'][0]['id'];die;
			 $attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$data['searchcity'][0]['cityid']);
			 $attractioncities_decode=json_decode($attractions,TRUE);
			 $sort = array();
			 foreach($attractioncities_decode as $k=>$v)
			 {
				 $sort['isselected'][$k] = $v['isselected'];
				 $sort['order'][$k] = $v['order'];
				 $sort['distance'][$k] = $v['distance'];
			 }
			 array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);
			 $data['filestore'] = json_encode($attractioncities_decode);
		 }
		 else
		 {
			 $this->Attraction_fm->getAttractionsOfSelectedCity($data['searchcity'][0]['cityid'],$token,1);
			 $attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$filenm);
			 $data['attractioncities']=json_decode($attractioncities,TRUE);
			 $data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$token.'/'.$cityfile);
			 //$attractioncities_decode=json_decode($data['filestore'],TRUE);
			 //echo "<Pre>";print_r($attractioncities_decode);die;
		 }

		 $data['cityid']=$cityfile;
		 $output['body']=$this->load->view('attractions/singlecountry/addExtraCity', $data, true);
		 $this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function saveMultiCountryXOrder()
	{
		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		$countryid=$_POST['coid'];
		$foldername=string_decode($secretkey[$lastKey]);
		$this->Attraction_fm->ChangeOrderOfCities($type='multicountry',$foldername);
		$data['uid']=$uniqueid=$_POST['uniqueid'];
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities'))
		{

			$file_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countryid]))
			{
				 $cityfile=$file_decode[$countryid][0]['id'];
				 foreach($file_decode[$countryid] as $ids)
			  	 {
			  	 	$idsArray[]=$ids['id'];
			  	 }

				$data['select']=0;
				$otherCities=$data['otherCities']=$this->attractions_wm->getOtherCitiesOfThisCountry($countryid,$idsArray);

				if(count($otherCities))
				{
					$options='<option value="">Select City</option>';
					foreach($otherCities as $list)
					{
						$combination=string_encode($list['country_id']."-".$list['id']);

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}


				$data['options']=$options;


				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/cities','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);



				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->attractions_wm->getLatandLongOfCity(md5($cityfile));
				$data['country_name']=$basic["country_name"];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];

				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');
				}

				$countries=$this->getMultiCountriesFromFile($secretkey[$lastKey],$uniqueid);

				$mergecountryids='';
				foreach($countries as $k=>$list)
				{
					if($k!=='encryptkey')
					{
						$mergecountryids .= $list['country_id'].'-';
					}
				}

				$cominineCountryidwithcityid=$mergecountryids.'-'.$basic['id'].'-'.$uniqueid;
			    $data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);

				 $data['countryconclusion']=$basic['country_conclusion'];
				 $data['countryimage']=$basic['countryimage'];
				 if($data['countryimage']!='')
				 {
					$data['countryimage']=site_url('userfiles/countries/banner').'/'.$data['countryimage'];
				 }
				 else
				 {
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				 }



				$data['citypostid']=$cityfile = md5($cityfile);

				$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile,$uniqueid,$foldername);
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();


						foreach($attraction_decode as $k=>$v)
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$uniqueid.'/'.$foldername.'/'.$cityfile,'w');



						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);
				}

			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('attractions/multicountries/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}

	}

}

/* End of file Attractions.php */
/* Location: ./application/controllers/Attractions.php */
