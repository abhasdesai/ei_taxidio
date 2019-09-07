<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Cms extends REST_Controller 
{	

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Cms_wm');
		$this->load->helper('app');
	}

	function privacy_policy_get()
	{
		$data=$this->Cms_wm->getCms(3);
		$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function discover_taxidio_get()
	{
		$data=$this->Cms_wm->getCms(4);
		$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function faq_get()
	{
		$data=$this->Cms_wm->getAllFaqs();
		$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

	function destination_get()
	{
		$data=$this->Cms_wm->getAllCountry();
		$message=array(
				'errorcode' =>1,
				'data'	=>$data
				);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}


}
?>