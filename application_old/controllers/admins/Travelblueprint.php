<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Travelblueprint extends Admin_Controller {

	public function __construct()
  {
		parent::__construct();
	}

    public function index()
    {
	     $data['webpagename'] = 'Travelblueprint';
		 $data['main'] = 'admins/adminfiles/Travelblueprint/index';
		 $data['section'] = 'Travel Blueprint';
		 $data['page'] = 'Travel Blueprint';
         $data['countries']=$this->Travelblueprint_m->getAllCountries();
         $data['blueprints']=$this->Travelblueprint_m->getAllBlueprints();
         $this->load->vars($data);
		 $this->load->view('admins/templates/innermaster');
	}


	function store()
	{

		$errors=$this->Travelblueprint_m->store();
		$flag=0;
		for($i=0;$i<5;$i++)
		{
			if($errors[$i]!='')
			{
				$flag=1;
			}
		}	
		if($flag==0)
		{
			$this->session->set_flashdata('success', 'Transaction Successful.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Some image might not be uploaded.');
		}
		redirect('admins/Travelblueprint');

	}


}
