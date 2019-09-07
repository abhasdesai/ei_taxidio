<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mandatorytag_m extends CI_Model {

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function add()
	{
		$Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();
		$mandatory_dest=explode(',',$_POST['mandatory_dest']);

		$data=array(
				'mandatory_tag_id'=>$_POST['mandatory_tag_id'],
				'mandatory_dest'=>$mandatory_dest[0],
				'mandatory_lat'=>substr($_POST['mandatory_lat'],0,20),
				'mandatory_long'=>substr($_POST['mandatory_long'],0,20),
				'city_id' => $_POST['city_id'],
				'country_id'=>$citydata['country_id'],
				'continent_id'=>$citydata['continent_id'],
			);

		$this->db->insert('city_mandatory_tag_master',$data);
		
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['mandatory_long'],0,18),substr($_POST['mandatory_lat'],0,18),$qttags);

	}

	function edit()
	{
		$mandatory_dest=explode(',',$_POST['mandatory_dest']);
		$data=array(
				'mandatory_tag_id'=>$_POST['mandatory_tag_id'],
				'mandatory_dest'=>$mandatory_dest[0],
				'mandatory_lat'=>substr($_POST['mandatory_lat'],0,20),
				'mandatory_long'=>substr($_POST['mandatory_long'],0,20),
			);


		$this->db->where('id',$_POST['id']);
		$this->db->update('city_mandatory_tag_master',$data);
		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();
		$this->writeAttractionsInFile($_POST['city_id'],substr($_POST['mandatory_long'],0,18),substr($_POST['mandatory_lat'],0,18),$qttags);
	}


	function writeAttractionsInFile($city_id,$longitude,$latitude,$tags)
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

			/*$mandatorydata=$this->mandatoryTags($city_id);
			$randomstring=md5($city_id);
			$file=fopen(FCPATH.'userfiles/attractionsfiles_taxidio/'.$randomstring,'w');
			fwrite($file,json_encode($mandatorydata));
			fclose($file);*/
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
						  'attractionid'=>0,
						  'cityid'=>md5($city_id),
						  'isplace'=>0,
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



	function delete($id)
	{
		$Q=$this->db->query('select mandatory_lat,mandatory_long,city_id from city_mandatory_tag_master where id="'.$id.'"');
		$data=$Q->row_array();

		$Qt=$this->db->query('select id,tag_name from tbl_tag_master');
		$qttags=$Qt->result_array();

		$this->db->where('id',$id);
		$this->db->delete('city_mandatory_tag_master');

		$this->writeAttractionsInFile($data['city_id'],$data['mandatory_long'],$data['mandatory_lat'],$qttags);
	}

	function getDetailsById($id)
	{
		$Q=$this->db->query('select * from city_mandatory_tag_master where id="'.$id.'"');
		return $Q->row_array(); 
	}

	function getMandatoryTagsFromMaster()
	{
		$data=array();
		$Q=$this->db->query('select id,mandatory_tag from tbl_mandatory_tags order by mandatory_tag asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{	
				$data[]=$row;
			}
		}
		return $data;
	}

	function getOptions()
	{
		$data=array();
		$Q=$this->db->query('select id,tag_type from tbl_tag_options');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{	
				$data[]=$row;
			}
		}
		return $data;
	}

	function check_combination_add($mandatory_tag_id)
	{
		$Q=$this->db->query('select id from city_mandatory_tag_master where mandatory_tag_id="'.$_POST['mandatory_tag_id'].'" and mandatory_dest="'.$_POST['mandatory_dest'].'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_combination_add','This tag name and tag dest combination is already exists.');
			return FALSE;
		}
		return TRUE;
	}

	function check_combination_edit($mandatory_tag_id)
	{
		$Q=$this->db->query('select id from city_mandatory_tag_master where mandatory_tag_id="'.$_POST['mandatory_tag_id'].'" and mandatory_dest="'.$_POST['mandatory_dest'].'" and id!="'.$_POST['id'].'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_combination_edit','This tag name, tag option and tag dest combination is already exists.');
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file Mandatorytag_m.php */
/* Location: ./application/models/Mandatorytag_m.php */