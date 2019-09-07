<?php
class Dashboard extends Admin_Controller {

	public function index() {
		$data['webpagename'] = 'dashboard';
		$data['main'] = 'admins/adminfiles/main';
		$data['section'] = 'Dashboard';
		$data['totalusers']=$this->Dashboard_m->getTotalUsers();
		$data['totalcountries']=$this->Dashboard_m->getTotalCountries();
		$data['totalcities']=$this->Dashboard_m->getTotalCities();
		$data['totalsubscribers']=$this->Dashboard_m->getTotalSubscribers();
		$data['page'] = 'Dashboard';
		$this->load->vars($data);
		$this->load->view('admins/templates/homemaster');
	}

	public function logout() {
		$this->User_m->logout();
		redirect('admin');
	}

	function checkCurrentPassword() {
		$flag = $this->User_m->checkCurrentPassword();
		echo $flag;
	}

	function updateCurrentPassword() {
		$flag = $this->User_m->updateCurrentPassword();
		echo $flag;
	}
}
?>
