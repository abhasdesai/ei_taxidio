<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cms_fm extends CI_Model
{
	function getAllFaqs()
	{
		$data=array();
		$Q=$this->db->query('select id,question,answer,(select category from tbl_faq_category where id=tbl_faq.category_id) as category from tbl_faq order by category_id asc');
		$dataAarry=$Q->result_array();
		if(count($dataAarry))
		{
			$data = array();
			foreach($dataAarry as $arg)
			{
				$data[$arg['category']][] = $arg;
			}
		}
		return $data;
	}

	function getAllTeams()
	{
		$data=array();
		$Q=$this->db->query('select * from tbl_team order by sortorder asc');
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[] = $row;
			}
		}

		return $data;
	}

	function getCms($id)
	{
		$Q=$this->db->query('select * from tbl_cms where id="'.$id.'"');
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

	function sendContactUsEmail()
	{
		$subject='Inquiry From Website';
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$message=$this->load->view('emailtemplate',$data,true);
		$from='info@taxidio.com';
		$to=$adminemail;
		$this->email->from($from,$_POST['name']);
		$this->email->subject($subject);
		$this->email->reply_to($_POST['email']);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			 $this->session->set_flashdata('success','Thank you for connecting us');
		}
		else
		{
		   $this->session->set_flashdata('error','Message was not sent.Please try again.');
		}

	}


	function sendFeedbackEmailToUser()
	{
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$data['taxidio']=$this->getSettings();
		$message=$this->load->view('emailtemplate_respond',$data,true);
		$subject="We’ve received your feedback";
		$from='info@taxidio.com';
		$to=$_POST['email'];
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->reply_to($adminemail);
		$this->email->to($to);
		$this->email->message($message);
		$this->email->send();
	}

	function insertdata($form){
		$query = $this->db->insert('tbl_api_model', $form);
		return $query;
	}

	// function api_enquiry()
	// {
	// 	$this->load->library('email');
	// 	$this->load->library('MY_Email_Other');
	// 	$this->sendApiEmail();
	// 	$this->sendApiFeedbackEmail();

	// 	$datetime=date('Y-m-d H:i:s');
	// 	$data=array(
	// 			'first_name'=>ucwords($_POST['first_name']),
	// 			'last_name'=>ucwords($_POST['last_name']),
	// 			'company'=>$_POST['company'],
	// 			'designation'=>$_POST['designation'],
	// 			'created'=>$datetime,
	// 			'email'=>$_POST['email'],
	// 			'phone'=>$_POST['phone'],
	// 			'message'=>$_POST['message']
	// 		);

	// 	$this->db->insert('tbl_api_model',$data);
	// }

	function sendApiEmail()
	{
	   	$getadminemail=$this->getSettingsEmail();
	   	$adminemail=$getadminemail['email'];
	   	$data=$_POST;
	   	$message = $this->load->view('emailtemplate_api',$data,true);
	   	$subject = 'Api Form Details';
	   	$from = 'varsha.anabhavane@taxidio.com';
	   	$to = $adminemail;
	   	//$to = 'varsha.anabhavane@taxidio.com';
	   	//$to = 'ei.muniruddin.malek@gmail.com';
	   	
	   	$this->email->from($from,'Taxidio');
	   	$this->email->to($to);
	   	//$this->email->reply_to($_POST['email']);
	   	$this->email->subject($subject);
	   	$this->email->message($message);

	   	if($this->email->send())
	   	{
	   		$this->session->set_flashdata('success','Data sent successfully. Thank you!');
	   	}
	   	else
	   	{
	   		$this->session->set_flashdata('error','Error in sending data! Please try again.');
	   	}
	}


	function sendApiFeedbackEmail()
	{
		$getadminemail=$this->getSettingsEmail();
		$adminemail=$getadminemail['email'];
		$data=$_POST;
		$data['taxidio']=$this->getSettings();
		$message=$this->load->view('emailtemplate_apirespond',$data,true);
		$subject = 'We’ve received your data.';
		$from = 'varsha.anabhavane@taxidio.com';
		$to = $_POST['email'];

		$this->email->from($from, 'Taxidio Travel India Pvt. Ltd.');
		$this->email->to($to);
		//$this->email->reply_to($adminemail);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}


	function countCategory($table)
	{
		$this->db->select('id');
		$this->db->where('image !=','');
		$this->db->where('credit !=','');
		$Q=$this->db->get($table);
		return $Q->num_rows();

	}

	function getAllCreditsOfCategory($table,$field,$limit,$start)
	{
		$this->db->select("image,credit,city_name,$field");
		$this->db->from($table);
		$this->db->join('tbl_city_master',"tbl_city_master.id=$table.city_id");
		$this->db->where('image !=','');
		$this->db->where('credit !=','');
		$this->db->limit($limit,$start);
		$Q=$this->db->get();
		return $Q->result_array();
	}

	function getTopCities()
	{
			 $this->db->select('tbl_city_master.city_name,tbl_seo_cities_countries.slug,cityimage');
			 $this->db->from('tbl_seo_cities_countries');
			 $this->db->join('tbl_city_master','tbl_city_master.id=tbl_seo_cities_countries.city_id');
			 $this->db->order_by('sortorder','ASC');
			 $Q=$this->db->get();
			 return $Q->result_array();
	}

	function getTopCountries()
	{
			$this->db->select('tbl_country_master.country_name,tbl_seo_cities_countries.slug,countryimage');
			$this->db->from('tbl_seo_cities_countries');
			$this->db->join('tbl_country_master','tbl_country_master.id=tbl_seo_cities_countries.country_id');
			$this->db->order_by('sortorder','ASC');
			$Q=$this->db->get();
			return $Q->result_array();
	}

}

?>
