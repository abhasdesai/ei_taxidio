<?php

class Master_model extends CI_Model
{
	
	function selectcolbycondition($selectcol,$table,$condition)
	{
		$this->db->select($selectcol);
		$this->db->from($table);
		$this->db->where($condition);
	    $query = $this->db->get();
	    if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function setfullpathforfile($olddata,$column_name,$path,&$newdata)
	{
		foreach($olddata as $key=>$r)
		{
			 if($r[$column_name]!="")
			 {
				$newdata[$key][$column_name]=site_url($path.$r[$column_name]);
			 }
			 else
			 {
				$newdata[$key][$column_name]="";
			 }
		}
	}
	
	function getcityinfo($cityid)
	{
		$data['citypostid']=$cityfile= md5($cityid);
		$data['basic']=$basic=$this->recommendation_wm->getLatandLongOfCity($cityfile);
		$data['basic']['countryimage']=site_url("userfiles/countries/".$basic['countryimage']);
		$data['basic']['cityimage']=site_url("userfiles/cities/".$basic['cityimage']);
		$data['basic']['countrybanner']=site_url("userfiles/countries/banner/".$basic['countrybanner']);
		$data['countryimage']=$data['basic']['countryimage'];
		$data['latitude']=$basic['citylatitude'];
		$data['longitude']=$basic['citylongitude'];
		$data['cityimage']=$data['basic']['cityimage'];
		$data['basiccityname']=$basic['city_name'];
		$data['countryconclusion']=$basic['country_conclusion'];
		$data['countrybanner']=$basic['countrybanner'];
		//$countrandtype=$returnkey.'-single-'.time();
		//$data['secretkey']=string_encode($countrandtype);
		$filestore=$this->recommendation_wm->getUserRecommededAttractionsForCountry($cityfile);

				$attraction_decode=json_decode($filestore,TRUE);
				$sort = array();
				foreach($attraction_decode as $k=>$v)
				{
				    $sort['isselected'][$k] = $v['isselected'];
				    $sort['order'][$k] = $v['order'];
				    $sort['tag_star'][$k] = $v['properties']['tag_star'];
				}
				array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
				
			    $data['filestore']=json_encode($attraction_decode);
			    return $data;
	}

	function getalluserdatabyid($id)
	{
		$column="`id`, `name`, `designation`, `image`, `company_name`, `address`, 
		`website`, `logo`, `token`, `device_id`, `version`, `device_type`";
		$this->db->select($column);
		$this->db->from("tbl_user_mst");
		$this->db->where("id='".$id."'");
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$result['data']=$query->row_array();
			
			if(isset($result['data']['image']) && $result['data']['image']!="")
			{
				$result['data']['image']=site_url("uploads/users/".$result['data']['image']);
			}
			else
			{
				$result['data']['image']="";
			}
			
			if(isset($result['data']['logo']) && $result['data']['logo']!="")
			{
				$result['data']['logo']=site_url("uploads/logo/".$result['data']['logo']);
			}
			else
			{
				$result['data']['logo']="";
			}
			//for contact no details
			$this->db->select("`type`,`contact_no`, `is_primary`");
			$this->db->from("tbl_contact_nos");
			$this->db->where("user_id='".$id."'");
			$query2=$this->db->get();
			$result['data']['contactno_details']=$query2->result_array();
			
			//for email details
			$this->db->select("`email`");
			$this->db->from("tbl_emails");
			$this->db->where("user_id='".$id."'");
			$query3=$this->db->get();
			$email=$query3->row_array();
			
			$result['data']['email']="";
			if(isset($email['email']))
			{
				$result['data']['email']=$email['email'];
			}
			
			//for social media link details
			$this->db->select("`link`");
			$this->db->from("tbl_social_media_link");
			$this->db->where("user_id='".$id."'");
			$query3=$this->db->get();
			$social_media_link=$query3->row_array();
			
			$result['data']['social_media_link']="";
			if(isset($social_media_link['link']))
			{
				$result['data']['social_media_link']=$social_media_link['link'];
			}
			
			 return $result['data'];					 
		}
		return false;
	}
	
