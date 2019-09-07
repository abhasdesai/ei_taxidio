<?php
class Faq extends Admin_Controller
{

	public function index()
	{
		$data['webpagename']='Faq';
        $data['page'] = 'Faq';
        $data['section'] = 'FAQ';
		$data['main']='admins/adminfiles/Faq/index';
		$this->load->vars($data);
		$this->load->view('admins/templates/innermaster');
	}
	
	
	public function getTable()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id','category','question','answer','id');
        
        // DB table to use
        $sTable = 'tbl_faq';
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
					$this->db->or_like('question',$this->db->escape_like_str($sSearch));
                    $this->db->or_like('answer',$this->db->escape_like_str($sSearch));
                    $this->db->or_like('category',$this->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select Data
        $this->db->select("SQL_CALC_FOUND_ROWS tbl_faq.id,question,answer,category", FALSE);
		$this->db->from('tbl_faq');
        $this->db->join('tbl_faq_category','tbl_faq_category.id=tbl_faq.category_id');
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

                $this->form_validation->set_rules('category_id','Category','trim|required');
                $this->form_validation->set_rules('question','Question','trim|required');
                $this->form_validation->set_rules('answer','Answer','trim|required');
                if($this->form_validation->run()==False)
                {
                    $this->session->set_flashdata('error','Treansaction Failed.');
                    $data['webpagename']='Faq';
                    $data['section'] = 'Add FAQ';
                    $data['category']=$this->Faq_m->getAllCategories();
                    $data['page']='Faq';
                    $data['main']='admins/adminfiles/Faq/add';
                    $this->load->vars($data);
                    $this->load->view('admins/templates/innermaster');
                }
                else
                {
                    $this->Faq_m->add();
                    $this->session->set_flashdata('success','Transaction Successful.');
                    redirect('admins/Faq');
                }


                
        }
        else
        {
            
            $data['webpagename']='Faq';
            $data['section'] = 'Add FAQ';
            $data['page']='Faq';
            $data['category']=$this->Faq_m->getAllCategories();
            $data['main']='admins/adminfiles/Faq/add';
            $this->load->vars($data);
            $this->load->view('admins/templates/innermaster');
        }
        
    }
    
    function edit($id)
    {
        if($this->input->post('btnsubmit'))
        {
            $this->form_validation->set_rules('category_id','Category','trim|required');
            $this->form_validation->set_rules('question','Question','trim|required');
            $this->form_validation->set_rules('answer','Answer','trim|required');
            if($this->form_validation->run()==False)
            {
                $data['webpagename']='Faq';
                $data['section'] = 'Edit FAQ';
                $data['page']='Faq';
                $data['id']=$id;
                $data['category']=$this->Faq_m->getAllCategories();
                $data['faq']=$this->Faq_m->getDetailsById($id);
                $data['main']='admins/adminfiles/Faq/edit';
                $this->load->vars($data);
                $this->load->view('admins/templates/innermaster');
            }
            else
            {
               $this->Faq_m->edit();
               $this->session->set_flashdata('success','Transaction Successful.');
               redirect('admins/Faq'); 
            }

                
        }
        else
        {
            $data['webpagename']='Faq';
            $data['section'] = 'Edit FAQ';
            $data['page']='Faq';
            $data['id']=$id;
            $data['category']=$this->Faq_m->getAllCategories();
            $data['faq']=$this->Faq_m->getDetailsById($id);
            $data['main']='admins/adminfiles/Faq/edit';
            $this->load->vars($data);
            $this->load->view('admins/templates/innermaster');
        }
    }


    function delete($id)
    {
        $this->Faq_m->delete($id);
        $this->session->set_flashdata('success','Transaction Successful.');
        redirect('admins/Faq');
    }




    public function faqcategory() {

        $data['webpagename'] = 'faqcategory';
        $data['main'] = 'admins/adminfiles/Faq/category';
        $data['section'] = 'FAQ Category';
        $data['page'] = 'FAQ Category';
        $this->load->vars($data);
        $this->load->view('admins/templates/innermaster');
    }

    public function category_getTable() {
        $aColumns = array('id', 'category', 'id');

        // DB table to use
        $sTable = 'tbl_faq_category';
        //

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        // Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, true);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, true);

                if ($bSortable == 'true') {
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
        if (isset($sSearch) && !empty($sSearch)) {

            $where = "(category like '%" . $this->db->escape_like_str($sSearch) . "%')";
            $this->db->where($where);

        }

        // Select Data
        $this->db->select("SQL_CALC_FOUND_ROWS id,category", FALSE);
        $this->db->from('tbl_faq_category');
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
            'aaData' => array(),
        );

        foreach ($rResult->result_array() as $aRow) {
            $row = array();

            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }

            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    function add_category() {

        if ($this->input->post('btnsubmit')) {

            $this->form_validation->set_rules('category', 'Category', 'trim|required|max_length[100]|is_unique[tbl_faq_category.category]');
           
            if ($this->form_validation->run() == FALSE) {
                $data['webpagename'] = 'faqcategory';
                $data['section'] = 'Add FAQ Category';
                $data['page'] = 'FAQ Categories';
                $data['main'] = 'admins/adminfiles/Faq/add_category';
                $this->load->vars($data);
                $this->load->view('admins/templates/innermaster');
            } else {
                $this->Faq_m->category_add();
                $this->session->set_flashdata('success', 'Transaction Successful.');
                redirect('admins/Faq/faqcategory');
            }

        } else {
            $data['webpagename'] = 'faqcategory';
            $data['section'] = 'Add FAQ Category';
            $data['page'] = 'FAQ Categories';
            $data['main'] = 'admins/adminfiles/Faq/add_category';
            $this->load->vars($data);
            $this->load->view('admins/templates/innermaster');
        }

    }

    function edit_category($id) {
        if ($this->input->post('btnsubmit')) {

            $this->form_validation->set_rules('category', 'Category', 'trim|required|max_length[100]|callback_check_category_edit');


            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Transaction Failed.');
                $data['webpagename'] = 'faqcategory';
                $data['section'] = 'Edit FAQ Category';
                $data['page'] = 'FAQ Categories';
                $data['id'] = $id;
                $data['category'] = $this->Faq_m->getCategoryDetailsById($id);
                $data['main'] = 'admins/adminfiles/Faq/edit_category';
                $this->load->vars($data);
                $this->load->view('admins/templates/innermaster');
            } else {
                $this->Faq_m->category_edit();
                $this->session->set_flashdata('success', 'Transaction Successful.');
                redirect('admins/Faq/faqcategory');
            }

        } else {

            $data['webpagename'] = 'faqcategory';
            $data['section'] = 'Edit FAQ Category';
            $data['page'] = 'FAQ Categories';
            $data['id'] = $id;
            $data['category'] = $this->Faq_m->getCategoryDetailsById($id);
            $data['main'] = 'admins/adminfiles/Faq/edit_category';
            $this->load->vars($data);
            $this->load->view('admins/templates/innermaster');
        }
    }

    function check_category_edit($category) {
        return $this->Faq_m->check_category_edit($category);

    }

    function delete_category($id) {
        $this->Faq_m->category_delete($id);
        $this->session->set_flashdata('success', 'Transaction Successful.');
        redirect('admins/Faq/faqcategory');

    }
    
    
}
?>
