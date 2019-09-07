<?php

class Forum extends Front_Controller
{

	public function discuss()
	{

				$data['webpage'] = 'Forum';
				$data['main'] = 'discuss';
				$this->load->library('pagination');
				$start_row=$this->uri->segment(2);
        $config["base_url"] = site_url('myquestions');
        $config["total_rows"] = $this->Forum_m->countTotalQuestions();
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
        $data['forum']=$this->Forum_m->getTotalQuestions($config["per_page"],$start_row);
				$data['pagination']=$this->pagination->create_links();
				$this->load->vars($data);
				$this->load->view('templates/innermaster');
	}

	public function myquestions()
	{
					if($this->session->userdata('fuserid')=='')
					{
						$this->session->set_userdata('backurl',current_url());
						redirect('auth');
					}
					$data['webpage'] = 'Forum';
					$data['main'] = 'myaccount/forum/myquestions';
					$this->load->library('pagination');
					$start_row=$this->uri->segment(2);
	        $config["base_url"] = site_url('myquestions');
	       	$config["total_rows"] = $this->Forum_m->countMyQuestions();
	        $config["full_tag_open"] = "<ul class='pagination pagination-split'>";
	        $config["full_tag_close"] = "</ul>";
	        $config["num_tag_open"] = "<li class='pagination-item-custom'>";
	        $config["num_tag_close"] = "</li>";
	        $config["cur_tag_open"] = "<li class='pagination-item-custom active'><a href='javascript:void(0)' class=''>";
	        $config["cur_tag_close"] = "</a></span></li>";
	        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
	        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
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
	        $data['forum']=$this->Forum_m->getMyQuestions($config["per_page"],$start_row);
					$data['pagination']=$this->pagination->create_links();
					$this->load->vars($data);
					$this->load->view('templates/dashboard/innermaster');
	}

	public function ask_question($id)
	{
			if($this->session->userdata('fuserid')=='')
			{
				$this->session->set_userdata('backurl',current_url());
				redirect('auth');
			}
			$data['itineraryinfo']=$this->Forum_m->getItineraryInfo($id);
			if(count($data['itineraryinfo'])<1)
			{
				show_404();
			}
			$data['webpage'] = 'forum';
			$data['main'] = 'myaccount/forum/ask_question';

			$this->load->vars($data);
			$this->load->view('templates/dashboard/innermaster');
	}

	function addQuestion($id)
	{
			if($this->session->userdata('fuserid')=='')
			{
				$this->session->set_userdata('backurl',current_url());
				redirect('auth');
			}
			$data['itineraryinfo']=$this->Forum_m->getItineraryInfo($id);
			if(count($data['itineraryinfo'])<1)
			{
				show_404();
			}
			$this->load->library('form_validation');
			$this->form_validation->set_rules('question', 'Question', 'trim|required|min_length[5]|max_length[2000]');

			if($this->form_validation->run()==FALSE)
			{
				$this->ask_question($id);
			}
			else
			{
				 $this->Forum_m->addQuestion($id);
				 $this->session->set_flashdata('success','Your question has been submitted.');
				 redirect('myquestions');
			}
	}

	function questionInfo($slug)
	{
			$data['webpage'] = 'Forum';
			$data['main'] = 'questionInfo';
			$data['forum']=$this->Forum_m->getQuestionDetails($slug);
			if(!count($data['forum']))
			{
				show_404();
			}
			$data['comments']=$this->Forum_m->getAllComments($slug);
			$data['totalcomments']=$this->Forum_m->countTotalComments($data['forum']['id']);
			$this->load->vars($data);
			$this->load->view('templates/innermaster');
	}

}
?>