	function getdatabyuser($user_id)
	{
		$column="u.id,u.name,e.email,c.contact_no,u.company_name,u.designation,u.address,u.image,u.token,u.device_id,u.version,u.device_type";
		$this->db->select($column);
		$this->db->from("tbl_user_mst as u");
		$this->db->where("u.id='".$user_id."'");
		$this->db->join('tbl_emails as e', 'e.user_id = u.id','left');
		$this->db->join('tbl_contact_nos as c', 'c.user_id = u.id','left');
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$data=$query->row_array();
			if(isset($data['image']) && $data['image']!="")
			{
				$data['image']=site_url("uploads/users/".$data['image']);
			}
			else
			{
				$data['image']="";
			}
			return $data;
		}
		return false;
	
	}
	
	function getallcontactbyuser($user_id)
	{
		$this->db->select("`id`, `name`, `designation`,`image`");
		$this->db->from("tbl_contacts");
		$this->db->where("user_id='".$user_id."'");
		if(isset($_POST['tag_id']) && $_POST['tag_id']!="")
		{
			$this->db->where("user_id='".$user_id."' and (tag_ids like '%".$_POST['tag_id']."%' || tag_ids like '%,".$_POST['tag_id']."%' || tag_ids like '%".$_POST['tag_id'].",%')");
		}
		$this->db->order_by("name", "asc");
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function getcontactbyid($user_id)
	{
		$column="`id`, `tag_ids`, `name`, `designation`, `company_name`, `address`, `image`,`notes`";
		$this->db->select($column);
		$this->db->from("tbl_contacts");
		$this->db->where("id='".$_POST['contact_id']."' and user_id='".$user_id."'");
		
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$result['data']=$query->row_array();
			foreach($result as $key=>$r)
			 {
			  $result[$key]['image']=site_url("uploads/contacts/".$r['image']);
			 }
			 
			//for tag details
			if ($result['data']['tag_ids'])
			{
				$this->db->select("`tag`");
				$this->db->from("tbl_tags");
				$this->db->where("id in (".$result['data']['tag_ids'].")");
				$query1=$this->db->get();
				$result['data']['tag_details']=$query1->result_array();
			}
			else
			{
				$result['data']['tag_details']=[];
			}
			
			//for contact no details
			$this->db->select("`type`,`contact_no`, `is_primary`");
			$this->db->from("tbl_contact_nos");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query2=$this->db->get();
			$result['data']['contactno_details']=$query2->result_array();
			
			//for email details
			$this->db->select("`email`, `is_primary`");
			$this->db->from("tbl_emails");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query3=$this->db->get();
			$result['data']['email_details']=$query3->result_array();
			
			//for social media link details
			$this->db->select("`social_media`, `link`");
			$this->db->from("tbl_social_media_link");
			$this->db->where("contact_id='".$_POST['contact_id']."'");
			$query3=$this->db->get();
			$result['data']['social_media_link_details']=$query3->result_array();
			 return $result;					 
		}
		return false;
	}
	
	function getdatabytoken($token)
	{
		$this->db->select("id");
		$this->db->from("tbl_user_mst");
		$this->db->where("token='".$token."'");
		$query=$this->db->get();
		if ( $query->num_rows() > 0 )
		{
			$row=$query->row_array();
			return $this->getdatabyuser($row["id"]);
		}
		return false;
	}
	
	function selectcolbytable($column,$table)
	{
		$this->db->select($column);
		$query=$this->db->get($table);
		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		return false;
	}
	
	function deletedata($condition,$table)
	{
		 $this->db->where($condition);
		  
		if(!$this->db->delete($table))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function mail_exists($condition,$table)
	{
		$this->db->where($condition);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}
		
	function sendEmail($email,$sub,$msg)
	{
		$this->load->library('email');
	    $this->email->clear();
		$this->email->from('ei.muniruddin.malek@gmail.com', 'Test Mail');
		$this->email->to($email);
		$this->email->subject($sub);
		$this->email->message($msg);
	
	    if ($this->email->send()) {
	        return true;
	    } else {
	        show_error($this->email->print_debugger());
	    }
	}
	
	function forgotPassword()
	{
			$Q=$this->db->query('select user_id from tbl_emails where email="'.$_POST['email'].'" and is_primary=1 and contact_id=0');
			if($Q->num_rows()>0)
			{
				$row=$Q->row_array();
				$user_id=$row['user_id'];
				$newpassword=$this->rand_Pass();
				$data = array(
		            'password' 				=> md5($newpassword),
		            'modified_datetime'		=> date('Y-m-d h:i:s'),
		            'token'					=> md5($user_id."$<!&".time())
		        );
				$this->db->where('id',$user_id);
				$this->db->set($data);
				$this->db->update("tbl_user_mst");
				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='color: #484343;'>
					<tr>
						<td class='mail_title' style='background:#f76237;color:#fff;font-weight:bold;padding:3px;'>
							Your Bussiness Card App Password has been change
						</td>
					</tr>
					<tr>
						<td style='border-width: 1px; border-color: #e5e5e5; border-style: solid;'>
						<table cellpadding='5' cellspacing='0'  width='600px' align='center' style='font-family: Arial; background : url(../image/back_body.jpg);'>
							<tr>
								<td colspan='2'><p style='font-size:14;'>We've received a request to reset the password for this email address.</p></td>
							</tr>
							<tr>
								<td colspan='2'><p style='font-size:14;'>Your New Password: ".$newpassword."</p></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						
					</tr>
				</table>";

				//echo $message;die;

				$config = array(
					'mailtype' => 'html',
					'charset' => 'utf-8',
					'smtp_host'=>'ssl://smtp.googlemail.com',
					'smtp_user'=>'ei.muniruddin.malek@gmail.com',
					'smtp_pass'=>'munir@2017',
					'smtp_port'=>465,
					'crlf'     =>"\r\n",
					'newline'  => "\r\n",
					'wordwrap' => TRUE
				);
				$this->load->library('email');
				$this->email->initialize($config);
				$subject='Password Reset';
				$to=$_POST['email'];
				$from='ei.muniruddin.malek@gmail.com';
				$this->email->from($from);
				$this->email->subject($subject);
				$this->email->to($to);
				$this->email->message($message);
				$this->email->send();
				return true;
			}
			else
			{
				return false;
			}
	}
	
	function checkExpireToken($id,$token)
	{
		$Q=$this->db->query('select expire from tbl_tokens where md5(user_id)="'.$id.'" and md5(token)="'.$token.'"');
		$data=$Q->row_array();
		if($data['expire']>time())
		{
			return 1;
		}
		else
		{
			$this->db->where('md5(user_id)',$id);
			$this->db->delete('tbl_tokens');
			return 0;
		}
	}

	function updatePassword($id)
	{
		$data = array(
			'password' => md5($_POST['password'])
		);
		$this->db->where('md5(id)', $id);
		$this->db->update('tbl_user_mst', $data);
		$this->db->where('md5(user_id)',$id);
		$this->db->delete('tbl_tokens');
	}

	function changedateformate($date,$format)
	{
		$date = str_replace('/', '-', $date);
		return date($format, strtotime($date));
	}
	
	function value_exists($condition,$table)
	{
		$this->db->where($condition);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        $row=$query->row_array();
			return $row["id"];
	    }
	        return false;
	}
	
}

?>
