<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjobs extends Front_Controller
{
        public function deleteOldItineraries()
        {
                $directory=FCPATH.'userfiles/search';
            		foreach(glob("{$directory}/*") as $file)
            		{
                  if(time()-filemtime($directory) >= 86400)
            	    {
            					$this->recursiveRemoveDirectory($directory);
            	    }

            		}

                $directory=FCPATH.'userfiles/files';
            		foreach(glob("{$directory}/*") as $file)
            		{
            			if(time()-filemtime($directory) >= 86400)
            	    {
            					$this->recursiveRemoveDirectory($directory);
            	    }

            		}

            		$directory=FCPATH.'userfiles/multicountries';
            		foreach(glob("{$directory}/*") as $file)
            		{
            			if(time()-filemtime($directory) >= 86400)
            	    {
            					$this->recursiveRemoveDirectory($directory);
            	    }

            		}

        }

        function deleteSavedItineraries()
        {
            $directory=FCPATH.'userfiles/savedfiles';
            foreach(glob("{$directory}/*") as $file)
            {

              $ar=explode('/',$file);
              $iti=end($ar);
              $isloggedin=$this->checkUserIsLoggedInOrNot($iti);
              if($isloggedin==0)
              {
                  $this->recursiveRemoveDirectory($directory);
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
                       unlink($file);
                   }
               }
               $exp=explode('/',$directory);
               if(!in_array(end($exp),array('search','multicountries','files','savedfiles')))
               {
                 rmdir($directory);
               }
        }
}

?>
