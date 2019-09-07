<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Planneditinerary_m extends CI_Model
{

    function deleteTrip($id)
    {


          $Q=$this->db->query('select id,trip_type from tbl_itineraries where id="'.$id.'" limit 1');

          if($Q->num_rows()>0)
          {

            $data=$Q->row_array();
            if($data['trip_type']==1)
            {

              $this->deleteSingleCountryTrip($data['id']);
            }
            else if($data['trip_type']==2)
            {
              $this->deleteMultiCountryTrip($data['id']);
            }
            else if($data['trip_type']==3)
            {
              $this->deleteSearchedCityTrip($data['id']);
            }

            if(is_dir(FCPATH.'userfiles/savedfiles/'.$tripid))
            {
              $files = glob(FCPATH.'userfiles/savedfiles/'.$tripid.'/*');
              foreach($files as $file)
              {
                 if(is_file($file))
                 {
                    unlink($file);
                 }
              }
              rmdir(FCPATH.'userfiles/savedfiles/'.$tripid);
            }


          }
          else
          {
            $this->session->set_flashdata('error', 'Oopp..!Something went wrong..');
            redirect('admin/planneditineraries');
          }
    }

    function deleteSingleCountryTrip($id)
  	{
  		$this->db->where('id',$id);
  		$this->db->delete('tbl_itineraries');

  		$this->db->where('itinerary_id',$id);
  		$this->db->delete('tbl_itineraries_cities');

  	}

  	function deleteSearchedCityTrip($id)
  	{

  		$this->db->where('id',$id);
  		$this->db->delete('tbl_itineraries');

  		$this->db->where('itinerary_id',$id);
  		$this->db->delete('tbl_itineraries_searched_cities');

  	}


  	function deleteMultiCountryTrip($id)
  	{

  		$Q=$this->db->query('select id from tbl_itineraries_multicountrykeys where itineraries_id="'.$id.'"');

  		if($Q->num_rows()>0)
  		{
  			foreach($Q->result_array() as $row)
  			{

  				$Q1=$this->db->query('select id from tbl_itineraries_multicountries where combination_id="'.$row['id'].'"');

  				if($Q1->num_rows()>0)
  				{


  					foreach($Q1->result_array() as $row1)
  					{
  						$this->db->where('country_combination_id',$row1['id']);
  					    $this->db->delete('tbl_itineraries_multicountries_cities');
  					}
  				}

  				$this->db->where('combination_id',$row['id']);
  				$this->db->delete('tbl_itineraries_multicountries');
  			}
  		}

  		$this->db->where('id',$id);
  		$this->db->delete('tbl_itineraries');

  		$this->db->where('itineraries_id',$id);
  		$this->db->delete('tbl_itineraries_multicountrykeys');


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
          $order[$i]=99999;
        }
        $data=array(
          'sort_planned_iti'=>$order[$i],
        );

        $this->db->where('id',$id[$i]);
        $this->db->update('tbl_itineraries',$data);
      }

    }
    
}

?>
