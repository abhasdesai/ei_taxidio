<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Packages_fm');
		$this->load->helper('Crypto');
	}
	
	public function purchase()
	{
		//Directly Update User payment status and balance
		//echo "<pre>";print_r($_POST);die;
		$package_details = $this->Packages_fm->getDetailsById($this->input->post('package_id'));
		
		/*$this->Packages_fm->updateUserPaymentStatus();
		$cityId = $this->input->post('city_id');
		$cityDetails = $this->Home_fm->getLatandLongOfCity(md5($cityId));
		$cityDetails = $this->Home_fm->getLatandLongOfCity($cityId);*/
		/*echo "<pre>";print_r($cityDetails);
		echo "Download Travel Guide";
		die;*/
		/*if($cityDetails['travelguide']!='')
		{
			$fileName = $cityDetails['travelguide'];
			$this->load->helper('download');
			$path = FCPATH.'/userfiles/'.$fileName;

			ob_clean();

			$data = file_get_contents($path); // Read the file's contents
			$name = $fileName;
			
			$this->Packages_fm->updateUserBalance($cityId);
			
			force_download($name, $data, NULL);
		}
		exit();*/
		$package_details = $this->Packages_fm->getDetailsById($this->input->post('package_id'));
		//echo "<pre>";print_r($package_details);die;
		$data['package_details'] = $package_details;
		$data['webpage'] = "Payment";
		$this->session->set_userdata('user_pck_cityid',$this->input->post('city_id'));
		$this->session->set_userdata('user_pck_id',$this->input->post('package_id'));
		$this->session->set_userdata('user_pck_qty',$this->input->post('bal'));
		$this->session->set_userdata('user_pck_price',$this->input->post('amount'));
		$data['main'] = "payment/index";
		$this->load->view('templates/innermaster',$data);
	}
	
	public function ccavRequestHandler()
	{
		$this->load->helper('Crypto');
		$this->load->view('payment/ccavRequestHandler');
	}
	
	function returnurl()
	{
		$this->load->model('Home_fm');
		$email = $this->session->userdata('email');
		$this->load->helper('Crypto');
		$workingKey=$this->config->item('workingKey');		//Working Key should be provided here.
		$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
		$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
		$order_status="";
		$decryptValues=explode('&', $rcvdString);
		$dataSize=sizeof($decryptValues);
		echo "<center>";

		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
			if($i==3)	$order_status=$information[1];
		}



		if($order_status==="Success")
		{			
			//Update User payment details and available balance
			$this->Packages_fm->updateUserPaymentStatus($decryptValues);
			$signup_flag_data = $this->Home_fm->checkReferSignup($email);
			$package_details = $this->Packages_fm->getCurrentlyPurchasedPackageDetails($this->session->userdata('fuserid'));
			if(!(empty($package_details)))
			{
				$this->load->model('Account_fm');
				$user_details = $this->Account_fm->getUserDetails();
				$this->Packages_fm->sendPurchaseEmailToUser($user_details);
			}
			
			redirect('myaccount');
		}
		else if($order_status==="Aborted")
		{
			echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
		
		}
		else if($order_status==="Failure")
		{
			echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
			$this->session->set_flashdata('Thank you for shopping with us.However,the transaction has been declined.');
			$this->Packages_fm->updateUserPaymentStatus($decryptValues);

			$signup_flag_data = $this->Home_fm->checkReferSignup($email);
			
			$package_details = $this->Packages_fm->getCurrentlyPurchasedPackageDetails($this->session->userdata('fuserid'));
			if(!(empty($package_details)))
			{
				$this->load->model('Account_fm');
				$user_details = $this->Account_fm->getUserDetails();
				$this->Packages_fm->sendPurchaseEmailToUser($user_details,$package_details);
			}
			
			redirect('myaccount');
		}
		else
		{
			echo "<br>Security Error. Illegal access detected";
		
		}

		echo "<br><br>";

		echo "<table cellspacing=4 cellpadding=4>";
		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
		    	echo '<tr><td>'.$information[0].'</td><td>'.urldecode($information[1]).'</td></tr>';
		}

		echo "</table><br>";
		echo "</center>";
	}
}
