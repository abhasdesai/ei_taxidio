<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Feedback_fm extends CI_Model 
{

	function sendFeedback()
	{
		$data=array(
					'subject'=>$this->input->post('subject'),
					'message'=>$this->input->post('message'),
					'user_id'=>$this->session->userdata('fuserid'),
					'created'=>date('Y-m-d H:i:s'),
					'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
	
		$this->db->insert('tbl_feedbacks',$data);
		
		if($this->session->userdata('askforemail')==1)
		{
			$toEmail=$_POST['email'];
		}
		else
		{
			$toEmail=$this->session->userdata('email');
		}

		$this->sendFeedbackEmail($toEmail);
		$this->sendFeedbackEmail_sender($toEmail);
	}


	function countFeedbacks()
	{
		$Q=$this->db->query('select id from tbl_feedbacks where user_id="'.$this->session->userdata('fuserid').'"');
		return $Q->num_rows();
	}

	function getFeedbacks($limit,$start)
	{
		$this->db->select('id,created,subject');
		$this->db->from('tbl_feedbacks');
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->limit($limit,$start);
		$Q=$this->db->get();
		return $Q->result_array();
	}

	function viewFeedback($id)
	{
		$Q=$this->db->query('select * from tbl_feedbacks where md5(id)="'.$id.'" and user_id="'.$this->session->userdata('fuserid').'" limit 1');
		return $Q->row_array();
	}

	function getSettings()
	{
		$Q=$this->db->query('select * from tbl_settings where id=1');
		return $Q->row_array(); 	
	}

	function getSettingsEmail()
	{
		$Q=$this->db->query('select email from tbl_settings where id=1');
		return $Q->row_array(); 	
	}

	function sendFeedbackEmail($toEmail)
	{
		$subject='Feedback & Help';
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$message=$this->load->view('myaccount/feedback/emailtemplate',$data,true);
		//echo $message;die;
		//$from='ei.anita.gupta@gmail.com'; 
		//$to='ei.anita.gupta@gmail.com'; 
		$to=$adminemail;		
		$this->email->from($toEmail,$this->session->userdata('name'));
		$this->email->subject($subject);
		$this->email->reply_to($toEmail);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			 $this->session->set_flashdata('success','Thank you for connecting us.');
		}
		else
		{
		   $this->session->set_flashdata('error','Message was not sent.Please try again.');
		}  
	}

	function sendFeedbackEmail_sender($toEmail)
	{
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data['taxidio']=$this->getSettings();
		$message=$this->load->view('myaccount/feedback/emailtemplate_respond',$data,true);
		$subject='Thanks For Feedback';
		$from='info@taxidio.com';  
		$to=$toEmail;
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->reply_to($adminemail);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}


	function deleteFeedback($id)
	{
		$Q=$this->db->query('select id from tbl_feedbacks where md5(id)="'.$id.'" and user_id="'.$this->session->userdata('fuserid').'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->db->where('md5(id)',$id);
			$this->db->delete('tbl_feedbacks');
		}
		else
		{
			$this->session->set_flashdata('error', 'Something wrong occured.');
			redirect('userfeedbacks');
		}
	}

}
?>