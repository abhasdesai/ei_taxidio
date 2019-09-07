<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hotels extends User_Controller {

	public function __construct() {
		parent::__construct();
	}

  function checkuniqueid($uniqueid,$searchtype)
  {
    if(!is_dir(FCPATH.'userfiles/'.$searchtype.'/'.$this->session->userdata('randomstring').'/'.$uniqueid))
    {
      redirect($_SERVER['HTTP_REFERER']);
    }
  }

	function showHotels($countryid)
	{
		$data['webpage'] = 'hotel_bookings';
		$data['main'] = 'showHotels';
		$data['recommendation']=$countryid;
    $data['countrynm']=$this->Hotel_fm->getSingleCountryName($countryid);
    $data['type']=1; //for singlecountry or multicountry
    $encryptid=explode('-',string_decode($countryid));
    $city_id=$encryptid[1];
    $data['cities']=$this->Hotel_fm->getAllCities($countryid);
		$data['cityid']=md5($city_id);
    $data['citybasic']=$this->Hotel_fm->getCityBasic($city_id);
    $this->load->vars($data);
		$this->load->view('templates/innermaster');
	}

	function getHotelsajax($recommendation)
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
                $data['citybasic']=$this->Hotel_fm->getCityBasicmd5($_POST['city_id']);  
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
            $config["base_url"] = base_url() . "hotels/getHotelsajax/".$recommendation;
            //$config["total_rows"] = $this->Hotel_fm->countHotels($recommendation);
            $config["total_rows"] = $this->Hotel_fm->countHotels($recommendation,$postcityid);
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
		        $data["hotels"] = $this->Hotel_fm->getAllHotels($recommendation,$postcityid,$config["per_page"],$start_row);
        		$data["pagination"]=$this->pagination->create_links();
        		$_html=$this->load->view('loadHotelsAjax', $data,TRUE);
        	      echo $_html;
		}	            
		
	}

	function showHotelsOfMultiCountries($countryid)
	{
            $data['webpage'] = 'hotel_bookings';
            $data['main'] = 'showMultiCountryHotels';
            $data['recommendation']=$countryid;
            $encryptid=explode('-',string_decode($countryid));
            $lastkey=key(array_slice( $encryptid, -1, 1, TRUE ));
            $uniqueid=$encryptid[$lastkey];
            $this->checkuniqueid($uniqueid,$searchtype='multicountries');
            
            //unset uniqueid
            unset($encryptid[count($encryptid)-1]);

           
            $city_id=$encryptid[count($encryptid)-1];
            unset($encryptid[count($encryptid)-1]);
            $countryid_db=$this->Hotel_fm->getCountryId($city_id);
            $data['countrynm']=$this->Hotel_fm->getCountryName($encryptid[0]);
            $data['type']=2; //for singlecountry or multicountry
            $data['cities']=$this->Hotel_fm->getAllMultiCountryCities($encryptid,$uniqueid);
            $data['cityid']=$city_id;
            $data['citybasic']=$this->Hotel_fm->getCityBasic($city_id);
            $data['countryid']=$countryid_db['country_id'];
            $data['uniqueid']=$uniqueid;
            $this->load->vars($data);
            $this->load->view('templates/innermaster');
	}

	function getMultiCountryHotelsajax($recommendation)
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
            $config["base_url"] = base_url() . "hotels/getMultiCountryHotelsajax/".$recommendation;
            $data['postcountryid']=$countryid;
            $data['postcityid']=$cityid;
            $config["total_rows"] = $this->Hotel_fm->countMultiCountryHotels($countryid,$cityid);
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
		        $data["hotels"] = $this->Hotel_fm->getAllMultiCountryHotels($countryid,$cityid,$config["per_page"],$start_row);
		        $data["pagination"]=$this->pagination->create_links();
		        $_html=$this->load->view('loadMultiCountryHotelsAjax', $data,TRUE);
		        echo $_html;
		}	            
	}

      function showSearchedCityHotels($encryptid)
      {
            $encryptidvar=explode('-',string_decode($encryptid));
            $filename=$encryptidvar[0];
            $cityid=$encryptidvar[1];
            $uniqueid=$encryptidvar[2];
            $this->checkuniqueid($uniqueid,$searchtype='search');
            $data['webpage'] = 'hotel_bookings';
            $data['main'] = 'showSearchedCityHotels';
            $data['recommendation']=$encryptid;
            $data['countrynm']=$this->Hotel_fm->getSearchedCityName($filename,$cityid,$uniqueid);
            $data['type']=3;
            $data['cities']=$this->Hotel_fm->getAllSearchedCities($filename,$cityid,$uniqueid);
            $key=$this->Hotel_fm->getKey($filename,$cityid,$uniqueid);
            if(!is_numeric($key))
            {
                $this->redirectUserToAttractionPage();
            }            
            $data['cityid']=$data['cities'][$key]['plainid'];
            $data['citybasic']=$this->Hotel_fm->getCityBasic($data['cityid']);
            $this->load->vars($data);
            $this->load->view('templates/innermaster');       
      }

      function showSearchedCityHotelsajax($recommendation)
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
                   $uniqueid=$filenmencode[2];
                 
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
            $config["base_url"] = base_url() . "hotels/showSearchedCityHotelsajax/".$recommendation;
            $data['postcityid']=$cityid;
            $data['postrecommendation']=$_POST['recommendation'];
            $config["total_rows"] = $this->Hotel_fm->countSearchedHotels($filenm,$cityid,$uniqueid);
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
            $data["hotels"] = $this->Hotel_fm->getSearchedHotels($filenm,$cityid,$config["per_page"],$start_row,$uniqueid);
            //echo "<pre>";
            //print_r($data["hotels"]);die;
                  //echo $this->db->last_query();die;
            $data["pagination"]=$this->pagination->create_links();
            $_html=$this->load->view('loadshowSearchedCityHotelsajax', $data,TRUE);
            echo $_html;
            }                 
      }

      function redirectUserToAttractionPage()
      {
          $file=fopen(FCPATH.'userfiles/search/'.$this->session->userdata('randomstring').'/inputs','r');
          $inputfile=json_decode(fgets($file),TRUE);
          fclose($file);

          $url=site_url('cityAttractions').'?sdestination='.urlencode($inputfile['sdestination']).'&sdays='.$inputfile['sdays'].'&sstart_date='.urlencode($inputfile['sstart_date']);
          redirect($url);
      }

}

?>
