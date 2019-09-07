<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Triphotels extends User_Controller 
{

	function checkItinaryIdandUserId($itinaryid)
	{
        $result=get_invited_trip_details($itinaryid);
        $this->db->select('id');
        $this->db->from('tbl_itineraries');
        if($result!==FALSE){
            $this->db->where('user_id',$result['user_id']);
        }
        else
        {
            $this->db->where('user_id',$this->session->userdata('fuserid'));
        }
        $this->db->where('id',$itinaryid);
        $Q=$this->db->get();
		if($Q->num_rows()<1)
		{
			$this->session->set_userdata('error','Something went wrong.');
			redirect('trips');
		}
	}

    function checkuniqueid($itineraryid,$searchtype)
    {
        if(!is_dir(FCPATH.'userfiles/savedfiles/'.$itineraryid))
        {
           $this->session->set_userdata('error','Something went wrong.');
           redirect('trips');
        }
    }


	function hotelListsForSavedCountry($itinaryid)
	{
		$iti_enc=explode('-',string_decode($itinaryid));
		$iti=$iti_enc[0];
		$city_id=$iti_enc[1];
		$this->checkItinaryIdandUserId($iti);
		$data['webpage'] = 'hotel_bookings';
		$data['main'] = 'myaccount/trip/hotels/showHotels';
		$data['recommendation']=$itinaryid;
        $data['account']=1;
		$data['countrynm']=$this->Triphotels_fm->getSingleCountryName($iti);

	    $data['type']=1; //for singlecountry or multicountry
	    $data['cities']=$this->Triphotels_fm->getAllCities($iti,$data['countrynm']['id']);
		$data['cityid']=md5($city_id);
	    $data['citybasic']=$this->Triphotels_fm->getCityBasic($city_id);
    	$this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function hotelListsForSavedCountry_ajax($itinaryid)
	{
		if($this->input->is_ajax_request())
		{
			$start_row=$this->uri->segment(4);
            if(trim($start_row)=='')
            {
                  $start_row=0;
            }
            if(isset($_POST['city_id']) && $_POST['city_id']!='' && $_POST['city_id']!=1)
            {
                  $postcityid=$data['postcityid']=$_POST['city_id'];
            }
            else
            {
                   $postcityid=$data['postcityid']=1;
            }

            if(isset($_POST['drop']) && $_POST['drop']==1)
            {
                $data['citybasic']=$this->Triphotels_fm->getCityBasicmd5($_POST['city_id']);  
                if($data['citybasic']['citybanner']!='' && file_exists(FCPATH.'userfiles/cities/banner/'.$data['citybasic']['citybanner']))
                   {
                        $data['citybanner']=site_url('userfiles/cities/banner').'/'.$data['citybasic']['citybanner'];
                   }
                   else
                   {
                        $data['citybanner']=site_url('assets/images/countrynoimage.jpg');
                   }  
                   $data['citynm']=$data['citybasic']['city_name'];
            }


            $this->load->library('pagination');
            $countryid=$this->Triphotels_fm->getCountryId($postcityid);
            $config["base_url"] = base_url() . "Triphotels/hotelListsForSavedCountry-ajax/".$itinaryid;
            //$config["total_rows"] = $this->Hotel_fm->countHotels($recommendation);
            //echo $itinaryid;die;
            $config["total_rows"] = $this->Triphotels_fm->countHotels($itinaryid,$postcityid,$countryid);
            $config["full_tag_open"] = "<ul class='pagination-custom'>";
            $config["full_tag_close"] = "</ul>";
            $config["num_tag_open"] = "<li class='pagination-item-custom'>";
            $config["num_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
            $config["cur_tag_close"] = "</a></span></li>";
            $config['prev_link'] = 'Previous';
            $config['next_link'] = 'Next';
            $config["prev_tag_open"] = "<li class='pagination-item-custom first'>";
            $config["prev_tag_close"] = "</li>";
            $config["next_tag_open"] = "<li class='pagination-item-custom last'>";
            $config["next_tag_close"] = "</li>";
            $config["first_link"] = "<li style='float:left'>&lsaquo; First";
            $config["first_link"] = "</li>";
            $config["last_link"] = "<li>Last &rsaquo;";
            $config["last_link"] = "</li>";
            $config['per_page'] = 15;
            $this->pagination->initialize($config);
	        $data["hotels"] = $this->Triphotels_fm->getAllHotels($itinaryid,$postcityid,$config["per_page"],$start_row,$countryid);
    		$data["pagination"]=$this->pagination->create_links();
    		$_html=$this->load->view('myaccount/trip/hotels/loadHotelsAjax', $data,TRUE);
    	      echo $_html;
		}	            
	}


    function hotelListsForSavedMultiCountry($secretid)
    {
            $data['webpage'] = 'hotel_bookings';
            $data['main'] = 'myaccount/trip/hotels/showMultiCountryHotels';
            $data['recommendation']=$secretid;

            $encryptid=explode('-',string_decode($secretid));
            
            $lastkey=key(array_slice( $encryptid, -1, 1, TRUE ));
            $iti=$encryptid[$lastkey];

            $this->checkItinaryIdandUserId($iti);
            $data['account']=1;
            unset($encryptid[count($encryptid)-1]);
            $city_id=$encryptid[count($encryptid)-1];
            
            $countryid_db=$this->Hotel_fm->getCountryId($city_id);

            $data['countrynm']=$this->Triphotels_fm->getCountryName($encryptid[0]);
            $data['type']=2; //for singlecountry or multicountry
            $data['cities']=$this->Triphotels_fm->getAllMultiCountryCities($encryptid,$iti);
            $data['cityid']=$city_id;
            $data['citybasic']=$this->Hotel_fm->getCityBasic($city_id);
            $data['countryid']=$countryid_db['country_id'];
            $data['iti']=$iti;
            $this->load->vars($data);
            $this->load->view('templates/innermaster');
    }

    function hotelListsForSavedMultiCountry_ajax($recommendation)
    {
        if($this->input->is_ajax_request())
        {
            $start_row=$this->uri->segment(4);
            if(trim($start_row)=='')
            {
                    $start_row=0;
            }
            $data['countrynm']='';
            if(isset($_POST['ids']) && $_POST['ids']!='')
            {
                  $ids=explode('-',$_POST['ids']);
                  $countryid=$ids[0];
                  $cityid=$ids[1];
                  $countryname=$this->Hotel_fm->getPostCountryName($countryid);
                  if(count($countryname))
                  {
                        $data['countrynm']=$countryname['country_name'];
                  }

            }
            else
            {
                  $countryid=$_POST['countryid'];
                  $cityid=$_POST['cityid'];
            }
            $data['iti']=$_POST['iti'];

            if(isset($_POST['drop']) && $_POST['drop']==1)
            {
                  
                $data['citybasic']=$this->Hotel_fm->getCityBasic($cityid);  
                if($data['citybasic']['citybanner']!='' && file_exists(FCPATH.'userfiles/cities/banner/'.$data['citybasic']['citybanner']))
                   {
                        $data['citybanner']=site_url('userfiles/cities/banner').'/'.$data['citybasic']['citybanner'];
                   }
                   else
                   {
                        $data['citybanner']=site_url('assets/images/countrynoimage.jpg');
                   }  
                   $data['citynm']=$data['citybasic']['city_name'];
            }

            $this->load->library('pagination');
            $config["base_url"] = base_url() . "triphotels/hotelListsForSavedMultiCountry-ajax/".$recommendation;
            $data['postcountryid']=$countryid;
            $data['postcityid']=$cityid;
            $config["total_rows"] = $this->Triphotels_fm->countMultiCountryHotels($countryid,$cityid);
            $config["full_tag_open"] = "<ul class='pagination-custom'>";
            $config["full_tag_close"] = "</ul>";
            $config["num_tag_open"] = "<li class='pagination-item-custom'>";
            $config["num_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
            $config["cur_tag_close"] = "</a></span></li>";
            $config['prev_link'] = 'Previous';
            $config['next_link'] = 'Next';
            $config["prev_tag_open"] = "<li class='pagination-item--wide first'>";
            $config["prev_tag_close"] = "</li>";
            $config["next_tag_open"] = "<li class='pagination-item--wide last'>";
            $config["next_tag_close"] = "</li>";
            $config["first_link"] = "<li style='float:left'>&lsaquo; First";
            $config["first_link"] = "</li>";
            $config["last_link"] = "<li>Last &rsaquo;";
            $config["last_link"] = "</li>";
            $config['per_page'] = 15;
            $this->pagination->initialize($config);
                $data["hotels"] = $this->Triphotels_fm->getAllMultiCountryHotels($countryid,$cityid,$config["per_page"],$start_row);
                $data["pagination"]=$this->pagination->create_links();
                $_html=$this->load->view('myaccount/trip/hotels/loadMultiCountryHotelsAjax', $data,TRUE);
                echo $_html;
        }               
    }


    function hotelListsForsearchedCity($encryptid)
    {
            $encryptidvar=explode('-',string_decode($encryptid));
            //echo "<pre>";print_r($encryptidvar);die;
            $filename=$encryptidvar[0];
            $cityid=$encryptidvar[1];
            $iti=$encryptidvar[2];
            $data['account']=1;
            $this->checkuniqueid($iti,$searchtype='search');
            $data['webpage'] = 'hotel_bookings';
            $data['main'] = 'myaccount/trip/hotels/showSearchedCityHotels';
            $data['recommendation']=$encryptid;
            $data['countrynm']=$this->Triphotels_fm->getSearchedCityName($filename,$cityid,$iti);
            //echo "<pre>";print_r($data['countrynm']);die;
            $data['type']=3;
            $data['cities']=$this->Triphotels_fm->getAllSearchedCities($filename,$cityid,$iti);
            $key=$this->Triphotels_fm->getKey($filename,$cityid,$iti);
            if(!is_numeric($key))
            {
                 $this->session->set_userdata('error','Something went wrong.');
                 redirect('trips');
            }            
            $data['cityid']=$data['cities'][$key]['plainid'];
            $data['citybasic']=$this->Hotel_fm->getCityBasic($data['cityid']);
            $this->load->vars($data);
            $this->load->view('templates/innermaster');    
    }

    function hotelListsForsearchedCity_ajax($recommendation)
    {
        if($this->input->is_ajax_request())
        {

            $start_row=$this->uri->segment(4);
            if(trim($start_row)=='')
            {
                    $start_row=0;
            }

            if(isset($_POST['ids']) && $_POST['ids']!='')
            {
                  $ids=explode('-',$_POST['ids']);
                  $countryid=$ids[0];
                  $cityid=$ids[1];
            }
            else
            {
                   $cityid=$_POST['cityid'];
                   $filenmencode=explode('-',string_decode($_POST['recommendation']));
                   $filenm=$filenmencode[0];
                   $iti=$filenmencode[2];
                 
            }

             if(isset($_POST['drop']) && $_POST['drop']==1)
            {
                $cityid=$_POST['cityid'];  
                $data['citybasic']=$this->Hotel_fm->getCityBasic($_POST['cityid']);  
                if($data['citybasic']['citybanner']!='' && file_exists(FCPATH.'userfiles/cities/banner/'.$data['citybasic']['citybanner']))
                   {
                        $data['citybanner']=site_url('userfiles/cities/banner').'/'.$data['citybasic']['citybanner'];
                   }
                   else
                   {
                        $data['citybanner']=site_url('assets/images/countrynoimage.jpg');
                   }  
                   $data['citynm']=$data['citybasic']['city_name'];
                   /*
                   $url=site_url('country').'/'.$data['citybasic']['slug'];
                   $data['countryconclusion']=word_limiter($data['citybasic']['city_conclusion'],110).'<span class="citycon"><a class="readmore" href="'.$url.'" target="_blank">Read More</a></span>';*/
                   
            }

            $this->load->library('pagination');
            $config["base_url"] = base_url() . "triphotels/hotelListsForsearchedCity-ajax/".$recommendation;
            $data['postcityid']=$cityid;
            $data['postrecommendation']=$_POST['recommendation'];
            $config["total_rows"] = $this->Triphotels_fm->countSearchedHotels($filenm,$cityid,$iti);
            //echo $this->db->last_query();die;
            $config["full_tag_open"] = "<ul class='pagination-custom'>";
            $config["full_tag_close"] = "</ul>";
            $config["num_tag_open"] = "<li class='pagination-item-custom'>";
            $config["num_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='pagination-item-custom is-active'><a href='javascript:void(0)' class=''>";
            $config["cur_tag_close"] = "</a></span></li>";
            $config['prev_link'] = 'Previous';
            $config['next_link'] = 'Next';
            $config["prev_tag_open"] = "<li class='pagination-item--wide first'>";
            $config["prev_tag_close"] = "</li>";
            $config["next_tag_open"] = "<li class='pagination-item--wide last'>";
            $config["next_tag_close"] = "</li>";
            $config["first_link"] = "<li style='float:left'>&lsaquo; First";
            $config["first_link"] = "</li>";
            $config["last_link"] = "<li>Last &rsaquo;";
            $config["last_link"] = "</li>";
            $config['per_page'] = 15;
            $this->pagination->initialize($config);
            $data["hotels"] = $this->Triphotels_fm->getSearchedHotels($filenm,$cityid,$config["per_page"],$start_row,$iti);
            //echo "<pre>";
            //print_r($data["hotels"]);die;
                  //echo $this->db->last_query();die;
            $data["pagination"]=$this->pagination->create_links();
            $_html=$this->load->view('myaccount/trip/hotels/loadshowSearchedCityHotelsajax', $data,TRUE);
            echo $_html;
            }        
    }





}

?>
