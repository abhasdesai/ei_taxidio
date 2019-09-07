<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Attractions_wm extends CI_Model {

	function getBasicCityDetails($city_id)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where md5(id)="'.$city_id.'"');
		return $Q->row_array();
	}

	function getBasicCityDetailsFromName($name)
	{
		$Q=$this->db->query('select id,city_name,city_conclusion,citybanner,slug from tbl_city_master where city_name="'.$name.'"');
		return $Q->row_array();
	}

	/* Below Code is for searched countries */

	function getSearchedCity()
	{

		$data=array();
		if(isset($_POST['searchtags']) && count($_POST['searchtags'])>0)
		{
			$data=array();
			$this->db->select('tbl_city_attraction_log.*,tbl_city_master.id,city_name,tbl_city_master.slug as cityslug,total_days,latitude,longitude,tbl_city_master.country_id,city_conclusion,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,(select countryimage from tbl_country_master where tbl_country_master.id=tbl_city_master.country_id) as countryimage,rome2rio_name,code',FALSE);

			$this->db->from('tbl_city_attraction_log');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.id=tbl_city_attraction_log.tag_id');
			$this->db->join('tbl_city_master', 'tbl_city_master.id=tbl_city_attraction_log.city_id');

			$sq = '';
			for ($i = 0; $i < count($_POST['searchtags']); $i++)
			{
					$tag = $_POST['searchtags'][$i];
					if (count($_POST['searchtags']) == 1)
					{
						$sq = '(tag_name="' . $tag . '")';
					}
					else
					{
						if ($i == 0)
						{
							$sq .= '(tag_name="' . $tag . '"';

						}
						else if ($i == count($_POST['searchtags']) - 1)
						{
							$sq .= ' OR tag_name="' . $tag . '")';
						}
						else
						{
							$sq .= ' OR tag_name="' . $tag . '"';
						}
					}
			}
			$this->db->where($sq);
			$this->db->where('rome2rio_name',$_POST['sdestination']);
			$this->db->group_by('attraction_id');
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}

			if(count($data))
			{
				$sum=0;
				foreach($data as $key=>$list)
				{
					$sum+=$list['tag_hours'];
				}
				$data[0]['totaldaysneeded']=ceil($sum/12);
				$newdata[]=$data[0];
				return $newdata;
			}

		}
		else
		{
			//echo "<pre>";print_r($_SERVER["QUERY_STRING"])print_r($_REQUEST);die;
			$this->db->select('id,city_name,slug as cityslug,total_days,latitude,longitude,country_id,city_conclusion,(select countryimage from tbl_country_master where id=tbl_city_master.country_id) as countryimage,(select country_conclusion from tbl_country_master where id=tbl_city_master.country_id) as country_conclusion,(select country_name from tbl_country_master where id=tbl_city_master.country_id) as country_name,rome2rio_name,code');
			$this->db->from('tbl_city_master');
			$this->db->where('rome2rio_name',$_POST['sdestination']);
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{
				foreach($Q->result_array() as $row)
				{
					$data[]=$row;
				}
			}


		}
		return $data;
	}

}

?>