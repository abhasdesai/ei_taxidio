<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Upload extends Front_Controller {

	function index()
	{
		$this->load->view('upload', array('error' => ' ' ));
	}	

	public function upload_file()
        {
                $config['upload_path']    = './uploads/';
                $config['allowed_types']  = 'gif|jpg|png|pdf|doc|txt';
                $config['max_size']       = 1000;
                $config['max_width']      = 1024;
                $config['max_height']     = 768;
                        
                $this->load->library('upload', $config);
                               				
		      if ( ! $this->upload->do_upload('userfile'))
                {
                    $this->load->helper('url');
                    echo 'You have uploaded wrong file!';        
                    redirect(base_url('contest'));                   
                }
                else
                {
                    $this->load->model('Fileupload_m');
                    $upload_data = $this->upload->data();
                    $data1['filename'] = $upload_data['file_name'];
                    $data1['email'] = $this->session->userdata('email');
                    date_default_timezone_set('Asia/Kolkata');
                    $data1['unique_id'] = date('YmdHisms');
                    $this->Fileupload_m->fileinfo($data1);
                                            
                    $this->Cms_fm->send_contest_email();                        
                    $this->load->helper('url');
                    redirect(base_url('contest'));

             //   $this->contest();

                }
        }

    }