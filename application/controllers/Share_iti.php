<?php

class Share_iti extends User_Controller
{

	public function __construct() {
		parent::__construct();
		$this->load->model('Share_iti_fm');
	}

	function invited_trips()
	{
		$data['webpage'] = 'invited_trips';
		$data['main'] = 'myaccount/invited_trips';
		$this->load->library('pagination');
		$start_row=$this->uri->segment(2);
        $config["base_url"] = site_url('trips');
        $config["total_rows"] = $this->Share_iti_fm->countInvitedTrips();
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
        $config['per_page'] = 12;
        $this->pagination->initialize($config);
        $data['trips']=$this->Share_iti_fm->getInvitedTrips($config["per_page"],$start_row);
        //echo "<pre>";print_r($data['trips']);die;
        $data['pagination']=$this->pagination->create_links();
		$this->load->vars($data);
		$this->load->view('templates/dashboard/innermaster');
	}

	function co_travellers()
	{
		$this->load->library('pagination');
		$start_row=$this->uri->segment(2,0);
        $config["base_url"] = site_url('getCoTravellers');
        $config["total_rows"] = $this->Share_iti_fm->count_co_travellers();
        $config["full_tag_open"] = "<ul class='pagination-custom pagination-lg'>";
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
        $config['per_page'] = 1;
        $this->pagination->initialize($config);
        $data['co_travellers']=$this->Share_iti_fm->get_co_travellers($config["per_page"],$start_row);
        //echo count($data['co_travellers']);die;
        //echo "<pre>";print_r($data['co_travellers']);die;
        $data['pagination']=$this->pagination->create_links();
		$_html=$this->load->view('myaccount/co_travellers', $data,TRUE);
		//echo "<pre>";print_r($data['public_itineraries']);die;
		echo $_html;
	}

	function get_public_modal()
	{
		$data['iti']=$this->Share_iti_fm->get_iti();
		$output = $this->load->view('myaccount/iti_public', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function share_iti_with_email()
	{
		$data['iti']=$this->Share_iti_fm->get_iti();
		$output = $this->load->view('myaccount/share_iti_with_email', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function share_iti_with_member_form()
	{
		//$data['iti']=$this->Share_iti_fm->get_iti();
		$output = $this->load->view('myaccount/share_iti_with_member', 'share', true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function share_iti_with_member()
	{
		$email=trim($_POST['email']);
		$data=$this->Share_iti_fm->get_iti();
		if(empty($data))
		{
			$this->session->set_flashdata('error','something went wrong.');
		}
		elseif($email==$_SESSION['email'])
		{
			$this->session->set_flashdata('error','You can not share trip with yourself.');
		}
		else
		{
			$this->load->library('email');
			$this->load->library('MY_Email_Other');	
			$result=$this->Share_iti_fm->check_email_for_member($email);
			if($result===FALSE)
			{
				$result2=$this->Share_iti_fm->is_already_share_email($data['id'],$email);
				if($result2===FALSE)
				{
					$this->Share_iti_fm->iti_share_with_guest($data);
				}
				else
				{
					$this->session->set_flashdata('error',"You have already share this trip with ".$email.".");
				}
			}
			else
			{
				$result3=$this->Share_iti_fm->is_already_share($data['id'],$result['id']);
				if($result3===FALSE)
				{
					$val=array('invited_user_id' => $result['id'],'iti_id' => $data['id'],'name' => $result['name'],'user_trip_name'=> $data['user_trip_name']);
					$this->Share_iti_fm->iti_share_with_member($val);
				}
				else
				{
					$this->session->set_flashdata('error',"You have already share this trip with ".ucwords($result['name']).".");
				}
			}
		}
		redirect('trips');
	}

	function iti_share_by_email()
	{
		$data=$this->Share_iti_fm->get_iti();
		if(empty($data))
		{
			$this->session->set_flashdata('error','something went wrong.');
		}
		else
		{
			$result=$this->Share_iti_fm->iti_share_by_email($data);
			if($result===TRUE)
			{
				$this->session->set_flashdata('success','Your itinerary link has been shared successfully.');
			}
			else
			{
				$this->session->set_flashdata('error','something went wrong.');
			}
		}
		redirect('trips');
	}

	function twitter_login($iti_id)
	{
		$data=$this->Share_iti_fm->get_iti($iti_id);
		if(empty($data))
		{
			$this->session->set_flashdata('error','something went wrong.');
			redirect('trips');
		}
		else
		{
			$session_data = array(
				'share_iti_id' =>$data['id'],
				'user_trip_name'=>$data['user_trip_name'],
				'share_url'	=>site_url('planned-itinerary-forum/'.$data['slug'])
				);
			$this->session->set_userdata('itinerary_data', $session_data);
			require_once(APPPATH.'libraries/Twitter/login.php');
		}
	}

	function iti_share_by_twitter()
	{
		$iti_id=$_SESSION['share_iti_id'];
		if (isset($_GET['oauth_verifier'], $_GET['oauth_token']) && $_GET['oauth_token'] == $_SESSION['oauth_token']) {	
			require_once(APPPATH.'libraries/Twitter/callback.php');
			require_once(APPPATH.'libraries/Twitter/twitter-php-master/send.php');
			if(isset($_SESSION['send_error']) && !empty($_SESSION['send_error']))
			{
				$this->session->set_flashdata('error',$_SESSION['send_error']);
				unset($_SESSION['send_error']);
			}
			else
			{
				$this->session->set_flashdata('success','Your itinerary link has been shared on your Twitter profile.');
			}
		}else{
			$this->session->set_flashdata('error','something want wrong.');
		}
		$this->Share_iti_fm->unset_twitter_session();
		redirect('trips');
	}

	function invited_trip_status_update()
	{
		$data=$this->Share_iti_fm->invited_trip_status_update();
		if(empty($data))
		{
			return FALSE;
		}
		writeTripsInFile();
		return $this->output->set_content_type('application/json')->set_output(json_encode('1'));
	}

	function deleteInvitedTrip($id)
	{
		$result=$this->Share_iti_fm->deleteInvitedTrip($id);
		if($result===TRUE)
		{
			writeTripsInFile();
			$this->session->set_flashdata('success','The trip you were invited to has been deleted.');
		}
		else
		{
			$this->session->set_flashdata('error','something went wrong.');
		}
		redirect('invited-trips');
	}

	function trip_notification()
	{
		$this->Share_iti_fm->trip_notification();
	}
}
?>
