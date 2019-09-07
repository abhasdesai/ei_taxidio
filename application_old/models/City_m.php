<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class City_m extends CI_Model {

	function getAttractionTime($id)
	{
		$Q=$this->db->query('select sum(attraction_time_required) as total_attraction_time from tbl_city_paidattractions where city_id="'.$id.'"');
		$data=$Q->row_array();
		return $data;
	}

	function add() {

		$city_neighbours = implode(',', array_map('ucwords', explode(',', $_POST['city_neighbours'])));
		$city_name=explode(',',$_POST['city_name']);
		$total_attraction_time=0;
		$pdf=$this->uploadPDF('1');
		$data = array(
			'city_name' => $city_name[0],
			//'rome2rio_name'=>$_POST['city_name'],
			'rome2rio_name'=>$this->setRomeName(),
			'code'=>strtoupper($_POST['code']),
			'romecountryname'=>$_POST['romecountryname'],
			'romecountrycode'=>$_POST['romecountrycode'],
			'country_id'=>$_POST['country_id'],
			'continent_id'=>0,
			'city_geographical_location' => $_POST['city_geographical_location'],
			'city_neighbours' => $city_neighbours,
			'city_history' => $_POST['city_history'],
			'city_cultural_identity' => $_POST['city_cultural_identity'],
			'city_natural_resources' => $_POST['city_natural_resources'],
			'relaxation_spa' => $_POST['relaxation_spa'],
			'restaurant_nightlife' => $_POST['restaurant_nightlife'],
			'city_guides_tours' => $_POST['city_guides_tours'],
			'city_transportation_hubs' => $_POST['city_transportation_hubs'],
			'city_baggage_allowance' => $_POST['city_baggage_allowance'],
			'city_food' => $_POST['city_food'],
			'city_avg_cost_meal_drink' => $_POST['city_avg_cost_meal_drink'],
			'city_shopping' => $_POST['city_shopping'],
			'city_toursit_benefits' => $_POST['city_toursit_benefits'],
			'city_essential_local_apps' => $_POST['city_essential_local_apps'],
			'city_driving' => $_POST['city_driving'],
			'city_travel_essentials' => $_POST['city_travel_essentials'],
			'city_local_currency' => $_POST['city_local_currency'],
			'city_vendor_atm_commission' => $_POST['city_vendor_atm_commission'],
			'city_time_zone' => $_POST['city_time_zone'],
			'city_language_spoken' => $_POST['city_language_spoken'],
			'city_political_scenario' => $_POST['city_political_scenario'],
			'city_economic_scenario' => $_POST['city_economic_scenario'],
			'city_religion_belief' => $_POST['city_religion_belief'],
			'city_safety' => $_POST['city_safety'],
			'city_visa_requirements' => $_POST['city_visa_requirements'],
			'city_embassies_consulates' => $_POST['city_embassies_consulates'],
			'city_restricted_accessibility' => $_POST['city_restricted_accessibility'],
			'city_emergencies' => $_POST['city_emergencies'],
			'city_dos_donts' => $_POST['city_dos_donts'],
			'city_tipping' => $_POST['city_tipping'],
			'city_pet_imp_policies' => $_POST['city_pet_imp_policies'],
			'city_conclusion' => $_POST['city_conclusion'],
			'budget_id' => $_POST['budget_id'],
			'accomodation_id' => $_POST['accomodation_id'],
			//'doi_id' => $_POST['doi_id'],
			'doi_id' => 0,
			//'days_id' => $_POST['days_id'],
			//'weather_id' => $_POST['weather_id'],
			'days_id' => 0,
			'weather_id' => 0,
			//'travel_time_id' => $_POST['travel_time_id'],
			'travel_time_id' => 0,
			'city_significance'=>$_POST['city_significance'],
			'city_weather_seasonality'=>$_POST['city_weather_seasonality'],
			'city_transportation_costs'=>$_POST['city_transportation_costs'],
			'city_essential_vaccination'=>$_POST['city_essential_vaccination'],
			'city_adventure_sports'=>$_POST['city_adventure_sports'],
			'city_local_sports_stadium'=>$_POST['city_local_sports_stadium'],
			'city_staying_connected'=>$_POST['city_staying_connected'],
			'city_hoho'=>$_POST['city_hoho'],
			'latitude'=>$_POST['latitude'],
			'longitude'=>$_POST['longitude'],
			'buffer_days'=>$_POST['buffer_days'],
			'total_attraction_time'=>$total_attraction_time,
			'total_days'=>$_POST['buffer_days']+$total_attraction_time,
			'travelguide'=>$pdf
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $city_name[0],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;

		$flg=0;
		if(isset($_FILES['cityimage']) && $_FILES['cityimage']['name'] != "")
		{
			$flg=1;
			$nm='small'.time();
			$config['upload_path'] = './userfiles/cities/';
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

			if (!$this->upload->do_upload('cityimage'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/city/Cities/add/');
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];

				if($imgwidth==1920 && $imgheight==1080)
				{
					if ($image['file_name'])
					{
					   $data['cityimage'] = $image['file_name'];
					}
					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/'.$data['cityimage'];
					$config['new_image'] = './userfiles/cities/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/'.$data['cityimage'];
					$config['new_image'] = './userfiles/cities/small/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] = 300;
					$config['height'] = 150;//276;
					$config['file_name'] = $data['cityimage'];
					$config['master_dim'] = 'width';
					$this->image_lib->clear();
					$this->image_lib->initialize($config);
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config
				}
				else
				{
					unlink(FCPATH.'userfiles/cities/'.$nm);
					$this->session->set_flashdata('error','Image Size must be 1920px X 1080px.');
					redirect('admins/city/Cities/add/');
				}

			}
		}

		if(isset($_FILES['citybanner']) && $_FILES['citybanner']['name'] != "")
		{
			$nm='banner'.time();
			$config['upload_path'] = './userfiles/cities/banner/';
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

			if (!$this->upload->do_upload('citybanner'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/city/Cities/add/');
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
					   $data['citybanner'] = $image['file_name'];
					}

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/banner/'.$data['citybanner'];
					$config['new_image'] = './userfiles/cities/tiny/';
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
					unlink(FCPATH.'userfiles/cities/banner/'.$nm);
					$this->session->set_flashdata('error','Banner Size must be 1920px X 350px.');
					redirect('admins/city/Cities/add/');
				}

			}
		}

		$this->db->insert('tbl_city_master', $data);
		$lastid=$this->db->insert_id();
		for($i=0;$i<count($_POST['tag_id']);$i++)
		{
			$tag_id=$_POST['tag_id'][$i];
			$tagdata=array(
					'city_id'=>$lastid,
					'tag_id'=>$tag_id,
				);
			$this->db->insert('tbl_city_tags',$tagdata);
		}

		for($i=0;$i<count($_POST['rating_id']);$i++)
		{
			$rating_id=$_POST['rating_id'][$i];
			$ratingdata=array(
					'city_id'=>$lastid,
					'rating_id'=>$rating_id,
					'rating'=>$_POST['rating'][$rating_id]
				);
			$this->db->insert('tbl_city_ratings',$ratingdata);
		}



	}

	function checkChangeInCityName($city_name)
	{
		$Q=$this->db->query('select id,rome2rio_name from tbl_city_master where city_name="'.$city_name.'" and id="'.$_POST['id'].'"');
		return $Q->row_array();
	}

	function edit() {

		//echo "<pre>";
		//print_r($_POST);die;
		$city_neighbours = implode(',', array_map('ucwords', explode(',', $_POST['city_neighbours'])));
		$city_name=explode(',',$_POST['city_name']);
		$total_attraction_time=$this->getAttractionTime($_POST['id']);
		$pdf=$this->uploadPDF('2');
		$checkChange=$this->checkChangeInCityName($city_name[0]);

		/*if(count($checkChange)>0)
		{
			$rome2rio_name=$checkChange['rome2rio_name'];
		}
		else
		{
			$rome2rio_name=$_POST['city_name'];
		}*/


		$data = array(
			'city_name' => $city_name[0],
			//'rome2rio_name'=>$rome2rio_name,
			'rome2rio_name'=>$this->setRomeName(),
			'code'=>strtoupper($_POST['code']),
			'romecountryname'=>$_POST['romecountryname'],
			'romecountrycode'=>$_POST['romecountrycode'],
			'country_id'=>$_POST['country_id'],
			'continent_id'=>0,
			'city_geographical_location' => $_POST['city_geographical_location'],
			'city_neighbours' => $city_neighbours,
			'city_history' => $_POST['city_history'],
			'city_cultural_identity' => $_POST['city_cultural_identity'],
			'city_natural_resources' => $_POST['city_natural_resources'],
			'relaxation_spa' => $_POST['relaxation_spa'],
			'restaurant_nightlife' => $_POST['restaurant_nightlife'],
			'city_guides_tours' => $_POST['city_guides_tours'],
			'city_transportation_hubs' => $_POST['city_transportation_hubs'],
			'city_baggage_allowance' => $_POST['city_baggage_allowance'],
			'city_food' => $_POST['city_food'],
			'city_avg_cost_meal_drink' => $_POST['city_avg_cost_meal_drink'],
			'city_shopping' => $_POST['city_shopping'],
			'city_toursit_benefits' => $_POST['city_toursit_benefits'],
			'city_essential_local_apps' => $_POST['city_essential_local_apps'],
			'city_driving' => $_POST['city_driving'],
			'city_travel_essentials' => $_POST['city_travel_essentials'],
			'city_local_currency' => $_POST['city_local_currency'],
			'city_vendor_atm_commission' => $_POST['city_vendor_atm_commission'],
			'city_time_zone' => $_POST['city_time_zone'],
			'city_language_spoken' => $_POST['city_language_spoken'],
			'city_political_scenario' => $_POST['city_political_scenario'],
			'city_economic_scenario' => $_POST['city_economic_scenario'],
			'city_religion_belief' => $_POST['city_religion_belief'],
			'city_safety' => $_POST['city_safety'],
			'city_visa_requirements' => $_POST['city_visa_requirements'],
			'city_embassies_consulates' => $_POST['city_embassies_consulates'],
			'city_restricted_accessibility' => $_POST['city_restricted_accessibility'],
			'city_emergencies' => $_POST['city_emergencies'],
			'city_dos_donts' => $_POST['city_dos_donts'],
			'city_tipping' => $_POST['city_tipping'],
			'city_pet_imp_policies' => $_POST['city_pet_imp_policies'],
			'city_conclusion' => $_POST['city_conclusion'],
			'budget_id' => $_POST['budget_id'],
			'accomodation_id' => $_POST['accomodation_id'],
			//'doi_id' => $_POST['doi_id'],
			'doi_id' => 0,
			//'days_id' => $_POST['days_id'],
			//'weather_id' => $_POST['weather_id'],
			'days_id' => 0,
			'weather_id' => 0,
			//'travel_time_id' => $_POST['travel_time_id'],
			'travel_time_id' => 0,
			'city_significance'=>$_POST['city_significance'],
			'city_weather_seasonality'=>$_POST['city_weather_seasonality'],
			'city_transportation_costs'=>$_POST['city_transportation_costs'],
			'city_essential_vaccination'=>$_POST['city_essential_vaccination'],
			'city_adventure_sports'=>$_POST['city_adventure_sports'],
			'city_local_sports_stadium'=>$_POST['city_local_sports_stadium'],
			'city_staying_connected'=>$_POST['city_staying_connected'],
			'city_hoho'=>$_POST['city_hoho'],
			'latitude'=>$_POST['latitude'],
			'longitude'=>$_POST['longitude'],
			'buffer_days'=>$_POST['buffer_days'],
			'travelguide'=>$pdf,
			'total_attraction_time'=>round(($total_attraction_time['total_attraction_time']/12)),
			'total_days'=>round(($total_attraction_time['total_attraction_time']/12) + $_POST['buffer_days']),
		);

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_master',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $city_name[0],
		);
		$slug = $this->slug->create_uri($slugdata, $this->input->post('id'));
		$data['slug'] = $slug;

		$flg=0;
		if(isset($_FILES['cityimage']) && $_FILES['cityimage']['name'] != "")
		{
			$flg=1;
			$nm='small'.time();
			$config['upload_path'] = './userfiles/cities/';
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

			if (!$this->upload->do_upload('cityimage'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error',($error['warning']));
				redirect('admins/city/Cities/edit/'.$_POST['id']);
			}
			else
			{
				$image = $this->upload->data();
				$imgwidth=$image['image_width'];
				$imgheight=$image['image_height'];

				if($imgwidth==1920 && $imgheight==1080)
				{
					if ($image['file_name'])
					{
					   $data['cityimage'] = $image['file_name'];
					}
					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/'.$data['cityimage'];
					$config['new_image'] = './userfiles/cities/tiny/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/'.$data['cityimage'];
					$config['new_image'] = './userfiles/cities/small/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] = 300;
					$config['height'] = 150;//276;
					$config['file_name'] = $data['cityimage'];
					$config['master_dim'] = 'width';
					$this->image_lib->clear();
					$this->image_lib->initialize($config);
					$this->load->library('image_lib', $config); //load library
					$this->image_lib->resize(); //do whatever specified in config

					$this->removeImage($_POST['id']);


				}
				else
				{
					//unlink(FCPATH.'userfiles/cities/'.$nm);
					$this->session->set_flashdata('error','Image Size must be 1920px X 1080px.');
					redirect('admins/city/Cities/edit/'.$_POST['id']);
				}

			}
		}

		if(isset($_FILES['citybanner']) && $_FILES['citybanner']['name'] != "")
		{
			$nm='banner'.time();
			$config['upload_path'] = './userfiles/cities/banner/';
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

			if (!$this->upload->do_upload('citybanner'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error',($error['warning']));
				redirect('admins/cities/edit/'.$_POST['id']);
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
					   $data['citybanner'] = $image['file_name'];
					}

					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/cities/banner/'.$data['citybanner'];
					$config['new_image'] = './userfiles/cities/tiny/';
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
					//unlink(FCPATH.'userfiles/cities/banner/'.$nm);
					$this->session->set_flashdata('error','Banner Size must be 1920px X 350px.');
					redirect('admins/city/Cities/edit/'.$_POST['id']);
				}

			}
		}

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_city_master', $data);

		$this->db->where('city_id', $this->input->post('id'));
		$this->db->delete('tbl_city_tags');



		$lastid=$this->input->post('id');
		for($i=0;$i<count($_POST['tag_id']);$i++)
		{
			$tag_id=$_POST['tag_id'][$i];
			$tagdata=array(
					'city_id'=>$lastid,
					'tag_id'=>$tag_id,
				);
			$this->db->insert('tbl_city_tags',$tagdata);
		}

		if(isset($_POST['rating_id']) && count($_POST['rating_id'])>0)
		{

			$this->db->where('city_id', $this->input->post('id'));
			$this->db->delete('tbl_city_ratings');

			for($i=0;$i<count($_POST['rating_id']);$i++)
			{
				$rating_id=$_POST['rating_id'][$i];
				$ratingdata=array(
						'city_id'=>$lastid,
						'rating_id'=>$rating_id,
						'rating'=>$_POST['rating'][$rating_id]
					);
				$this->db->insert('tbl_city_ratings',$ratingdata);
			}
		}

		updateCityData($this->input->post('id'));

	}

	function removeImage($id)
	{
		$Q=$this->db->query('select cityimage from tbl_city_master where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['cityimage']!='')
		{
			if(file_exists(FCPATH.'userfiles/cities/'.$data['cityimage']))
			{
				unlink(FCPATH.'userfiles/cities/'.$data['cityimage']);
			}
			if(file_exists(FCPATH.'userfiles/cities/small/'.$data['cityimage']))
			{
				unlink(FCPATH.'userfiles/cities/small/'.$data['cityimage']);
			}
			if(file_exists(FCPATH.'userfiles/cities/tiny'.$data['cityimage']))
			{
				uunlink(FCPATH.'userfiles/cities/tiny/'.$data['cityimage']);
			}

		}
	}

	function removeBanner($id)
	{
		$Q=$this->db->query('select citybanner from tbl_city_master where id="'.$id.'"');
		$data=$Q->row_array();
		if($data['citybanner']!='')
		{
			if(file_exists(FCPATH.'userfiles/cities/banner/'.$data['citybanner']))
			{
				unlink(FCPATH.'userfiles/cities/banner/'.$data['citybanner']);
			}

			if(file_exists(FCPATH.'userfiles/cities/tiny'.$data['citybanner']))
			{
				uunlink(FCPATH.'userfiles/cities/tiny/'.$data['citybanner']);
			}

		}
	}

	function getAllCityTags($city_id)
	{
		$data=array();
		$Q=$this->db->query('select tag_id from tbl_city_tags where city_id="'.$city_id.'"');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}


	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_city_master', array('id' => $id));
		return $Q->row_array();
	}

	function check_city($city_name) {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_city_master', array('id !=' => $_POST['id'], 'city_name' => $city_name,'country_id'=>$_POST['country_id']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_city', 'This City,Country Combination already exists.');
			return FALSE;
		}
		return TRUE;
	}

	function check_combination($city_name)
	{
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_city_master', array('city_name' => $city_name,'country_id'=>$_POST['country_id']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_combination', 'This City,Country Combination already exists.');
			return FALSE;
		}
		return TRUE;
	}

	function delete($id)
	{
		$this->removeImage($id);
		$this->removeBanner($id);
		$this->removeOldPDF($id);

		$this->db->where('id', $id);
		$this->db->delete('tbl_city_master');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_clothes');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_events');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_paidattractions');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_relaxationspa');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_restaurants');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_stadiums');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_weathers');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_tags');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_ratings');

		$this->db->where('city_id', $id);
		$this->db->delete('city_mandatory_tag_master');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_hotel_cost_master');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_sports_adventures');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_zipcodes');

		$this->db->where('city_id', $id);
		$this->db->delete('tbl_city_attraction_log');
	}

	function getTags()
	{
		$data=array();
		$Q=$this->db->query('select id,tag_name from tbl_tag_master order by sortorder asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getRatings()
	{
		$data=array();
		$Q=$this->db->query('select id,rating_type from tbl_rating_master order by rating_type asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAllContinents()
	{
		$data=array();
		$Q=$this->db->query('select id,continent_name from tbl_continent_master where id in(select continent_id from tbl_continent_countries where continent_id=tbl_continent_master.id) order by continent_name asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getCountries()
	{
		$data=array();
		$this->db->select('tbl_country_master.id,country_name');
		$this->db->from('tbl_country_master');
		$this->db->join('tbl_continent_countries','tbl_continent_countries.country_id=tbl_country_master.id');
		$this->db->where('tbl_continent_countries.continent_id',$_POST['continent_id']);
		$this->db->order_by('country_name','ASC');
		$this->db->distinct();
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getAllCountries()
	{
		$data=array();
		$this->db->select('tbl_country_master.id,country_name');
		$this->db->from('tbl_country_master');
		$this->db->order_by('country_name','ASC');
		$this->db->distinct();
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function add_event()
	{
		$Q=$this->db->query('select continent_id,country_id from tbl_city_master where id="'.$_POST['city_id'].'"');
		$citydata=$Q->row_array();

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_events',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['event_name'],
		);
		$slug = $this->slug->create_uri($slugdata);
		$data['slug'] = $slug;
		$data=array(
			'month_id'=>$_POST['month_id'],
			'event_name'=>$_POST['event_name'],
			'event_description'=>$_POST['event_description'],
			'city_id'=>$_POST['city_id'],
			'country_id'=>$citydata['country_id'],
			'continent_id'=>$citydata['continent_id']
			);

		$this->db->insert('tbl_city_events', $data);
	}

	function edit_event()
	{

		$config = array(
			'field' => 'slug',
			'slug' => 'slug',
			'table' => 'tbl_city_events',
			'id' => 'id',
		);
		$this->load->library('slug', $config);
		$slugdata = array(
			'slug' => $_POST['event_name'],
		);
		$slug = $this->slug->create_uri($slugdata,$this->input->post('id'));
		$data['slug'] = $slug;
		$data=array(
			'month_id'=>$_POST['month_id'],
			'event_name'=>$_POST['event_name'],
			'event_description'=>$_POST['event_description'],
			);

		$this->db->where('id',$this->input->post('id'));
		$this->db->update('tbl_city_events', $data);
	}

	function delete_event($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_city_events');
	}

	function getAllMonths()
	{
		$data=array();
		$Q=$this->db->query('select id,month_name from tbl_month_master order by id ASC');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		return $data;
	}

	function getEventDetails($id)
	{
		$Q=$this->db->query('select * from tbl_city_events where id="'.$id.'"');
		return $Q->row_array();
	}

	function getCityName($id)
	{
		$Q=$this->db->query('select city_name from tbl_city_master where id="'.$id.'"');
		return $Q->row_array();
	}

	function check_event_month_add($event_name)
	{
		$Q=$this->db->query('select id from tbl_city_events where event_name="'.$event_name.'" and month_id="'.$_POST['month_id'].'" and city_id="'.$_POST['city_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_event_month_add','This event already exists for this city and month.');
			return FALSE;
		}
		return TRUE;
	}

	function check_event_month_edit($event_name)
	{
		$Q=$this->db->query('select id from tbl_city_events where event_name="'.$event_name.'" and month_id="'.$_POST['month_id'].'" and city_id="'.$_POST['city_id'].'" and id!="'.$_POST['id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_event_month_edit','That event already exists for this city and month.');
			return FALSE;
		}
		return TRUE;
	}


	function getAllRatings()
	{
		$data=array();
		$Q=$this->db->query('select id,rating_type from tbl_rating_master order by rating_type ASC');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		return $data;
	}

	function getAllSelectedRatings($id)
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_city_ratings where city_id="'.$id.'"');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function addDefaultTags()
	{
		for($i=0;$i<count($_POST['id']);$i++)
		{
			$tags=0;
			if($_POST['id'][$i]==1)
			{
				if(isset($_POST['stadium']) && $_POST['stadium']!='')
				{
					$tags=implode(',',$_POST['stadium']);
				}
			}
			else if($_POST['id'][$i]==2)
			{
				if(isset($_POST['restaurant']) && $_POST['restaurant']!='')
				{
					$tags=implode(',',$_POST['restaurant']);
				}
			}
			else if($_POST['id'][$i]==3)
			{
				if(isset($_POST['attraction']) && $_POST['attraction']!='')
				{
					$tags=implode(',',$_POST['attraction']);
				}
			}
			else if($_POST['id'][$i]==4)
			{
				if(isset($_POST['relaxation']) && $_POST['relaxation']!='')
				{
					$tags=implode(',',$_POST['relaxation']);
				}
			}
			else if($_POST['id'][$i]==5)
			{
				if(isset($_POST['adventure']) && $_POST['adventure']!='')
				{
					$tags=implode(',',$_POST['adventure']);
				}
			}

			$data=array(
						'default_tags'=>$tags
					);
			$this->db->where('id',$_POST['id'][$i]);
			$this->db->update('tbl_defaults',$data);

		}
	}

	function getDefaultStadiumTags()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=1');
		return $Q->row_array();
	}

	function getDefaultAttractionTags()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=3');
		return $Q->row_array();
	}

	function getDefaultRestaurantTags()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=2');
		return $Q->row_array();
	}

	function getDefaultRelaxationTags()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=4');
		return $Q->row_array();
	}

	function getDefaultAdventureTags()
	{
		$Q=$this->db->query('select default_tags from tbl_defaults where id=5');
		return $Q->row_array();
	}

	function check_code_add($code)
	{
		$Q=$this->db->query('select id from tbl_city_master where code="'.$code.'" and country_id="'.$_POST['country_id'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_code_add','This code already exists for this country.');
			return FALSE;
		}
		return TRUE;
	}

	function check_code_edit($code)
	{
		$Q=$this->db->query('select id from tbl_city_master where code="'.$code.'" and country_id="'.$_POST['country_id'].'" and id!="'.$_POST['id'].'"');
		//echo $this->db->last_query();die;
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_code_edit','This code already exists in this country.');
			return FALSE;
		}
		return TRUE;
	}

	function uploadPDF($addoredit)
	{
		$pdf='';
		if(isset($_FILES['travelguide']) && $_FILES['travelguide']['name'] != "")
		{
			$config['upload_path'] = './userfiles/travelguide/';
			$config['allowed_types'] = 'pdf';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('travelguide'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				if($addoredit==1)
				{
					redirect('admins/city/Cities/add/');
				}
				else
				{
					redirect('admins/city/Cities/edit/'.$_POST['id']);
				}
			}
			else
			{
				$image = $this->upload->data();
				$pdf=$image['file_name'];

				if($addoredit==2)
				{
					$this->removeOldPDF($_POST['id']);
				}

			}

		}

		return $pdf;

	}

	function removeOldPDF($id)
	{
		$Q=$this->db->query('select travelguide from tbl_city_master where id="'.$id.'" limit 1');
		$data=$Q->row_array();
		if($data['travelguide']!='')
		{
			if(file_exists(FCPATH.'userfiles/travelguide/'.$data['travelguide']))
			{
				unlink(FCPATH.'userfiles/travelguide/'.$data['travelguide']);
			}
		}
	}

	function setRomeName()
	{
		$this->db->select('country_name');
		$this->db->from('tbl_country_master');
		$this->db->where('id',$_POST['country_id']);
		$Q=$this->db->get();
		$data=$Q->row_array();
	    $city_name=explode(',',$_POST['city_name']);
		if($data['country_name']!=$city_name[0])
		{
			$nm=$city_name[0].', '.$data['country_name'];
		}
		else
		{
			$nm=$city_name[0];
		}
		return $nm;

	}
}

/* End of file  */
/* Location: ./application/models/ */
