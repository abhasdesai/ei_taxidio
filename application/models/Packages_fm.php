<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Packages_fm extends CI_Model {
	
	private $table = "tbl_travelPackages_mst";
	private $userbalance = "tbl_userTravelGuideBalance_mst";
	private $payment = "tbl_payment_mst";
	private $user_City_Travel_Guide = "tbl_userTravelGuide_mst";
	
	public function getDetailsById($pacakge_id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('id',$pacakge_id);
		$Q=$this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->row();
		}
		return NULL;
	}
	

	public function getAll()
	{
		$this->db->select('id,package_name,package_qty,package_price,description');
		$this->db->from($this->table);
		$this->db->where('status',1);
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->result();
		}
		return array();
	}
	
	public function checkAlreadyPurchased()
	{
		$this->db->select('id,balance');
		$this->db->from($this->userbalance);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			$row = $Q->row();
			if($row->balance > 0)
			{
				$this->session->set_flashdata('pcgmsg','Balance Available to download travel guide.');
				return 1;
			}
			else
			{
				/*$this->session->set_flashdata('pcgmsg','Please Purchase the Package again.<br>As there is no balance available to Doanload Travel Guide');*/
				$this->session->set_flashdata('pcgmsg','Currently, you have no available balance. Please purchase a package to get access to this Travel Guide.');
				return 0;
			}
		}
		
		return 0;
	}
	
	public function getPurchasedPackage()
	{
		$this->db->select('T1.id,package_name,package_qty,package_price,T2.balance,date as purchsed_date');
		$this->db->from($this->userbalance.' as T2');
		$this->db->join($this->table.' as T1','T1.id = T2.package_id');
		$this->db->where('T2.user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->result_array();
		}
		return [];
		
	}
	
	public function updateUserPaymentStatus($arr)
	{
		//echo "Model";echo "<pre>";print_r($_POST);die;
		
		
		//echo "<pre>";print_r($arr);die;
		
		$order_id=explode('=',$arr[0]);
		$tracking_id=explode('=',$arr[1]);
		$bank_ref_no=explode('=',$arr[2]);
		$order_status=explode('=',$arr[3]);
		$payment_mode=explode('=',$arr[5]);
		$card_name=explode('=',$arr[6]);
		$currency=explode('=',$arr[9]);
		$amount=explode('=',$arr[10]);
		$billing_name=explode('=',$arr[11]);
		$billing_address=explode('=',$arr[12]);
		$billing_city=explode('=',$arr[13]);
		$billing_state=explode('=',$arr[14]);
		$billing_zip=explode('=',$arr[15]);
		$billing_country=explode('=',$arr[16]);
		$billing_tel=explode('=',$arr[17]);
		$billing_email=explode('=',$arr[18]);
		$billing_email=explode('=',$arr[18]);
		$mer_amount=explode('=',$arr[35]);
		$failure_message=explode('=',$arr[4]);
		$response_code=explode('=',$arr[38]);
		$trans_date=explode('=',$arr[40]);
		
		//Insert Payment Details
		/*$data = array(
			'user_id'=>$this->session->userdata('fuserid'),
			'package_id'=>$this->input->post('package_id'),
			'amount'=>$this->input->post('amount'),
			'date'=>date('Y-m-d'),
			'txn_id'=>123456,
			'status'=>1,
			'created_at'=>date('Y-m-d H:i:s'),
		);*/
		
		$payment=array(
			'user_id'=>$this->session->userdata('fuserid'),
			'package_id'=>$this->session->userdata('user_pck_id'),
			'amount'=>$amount[1],
			'date'=>date('Y-m-d'),
			'txn_id'=>$tracking_id[1],
			'status'=>1,
			'created_at'=>date('Y-m-d H:i:s'),
			'order_id'=>$order_id[1],
			'tracking_id'=>$tracking_id[1],
			'bank_ref_no'=>$bank_ref_no[1],
			'order_status'=>$order_status[1],
			//'payment_mode'=>$payment_mode[1],
			'card_name'=>$card_name[1],
			'currency'=>$currency[1],
			'amount'=>$amount[1],
			'billing_name'=>$billing_name[1],
			'billing_email'=>$billing_email[1],
			'billing_address'=>$billing_address[1],
			'billing_city'=>$billing_city[1],
			'billing_state'=>$billing_state[1],
			'billing_zip'=>$billing_zip[1],
			'billing_country'=>$billing_country[1],
			'billing_phno'=>$billing_tel[1],
			'response_code'=>$response_code[1],
			'trans_date'=>date('Y-m-d H:i:s',strtotime($trans_date[1])),
		);
		
		//echo "<pre>";print_r($payment);die;
		
		$this->db->insert($this->payment,$payment);
		
		$this->db->select('id,balance');
		$this->db->from($this->userbalance);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			$row = $Q->row();
			
			$baldata = array(
				'user_id'=>$this->session->userdata('fuserid'),
				'balance'=>$row->balance+$this->session->userdata('user_pck_qty'),
				'date'=>date('Y-m-d'),
				'package_id'=>$this->session->userdata('user_pck_id'),
				'updated_at'=>date('Y-m-d H:i:s'),
			);
			$this->db->set($baldata);
			$this->db->where('user_id',$this->session->userdata('fuserid'));
			$this->db->update($this->userbalance);
		}
		else
		{
			//Update User Balance
			$baldata = array(
				'user_id'=>$this->session->userdata('fuserid'),
				'balance'=>$this->session->userdata('user_pck_qty'),
				'date'=>date('Y-m-d'),
				'package_id'=>$this->session->userdata('user_pck_id'),
				'created_at'=>date('Y-m-d H:i:s'),
				'updated_at'=>date('Y-m-d H:i:s'),
			);
			
			$this->db->insert($this->userbalance,$baldata);
		}
		
		
		//Update User city Travel Guide
		
		$traveldata = array(
			'user_id'=>$this->session->userdata('fuserid'),
			'city_id'=>$this->session->userdata('user_pck_cityid'),
			'is_downloaded'=>0,
			'no_of_times'=>0,
			'created_at'=>date('Y-m-d H:i:s'),
		);
		
		$this->db->insert($this->user_City_Travel_Guide,$traveldata);
		
		
	}
	
	public function updateUserBalance($cityId)
	{
		//echo $cityId;die;
		
		$user_package = $this->getPurchasedPackage();
		//echo "<pre>";print_r($user_package);die;
		$data = array(
			'user_id'=>$this->session->userdata('fuserid'),
			'balance'=>($user_package[0]['balance']>0)?$user_package[0]['balance']-1:$user_package[0]['balance'],
			'date'=>date('Y-m-d'),
			'updated_at'=>date('Y-m-d H:i:s'),
		);
		
		$this->db->set($data);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->update($this->userbalance);
		
		$this->updateUseCityTravelGuide($cityId);
		
	}
	
	public function updateUseCityTravelGuide($cityId)
	{
		$this->db->select('*');
		$this->db->from($this->user_City_Travel_Guide);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$this->db->where('city_id',$cityId);
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			$row = $Q->row();
			
			$traveldata = array(
				'user_id'=>$this->session->userdata('fuserid'),
				'city_id'=>$cityId,
				'is_downloaded'=>1,
				'no_of_times'=> $row->no_of_times + 1,
			);
			$this->db->set($traveldata);
			$this->db->where('city_id',$cityId);
			$this->db->update($this->user_City_Travel_Guide);
		}
		else
		{
			$traveldata = array(
				'user_id'=>$this->session->userdata('fuserid'),
				'city_id'=>$cityId,
				'is_downloaded'=>1,
				'no_of_times'=>1,
				'created_at'=>date('Y-m-d H:i:s'),
			);
			
			$this->db->insert($this->user_City_Travel_Guide,$traveldata);
		}
	}
	
	public function getAllExpenseOfUser()
	{
		/*$this->db->select('T1.id,package_name,package_qty,package_price,description,tbl_payment_mst.date as purchsed_date,tbl_payment_mst.id as orderid');
		$this->db->from($this->userbalance.' as T2');
		$this->db->join($this->table.' as T1','T1.id = T2.package_id');
		$this->db->join('tbl_payment_mst','tbl_payment_mst.user_id ='.$this->session->userdata('fuserid'));
		$this->db->where('tbl_payment_mst.user_id',$this->session->userdata('fuserid'));*/

		$this->db->select('tbl_payment_mst.date as purchsed_date,tbl_payment_mst.id as orderid,T1.package_name,T1.package_qty,T1.package_price');
		$this->db->from('tbl_payment_mst');
		$this->db->join($this->table .' as T1' ,'T1.id = tbl_payment_mst.package_id');
		$this->db->where('tbl_payment_mst.user_id',$this->session->userdata('fuserid'));

		$Q = $this->db->get();
		/*echo $this->db->last_query();
		die;*/
		if($Q->num_rows() > 0)
		{
			return $Q->result_array();
		}
		return NULL;
	}

	public function getAvailBalance()
	{
		$this->db->select('balance');
		$this->db->from($this->userbalance);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->row()->balance;
		}
		return NULL;
	}
	
	public function getDownloadedTravelGuides()
	{
		$this->db->select('T1.*,T2.city_name');
		$this->db->from($this->user_City_Travel_Guide.' as T1');
		$this->db->join('tbl_city_master as T2','T1.city_id = T2.id');
		$this->db->where('T1.user_id',$this->session->userdata('fuserid'));
		$this->db->where('T1.is_downloaded',1);
		$Q = $this->db->get();
		
		//echo $this->db->last_query();die;
		
		if($Q->num_rows() > 0)
		{
			return $Q->result();
		}
		return NULL;
	}
	
	public function checkAlreadyDownloaded($cityId)
	{		
		$this->db->select('no_of_times,id,is_downloaded');
		$this->db->from($this->user_City_Travel_Guide);
		$this->db->where('md5(city_id)',$cityId);
		$this->db->where('user_id',$this->session->userdata('fuserid'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			$row = $Q->row();
			if($row->no_of_times > 0 && $row->is_downloaded > 0)
			{
				$this->session->set_flashdata('success','You have already downloaded the Travel Guide for this city');
				return 1;
			}
		}
		return 0;
		
	}
	
	public function getCurrentlyPurchasedPackageDetails()
	{
		$this->db->select('T1.id,package_name,package_qty,package_price,T2.balance,date as purchsed_date');
		$this->db->from($this->userbalance.' as T2');
		$this->db->join($this->table.' as T1','T1.id = T2.package_id');
		$this->db->where('T2.user_id',$this->session->userdata('fuserid'));
		$this->db->where('T2.date',date('Y-m-d'));
		$Q = $this->db->get();
		if($Q->num_rows() > 0)
		{
			return $Q->row();
		}
		return NULL;
	}
	
	public function sendPurchaseEmailToUser($user_details)
	{		
		
		$this->load->library('PDF');
		
		$this->db->select('*');
		$this->db->from('tbl_payment_mst');
		$this->db->join('tbl_travelPackages_mst','tbl_travelPackages_mst.id=tbl_payment_mst.package_id');
		$this->db->where('tbl_payment_mst.user_id',$user_details['id']);
		$this->db->where('tbl_payment_mst.date',date('Y-m-d'));
		$query = $this->db->get();	
		$payment_details = $query->row_array();
		$data['invoice'] = $payment_details;
		$html = $this->load->view('download_invoice',$data,TRUE);
		$this->pdf->load_html($html);
		$this->pdf->render();
		$invoice_output = $this->pdf->output();
		

		$query = $this->db->get();
		$data['taxidio'] = $settings;
		$data['user_details'] = $user_details;
		
		
		$subject = 'Invoice for Taxidio’s Travel Guide Package';
		//$from = 'varsha.anabhavane@taxidio.com';
		$from = 'ei.bhrugesh.vadhankar@gmail.com';
		
		$to = $user_details['email'];
		$data['name'] = $user_details['name'];
		$message = $this->load->view('emailtemplate_invoice',$data,true);
		$this->email->from($from, 'Taxidio Travel India Pvt. Ltd.');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->attach($invoice_output, 'application/pdf', "Invoice " . date("m-d H-i-s") . ".pdf", false);
		$this->email->message($message);
		
		$this->email->send();
	}

}

/* End of file  */
/* Location: ./application/models/ */
