<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Seo_m extends CI_Model
{

  function add($iscity)
  {
     $sortorder='999999999';
     if($_POST['sortorder']>0)
     {
       $sortorder=$_POST['sortorder'];
     }

    $config = array(
   			'field' => 'slug',
   			'slug' => 'slug',
   			'table' => 'tbl_seo_cities_countries',
   			'id' => 'id',
 		);
 		$this->load->library('slug', $config);
 		$slugdata = array(
 			'slug' => $_POST['slug'],
 		);
 		$slug = $this->slug->create_uri($slugdata);

    if($iscity==0)
    {
        $country_id=$_POST['country_id'];
        $city_id=0;
    }
    else {
      $country_id=0;
      $city_id=$_POST['city_id'];
    }

    $data = array(
  			'country_id' => $country_id,
        'city_id' => $city_id,
        'description'=>$_POST['description'],
        'meta_title'=>$_POST['meta_title'],
        'meta_keywords'=>$_POST['meta_keywords'],
        'meta_description'=>$_POST['meta_description'],
        'sortorder'=>$sortorder,
        'original_title'=>$_POST['slug'],
        'slug'=>$slug
  		);
      $this->db->insert('tbl_seo_cities_countries', $data);
	}

	function edit($iscity)
  {

    if($iscity==0)
    {
        $country_id=$_POST['country_id'];
        $city_id=0;
    }
    else {
      $country_id=0;
      $city_id=$_POST['city_id'];
    }
    $sortorder='999999999';
    if($_POST['sortorder']>0)
    {
      $sortorder=$_POST['sortorder'];
    }
    $config = array(
   			'field' => 'slug',
   			'slug' => 'slug',
   			'table' => 'tbl_seo_cities_countries',
   			'id' => 'id',
 		);
 		$this->load->library('slug', $config);
 		$slugdata = array(
 			'slug' => $_POST['slug'],
 		);
 		$slug = $this->slug->create_uri($slugdata,$_POST['id']);

    $data = array(
      'country_id' => $country_id,
      'city_id' => $city_id,
      'description'=>$_POST['description'],
      'meta_title'=>$_POST['meta_title'],
      'meta_keywords'=>$_POST['meta_keywords'],
      'meta_description'=>$_POST['meta_description'],
      'original_title'=>$_POST['slug'],
      'sortorder'=>$sortorder,
      'original_title'=>$_POST['slug'],
      'slug'=>$slug
    );

    $this->db->where('id', $this->input->post('id'));
		$this->db->update('tbl_seo_cities_countries', $data);
	}

	function getDetailsById($id) {
		$Q = $this->db->get_where('tbl_seo_cities_countries', array('id' => $id));
		return $Q->row_array();
	}

	function check_country() {
		$this->db->select('id');
		$Q = $this->db->get_where('tbl_seo_cities_countries', array('id !=' => $_POST['id'], 'country_id' => $_POST['country_id']));
		if ($Q->num_rows() > 0) {
			$this->form_validation->set_message('check_country','That country already exists');
			return FALSE;
		}
		return TRUE;
	}

  function check_city()
  {
    $this->db->select('id');
    $Q = $this->db->get_where('tbl_seo_cities_countries', array('id !=' => $_POST['id'], 'city_id' => $_POST['country_id']));
    if ($Q->num_rows() > 0) {
      $this->form_validation->set_message('check_city','That city already exists');
      return FALSE;
    }
    return TRUE;
  }

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('tbl_seo_cities_countries');
	}

	function updateSortOrder()
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
				$this->db->update('tbl_seo_cities_countries',$data);
			}

		}


    function getAllCountries()
    {
       $data=array();
       $this->db->select('id,country_name');
       $this->db->from('tbl_country_master');
       $this->db->where('id not in(select country_id from tbl_seo_cities_countries where country_id=tbl_country_master.id )');
       $this->db->order_by('country_name','ASC');
       $Q=$this->db->get();
       return $Q->result_array();
    }

    function getAllCountriesEdit($country_id)
    {
       $data=array();
       $this->db->select('id,country_name');
       $this->db->from('tbl_country_master');
       $this->db->where("id not in(select country_id from tbl_seo_cities_countries where country_id=tbl_country_master.id) or id=$country_id");
       $this->db->order_by('country_name','ASC');
       $Q=$this->db->get();
       return $Q->result_array();
    }

    function getAllCities()
    {
       $data=array();
       $this->db->select('id,city_name');
       $this->db->from('tbl_city_master');
       $this->db->where('id not in(select city_id from tbl_seo_cities_countries where city_id=tbl_city_master.id )');
       $this->db->order_by('city_name','ASC');
       $Q=$this->db->get();
       return $Q->result_array();
    }

    function getAllCitiesEdit($city_id)
    {
       $data=array();
       $this->db->select('id,city_name');
       $this->db->from('tbl_city_master');
       $this->db->where("id not in(select city_id from tbl_seo_cities_countries where country_id=tbl_city_master.id) or id=$city_id");
       $this->db->order_by('city_name','ASC');
       $Q=$this->db->get();
       return $Q->result_array();
    }





    function check_slug($slug)
    {
        $this->db->select('id');
        $Q = $this->db->get_where('tbl_seo_cities_countries', array('id !=' => $_POST['id'],'slug'=>$slug));
        if ($Q->num_rows() > 0)
        {
          $this->form_validation->set_message('check_slug','That slug already exists');
          return FALSE;
        }
        return TRUE;
    }


    function check_slug_add($slug)
    {
       $string = strtolower(url_title(convert_accented_characters($slug),'-'));
       $Q = $this->db->get_where('tbl_seo_cities_countries', array('slug' =>$string));
       if ($Q->num_rows() > 0) {
         $this->form_validation->set_message('check_slug_add','That slug already exists');
         return FALSE;
       }
       return TRUE;
    }


}


?>
