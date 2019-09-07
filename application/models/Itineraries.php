<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Itineraries extends Front_Controller
{

  	public function __construct()
    {
  		  parent::__construct();
  	}

    public function planned_itineraries()
    {
			$data['webpage'] = 'Itineraries';
			$data['main'] = 'itineraries/planned_itineraries';
			$this->load->library('pagination');
			$start_row=$this->uri->segment(2);
			$config["base_url"] = site_url('myquestions');
			$config["total_rows"] = $this->Itinerary_fm->countTotalPublicItineraries();
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
			$data['public_itineraries']=$this->Itinerary_fm->getTotalPublicItineraries($config["per_page"],$start_row);
			$data['pagination']=$this->pagination->create_links();
			$this->load->vars($data);
			$this->load->view('templates/innermaster');
    }

		public function planned_itinerary_forum($trip)
		{
				 $data['webpage'] = 'Itineraries';
				 $data['main'] = 'itineraries/planned_itinerary_forum';
				 $data['reviews']=$this->Itinerary_fm->getItineraryInfo($trip);
				 if(count($data['reviews'])<1)
				 {
					 show_404();
				 }
				 if($data['reviews']['trip_type']==1)
				 {
					 	$this->loadSingleCountryItinarary($data['reviews']['id']);
				 }
				 else if($data['reviews']['trip_type']==2)
				 {
					  $this->loadMultiCountryItinarary($data['reviews']['id']);
				 }
				 else
				 {
					  $this->loadSearchedCountryItinarary($data['reviews']['id']);
				 }
				 $this->load->vars($data);
				 $this->load->view('templates/innermaster');
		 }


			function loadSingleCountryItinarary($itineraryid)
			{

						$data['itineraryid']=$itineraryid;
						$singlecountryinfo=$this->Itinerary_fm->getSingleCountryItineraryDetails($itineraryid);
						$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/singlecountry','r');
						$arr=fgets($file);
						fclose($file);
						$nr=json_decode($arr,TRUE);

						$returnkey='';
						$firstcityid='';
						foreach ($nr as $key => $list)
						{

							foreach ($list as $keyinner => $innerlist)
							{
									if($innerlist['slug']==$slug)
									{
										 $returnkey=$key;
										 $firstcityid=$list[0]['id'];
										 foreach($list as $ids)
										 {
											$idsArray[]=$ids['id'];
										 }
										 break;
									}
							}
						}


						$data['countrynm']=$this->Home_fm->getCountryNameFromSlug($slug);
						$data['country']=md5($returnkey);
						$cominineCountryidwithcityid=string_decode($secretid).'-'.$firstcityid;
						$data['countryid_encrypt']=string_encode($cominineCountryidwithcityid);
						if(!isset($returnkey) || $returnkey=='')
						{
							redirect('trips');
						}

						$attractioncountries=array();
						if(count($nr[$returnkey]))
						{
							$attractioncountries=$nr[$returnkey];
						}

						$data['webpage'] = 'attraction_listings';
						$data['main'] = 'myaccount/trip/attraction_listings';
						$data['attractioncities'] = $attractioncountries;
						$data['citypostid']=$cityfile = md5($attractioncountries[0]['id']);
						$countrandtype=$returnkey.'-single';
						$data['secretkey']=string_encode($itineraryid);
						$data['basic']=$basic=$this->Home_fm->getLatandLongOfCity($cityfile);
						$data['countryid']=$basic['country_id'];
						$data['cityimage']=$basic['cityimage'];
						$data['basiccityname']=$basic['city_name'];
						$data['countryconclusion']=$basic['country_conclusion'];
						$data['countrybanner']=$basic['countrybanner'];
						$data['latitude']=$basic['citylatitude'];
						$data['longitude']=$basic['citylongitude'];
						$filestore= file_get_contents(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile);

						$attraction_decode=json_decode($filestore,TRUE);
						$sort = array();
						foreach($attraction_decode as $k=>$v)
						{
								$sort['isselected'][$k] = $v['isselected'];
								$sort['order'][$k] = $v['order'];
								$sort['tag_star'][$k] = $v['properties']['tag_star'];
						}
						array_multisort($sort['isselected'], SORT_DESC,$sort['order'], SORT_ASC,$attraction_decode);
						$file=fopen(FCPATH.'userfiles/savedfiles/'.$itineraryid.'/'.$cityfile,'w');
						fwrite($file,json_encode($attraction_decode));
						fclose($file);
						$data['filestore']=json_encode($attraction_decode);
						$this->load->vars($data);
						$this->load->view('templates/innermaster');
			}

		function loadMultiCountryItinarary()
		{

		}

		function loadSearchedCountryItinarary()
		{

		}

}
