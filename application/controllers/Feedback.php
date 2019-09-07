<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends User_Controller 
{	

	public function userfeedbacks()
	{

		$data['webpage'] = 'Feedback';
		$data['main'] = 'myaccount/feedback/userfeedbacks';
		$this->load->library('pagination');
		$start_row=$this->uri->segment(2);
        $config["base_url"] = site_url('userfeedbacks');
        $config["total_rows"] = $this->Feedback_fm->countFeedbacks();
        $config["full_tag_open"] = "<ul class='pagination pagination-split'>";
        $config["full_tag_close"] = "</ul>";
        $config["num_tag_open"] = "<li class='pagination-item-custom'>";
        $config["num_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='pagination-item-custom active'><a href='javascript:void(0)' class=''>";
        $config["cur_tag_close"] = "</a></span></li>";
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
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
        $data['feedbacks']=$this->Feedback_fm->getFeedbacks($config["per_page"],$start_row);
		$data['pagination']=$this->pagination->create_links();
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	public function createFeedback()
	{
		$data['webpage'] = 'Feedback';
		$data['main'] = 'myaccount/feedback/createFeedback';
		$sessionemail=$this->session->userdata('isemail');
		$data['askforemail']=0;
		if($sessionemail==1)
		{
			$data['askforemail']=1;
		}
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	public function sendFeedback()
	{
		$this->load->library('form_validation');
		if($this->session->userdata('askforemail')==1)
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[450]|valid_email');
		}
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[5]');

		if($this->form_validation->run()==FALSE)
		{

			$this->session->set_flashdata('error','Something Wrong Occured.');
			$this->createFeedback();
		}
		else
		{
			 $this->load->library('email');
		     $this->load->library('MY_Email');	 
			$this->Feedback_fm->sendFeedback();
			$this->session->set_flashdata('success','Your message has been Sent.');
			redirect('createFeedback');
		}

	}

	function viewFeedback($id)
	{
		$data['webpage'] = 'Feedback';
		$data['main'] = 'myaccount/feedback/viewFeedback';
		$data['feedback']=$this->Feedback_fm->viewFeedback($id);
		if(!count($data['feedback']))
		{
			show_404();
		}
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	function deleteFeedback($id)
	{
		$this->Feedback_fm->deleteFeedback($id);
		$this->session->set_flashdata('success', 'Your Feedback has been deleted.');
		redirect('userfeedbacks');
	}
}
?>