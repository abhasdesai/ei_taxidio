<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class User_wm extends CI_Model {

	function deviceUpdate()
	{
		//$this->load->helper('app');
		$udid=$_POST['device_udid'];
		$version=$_POST['device_version'];
		$device_type=$_POST['device_type'];
		$datetime=date('Y-m-d H:i:s');
		$result=selectcolbycondition('id','tbl_device_master','udid like "'.$udid.'" and device_type="'.$device_type.'"');
		
		if($result==false)
		{
			$data=array(
				'udid'		 => $udid,
				'version'	 => $version,
				'device_type'=> $device_type,
				'created'	 => $datetime,
				'last_access'=> $datetime
				);
			$this->db->insert('tbl_device_master',$data);
			return 1;
		}
		else
		{
			$data=array(
				'version'	 => $version,
				'last_access'=> $datetime
				);
			$this->db->where('id ="'.$result[0]['id'].'"');
			$this->db->update('tbl_device_master',$data);
			return 1;
		}
	}

	function getUserDetails()
	{
		$Q=$this->db->query('select id,name as username,email as useremail,dob,phone,gender,userimage,socialimage,passport,country_id from tbl_front_users where id="'.$_POST['userid'].'"');
		return $Q->row_array();
	}

	function getCountries()
	{
		$data=array();
		$Q=$this->db->query('select id,name from tbl_worlds_countries order by name asc');
		return $Q->result_array();
	}


	function signupUser()
	{
		$condition='udid like "'.$_POST['device_udid'].'" and device_type="'.$_POST['device_type'].'"';
		$device_id=selectcolbycondition('id','tbl_device_master',$condition);

		$datetime=date('Y-m-d H:i:s');
		$data=array(
				'name'=>ucwords($_POST['username']),
				'email'=>$_POST['useremail'],
				'password'=>$this->hash($_POST['userpassword']),
				'isactive'=>1,
				'created'=>$datetime,
				'last_login'=>$datetime,
				'userimage'=>'',
				'phone'=>'',
				'gender'=>0,
				'facebookid'=>'',
				'googleid'=>'',
				'logintype'=>1,//now not important
				'country_id'=>0,
				'dob'=>'0000-00-00',
				'isemail'=>0,
				'device_id'=> $device_id[0]['id'],
				'last_login'=>$datetime,
				'isloggedin'=>1,
				'socialimage'=>''
			);

		$this->db->insert('tbl_front_users',$data);
		$userid=$this->db->insert_id();
		deviceLogin($userid,$device_id[0]['id']);

		$userArray=array(
					'userid'=>$userid,
					'username'=>ucwords($_POST['username']),
					'useremail'=>$_POST['useremail'],
					'userimage'=>'',
					'issocial'=>0,
					'askforemail'=>0
				);

		$data['taxidio']=$this->getSettings();

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
		$this->email->clear();
		$this->email->initialize($config);
		$_SESSION['name']=$userArray['username'];
		$message=$this->load->view('register_template',$data,true);
		$from='ei.muniruddin.malek@gmail.com';//noreply@taxidio.com
		$to=$userArray['useremail'];
		$subject='Welcome to Taxidio’s World of Travel.';
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
		unset($_SESSION['name']);
		return $userArray;
	}

	function googleLogin()
	{
		$Q=$this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where googleid="'.$_POST['googleid'].'"');
		
		$condition='udid like "'.$_POST['device_udid'].'" and device_type="'.$_POST['device_type'].'"';
		$device_id=selectcolbycondition('id','tbl_device_master',$condition);

			$datetime=date('Y-m-d H:i:s');
		if($Q->num_rows()>0)
		{
			$returnData=$Q->row_array();

			$data=array(
				'socialimage'=>$_POST['socialimage'],
				'device_id'=> $device_id[0]['id'],
				'last_login'=>$datetime
				);

			$this->db->where('id',$returnData['id']);
			$this->db->update('tbl_front_users',$data);

			deviceLogin($returnData['id'],$device_id[0]['id']);

			$userArray=array(
				'userid'=>$returnData['id'],
				'username'=>ucwords($returnData['name']),
				'useremail'=>$returnData['email'],
				'socialimage'=>$_POST['socialimage'],
				'issocial'=>1,
				'askforemail'=>$returnData['isemail']
			);

		}
		else
		{

			$qrcheck=$this->db->query('select id from tbl_front_users where email="'.$_POST['useremail'].'" limit 1');
			if($qrcheck->num_rows()>0)
			{
				$uid=$qrcheck->row_array();
				$updatedata=array(
					'isactive'=>1,
					'googleid'=>$_POST['googleid'],
					'socialimage'=>$_POST['socialimage'],
					'gender'=>$_POST['usergender'],
					'isemail'=>0,
					'isloggedin'=>1
				);
				$this->db->where('id',$uid['id']);
				$this->db->update('tbl_front_users',$updatedata);
				$userid=$uid['id'];
			}
			else
			{
				$insertdata=array(
					'name'=>$_POST['username'],
					'email'=>$_POST['useremail'],
					'logintype'=>2,// not important 
					'isactive'=>1,
					'created'=>$datetime,
					'password'=>'',
					'googleid'=>$_POST['googleid'],
					'facebookid'=>'',
					'socialimage'=>$_POST['socialimage'],
					'phone'=>'',
					'gender'=>$_POST['usergender'],
					'country_id'=>0,
					'dob'=>'',
					'isemail'=>0,
					'device_id'=> $device_id[0]['id'],
					'last_login'=>$datetime,
					'isloggedin'=>1
				);
				$this->db->insert('tbl_front_users',$insertdata);
				$userid=$this->db->insert_id();

			}

			deviceLogin($userid,$device_id[0]['id']);
			$userArray=array(
				'userid'=>$userid,
				'username'=>ucwords($_POST['username']),
				'useremail'=>$_POST['useremail'],
				'socialimage'=>$_POST['socialimage'],
				'issocial'=>1,
				'askforemail'=>0
			);
		}
			return $userArray;
	}

	function facebookLogin()
	{
		$Q=$this->db->query('select id,name,email,last_login,userimage,isemail from tbl_front_users where facebookid="'.$_POST['facebookid'].'"');
		
		$condition='udid like "'.$_POST['device_udid'].'" and device_type="'.$_POST['device_type'].'"';
		$device_id=selectcolbycondition('id','tbl_device_master',$condition);

			$datetime=date('Y-m-d H:i:s');
		if($Q->num_rows()>0)
		{
			$returnData=$Q->row_array();

			$socialimage="";
			if(isset($_POST['socialimage']) && $_POST['socialimage']!='')
			{
				$socialimage=$_POST['socialimage'];
			}

			$data=array(
				'socialimage'=>$socialimage,
				'device_id'=> $device_id[0]['id'],
				'last_login'=>$datetime
				);
			$this->db->where('id',$returnData['id']);
			$this->db->update('tbl_front_users',$data);
			
			deviceLogin($returnData['id'],$device_id[0]['id']);

			$userArray=array(
				'userid'=>$returnData['id'],
				'username'=>ucwords($returnData['name']),
				'useremail'=>$returnData['email'],
				'socialimage'=>$_POST['socialimage'],
				'issocial'=>1,
				'askforemail'=>$returnData['isemail']
			);
		}
		else
		{

			if(isset($_POST['useremail']) && $_POST['useremail']!='')
			{
				$uemail=$_POST['useremail'];
				$isemail=0;
			}
			else
			{
				$uemail=$_POST['facebookid'].'@facebook.com';
				$isemail=1;
			}


			$socialimage='';
			if(isset($_POST['socialimage']) && $_POST['socialimage']!='')
			{
				$socialimage=$_POST['socialimage'];
			}

			$insertdata=array(
				'name'=>ucwords($_POST['username']),
				'email'=>$uemail,
				'logintype'=>3,
				'isactive'=>1,
				'created'=>$datetime,
				'password'=>'',
				'googleid'=>'',
				'facebookid'=>$_POST['facebookid'],
				'socialimage'=>$socialimage,
				'phone'=>'',
				'gender'=>$_POST['usergender'],
				'country_id'=>0,
				'dob'=>'',
				'isemail'=>$isemail,
				'device_id'=> $device_id[0]['id'],
				'last_login'=>$datetime,
				'isloggedin'=>1
			);
			$this->db->insert('tbl_front_users',$insertdata);
			$userid=$this->db->insert_id();
			
			deviceLogin($userid,$device_id[0]['id']);

			$userArray=array(
				'userid'=>$userid,
				'username'=>ucwords($_POST['username']),
				'useremail'=>$uemail,
				'socialimage'=>$socialimage,
				'issocial'=>1,
				'askforemail'=>$isemail
			);
		}
			return $userArray;
	}

	function signinUser()
	{
		$Q=$this->db->query('select * from tbl_front_users where email="'.$_POST['useremail'].'" and password="'.$this->hash($_POST['userpassword']).'" limit 1');
		if($Q->num_rows()>0)
		{
			$condition='udid like "'.$_POST['device_udid'].'" and device_type="'.$_POST['device_type'].'"';
			$device_id=selectcolbycondition('id','tbl_device_master',$condition);
			$data=$Q->row_array();
			$datetime=date('Y-m-d H:i:s');
			$this->db->where('id',$data['id']);
			$this->db->update('tbl_front_users',array('last_login'=>$datetime,'device_id'=> $device_id[0]['id']));

			deviceLogin($data['id'],$device_id[0]['id']);

			$sessionArray=array(
					'userid'=>$data['id'],
					'username'=>$data['name'],
					'useremail'=>$data['email'],
					'userimage'=>$data['userimage'],
					'issocial'=>0,
					'askforemail'=>0
				);

			return $sessionArray;
		}
		return false;
	}

	public function hash($string)
	{
			return hash('sha512',$string.config_item('encryption_key'));
	}

	function forgotPassword()
	{
			$Q=$this->db->query('select id from tbl_front_users where email="'.$_POST['useremail'].'"');
			if($Q->num_rows()>0)
			{
				$uniq=uniqid();
				$data=$Q->row_array();
				$udata=array(
					'user_id'=>$data['id'],
					'expire'=>strtotime("+12 hour"),
					'token'=>$uniq
				);

				$this->db->where('user_id',$data['id']);
				$this->db->delete('tbl_tokens');
				$this->db->insert('tbl_tokens',$udata);
				$data['url']=site_url('reset-password').'/'.md5($data['id']).'/'.md5($uniq);
				$message=$this->load->view('forgotpasspassword_template',$data,true);
				
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
				$this->email->clear();
				$this->email->initialize($config);

				$from='ei.muniruddin.malek@gmail.com';//noreply@taxidio.com
				$to=$_POST['useremail'];
				$subject='Password Reset Help';
				$this->email->from($from,'Taxidio');
				$this->email->subject($subject);
				//$this->email->reply_to($from);
				$this->email->to($to);
				$this->email->message($message);
				$this->email->send();
				//echo "sd".$message;die;
				return true;
			}
			else
			{
				return false;
			}
	}

	function check_current_password()
	{
		$Q=$this->db->query('select id from tbl_front_users where id="'.$_POST['userid'].'" and password="'.$this->hash($_POST['currentpassword']).'" limit 1');
		if($Q->num_rows()<1)
		{
			$this->form_validation->set_message('check_current_password','Please provide correct current Password.');
			return FALSE;
		}
		return TRUE;
	}

	function changepassword()
	{
		$this->db->where('id',$_POST['userid']);
		$this->db->update('tbl_front_users',array('password'=>$this->hash($_POST['newpassword'])));
		$udata=selectcolbycondition('name,email','tbl_front_users','id='.$_POST['userid']);
		//$this->session->userdata('name')=
		
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
		$this->email->clear();
		$this->email->initialize($config);

		$_SESSION['name']=$udata[0]['name'];
		$message=$this->load->view('myaccount/changepasspassword_template',$data="",true);
		
		$from='ei.muniruddin.malek@gmail.com';//noreply@taxidio.com
		$to=$udata[0]['email'];
		$subject='Password Changed';
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
		unset($_SESSION['name']);
	}

	function getSettings()
	{
		$Q=$this->db->query('select * from tbl_settings where id=1');
		return $Q->row_array();
	}

	function editUser()
	{
		$dob = implode("-", array_reverse(explode("/", $_POST['dob'])));
		if(isset($_POST['issocial']) && $_POST['issocial']!=1)
		{
			$data=array(
				'name'=>ucwords($_POST['username']),
				'email'=>$_POST['useremail'],
				'passport'=>$_POST['passport'],
				'country_id'=>$_POST['country_id'],
				'dob'=>$dob,
				'phone'=>$_POST['phone'],
				'gender'=>$_POST['gender']
			);
			if (isset($_FILES['userimage']) && !empty($_FILES['userimage']['name'])) {
				$image=$this->img_save_to_file_profile();
				$data['userimage']=$image;
			}
		}
		else
		{
			$data=array(
				'passport'=>$_POST['passport'],
				'country_id'=>$_POST['country_id'],
				'dob'=>$dob,
				'phone'=>$_POST['phone'],
				'gender'=>$_POST['gender']
			);
		}

		$this->db->where('id',$_POST['userid']);
		$this->db->update('tbl_front_users',$data);

		$user=getrowbycondition("*","tbl_front_users","id=".$_POST['userid']);
		$sessionArray=array(
				'userid'=>$user['id'],
				'username'=>$user['name'],
				'useremail'=>$user['email'],
				'issocial'=>0,
				'askforemail'=>0
			);

		if(isset($_POST['issocial']) && $_POST['issocial']!=1)
		{
			$sessionArray['userimage']=$user['userimage'];
		}
		else
		{
			$sessionArray['socialimage']=$user['socialimage'];
		}
		return $sessionArray;
	}

	function updateEmail()
	{
		$data=array(
				'email'=>$_POST['useremail'],
				'isemail'=>0
			);
		$this->db->where('id',$_POST['userid']);
		$this->db->update('tbl_front_users',$data);

		$user=getrowbycondition("*","tbl_front_users","id=".$_POST['userid']);
		$sessionArray=array(
				'userid'=>$user['id'],
				'username'=>$user['name'],
				'useremail'=>$user['email'],
				'socialimage'=>$user['socialimage'],
				'issocial'=>0,
				'askforemail'=>0
			);

		return $sessionArray;
	}

	function check_email($email)
	{
		$Q=$this->db->query('select id from tbl_front_users where email="'.$email.'" and id!="'.$_POST['userid'].'"');
		if($Q->num_rows()>0)
		{
			$this->form_validation->set_message('check_email','That Email already exists.');
			return FALSE;
		}
		return TRUE;
	}

	/*Image Upload*/

	function img_save_to_file_profile() {
		//print_r($_FILES['userimage']);die;
		
			$config['upload_path'] = './userfiles/userimages/medium/';
			$config['allowed_types'] = '*';
			$config['max_size'] = '';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = false;
			$config['max_width'] = '';
			$config['max_height'] = '';
			$config['file_name'] = time().'.jpg';
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('userimage'))
			{
				 return "";
			}
			else
			{
				$image = $this->upload->data();
				
				$config['image_library'] = 'gd2';
				$config['source_image'] = './userfiles/userimages/medium/' . $image['file_name'];
				$config['new_image'] = './userfiles/userimages/small/';
				$config['maintain_ratio'] = TRUE;
				$config['overwrite'] = false;
				$config['width'] = 150;
				$config['height'] = 150;
				$config['master_dim'] = 'width';
				$config['file_name'] = time();
				$this->load->library('image_lib', $config); //load library
				$this->image_lib->resize(); //do whatever specified in config

				$this->removeImage();//remove old image

				return $image['file_name'];
			}
		
	}

	function removeImage()
	{
		$Q=$this->db->query('select userimage from tbl_front_users where id="'.$_POST['userid'].'" limit 1');

		$imagedata=$Q->row_array();
		if($imagedata['userimage']!='')
		{
			if(file_exists(FCPATH.'userfiles/userimages/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/'.$imagedata['userimage']);
			}

			if(file_exists(FCPATH.'userfiles/userimages/medium/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/medium/'.$imagedata['userimage']);
			}

			if(file_exists(FCPATH.'userfiles/userimages/small/'.$imagedata['userimage']))
			{
				unlink(FCPATH.'userfiles/userimages/small/'.$imagedata['userimage']);
			}
		}
	}
}

/* End of file User_wm.php */
/* Location: ./application/models/webservices_models/User_wm.php */
