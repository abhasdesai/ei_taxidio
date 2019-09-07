<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Travelblueprint_m extends CI_Model {


    function getAllCountries()
    {
       $data=array();
       $Q=$this->db->query('select id,country_name from tbl_country_master order by country_name asc');
       return $Q->result_array();
    }

    function getAllBlueprints()
    {
       $data=array();
       $Q=$this->db->query('select * from tbl_travel_blueprint order by id asc');
       return $Q->result_array();	
    }


    function store()
    {
    	$errors=array();
    	for($i=1;$i<=5;$i++)
    	{
    		 if($i!=3)
    		 {
    		 	$width=577;$height=331;
    		 }
    		 else
    		 {
    		 	$width=1170;$height=331;
    		 }
    		 if(isset($_FILES['image'.$i]['name']) && $_FILES['image'.$i]['name']!='')
    		 {
    		 	$errors[]=$this->uploadimage('image'.$i,$width,$height,$i);
    		 }
    		 else
    		 {
    		 	$this->db->where('id',$i);
    		 	$this->db->update('tbl_travel_blueprint',array('country_id'=>$_POST['country'.''.$i]));
    		 }
    	}
    	$errors = array_map('current', $errors);
    	return $errors;
    }

    function uploadimage($imagenm,$imgwidth,$imgheight,$counter)
    {
    	STATIC $count1 = 0;
    	$count1++;
    	$errors=array();
		$flag1=true;
		$errormsg ="";
		$nm=time().'_'.$counter;
		if($_FILES[$imagenm]['name'] != "")
		{		
			$config['upload_path'] = './userfiles/travelblueprint/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '11701';
			$config['max_height']  = '3311';
			$config['file_name'] = $nm;
			$this->load->library('upload');
			$this->upload->initialize($config); 	
			if (!$this->upload->do_upload($imagenm))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error',($error[warning]));
				redirect('admins/Travelblueprint/');
			}
			else
			{
				$image = $this->upload->data();
				list($width,$height)=getimagesize(FCPATH.'userfiles/travelblueprint/'.$image['file_name']);

				if($imgwidth==$width && $imgheight==$height)
				{					
					if ($image['file_name'])
					{
					   $data['image'] = $image['file_name'];
					}
					$config['image_library'] = 'gd2';
					$config['source_image'] = './userfiles/travelblueprint/'.$data['image'];
					$config['new_image'] = './userfiles/travelblueprint/small/';
					$config['maintain_ratio'] = TRUE;
					$config['overwrite'] = false;
					$config['width'] =100;
					$config['height'] = 100;//84;
					$config['master_dim'] = 'width';
					$config['file_name'] = $nm;
					if($count1>1)
					{
						$this->image_lib->clear();
						$this->image_lib->initialize($config);
					}
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					
					
					$Q=$this->db->query('select image from tbl_travel_blueprint where id="'.$counter.'"');
					$dt=$Q->row_array();
					if($dt['image']!='' && file_exists(FCPATH.'userfiles/travelblueprint/'.$dt['image']))
					{
						unlink(FCPATH.'userfiles/travelblueprint/'.$dt['image']);
						unlink(FCPATH.'userfiles/travelblueprint/small/'.$dt['image']);
					}

					$Q1=$this->db->query('select image from tbl_travel_blueprint where id="'.$counter.'"');
					if($Q1->num_rows()>0)
					{
						$this->db->where('id',$counter);	
						$this->db->update('tbl_travel_blueprint',array('image'=>$image['file_name'],'country_id'=>$_POST['country'.''.$counter]));
					}
					else
					{
						$this->db->insert('tbl_travel_blueprint',array('image'=>$image['file_name'],'country_id'=>$_POST['country'.''.$counter]));
					}

					

				}
				else
				{
					unlink(FCPATH.'userfiles/travelblueprint/'.$image['file_name']);
					$errors=array(
							'image'=>$imagenm
						);
				}
			}
		}

		return $errors;

    }


}

?>
