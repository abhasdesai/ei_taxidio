<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cms extends Front_Controller {

	function faq()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'faq';
		$data['main'] = 'cms/faq';
		$data['meta_title']='FAQs | All You Need To Know About Taxidio';
		$data['meta_description']='How does Taxidio work? How can I plan a trip using Taxidio? Which destinations does Taxidio cover? You will find all the details you should know about Taxidio on FAQ page of Taxidio.';
		$data['faq']=$this->Cms_fm->getAllFaqs();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}
	
	function contest()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'contest';
		$data['main'] = 'cms/contest';
		$data['meta_title']='Contests | Participate With Taxidio';
		$data['meta_description']='No pack of cards, no slot machines, no wheels of fortune, but a chance to win big
without losing anything. Try your luck by participating in a Taxidio contest.';
		$data['cms']=$this->Cms_fm->getCms(10);
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
		$data['meta_title']='Our Team | Taxidio - The Where, Why & How Traveler';
		$data['meta_description']='Taxidio is the team of young travel enthusiasts, who love our work as much as our freshly brewed coffee. See our team and email us to join Taxidio.';
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
		$data['meta_title']='Send Us Your Query Or Feedback | Contact Us | Taxidio';
		$data['meta_description']="Queries, feedbacks or just a hello. We would love to hear from you. Contact Taxidio here";
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

	function api()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'api';
		$data['main'] = 'cms/api';
		$data['meta_title']='Taxidio For Business | Contact Us For Business Inquiry';
		$data['meta_description']="Taxidio provides a platform for hotel chains to automate their concierge services. Contact us with your business inquiry.";
		$data['settings'] = $this->Cms_fm->getSettings();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function apimodel()
	{
		if($this->input->post('apisubmit'))
		{
			$this->load->library('form_validation');
			$this->load->library('email');
			$this->load->library('MY_Email');

			$this->form_validation->set_rules('first_name', 'Firstname', 'required|alpha');
			$this->form_validation->set_rules('last_name', 'Lastname', 'required|alpha');
			$this->form_validation->set_rules('company','Company Name','required');
			$this->form_validation->set_rules('designation','Designation','required|alpha');
			$this->form_validation->set_rules('email','Email','required');
			$this->form_validation->set_rules('message','Message','required');

			if($this->form_validation->run()==FALSE)
			{
				$this->api();	
			}
			else
			{
				$this->load->model('Cms_fm');

				$datetime=date('Y-m-d H:i:s');
				$form = array(
					"first_name" => $this->input->post("first_name"),
					"last_name" => $this->input->post("last_name"),
					"company" => $this->input->post("company"),
					"designation" => $this->input->post("designation"),
					"email" => $this->input->post("email"),
					"phone"	=> $this->input->post("phone"),
					'created'=>$datetime,
					"message" => $this->input->post("message")
				);

				$this->Cms_fm->insertdata($form);
				$this->Cms_fm->sendApiEmail();
				$this->Cms_fm->sendApiFeedbackEmail();
				
				redirect('thankyou');
			}

		}
		else
		{
			redirect('api');
		}
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
		$data['meta_title']='Experience an easy way to plan your trip with Taxidio | Discover Taxidio';
		$data['meta_keywords']='vacation trip planner,trip planners,travel route planner,trip route planner,online trip planner,online route planner,free trip planner,trip planner online,holiday trip planner,travel planner online,travel search engines';
		$data['meta_description']='Taxidio is global trip planner that recommends holiday destinations, creates itineraries, offers travel guides, accommodation bookings, attraction bookings and sharing. Read here to know more about Taxidio.';
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function privacy_policy()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'privacy_policy';
		$data['main'] = 'cms/content';
		$data['meta_title']='Read The Policies And Terms & Conditions | Taxidio';
		$data['meta_description']='Your access to Taxidio and its services are regulated by this section. We advise you to
read policies and terms & conditions.';
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
		$data['meta_title']='Read The Cookie Policies | Taxidio';
		$data['meta_description']="'Cookie Policy for Taxidio' explains how Taxidio operates this website and how the data
protection regulation is applied. Read the cookie policies here.";
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
			  $this->session->set_flashdata('success', 'Thank you for writing to us. We will get back to you shortly.');
			  redirect('thank-you');
		  }
		}
		else
		{
			redirect('contactus');
		}

	}

	function thankyou()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'thankyou';
		$data['main'] = 'cms/thankyou';
		$data['settings']=$this->Cms_fm->getSettings();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function thankyouforapi()
	{
		$data['webpage'] = 'cms';
		$data['page'] = 'thankyouforapi';
		$data['main'] = 'cms/thankyouforapi';
		$data['settings']=$this->Cms_fm->getSettings();
		$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}


	function credit()
	{
		//phpinfo();
		$data['webpage'] = 'cms';
		$data['page'] = 'credit';
		$data['main'] = 'cms/credit';
		$data['meta_title']='Credits for images used on our website | Taxidio';
		$data['meta_description']="This is a page where you will find all the credits for images that are used in the Taxidio website.";
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
				$table='tbl_city_restaurants';
				$field='ran_name as name';
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
	            $config["prev_tag_open"] = "<li class='pagination-item-custom first'>";
	            $config["prev_tag_close"] = "</li>";
	            $config["next_tag_open"] = "<li class='pagination-item-custom last'>";
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
