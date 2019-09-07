<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Feedback_wm extends CI_Model 
{

	function sendFeedback()
	{
		$data=array(
					'subject'=>$this->input->post('subject'),
					'message'=>$this->input->post('message'),
					'user_id'=>$this->input->post('userid'),
					'created'=>date('Y-m-d H:i:s'),
					'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
	
		$this->db->insert('tbl_feedbacks',$data);
		$user=getrowbycondition('name,email','tbl_front_users',"id=".$this->input->post('userid'));
		$toEmail=$user['email'];
		
		$this->sendFeedbackEmail($toEmail,$user['name']);
		$this->sendFeedbackEmail_sender($toEmail);
	}

	function countFeedbacks()
	{
		$Q=$this->db->query('select id from tbl_feedbacks where user_id="'.$this->input->post('userid').'"');
		return $Q->num_rows();
	}

	function getFeedbacks($limit,$start)
	{
		$this->db->select('id,subject,message,created');
		$this->db->from('tbl_feedbacks');
		$this->db->where('user_id',$this->input->post('userid'));
		$this->db->limit($limit,$start);
		$this->db->order_by('id','desc');
		$Q=$this->db->get();
		return $Q->result_array();
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

	function sendFeedbackEmail($toEmail,$username)
	{
		$subject='Feedback & Help';
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$message=$this->load->view('myaccount/feedback/emailtemplate',$data,true);
		//echo $message;die;
		//$from='ei.anita.gupta@gmail.com'; 
		//$to='ei.muniruddin.malek@gmail.com'; 
		$to=$adminemail;		
		$this->email->from($toEmail,$username);
		$this->email->subject($subject);
		$this->email->reply_to($toEmail);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}

	function sendFeedbackEmail_sender($toEmail)
	{
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];//'ei.muniruddin.malek@gmail.com';
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


	function deleteFeedback()
	{
		$Q=$this->db->query('select id from tbl_feedbacks where id="'.$this->input->post('feedback_id').'" and user_id="'.$this->input->post('userid').'" limit 1');
		if($Q->num_rows()>0)
		{
			$this->db->where('id',$this->input->post('feedback_id'));
			$this->db->delete('tbl_feedbacks');
			return 1;
		}
		else
		{
			return 0;
		}
	}

}
?>