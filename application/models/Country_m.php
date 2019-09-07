<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Country_m extends CI_Model {

	function add() {
		$country_name=explode(',',$_POST['country_name']);
		$countrycode=strtoupper($country_name[0]);
		if(isset($_POST['countrycode']) && $_POST['countrycode']!='')
		{
			$countrycode=strtoupper($_POST['countrycode']);
		}
		$data = array(
			'country_name' => $country_name[0],
			'rome2rio_name'=>$_POST['country_name'],
			'rome2rio_code'=>$_POST['rome2rio_code'],
			'continent_id' => 0,
			'timezone' => $_POST['timezone'],
			'latitude' => $_POST['latitude'],
			'longitude' => $_POST['longitude'],
			'country_capital' => $_POST['country_capital'],
			'country_neighbours' => $_POST['country_neighbours'],
			'country_conclusion' => $_POST['country_conclusion'],
			'country_currency' => $_POST['country_currency'],
			'country_economic' => $_POST['country_economic'],
			'country_history' => $_POST['country_history'],
			'country_cultural_identity' => $_POST['country_cultural_identity'],
			'country_national_carrier' => $_POST['country_national_carrier'],
			'country_tourist_rating' => $_POST['country_tourist_rating'],
			'country_no_of_annual_tourists' => $_POST['country_no_of_annual_tourists'],
			'country_natural_cultural_resources' => $_POST['country_natural_cultural_resources'],
			'country_best_season_visit' => $_POST['country_best_season_visit'],
			'countrycode'=>$countrycode
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_country_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $country_name[0],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$flg=0;
		if(isset($_FILES['countryimage']) && $_FILES['countryimage']['name'] != "")
		{	
			$flg=1;
			$nm='small'.time();	
			$config['upload_path'] = './userfiles/countries/small/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = $nm;
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('countryimage'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/countries/add/');
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];

				if($imgwidth==450 && $imgheight==254)
				{
					if ($image['file_name'])
					{
					   $data['countryimage'] = $image['file_name'];
					}
					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/countries/small/'.$data['countryimage'];
					$config['new_image'] = './userfiles/countries/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config
				}	
				else
				{
					unlink(FCPATH.'userfiles/countries/small/'.$nm);
					$this->session->set_flashdata('error','Image Size must be 450px X 254px.');
					redirect('admins/countries/add/');
				}

			}
		}

		if(isset($_FILES['countrybanner']) && $_FILES['countrybanner']['name'] != "")
		{		
			$nm='banner'.time();
			$config['upload_path'] = './userfiles/countries/banner/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = $nm;
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('countrybanner'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error',($error[warning]));
				redirect('admins/countries/add/');
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];
			    if($imgwidth==1920 && $imgheight==350)
				{

					if ($image['file_name'])
					{
					   $data['countrybanner'] = $image['file_name'];
					}

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/countries/banner/'.$data['countrybanner'];
					$config['new_image'] = './userfiles/countries/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					if($flg==1)
					{
						$this->image_lib->clear();
				        $this->image_lib->initialize($config);	
					}
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config
				}	
				else
				{
					unlink(FCPATH.'userfiles/countries/banner/'.$nm);
					$this->session->set_flashdata('error','Image Size must be 1920px X 350px.');
					redirect('admins/countries/add/');
				}

			}
		}

        $this->db->insert('tbl_country_master', $data);
		$lastid=$this->db->insert_id();

		if(isset($_POST['name']) && $_POST['name']!='')
		{
			$expname=explode(',',$_POST['name']);
			for($i=0;$i<count($expname);$i++)
			{
				$alternativedata=array(
						'name'=>$expname[$i],
						'country_id'=>$lastid
					);
				$this->db->insert('tbl_country_alternative_names',$alternativedata);
			}
		}

		for($i=0;$i<count($_POST['tag_id']);$i++)
		{
			$tagdata=array(
					'country_id'=>$lastid,
					'tag_id'=>$_POST['tag_id'][$i]
				);
			$this->db->insert('tbl_country_tags',$tagdata);
		}

		for($i=0;$i<count($_POST['continent_id']);$i++)
		{
			$con_cou=array(
					'country_id'=>$lastid,
					'continent_id'=>$_POST['continent_id'][$i]
				);
			$this->db->insert('tbl_continent_countries',$con_cou);
		}


	}

	function edit() {

		$country_name=explode(',',$_POST['country_name']);
		$countrycode=strtoupper($country_name[0]);
		if(isset($_POST['countrycode']) && $_POST['countrycode']!='')
		{
			$countrycode=strtoupper($_POST['countrycode']);
		}

		$data = array(
			'country_name' => $country_name[0],
			'rome2rio_name'=>$_POST['country_name'],
			'rome2rio_code'=>$_POST['rome2rio_code'],
			'continent_id' => 0,
			'timezone' => $_POST['timezone'],
			'latitude' => $_POST['latitude'],
			'longitude' => $_POST['longitude'],
			'country_capital' => $_POST['country_capital'],
			'country_neighbours' => $_POST['country_neighbours'],
			'country_conclusion' => $_POST['country_conclusion'],
			'country_currency' => $_POST['country_currency'],
			'country_economic' => $_POST['country_economic'],
			'country_history' => $_POST['country_history'],
			'country_cultural_identity' => $_POST['country_cultural_identity'],
			'country_national_carrier' => $_POST['country_national_carrier'],
			'country_tourist_rating' => $_POST['country_tourist_rating'],
			'country_no_of_annual_tourists' => $_POST['country_no_of_annual_tourists'],
			'country_natural_cultural_resources' => $_POST['country_natural_cultural_resources'],
			'country_best_season_visit' => $_POST['country_best_season_visit'],
			'countrycode'=>$countrycode
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_country_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $country_name[0],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;
		$flg=0;
		if(isset($_FILES['countryimage']) && $_FILES['countryimage']['name'] != "")
		{	
			$flg=1;
		    $nm='small'.time();	
			$config['upload_path'] = './userfiles/countries/small/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = $nm;
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('countryimage'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/countries/edit/'.$_POST['id']);
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];

				if($imgwidth==450 && $imgheight==254)
				{

					if ($image['file_name'])
					{
					   $data['countryimage'] = $image['file_name'];
					}

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/countries/small/'.$data['countryimage'];
					$config['new_image'] = './userfiles/countries/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config
					$this->removeImage($_POST['id']);
				}	
				else
				{
					unlink(FCPATH.'userfiles/countries/small/'.$nm);
					$this->session->set_flashdata('error','Image Size must be 450px X 254px.');
					redirect('admins/countries/edit/'.$_POST['id']);
				}
			}
		}

		if(isset($_FILES['countrybanner']) && $_FILES['countrybanner']['name'] != "")
		{	
		    $nm='banner'.time();	
			$config['upload_path'] = './userfiles/countries/banner/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = $nm;
			$this->load->library('upload');
			$this->upload->initialize($config); 	
						
			if (!$this->upload->do_upload('countrybanner'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/countries/edit/'.$_POST['id']);
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];
			    if($imgwidth==1920 && $imgheight==350)
				{

					if ($image['file_name'])
					{
					   $data['countrybanner'] = $image['file_name'];
					}

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/countries/banner/'.$data['countrybanner'];
					$config['new_image'] = './userfiles/countries/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					if($flg==1)
					{
						$this->image_lib->clear();
				        $this->image_lib->initialize($config);	
					}
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config
					$this->removeBanner($_POST['id']);
			}	
			else
			{
				unlink(FCPATH.'userfiles/countries/banner/'.$nm);
				$this->session->set_flashdata('error','Image Size must be 1920px X 350px.');
				redirect('admins/countries/edit/'.$_POST['id']);
			}

			}
		}


		$lastid=$this->input->post('id');

		$this->db->where('id', $lastid);
		$this->db->update('tbl_country_master', $data);

		$this->db->where('country_id', $lastid);
		$this->db->delete('tbl_country_tags');

		$this->db->where('country_id', $lastid);
		$this->db->delete('tbl_country_top_attractions');

		$this->db->where('country_id', $lastid);
		$this->db->delete('tbl_continent_countries');

		


		if(isset($_POST['name']) && $_POST['name']!='')
		{
			$this->db->where('country_id',$lastid);
			$this->db->delete('tbl_country_alternative_names');

			$expname=explode(',',$_POST['name']);
			for($i=0;$i<count($expname);$i++)
			{
				$alternativedata=array(
						'name'=>$expname[$i],
						'country_id'=>$lastid
					);
				$this->db->insert('tbl_country_alternative_names',$alternativedata);
			}
		}
		else
		{
			$Q=$this->db->query('select id from tbl_country_alternative_names where country_id="'.$lastid.'"');
			if($Q->num_rows()>0)
			{
				$this->db->where('country_id',$lastid);
			    $this->db->delete('tbl_country_alternative_names');
			}

		}

		for($i=0;$i<count($_POST['attraction_id']);$i++)
		{
			list($attraction_category, $attraction_id)=explode('-', $_POST['attraction_id'][$i]);
			$attractiondata=array(
					'country_id'=>$lastid,
					'attraction_category'=>$attraction_category,
					'attraction_id'=>$attraction_id
				);
			$this->db->insert('tbl_country_top_attractions',$attractiondata);
		}

		for($i=0;$i<count($_POST['tag_id']);$i++)
		{
			$tagdata=array(
					'country_id'=>$lastid,
					'tag_id'=>$_POST['tag_id'][$i]
				);
			$this->db->insert('tbl_country_tags',$tagdata);
		}

		for($i=0;$i<count($_POST['continent_id']);$i++)
		{
			$con_cou=array(
					'country_id'=>$lastid,
					'continent_id'=>$_POST['continent_id'][$i]
				);
			$this->db->insert('tbl_continent_countries',$con_cou);
		}
	}

	function getAllSelectedContinents($id)
	{
		$data=array();
		$Q=$this->db->query('select continent_id from tbl_continent_countries where country_id="'.$id.'"');
		$data=array_column($Q->result_array(),'continent_id');
		return $data;
	}

	function removeImage($id)
	{
		$Q=$this->db->query('select countryimage from tbl_country_master where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['countryimage']!='')
		{
			if(file_exists(FCPATH.'userfiles/countries/small/'.$data['countryimage']))
			{
				unlink(FCPATH.'userfiles/countries/small/'.$data['countryimage']);
			}
			if(file_exists(FCPATH.'userfiles/countries/tiny/'.$data['countryimage']))
			{
				unlink(FCPATH.'userfiles/countries/tiny/'.$data['countryimage']);
			}
			
		}
	}

	function removeBanner($id)
	{
		$Q=$this->db->query('select countrybanner from tbl_country_master where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['countrybanner']!='')
		{
			if(file_exists(FCPATH.'userfiles/countries/banner/'.$data['countrybanner']))
			{
				unlink(FCPATH.'userfiles/countries/banner/'.$data['countrybanner']);
			}
			if(file_exists(FCPATH.'userfiles/countries/tiny/'.$data['countrybanner']))
			{
				unlink(FCPATH.'userfiles/countries/tiny/'.$data['countrybanner']);
			}
			
		}
	}

	
	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_country_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_country() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_country_master', array('id !=' => $_POST['id'], 'country_name' => $_POST['country_name']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_country', $_POST['country_name'] . ' country already exists');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id) {

		$Q=$this->db->query('select id from tbl_city_master where country_id="'.$id.'"');
		if($Q->num_rows()>0)
		{
			$this->session->set_flashdata('error','You can not delete this Country.This Country is in use.');
			redirect('admins/Countries');
		}
		else
		{
			$this->removeBanner($id);
			$this->removeImage($id);
			$this->db->where('id', $id);
			$this->db->delete('tbl_country_master');

			$this->db->where('country_id', $id);
			$this->db->delete('tbl_country_tags');

			$this->db->where('country_id',$id);
		    $this->db->delete('tbl_country_alternative_names');

		    $this->db->where('country_id',$id);
		    $this->db->delete('tbl_continent_countries');
		}	
	}

	function getAllContinents() {
		$data = array();
		$Q = $this->db->query('select * from tbl_continent_master order by continent_name asc');
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function getAllAttractionbycountry($country_id) {
		$data = array();

		$i=0;
		$this->db->select("CONCAT('paidattractions-', id) as id,attraction_name");
		$this->db->from('tbl_city_paidattractions');
		$this->db->where('country_id',$country_id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[$i] = $row;
				$i++;
			}
		}

		$this->db->select("CONCAT('relaxationspa-', id) as id,ras_name as attraction_name");
		$this->db->from('tbl_city_relaxationspa');
		$this->db->where('country_id',$country_id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[$i] = $row;
				$i++;
			}
		}

		$this->db->select("CONCAT('restaurants-', id) as id,ran_name as attraction_name");
		$this->db->from('tbl_city_restaurants');
		$this->db->where('country_id',$country_id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[$i] = $row;
				$i++;
			}
		}

		$this->db->select("CONCAT('stadiums-', id) as id,stadium_name as attraction_name");
		$this->db->from('tbl_city_stadiums');
		$this->db->where('country_id',$country_id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[$i] = $row;
				$i++;
			}
		}

		$this->db->select("CONCAT('sports_adventures-', id) as id,adventure_name as attraction_name");
		$this->db->from('tbl_city_sports_adventures');
		$this->db->where('country_id',$country_id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row) {
				$data[$i] = $row;
				$i++;
			}
		}

		return $data;
	}


	function check_combination_add($country_name)
	{
		$Q=$this->db->query('select id from tbl_country_master where country_name="'.$country_name.'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_combination_add','This Continent and Country Combination already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function getTags()
	{
		$data=array();
		$Q=$this->db->query('select id,tag_name from tbl_tag_master order by tag_name asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAllCountryTags($country_id)
	{
		$data=array();
		$Q=$this->db->query('select tag_id from tbl_country_tags where country_id="'.$country_id.'"');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAllCountryattractions($country_id)
	{
		$data=array();
		$Q=$this->db->query("select CONCAT(attraction_category,'-', attraction_id) as attraction_id from tbl_country_top_attractions where country_id='".$country_id."'");
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function check_code_add($countrycode)
	{
		$Q=$this->db->query('select id from tbl_country_master where countrycode="'.$countrycode.'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_code_add',' Country code is in use.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function check_code_edit($countrycode)
	{
		$Q=$this->db->query('select id from tbl_country_master where countrycode="'.$countrycode.'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_code_edit',' Country code is in use.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function getAllAlternativeNames($country_id)
	{
		$data=array();
		$Q=$this->db->query('select name from tbl_country_alternative_names where country_id="'.$country_id.'"');
		return $Q->result_array(); 
	}

	/*function updateSortOrder()
		{
			$id=explode(',',$_POST['id']);
			$order=explode(',',$_POST['order']);

			$counter=count($id);
			for($i=0;$i<$counter;$i++)
			{
				if($order[$i]==0)
				{
					$order[$i]=999999999;
				}
				$data=array(
					'sortorder'=>$order[$i],
				);

				$this->db->where('id',$id[$i]);
				$this->db->update('tbl_hotels',$data);
			}

		}
	*/

}

/* End of file  */
/* Location: ./application/models/ */
