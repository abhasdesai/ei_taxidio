<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Temp extends Temp_Controller 
{

		function index()
		{
			$this->load->view('temp');	
		}

		function login()
		{
			if(isset($_POST['betausername']) && isset($_POST['betapassword']))
			{
				if($_POST['betausername']=='taxidio_beta' && $_POST['betapassword']=='6wjsn7zx3')
				{
					$arr=array(
							'tempid'=>1,
							'temproleid'=>1,
						);
					$this->session->set_userdata($arr);
					redirect('home');
				}
				else
				{
					$this->session->set_flashdata('error','Invalid Username/Password');
					redirect(site_url());
				}
			}
			else
			{
				$this->session->set_flashdata('error','Invalid Username/Password');
				redirect(site_url());
			}

		}

}
