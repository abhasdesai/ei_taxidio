<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends User_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('payu');
	}

	function index()
	{
		$data['webpage'] = 'attraction_listings';
		$data['main'] = 'payment';
		$this->load->vars($data);
		$this->load->view('templates/dashboard/homemaster');
	}

	// function payment_success() {
	// 	/* Payment success logic goes here. */
	// 	echo "Congratulations !! The Payment is successful.";
	// }

	// function payment_failure() {
	// 	/* Payment failure logic goes here. */
	// 	echo "We are sorry. The Payment has failed";
	// }

	function payment()
	{
		if ( count( $_POST ) ) 
			pay_page( array ('key' => '71tFEF', 'txnid' => uniqid( 'animesh_' ), 'amount' => rand( 0, 100 ),
					'firstname' => 'animesh', 'email' => 'animesh.kundu@payu.in', 'phone' => '1234567890',
					'productinfo' => 'This is shit', 'surl' => 'payment_success', 'furl' => 'payment_failure'), 
					'B0Gnqt1g' );
	}

}


/* And we are done. */
?>