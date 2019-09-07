<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hotel_m extends CI_Model
{

	function getAllContinents()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_continent_master order by continent_name asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[]=$row;
			}
		}

		return $data;
	}

	function add()
	{
		if(isset($_FILES['hotel']['name']) && $_FILES['hotel']['name'] !='')
		{
			$config['upload_path'] = './userfiles/hotels/';
			$config['allowed_types'] = '*';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['file_name'] = time();
			$this->load->library('upload');
			$this->upload->initialize($config); 

			if (!$this->upload->do_upload('hotel'))
			{
				$flag1 = false;
				$error = array('warning' =>  $this->upload->display_errors());
				$this->session->set_flashdata('error', ($error['warning']));
				redirect('admins/Hotels','refresh');
			}
			else
			{
				$image = $this->upload->data();
			    $foldername=explode('.',$image['file_name']);
			   	$foldername=$foldername[0];
			    
			    mkdir(FCPATH.'userfiles/hotels/'.$foldername, 0777,true);         
			      
				$zip = new ZipArchive;
            	if ($zip->open(FCPATH.'userfiles/hotels/'.$image['file_name']) === TRUE) 
            	{
                    $zip->extractTo(FCPATH.'userfiles/hotels/'.$foldername);
                    $zip->close();

	            } 
	            else 
	            {
	                echo 'failed';
	            }


				$uploadedfiles = glob(FCPATH.'userfiles/hotels/'.$foldername.'/*');
				$content = file_get_contents($uploadedfiles[0]);			
				$lines 	= explode("\n",$content);

				
				for($i=1;$i<count($lines)-1;$i++)
				{
					$columns = explode("\t", $lines[$i]);
					$col0='';$col1='';$col2='';$col3='';$col4='';$col9='';$col10='';
					$col13='';$col14='';$col17='';$col34='';$col33='';$col16='';$col18='';
					$col8='';

					
					if(isset($columns[0]) && $columns[0]!='')
					{
						$col0=$columns[0];
					}
					if(isset($columns[1]) && $columns[1]!='')
					{
						$col1=$columns[1];
					}
					if(isset($columns[2]) && $columns[2]!='')
					{
						$col2=$columns[2];
					}
					if(isset($columns[3]) && $columns[3]!='')
					{
						$col3=$columns[3];
					}		
					if(isset($columns[4]) && $columns[4]!='')
					{
						$col4=$columns[4];
					}
					if(isset($columns[9]) && $columns[9]!='')
					{
						$col9=$columns[9];
					}
					if(isset($columns[10]) && $columns[10]!='')
					{
						$col10=$columns[10];
					}
					if(isset($columns[13]) && $columns[13]!='')
					{
						$col13=$columns[13];
					}
					if(isset($columns[14]) && $columns[14]!='')
					{
						$col14=$columns[14];
					}
					if(isset($columns[17]) && $columns[17]!='')
					{
						$col17=$columns[17];
					}
					if(isset($columns[34]) && $columns[34]!='')
					{
						$col34=$columns[34];
					}
					if(isset($columns[33]) && $columns[33]!='')
					{
						$col33=$columns[33];
					}
					if(isset($columns[16]) && $columns[16]!='')
					{
						$col16=$columns[16];
					}
					if(isset($columns[18]) && $columns[18]!='')
					{
						$col18=$columns[18];
					}
					if(isset($columns[8]) && $columns[8]!='')
					{
						$col8=$columns[8];
					}


					if($col1!='')
					{
						$data=array(
							'booking_hotel_id'=>$col0,
							'hotel_name'=>$col1,
							'address'=>$col2,
							'zip'=>$col3,
							'city_hotel'=>$col4,
							'minrate'=>$col9,
							'maxrate'=>$col10,
							'longitude'=>$col13,
							'latitude'=>$col14,
							'photo_url'=>$col17,
							'city_preferred'=>$col34,
							'city_unique'=>$col33,
							'hotel_url'=>$col16,
							'description'=>$col18,
							'currencycode'=>$col8,
							'continent_id'=>$_POST['continent_id'],				
						);
						$this->db->insert('tbl_hotels',$data);
					}

				}

				if(is_dir(FCPATH.'userfiles/hotels/'.$foldername))
				{
					$files = glob(FCPATH.'userfiles/hotels/'.$foldername.'/*');
					foreach($files as $file)
					{
					   if(is_file($file))
					   {
					      unlink($file);
					   }	
					}
					unlink(FCPATH.'userfiles/hotels/'.$image['file_name']);
					rmdir(FCPATH.'userfiles/hotels/'.$foldername); 
				}
				
				

			}

			

		}


		
	}

}


?>