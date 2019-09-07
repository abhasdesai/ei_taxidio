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
		//print_r($result);die;
		/*$Q=$this->db->query('select id from tbl_device_master where udid like "'.$udid.'" and device_type="'.$device_type.'"');
		$row=$Q->row_array();*/
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
/*
	function deviceLogin($user_id,$device_id)
	{
		$data=array(
				'last_login_id' => $user_id,
				'last_access'	=> date('Y-m-d H:i:s')
				);
		$this->db->where('id ="'.$device_id.'"');
		$this->db->update('tbl_device_master',$data);
	}
*/
	function getUserDetails()
	{
		$Q=$this->db->query('select * from tbl_front_users where id="'.$_POST['userid'].'"');
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
		//$this->load->library('email');
		//$this->load->library('MY_Email_Other');
		//$this->load->helper('app');
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
				'isloggedin'=>1
			);

		$this->db->insert('tbl_front_users',$data);
		$userid=$this->db->insert_id();
		deviceLogin($userid,$device_id[0]['id']);

		$userArray=array(
					'userid'=>$userid,
					'username'=>ucwords($_POST['username']),
					'useremail'=>$_POST['useremail'],
					'last_login'=>$datetime,
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
				'userimage'=>$_POST['userimage'],
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
				'last_login'=>$datetime,
				'userimage'=>$_POST['userimage'],
				'issocial'=>1,
				'askforemail'=>$returnData['isemail']
			);

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
				'userimage'=>$_POST['userimage'],
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

			deviceLogin($userid,$device_id[0]['id']);

			$userArray=array(
				'userid'=>$userid,
				'username'=>ucwords($_POST['username']),
				'useremail'=>$_POST['useremail'],
				'last_login'=>$datetime,
				'userimage'=>$_POST['userimage'],
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

			$userimage="";
			if(isset($_POST['userimage']) && $_POST['userimage']!='')
			{
				$userimage=$_POST['userimage'];
			}

			$data=array(
				'userimage'=>$userimage,
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
				'last_login'=>$datetime,
				'userimage'=>$_POST['userimage'],
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


			$user_image='';
			if(isset($_POST['userimage']) && $_POST['userimage']!='')
			{
				$user_image=$_POST['userimage'];
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
				'userimage'=>$user_image,
				'phone'=>'',
				'gender'=>$_POST['usergender'],
				'logintype'=>1,
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
				'email'=>$uemail,
				'last_login'=>$datetime,
				'userimage'=>$user_image,
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

			$this->deviceLogin($data['id'],$device_id[0]['id']);

			$sessionArray=array(
					'userid'=>$data['id'],
					'name'=>$data['name'],
					'email'=>$data['email'],
					'userimage'=>$data['userimage'],
					'last_login'=>$datetime,
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
		$Q=$this->db->query('select id from tbl_front_users where id="'.$_POST['userid'].'" and password="'.$this->hash($_POST['cpassword']).'" limit 1');
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
		$message=$this->load->view('myaccount/changepasspassword_template',$data,true);
		
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

}

/* End of file User_wm.php */
/* Location: ./application/models/webservices_models/User_wm.php */
