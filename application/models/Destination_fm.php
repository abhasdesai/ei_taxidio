<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Destination_fm extends CI_Model {
	
	function getAllCountry()
	{
		$data=array();
		if(!isset($_POST['q']))
		{
			$_POST['q']=null;
		}
		$Q=$this->db->query('select country_name from tbl_country_master where country_name like "'.$_POST['q'].'%" or slug like "'.$_POST['q'].'%"');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
			
		}
		return json_encode($data);
	}

	function getAllContinent()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_continent_master order by continent_name ASC');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
			
		}
		return $data;
	}

    function countTotalDestinations()
    {
        $this->db->select('id');
        $this->db->from('tbl_country_master');

        if(isset($_POST['continent_id']) && $_POST['continent_id']>0)
        {
             $this->db->where('id in(select country_id from tbl_continent_countries where continent_id="'.$_POST['continent_id'].'")');    
        }
        elseif (isset($_POST['country_name']) && !empty($_POST['country_name'])) {
        	$this->db->where('(country_name like "'.$_POST['country_name'].'%" or slug like "'.$_POST['country_name'].'%" or rome2rio_name like "'.$_POST['country_name'].'%")');
        }

        $Q=$this->db->get();
        return $Q->num_rows();
    }

    function getTotalDestinations($limit,$start)
    {
        $data=array(); 
        $this->db->select('id,country_name,(select continent_name from tbl_continent_master where id=tbl_country_master.continent_id) as continent_name,country_capital,country_currency,country_conclusion,countryimage,timezone');
        $this->db->from('tbl_country_master');
 
        if(isset($_POST['continent_id']) && $_POST['continent_id']>0)
        {
             $this->db->where('id in(select country_id from tbl_continent_countries where continent_id="'.$_POST['continent_id'].'")');   
        }
        elseif (isset($_POST['country_name']) && !empty($_POST['country_name'])) {
        	$this->db->where('(country_name like "'.$_POST['country_name'].'%" or slug like "'.$_POST['country_name'].'%" or rome2rio_name like "'.$_POST['country_name'].'%")');
        }

        $this->db->order_by('continent_id','ASC');
        $this->db->order_by('country_name','ASC');
        $this->db->limit($limit,$start);
        $Q=$this->db->get();
        $data=$Q->result_array();
        
        //echo "<pre>";print_r($data);die;
        return $data;
    }


	function getCities($country_id)
	{
		$data=array();
		$Q=$this->db->query('select id,city_name,slug from tbl_city_master where  country_id="'.$country_id.'" order by city_name ASC');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
			
		}
		return $data;
	}

	function getTag($country_id)
	{
		$Q=$this->db->query('select tag_name from tbl_tag_master where id in (select tag_id from tbl_country_tags where country_id='.$country_id.')');
		if($Q->num_rows()>0)
		{
			foreach ($Q->result_array() as $row) 
			{
				$data[]=$row;
			}
		}
		return $data;
	}

	function getPaidattractionbyid($id)
	{
		$data=array();
		$this->db->select("attraction_name");
		$this->db->from('tbl_city_paidattractions');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			$data=$Q->row_array();
		}
		return $data;
	}

	function getRelaxationspabyid($id)
	{
		$data=array();
		$this->db->select("ras_name as attraction_name");
		$this->db->from('tbl_city_relaxationspa');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			$data=$Q->row_array();
		}
		return $data;
	}

	function getRestaurantsbyid($id)
	{
		$data=array();
		$this->db->select("ran_name as attraction_name");
		$this->db->from('tbl_city_restaurants');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			$data=$Q->row_array();
		}
		return $data;
	}

	function getStadiumsbyid($id)
	{
		$data=array();
		$this->db->select("stadium_name as attraction_name");
		$this->db->from('tbl_city_stadiums');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			$data=$Q->row_array();
		}
		return $data;
	}

	function getSports_adventuresbyid($id)
	{
		$data=array();
		$this->db->select("adventure_name as attraction_name");
		$this->db->from('tbl_city_sports_adventures');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if ($Q->num_rows() > 0) {
			$data=$Q->row_array();
		}
		return $data;
	}

	function getAllCountryattractions($country_id)
	{
		$data=array();
		$Q=$this->db->query("select * from tbl_country_top_attractions where country_id='".$country_id."'");
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				if($row['attraction_category']==='paidattractions')
				{
					$data[]=$this->getPaidattractionbyid($row['attraction_id']);
				}
				elseif($row['attraction_category']==='relaxationspa')
				{
					$data[]=$this->getRelaxationspabyid($row['attraction_id']);
				}
				elseif($row['attraction_category']==='restaurants')
				{
					$data[]=$this->getRestaurantsbyid($row['attraction_id']);
				}
				elseif($row['attraction_category']==='stadiums')
				{
					$data[]=$this->getStadiumsbyid($row['attraction_id']);
				}
				elseif($row['attraction_category']==='sports_adventures')
				{
					$data[]=$this->getSports_adventuresbyid($row['attraction_id']);
				}
			}
		}
		return $data;
	}

}
