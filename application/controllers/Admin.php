<?php
class Admin extends CI_Controller
{

	public function index()
	{

		if($this->session->userdata('id') && $this->session->userdata('role_id')==1)
		{
			redirect('admins/dashboard');
		}
		$data['webpagename']='login';
		$data['main']='admins/login';
		$this->load->vars($data);
		$this->load->view('admins/templates/loginmaster');
	}
	
	public function login()
	{
		$this->load->model('User_m');
		$login=$this->User_m->login();
		if($login==1)
		{
			redirect('admins/dashboard');
		}
		else
		{
			$this->session->set_flashdata('error','Invalid username/password combination');
			redirect('admin');
		}
	}
	
	public function forgotPassword()
	{
		$this->load->model('User_m');
		$check=$this->User_m->forgotPassword();
		echo $check;
	}
	
	function reset_password($id,$token)
	{
		$this->load->model('User_m');
		$check=$this->User_m->checkExpireToken($id,$token);
		if($check==0)
		{
			redirect('admin/pagenotfound');
		}
		else
		{
			$data['webpagename']='reset_password';
			$data['main']='admins/reset_password';
			$data['id']=$id;
			$this->load->vars($data);
			$this->load->view('admins/templates/loginmaster');
		}
	}
	
	function pagenotfound()
	{
			$data['webpagename']='pagenotfound';
			$data['main']='admins/pagenotfound';
			$this->load->vars($data);
			$this->load->view('admins/templates/loginmaster');
	}
	
	function update_reset_password()
	{
		$this->load->model('User_m');
		if($this->input->post('btnsubmit'))
		{
			$this->User_m->updatePassword();
			$this->session->set_flashdata('success','Your password has been updated.');
			redirect('admin');
		}
	
	}
	
}
?>
