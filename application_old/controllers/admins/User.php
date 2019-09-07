<?php
class User extends Admin_Controller
{

	public function index()
	{
		$data['webpagename']='user';
		$data['main']='admins/user/index';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}
	
	
	public function getTable()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id','isfull', 'name', 'profileimage','email', 'phone', 'pincode_location','id');
        
        // DB table to use
        $sTable = 'tbl_user';
        //
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select Data
       // $this->db->select('SQL_CALC_FOUND_ROWS id,firstname,lastname');
        $this->db->select("SQL_CALC_FOUND_ROWS id,isfull,profileimage,concat(firstname,' ',lastname) as name,email,phone,pincode_location", FALSE);
		$this->db->from('tbl_user');
		$where="approvedby!=0 OR rejectedby!=0";
		$this->db->where($where);
        $rResult = $this->db->get();
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $row[] = $aRow[$col];
            }
    
            $output['aaData'][] = $row;
        }
    
        echo json_encode($output);
    }
    
    function add()
    {
		if($this->input->post('btnsubmit'))
		{
				$this->User_m->add();
				$this->session->set_flashdata('success','Transaction Successful.');
				redirect('admins/user');
		}
		else
		{
			$data['webpagename']='user';
			$data['main']='admins/user/add';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
    	
    }
    
    function edit($id)
    {
		if($this->input->post('btnsubmit'))
		{
				$this->User_m->edit();
				$this->session->set_flashdata('success','Transaction Successful.');
				redirect('admins/user');
		}
		else
		{
			$data['webpagename']='user';
			$data['id']=$id;
			$data['user']=$this->User_m->getDetailsById($id);
			$data['main']='admins/user/edit';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
	}
	
	function delete($id)
	{
		$this->User_m->delete($id);
		$this->session->set_flashdata('success','Transaction Successful.');
		redirect('admins/user');
	}
	
	function view($id)
	{
		$data['webpagename']='user';
		$data['user']=$this->User_m->getDetailsById($id);
		$data['main']='admins/user/view';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}
    
    function checkUniqueEmail()
    {
		$flag=$this->User_m->checkUniqueEmail();
		echo $flag;
	}
	
	function checkUniqueUsername()
	{
		$flag=$this->User_m->checkUniqueUsername();
		echo $flag;
	}
	
	function checkUniqueEmailForUser()
	{
		$flag=$this->User_m->checkUniqueEmailForUser();
		echo $flag;
	}
	
	function getFullMember()
	{
		$data["fullmember"]=$this->User_m->getFullMember();
		$data['start']=$_POST['location'];
		$output['body'] =$this->load->view('admins/user/loadfullmember', $data, true);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));	
	}
	
}
?>
