<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cms extends Front_Controller {

	function faq()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'faq';
		$data['main'] = 'cms/faq';
		$data['faq']=$this->Cms_fm->getAllFaqs();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function pricing()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'pricing';
		$data['main'] = 'cms/pricing';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}

	function team()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'team';
		$data['main'] = 'cms/team';
		$data['team']=$this->Cms_fm->getAllTeams();
		$data['cms']=$this->Cms_fm->getCms(1);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}

	function membership()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'membership';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(9);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}

	

	function contactus()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'contactus';
		$data['main'] = 'cms/contactus';
		$data['settings']=$this->Cms_fm->getSettings();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}	

	function terms_and_condition()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'terms_and_condition';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(2);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');	
	}

	function media()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'media';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(5);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function career()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'career';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(1);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function discover_taxidio()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'discover_taxidio';
		$data['main'] = 'cms/discover';
		$data['cms']=$this->Cms_fm->getCms(4);
		$data['meta_title']='Your Vacation Trip Planner :: Discover Taxidio';
		$data['meta_keywords']='vacation trip planner,my trip planner,trip planners travel route planner,trip route planner,online trip planner,online route planner,free trip planner,trip planner online,holiday trip planner,travel planner online';
		$data['meta_description']='Taxidio is a free online holiday trip planner that bridges the gap between trip planning & execution. Find out more about our online travel route planner & its features.';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function privacy_policy()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'privacy_policy';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(3);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function user_content()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'user_content';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(8);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function cookie()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'cookie';
		$data['main'] = 'cms/content';
		$data['cms']=$this->Cms_fm->getCms(7);
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function postcontactus()
	{
		if($this->input->post('contactsubmit'))
		{
		  $this->load->library('form_validation');
		  $this->load->library('email');
		  $this->load->library('MY_Email');	 
		  $this->form_validation->set_rules('name','Name','required',array('required' => 'You must provide your %s.')); 
		  $this->form_validation->set_rules('email','Email','required|valid_email',array('required' => 'You must provide your %s.'));
		  $this->form_validation->set_rules('phone','Phone','required',array('required' => 'You must provide your %s.'));
		  $this->form_validation->set_rules('message','Message','required',array('required' => 'You must provide your %s.')); 


		  if($this->form_validation->run()==FALSE)
		  {
		  	 $this->contactus();
			 $this->session->set_flashdata('error', 'Please provide all the inputs.');
		  }
		  else
		  {

		  	$this->Cms_fm->sendContactUsEmail();
		  	$this->Cms_fm->sendFeedbackEmailToUser();
			$this->session->set_flashdata('success', 'Thanks for the inquiry.We will get back to you soon');
			redirect('contactus');
		  }
		}
		else
		{
			redirect('contactus');
		}
		 
	}


	function credit()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'credit';
		$data['main'] = 'cms/credit';
		$data['category']=1;
		$this->load->vars($data);
		$this->load->view('templates/innermaster');

	}


	function credit_ajax()
	{
		if($this->input->is_ajax_request())
		{
			$data['categoryid']=$categoryid=$_POST['category'];
			$table='';
            if($categoryid==1)
			{
				$table='tbl_city_paidattractions';
				$field='attraction_name as name';
			}
			else if($categoryid==2)
			{
				$table='tbl_city_relaxationspa';
				$field='ras_name as name';
			}
			else if($categoryid==3)
			{
				$table='ran_name';
				$field='attraction_name as name';
			}
			else if($categoryid==4)
			{
				$table='tbl_city_sports_adventures';
				$field='adventure_name as name';
			}
			else if($categoryid==5)
			{
				$table='tbl_city_stadiums';
				$field='stadium_name as name';
			}

			if($table!='')
			{
				$start_row=$this->uri->segment(3);
	            if(trim($start_row)=='')
	            {
	                    $start_row=0;
	            }
				$this->load->library('pagination');
	            $config["base_url"] = site_url('cms/credit_ajax');
	            
	            $config["total_rows"] = $this->Cms_fm->countCategory($table);
	            $config["full_tag_open"] = "<ul class='pagination-custom'>";
	            $config["full_tag_close"] = "</ul>";
	            $config["num_tag_open"] = "<li class='pagination-item-custom'>";
	            $config["num_tag_close"] = "</li>";
	            $config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
	            $config["cur_tag_close"] = "</a></span></li>";
	            $config['prev_link'] = 'Previous';
	            $config['next_link'] = 'Next';
	            $config["prev_tag_open"] = "<li class='pagination-item--wide first'>";
	            $config["prev_tag_close"] = "</li>";
	            $config["next_tag_open"] = "<li class='pagination-item--wide last'>";
	            $config["next_tag_close"] = "</li>";
	            $config["first_link"] = "<li style='float:left'>&lsaquo; First";
	            $config["first_link"] = "</li>";
	            $config["last_link"] = "<li>Last &rsaquo;";
	            $config["last_link"] = "</li>";
	            $config['per_page'] = 15;
	            $this->pagination->initialize($config);
			        $data["credits"] = $this->Cms_fm->getAllCreditsOfCategory($table,$field,$config["per_page"],$start_row);
			        $data["pagination"]=$this->pagination->create_links();
			        $_html=$this->load->view('cms/credit_ajax', $data,TRUE);
			        echo $_html;
			}
			else
			{
				echo "1";
			}

		    
		}	            
	}


}

?>
