<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Share_iti_fm extends CI_Model
{

	function countInvitedTrips()
	{
	    $this->db->select('tbl_itineraries.id');
	    $this->db->from('tbl_itineraries');
	    $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
	    $this->db->join('tbl_share_itineraries','tbl_share_itineraries.user_id=tbl_itineraries.user_id');
	    $this->db->where('tbl_share_itineraries.itinerary_id=tbl_itineraries.id');
	    $this->db->where('tbl_share_itineraries.invited_user_id',$this->session->userdata('fuserid'));
		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function getInvitedTrips($limit,$start)
	{
		$data=array();
	    $this->db->select('tbl_itineraries.id,status,inputs,singlecountry,tbl_itineraries.created,user_trip_name,name,dob,gender,passport,email,phone,trip_type,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as countryname,citiorcountries,cities,tbl_itineraries.country_id,slug,(select count(id) from tbl_itinerary_questions where tbl_itinerary_questions.itinerary_id=tbl_itineraries.id) as totalquestions,userimage,googleid,facebookid,name,tbl_itineraries.user_id,tripname,views,rating');
	    $this->db->from('tbl_itineraries');
	    $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
	    $this->db->join('tbl_share_itineraries','tbl_share_itineraries.user_id=tbl_itineraries.user_id');
	    $this->db->where('tbl_share_itineraries.itinerary_id=tbl_itineraries.id');
	    $this->db->where('tbl_share_itineraries.invited_user_id',$this->session->userdata('fuserid'));
		$this->db->limit($limit,$start);
		$this->db->order_by('tbl_share_itineraries.id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $i=>$row)
			{
				$data[$i]=$row;
			}
		}
		return $data;
	}

	function count_co_travellers()
	{
		$result=get_invited_trip_details(string_decode($_POST['iti_id']));
	    $this->db->select('tbl_front_users.id');
	    $this->db->from('tbl_front_users');
	    $this->db->join('tbl_share_itineraries','tbl_share_itineraries.invited_user_id=tbl_front_users.id OR tbl_share_itineraries.user_id=tbl_front_users.id');
		if($result!==FALSE)
		{
	    	$this->db->where('tbl_front_users.id!=',$this->session->userdata('fuserid'));
		}
		else
		{
	    	$this->db->where('tbl_share_itineraries.status',1);
	    	$this->db->where('tbl_front_users.id!=',$this->session->userdata('fuserid'));
	    	$this->db->where('tbl_share_itineraries.user_id',$this->session->userdata('fuserid'));
	    }
	    $this->db->where('tbl_share_itineraries.itinerary_id',string_decode($_POST['iti_id']));
 		$this->db->group_by('tbl_front_users.id'); 
		$Q=$this->db->get();
		return $Q->num_rows();
	}

	function get_co_travellers($limit,$start)
	{
		$data=array();
		$result=get_invited_trip_details(string_decode($_POST['iti_id']));
	    $this->db->select('tbl_front_users.id,itinerary_id,name,dob,gender,passport,email,phone');
	    $this->db->from('tbl_front_users');
	    $this->db->join('tbl_share_itineraries','tbl_share_itineraries.invited_user_id=tbl_front_users.id OR tbl_share_itineraries.user_id=tbl_front_users.id');
		if($result!==FALSE)
		{
	    	$this->db->where('tbl_front_users.id!=',$this->session->userdata('fuserid'));
		}
		else
		{
	    	$this->db->where('tbl_share_itineraries.status',1);
	    	$this->db->where('tbl_front_users.id!=',$this->session->userdata('fuserid'));
	    	$this->db->where('tbl_share_itineraries.user_id',$this->session->userdata('fuserid'));
	    }
	    $this->db->where('tbl_share_itineraries.itinerary_id',string_decode($_POST['iti_id']));
		$this->db->limit($limit,$start);
 		$this->db->group_by('tbl_front_users.id'); 
		$this->db->order_by('tbl_share_itineraries.id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$data=$Q->result_array();
		}
		return $data;
	}

	function invited_trip_status_update()
	{
		$result=get_invited_trip_details(string_decode($_POST['iti_id']));
		if($result!==FALSE)
		{
			$this->db->where('id',$result['id']);
			$this->db->update('tbl_share_itineraries',array('status'=>1,'modified'=>date('Y-m-d H:i:s')));
			return TRUE;
		}
		return FALSE;
	}

	function get_iti($iti_id=0)
	{
		if($iti_id===0)
		{
			$iti=string_decode($_POST['iti_id']);
		}
		else
		{
			$iti=string_decode($iti_id);
		}
		$Q=$this->db->query('select id,user_trip_name,trip_mode,slug from tbl_itineraries where user_id="'.$this->session->userdata('fuserid').'" and id="'.$iti.'"');
		if($Q->num_rows()>0)
		{
			return $Q->row_array();
		}
		return FALSE;
	}

	function is_already_share_email($iti,$email)
	{
		$Q=$this->db->query('select id from tbl_invited_users where user_id="'.$this->session->userdata('fuserid').'" and itinerary_id="'.$iti.'" and invited_user_email like "'.$email.'"');
		if($Q->num_rows()>0)
		{
			return $Q->row_array();
		}
		return FALSE;
	}

	function is_already_share($iti,$invited_user_id)
	{
		$Q=$this->db->query('select id from tbl_share_itineraries where user_id="'.$this->session->userdata('fuserid').'" and itinerary_id="'.$iti.'" and invited_user_id="'.$invited_user_id.'"');
		if($Q->num_rows()>0)
		{
			return $Q->row_array();
		}
		return FALSE;
	}

	function check_email_for_member($email)
	{
		$Q=$this->db->query('select id,name from tbl_front_users where email="'.$email.'" and id!="'.$this->session->userdata('fuserid').'"');
		if($Q->num_rows()>0)
		{
			return $Q->row_array();
		}
		return FALSE;
	}

	function iti_share_with_guest($data)
	{
		$message=$this->load->view('myaccount/share-itinerary-with-guest',$data,true);
		$from=$_SESSION['email'];
		$to=trim($_POST['email']);
		$subject="Check out our trip itinerary!";
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			$iudata = array(
				'type' => 2, 
				'user_id' => $this->session->userdata('fuserid'), 
				'itinerary_id' => $data['id'], 
				'invited_user_email' => $_POST['email'], 
				);
			$this->invited_users_entry($iudata);
			$this->session->set_flashdata('success','Your itinerary link has been shared with your travel companion.');
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		$this->session->set_flashdata('error','something went wrong.');
		return FALSE;
	}

	function invited_users_entry($data)
	{
		$this->db->select('id');
	    $this->db->from('tbl_invited_users');
	    $this->db->where('type',$data['type']);
	    if(!empty($data['itinerary_id']))
	    {
	    	$this->db->where('itinerary_id',$data['itinerary_id']);
	    }
	    $this->db->where('invited_user_email',$data['invited_user_email']);
	    $this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			$row=$Q->row_array();
			$iudata=array(
				'type'=>$data['type'],
				'user_id'=>$data['user_id'],
				'itinerary_id'=>$data['itinerary_id'],
				'invited_user_email'=>$data['invited_user_email'],
				'created'=>date('Y-m-d H:i:s')
			);
	    	$this->db->where('id',$row['id']);
			$this->db->update('tbl_invited_users',$iudata);
		}
		else
		{
			$iudata=array(
				'type'=>$data['type'],
				'user_id'=>$data['user_id'],
				'itinerary_id'=>$data['itinerary_id'],
				'invited_user_email'=>$data['invited_user_email'],
				'created'=>date('Y-m-d H:i:s')
			);

			$this->db->insert('tbl_invited_users',$iudata);
		}
	}

	function iti_share_with_member($data)
	{
		$dt=array(
				'user_id'=>$this->session->userdata('fuserid'),
				'invited_user_id'=>$data['invited_user_id'],
				'itinerary_id'=>$data['iti_id'],
				'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('tbl_share_itineraries',$dt);
		$message=$this->load->view('myaccount/share-itinerary-with-member',$data,true);
		$from=$_SESSION['email'];
		$to=trim($_POST['email']);
		$subject="Check out our trip itinerary!";
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			$this->session->set_flashdata('success','Your itinerary link has been shared with your travel companion.');
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		$this->session->set_flashdata('error','something went wrong.');
		return FALSE;
	}

	function iti_share_by_email($data)
	{
		$this->load->library('email');
		$this->load->library('MY_Email_Other');	
		$message=$this->load->view('myaccount/itinerary-share-by-email',$data,true);
		$from=$_SESSION['email'];
		$to=trim($_POST['share_email']);
		$subject="Check out my travel itinerary!";
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		return FALSE;
	}

	function unset_twitter_session()
	{
		if(isset($_SESSION['send_error']))
		{
			unset($_SESSION['send_error']);
		}
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		unset($_SESSION['twitter_access_token']);
		unset($_SESSION['itinerary_data']);
	}

	function getsharedUserTrips($id)
	{
		$data=array();
		$this->db->select('tbl_itineraries.*,(select country_name from tbl_country_master where id=tbl_itineraries.country_id) as country_name,trip_type,(select count(id) from tbl_itinerary_questions where itinerary_id=tbl_itineraries.id) as total,trip_mode');
		$this->db->from('tbl_itineraries');
		$this->db->where('id',$id);
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			return $Q->row_array();
		}
		return FALSE;
	}

	function deleteInvitedTrip($id)
	{
		$result=get_invited_trip_details(string_decode($id));
		if($result!==FALSE)
		{
			$this->db->where('id',$result['id']);
			$this->db->delete('tbl_share_itineraries');
			return TRUE;
		}
		return FALSE;
	}

	function trip_notification()
	{
		$this->db->select('id,inputs,start_date,end_date,trip_type');
		$this->db->from('tbl_itineraries');
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				if(empty($row['start_date']) || empty($row['end_date']))
				{
	                $json=json_decode($row['inputs'],TRUE);
	                if($row['trip_type']==1 || $row['trip_type']==2)
	                {
	                    $startdate=$json['start_date'];
	                    $ttldays=$json['days']-1;
	                }
	                else if($row['trip_type']==3)
	                {
	                    $startdate=$json['sstart_date'];
	                    $ttldays=$json['sdays']-1;
	                }
	                 $startdateformat=explode('/',$startdate);
	                 $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];
					$data = array(
					'start_date' => strtotime($startdateymd),
					'end_date'=>strtotime($startdateymd. " + $ttldays days")
					);
					$this->db->where('id',$row['id']);
					$this->db->update('tbl_itineraries', $data);
				}
			}
		}

		
        $this->db->select('tbl_itineraries.id,user_trip_name,start_date,name,email');
        $this->db->from('tbl_itineraries');
        $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
		$this->db->where('tbl_itineraries.user_id',$this->session->userdata('fuserid'));
		$this->db->order_by('id','DESC');
		$Q=$this->db->get();
		if($Q->num_rows()>0)
		{
			foreach($Q->result_array() as $row)
			{
				$check1=strtotime(date('d-m-Y'). ' + 15 day');
				$check2=strtotime(date('d-m-Y'). ' + 1 day');
				$start_date=strtotime(date('d-m-Y',$row['start_date']));
				if($check1==$start_date)
				{
					$this->trip_notification_email(1,$row);
				}
				elseif($check2==$start_date)
				{
					$this->trip_notification_email(2,$row);
				}
			}
		}
	}

	function trip_notification_email($type,$data)
	{
		$this->load->library('email');
		$this->load->library('MY_Email_Other');	
		if($type==1)
		{
			$message=$this->load->view('myaccount/trip-notification-email',$data,true);
		}
		else
		{
			$message=$this->load->view('myaccount/trip-notification-email2',$data,true);
		}
		$from="noreply@taxidio.com";
		$to=$data['email'];//'ei.muniruddin.malek@gmail.com';
		$subject="Trip Notification";
		$this->email->from($from,'Taxidio');
		$this->email->subject($subject);
		$this->email->to($to);
		$this->email->message($message);
		if($this->email->send())
		{
			return TRUE;
		}
		//echo $this->email->print_debugger();die;
		return FALSE;
	}

}
?>
