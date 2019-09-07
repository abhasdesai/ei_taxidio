<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjobs extends Front_Controller
{
        function deleteData()
        {
            $folders=array('search','files','multicountries');
            for($i=0;$i<count($folders);$i++)
            {
                $this->deleteFolderAndFiles($folders[$i]);
            }
            $this->deleteSavedItineraries();

            /*$d=array(
              'name'=>date('Y-m-d H:i:s')
            );

            $this->db->insert('tt',$d);
            */
        }

        function deleteFolderAndFiles($folder)
        {
            $directory=FCPATH.'userfiles/'.$folder;
            $dirs = array_filter(glob("{$directory}/*"), 'is_dir');
            if(count($dirs))
            {
                foreach($dirs as $key=>$list)
                {
                    $subdirs = array_filter(glob("{$list}/*"), 'is_dir');
                    foreach($subdirs as $innerlist)
                    {
                        if(time()-filemtime($innerlist) >= 86400)
                        {
                            $this->recursiveRemoveDirectory($innerlist);
                        }
                    }
                    if(count(glob("$list/*"))==0){
                      rmdir($list);
                    }
                }
              }
        }

        function deleteSavedItineraries()
        {
            $directory=FCPATH.'userfiles/savedfiles';
            foreach(glob("{$directory}/*") as $file)
            {

                if(file_exists($file) || is_dir($file))
                {
                    $ar=explode('/',$file);
                    $iti=end($ar);
                    $isloggedin=$this->checkUserIsLoggedInOrNot($iti);
                    if($isloggedin==0)
                    {
                        $this->recursiveRemoveDirectory($file);
                    }
                }
            }
        }

        function checkUserIsLoggedInOrNot($iti)
        {
           $isloggedin=0;
           $this->db->select('isloggedin,last_login');
           $this->db->from('tbl_itineraries');
           $this->db->join('tbl_front_users','tbl_front_users.id=tbl_itineraries.user_id');
           $this->db->where('tbl_itineraries.id',$iti);
           $Q=$this->db->get();
           if($Q->num_rows()>0)
           {
               $data=$Q->row_array();
               if($data['isloggedin']==0)
               {
                  $isloggedin=0;
               }
               else
               {
                  if($data['isloggedin']==1 && time()-strtotime($data['last_login'])>=43200)
                  {
                     $isloggedin=0;
                  }
                  else
                  {
                     $isloggedin=1;
                  }

               }
           }
           return $isloggedin;
        }



        function recursiveRemoveDirectory($directory)
        {
               foreach(glob("{$directory}/*") as $file)
               {
                   if(is_dir($file)) {
                       $this->recursiveRemoveDirectory($file);
                   } else {
                     if(file_exists($file))
                     {
                       unlink($file);
                     }
                   }
               }
               $exp=explode('/',$directory);
               if(!in_array(end($exp),array('search','multicountries','files','savedfiles')))
               {
                 rmdir($directory);
               }
        }
        function generateCityPDFs()
        {
            $this->load->helper('pdf');
            $this->load->model('cron_city_pdf_m');
            $data = $this->cron_city_pdf_m->getcitydetails();
            $this->load->view("pdfs/city_pdfs", $data);
        }
        function sednNotificationUpcomingtrips()
        {
            $this->load->model('Account_fm');
            $data = $this->Account_fm->getAllTripsofTime();
           
            $this->load->library('email');
            $this->load->library('MY_Email');
            $this->load->model('Cms_fm');

            $email = $this->Cms_fm->getSettingsEmail();
            $setting_info = $this->Cms_fm->getSettings();

            $this->Account_fm->sendUpcomingTripEmails($email,$setting_info,$data);
          /*  echo "ddd";die;
           */
            //echo '<pre>';print_r($data);die;
            //$this->load->view("pdfs/city_pdfs", $data);
        }

}

?>
