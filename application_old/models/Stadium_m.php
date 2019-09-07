<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Stadium_m extends CI_Model {

	function add() {

        $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$known_for=implode(',',$_POST['knownfor']);
		$stadium_name=explode(',',$_POST['stadium_name']);
		$zipcode='';
	  if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
	  {
		$zipcode=$_POST['zipcode'];
	  }
		$data = array(
			'stadium_name' => $stadium_name[0],
			'stadium_description' => $_POST['stadium_description'],
			'stadium_address' => $_POST['stadium_address'],
			'stadium_lat' => substr($_POST['stadium_lat'],0,20),
			'stadium_long' => substr($_POST['stadium_long'],0,20),
			'stadium_contact' => $_POST['stadium_contact'],
			'stadium_website' => $_POST['stadium_website'],
			'stadium_timing' => $_POST['stadium_timing'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
			'stadium_get_your_guide'=>$_POST['stadium_get_your_guide'],
			'tag_star'=>$_POST['tag_star'],
			'known_for'=>$known_for,
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_stadiums',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $stadium_name[0],
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
				redirect('admins/city/Stadiums/add/'.$_POST['city_id']);
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
		
		$this->db->insert('tbl_city_stadiums', $data);
		
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['stadium_long'],0,16),substr($_POST['stadium_lat'],0,16),$qttags);
		
	}

	function edit() {

	 $known_for=implode(',',$_POST['knownfor']);	
     $stadium_name=explode(',',$_POST['stadium_name']);
     $zipcode='';
	  if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
	  {
		$zipcode=$_POST['zipcode'];
	  }
	 $data = array(
			'stadium_name' => $stadium_name[0],
			'stadium_description' => $_POST['stadium_description'],
			'stadium_address' => $_POST['stadium_address'],
			'stadium_lat' => substr($_POST['stadium_lat'],0,20),
			'stadium_long' => substr($_POST['stadium_long'],0,20),
			'stadium_contact' => $_POST['stadium_contact'],
			'stadium_website' => $_POST['stadium_website'],
			'stadium_timing' => $_POST['stadium_timing'],
			'stadium_get_your_guide'=>$_POST['stadium_get_your_guide'],
			'tag_star'=>$_POST['tag_star'],
			'known_for'=>$known_for,
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_stadiums',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $stadium_name[0],
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
				redirect('admins/city/Stadiums/edit/'.$_POST['id']);
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
		$this->db->update('tbl_city_stadiums', $data);

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['stadium_long'],0,16),substr($_POST['stadium_lat'],0,16),$qttags);

	}

	function removeOldFile($id)
	{
		$Q=$this->db->query('select image from tbl_city_stadiums where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['image']!='')
		{
			unlink(FCPATH.'userfiles/images/'.$data['image']);
			unlink(FCPATH.'userfiles/images/small/'.$data['image']);
		}
	}
	
	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_city_stadiums', array('id' => $id));
		return $Q->row_array();
	}

	function delete($id) {
		$this->removeOldFile($id);
		

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();

		$Q=$this->db->query('select city_id,country_id,stadium_long,stadium_lat from tbl_city_stadiums where id="'.$id.'"');
		$data=$Q->row_array();

		$this->db->where('id', $id);
		$this->db->delete('tbl_city_stadiums');

		$this->writeAttractionsInFile($data['city_id'],substr($data['stadium_long'],0,16),substr($data['stadium_lat'],0,16),$qttags);
	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function check_stadium_add($stadium_name)
	{
		$Q=$this->db->query('select id from tbl_city_stadiums where stadium_name="'.$stadium_name.'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_stadium_add','This Stadium already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function check_stadium_edit($stadium_name)
	{
		$Q=$this->db->query('select id from tbl_city_stadiums where stadium_name="'.$stadium_name.'" and city_id="'.$_POST['city_id'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_stadium_edit','That Stadium already exists for this city.');
			return FALSE;
		}
		return TRUE;
	}

	function getDefaultTag()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=1');
		return $Q->row_array(); 
	}

	function writeAttractionsInFile($city_id,$longitude,$latitude,$tags)
	{
		$Q=$this->db->query('select id,stadium_name as attraction_name,stadium_lat as attraction_lat,stadium_long as attraction_long,stadium_description as attraction_details,stadium_address as attraction_address,stadium_contact as attraction_contact,tag_star,stadium_get_your_guide as attraction_getyourguid,known_for as attraction_known_for from tbl_city_stadiums where city_id="'.$city_id.'"');

		
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{  
				$isplace=1;
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
			$file=fopen(FCPATH.'userfiles/stadium/'.$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);
		}
		else
		{
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/stadium/'.$randomstring,'w');
			fclose($file);
		}	
			
	}


}

/* End of file  */
/* Location: ./application/models/ */
