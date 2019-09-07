<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Developer extends Front_Controller {

	public function __construct() {
		parent::__construct();
	}

	function ddd()
	{
			$directory=FCPATH.'userfiles/multicountries';
			$dirs = array_filter(glob("{$directory}/*"), 'is_dir');
			if(count($dirs))
			{
					foreach($dirs as $key=>$list)
					{
							$subdirs = array_filter(glob("{$list}/*"), 'is_dir');
							foreach($subdirs as $innerlist)
							{
									if(time()-filemtime($innerlist) >= 50)
									{
											$this->recursiveRemoveDirectory1($innerlist);
									}
							}
						 if(count(glob("$list/*"))==0)
						 {
								rmdir($list);
							}
					}
				}
	}

	function recursiveRemoveDirectory1($directory)
	{
					 foreach(glob("{$directory}/*") as $file)
				 {
					 		if(is_dir($file))
							{
								 $this->recursiveRemoveDirectory($file);
						  }
							else
							{
								 if(file_exists($file))
								 {
									 unlink($file);
								 }
						 }
				 }
				 $exp=explode('/',$directory);
				 if(!in_array(end($exp),array('search','multicountries','files','savedfiles')))
				 {
					 rmdir($directory);
				 }
	}

	function see()
	{
		$this->load->library('session');
		$this->session->set_userdata('fuserid',216);
		$this->session->set_userdata('issocial',1);
		$this->session->set_userdata('askforemail',1);

	}

	function ae()
	{

		echo string_encode('1-2-3-4-5-6-7-8-99-123-444-555-122-44-50-66-600-4344-234-654-445-31-65-87-34');die;
		$enc=string_encode('1-2-3-4-5-6-7-8-99-123-444-555-122-44-50-66-600-4344-234-654-445-31-65-87-34');
		echo $enc."<br/>";
		$dec=string_decode($enc);
		echo $dec."<br/>";die;

		$this->load->library('encryption');

		$str='2-6-8-19-27-28-29-30-48-59-2-6-8-19-27-28-29-30-48-59';
		$rr=strtr(base64_encode($str), '+/=', '-_~');
		echo urlencode($rr)."<br/>";
		$dd=base64_decode(strtr($rr, '-_~', '+/='));
		echo $dd;
		die;
		$key = $this->config->item('encryption_key');
		$encoded_url_safe_string = urlencode(string_encode('2-6-8-19-27-28-29-30-48-59-2-6-8-19-27-28-29-30-48-59', $key, true));
		echo $encoded_url_safe_string;die;
		echo $this->encryption->encrypt('2-6-8-19-27-28-29-30-48-59-63-22-999-22-334-55-66-189-6785-443');
		/*

		//echo $this->encryption->encrypt('2-6-8-19-27-28-29-30-48-59-63-22');
		$enc_username=string_encode('99999999999999999999999');
		$enc_username=str_replace(array('+', '/', '='), array('-', '_', '~'), $enc_username);
		echo $enc_username;
		*/
	}

	function ne()
	{
		$this->load->helper('citydata');
		$Q1=$this->db->query('select id from tbl_city_master');
		foreach ($Q1->result_array() as $row1)
		{
			updateCityData($row1['id']);
		}


	}

	function k()
	{
		$directory=FCPATH.'userfiles/search';
		$this->recursiveRemoveDirectory($directory);

		$directory=FCPATH.'userfiles/files';
		$this->recursiveRemoveDirectory($directory);

		$directory=FCPATH.'userfiles/multicountries';
		$this->recursiveRemoveDirectory($directory);

		$directory=FCPATH.'userfiles/savedfiles';
		$this->recursiveRemoveDirectory($directory);


	}

	function recursiveRemoveDirectory($directory)
	{

	    foreach(glob("{$directory}/*") as $file)
	    {
	        if(is_dir($file)) {
	            $this->recursiveRemoveDirectory($file);
	        } else {
	            unlink($file);
	        }
	    }
	    /*if(time()-filemtime($directory) < 86400)
	    {

	    }*/

	    $exp=explode('/',$directory);
	    /*
	    if(end($exp)!='search')
	    {
	    	rmdir($directory);
		}
		*/

		$exp=explode('/',$directory);
	    if(!in_array(end($exp),array('search','multicountries','files','savedfiles')))
	    {
	    	rmdir($directory);
		}
	}

	/*

	function deletefol($dir=FCPATH.'userfiles/search')
	{
		  foreach(glob($dir . '/*') as $file)
		  {
		    if(is_dir($file))
		    {
		        $this->deletefol($file);
		    }
		  }
		  rmdir($dir);
   }

	*/


	function p()
	{
		 $Q=$this->db->query('select * from tbl_city_master');
	     foreach($Q->result_array() as $row)
	     {
	        $ttd=ceil($row['total_attraction_time']+$row['total_adventure_time']+$row['buffer_days']);

	        $this->db->where('id',$row['id']);
	        $this->db->update('tbl_city_master',array('total_days'=>$ttd));
	     }
	}



	function updateRomeName()
	{
		$this->db->select('tbl_city_master.id,city_name,country_name');
		$this->db->from('tbl_city_master');
		$this->db->join('tbl_country_master','tbl_country_master.id=tbl_city_master.country_id');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				if($row['city_name']!=$row['country_name'])
				{
					$nm=$row['city_name'].', '.$row['country_name'];
					$this->db->where('id',$row['id']);
					$this->db->update('tbl_city_master',array('rome2rio_name'=>$nm));
				}
			}
		}
	}

	function writeStadiums()
	{
		$Q=$this->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q->result_array();

		$Q1=$this->db->query('select id,latitude,longitude from tbl_city_master');
		foreach ($Q1->result_array() as $row1)
		{
			$this->s($row1['id'],$row1['longitude'],$row1['latitude'],$tags);
		}
	}

	function s($city_id,$longitude,$latitude,$tags)
	{

		$Q=$this->db->query('select id,stadium_name as attraction_name,stadium_lat as attraction_lat,stadium_long as attraction_long,stadium_description as attraction_details,stadium_address as attraction_address,stadium_contact as attraction_contact,tag_star,stadium_get_your_guide as attraction_getyourguid,known_for as attraction_known_for from tbl_city_stadiums where city_id="'.$city_id.'"');
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{
				$ispaid=0;
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
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']);
					}
					else
					{
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']).', ';
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
						  'isplace'=>1,
						  'ispaid'=>$ispaid,
						  'category'=>5
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$att_array=$data;

			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/stadium/'.$randomstring,'w');
			fwrite($file,json_encode($att_array));
			fclose($file);
		}



	}

	function update_romename()
	{
		$Q=$this->db->query('select id,rome2rio_name from tbl_city_master where rome2rio_name!=romecountryname');
		foreach($Q->result_array() as $row)
		{

			$start_city=urldecode($row['rome2rio_name']);
			$url = 'http://free.rome2rio.com/api/1.4/json/Geocode?key=gQIcwUKF&query=' . urlencode($start_city).'';
			$content = file_get_contents($url);
			$json = json_decode($content, true);
			//echo "<pre>";print_r($json);die;
			$longname=$json['places'][0]['longName'];
			$countryname=$json['places'][0]['countryName'];

			$explodespace=explode(', ',$longname);

			$Q1=$this->db->query('select country_name from tbl_country_master where country_name="'.$countryname.'" limit 1');
			$cname=$Q1->row_array();
			$co_name=$cname['country_name'];

			if($co_name=='')
			{
				$rome2rio_name=$longname;
			}
			else if($co_name==end($explodespace) && $co_name!='')
			{
				$rome2rio_name=$longname;
			}
			else
			{
				$cnt=count($explodespace)-1;
				$explodespace[$cnt]=$co_name;
				$impstr=implode(', ',$explodespace);
				$rome2rio_name=$impstr;
			}

			//echo "<pre>";print_r($rome2rio_name);die;
			//$rome2rio_name=$json['places'][0]['countryCode'];

			$this->db->where('id',$row['id']);
			$this->db->update('tbl_country_master',array('rome2rio_name'=>$rome2rio_name));
		}
	}

	function updateCountryCode()
	{
		$Q=$this->db->query('select id,rome2rio_name from tbl_country_master');
		foreach($Q->result_array() as $row)
		{
			$start_city=urldecode($row['rome2rio_name']);
			$url = 'http://free.rome2rio.com/api/1.4/json/Geocode?key=gQIcwUKF&query=' . urlencode($start_city).'';
			$content = file_get_contents($url);
			$json = json_decode($content, true);

			$this->db->where('id',$row['id']);
			$this->db->update('tbl_country_master',array('rome2rio_code'=>$json['places'][0]['countryCode']));
		}
	}

	function citynull()
	{
		$banner=array();$small=array();
		$Q=$this->db->query('select city_name from tbl_city_master where cityimage is null');
		echo "<pre>";
		print_r($Q->result_array());die;

	}

	function checkcountrybannernull()
	{
		$banner=array();$small=array();
		$Q=$this->db->query('select country_name,countrybanner,countryimage from tbl_country_master where countrybanner is not null or countrybanner!=""');
		foreach($Q->result_array() as $row)
		{
			if(!file_exists(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']))
			{
				$banner[]=$row;
			}
			if(!file_exists(FCPATH.'userfiles/countries/small/'.$row['countryimage']))
			{
				$small[]=$row;
			}
		}

		echo "<pre>Banner";
		//print_r($Q->result_array());die;
		print_r($banner);
		echo "<pre>Small";print_r($small);die;
	}

	function countrybanner()
	{
		$banner=array();$small=array();
		$Q=$this->db->query('select country_name,countrybanner,countryimage from tbl_country_master where countrybanner is null');
		foreach($Q->result_array() as $row)
		{
			if(!file_exists(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']))
			{
				$banner[]=$row;
			}
			if(!file_exists(FCPATH.'userfiles/countries/small/'.$row['countryimage']))
			{
				$small[]=$row;
			}
		}

		echo "<pre>Banner";
		print_r($Q->result_array());die;
		print_r($banner);
		echo "<pre>Small";print_r($small);die;
	}

	function citybanner()
	{
		$banner=array();$small=array();
		$Q=$this->db->query('select city_name,citybanner,cityimage from tbl_city_master where cityimage!=""');
		foreach($Q->result_array() as $row)
		{
			/*if(!file_exists(FCPATH.'userfiles/cities/banner/'.$row['cityimage']))
			{
				$banner[]=$row;
			}*/
			if(!file_exists(FCPATH.'userfiles/cities/small/'.$row['cityimage']))
			{
				$small[]=$row;
			}
		}

		echo "<pre>Banner";
		//print_r($Q->result_array());die;
		//print_r($banner);die;
		echo "<pre>Small";print_r($small);die;
	}


	function r()
	{

		$Q=$this->db->query('select cityimage from tbl_city_master where cityimage!=""');
		$count1 = 0;
		foreach($Q->result_array() as $row)
		{
			/*if(file_exists(FCPATH.'userfiles/cities/small/'.$row['cityimage']))
			{
				unlink(FCPATH.'userfiles/cities/small/'.$row['cityimage']);
			}*/
			$count1++;
			$config['image_library'] = 'gd2';
			$config['source_image'] = './userfiles/cities/'.$row['cityimage'];
			$config['new_image'] = './userfiles/cities/small/';
			$config['maintain_ratio'] = TRUE;
			$config['overwrite'] = false;
			$config['width'] = 300;
			$config['height'] = 150;//276;
			$config['file_name'] = $row['cityimage'];
			$config['master_dim'] = 'width';
			if($count1>1)
			{
				$this->image_lib->clear();
				$this->image_lib->initialize($config);
			}
			$this->load->library('image_lib', $config); //load library
			$this->image_lib->resize(); //do whatever specified in config
		}



	}

	function checkcityimg()
	{
		$Q=$this->db->query('select id,city_name,cityimage from tbl_city_master where cityimage=""');
		$data=array();
		foreach ($Q->result_array() as $row)
		{
			$data[]=$row;

			/*if(!file_exists(FCPATH.'userfiles/cities/'.$row['cityimage']))
			{
				$data[]=$row;
			}*/
		}

		echo "<pre>";print_r($data);die;

	}



	function writespa()
	{
		$data=array();

		$Q=$this->db->query('select id,tag_name from tbl_tag_master');
		$tags=$Q->result_array();

		/*
		// spa
		$Q1=$this->db->query('select id,ras_lat,ras_long from tbl_city_relaxationspa');

		// Restaurant
		//$Q1=$this->db->query('select id,ran_lat,ran_long from tbl_city_restaurants');

		// Adventure
		//$Q1=$this->db->query('select id,adventure_lat,adventure_long from tbl_city_sports_adventures');
		*/

		$Q1=$this->db->query('select id,latitude,longitude from tbl_city_master');
		foreach ($Q1->result_array() as $row1) {

			$this->writeSpaInFile($row1['id'],$row1['longitude'],$row1['latitude'],$tags);

		}

		/*foreach ($Q1->result_array() as $row1) {
			echo "<pre>";print_r($row1);die;

			$this->writeSpaInFile($row1['id'],$row1['ras_lat'],$row1['ras_long'],$tags);

		}*/
	}

	function writeSpaInFile($city_id,$longitude,$latitude,$tags)
	{
		// spa  2
		//$Q=$this->db->query('select id,ras_name as attraction_name,ras_lat as attraction_lat,ras_long as attraction_long,ras_description as attraction_details,ras_address as attraction_address,ras_contact as attraction_contact,known_for as attraction_known_for,tag_star from tbl_city_relaxationspa where city_id="'.$city_id.'"');

		//Restaurant 3
		//$Q=$this->db->query('select id,ran_name as attraction_name,ran_lat as attraction_lat,ran_long as attraction_long,ran_description as attraction_details,ran_address as attraction_address,ran_contact as attraction_contact,known_for as attraction_known_for,tag_star from tbl_city_restaurants where city_id="'.$city_id.'"');

		//Adventure 4
		$Q=$this->db->query('select id,adventure_name as attraction_name,adventure_lat as attraction_lat,adventure_long as attraction_long,adventure_details as attraction_details,adventure_address as attraction_address,adventure_contact as attraction_contact,adventure_known_for as attraction_known_for,tag_star,adventure_getyourguid as attraction_getyourguid,adventure_buy_ticket as ispaid from tbl_city_sports_adventures where city_id="'.$city_id.'"');

		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{
				$gyg=0;
				if(isset($row['attraction_getyourguid']) && $row['attraction_getyourguid']==1)
				{
					$gyg=1;
				}
				$isplace=1;

				$ispaid=0;
				if(isset($row['ispaid']) && $row['ispaid']==1)
				{
					$ispaid=1;
				}

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
						$known_tags .=str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$tags[$keytag]['tag_name']);
					}
					else
					{
						$known_tags .=str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$tags[$keytag]['tag_name']).', ';
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
						  'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$row['attraction_name']),
						  'knownfor'=>$row['attraction_known_for'],
						  'known_tags'=>$known_tags,
						  'tag_star'=>$row['tag_star'],
						  //'address'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_address']),
						  'getyourguide'=>$gyg,
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						  'isplace'=>$isplace,
						  'ispaid'=>$ispaid,
						  'category'=>4
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$randomstring=md5($city_id);
			//spa
			//$file=fopen(FCPATH.'userfiles/relaxationspa/'.$randomstring,'w');

			//restaurant
			//$file=fopen(FCPATH.'userfiles/restaurant/'.$randomstring,'w');

			//sport
			$file=fopen(FCPATH.'userfiles/sport/'.$randomstring,'w');

			fwrite($file,json_encode($data));
			fclose($file);
		}


	}

	/*function banner()
	{
		$Q=$this->db->query("select id,citybanner,city_name from tbl_city_master where citybanner!=''");
		$c=0;
		foreach($Q->result_array() as $key=>$row)
		{
			if(file_exists(FCPATH.'userfiles/nre/'.$row['city_name'].'.jpg'))
			{
				$t=$row['city_name'].'_banner'.$key.'.jpg';
				$oldfolder=FCPATH.'userfiles/nre/'.$row['city_name'].'.jpg';
				$newfolder=FCPATH.'userfiles/cities/banner/'.$t;
				rename($oldfolder, $newfolder);

				$this->db->where('city_name',$row['city_name']);
				$this->db->update('tbl_city_master',array('citybanner'=>$t));
				$this->resizeimages($t);
				if(file_exists(FCPATH.'userfiles/cities/banner/'.$row['citybanner']))
				{
					unlink(FCPATH.'userfiles/cities/banner/'.$row['citybanner']);
				}
				if(file_exists(FCPATH.'userfiles/cities/tiny/'.$row['citybanner']))
				{
					unlink(FCPATH.'userfiles/cities/tiny/'.$row['citybanner']);
				}
				$c++;
			}
		}
	}

	function resizeimages($t)
	{
		STATIC $count1 = 0;
		$count1++;
		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/cities/banner/'.$t;
		$config['new_image'] = './userfiles/cities/tiny/';
		$config['maintain_ratio'] = TRUE;
		$config['overwrite'] = false;
		$config['width'] = 140;
		$config['height'] = 100;//276;
		$config['file_name'] = $t;
		$config['master_dim'] = 'width';
		if($count1>1)
		{
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
		}
		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config
	}

	*/

	/*function banner()
	{
		$Q=$this->db->query("select id,countrybanner,country_name from tbl_country_master where countrybanner is null");

		$c=0;
		foreach($Q->result_array() as $key=>$row)
		{
			//print_r($row);die;
			if(file_exists(FCPATH.'userfiles/nre/'.$row['country_name'].'.jpg'))
			{
				$t=$row['country_name'].$key.'.jpg';
				$oldfolder=FCPATH.'userfiles/nre/'.$row['country_name'].'.jpg';
				$newfolder=FCPATH.'userfiles/countries/banner/'.$t;
				rename($oldfolder, $newfolder);

				$this->db->where('country_name',$row['country_name']);
				$this->db->update('tbl_country_master',array('countrybanner'=>$t));
				$this->resizeimages($t);
				if(file_exists(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']))
				{
					unlink(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']);
				}
				if(file_exists(FCPATH.'userfiles/countries/tiny/'.$row['countrybanner']))
				{
					unlink(FCPATH.'userfiles/countries/tiny/'.$row['countrybanner']);
				}
				$c++;
			}
		}
	}*/


	function banner()
	{
		$Q=$this->db->query("select id,countrybanner,country_name from tbl_country_master where countrybanner!=''");

		$c=0;
		foreach($Q->result_array() as $key=>$row)
		{
			//print_r($row);die;
			if(file_exists(FCPATH.'userfiles/nre/'.$row['country_name'].'.jpg'))
			{
				if(file_exists(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']))
				{
					unlink(FCPATH.'userfiles/countries/banner/'.$row['countrybanner']);
				}
				if(file_exists(FCPATH.'userfiles/countries/tiny/'.$row['countrybanner']))
				{
					unlink(FCPATH.'userfiles/countries/tiny/'.$row['countrybanner']);
				}
				$t=$row['country_name'].'_banner'.$key.'.jpg';
				$oldfolder=FCPATH.'userfiles/nre/'.$row['country_name'].'.jpg';
				$newfolder=FCPATH.'userfiles/countries/banner/'.$t;
				rename($oldfolder, $newfolder);

				$this->db->where('country_name',$row['country_name']);
				$this->db->update('tbl_country_master',array('countrybanner'=>$t));
				$this->resizeimages($t);
				$c++;
			}
		}
	}

	function resizeimages($t)
	{
		STATIC $count1 = 0;
		$count1++;
		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/countries/banner/'.$t;
		$config['new_image'] = './userfiles/countries/tiny/';
		$config['maintain_ratio'] = TRUE;
		$config['overwrite'] = false;
		$config['width'] = 140;
		$config['height'] = 100;//276;
		$config['file_name'] = $t;
		$config['master_dim'] = 'width';
		if($count1>1)
		{
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
		}
		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config
	}



	function banner1()
	{
		$Q=$this->db->query("select id,countryimage,country_name from tbl_country_master where countryimage is null");

		$c=0;
		foreach($Q->result_array() as $key=>$row)
		{
			//print_r($row);die;
			if(file_exists(FCPATH.'userfiles/resize/'.$row['country_name'].'.jpg'))
			{
				$t=$row['country_name'].'_image'.$key.'.jpg';
				$oldfolder=FCPATH.'userfiles/resize/'.$row['country_name'].'.jpg';
				$newfolder=FCPATH.'userfiles/countries/'.$t;
				rename($oldfolder, $newfolder);

				$this->db->where('country_name',$row['country_name']);
				$this->db->update('tbl_country_master',array('countryimage'=>$t));
				$this->resizeimages1($t);

				/*if(file_exists(FCPATH.'userfiles/countries/small/'.$row['countryimage']))
				{
					unlink(FCPATH.'userfiles/countries/small/'.$row['countryimage']);
				}
				if(file_exists(FCPATH.'userfiles/countries/tiny/'.$row['countryimage']))
				{
					unlink(FCPATH.'userfiles/countries/tiny/'.$row['countryimage']);
				}*/
				$c++;
			}
		}
	}

	function resizeimages1($t)
	{
		STATIC $count1 = 0;
		$count1++;
		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/countries/'.$t;
		$config['new_image'] = './userfiles/countries/tiny/';
		$config['maintain_ratio'] = TRUE;
		$config['overwrite'] = false;
		$config['width'] = 140;
		$config['height'] = 100;//276;
		$config['file_name'] = $t;
		$config['master_dim'] = 'width';
		if($count1>1)
		{
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
		}
		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config

		$count1++;
		$config['image_library'] = 'gd2';
		$config['source_image'] = './userfiles/countries/'.$t;
		$config['new_image'] = './userfiles/countries/small/';
		$config['maintain_ratio'] = TRUE;
		$config['overwrite'] = false;
		$config['width'] = 450;
		$config['height'] = 254;//276;
		$config['file_name'] = $t;
		$config['master_dim'] = 'width';
		$this->image_lib->clear();
		$this->image_lib->initialize($config);
		$this->load->library('image_lib', $config); //load library
		$this->image_lib->resize(); //do whatever specified in config
	}

	function uploadimage()
	{
		$Q=$this->db->query('select id,city_name from tbl_city_master');
		foreach($Q->result_array() as $row)
		{
			if(file_exists(FCPATH.'userfiles/resize/'.$row['city_name'].'.jpg'))
			{
				$t=time().'.jpg';
				$oldfolder=FCPATH.'userfiles/resize/'.$row['city_name'].'.jpg';
				$newfolder=FCPATH.'userfiles/cities/'.$t;
				rename($oldfolder, $newfolder);

				$this->db->where('id',$row['id']);
				$this->db->update('tbl_city_master',array('cityimage'=>$t));
			}
		}
	}

	function ccode()
	{
		$Q=$this->db->query('select id,country_name from tbl_country_master');
		foreach($Q->result_array() as $row)
		{
			$this->db->where('id',$row['id']);
			$this->db->update('tbl_country_master',array('countrycode'=>$row['country_name']));
		}
	}


	function updateslug()
	{
		$Q1=$this->db->query('select id,stadium_name from tbl_city_stadiums');
		foreach ($Q1->result_array() as $row1)
		{
			$this->makeslug($row1['id'],$row1['stadium_name']);
		}
	}

	function makeslug($id,$name)
	{
		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_stadiums',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $name,
		);
		$slug = $this->slug->create_uri($slugdata,$id);

		$this->db->where('id',$id);
		$this->db->update('tbl_city_stadiums',array('slug'=>$slug));

	}



	function zip()
	{
		$Q1=$this->db->query('select id,country_id from tbl_city_master');
		foreach ($Q1->result_array() as $row1)
		{
			$this->zipcode($row1['id'],$row1['country_id']);
		}
	}

	function zipcode($city_id,$country_id)
	{
		//$city_id=139;

		$attractionzipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_paidattractions');
		$this->db->where('ispaid <',2);
		$this->db->where('zipcode !=','');
		$this->db->where('city_id',$city_id);
		$this->db->group_by('zipcode');
		$this->db->having('count >',0);
		$this->db->order_by('count','DESC');
		$Q=$this->db->get();

		foreach($Q->result_array() as $row)
		{
			$att_zip=array(
					'zipcode'=>$row['zipcode'],
					'total'=>$row['count'],
					'city_id'=>$city_id,
					'country_id'=>$country_id
				);
			$this->db->insert('tbl_city_zipcodes',$att_zip);
		}


		$spazipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_relaxationspa');
		$this->db->where('zipcode !=','');
		$this->db->where('city_id',$city_id);
		$this->db->group_by('zipcode');
		$this->db->order_by('count','DESC');
		$Q1=$this->db->get();
		foreach($Q1->result_array() as $row)
		{
			$qr=$this->db->query('select id,total from tbl_city_zipcodes where city_id="'.$city_id.'" and country_id="'.$country_id.'" and zipcode="'.$row['zipcode'].'"');
			if($qr->num_rows()>0)
			{
				$retdata=$qr->row_array();
				$rel_data=array(
						'total'=>$retdata['total']+$row['count'],
					);

				$this->db->where('id',$retdata['id']);
				$this->db->update('tbl_city_zipcodes',$rel_data);

			}
			else
			{
				$rel_data=array(
					'zipcode'=>$row['zipcode'],
					'total'=>$row['count'],
					'city_id'=>$city_id,
					'country_id'=>$country_id
				);
				$this->db->insert('tbl_city_zipcodes',$rel_data);
			}
		}

		$spazipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_restaurants');
		$this->db->where('zipcode !=','');
		$this->db->where('city_id',$city_id);
		$this->db->group_by('zipcode');
		$this->db->order_by('count','DESC');
		$Q1=$this->db->get();
		foreach($Q1->result_array() as $row)
		{
			$qr=$this->db->query('select id,total from tbl_city_zipcodes where city_id="'.$city_id.'" and country_id="'.$country_id.'" and zipcode="'.$row['zipcode'].'"');
			if($qr->num_rows()>0)
			{
				$retdata=$qr->row_array();
				$rel_data=array(
						'total'=>$retdata['total']+$row['count'],
					);

				$this->db->where('id',$retdata['id']);
				$this->db->update('tbl_city_zipcodes',$rel_data);

			}
			else
			{
				$rel_data=array(
					'zipcode'=>$row['zipcode'],
					'total'=>$row['count'],
					'city_id'=>$city_id,
					'country_id'=>$country_id
				);
				$this->db->insert('tbl_city_zipcodes',$rel_data);
			}
		}

		$spazipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_sports_adventures');
		$this->db->where('zipcode !=','');
		$this->db->where('city_id',$city_id);
		$this->db->group_by('zipcode');
		$this->db->order_by('count','DESC');
		$Q1=$this->db->get();
		foreach($Q1->result_array() as $row)
		{
			$qr=$this->db->query('select id,total from tbl_city_zipcodes where city_id="'.$city_id.'" and country_id="'.$country_id.'" and zipcode="'.$row['zipcode'].'"');
			if($qr->num_rows()>0)
			{
				$retdata=$qr->row_array();
				$rel_data=array(
						'total'=>$retdata['total']+$row['count'],
					);

				$this->db->where('id',$retdata['id']);
				$this->db->update('tbl_city_zipcodes',$rel_data);

			}
			else
			{
				$rel_data=array(
					'zipcode'=>$row['zipcode'],
					'total'=>$row['count'],
					'city_id'=>$city_id,
					'country_id'=>$country_id
				);
				$this->db->insert('tbl_city_zipcodes',$rel_data);
			}
		}







	}

	function spa($city_id)
	{

		$spazipcode=array();
		$this->db->select('zipcode,count(*) as count');
		$this->db->from('tbl_city_relaxationspa');
		$this->db->group_by('zipcode');
		$this->db->where('zipcode !=','');
		$this->db->order_by('count','DESC');
		$this->db->limit(1);
		$this->db->where('city_id',$city_id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$spazipcode=$Q->row_array();
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
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/trades/add_question/'.$id);
			}
			else
			{
				$image = $this->upload->data();
				ini_set("display_errors",0);
				require_once 'excel_reader2.php';
				$insert=array();
				$data = new Spreadsheet_Excel_Reader(FCPATH."userfiles/quiz/".$image['file_name']);
				$c=0;$f=0;
				//echo count($data->sheets);die;
				for($i=0;$i<count($data->sheets);$i++)
				//for($i=0;$i<1;$i++)
				{
						if(count($data->sheets[$i][cells])>0) // checking sheet not empty
						{
							$c=0;
							foreach($data->sheets[$i][cells] as $key=>$list)
							{
								if($c>0)
								{
									if($i==0)
									{
										$table='tbl_city_paidattractions';
									}
									else if($i==1)
									{
										$table='tbl_city_relaxationspa';
									}
									else if($i==2)
									{
										$table='tbl_city_restaurants';
									}
									else if($i==3)
									{
										$table='tbl_city_sports_adventures';
									}
									else if($i==4)
									{
										$table='tbl_city_stadiums';
									}

									if($c!=0 && isset($list) && $list!='')
									{

										if($list[2]!='')
										{
											$arr=array(
													'credit'=>htmlspecialchars($list[2]),
												);

											$this->db->where('id',$list[1]);
											$this->db->update($table,$arr);
										}

									}
								}


								$c++;

							}
						}
				}

				unlink(FCPATH."userfiles/quiz/".$image['file_name']);
				redirect('developer/import');
			}
		}
	}



	function importfile1()
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
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/trades/add_question/'.$id);
			}
			else
			{
				$image = $this->upload->data();
				ini_set("display_errors",0);
				require_once 'excel_reader2.php';
				$insert=array();
				$data = new Spreadsheet_Excel_Reader(FCPATH."userfiles/quiz/".$image['file_name']);
				$c=0;$f=0;
				for($i=0;$i<count($data->sheets);$i++)
				{
						if(count($data->sheets[$i][cells])>0) // checking sheet not empty
						{

							foreach($data->sheets[$i][cells] as $key=>$list)
							{

								if($c!=0 && isset($list) && $list!='')
								{

									/*if(file_exists(FCPATH.'userfiles/nre/'.$list[1].'.jpg'))
									{

										$t=time().'.jpg';
										$oldfolder=FCPATH.'userfiles/nre/'.$list[1].'.jpg';
										$newfolder=FCPATH.'userfiles/cities/banner/'.$t;
										rename($oldfolder, $newfolder);
									    $this->db->where('city_name',$list[1]);
										$this->db->update('tbl_city_master',array('citybanner'=>$t));

										$this->resizeimages($t,$c);


									}
									else
									*/

									if($list[5]!='')
									{

										if(file_exists(FCPATH.'userfiles/nre/'.$list[5].'.jpg'))
										{
											//echo $list[5];die;
											$t=time().$c.'.jpg';
											$oldfolder=FCPATH.'userfiles/nre/'.$list[5].'.jpg';
											$newfolder=FCPATH.'userfiles/cities/banner/'.$t;
											rename($oldfolder, $newfolder);
											$this->db->where('city_name',$list[1]);
											$this->db->update('tbl_city_master',array('citybanner'=>$t));
											$this->resizeimages($t,$c);

										}
									}


								}
								$c++;

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

		$search = 'php vadodara';
		$results = json_decode( file_get_contents( 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=' . urlencode( $search ) ),TRUE );
		print_r($results);die;
		print_r($results->responseData->cursor->estimatedResultCount);die;

	
		/*
		$params = array('q' => 'obama');
		$content = file_get_contents('https://www.google.com/search?' . http_build_query($params));
		echo "<pre>";print_r($content);die;
		preg_match('/About (.*) results/i', $content, $matches);
		echo !empty($matches[1]) ? $matches[1] : 0;
			*/

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
		$Q=$this->db->query('select id,attraction_name,attraction_lat,attraction_long,attraction_details,attraction_address,attraction_getyourguid,attraction_contact,attraction_known_for,tag_star,ispaid from tbl_city_paidattractions where city_id="'.$city_id.'" order by FIELD(tag_star, 2) DESC');
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{
				$ispaid=0;
				$isplace=1;
				if($row['ispaid']==1)
				{
					$ispaid=1;
				}

				if($row['ispaid']==2)
				{
					$isplace=0;
				}


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
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']);
					}
					else
					{
						$known_tags .=str_replace(array("\n", "\r","'"),array("","","\u0027"),$tags[$keytag]['tag_name']).', ';
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
						  'name'=>str_replace(array("\n", "\r","'",'"'),array("","","\u0027","\u0022"),$row['attraction_name']),
						  'knownfor'=>$row['attraction_known_for'],
						  'known_tags'=>$known_tags,
						  'tag_star'=>$row['tag_star'],
						  //'address'=>str_replace(array("\n", "\r","'"),array("","","\u0027"),$row['attraction_address']),
						  'getyourguide'=>$row['attraction_getyourguid'],
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						  'isplace'=>$isplace,
						  'ispaid'=>$ispaid,
						  'category'=>1
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
						  'isplace'=>0,
						  'ispaid'=>0,
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

	function generateSlug()
	{
		 $Q=$this->db->query('select id,user_trip_name from tbl_itineraries where slug IS NULL');
		 foreach($Q->result_array() as $row)
		 {
		 	$slg=$row['user_trip_name'];

			 	$config = array(
				'field' => 'slug',
				'slug' => 'slug',
				'table' => 'tbl_itineraries',
				'id' => 'id',
			);

			$this->load->library('slug', $config);
			$slugdata = array(
				'slug' => $slg,
			);
			$slug = $this->slug->create_uri($slugdata);

			$this->db->where('id',$row['id']);
			$this->db->update('tbl_itineraries',array('slug'=>$slug));
		 }
	}


	function updatecc()
	{
		$this->load->model('Trip_fm');
		$Q=$this->db->query('select * from tbl_itineraries');
		foreach ($Q->result_array() as $list)
		{

			if($list['trip_type']!=2)
			{

				$tripname_main=$this->Trip_fm->getContinentCountryName($list['country_id']);
				$dt=$tripname_main['country_name'];
				$trp=$this->getTripName1($list['tripname']);
				$this->db->where('id',$list['id']);
				$this->db->update('tbl_itineraries',array('user_trip_name'=>'Trip '.$dt,'citiorcountries'=>$trp));

			}
			else
			{
				//$tripname=explode('-',$list['tripname']);
				//echo $list['tripname'];die;
				$tripname_main=$this->Trip_fm->getContinentName($list['tripname']);
				$tripname_main_name='Trip '.$tripname_main['country_name'];

				$trp=$this->getTripName($list['tripname']);

				$this->db->where('id',$list['id']);
				$this->db->update('tbl_itineraries',array('user_trip_name'=>$tripname_main_name,'citiorcountries'=>$trp));

			}

		}
	}

	function getTripName($tnm)
	{
		$exp=explode('-',$tnm);
		$s='';
		for($i=0;$i<count($exp);$i++)
		{
			$q=$this->db->query('select country_name from tbl_country_master where rome2rio_code="'.$exp[$i].'"');
			$dt=$q->row_array();
			$s .=$dt['country_name'].'-';
		}
		return substr($s,0,-1);
	}

	function getTripName1($tnm)
	{
		$exp=explode('-',$tnm);
		$s='';
		for($i=0;$i<count($exp);$i++)
		{
			$q=$this->db->query('select city_name from tbl_city_master where code="'.$exp[$i].'"');
			$dt=$q->row_array();
			$s .=$dt['city_name'].'-';
		}
		return substr($s,0,-1);
	}

}
