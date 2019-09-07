<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attractions extends Front_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	function attractionsFromGYG($longitude,$latitude)
	{
		$gyg2="https://api.getyourguide.com/1/tours?cnt_language=en&currency=USD&access_token=TpY7sMc0qjBYso2ifXBBBpBqaIw32ToT8yH66yyfK0mkIrHp&coordinates[lat]=".$latitude."&coordinates[long]=".$longitude."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $gyg2);
		$result=curl_exec($ch);
		$response = json_decode($result,true);
		curl_close($ch);
		$data['response']=$response;
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'attractions/attractionsFromGYG';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	
	}
	

	function alterMainAttraction()
	{
		$cityfile=$_POST['cityid'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single';
		$data['secretkey']=string_encode($countrandtype);
		$this->updateMainFile($cityfile,$_POST['attractionid']);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{

			
			$filestore = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);	


		   $file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			
		}
		else
		{
			$this->Home_fm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
		}
		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('getMainMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function updateMainFile($cityfile,$attractionid)
	{	
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;		
						}
						else
						{
							$filestore[$key]['isselected']=0;
							$filestore[$key]['tempremoved']=1;
							//$filestore[$key]['order']=99999;			
						}
						
						
					}

				}
			

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single';
		$data['secretkey']=string_encode($countrandtype);
		$this->updateFile($cityfile,$_POST['attractionid']);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{

			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$attraction_decode=json_decode($data['filestore'],TRUE);

			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);	

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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

		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;	
						}
						else
						{
							$filestore[$key]['isselected']=0;
							//$filestore[$key]['order']=99999;		
						}
						
						
					}

				}
			

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
			$cityfile=$_POST['citypostid'];
			if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
			{
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
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
						  'name'=>str_replace(array("\n", "\r"),array("",""),$location[0]),
						  'tag_star'=>0,
						  //'address'=>'',
						  'getyourguide'=>0,
						  'attractionid'=>0,
						  'cityid'=>$_POST['citypostid'],
						);
				$data[$newkey]['isselected']=1;
				$data[$newkey]['order']=99999;
				$data[$newkey]['tempremoved']=0;

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
				
				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);
				
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$countrandtype=$basic['country_id'].'-single';
				$data['secretkey']=string_encode($countrandtype);
				$data=array();
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
			$cityfile=$_POST['citypostid'];
			if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
			{
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
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
								  'name'=>str_replace(array("\n", "\r"),array("",""),$location[0]),
								  'tag_star'=>0,
								  //'address'=>'',
								  'getyourguide'=>0,
								  'attractionid'=>time(),
								  'cityid'=>$_POST['citypostid'],
								);
						$data[$newkey]['isselected']=1;
						$data[$newkey]['order']=99999;
						$data[$newkey]['tempremoved']=0;

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
				
				
				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);
				
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$countrandtype=$basic['country_id'].'-single';
			    $data['secretkey']=string_encode($countrandtype);
				$data=array();
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
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

	function deleteAllFolderFiles()
	{
		if(is_dir(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring')))
		{
			$files = glob(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }	
			}
		}
	}

	function cityAttractions()
	{
		if($this->session->userdata('randomstring')=='')
		{
			$this->session->set_userdata('randomstring',date('Y-m-d').'_'.getRandomString().'_'.rand(10,999999));
		}
		if($this->session->userdata('isnewsearch')==1)
		{
			$this->deleteAllFolderFiles();
			$this->session->set_userdata('isnewsearch',2);
		}	
		$data['webpage'] = 'cityattractions';
		$data['main'] = 'attractions/cityAttractions';
		$data['searchcity'] = $this->Home_fm->getSearchedCity();
		$this->createsearchSearchFile($data['searchcity'][0]['id'],$data['searchcity']);
		$data['city']=$data['searchcity'][0]['city_name'];
		$data['latitude']=$data['searchcity'][0]['latitude'];
		$data['longitude']=$data['searchcity'][0]['longitude'];
		$data['cityname']=$data['searchcity'][0]['city_name'];
		$data['countryname']=$data['searchcity'][0]['country_name'];
		$data['countryconclusion']=$data['searchcity'][0]['country_conclusion'];
		$data['countryimage']=$data['searchcity'][0]['countryimage'];
		$data['citymd5id']=$cityfile = $data['searchcity'][0]['cityid'];
		$data['othercities'] = $this->Home_fm->getSearchedCityOther($data['searchcity']);
		$cityfile=$data['searchcity'][0]['cityid'];
		$data['citypostid']=$cityfile;
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['cityimage']=$basic['cityimage'];
		//print_r($data['cityimage']);die;
		$data['countryid_encrypt']=string_encode($data['searchcity'][0]['id']);
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$data['searchcity'][0]['cityid']))
		{
			
			$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$data['searchcity'][0]['id']);
			$data['attractioncities']=json_decode($attractioncities,TRUE);

			$attractions=file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$data['searchcity'][0]['cityid']);
			$attractioncities_decode=json_decode($attractions,TRUE);
			$sort = array();
			foreach($attractioncities_decode as $k=>$v) 
			{
				$sort['isselected'][$k] = $v['isselected'];
				$sort['order'][$k] = $v['order'];
				$sort['distance'][$k] = $v['distance'];
			}
			array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);
			//array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);


			$data['filestore'] = json_encode($attractioncities_decode);
		}
		else
		{
			$this->Attraction_fm->getAttractionsOfSelectedCity($data['searchcity'][0]['cityid']);
			$attractioncities = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$data['searchcity'][0]['id']);
			$data['attractioncities']=json_decode($attractioncities,TRUE);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			//$attractioncities_decode=json_decode($data['filestore'],TRUE);
			//echo "<Pre>";print_r($attractioncities_decode);die;
		}

		


	
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function createsearchSearchFile($cityid,$cityarray)
	{
		$randomstring=$this->session->userdata('randomstring');
		if (!is_dir(FCPATH.'userfiles/search/'.$randomstring)) 
		{
			mkdir(FCPATH.'userfiles/search/'.$randomstring, 0777,true);         
		}		
		$file=fopen(FCPATH.'userfiles/search/'.$randomstring.'/'.$cityid,'w');
		fwrite($file,json_encode($cityarray));
		fclose($file);
	}

	

	/* Single City */

	function getAllAttractionsOfSingleCity()
	{

		$data['ountrydetails']=array();
		$cityfile=$_POST['id'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{


			    $filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
				$attraction_decode=json_decode($filestore,TRUE);
				
				$finalsort = array();
			    foreach($attraction_decode as $k=>$v) 
			    {
				   $finalsort['distance'][$k] = $v['distance'];
			    }
			    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);
				
				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if($basic['cityimage']!='')
		{
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
		}
		else
		{
			$basic['cityimage']=site_url('assets/images/cairo.jpg');
		
		}

		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		$filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);
		$sort = array();
		foreach($attraction_decode as $k=>$v) 
		{
		    $sort['isselected'][$k] = $v['isselected'];
		    $sort['order'][$k] = $v['order'];
		}
		
		array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
	
		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);

		$data['filestore']=json_encode($attraction_decode);
		$data['cityid']=$cityfile;
		$output['body'] =$this->load->view('attractions/singlecountry/getMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function getUserAttractionsOfSingleCountry()
	{

		$cityfile=$_POST['id'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if($basic['cityimage']!='')
		{
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
		}
		else
		{
			$basic['cityimage']=site_url('assets/images/cairo.jpg');
		
		}
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$filestore= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);
		
		$attraction_decode[0]['distance']=0;
		for($i=1;$i<count($attraction_decode);$i++)
		{
			$distance=$this->Attraction_fm->Attraction_fm->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
			$attraction_decode[$i]['distance']=$distance;
		}

		$sort = array();
		foreach($attraction_decode as $k=>$v) 
		{
		    $sort['isselected'][$k] = $v['isselected'];
		    $sort['order'][$k] = $v['order'];
		}
			
		array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);


		$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMainFileSingle($cityfile,$_POST['attractionid']);
		}
		else
		{
			$this->updateFileSingle($cityfile,$_POST['attractionid']);
		}
		
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];		
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
		
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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


	function updateMainFileSingle($cityfile,$attractionid)
	{	
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;		
						}
						else
						{
							$filestore[$key]['isselected']=0;
							$filestore[$key]['tempremoved']=1;		
								
						}
					}

				}
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateFileSingle($cityfile,$attractionid)
	{

		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;	
						}
						else
						{
							$filestore[$key]['isselected']=0;
							$filestore[$key]['order']=99999;			
						}
					}

				}
			

			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);
			
			
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;	
			}	

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];		
			$filestore = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
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

			$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);
			
			
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;	
			}	

			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];		
			$filestore = file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
		
			$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('attractions/singlecountry/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


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
			if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
			{
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
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
						  'name'=>str_replace(array("\n", "\r"),array("",""),$location[0]),
						  'tag_star'=>0,
						  //'address'=>'',
						  'getyourguide'=>0,
						  'attractionid'=>0,
						  'cityid'=>$_POST['citypostid'],
						);
				$data[$newkey]['isselected']=1;
				$data[$newkey]['order']=99999;
				$data[$newkey]['tempremoved']=0;

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
				
				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);
				
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
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
			if(file_exists(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile))
			{
				$data=array();
				$filedata= file_get_contents(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile);
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
								  'name'=>str_replace(array("\n", "\r"),array("",""),$location[0]),
								  'tag_star'=>0,
								  //'address'=>'',
								  'getyourguide'=>0,
								  'attractionid'=>time(),
								  'cityid'=>$_POST['citypostid'],
								);
						$data[$newkey]['isselected']=1;
						$data[$newkey]['order']=99999;
						$data[$newkey]['tempremoved']=0;

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
				
				
				$file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);
				
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$basic['cityimage']=site_url('assets/images/cairo.jpg');
				
				}
				$data=array();
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

	function multicountries($encryptkey)
	{
		if($this->session->userdata('randomstring')=='')
		{
			redirect(site_url());
		}
		$randomstring=$this->session->userdata('randomstring');
		$data['flagpage']=1;
		if($randomstring=='')
		{
			redirect(site_url());
		}
		
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'attractions/multicountries/attraction_listings';
		$countries=$data['countries']=$this->Attraction_fm->setMultiCountries($encryptkey);
		
		$mergecountryids='';
		foreach($countries as $k=>$list)
		{
			if($k!=='encryptkey')
			{
				$mergecountryids .= $list['country_id'].'-';
			}
		}

		
		$cityid=$countries[0]['id'];
		$countryid=$countries[0]['country_id'];
		$slug=$countries[0]['slug'];
		$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($slug);
		$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities');
		$filedata_decode=json_decode($filedata,TRUE);
		$idsArray=array();
		foreach($filedata_decode[$countryid] as $key=>$list)
		{
			$idsArray[]=$list['id'];
		}

		$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);
		$data['latitude']=$countries[0]['latitude'];
		$data['longitude']=$countries[0]['longitude'];
		$data['countryimage']=$countries[0]['countryimage'];
		$cityfile = md5($countries[0]['id']);
		$data['citypostid']=$cityfile;
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		
		$data['cityimage']=$basic['cityimage'];
		$data['countryconclusion']=$data['countrynm']['country_conclusion'];
		
		$cominineCountryidwithcityid=$mergecountryids.''.$countries[0]['id'];
		$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
		
		$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$randomstring);
		
		$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile);
		if($returnflag==1)
		{
		   		$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);

		}
		else
		{
				$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
				$attraction_decode=json_decode($filestore,TRUE);
				$sort = array();
				foreach($attraction_decode as $k=>$v) 
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				    $sort['tag_star'][$k] = $v['properties']['tag_star'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);
			    $data['filestore']=json_encode($attraction_decode);			
		}
		
		$this->load->vars($data);
		$this->load->view('templates/innermaster');

	}


	function getCitiesFromFile($countryid,$encryptkey,$randomstring)
	{
		$cities_encode = file_get_contents(FCPATH.'userfiles/multicountries/'.$randomstring.'/cities');
		$cities_decode = json_decode($cities_encode,TRUE);
		return $cities_decode[$countryid];
	
	}

	function multicity_attractions_ajax()
	{
		if($this->input->is_ajax_request())
		{
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];

			$cityfile=$_POST['id'];
			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			if($basic['cityimage']!='')
			{
				$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			}
			else
			{
				$basic['cityimage']=site_url('assets/images/cairo.jpg');
			
			}
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			
			$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'];
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
			
			$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile);
			if($returnflag==1)
			{
			   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
			}
			else
			{
					$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
					$attraction_decode=json_decode($filestore,TRUE);
					
					$attraction_decode[0]['distance']=0;
					for($i=1;$i<count($attraction_decode);$i++)
					{
						$distance=$this->Attraction_fm->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
						$attraction_decode[$i]['distance']=$distance;
					}

					$sort = array();
					foreach($attraction_decode as $k=>$v) 
					{
					    $sort['isselected'][$k] = $v['isselected'];
					    $sort['order'][$k] = $v['order'];
					}
				
					

					array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

					$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
			redirect(site_url());
		}

	}

	function getDataForNewCountry()
	{
		if($this->input->is_ajax_request())
		{
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];

			$md5country_id=$_POST['countryid'];
			$randomstring=$this->session->userdata('randomstring');
			$encryptkey=$_POST['key'];
			$countryArray=$data['countries']=$this->Attraction_fm->setMultiCountriesMD5($encryptkey);
			$countries=array();

			foreach($countryArray as $newKey=>$newlist)
			{
				if(md5($newlist['country_id'])==$_POST['countryid'])
				{
					$countries = $countryArray[$newKey];
					break;
				}
			}


			$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($countries['slug']);
			$data['country_name']=$data['countrynm']['country_name'];
			$cityid=$countries['id'];
			$data['cityid']=md5($cityid);
			$basic=$this->Home_fm->getLatandLongOfCity(md5($cityid));
			
			if($basic['cityimage']!='')
			{
				$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			}
			else
			{
				$data['cityimage']=site_url('assets/images/cairo.jpg');
			}

			 $data['countryconclusion']=$data['countrynm']['country_conclusion'];
			 $data['countryimage']=$data['countrynm']['countryimage'];
			 if($data['countryimage']!='')
			 {
				$data['countryimage']=site_url('userfiles/countries/banner').'/'.$data['countryimage'];
			 }
			 else
			 {
				$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
			 }	


			 $countryid=$countries['country_id'];


			$data['select']=0;

			$filedata= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities');
			$filedata_decode=json_decode($filedata,TRUE);
			$idsArray=array();
			foreach($filedata_decode[$countryid] as $key=>$list)
			{
				$idsArray[]=$list['id'];
			}


			$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);	
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
			
			$cominineCountryidwithcityid=$countries['country_id'].'-'.$countries['id'];
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);	

			$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$randomstring);
			$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile);
			if($returnflag==1)
			{
			   		$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);

			}
			else
			{
					$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
					$attraction_decode=json_decode($filestore,TRUE);
					$sort = array();
					foreach($attraction_decode as $k=>$v) 
					{
					    $sort['isselected'][$k] = $v['isselected'];
					    $sort['order'][$k] = $v['order'];
					    $sort['tag_star'][$k] = $v['properties']['tag_star'];
					}

					array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
					$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];
			
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);
			
			
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;	
			}	

			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];		
			$filestore = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
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

			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		$cityfile=$_POST['cityid'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMultiMainFile($cityfile,$_POST['attractionid']);
		}
		else
		{
			$this->updateMultiFile($cityfile,$_POST['attractionid']);
		}

		$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
		$lastKey=count($secretkey)-1;
		$data['secretkey']=$secretkey[$lastKey];
		
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];		
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
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


			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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

	function updateMultiMainFile($cityfile,$attractionid)
	{	
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;		
							
						}
						else
						{
							$filestore[$key]['isselected']=0;
							$filestore[$key]['tempremoved']=1;		
								
						}
					}

				}
			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateMultiFile($cityfile,$attractionid)
	{

		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$filestore=json_decode($data['filestore'],TRUE);
			foreach ($filestore as $key => $value) {
					
					if($value['properties']['attractionid']==$attractionid ||  $value['properties']['attractionid']=='tag_'.$attractionid)
					{
						if($_POST['flag']==1)
						{
							$filestore[$key]['isselected']=1;
							$filestore[$key]['tempremoved']=0;	
						}
						else
						{
							$filestore[$key]['isselected']=0;
							$filestore[$key]['tempremoved']=0;			
						}
					}

				}
			
			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
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
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile))
		{

			$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


			$finalsort = array();
		    foreach($attraction_decode as $k=>$v) 
		    {
			   $finalsort['distance'][$k] = $v['distance'];
		    }
		    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);
			
			$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');
				
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);	


		}
		else
		{
			$this->Home_fm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
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
			$countrtid=$postid[0];
			$cityid=$postid[1];
			$addordelete=$_POST['addordelete'];
			$this->alterSingleCityFile($countrtid,$cityid,$addordelete);
			
		}	
	}

	function alterSingleCityFile($countrtid,$cityid,$addordelete)
	{
		if(file_exists(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/singlecountry'))
		{
			$cityfile=$cityid;
			$file_encode=file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/singlecountry');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countrtid]))
			{
					foreach($file_decode[$countrtid] as $key=>$list)
					{

						if($addordelete==0)
						{
							if($list['id']==$cityid)
							{
								 unset($file_decode[$countrtid][$key]);
								 $file_decode[$countrtid]=array_values($file_decode[$countrtid]);
								 $cityfile=$file_decode[$countrtid][0]['id'];
								 foreach($file_decode[$countrtid] as $ids)
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
							$count=count($file_decode[$countrtid]);
							$this->Attraction_fm->makeFileForThisCity(md5($cityid));
							$file_decode[$countrtid][]=$cityData;
							foreach($file_decode[$countrtid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}
						
					}

				$data['select']=$addordelete;
				$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countrtid,$idsArray);	

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


				$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/singlecountry','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);	
				

				$data['attractioncities'] = $file_decode[$countrtid];
					
				$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$basic['cityimage']=site_url('assets/images/cairo.jpg');
				
				}
				$countrandtype=$basic['country_id'].'-single';
			    $data['secretkey']=string_encode($countrandtype);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);
				
				$returnflag=$this->Home_fm->getUserRecommededAttractionsForNewCity($cityfile);
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();
						

						foreach($attraction_decode as $k=>$v) 
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/files/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');


						
						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);			
				}

			$data['cityid']=md5($cityid);
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
			$countrtid=$postid[0];
			$cityid=$postid[1];
			$addordelete=$_POST['addordelete'];
			$this->alterMultiCountryCityFile($countrtid,$cityid,$addordelete);
			
		}	
	}

	function alterMultiCountryCityFile($countrtid,$cityid,$addordelete)
	{
		if(file_exists(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities'))
		{
			$secretkey=explode('/',$_SERVER['HTTP_REFERER']);
			$lastKey=count($secretkey)-1;
			$data['secretkey']=$secretkey[$lastKey];
			$cityfile=$cityid;
			$file_encode=file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countrtid]))
			{
					foreach($file_decode[$countrtid] as $key=>$list)
					{

						if($addordelete==0)
						{
							if($list['id']==$cityid)
							{ 
								
								unset($file_decode[$countrtid][$key]);
								$file_decode[$countrtid]=array_values($file_decode[$countrtid]);
								//unset($file_decode[$countrtid]);
								$cityfile=$file_decode[$countrtid][0]['id'];
								 foreach($file_decode[$countrtid] as $ids)
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
							$count=count($file_decode[$countrtid]);
							$this->Attraction_fm->makeFileForThisCityMulti(md5($cityid));
							$file_decode[$countrtid][]=$cityData;
							foreach($file_decode[$countrtid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}
						
					}

				//echo "<pre>";print_r($file_decode);die;	

				$data['select']=$addordelete;
				$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countrtid,$idsArray);	

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


				$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/cities','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);	
				

				$data['attractioncities'] = $file_decode[$countrtid];
					
				$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
				$data['country_name']=$basic["country_name"];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');
				}

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
				
				$returnflag=$this->Attraction_fm->getUsersMultiCountryRecommendations($cityfile);
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();
						

						foreach($attraction_decode as $k=>$v) 
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/multicountries/'.$this->session->userdata('randomstring').'/'.$cityfile,'w');


						
						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);			
				}

			$data['cityid']=md5($cityid);
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

}

/* End of file Attractions.php */
/* Location: ./application/controllers/Attractions.php */