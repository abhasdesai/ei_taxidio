<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Developer extends Front_Controller {

	public function __construct() {
		parent::__construct();
	}


	function zip()
	{
		$Q1=$this->db->query('select id from tbl_city_master');
		foreach ($Q1->result_array() as $row1) 
		{
			$this->zipcode($row1['id']);	
		}
	}

	function zipcode($city_id)
	{
		$attractionzipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_paidattractions');
		$this->db->where('zipcode <',2);
		$this->db->group_by('zipcode');
		$this->db->order_by('count','DESC');
		$this->db->limit(1);
		$this->db->where('city_id',$city_id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$attractionzipcode=$Q->row_array();
		}

		$spazipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_relaxationspa');
		$this->db->group_by('zipcode');
		$this->db->order_by('count','DESC');
		$this->db->limit(1);
		$this->db->where('city_id',$city_id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$spazipcode=$Q->row_array();
		}

		$max_zipcode='';
		if(count($attractionzipcode)>0 && count($spazipcode)>0)
		{
			if($attractionzipcode['count']>$spazipcode['count'])
			{
				$max_zipcode=$attractionzipcode['zipcode'];
			}
			else
			{
				$max_zipcode=$spazipcode['zipcode'];
			}
		}
		else if(count($attractionzipcode)>0 && count($spazipcode)<1)
		{
			$max_zipcode=$attractionzipcode['zipcode'];
		}
		else if(count($attractionzipcode)<1 && count($spazipcode)>0)
		{
			$max_zipcode=$spazipcode['zipcode'];
		}

		$this->db->where('id',$city_id);
		$this->db->update('tbl_city_master',array('max_zipcode'=>$max_zipcode));
	}


	function import()
	{
		$this->load->view('import');
	}
	
	function importfile()
	{
		$flag1 = true;
		$errormsg = "";
		if ($_FILES['excel']['name'] != "") 
		{
			$config['upload_path'] = './userfiles/quiz/';
			$config['allowed_types'] = 'xls';
			$config['max_size'] = '0';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width'] = '';
			$config['max_height'] = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('excel')) 
			{
				$flag1 = false;
				$error = array('warning' => $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error[warning]));
				redirect('admins/trades/add_question/'.$id);
			} 
			else 
			{
				$image = $this->upload->data();
				ini_set("display_errors",0);
				require_once 'excel_reader2.php';
				$insert=array();
				$data = new Spreadsheet_Excel_Reader(FCPATH."userfiles/quiz/".$image['file_name']);
				for($i=0;$i<count($data->sheets);$i++)
				{
						if(count($data->sheets[$i][cells])>0) // checking sheet not empty
						{ 
							$c=0;
							foreach($data->sheets[$i][cells] as $key=>$list)
							{
								$c++;
								if($c!=1 && isset($list) && $list!='')
								{
									$zipcode='';
									if(isset($list[3]) && $list[3]!='')
									{
										$zipcode=$list[3];
									}
									
									$this->db->where('id',$list[1]);
									$this->db->update('tbl_city_relaxationspa',array('zipcode'=>$zipcode));			
								}
							}
						}
				}

				unlink(FCPATH."userfiles/quiz/".$image['file_name']);
				redirect('developer/import');
			}
		}
	}



	function latlng()
	{
		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long from tbl_city_paidattractions');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				//echo "<pre>";print_r($row);die;
				$latitude=$row['attraction_lat'];
				$longitude=$row['attraction_long'];
				$jsondata=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&libraries=places');
				$jsondata_decode=json_decode($jsondata,TRUE);

				$addressComponents = $jsondata_decode->results[0]->address_components;

				 echo "<pre>";print_r($addressComponents);die;
				
        		 
			}
		}	
	}

	function at()
	{
		$data=array();

		$Q1=$this->db->query('select id from tbl_city_master');
		foreach ($Q1->result_array() as $row1) {
			
			$this->c($row1['id']);	

		}

		$this->getAllCountries();

	}

	function c($city_id)
	{
		$Q=$this->db->query('select sum(attraction_time_required) as total_attraction_time from tbl_city_paidattractions where city_id="'.$city_id.'"');
		$attractiontime=$Q->row_array();

		$Qa=$this->db->query('select sum(adventure_time_required) as total_adventure_time_required from tbl_city_sports_adventures where city_id="'.$city_id.'"');
		$adventuretime=$Qa->row_array();
		
		$Q1=$this->db->query('select buffer_days from tbl_city_master where id="'.$city_id.'"');
		$bufferdays=$Q1->row_array();

			
		if(isset($bufferdays['buffer_days']) && $bufferdays['buffer_days']!='')
		{
			$buffer=$bufferdays['buffer_days'];
		}
		else
		{
			$buffer=0;	
		}

		if(isset($attractiontime['total_attraction_time']) && $attractiontime['total_attraction_time']!='')
		{
			$attraction=$attractiontime['total_attraction_time'];
		}
		else
		{
			$attraction=0;	
		}

		
		if(isset($adventuretime['total_adventure_time_required']) && $adventuretime['total_adventure_time_required']!='')
		{
			$adventure=$adventuretime['total_adventure_time_required'];
		}
		else
		{
			$adventure=0;	
		}

		$total=$buffer + number_format((float)$attraction/12, 2, '.', '') + number_format((float)$adventure/12, 2, '.', '');
		
		$savedata=array(
				'total_attraction_time'=>number_format((float)$attraction/12, 2, '.', ''),
				'total_adventure_time'=>number_format((float)$adventure/12, 2, '.', ''),
				'total_days'=>round($total)
			);

		$this->db->where('id',$city_id);
		$this->db->update('tbl_city_master',$savedata);


	}


	function getAllCountries()
	{
		$Q=$this->db->query('select id from tbl_country_master');
		foreach($Q->result_array() as $row)
		{
			$this->calculateHours($row['id']);
		}
	}

	function calculateHours($country_id)
	{
		$Q=$this->db->query('select sum(total_days) as total from tbl_city_master where country_id="'.$country_id.'"');
		if($Q->num_rows()>0)
		{
			$data=$Q->row_array();
			$ttl=0;
			if(isset($data['total']) && $data['total']!='')
			{
				$ttl=$data['total'];
			}	

			$updateData=array(
					'country_total_days'=>$ttl
					);

			$this->db->where('id',$country_id);
			$this->db->update('tbl_country_master',$updateData);
		}
		
	}

	function ttt()
	{
		
		/*$search = 'php vadodara';
		$results = json_decode( file_get_contents( 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=' . urlencode( $search ) ),TRUE );
		print_r($results->responseData->cursor->estimatedResultCount);die;

		*/
	
		$params = array('q' => 'narendra modi');
		$content = file_get_contents('http://www.google.pk/search?' . http_build_query($params));
		preg_match('/About (.*) results/i', $content, $matches);
		echo !empty($matches[1]) ? $matches[1] : 0;


	}

	function setA()
	{
		$data=array(
				'attraction_buy_ticket'=>0,
				'attraction_getyourguid'=>0
			);
		$this->db->where('ispaid',0);
		$this->db->update('tbl_city_paidattractions',$data);

		$this->setB();
	}

	function setB()
	{
		$data=array(
				'attraction_buy_ticket'=>0,
				'attraction_getyourguid'=>0
			);
		$this->db->where('ispaid',2);
		$this->db->update('tbl_city_paidattractions',$data);
	}

	
	function writeAttractionsInFile()
	{
		$data=array();

		$Q=$this->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q->result_array();

		
		$Q1=$this->db->query('select id,latitude,longitude from tbl_city_master');
		foreach ($Q1->result_array() as $row1) {
			
			$this->b($row1['id'],$row1['longitude'],$row1['latitude'],$tags);	

		}
	}

	function b($city_id,$longitude,$latitude,$tags)
	{
		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star from tbl_city_paidattractions where city_id="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{  
				$knwofortag=array();
				$knwofortag=explode(',',$row['attraction_known_for']);
				$known_tags='';
				$t=0;
				for($i=0;$i<count($knwofortag);$i++)
				{
					$t++;
					$keytag = array_search($knwofortag[$i], array_column($tags, 'id'));;
					
					if($t==count($knwofortag))
					{
						$known_tags .=$tags[$keytag]['tag_name'];
					}
					else
					{
						$known_tags .=$tags[$keytag]['tag_name'].', ';	
					}
				}
				

				$data[$key]['type']='Feature';
				$data[$key]['geometry']=array(
						'type'=>'Point',
						);
				$data[$key]['geometry']['coordinates']=array(
						'0'=>$row['attraction_long'],
						'1'=>$row['attraction_lat'],
						);
				$data[$key]['properties']=array(
						  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_name']),
						  'knownfor'=>$row['attraction_known_for'],
						  'known_tags'=>$known_tags,
						  'tag_star'=>$row['tag_star'],
						  //'address'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_address']),
						  'getyourguide'=>str_replace(array("\n", "\r"),array("",""),$row['attraction_getyourguid']),
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$mandatorydata=$this->mandatoryTags($city_id);
			if(count($mandatorydata))
			{
				$att_array=array_merge($data,$mandatorydata);
			}
			else
			{
				$att_array=$data;
			}


			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($att_array));
			fclose($file);
		}
		else
		{
			$mandatorydata=$this->mandatoryTags($city_id);
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($mandatorydata));
			fclose($file);
		}


	}


	function mandatoryTags($city_id)
	{
		$mand=array();
		$Q1=$this->db->query('select id,mandatory_dest,mandatory_lat,mandatory_long,(select mandatory_tag from tbl_mandatory_tags where id=city_mandatory_tag_master.mandatory_tag_id) as tag from city_mandatory_tag_master where city_id="'.$city_id.'" order by id DESC');
		if($Q1->num_rows()>0)
		{
			foreach($Q1->result_array() as $k=>$row)
			{
				$mand[$k]['type']='Feature';
				$mand[$k]['geometry']=array(
						'type'=>'Point',
						);
				$mand[$k]['geometry']['coordinates']=array(
						'0'=>$row['mandatory_long'],
						'1'=>$row['mandatory_lat'],
						);
				$mand[$k]['properties']=array(
						  'name'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['mandatory_dest']),
						  'knownfor'=>0,
						  'known_tags'=>$row['tag'],
						  'tag_star'=>1,
						  //'address'=>$row['tag'],
						  'getyourguide'=>0,
						  'attractionid'=>'tag_'.$row['id'],
						  'cityid'=>md5($city_id),
						  'isplace'=>0
						);
			}
		}


		return $mand;

	}

	function rome1() {
		$start_city = urlencode('Vadodara, India');
		$end_city = urlencode('Czech Republic');
		$url = 'http://free.rome2rio.com/api/1.2/json/Search?key=xa3wFHMZ&query&oName=' . $start_city . '&dName=' . $end_city . '';
		$content = file_get_contents($url);
		$json = json_decode($content, true);

		echo "<pre>";
		print_r($json);
		die;

		foreach ($json['places'] as $key => $value) {
			//echo "<pre>";
			print_r($value);
			die;
			$data[] = array('name' => trim($value['longName']));
		}
		echo json_encode($data);
	}

	function rome() {
		$start_city = urlencode('egypt');
		$end_city = urlencode('Italy');
		$url = 'http://free.rome2rio.com/api/1.2/json/Search?key=xa3wFHMZ&query&oName=' . $start_city . '&dName=' . $end_city . '';
		$ch = curl_init();
		// Disable SSL verification.	/etc/pki/tls/openssl.cnf
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Return response as string rather than outputting it directly
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set cURL url
		curl_setopt($ch, CURLOPT_URL, $url);
		// Execute request
		$result = curl_exec($ch);

		
		$data = json_decode($result, true);
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		// Close cURL resource
		curl_close($ch);

	}


	function atlog()
	{
		$Q=$this->db->query('select id,country_id,city_id,attraction_known_for,attraction_time_required from tbl_city_paidattractions');
		$citydata=$Q->result_array();
				
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$known_for=explode(',',$row['attraction_known_for']);

				for($i=0;$i<count($known_for);$i++)
				{
					$logdata=array(
						'country_id'=>$row['country_id'],
						'city_id'=>$row['city_id'],
						'attraction_id'=>$row['id'],
						'tag_id'=>$known_for[$i],
						'tag_hours'=>$row['attraction_time_required'],
					);
					$this->db->insert('tbl_city_attraction_log',$logdata);
				}

			}
		}
	}
}