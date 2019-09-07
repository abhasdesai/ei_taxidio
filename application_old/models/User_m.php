<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model {

		public function __construct()
		{
			parent::__construct();
		}

		public function login()
		{
			$this->db->select('tbl_users.id,tbl_login.role_id,tbl_users.first_name,tbl_users.last_name');
			$this->db->from('tbl_users');
			$this->db->join('tbl_login','tbl_login.user_id=tbl_users.id');
			$this->db->where('tbl_login.username',$this->input->post('username'));
			$this->db->where('tbl_login.password',$this->hash($this->input->post('password')));
			$Q=$this->db->get();
			if($Q->num_rows()>0)
			{

				$this->session->unset_userdata('fuserid');

				$data=$Q->row_array();

				$arr=array(
					'id'=>$data['id'],
					'role_id'=>$data['role_id'],
				);
				$this->session->set_userdata($arr);
				if(isset($_POST['remember']))
				{
					setcookie('usernamelogin',$_POST['username']);
                    setcookie('passwordlogin',$_POST['password']);
				}
				else
				{
					if($this->input->cookie('passwordlogin') && $this->input->cookie('passwordlogin')!='' && $this->input->cookie('usernamelogin') && $this->input->cookie('usernamelogin')!='')
					{
						setcookie('usernamelogin','');
						setcookie('passwordlogin','');

					  }
			  }
				return 1;
			}
			else
			{
				return 0;
			}
		}

		public function loggedin()
		{
			if($this->session->userdata('id'))
			{
				return '1';
			}
			else
			{
				return '0';
			}

		}

		function logout()
		{
			$this->session->sess_destroy();
		}

		public function hash($string)
		{
				return hash('sha512',$string.config_item('encryption_key'));
		}

		function checkCurrentPassword()
		{
			$Q=$this->db->query('select id from tbl_login where user_id="'.$this->session->userdata('id').'" and password="'.$this->hash($_POST['currentpassword']).'"');
			return $Q->num_rows();
		}

		function updateCurrentPassword()
		{
			$data=array(
			'password'=>$this->hash($_POST['password'])
			);

			$this->db->where('user_id',$this->session->userdata('id'));
			$this->db->update('tbl_login',$data);
		}

		function forgotPassword()
		{
				$Q=$this->db->query('select id from tbl_users where email="'.$_POST['email'].'"');
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
					$emailurl=site_url('admin/reset_password').'/'.md5($data['id']).'/'.md5($uniq);
					$message = "<table cellpadding='0' cellspacing='0' width='600px'>
						<tr>
							<td>
								<img src='".base_url()."image/emailtop.jpg'>
							</td>
						</tr>
						<tr>
							<td style='border-width: 1px; border-color: #e5e5e5; border-style: solid;'>
							<table cellpadding='5' cellspacing='0'  width='600px' align='center' style='font-family: Arial; background : url(../image/back_body.jpg);'>
								<tr>
									<td colspan='2'><p style='font-size:14;'>We've received a request to reset the password for this email address.</p></td>
								</tr>
								<tr>
									<td colspan='2'><p style='font-size:14;'>To reset the password please click on this link or copy and paste this link into your browser.(link expires in 12 hour).</p></td>
								</tr>
								<tr>
									<td colspan='2'><p style='font-size:13;'><a href='".$emailurl."' target='_blank'>".$emailurl."</a></p></td>
								</tr>
								<tr>
									<td colspan='2'><p style='font-size:14;'>This link takes you to a secure page where you can change your password.</p></td>
								</tr>
								<tr>
									<td colspan='2'><p style='font-size:14;'>If you dont want to reset your password then ignore this email Your password will not be reset.</p></td>
								</tr>
							 ";

							$message.="
							</table>
							</td>
						</tr>
						<tr>
							<td>
								<img src='".base_url()."image/emailbtm.jpg'>
							</td>
						</tr>
					</table>";

					echo $message;die;

					$config = array(
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'smtp_host'=>'smtp.emailsrvr.com',
						'smtp_user'=>'system@alakik.net',
						'smtp_pass'=>'TimeDesk',
						'smtp_port'=>'25',
						'crlf'     =>"\r\n",
						'newline'  => "\r\n",
						'wordwrap' => TRUE
					);
					$this->load->library('email');
					$this->email->initialize($config);
					$subject='Password Reset Help';
					$to=$_POST['email'];
					$from='admin@gmail.com';
					$this->email->from($from);
					$this->email->subject($subject);
					$this->email->to($to);
					$this->email->message($message);
					$this->email->send();
					return 1;
				}
				else
				{
					return 0;
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

		function updatePassword()
		{
			$data = array(
				'password' => $this->hash($_POST['password'])
			);
			$this->db->where('md5(user_id)', $this->input->post('user_id'));
			$this->db->update('tbl_login', $data);
			$this->db->where('md5(user_id)',$this->input->post('user_id'));
			$this->db->delete('tbl_tokens');
		}


		function getUserDetails($id)
		{
			 $Q=$this->db->query('select *,(select name from tbl_worlds_countries where id=tbl_front_users.country_id) as country_name from tbl_front_users where id="'.$id.'" limit 1');
			 return $Q->row_array();
		}
}
