<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adventures_m extends CI_Model {

	
	function add() {

        $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$known_for=implode(',',$_POST['knownfor']);
		$adventure_name=explode(',',$_POST['adventure_name']);
		 $zipcode='';
		  if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
		  {
			$zipcode=$_POST['zipcode'];
		  }
		$data = array(
			'adventure_name' => $adventure_name[0],
			'adventure_details' => $_POST['adventure_details'],
			'adventure_address' => $_POST['adventure_address'],
			'adventure_lat' => trim(substr($_POST['adventure_lat'],0,20)),
			'adventure_long' => trim(substr($_POST['adventure_long'],0,20)),
			'adventure_contact' => $_POST['adventure_contact'],
			'adventure_website' => $_POST['adventure_website'],
			'adventure_nearest_public_transport' => $_POST['adventure_nearest_public_transport'],
			'adventure_open_close_timing' => $_POST['adventure_open_close_timing'],
			'adventure_admissionfee'=>$_POST['adventure_admissionfee'],
			'adventure_time_required' => $_POST['adventure_time_required'],
			'adventure_wait_time' => $_POST['adventure_wait_time'],
			'adventure_buy_ticket' => $_POST['adventure_buy_ticket'],
			'adventure_getyourguid' => $_POST['adventure_getyourguid'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
			'adventure_buy_ticket'=>$_POST['adventure_buy_ticket'],
			'adventure_getyourguid'=>$_POST['adventure_getyourguid'],
			'tag_star'=>$_POST['tag_star'],
			'adventure_known_for'=>$known_for,
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_sports_adventures',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $adventure_name[0],
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
				redirect('admins/city/Adventures/add/'.$_POST['city_id']);
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
	
		$this->db->insert('tbl_city_sports_adventures', $data);
		$this->updateCityAttractionTime($_POST['city_id'],$_POST['country_id']);

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['adventure_long'],0,12),substr($_POST['adventure_lat'],0,12),$qttags);
		if($zipcode!=0 && $zipcode!='')
		{
			$this->manageZipcodes($_POST['city_id'],$citydata['country_id'],$zipcode);
		}

	}

	function edit() {

		 $known_for=implode(',',$_POST['knownfor']);
		 $adventure_name=explode(',',$_POST['adventure_name']);
		  $zipcode='';
	  if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
	  {
		$zipcode=$_POST['zipcode'];
	  }
		 $data = array(
			'adventure_name' => $adventure_name[0],
			'adventure_details' => $_POST['adventure_details'],
			'adventure_address' => $_POST['adventure_address'],
			'adventure_lat' => trim(substr($_POST['adventure_lat'],0,16)),
			'adventure_long' => trim(substr($_POST['adventure_long'],0,16)),
			'adventure_contact' => $_POST['adventure_contact'],
			'adventure_website' => $_POST['adventure_website'],
			'adventure_nearest_public_transport' => $_POST['adventure_nearest_public_transport'],
			'adventure_open_close_timing' => $_POST['adventure_open_close_timing'],
			'adventure_admissionfee'=>$_POST['adventure_admissionfee'],
			'adventure_time_required' => $_POST['adventure_time_required'],
			'adventure_wait_time' => $_POST['adventure_wait_time'],
			'adventure_buy_ticket' => $_POST['adventure_buy_ticket'],
			'adventure_getyourguid' => $_POST['adventure_getyourguid'],
			'adventure_buy_ticket'=>$_POST['adventure_buy_ticket'],
			'adventure_getyourguid'=>$_POST['adventure_getyourguid'],
			'tag_star'=>$_POST['tag_star'],
			'adventure_known_for'=>$known_for,
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_sports_adventures',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $adventure_name[0],
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
				redirect('admins/city/Adventures/edit/'.$_POST['id']);
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
		$this->db->update('tbl_city_sports_adventures', $data);
		$this->updateCityAttractionTime($_POST['city_id'],$_POST['country_id']);

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],trim(substr($_POST['adventure_long'],0,16)),trim(substr($_POST['adventure_lat'],0,16)),$qttags);

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

	function decreseCounter($city_id,$country_id,$zipcode)
	{
		$this->db->where('city_id',$city_id);
		$this->db->where('country_id',$country_id);
		$this->db->where('zipcode',$zipcode);
		$this->db->set('total', 'total-1', FALSE);
		$this->db->update('tbl_city_zipcodes');
	}


	function removeOldFile($id)
	{
		$Q=$this->db->query('select image from tbl_city_sports_adventures where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['image']!='')
		{
			unlink(FCPATH.'userfiles/images/'.$data['image']);
			unlink(FCPATH.'userfiles/images/small/'.$data['image']);
		}
	}


	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_city_sports_adventures', array('id' => $id));
		return $Q->row_array();
	}

	
	function check_continent($continent_name) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_city_sports_adventures', array('id !=' => $_POST['id'], 'continent_name' => $continent_name));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_continent', $continent_name . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$Q=$this->db->query('select city_id,country_id,adventure_long,adventure_lat,zipcode from tbl_city_sports_adventures where id="'.$id.'"');
		$data=$Q->row_array();
		$this->removeOldFile($id);
		$this->db->where('id', $id);
		$this->db->delete('tbl_city_sports_adventures');
		$this->updateCityAttractionTime($data['city_id'],$data['country_id']);

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($data['city_id'],$data['adventure_long'],$data['adventure_lat'],$qttags);

		$this->db->where('country_id',$data['country_id']);
		$this->db->where('city_id',$data['city_id']);
		$this->db->where('zipcode',$data['zipcode']);
		$this->db->set('total', 'total-1', FALSE);
		$this->db->update('tbl_city_zipcodes');


	}

	function writeAttractionsInFile($city_id,$longitude,$latitude,$tags)
	{
		$Q=$this->db->query('select id,adventure_name as attraction_name,adventure_lat as attraction_lat,adventure_long as attraction_long,adventure_details as attraction_details,adventure_address as attraction_address,adventure_contact as attraction_contact,adventure_known_for as attraction_known_for,tag_star,adventure_getyourguid as attraction_getyourguid,adventure_buy_ticket as ispaid from tbl_city_sports_adventures where city_id="'.$city_id.'"');

		
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{  
				$isplace=1;
				$ispaid=0;
				if($row['ispaid']==1)
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
						  'getyourguide'=>$row['attraction_getyourguid'],
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
			$file=fopen(FCPATH.'userfiles/sport/'.$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);
		}
		else
		{
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/sport/'.$randomstring,'w');
			fclose($file);
		}		

	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function check_adventure_add($adventure_name)
	{
		$Q=$this->db->query('select id from tbl_city_sports_adventures where adventure_name="'.$adventure_name.'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_adventure_add','This Sports & Adventure already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function check_adventure_edit($adventure_name)
	{
		$Q=$this->db->query('select id from tbl_city_sports_adventures where adventure_name="'.$adventure_name.'" and city_id="'.$_POST['city_id'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_adventure_edit','That Sports & Adventure already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function getDefaultTag()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=5');
		return $Q->row_array(); 
	}

	function updateCityAttractionTime($city_id,$country_id)
	{
		$Qa=$this->db->query('select sum(adventure_time_required) as total_adventure_time_required from tbl_city_sports_adventures where city_id="'.$city_id.'"');
		$adventuretime=$Qa->row_array();
		
		$Q1=$this->db->query('select buffer_days,total_attraction_time from tbl_city_master where id="'.$city_id.'"');
		$bufferdays=$Q1->row_array();

			
		if(isset($bufferdays['buffer_days']) && $bufferdays['buffer_days']!='')
		{
			$buffer=$bufferdays['buffer_days'];
		}
		else
		{
			$buffer=0;	
		}

		if(isset($bufferdays['total_attraction_time']) && $bufferdays['total_attraction_time']!='')
		{
			$attraction=$bufferdays['total_attraction_time'];
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
		//echo $adventure;die;
		$total=$buffer + number_format((float)$adventure/12, 2, '.', '')+$attraction;

		$savedata=array(
				'total_adventure_time'=>number_format((float)$adventure/12, 2, '.', ''),
				'total_days'=>ceil($total)
			);

		$this->db->where('id',$city_id);
		$this->db->update('tbl_city_master',$savedata);

		$this->calculateHours($country_id);
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

/* End of file Adventures_m.php */
/* Location: ./application/models/Adventures_m.php */
