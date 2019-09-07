<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Account extends User_Controller {

	public function __construct() {
		parent::__construct();
	}

	function changepassword()
	{
		if($this->session->userdata('issocial')==1)
		{
			redirect('myaccount');
		}

		if($this->input->post('btnsubmit'))
		{

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
			$this->form_validation->set_rules('cpassword', 'cpassword', 'trim|required|min_length[6]|callback_check_current_password');
			$this->form_validation->set_rules('newpassword', 'New Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('rnewpassword', 'Re-type password', 'trim|required|matches[newpassword]',array('matched'=>'Your new password and re-typed password don’t match. Please try again.'));
			
			$this->form_validation->set_message('matches', 'Your new password and re-typed password don’t match. Please try again.');

			if($this->form_validation->run()==FALSE)
			{
				$data['webpagename']='changepassword';
				$data['main']='myaccount/changepassword';
				$this->load->vars($data);
				$this->load->view('templates/dashboard/innermaster');
			}
			else
			{
				$this->load->library('email');
				$this->load->library('MY_Email_Other');	
				$this->Account_fm->changepassword();
				$this->session->set_flashdata('success', 'Your Password has been updated.');
				redirect('changepassword');
			}

		}
		else
		{
			$data['webpagename']='changepassword';
			$data['main']='myaccount/changepassword';
			$this->load->vars($data);
			$this->load->view('templates/dashboard/innermaster');
		}

		
	}

	function check_current_password()
	{
		return $this->Account_fm->check_current_password();
	}

	function logout()
	{
		//$this->Account_fm->deleteUserSavedFiles();
		//$this->Account_fm->deleteMyAccountFiles();
		$this->Account_fm->logout();
		redirect(site_url());
	}


	function checkuniqueid($uniqueid,$searchtype)
	{
		//echo FCPATH.'userfiles/'.$searchtype.'/'.$this->session->userdata('randomstring').'/'.$uniqueid;die;
		if(!is_dir(FCPATH.'userfiles/'.$searchtype.'/'.$this->session->userdata('randomstring').'/'.$uniqueid))
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
	}


	function save_itinerary($secretkey)
	{
		$secretkeyid=string_decode($secretkey);
		$type=explode('-',$secretkeyid);
		$uniqueid=$type[2];
		$this->checkuniqueid($uniqueid,$searchtype='files');
		if($type[1]=='single' || $type[1]=='multi')
		{
			if($type[1]=='single')
			{
				$lastid=$this->Account_fm->saveSingleIitnerary($type[0],$uniqueid);
				$encodeid=string_encode($lastid);
			    $this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
				writeTripsInFile();
				redirect('userSingleCountryTrip/'.$encodeid);	
				//redirect($_SERVER['HTTP_REFERER']);
			}
			else
			{
				$this->session->set_flashdata('itisavefail', 'Something went wrong.');
				redirect($_SERVER['HTTP_REFERER']);
			}	
		}
		else
		{
			redirect(site_url());
		}
		 
	}

	function save_multi_itinerary($uniqueid,$secretkey)
	{
		
		$this->checkuniqueid($uniqueid,$searchtype='multicountries');
		$secretkeyid=string_decode($secretkey);
		//echo "<pre>";print_r($secretkeyid);die;	
		if(strpos($secretkeyid,'-')==FALSE)
		{
			$this->session->set_flashdata('itisavefail', 'Something went wrong.');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$lastid=$this->Account_fm->saveMultiIitnerary($uniqueid,$secretkey);
		$encodeid=string_encode($lastid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('multicountrytrips/'.$encodeid);	
		
	}

	function update_single_itinerary($iti)
	{
		$itid=string_decode($iti);
        $result=get_invited_trip_details($itid);
		$this->Account_fm->update_single_itinerary($itid);
        if($result===FALSE){
			$this->deleteItinerary($itid);
			writeTripsInFile();
		}
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSingleCountryTrip/'.$iti);
	}

	function save_searched_itinerary($secretkey)
	{
		$secretkeyid=string_decode($secretkey);
		$type=explode('-',$secretkeyid);
		$secretkey=$type[0];
		$uniqueid=$type[1];
		$this->checkuniqueid($uniqueid,$searchtype='search');
		$lastid=$this->Account_fm->save_searched_itinerary($secretkey,$uniqueid);
		writeTripsInFile();
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSearchedCityTrip/'.string_encode($lastid));
	}   

	function update_searched_itinerary($secretkey)
	{
		$iti_encode=explode('-',string_decode($secretkey));
		$secretkey=$iti_encode[0];
		$iti=$iti_encode[1];
		$result=get_invited_trip_details($iti);
		$this->Account_fm->update_searched_itinerary($secretkey,$iti);
        if($result===FALSE){
			$this->deleteItinerary($itid);
			writeTripsInFile();
		}
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('userSearchedCityTrip/'.string_encode($iti));
	}

	function updatesave_multi_itinerary($iti)
	{
		$itid=string_decode($iti);
        $result=get_invited_trip_details($itid);
		$this->Account_fm->updatesave_multi_itinerary($itid);
        if($result===FALSE){
			$this->deleteItinerary($itid);
			writeTripsInFile();
		}
		$this->session->set_flashdata('itisavesuccess', 'Your trip has been saved.');
		redirect('multicountrytrips/'.$iti);
	}


	function deleteItinerary($itid)
	{
		if(is_dir(FCPATH.'userfiles/savedfiles/'.$itid))
		{
			$files = glob(FCPATH.'userfiles/savedfiles/'.$itid.'/*');
			foreach($files as $file)
			{
			   if(is_file($file))
			   {
			      unlink($file);
			   }	
			}
			rmdir(FCPATH.'userfiles/savedfiles/'.$itid);
		}
	}


}
?>
