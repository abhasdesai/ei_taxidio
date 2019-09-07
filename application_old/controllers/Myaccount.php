<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount extends User_Controller 
{

	function index()
	{

		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'myaccount/index';
		$data['trips']=$this->Account_fm->countUserTripsDashboard();
		$data['recenttrips']=$this->Account_fm->getRecentTrips();
		$data['calendartrip']=$this->Account_fm->getCalendarTrips();
		$this->load->vars($data);
		$this->load->view('templates/dashboard/homemaster');
	}

	function myprofile()
	{
		
		$data['webpage'] = 'profile';
		$data['main'] = 'myaccount/profile';
		$data['user']=$this->Account_fm->getUserDetails();
		$data['countries']=$this->Account_fm->getCountries();
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(site_url());
	}

	function editUser()
	{
		
		if($this->input->post('btnsubmit'))
		{
			$this->load->library('form_validation');
			if($this->session->userdata('issocial')!=1)
			{
				$this->form_validation->set_rules('name','Name','trim|required|min_length[2]|max_length[200]');
				$this->form_validation->set_rules('email','Email','trim|valid_email|min_length[5]|max_length[450]|callback_check_email');
			}
			$this->form_validation->set_rules('country_id','Country','trim|required');
			$this->form_validation->set_rules('dob','Date Of Birth','trim|required|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('gender','Gender','trim|required');
			$this->form_validation->set_rules('phone','Phone','trim|max_length[15]');

			if($this->form_validation->run()==FALSE)
			{
				//echo "sd";die;
				$this->session->set_flashdata('error', 'Your Profile has not been Updated.');
				$this->myprofile();
			}
			else
			{
				$this->Account_fm->editUser();
				$this->session->set_flashdata('success', 'Your Profile has been Updated.');
				redirect('myprofile');
			}
		}
		else
		{
			redirect('myprofile');
		}
	}

	function trips()
	{
		$data['webpage'] = 'trips';
		$data['main'] = 'myaccount/trips';
		$this->load->library('pagination');
		$start_row=$this->uri->segment(2);
        $config["base_url"] = site_url('trips');
        //$config["total_rows"] = $this->Hotel_fm->countHotels($recommendation);
        $config["total_rows"] = $this->Account_fm->countTrips();
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
        $config['per_page'] = 12;
        $this->pagination->initialize($config);
        $data['trips']=$this->Account_fm->getUserTrips($config["per_page"],$start_row);
        $data['pagination']=$this->pagination->create_links();
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	function trip($id)
	{
		$returnslug=$this->Account_fm->resetTrip($id);
		if(count($returnslug)<1)
		{
			redirect(site_url());
		}
		else
		{
				
				
		}
	}	


	

	function getSavedCityAttractions()
	{
		if($this->input->is_ajax_request())
		{
			$cityfile=$_POST['id'];
			$data['itineraryid']=$itineraryid=$_POST['iti'];
			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$countrandtype=$basic['country_id'].'-single';
			$data['secretkey']=string_encode($itineraryid);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'];
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
			$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);

			$attraction_decode=json_decode($filestore,TRUE);
				
			
			$attraction_decode[0]['distance']=0;
			for($i=1;$i<count($attraction_decode);$i++)
			{
				$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
				$attraction_decode[$i]['distance']=$distance;
			}

			
				

			foreach($attraction_decode as $k=>$v) 
			{
				if(isset($v['isselected']) && $v['isselected']>=0)
				{
					$attraction_decode[$k]['isselected'] = $v['isselected'];	
				}
				else
				{
					$attraction_decode[$k]['isselected'] = 1;		
				}

				if(isset($v['order']) && $v['order']>=0)
				{
					$attraction_decode[$k]['order'] = $v['order'];	
				}
				else
				{
					$attraction_decode[$k]['order'] = $k;		
				}

				if(isset($v['tempremoved']) && $v['tempremoved']>=0)
				{
					$attraction_decode[$k]['tempremoved'] = $v['tempremoved'];	
				}
				else
				{
					$attraction_decode[$k]['tempremoved'] = 0;		
				}

				
			}

			//echo "<pre>";print_r($attraction_decode);die;
				
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
				$sort['isselected'][$k] = $v['isselected'];	
				$sort['order'][$k] = $k;
			}

			//echo "<pre>";print_r($attraction_decode);die;
		
			

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);

			$data['filestore']=json_encode($attraction_decode);

			$data['cityid']=$cityfile;
			$output['body'] =$this->load->view('myaccount/trip/getMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}	
		else
		{
			//redirect(site_url());
		}
		
	}

	function haversineGreatCircleDistance(
  		$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		 $rad = M_PI / 180;
        return acos(sin($latitudeTo*$rad) * sin($latitudeFrom*$rad) + cos($latitudeTo*$rad) * cos($latitudeFrom*$rad) * cos($longitudeTo*$rad - $longitudeFrom*$rad)) * 6371;
	
	}

	
	


	

	function alterSavedCity()
	{
		if($this->input->is_ajax_request())
		{
			$postid=explode('-',string_decode($_POST['cityname']));
			$countrtid=$postid[0];
			$cityid=$postid[1];
			$itineraryid=$postid[2];
			$addordelete=$_POST['addordelete'];
			$this->alterSingleCityFileSaved($countrtid,$cityid,$addordelete,$itineraryid);
			
		}	
	}

	function alterSingleCityFileSaved($countrtid,$cityid,$addordelete,$itineraryid)
	{
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry'))
		{
			$cityfile=$cityid;
			$data['itineraryid']=$itineraryid;
			$file_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry');
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
							$this->Account_fm->makeFileForThisCity(md5($cityid),$itineraryid);
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
						$combination=string_encode($list['country_id']."-".$list['id']."-".$itineraryid);

						$options .='<option value='.$combination.'>'.str_replace(array("'",'"'),array("\u0027","\u0022"),$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}
				
				
				$data['options']=$options;


				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);	
				

				$data['attractioncities'] = $file_decode[$countrtid];
					
				$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));
				$countrandtype=$basic['country_id'].'-single';
			    $data['secretkey']=string_encode($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);
				
				$returnflag=$this->Account_fm->getUserRecommededAttractionsForNewCity($cityfile,$itineraryid);
				if($returnflag==1)
				{
				   $filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);

				}
				else
				{
						$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
						$attraction_decode=json_decode($filestore,TRUE);
						
						if(!isset($attraction_decode[0]['isselected']))
						{
							$attraction_decode=$this->changeArray($attraction_decode);
						}
						else
						{

							$sort = array();
							foreach($attraction_decode as $k=>$v) 
							{
							    $sort['isselected'][$k] = $v['isselected'];
							    $sort['order'][$k] = $v['order'];
							    $sort['tag_star'][$k] = $v['properties']['tag_star'];
							}
							array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
							
						}
						$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');


						
						fwrite($file,json_encode($attraction_decode));
						fclose($file);
					    $data['filestore']=json_encode($attraction_decode);			
				}

			$data['cityid']=md5($cityid);
			$output['body']=$this->load->view('myaccount/trip/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}
		
	}

	function saveOrderSaved()
	{
		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);
			
			
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;	
			}	

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$countrandtype=$basic['country_id'].'-single';
			//$data['secretkey']=string_encode($countrandtype);
			$data['secretkey']=string_encode($itineraryid);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];		
			$filestore = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
		
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			$output['body']=$this->load->view('myaccount/trip/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}
	}


	// MUlti countries Trips
	function getCitiesFromFile($countryid,$encryptkey,$iti)
	{
		$cities_encode = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
		$cities_decode = json_decode($cities_encode,TRUE);
		return $cities_decode[$countryid];
	
	}

	function multicountrytrips($id)
	{
		$itid=string_decode($id);
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$this->Account_fm->deleteItinerary($itid);
		}

		$returnslug=$this->Account_fm->resetMultiTrip($id);
		$encryptkey=$returnslug['combination_key'];
		
		$data['itineraryid']=$iti=$returnslug['id'];
		if(count($returnslug)<1)
		{

			redirect('trips');
		}
		
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'myaccount/trip/multicountries/attraction_listings';
		
		$countries=$data['countries']=$this->Account_fm->setMultiCountries($encryptkey,$iti);
		//echo "<pre>";print_r($countries);die;
		$mergecountryids='';
		foreach($countries as $k=>$list)
		{
			if($k!=='encryptkey')
			{
				$mergecountryids .= $list['country_id'].'-';
			}
		}

		$cityid=$countries[0]['id'];
		$data['countrid']=$countryid=$countries[0]['country_id'];
		$slug=$countries[0]['slug'];
		$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($slug);
		$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
		$filedata_decode=json_decode($filedata,TRUE);
		//echo "<pre>".$iti;print_r($filedata_decode);die;
		$idsArray=array();
		foreach($filedata_decode[$countryid] as $key=>$list)
		{
			$idsArray[]=$list['id'];
		}
		
		$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);

		$data['latitude']=$countries[0]['latitude'];
		$data['longitude']=$countries[0]['longitude'];
		$data['countryimage']=$countries[0]['countryimage'];
		$data['basiccityname']=$countries[0]['city_name'];
		$data['countryconclusion']=$data['countrynm']['country_conclusion'];
		$cityfile = md5($countries[0]['id']);
		$data['citypostid']=$cityfile;
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['countrybanner']=$basic['countrybanner'];
		$data['cityimage']=$basic['cityimage'];
		$data['countryconclusion']=$data['countrynm']['country_conclusion'];
		

		$cominineCountryidwithcityid=$mergecountryids.''.$countries[0]['id'].'-'.$iti;
		$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
		
		$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$iti);
		
		$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);

		if(!isset($attraction_decode[0]['isselected']))
		{
			$attraction_decode=$this->changeArray($attraction_decode);
		}
		else
		{
			$sort = array();
			foreach($attraction_decode as $k=>$v) 
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			    $sort['tag_star'][$k] = $v['properties']['tag_star'];
			}
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
		}

		//echo "<pre>";print_r($attraction_decode);die;
		
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
	    $data['filestore']=json_encode($attraction_decode);	
	    $this->load->vars($data);
		$this->load->view('templates/innermaster');

	}

	function changeArray($attraction_decode)
	{
			$attraction_decode[0]['distance']=0;
			for($i=1;$i<count($attraction_decode);$i++)
			{
				$distance=$this->haversineGreatCircleDistance($attraction_decode[0]['geometry']['coordinates'][1],$attraction_decode[0]['geometry']['coordinates'][0],$attraction_decode[$i]['geometry']['coordinates'][1],$attraction_decode[$i]['geometry']['coordinates'][0]);	
				$attraction_decode[$i]['distance']=$distance;
			}

			$finalsort = array();
			foreach($attraction_decode as $k=>$v) 
			{
				$finalsort['distance'][$k] = $v['distance'];
			}
			array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);




			if(count($attraction_decode))
			{
				foreach($attraction_decode as $key=>$attlist)
				{

					$attraction_decode[$key]['isselected']=1;
					$attraction_decode[$key]['order']=$key;
					$attraction_decode[$key]['tempremoved']=0;
				}
			}

			return $attraction_decode;

	}

	/* Search city trips  */

	function getCitiesInFileName($iti)
	{
		$randomstring=$iti;
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		return $filename;
	}


	

	function getCitiesInFile($itid)
	{

		$randomstring=$itid;
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$randomstring.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$randomstring.'/'.$filename,'r');
		$cityarrayinfile=fgets($file);
		$cityarray=json_decode($cityarrayinfile,TRUE);
		fclose($file);

		return $cityarray;
	}
	

	function img_save_to_file_profile() {
		$this->Account_fm->img_save_to_file_profile();
	}

	function img_crop_to_file_profile() {
		$this->Account_fm->img_crop_to_file_profile();
	}

	function uploadImage()
	{
		$this->Account_fm->uploadImage();
		$data['images'] = $this->Account_fm->getPic();
		$output['body'] = $this->load->view('myaccount/loadImage', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function removeProfileImage()
	{
		$val = $this->Account_fm->removeImage();
		$data['images'] = $this->Account_fm->getPic();
		$output['body'] = $this->load->view('myaccount/loadImage', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function removeProfileImageFromStorage()
	{
		$this->Account_fm->removeProfileImageFromStorage();
	}

}

?>