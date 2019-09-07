<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cron_city_pdf_m extends CI_Model {

	function getcitydetails($city_id='') 
	{
		$city_details = array();
		$attraction_known_for = array();
		$result_relaxation_array = array();
		$result_restaurants_array = array();
		$result_events_array = array();
		$result_adventures_array = array();
		$result_stadium_array = array();
		$city_tags_array = array();
		/*City tags*/
		$this->db->select('*');
        $this->db->from('tbl_city_tags as t');
        $this->db->join('tbl_tag_master as m', 'm.id = t.tag_id');
        $this->db->group_by('city_id,t.id');
        $query = $this->db->get();
        $city_tags = $query->result();
        foreach ($city_tags as $key_city => $value_city) 
        {
        	$city_tags_array[$value_city->city_id][] = $value_city->tag_name;
        }
       
		/*End city tags*/
		$this->db->select('tm.*');
        $this->db->from('tbl_tag_master as tm');
        $query = $this->db->get();
        $attraction_known_for_array = $query->result();
        //$attraction_known_for_last_array = array_combine(array_column($attraction_known_for_array, 'id'), array_column($attraction_known_for_array, 'tag_name'));
 //echo '<pre>';print_r($attraction_known_for_last_array);die;

		$attraction_known_for_last_array = array_column($attraction_known_for_array, 'tag_name', 'id');

		$this->db->select('cm.*');
        $this->db->from('tbl_city_master as cm');
        //$this->db->where('cm.id',$city_id);
        $query = $this->db->get();
        $result = $query->result();

        $city_details = $result;


         $city_ids = array_map(function ($value) {
        return  $value->id;
        }, $result);


        //$city_ids = array_column($result, 'id');



         /*All month details*/
        $this->db->select('*');
        $this->db->from('tbl_month_master');
        $query = $this->db->get();
        $month_array = $query->result();
        //$attraction_known_for_last_array = array_combine(array_column($attraction_known_for_array, 'id'), array_column($attraction_known_for_array, 'tag_name'));
 //echo '<pre>';print_r($attraction_known_for_last_array);die;

		$month_last_array = array_column($month_array, 'month_name', 'id');
       /*End all month details*/

      //echo '<pre>';print_r($city_ids);die;


        $this->db->select('pa.*');
        $this->db->from('tbl_city_paidattractions as pa');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,pa.id');
        $query = $this->db->get();
        $result_attractions = $query->result();


        foreach ($result_attractions as $key => $value) 
        {
        	$exploded_attractions = explode(",", $value->attraction_known_for);
        	$tags = '';
        	foreach ($exploded_attractions as $key1 => $value1) 
        	{
        		$tags .=  $attraction_known_for_last_array[$value1].',';
        	}
        	//$attraction_known_for[$value->city_id][$value1]['attraction_details']->attraction_known_for =  rtrim($tags,",");
        	$value->attraction_known_for =  rtrim($tags,",");
            if($value->ispaid == 1)
            {
                $attraction_known_for[$value->city_id]['attraction_details']['paid'][] =  $value;
            }
            else
            {
                $attraction_known_for[$value->city_id]['attraction_details']['free'][] =  $value;
            }
            
        	

        	//$attraction_known_for[$value->city_id][$value1]['tags'] =  rtrim($tags,",");
        	
        }
        //echo '<pre>';print_r($attraction_known_for);die;


        $this->db->select('*');
        $this->db->from('tbl_city_relaxationspa');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,id');
        $query = $this->db->get();
        $result_relaxation = $query->result();
        foreach ($result_relaxation as $key => $value) 
        {
        	$result_relaxation_array[$value->city_id][] = $value;
        	
        	
        }
    
        //attraction_known_for
        $this->db->select('*');
        $this->db->from('tbl_city_restaurants');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,id');
        $query = $this->db->get();
        $result_restaurants = $query->result();
        foreach ($result_restaurants as $key => $value) 
        {
        	$result_restaurants_array[$value->city_id][] = $value;
        }

        $this->db->select('*');
        $this->db->from('tbl_city_events');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,id');
        $query = $this->db->get();
        $result_events = $query->result();
        foreach ($result_events as $key => $value) 
        {
        	$value->month_name = $month_last_array[$value->month_id];
        	$result_events_array[$value->city_id][] = $value;

        }


        $this->db->select('*');
        $this->db->from('tbl_city_sports_adventures');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,id');
        $query = $this->db->get();
        $result_adventures = $query->result();
        foreach ($result_adventures as $key => $value) 
        {
        	$result_adventures_array[$value->city_id][] = $value;
        }



        $this->db->select('*');
        $this->db->from('tbl_city_stadiums');
        $this->db->where_in('city_id',$city_ids);
        $this->db->group_by('city_id,id');
        $query = $this->db->get();
        $result_stadium = $query->result();
        foreach ($result_stadium as $key => $value) 
        {
        	$result_stadium_array[$value->city_id][] = $value;
        }

        $final_array = array('city_details'=>$city_details,'attraction_details'=>$attraction_known_for,'spa_details'=>$result_relaxation_array,'restaurants_details'=>$result_restaurants_array,'events_details'=>$result_events_array,'adventures_details'=>$result_adventures_array,'stadium_details'=>$result_stadium_array,'tag_array'=>$city_tags_array);
        

        return $final_array;
	}

	

}
