<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends User_Controller
{		


	public function __construct()
	{
		parent::__construct();
		$this->load->library('PDF');
	}
	public function download_invoice($payment_id)
	{
		$this->load->model('Invoice_m');
		$data['invoice'] = $this->Invoice_m->invoice_detail($payment_id);
		$html = $this->load->view('download_invoice',$data,TRUE);
		$this->pdf->load_html($html);
		/*$this->pdf->setPaper('A4', 'landscape');*/
        $this->pdf->render();
        $this->pdf->stream("Invoice.pdf");
		//$this->load->view('download_invoice',$data);
			
			
	}
		
}

?>

