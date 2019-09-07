<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Hotels extends REST_Controller 
{	

	public function __construct() {
		parent::__construct();
        $this->load->model('webservices_models/Hotels_wm');
		$this->load->helper('app');
	}

	function get_city_hotels_post()
	{
		$pageno=$_POST['pageno'];
		if(empty((trim($pageno))))
		{
			$start_row=0;
		}
		else
		{
			$start_row=($pageno*5)-5;
		}
		$total_rows = $this->Hotels_wm->countHotels();

		$data=$trips=$this->Hotels_wm->getHotels(5,$start_row);
				
		$message=array(
			'errorcode' =>1,
			'total_page'=>ceil($total_rows/5),
			'data'	=>$data
			);
		$this->set_response($message, REST_Controller::HTTP_OK);
	}

}
?>