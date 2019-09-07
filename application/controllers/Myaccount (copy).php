<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount extends User_Controller 
{

	function index()
	{
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'myaccount/index';
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
			$this->form_validation->set_rules('name','Name','trim|required|min_length[2]|max_length[150]');
			$this->form_validation->set_rules('email','Email','trim|valid_email|min_length[5]|max_length[250]|callback_check_email');
			$this->form_validation->set_rules('country_id','Country','trim|required');
			$this->form_validation->set_rules('dob','Date Of Birth','trim|required|min_length[10]|max_length[10]');
			if($this->form_validation->run()==FALSE)
			{

				$this->session->set_flashdata('error', 'Transaction Failed.');
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

	function check_email($email)
	{
		return $this->Account_fm->check_email($email);
	}

	function trips()
	{
		$data['webpage'] = 'trips';
		$data['main'] = 'myaccount/trips';
		$data['trips']=$this->Account_fm->getUserTrips();
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
				
				$slug=$returnslug['slug'];
				$data['itineraryid']=$itineraryid=$returnslug['id'];
				
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry','r');
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
				$cominineCountryidwithcityid=$returnkey.'-'.$firstcityid;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				if(!isset($returnkey) || $returnkey=='')
				{
					redirect('trips');
				}

				$attractioncountries=array();
				if(count($nr[$returnkey]))
				{
					$attractioncountries=$nr[$returnkey];
				}		

				$data['webpage'] = 'attraction_listings';
				$data['main'] = 'myaccount/trip/attraction_listings';
				$data['attractioncities'] = $attractioncountries;
				$data['latitude']=$attractioncountries[0]['citylatitude'];
				$data['longitude']=$attractioncountries[0]['citylongitude'];
				$cityfile = md5($attractioncountries[0]['id']);
				$data['citypostid']=$cityfile;
				$countrandtype=$returnkey.'-single';
				$data['secretkey']=string_encode($countrandtype);
				
				$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);


				$attraction_decode=json_decode($filestore,TRUE);
				$sort = array();
				foreach($attraction_decode as $k=>$v) 
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				    $sort['tag_star'][$k] = $v['properties']['tag_star'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);
			    $data['filestore']=json_encode($attraction_decode);	
				$this->load->vars($data);
				$this->load->view('templates/innermaster');
		}
	}	


	function getAllAttractionsOfCitySaved()
	{
		$data['ountrydetails']=array();
		$cityfile=$_POST['id'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single';
		$data['secretkey']=string_encode($countrandtype);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{

			$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$_POST['iti'].'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


			$finalsort = array();
		    foreach($attraction_decode as $k=>$v) 
		    {
			   $finalsort['distance'][$k] = $v['distance'];
		    }
		    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);
			
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);	

		}
		else
		{
			echo "error";
		}
		$data['cityid']=$cityfile;
		$output['body']=$this->load->view('myaccount/trip/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function getSavedCityAttractions()
	{
		if($this->input->is_ajax_request())
		{
			$cityfile=$_POST['id'];
			$data['itineraryid']=$itineraryid=$_POST['iti'];
			$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$countrandtype=$basic['country_id'].'-single';
			$data['secretkey']=string_encode($countrandtype);
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

	
	function alterMainAttractionSaved()
	{
		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];
		$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single';
		$data['secretkey']=string_encode($countrandtype);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMainFileSaved($cityfile,$_POST['attractionid'],$itineraryid);
		}
		else
		{
			$this->updateFileSaved($cityfile,$_POST['attractionid'],$itineraryid);
		}
		
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];		
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
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

		}
		
		$data['cityid']=$cityfile;
		
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$output['body']=$this->load->view('myaccount/trip/getMainMap', $data, true);
		}
		else
		{
			$output['body']=$this->load->view('myaccount/trip/getMap_All', $data, true);	
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));	
	}


	function updateMainFileSaved($cityfile,$attractionid,$itineraryid)
	{	
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateFileSaved($cityfile,$attractionid,$itineraryid)
	{

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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
			

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
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

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
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
			    $data['secretkey']=string_encode($countrandtype);
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
						$sort = array();
						

						foreach($attraction_decode as $k=>$v) 
						{
						    $sort['isselected'][$k] = $v['isselected'];
						    $sort['order'][$k] = $v['order'];
						    $sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
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
			$data['secretkey']=string_encode($countrandtype);
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


}

?>