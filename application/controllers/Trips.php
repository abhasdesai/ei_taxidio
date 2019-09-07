<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trips extends User_Controller
{


	function userSingleCountryTrip($secretid)
	{
		$itid=string_decode($secretid);
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$this->Account_fm->deleteItinerary($itid);
		}

		$returnslug=$this->Account_fm->resetTrip($secretid);
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
				$cominineCountryidwithcityid=string_decode($secretid).'-'.$firstcityid;
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

				$data['citypostid']=$cityfile = md5($attractioncountries[0]['id']);

				$countrandtype=$returnkey.'-single';
				$data['secretkey']=string_encode($itineraryid);

				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data['countryid']=$basic['country_id'];
				$data['cityimage']=$basic['cityimage'];
				$data['basiccityname']=$basic['city_name'];
				$data['countryconclusion']=$basic['country_conclusion'];
				$data['countrybanner']=$basic['countrybanner'];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];

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
			    
			    
			    /*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				//echo "<pre>";print_r($data);die;
			    
				$this->load->vars($data);
				$this->load->view('templates/innermaster');

		}

	}

	function getAllAttractionsOfCitySaved()
	{
		$data['ountrydetails']=array();
		$cityfile=$_POST['id'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$countrandtype=$basic['country_id'].'-single';
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];
		$data['secretkey']=string_encode($itineraryid);
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
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
		$output['body']=$this->load->view('myaccount/trip/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function alterMainAttractionSaved()
	{
		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];
		$cityfile=$_POST['cityid'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['secretkey']=string_encode($itineraryid);
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

			if(isset($_POST['ismain']) && $_POST['ismain']==0)
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
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
			}

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

		}
		else
		{
			$this->Home_fm->writeAttractionsInFilemd5($cityfile);
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
		}
		$data['cityid']=$cityfile;
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
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


	function getUserSavedSingleCountryAttractions()
	{
		if($this->input->is_ajax_request())
		{
			$cityfile=$_POST['id'];
			$data['itineraryid']=$itineraryid=$_POST['iti'];
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
			$data['secretkey']=string_encode($itineraryid);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];

			$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($data['basiccityname']).'/'.$data['longitude'].'/'.$data['latitude'];

			$cominineCountryidwithcityid=$itineraryid.'-'.$basic['id'];
			//$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
			if(file_exists(FCPATH.'/userfiles/savedfiles/'.$itineraryid))
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
				//echo "<pre>";print_r($attraction_decode);die;
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($attraction_decode));
				fclose($file);
			    $data['filestore']=json_encode($attraction_decode);
			    $data['cityid']=$cityfile;
			    
			    /*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				//echo "<pre>";print_r($data);die;
				
				$output['body'] =$this->load->view('myaccount/trip/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}

		}
	}



	function saveSingleListing()
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

			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
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

			//echo "<pre>";
			//print_r($_POST);
			//print_r($attraction_decode);die;

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}

	}

	function alterSavedSingleCountryCity()
	{
		if($this->input->is_ajax_request())
		{
			$postid=explode('-',string_decode($_POST['cityname']));
			$countryid=$postid[0];
			$cityid=$postid[1];
			$addordelete=$_POST['addordelete'];
			$this->alterSingleCityFile($countryid,$cityid,$addordelete);

		}
	}

	function alterSingleCityFile($countryid,$cityid,$addordelete)
	{
		$data['itineraryid']=$itineraryid=$_POST['iti'];

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry'))
		{
			$cityfile=$cityid;
			$file_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.'singlecountry');
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
							$this->Trip_fm->makeFileForThisCity(md5($cityid),$itineraryid);
							$file_decode[$countryid][]=$cityData;
							$file_decode=CalculateDistance($file_decode,$countryid);
							foreach($file_decode[$countryid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}

					}

				$data['select']=$addordelete;
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.'singlecountry','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);


				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));


				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

				//$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'].'-'.$itineraryid;
				//$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				$cominineCountryidwithcityid=$itineraryid.'-'.$basic['id'];
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				$data['secretkey']=string_encode($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);

				$returnflag=$this->Trip_fm->getUserRecommededAttractionsForNewCity($cityfile,$itineraryid);
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

			$data['cityid']=$cityfile;
			
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			

			
			$output['body']=$this->load->view('myaccount/trip/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}






	}


	function addUserActivityToSingleCountrySaved()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->addUserActivityToSingleCountrySaved_All();
			}
			else
			{
				$this->addUserActivityToSingleCountrySaved_Main();
			}
		}
	}

	function addUserActivityToSingleCountrySaved_All()
	{
			$itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];
			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);


				$data['basic']=$basic;
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['countryconclusion']=$basic['country_conclusion'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}
				$data['itineraryid']=$itineraryid;
				$data['secretkey']=string_encode($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				
				$output['body'] =$this->load->view('myaccount/trip/getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function addUserActivityToSingleCountrySaved_Main()
	{
			$itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];

			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{

				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$data['itineraryid']=$itineraryid;
				$data['secretkey']=string_encode($itineraryid);
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['countryconclusion']=$basic['country_conclusion'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				$output['body'] =$this->load->view('myaccount/trip/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

/* Following Code is for saved multicountries*/

	function getCitiesFromFile($countryid,$encryptkey,$iti)
	{
		$encryptkey=clearHashLink($encryptkey);
		$cities_encode = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
		$cities_decode = json_decode($cities_encode,TRUE);
		return $cities_decode[$countryid];

	}

	function getMultiCountriesFromFile($encryptkey,$iti)
	{
		$combinations_encode = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations');
		$combinations_decode = json_decode($combinations_encode,TRUE);
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
				$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/combinations','w');
				fwrite($file,json_encode($combinations_decode));
				fclose($file);
				$encryptionkeyArray=$combinations_decode[$key];

			}
		}

		return $encryptionkeyArray;
	}

	function getDataForNewCountryMultiSaved()
	{
		if($this->input->is_ajax_request())
		{
			$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
			$data['secretkey']=string_encode($itineraryid);
			$md5country_id=$_POST['countryid'];


			$encryptkeyRet=$this->Trip_fm->getCombinationKey($iti);
			if(count($encryptkeyRet)<1)
			{
				echo "2";die;
			}
			$encryptkey=$encryptkeyRet['combination_key'];
			$countryArray=$data['countries']=$this->Trip_fm->setMultiCountriesMD5($encryptkey,$iti);

			$countries=array();
			foreach($countryArray as $newKey=>$newlist)
			{
				if(md5($newlist['country_id'])==$_POST['countryid'])
				{
					$countries = $countryArray[$newKey];
					break;
				}
			}
			$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
			$cities=json_decode($filedata,TRUE);
			$maincities=array();
			foreach ($cities as $key => $value) {
				if(md5($key)==$md5country_id)
				{
					$maincities=$cities[$key];
				}
			}
			$idsArray=array();
			//echo "<pre>".$countries['country_id'];print_r($maincities);die;
			$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($countries['slug']);
			$data['country_name']=$data['countrynm']['country_name'];
			$cityid=$maincities[0]['id'];
			$data['cityid']=md5($cityid);
			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityid));
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

			foreach($cities[$countryid] as $key=>$list)
			{
				$idsArray[]=$list['id'];
			}


			$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);
			if(count($otherCities))
			{
				$options='<option value="">Select City</option>';
				foreach($otherCities as $list)
				{
					$combination=string_encode($list['country_id']."-".$list['id']."-".$iti);

					$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';

				}
			}
			else
			{
				$options=1;
			}

			$data['options']=$options;

			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$cityfile = md5($cityid);
			$data['citypostid']=$cityfile;

			$dbSecretKey=$this->Trip_fm->getCombinationKeyLogin($iti);

			$countries=$this->getMultiCountriesFromFile($dbSecretKey,$iti);

			$mergecountryids='';
			foreach($countries as $k=>$list)
			{
				if($k!=='encryptkey')
				{
					$mergecountryids .= $list['country_id'].'-';
				}
			}


			$cominineCountryidwithcityid=substr($mergecountryids,0,-1).'-'.$basic['id'].'-'.$iti;
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


			$data['attractioncities']=$cities=$this->getCitiesFromFile($countryid,$encryptkey,$iti);
			$returnflag=$this->Trip_fm->getUserRecommededAttractionsForNewCity($cityfile,$itineraryid);
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


			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
		    $output['body'] =$this->load->view('myaccount/trip/multicountries/getNewCountryMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
			//echo "<pre>";print_r($data);die;
		}
	}

	function getAllAttractionsOfMultiCitySaved()
	{
		$cityfile=$_POST['id'];
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		$data['secretkey']=string_encode($itineraryid);
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{

			$filestore=$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


			$finalsort = array();
		    foreach($attraction_decode as $k=>$v)
		    {
			   $finalsort['distance'][$k] = $v['distance'];
		    }
		    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');

			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);


		}

		$data['cityid']=$cityfile;
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
		$output['body']=$this->load->view('myaccount/trip/multicountries/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}

	function savedmulticity_attractions_ajax()
	{
		if($this->input->is_ajax_request())
		{

			$data['itineraryid']=$iti=$itineraryid=$_POST['iti'];
			$cityfile=$_POST['id'];
			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$data['secretkey']=string_encode($itineraryid);
			$dbSecretKey=$this->Trip_fm->getCombinationKeyLogin($iti);
			$countries=$this->getMultiCountriesFromFile($dbSecretKey,$iti);
			//echo "<pre>";print_r($countries);die;

			$mergecountryids='';
			foreach($countries as $k=>$list)
			{
				if($k!=='encryptkey')
				{
					$mergecountryids .= $list['country_id'].'-';
				}
			}

			$cominineCountryidwithcityid=substr($mergecountryids,0,-1).'-'.$basic['id'].'-'.$iti;
			$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


			if($basic['cityimage']!='')
			{
				$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			}
			else
			{
				$data['cityimage']=site_url('assets/images/cairo.jpg');

			}
			$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];


			$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);

			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}
			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);


			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

			$data['cityid']=$cityfile;
			
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body'] =$this->load->view('myaccount/trip/multicountries/getMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
		else
		{
			redirect(site_url());
		}
	}


	function saveMultiOrderSaved()
	{
		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$data['secretkey']=string_encode($itineraryid);

			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);


			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$filestore = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/multicountries/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}
	}


	function alterSavedMultiCountryCity()
	{
		if($this->input->is_ajax_request())
		{
			$postid=explode('-',string_decode($_POST['cityname']));
			$countryid=$postid[0];
			$cityid=$postid[1];
			$iti=$postid[2];
			$addordelete=$_POST['addordelete'];
			$this->alterSavedMultiCountryCityFile($countryid,$cityid,$addordelete,$iti);

		}
	}

	function alterSavedMultiCountryCityFile($countryid,$cityid,$addordelete,$iti)
	{
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/cities'))
		{
			$data['itineraryid']=$itineraryid=$iti;
			$data['secretkey']=string_encode($itineraryid);
			$cityfile=$cityid;
			$file_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
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
							$this->Trip_fm->makeFileForThisCity(md5($cityid),$iti);
							$file_decode[$countryid][]=$cityData;
							$file_decode=CalculateDistance($file_decode,$countryid);
							foreach($file_decode[$countryid] as $ids)
						  	{
						  	 	$idsArray[]=$ids['id'];
						  	}
							break;
						}

					}

				//echo "<pre>";print_r($file_decode);die;

				$data['select']=$addordelete;
				$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);

				if(count($otherCities))
				{
					$options='<option value="">Select City</option>';
					foreach($otherCities as $list)
					{
						$combination=string_encode($list['country_id']."-".$list['id']."-".$iti);

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}


				$data['options']=$options;


				$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/cities','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);


				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));

				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['country_name']=$basic["country_name"];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);
				$data['countryconclusion']=$basic['country_conclusion'];
				//$data['countryimage']=$basic['countryimage'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}

				$dbSecretKey=$this->Trip_fm->getCombinationKeyLogin($iti);

			   $countries=$this->getMultiCountriesFromFile($dbSecretKey,$iti);
				$mergecountryids='';
				foreach($countries as $k=>$list)
				{
					if($k!=='encryptkey')
					{
						$mergecountryids .= $list['country_id'].'-';
					}
				}


				$cominineCountryidwithcityid=substr($mergecountryids,0,-1).'-'.$basic['id'].'-'.$iti;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


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

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
		    $data['filestore']=json_encode($attraction_decode);

			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/multicountries/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}
	}


	function alterSavedMultiAttraction()
	{
		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$_POST['iti'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateSavedMultiMainFile($cityfile,$_POST['attractionid'],$itineraryid);
		}
		else
		{
			$this->updateSavedMultiFile($cityfile,$_POST['attractionid'],$itineraryid);
		}
		$data['secretkey']=string_encode($itineraryid);

		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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


			$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

		}

		$data['cityid']=$cityfile;
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}

		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$output['body']=$this->load->view('myaccount/trip/multicountries/getMainMap', $data, true);
		}
		else
		{
			$output['body']=$this->load->view('myaccount/trip/multicountries/getMap_All', $data, true);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function updateSavedMultiMainFile($cityfile,$attractionid,$iti)
	{
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
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
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateSavedMultiFile($cityfile,$attractionid,$iti)
	{

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
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

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
	}



	/* Code for searched city....... */

	function getCitiesInFileName($iti)
	{
		$randomstring=$iti;
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);
		return $filename;
	}

	function getCitiesInFile($iti)
	{

		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/mainfile','r');
		$filename=fgets($file);
		fclose($file);

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filename,'r');
		$cityarrayinfile=fgets($file);
		$cityarray=json_decode($cityarrayinfile,TRUE);
		fclose($file);

		return $cityarray;

	}

	function getInputs($iti)
	{
		$randomstring=$this->session->userdata('randomstring');
		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/inputs','r');
		$inputs=json_decode(fgets($file),TRUE);
		fclose($file);
		return $inputs;
	}

	function userSearchedCityTrip($id)
	{
		$itid=string_decode($id);
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$this->Account_fm->deleteItinerary($itid);
		}
		
		$tripdetails=$this->Account_fm->resetSearchedCityTrips($id);
		$itid=string_decode($id);
		$data['itineraryid']=$iti=$tripdetails['id'];
		$data['searchcity']=$this->getCitiesInFile($itid);
		$inputs=$this->getInputs($itid);
		$ttldays=0;
		$other=0;
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


		$data['citymd5id']=$data['citypostid']=$cityfile=$data['searchcity'][0]['cityid'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['city']=$basic['city_name'];
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['cityname']=$basic['city_name'];
		$data['countryname']=$basic['country_name'];
		$data['countryconclusion']=$basic['country_conclusion'];
		$data['countryimage']=$basic['countryimage'];
		$data['cityimage']=$basic['cityimage'];
		$data['countrybanner']=$basic['countrybanner'];

		if($other==1)
		{
			$data['othercities'] = $this->Account_fm->getSearchedCityOther($data['searchcity'],$itid);
		}
		else
		{
			$data['othercities'] = array();
		}
		$data['filenm']=$filenm=$this->getCitiesInFileName($itid);
		$data['citypostid']=$cityfile;

		$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][0]['id'].'-'.$iti);

		$attractioncities = file_get_contents(FCPATH.'userfiles/savedfiles/'.$itid.'/'.$filenm);
		$data['attractioncities']=json_decode($attractioncities,TRUE);

		$attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itid.'/'.$data['searchcity'][0]['cityid']);
		$attractioncities_decode=json_decode($attractions,TRUE);
		//echo "<pre>";print_r($attractioncities_decode);die;
		$sort = array();
		foreach($attractioncities_decode as $k=>$v)
		{
			$sort['isselected'][$k] = $v['isselected'];
			$sort['order'][$k] = $v['order'];
			$sort['distance'][$k] = $v['distance'];
		}
		array_multisort($sort['order'], SORT_ASC,$sort['isselected'], SORT_DESC,$attractioncities_decode);
		$data['filestore'] = json_encode($attractioncities_decode);
		$data['webpage'] = 'cityattractions';
		$data['main'] = 'myaccount/trip/singlecountry/cityAttractions';
		
		
		 /*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
		$this->load->vars($data);
		$this->load->view('templates/innermaster');

	}


	function getAllAttractionsOfSingleCitySaved()
	{
		$cityfile=$_POST['id'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];

		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		$data['filenm']=$filenm=$this->getCitiesInFileName($iti);
		$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);

		//echo "<pre>";print_r($attraction_decode);die;

		$finalsort = array();
	    foreach($attraction_decode as $k=>$v)
	    {
		   $finalsort['distance'][$k] = $v['distance'];
	    }
	    array_multisort($finalsort['distance'], SORT_ASC,$attraction_decode);

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
		$data['filestore'] =json_encode($attraction_decode);

		$data['cityid']=$cityfile;
		
		 /*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
	    $output['body']=$this->load->view('myaccount/trip/singlecountry/getMap_All', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function getUserAttractionsOfSingleCountrySaved()
	{

		$cityfile=$_POST['id'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		if($basic['cityimage']!='')
		{
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
		}
		else
		{
			$data['cityimage']=site_url('assets/images/cairo.jpg');

		}
		$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
		$data['filenm']=$filenm=$this->getCitiesInFileName($iti);
		$data['countryid_encrypt']=string_encode($filenm.'-'.$basic['id'].'-'.$iti);
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
		$attraction_decode=json_decode($filestore,TRUE);

		$sort = array();
		foreach($attraction_decode as $k=>$v)
		{
		    $sort['isselected'][$k] = $v['isselected'];
		    $sort['order'][$k] = $v['order'];
		}

	    array_multisort($sort['order'], SORT_ASC,$attraction_decode);

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
		fwrite($file,json_encode($attraction_decode));
		fclose($file);
		$data['filestore']=json_encode($attraction_decode);
		$data['cityid']=$cityfile;
		
		 /*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
		
		$output['body'] =$this->load->view('myaccount/trip/singlecountry/getMap', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}

	function alterMainAttractionSingleSaved()
	{
		$cityfile=$_POST['cityid'];
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		$data['filenm']=$filenm=$this->getCitiesInFileName($iti);

		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$this->updateMainFileSingle($cityfile,$_POST['attractionid'],$iti);
		}
		else
		{
			$this->updateFileSingle($cityfile,$_POST['attractionid'],$iti);
		}
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$filestore = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);


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

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);

		}

		$data['cityid']=$cityfile;
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}

		if(isset($_POST['ismain']) && $_POST['ismain']==1)
		{
			$output['body']=$this->load->view('myaccount/trip/singlecountry/getMainMap', $data, true);
		}
		else
		{
			$output['body']=$this->load->view('myaccount/trip/singlecountry/getMap_All', $data, true);
		}
		
		
		
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}


	function updateMainFileSingle($cityfile,$attractionid,$iti)
	{

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
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
						break;
					}

				}
			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);
		}
		else
		{
			echo "1";
		}
	}


	function updateFileSingle($cityfile,$attractionid,$iti)
	{

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
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

						break;
					}

				}


			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($filestore));
			fclose($file);

		}
		else
		{
			echo "1";
		}
	}


	function addExtraCityInSaved()
	{
		if($this->input->is_ajax_request())
		{
			$cityid=$_POST['cityid'];
			$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
			$citydetails=$this->Account_fm->checkCityExist($cityid,$iti);
			if(count($citydetails))
			{
				$this->Account_fm->addExtraCity($citydetails,$iti);
				$data['searchcity']=$this->getCitiesInFile($iti);

				$inputs=$this->getInputs($itineraryid);
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
							$data['message']='Your preferred number of travel days are less than the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
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
							$data['message']='Your preferred number of travel days are less than the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
						else
						{
							$data['message']='Your preferred number of travel days exceed the recommended time required to tour the chosen destination(s). Would you like to explore an additional city?';
						}
					}


				}

				$data['filenm']=$filenm=$this->getCitiesInFileName($iti);
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
				$data['othercities'] = $this->Account_fm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=1,$iti);
				$cityfile=$data['searchcity'][$lastkey]['cityid'];
				$data['citypostid']=$cityfile;
				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
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
				$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][$lastkey]['id'].'-'.$iti);

				if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$data['searchcity'][$lastkey]['cityid']))
				{
					$attractioncities = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filenm);

					$data['attractioncities']=json_decode($attractioncities,TRUE);

					$attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$data['searchcity'][$lastkey]['cityid']);
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
					//echo "<pre>1";print_r(json_decode($data['filestore'],TRUE));die;
				}
				else
				{
					$this->Account_fm->createAttractionFileForExtraSearchCity($data['searchcity'][$lastkey]['cityid'],$iti);
					$attractioncities = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filenm);
					$data['attractioncities']=json_decode($attractioncities,TRUE);
					$data['filestore'] = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
					//echo "<pre>2";print_r(json_decode($data['filestore'],TRUE));die;
				}
				$data['cityid']=$cityfile;
				$output['body']=$this->load->view('myaccount/trip/singlecountry/addExtraCity', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				//city not exists
			}

		}
	}

	function removeExtraCityFromSave()
	{
		if($this->input->is_ajax_request())
		{
			$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		  $data['filenm']=$filenm=$this->getCitiesInFileName($iti);
			$this->removeThisCityFromFile($_POST['cityid'],$filenm,$iti);
			$data['searchcity']=$this->getCitiesInFile($iti);
			$data['city']=$data['searchcity'][0]['city_name'];
			$data['latitude']=$data['searchcity'][0]['latitude'];
			$data['longitude']=$data['searchcity'][0]['longitude'];
			$data['cityname']=$data['searchcity'][0]['city_name'];
			$data['countryname']=$data['searchcity'][0]['country_name'];
			$data['countryconclusion']=$data['searchcity'][0]['country_conclusion'];
			$data['countryimage']=$data['searchcity'][0]['countryimage'];
			$data['citymd5id']=$cityfile = $data['searchcity'][0]['cityid'];
			$data['othercities'] = $this->Account_fm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=0,$iti);
			$data['isaddordelete']=0;
			$cityfile=$data['searchcity'][0]['cityid'];
			$data['citypostid']=$cityfile;
			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
			$data['countrybanner']=$basic['countrybanner'];
			$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][0]['id'].'-'.$iti);

			$attractioncities = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$data['searchcity'][0]['id']);
			$data['attractioncities']=json_decode($attractioncities,TRUE);
			$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
			$attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$data['searchcity'][0]['cityid']);
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
			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/singlecountry/addExtraCity', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
	}


	function removeThisCityFromFile($cityid,$filenm,$iti)
	{

		$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filenm,'r');
		$fileencodedata=fgets($file);
		$filedata=json_decode($fileencodedata,TRUE);
		$key = array_search($cityid, array_column($filedata,'cityid'));
		if($key!==false && $filedata[$key]['id']!=$filenm)
		{
			unset($filedata[$key]);
		}
	    $arr = array_values($filedata);
	    fclose($file);

	    $file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filenm,'w+');
	    fwrite($file,json_encode($arr));
	    fclose($file);

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityid))
		{
			unlink(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityid);
		}

	}

	function addNewActivityToSavedSearchedCity()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->addNewActivityToSavedSearchedCity_All();
			}
			else
			{
				$this->addNewActivityToSavedSearchedCity_Main();
			}
		}
	}

	function addNewActivityToSavedSearchedCity_All()
	{
			$itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];
			$data['filenm']=$filenm=$this->getCitiesInFileName($itineraryid);
			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{
				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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


				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);


				$data['basic']=$basic;
				$data['itineraryid']=$itineraryid;

				$data['filenm']=$filenm=$this->getCitiesInFileName($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				
				$output['body'] =$this->load->view('myaccount/trip/singlecountry/getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function addNewActivityToSavedSearchedCity_Main()
	{
		    $itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];

			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{

				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$data['itineraryid']=$itineraryid;

				$data['filenm']=$filenm=$this->getCitiesInFileName($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				$output['body'] =$this->load->view('myaccount/trip/singlecountry/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}


	}




	function addNewActivitySavedMultiCountry()
	{
		if($this->input->is_ajax_request())
		{
			if(isset($_POST['isall']) && $_POST['isall']==1)
			{
				$this->addNewActivitySavedMultiCountry_All();
			}
			else
			{
				$this->addNewActivitySavedMultiCountry_Main();
			}
		}
	}

	function addNewActivitySavedMultiCountry_All()
	{
			$itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];

			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{

				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$data['itineraryid']=$itineraryid;
				$data['secretkey']=string_encode($itineraryid);
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['countryconclusion']=$basic['country_conclusion'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				$output['body'] =$this->load->view('myaccount/trip/multicountries/getMap_All', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}
	}

	function addNewActivitySavedMultiCountry_Main()
	{

			$itineraryid=$_POST['iti'];
			$cityfile=$_POST['citypostid'];

			if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile))
			{

				$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
				$data=array();

				$filedata= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
				fwrite($file,json_encode($filedata_decode_merge));
				fclose($file);

				$data['basic']=$basic;
				$data['itineraryid']=$itineraryid;
				$data['secretkey']=string_encode($itineraryid);
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['countryconclusion']=$basic['country_conclusion'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['filestore']=json_encode($filedata_decode_merge);
				$data['cityid']=$cityfile;
				
				/*Travel Packages*/
			    $this->load->model('Packages_fm');
			    $check = $this->Packages_fm->checkAlreadyPurchased();
			    if($check > 0)
			    {
					$data['purcahse_chk'] = 1;
					$data['package'] = $this->Packages_fm->getPurchasedPackage();
					$data['packages'] = array();
				}
				else
				{
					$data['purcahse_chk'] = 0;
					$data['packages'] = $this->Packages_fm->getAll();
				}
				
				$output['body'] =$this->load->view('myaccount/trip/multicountries/getMap', $data, true);
				$this->output->set_content_type('application/json')->set_output(json_encode($output));

			}
			else
			{
				echo "1";
			}

	}


	function saveOrderSingleSaved()
	{

		$cityfile=$_POST['cityid'];
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile))
		{
			$selectedArray=array();
			$arrayToWrite=array();
			$orders = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$decodeorders=json_decode($orders,TRUE);

			$data['filenm']=$filenm=$this->getCitiesInFileName($iti);
			foreach($_POST['listing'] as $key=>$list)
			{
				$decodeorders[$list]['order']=$key;
			}

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($decodeorders));
			fclose($file);

			$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
			$data['latitude']=$basic['citylatitude'];
			$data['longitude']=$basic['citylongitude'];
			$filestore = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile);
			$attraction_decode=json_decode($filestore,TRUE);
			$sort = array();
			foreach($attraction_decode as $k=>$v)
			{
			    $sort['isselected'][$k] = $v['isselected'];
			    $sort['order'][$k] = $v['order'];
			}

			array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
			$data['filestore']=json_encode($attraction_decode);
			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
			$this->load->model('Packages_fm');
			$check = $this->Packages_fm->checkAlreadyPurchased();
			if($check > 0)
			{
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/singlecountry/getMainMap', $data, true);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));


		}
		else
		{
			$arr=array('tripdelete'=>1);
			echo json_encode($arr);die;
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

	function deleteTrip($id)
	{
		$this->Trip_fm->deleteTrip($id);
		writeTripsInFile();
		$this->session->set_flashdata('success', 'Your trip has been deleted.');
		redirect('trips');

	}

	function editTrip($id)
	{
		$data['webpage'] = 'trips';
		$data['main'] = 'myaccount/edittrip';
		$data['trip']=$this->Trip_fm->getTripDetails($id);
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	function updateTrip($id)
	{
		//echo "<pre>";print_r($_POST);die;
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('user_trip_name','Trip Name','trim|required|max_length[500]');
		$this->form_validation->set_rules('start_date','Start Date','trim|required|max_length[10]|min_length[10]');
		$this->form_validation->set_rules('end_date','End Date','trim|required|max_length[10]|min_length[10]|callback_check_greatertrip');

		if($this->session->userdata('askforemail')==1)
		{
			$this->form_validation->set_rules('email','Email','trim|required|max_length[450]|valid_email|is_unique[tbl_front_users.email]',array('is_unique' => 'This email already exist.'));
		}

		if($this->form_validation->run()==FALSE)
		{
			$this->editTrip($id);
		}
		else
		{
			$result=get_invited_trip_details($id);
			$data['webpage'] = 'trips';
			$data['main'] = 'myaccount/edittrip';
			$data['trip']=$this->Trip_fm->updateTrip($id,$result);
			writeTripsInFile();
			if($result!==FALSE){
				$this->session->set_flashdata('success', 'The trip you were invited to has been updated.');
				redirect('invited-trips');
			}
			else
			{
				$this->session->set_flashdata('success', 'Your trip has been updated.');
				redirect('trips');
			}
		}

	}

	public function check_greatertrip()
	{
		$this->load->library('form_validation');
		$start_date=date('Y-m-d', strtotime(str_replace('/', '-', $_POST['start_date'])));
		$end_date=date('Y-m-d', strtotime(str_replace('/', '-', $_POST['end_date'])));
		if(strtotime($start_date)>strtotime($end_date))
		{
				$this->form_validation->set_message('check_greatertrip','End date must be greater than or equal to start date.');
				return FALSE;
		}
		return TRUE;
	}

	function savedSearchedCityXOrder()
	{

		$this->Trip_fm->ChangeOrderOfCities($type='searchcity');
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
	    $data['filenm']=$filenm=$this->getCitiesInFileName($iti);
		$data['searchcity']=$this->getCitiesInFile($iti);
		$data['city']=$data['searchcity'][0]['city_name'];
		$data['latitude']=$data['searchcity'][0]['latitude'];
		$data['longitude']=$data['searchcity'][0]['longitude'];
		$data['cityname']=$data['searchcity'][0]['city_name'];
		$data['countryname']=$data['searchcity'][0]['country_name'];
		$data['countryconclusion']=$data['searchcity'][0]['country_conclusion'];
		$data['countryimage']=$data['searchcity'][0]['countryimage'];
		$data['citymd5id']=$cityfile = $data['searchcity'][0]['cityid'];
		$data['othercities'] = $this->Account_fm->getSearchedCityOtherFromFile($data['searchcity'],$isadd=0,$iti);
		$data['isaddordelete']=0;
		$cityfile=$data['searchcity'][0]['cityid'];
		$data['citypostid']=$cityfile;
		$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
		if($basic['cityimage']!='')
		{
			$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
		}
		else
		{
			$data['cityimage']=site_url('assets/images/cairo.jpg');
		}
		$data['countrybanner']=$basic['countrybanner'];
		$data['countryid_encrypt']=string_encode($filenm.'-'.$data['searchcity'][0]['id'].'-'.$iti);
		$attractioncities = file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$filenm);
		$data['attractioncities']=json_decode($attractioncities,TRUE);
		//echo "<pre>";print_r($data['attractioncities']);die;
		$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
		$attractions=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$data['searchcity'][0]['cityid']);
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
		$data['cityid']=$cityfile;
		
		/*Travel Packages*/
	    $this->load->model('Packages_fm');
	    $check = $this->Packages_fm->checkAlreadyPurchased();
	    if($check > 0)
	    {
			$data['purcahse_chk'] = 1;
			$data['package'] = $this->Packages_fm->getPurchasedPackage();
			$data['packages'] = array();
		}
		else
		{
			$data['purcahse_chk'] = 0;
			$data['packages'] = $this->Packages_fm->getAll();
		}
		
		$output['body']=$this->load->view('myaccount/trip/singlecountry/addExtraCity', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));

	}

	function savedSingleCountryXOrder()
	{
		$this->Trip_fm->ChangeOrderOfCities($type='singlecountry');

		$data['itineraryid']=$itineraryid=$_POST['iti'];
		$countryid=$_POST['coid'];

		if(file_exists(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry'))
		{

			$file_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.'singlecountry');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countryid]))
			{
				$cityfile=$file_decode[$countryid][0]['id'];
				foreach($file_decode[$countryid] as $ids)
				{
				 	$idsArray[]=$ids['id'];
				}

				$data['select']=0;
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

				$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.'singlecountry','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);


				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));


				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];

				//$cominineCountryidwithcityid=$basic['country_id'].'-'.$basic['id'].'-'.$itineraryid;
				//$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				$cominineCountryidwithcityid=$itineraryid.'-'.$basic['id'];
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
				$data['secretkey']=string_encode($itineraryid);
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);

				$returnflag=$this->Trip_fm->getUserRecommededAttractionsForNewCity($cityfile,$itineraryid);
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

			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
			$this->load->model('Packages_fm');
			$check = $this->Packages_fm->checkAlreadyPurchased();
			if($check > 0)
			{
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
		
			
			$output['body']=$this->load->view('myaccount/trip/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}
	}


	function savedMultiCountryXOrder()
	{
		$this->Trip_fm->ChangeOrderOfCities($type='multicountry');
		$data['itineraryid']=$itineraryid=$iti=$_POST['iti'];
		if(file_exists(FCPATH.'userfiles/savedfiles/'.$iti.'/cities'))
		{

			$data['countryid']=$countryid=$_POST['coid'];
			$data['secretkey']=string_encode($itineraryid);
			$file_encode=file_get_contents(FCPATH.'userfiles/savedfiles/'.$iti.'/cities');
			$file_decode=json_decode($file_encode,TRUE);
			if(count($file_decode[$countryid]))
			{
				 $cityfile=$file_decode[$countryid][0]['id'];
				 $file_decode=CalculateDistance($file_decode,$countryid);
				 foreach($file_decode[$countryid] as $ids)
			  	 {
			  	 	$idsArray[]=$ids['id'];
			  	 }

				$data['select']=0;
				$otherCities=$data['otherCities']=$this->Home_fm->getOtherCitiesOfThisCountry($countryid,$idsArray);

				if(count($otherCities))
				{
					$options='<option value="">Select City</option>';
					foreach($otherCities as $list)
					{
						$combination=string_encode($list['country_id']."-".$list['id']."-".$iti);

						$options .='<option value='.$combination.'>'.str_replace("'","\u0027",$list["city_name"]).'</option>';
					}
				}
				else
				{
					$options=1;
				}


				$data['options']=$options;


				$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/cities','w');
				fwrite($file,json_encode($file_decode));
				fclose($file);


				$data['attractioncities'] = $file_decode[$countryid];

				$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity(md5($cityfile));

				$data['attrurl']=site_url('cityAttractionFromGYG').'/'.urlencode($basic['city_name']).'/'.$basic['citylongitude'].'/'.$basic['citylatitude'];
				if($basic['cityimage']!='')
				{
					$data['cityimage']=site_url('userfiles/cities').'/'.$basic['cityimage'];
				}
				else
				{
					$data['cityimage']=site_url('assets/images/cairo.jpg');

				}
				$data['country_name']=$basic["country_name"];
				$data['latitude']=$basic['citylatitude'];
				$data['longitude']=$basic['citylongitude'];
				$data['citypostid']=$cityfile = md5($cityfile);
				$data['countryconclusion']=$basic['country_conclusion'];
				//$data['countryimage']=$basic['countryimage'];
				if($basic['countryimage']!='')
				{
				 	$data['countryimage']=site_url('userfiles/countries/banner').'/'.$basic['countryimage'];
				}
				else
				{
					$data['countryimage']=site_url('assets/images/countrynoimage.jpg');
				}

				$dbSecretKey=$this->Trip_fm->getCombinationKeyLogin($iti);

			   $countries=$this->getMultiCountriesFromFile($dbSecretKey,$iti);
				$mergecountryids='';
				foreach($countries as $k=>$list)
				{
					if($k!=='encryptkey')
					{
						$mergecountryids .= $list['country_id'].'-';
					}
				}


				$cominineCountryidwithcityid=substr($mergecountryids,0,-1).'-'.$basic['id'].'-'.$iti;
				$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);


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

			$file=fopen(FCPATH.'userfiles/savedfiles/'.$iti.'/'.$cityfile,'w');
			fwrite($file,json_encode($attraction_decode));
			fclose($file);
		    $data['filestore']=json_encode($attraction_decode);

			$data['cityid']=$cityfile;
			
			/*Travel Packages*/
		    $this->load->model('Packages_fm');
		    $check = $this->Packages_fm->checkAlreadyPurchased();
		    if($check > 0)
		    {
				$data['purcahse_chk'] = 1;
				$data['package'] = $this->Packages_fm->getPurchasedPackage();
				$data['packages'] = array();
			}
			else
			{
				$data['purcahse_chk'] = 0;
				$data['packages'] = $this->Packages_fm->getAll();
			}
			
			$output['body']=$this->load->view('myaccount/trip/multicountries/getNewCountryMap', $data, true);
		    $this->output->set_content_type('application/json')->set_output(json_encode($output));
			}
			else
			{
				echo "2";
			}

		}
		
		

	}
	
	public function downloadGuide($cityId)
	{
		$redownload_flag = $this->uri->segment(3);
		if($redownload_flag==1)
		{
			$cityDetails = $this->Home_fm->getLatandLongOfCity($cityId);
			if($cityDetails['travelguide']!='')
			{
				$fileName = $cityDetails['travelguide'];
			
				$this->load->helper('download');
				$path = FCPATH.'uploads/city_pdfs/'.$fileName;
				//echo $path;die;
				if(file_exists(FCPATH.'uploads/city_pdfs/'.$fileName))
				{	
					$this->load->model('Packages_fm');
					$this->Packages_fm->updateUseCityTravelGuide($cityDetails['id']);
					force_download(FCPATH.'uploads/city_pdfs/'.$fileName, null);
				}
			}
			else
			{
				$this->session->set_flashdata('error','Travel Guide for the particular city is not available');
				redirect('my-orders');
			}
		}
		else
		{
			$this->load->model('Packages_fm');
			$check = $this->Packages_fm->checkAlreadyDownloaded($cityId);
			$check = $this->Packages_fm->checkAlreadyDownloaded($cityId);
			if($check==0)
			{
				$cityDetails = $this->Home_fm->getLatandLongOfCity($cityId);
				
				if($cityDetails['travelguide']!='')
				{
					$fileName = $cityDetails['travelguide'];
				
					$this->load->helper('download');
					$path = FCPATH.'uploads/city_pdfs/'.$fileName;
					//echo $path;die;
					if(file_exists(FCPATH.'uploads/city_pdfs/'.$fileName))
					{	
						$this->load->model('Packages_fm');
						$this->Packages_fm->updateUserBalance($cityDetails['id']);
						force_download(FCPATH.'uploads/city_pdfs/'.$fileName, null);
					}
				}
				else
				{
					$this->session->set_flashdata('error','Travel Guide for the particular city is not available');
					redirect('my-orders');
				}
			}
			else if($check==1)
			{
				redirect('my-orders');
			}
		}		
	}



}

?>
