<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cityattraction_m extends CI_Model {

	function add() {

		//echo "<pre>";
		//print_r($_POST);die;

        $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$zipcode='';
		if($_POST['ispaid']==1)
		{
			$attraction_admissionfee=$_POST['attraction_admissionfee'];
			$attraction_buy_ticket=$_POST['attraction_buy_ticket'];
			$attraction_getyourguid=$_POST['attraction_getyourguid'];
			$attraction_address=$_POST['attraction_address'];
			$attraction_contact=$_POST['attraction_contact'];
			$attraction_website=$_POST['attraction_website'];
			$attraction_public_transport=$_POST['attraction_public_transport'];
			$attraction_time_required=$_POST['attraction_time_required'];
			$attraction_wait_time=$_POST['attraction_wait_time'];
			$attraction_time_required=$_POST['attraction_time_required'];
			if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
			{
				$zipcode=$_POST['zipcode'];
			}
		}
		else if($_POST['ispaid']==0)
		{
			$attraction_admissionfee='';
			$attraction_buy_ticket=0;
			$attraction_getyourguid=0;
			$attraction_address=$_POST['attraction_address'];
			$attraction_contact=$_POST['attraction_contact'];
			$attraction_website=$_POST['attraction_website'];
			$attraction_public_transport=$_POST['attraction_public_transport'];
			$attraction_time_required=$_POST['attraction_time_required'];
			$attraction_wait_time=$_POST['attraction_wait_time'];
			$attraction_time_required=$_POST['attraction_time_required'];
			if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
			{
				$zipcode=$_POST['zipcode'];
			}
		}
		else
		{
			$attraction_address='';
			$attraction_contact='';
			$attraction_website='';
			$attraction_public_transport='';
			$attraction_admissionfee='';
			$attraction_time_required='';
			$attraction_wait_time='';
			$attraction_buy_ticket=0;
			$attraction_getyourguid=0;
			$attraction_time_required=0;
			$zipcode=0;
		}

		$known_for=implode(',',$_POST['attraction_knownfor']);
		$attraction_name=explode(',',$_POST['attraction_name']);

		$data = array(
			'attraction_name' => $attraction_name[0],
			'attraction_details' => $_POST['attraction_details'],
			'attraction_address' => $_POST['attraction_address'],
			'attraction_known_for'=>$known_for,
			'attraction_lat' => trim(substr($_POST['attraction_lat'],0,16)),
			'attraction_long' => trim(substr($_POST['attraction_long'],0,16)),
			'attraction_contact' => $_POST['attraction_contact'],
			'attraction_website' => $_POST['attraction_website'],
			'attraction_public_transport' => $_POST['attraction_public_transport'],
			'attraction_timing' => $_POST['attraction_timing'],
			'attraction_admissionfee'=>$attraction_admissionfee,
			'attraction_time_required' => $attraction_time_required,
			'attraction_wait_time' => $_POST['attraction_wait_time'],
			'attraction_buy_ticket' => $_POST['attraction_buy_ticket'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
			'ispaid'=>$_POST['ispaid'],
			'attraction_buy_ticket'=>$attraction_buy_ticket,
			'attraction_getyourguid'=>$attraction_getyourguid,
			'tag_star'=>$_POST['tag_star'],
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
			
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_paidattractions',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $attraction_name[0],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;
		
		if(isset($_FILES['image']) && $_FILES['image']['name'] != "")
		{		
			$config['upload_path'] = './userfiles/images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('image'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/city/Cityattractions/add/'.$_POST['city_id']);
			}
			else
			{
				$image = $this->upload->data();
				if ($image['file_name'])
				{
				   $data['image'] = $image['file_name'];
				}

				$config['image_library'] = 'gd2';
				$config['source_image'] = './userfiles/images/'.$data['image'];
				$config['new_image'] = './userfiles/images/small/';
				$config['maintain_ratio'] = TRUE;
				$config['overwrite'] = false;
				$config['width'] =100;
				$config['height'] = 100;//84;
				$config['master_dim'] = 'width';
				$config['file_name'] = time();
				$this->load->library('image_lib', $config); //load library
				$this->image_lib->resize(); //do whatever specified in config

			}
		}
	
	
		$this->db->insert('tbl_city_paidattractions', $data);
		if(file_exists(FCPATH.'userfiles/attractionsfiles_taxidio/'.md5($_POST['city_id'])))
		{
			unlink(FCPATH.'userfiles/attractionsfiles_taxidio/'.md5($_POST['city_id']));
		}
		$lastid=$this->db->insert_id();
		for($i=0;$i<count($_POST['attraction_knownfor']);$i++)
		{
			$logdata=array(
						'country_id'=>$citydata['country_id'],
						'city_id'=>$_POST['city_id'],
						'attraction_id'=>$lastid,
						'tag_id'=>$_POST['attraction_knownfor'][$i],
						'tag_hours'=>$attraction_time_required,
				);
			$this->db->insert('tbl_city_attraction_log',$logdata);
	
		}
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();

		$this->writeAttractionsInFile($_POST['city_id'],trim(substr($_POST['attraction_long'],0,16)),trim(substr($_POST['attraction_lat'],0,16)),$qttags);
		$this->updateCityAttractionTime($_POST['city_id'],$citydata['country_id']);
		if($zipcode!=0 && $zipcode!='')
		{
			$this->manageZipcodes($_POST['city_id'],$citydata['country_id'],$zipcode);
		}
	}

	function updateCityAttractionTime($city_id,$country_id)
	{
		$Q=$this->db->query('select sum(attraction_time_required) as total_attraction_time from tbl_city_paidattractions where city_id="'.$city_id.'"');
		$attractiontime=$Q->row_array();

		$Q1=$this->db->query('select buffer_days,total_adventure_time from tbl_city_master where id="'.$city_id.'"');
		$bufferdays=$Q1->row_array();

			
		if(isset($bufferdays['buffer_days']) && $bufferdays['buffer_days']!='')
		{
			$buffer=$bufferdays['buffer_days'];
		}
		else
		{
			$buffer=0;	
		}

		if(isset($bufferdays['total_adventure_time']) && $bufferdays['total_adventure_time']!='')
		{
			$adventure=$bufferdays['total_adventure_time'];
		}
		else
		{
			$adventure=0;	
		}

		if(isset($attractiontime['total_attraction_time']) && $attractiontime['total_attraction_time']!='')
		{
			$attraction=$attractiontime['total_attraction_time'];
		}
		else
		{
			$attraction=0;	
		}



		$total=$buffer + number_format((float)$attraction/12, 2, '.', '') + $adventure;

		$savedata=array(
				'total_attraction_time'=>number_format((float)$attraction/12, 2, '.', ''),
				'total_days'=>ceil($total)
			);

		$this->db->where('id',$city_id);
		$this->db->update('tbl_city_master',$savedata);

		$this->calculateHours($country_id);
	}

	function edit() {

		$Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$zipcode='';
        if($_POST['ispaid']==1)
		{
			$attraction_admissionfee=$_POST['attraction_admissionfee'];
			$attraction_buy_ticket=$_POST['attraction_buy_ticket'];
			$attraction_getyourguid=$_POST['attraction_getyourguid'];
			$attraction_address=$_POST['attraction_address'];
			$attraction_contact=$_POST['attraction_contact'];
			$attraction_website=$_POST['attraction_website'];
			$attraction_public_transport=$_POST['attraction_public_transport'];
			$attraction_time_required=$_POST['attraction_time_required'];
			$attraction_wait_time=$_POST['attraction_wait_time'];
			$attraction_time_required=$_POST['attraction_time_required'];
			if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
			{
				$zipcode=$_POST['zipcode'];
			}
		}
		else if($_POST['ispaid']==0)
		{
			$attraction_admissionfee='';
			$attraction_buy_ticket=0;
			$attraction_getyourguid=0;
			$attraction_address=$_POST['attraction_address'];
			$attraction_contact=$_POST['attraction_contact'];
			$attraction_website=$_POST['attraction_website'];
			$attraction_public_transport=$_POST['attraction_public_transport'];
			$attraction_time_required=$_POST['attraction_time_required'];
			$attraction_wait_time=$_POST['attraction_wait_time'];
			$attraction_time_required=$_POST['attraction_time_required'];
			if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
			{
				$zipcode=$_POST['zipcode'];
			}
		}
		else
		{
			$attraction_address='';
			$attraction_contact='';
			$attraction_website='';
			$attraction_public_transport='';
			$attraction_admissionfee='';
			$attraction_time_required='';
			$attraction_wait_time='';
			$attraction_buy_ticket=0;
			$attraction_getyourguid=0;
			$attraction_time_required=0;
			$zipcode=0;
		}

		$known_for=implode(',',$_POST['attraction_knownfor']);

		$attraction_name=explode(',',$_POST['attraction_name']);
		
		$data = array(
			'attraction_name' => $attraction_name[0],
			'attraction_details' => $_POST['attraction_details'],
			'attraction_address' => $attraction_address,
			'attraction_known_for'=>$known_for,
			'attraction_lat' => substr($_POST['attraction_lat'],0,18),
			'attraction_long' => substr($_POST['attraction_long'],0,18),
			'attraction_contact' =>$attraction_contact,
			'attraction_website' => $attraction_website,
			'attraction_public_transport' =>$attraction_public_transport,
			'attraction_timing' => $_POST['attraction_timing'],
			'attraction_admissionfee'=>$attraction_admissionfee,
			'attraction_time_required' => $attraction_time_required,
			'attraction_wait_time' => $attraction_wait_time,
			'attraction_buy_ticket' => $_POST['attraction_buy_ticket'],
			'ispaid'=>$_POST['ispaid'],
			'attraction_buy_ticket'=>$attraction_buy_ticket,
			'attraction_getyourguid'=>$attraction_getyourguid,
			'tag_star'=>$_POST['tag_star'],
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_paidattractions',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $attraction_name[0],
		);
		$slug = $this->slug->create_uri($slugdata,$this->input->post('id'));
		$data['slug'] = $slug;
		
		if(isset($_FILES['image']) && $_FILES['image']['name'] != "")
		{		
			$config['upload_path'] = './userfiles/images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('image'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/city/Cityattractions/edit/'.$_POST['id']);
			}
			else
			{
				$image = $this->upload->data();
				if ($image['file_name'])
				{
				   $data['image'] = $image['file_name'];
				}

				$config['image_library'] = 'gd2';
				$config['source_image'] = './userfiles/images/'.$data['image'];
				$config['new_image'] = './userfiles/images/small/';
				$config['maintain_ratio'] = TRUE;
				$config['overwrite'] = false;
				$config['width'] =100;
				$config['height'] = 100;//84;
				$config['master_dim'] = 'width';
				$config['file_name'] = time();
				$this->load->library('image_lib', $config); //load library
				$this->image_lib->resize(); //do whatever specified in config
				$this->removeOldFile($_POST['id']);

			}
		}
        $this->db->where('id',$_POST['id']);
		$this->db->update('tbl_city_paidattractions', $data);

		$this->db->where('attraction_id',$_POST['id']);
		$this->db->delete('tbl_city_attraction_log');
		$lastid=$_POST['id'];
		for($i=0;$i<count($_POST['attraction_knownfor']);$i++)
		{
			$logdata=array(
						'country_id'=>$citydata['country_id'],
						'city_id'=>$_POST['city_id'],
						'attraction_id'=>$lastid,
						'tag_id'=>$_POST['attraction_knownfor'][$i],
						'tag_hours'=>$attraction_time_required,
				);
			$this->db->insert('tbl_city_attraction_log',$logdata);
	
		}


		unlink(FCPATH.'userfiles/attractionsfiles_taxidio/'.md5($_POST['city_id']));
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();

		$this->writeAttractionsInFile($_POST['city_id'],trim(substr($_POST['attraction_long'],0,16)),trim(substr($_POST['attraction_lat'],0,16)),$qttags);

		$this->updateCityAttractionTime($_POST['city_id'],$_POST['country_id']);
		if(($zipcode!='' || $zipcode!=0) && $zipcode!=$_POST['oldzipcode'])
		{
			
			if(($zipcode!='' || $zipcode!=0) && $_POST['oldzipcode']=='')
			{
				$this->manageZipcodes($_POST['city_id'],$_POST['country_id'],$zipcode);
			}
			else if(($zipcode!='' || $zipcode!=0) && $_POST['oldzipcode']!='')
			{
				$this->decreseCounter($_POST['city_id'],$_POST['country_id'],$_POST['oldzipcode']);
				$this->manageZipcodes($_POST['city_id'],$_POST['country_id'],$zipcode);
			}
			
		}
		else if($_POST['oldzipcode']!='' && ($zipcode=='' || $zipcode==0))
		{
			$this->decreseCounter($_POST['city_id'],$_POST['country_id'],$_POST['oldzipcode']);
		}	
	}
	
	function removeOldFile($id)
	{
		$Q=$this->db->query('select image from tbl_city_paidattractions where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['image']!='')
		{
			unlink(FCPATH.'userfiles/images/'.$data['image']);
			unlink(FCPATH.'userfiles/images/small/'.$data['image']);
		}
	}

	function decreseCounter($city_id,$country_id,$zipcode)
	{
		$this->db->where('city_id',$city_id);
		$this->db->where('country_id',$country_id);
		$this->db->where('zipcode',$zipcode);
		$this->db->set('total', 'total-1', FALSE);
		$this->db->update('tbl_city_zipcodes');
	}


	function manageZipcodes($city_id,$country_id,$zipcode)
	{
		$qr=$this->db->query('select id,total from tbl_city_zipcodes where city_id="'.$city_id.'" and country_id="'.$country_id.'" and zipcode="'.$zipcode.'" limit 1');
		if($qr->num_rows()>0)
		{
			$getdata=$qr->row_array();
			$data=array(
					'total'=>$getdata['total']+1,
				);

			$this->db->where('id',$getdata['id']);
			$this->db->update('tbl_city_zipcodes',$data);
			//echo $this->db->last_query();die;
			
		}
		else
		{
			$data=array(
				'zipcode'=>$zipcode,
				'total'=>1,
				'city_id'=>$city_id,
				'country_id'=>$country_id
			);
			$this->db->insert('tbl_city_zipcodes',$data);
			
		}

	}
	
	function writeAttractionsInFile($city_id,$longitude,$latitude,$tags)
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
			
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fclose($file);
		}

	}

	function mandatoryTags($city_id)
	{
		$mand=array();
		$Q1=$this->db->query('select id,mandatory_dest,mandatory_lat,mandatory_long,(select mandatory_tag from tbl_mandatory_tags where id=city_mandatory_tag_master.mandatory_tag_id) as tag,(select longitude from tbl_city_master where id=city_mandatory_tag_master.city_id) as citylongitude,(select latitude from tbl_city_master where id=city_mandatory_tag_master.city_id) as citylatitude from city_mandatory_tag_master where city_id="'.$city_id.'" order by id DESC');
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
						  'category'=>0
						);
				$mand[$k]['devgeometry']['devcoordinates']=array(
						'0'=>$row['citylongitude'],
						'1'=>$row['citylatitude'],
						);
			}
		}

		return $mand;
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_city_paidattractions', array('id' => $id));
		return $Q->row_array();
	}

	function check_continent($continent_name) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_city_paidattractions', array('id !=' => $_POST['id'], 'continent_name' => $continent_name));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_continent', $continent_name . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		
		$Q=$this->db->query('select city_id,country_id,zipcode,attraction_long,attraction_lat from tbl_city_paidattractions where id="'.$id.'"');
		$data=$Q->row_array();

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();


		$this->removeOldFile($id);
		$this->db->where('id', $id);
		$this->db->delete('tbl_city_paidattractions');
		$this->db->where('attraction_id',$id);
		$this->db->delete('tbl_city_attraction_log');
		
		$this->writeAttractionsInFile($data['city_id'],$data['attraction_long'],$data['attraction_lat'],$qttags);
		

		$this->updateCityAttractionTime($data['city_id'],$data['country_id']);
		
		$this->db->where('country_id',$data['country_id']);
		$this->db->where('city_id',$data['city_id']);
		$this->db->where('zipcode',$data['zipcode']);
		$this->db->set('total', 'total-1', FALSE);
		$this->db->update('tbl_city_zipcodes');


		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();

	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function check_attraction_add($attraction_name)
	{
		$Q=$this->db->query('select id from tbl_city_paidattractions where attraction_name="'.$attraction_name.'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_attraction_add','This Attraction already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function check_attraction_edit($attraction_name)
	{
		$Q=$this->db->query('select id from tbl_city_paidattractions where attraction_name="'.$attraction_name.'" and city_id="'.$_POST['city_id'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_attraction_edit','That Attraction already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function getDefaultTag()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=3');
		return $Q->row_array(); 
	}

	function checkTagStarValue($city_id)
	{
		$Q=$this->db->query('select id from tbl_city_paidattractions where city_id="'.$city_id.'" and tag_star=2');
		return $Q->num_rows();
	}

	function check_two()
	{
		$Q=$this->db->query('select attraction_name from tbl_city_paidattractions where tag_star=2 and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$dt=$Q->row_array();
			$this->form_validation->set_message('check_two','Tag Star 2 already exists for '.$dt['attraction_name']);
			return FALSE;
		}
		return TRUE;
	}

	function check_two_edit()
	{
		$Q=$this->db->query('select attraction_name from tbl_city_paidattractions where tag_star=2 and city_id="'.$_POST['city_id'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$dt=$Q->row_array();
			$this->form_validation->set_message('check_two_edit','Tag Star 2 already exists for '.$dt['attraction_name']);
			return FALSE;
		}
		return TRUE;
	}

	function getCountryId($city_id)
	{
		$Q=$this->db->query('select country_id from tbl_city_master where id="'.$city_id.'"');
		return $Q->row_array();
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


}

/* End of file  */
/* Location: ./application/models/ */
