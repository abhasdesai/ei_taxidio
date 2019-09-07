<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Restaurant_m extends CI_Model {

	function add() {

        $Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$known_for=implode(',',$_POST['knownfor']);
		$ran_name=explode(',',$_POST['ran_name']);
		$zipcode='';
		if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
		{
			$zipcode=$_POST['zipcode'];
		}
		$data = array(
			'ran_name' => $ran_name[0],
			'ran_description' => $_POST['ran_description'],
			'ran_address' => $_POST['ran_address'],
			'ran_lat' => trim(substr($_POST['ran_lat'],0,16)),
			'ran_long' => trim(substr($_POST['ran_long'],0,16)),
			'ran_contact' => $_POST['ran_contact'],
			'ran_website' => $_POST['ran_website'],
			'ran_timing' => $_POST['ran_timing'],
			'city_id' => $_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id'],
			'ran_display'=>$_POST['ran_display'],
			'tag_star'=>$_POST['tag_star'],
			'known_for'=>$known_for,
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_restaurants',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $ran_name[0],
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
				redirect('admins/city/Restaurants/add/'.$_POST['city_id']);
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
		$this->db->insert('tbl_city_restaurants', $data);
		
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['ran_long'],0,14),substr($_POST['ran_lat'],0,14),$qttags);
		if($zipcode!=0 && $zipcode!='')
		{
			$this->manageZipcodes($_POST['city_id'],$citydata['country_id'],$zipcode);
		}

	}

	
	function getMonthWiseRestaurantRate($id,$hotel_id)
	{
		$Q=$this->db->query('select cost from city_hotel_cost_master where hotel_id="'.$hotel_id.'" and month_id="'.$id.'"');
		return $Q->row_array();
	}

	function edit() {

	  $known_for=implode(',',$_POST['knownfor']);	
	  $ran_name=explode(',',$_POST['ran_name']);
	  $zipcode='';
	  if(isset($_POST['zipcode']) && $_POST['zipcode']!='')
	  {
		$zipcode=$_POST['zipcode'];
	  }
		$data = array(
			'ran_name' => $ran_name[0],
			'ran_description' => $_POST['ran_description'],
			'ran_address' => $_POST['ran_address'],
			'known_for'=>$known_for,
			'ran_lat' => trim(substr($_POST['ran_lat'],0,16)),
			'ran_long' => trim(substr($_POST['ran_long'],0,16)),
			'ran_contact' => $_POST['ran_contact'],
			'ran_website' => $_POST['ran_website'],
			'ran_timing' => $_POST['ran_timing'],
			'ran_display'=>$_POST['ran_display'],
			'tag_star'=>$_POST['tag_star'],
			'zipcode'=>$zipcode,
			'credit'=>$_POST['credit']
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_restaurants',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $ran_name[0],
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
				redirect('admins/city/Restaurants/edit/'.$_POST['id']);
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
		$this->db->update('tbl_city_restaurants', $data);
		
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],trim(substr($_POST['ran_long'],0,16)),trim(substr($_POST['ran_lat'],0,16)),$qttags);
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
		$Q=$this->db->query('select image from tbl_city_restaurants where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['image']!='')
		{
			unlink(FCPATH.'userfiles/images/'.$data['image']);
			unlink(FCPATH.'userfiles/images/small/'.$data['image']);
		}
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_city_restaurants', array('id' => $id));
		return $Q->row_array();
	}

	function check_continent($continent_name) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_city_restaurants', array('id !=' => $_POST['id'], 'continent_name' => $continent_name));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_continent', $continent_name . ' Continent already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {
		$this->removeOldFile($id);

		$Q=$this->db->query('select city_id,country_id,zipcode,ran_lat,ran_long from  tbl_city_restaurants where id="'.$id.'"');
		$data=$Q->row_array();
		
		$this->db->where('id', $id);
		$this->db->delete('tbl_city_restaurants');
		
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($data['city_id'],$data['ran_long'],$data['ran_lat'],$qttags);

		$this->db->where('country_id',$data['country_id']);
		$this->db->where('city_id',$data['city_id']);
		$this->db->where('zipcode',$data['zipcode']);
		$this->db->set('total', 'total-1', FALSE);
		$this->db->update('tbl_city_zipcodes');

	}

	function writeAttractionsInFile($city_id,$longitude,$latitude,$tags)
	{
		$Q=$this->db->query('select id,ran_name as attraction_name,ran_lat as attraction_lat,ran_long as attraction_long,ran_description as attraction_details,ran_address as attraction_address,ran_contact as attraction_contact,known_for as attraction_known_for,tag_star from tbl_city_restaurants where city_id="'.$city_id.'"');

		
		if($Q->num_rows()>0)
		{
			$c=0;
			foreach($Q->result_array() as $key=>$row)
			{  
				$isplace=1;
				
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
						  'getyourguide'=>0,
						  'attractionid'=>md5($row['id']),
						  'cityid'=>md5($city_id),
						  'isplace'=>$isplace,
						  'ispaid'=>0,
						  'category'=>3
						);
				$data[$key]['devgeometry']['devcoordinates']=array(
						'0'=>$longitude,
						'1'=>$latitude,
						);

			}

			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/restaurant/'.$randomstring,'w');
			fwrite($file,json_encode($data));
			fclose($file);
		}
		else
		{
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/restaurant/'.$randomstring,'w');
			fclose($file);
		}
		

	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function getAllMonths()
	{
		$data=array();
		$Q=$this->db->query('select id,month_name from tbl_month_master order by id asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}	
		return $data;
	}

	function getDefaultTag()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=2');
		return $Q->row_array(); 
	}
}

/* End of file  */
/* Location: ./application/models/ */
