<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller {

	function cityReport()
	{
		if($this->input->post('btnsubmit'))
		{	
			ob_start();
			$result=$this->Report_m->getCityReport();
			$tags=$this->Report_m->getCityTags();
			$ratings=$this->Report_m->getCityRatings();


			echo "City\t";					
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"), $result['city_name']);
			echo "\n";
			
			echo "Country\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"), $result['country_name']);
			echo "\n";
			
			echo "Continent\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"), $result['continent_name']);
			echo "\n";
			
			echo "Currency\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_local_currency']);
			echo "\n";

			echo "Time Zone\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_time_zone']);
			echo "\n";


			echo "Geographical Location\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_geographical_location']);
			echo "\n";

			echo "Neighbours\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_neighbours']);
			echo "\n";

			echo "History\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_history']);
			echo "\n";
			
			echo "Cultural Identity\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_cultural_identity']);
			echo "\n";

			echo "Natural Resources\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_natural_resources']);
			echo "\n";

			echo "Guides Tours\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_guides_tours']);
			echo "\n";

			echo "Transportation Hubs\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_transportation_hubs']);
			echo "\n";

			echo "Baggage Allowance\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_baggage_allowance']);
			echo "\n";

			echo "Food\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_food']);
			echo "\n";

			echo "Average Cost Meal-Drink\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_avg_cost_meal_drink']);
			echo "\n";

			echo "Shopping\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_shopping']);
			echo "\n";

			echo "Toursit Benefits\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_toursit_benefits']);
			echo "\n";

			echo "Essential Local Apps\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_essential_local_apps']);
			echo "\n";

			echo "Driving Requirement\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_driving']);
			echo "\n";

			echo "Travel Essentials\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_travel_essentials']);
			echo "\n";


			echo "Vendor ATM Commission\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_vendor_atm_commission']);
			echo "\n";

			echo "Language Spoken\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_language_spoken']);
			echo "\n";

			echo "Political Scenario\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_political_scenario']);
			echo "\n";

			echo "Economic Scenario\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_economic_scenario']);
			echo "\n";

			echo "Religion Belief\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_religion_belief']);
			echo "\n";

			echo "Visa Requirements\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_visa_requirements']);
			echo "\n";

			echo "Safety\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_safety']);
			echo "\n";

			echo "Embassies Consulates\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_embassies_consulates']);
			echo "\n";

			echo "Restricted Accessibility\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_restricted_accessibility']);
			echo "\n";

			echo "Emergencies\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_emergencies']);
			echo "\n";

			echo "Do and Donts\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_dos_donts']);
			echo "\n";

			echo "Tipping\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_tipping']);
			echo "\n";

			echo "Pet Important Policies\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_pet_imp_policies']);
			echo "\n";

			echo "Conclusion\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_conclusion']);
			echo "\n";

			echo "Significance\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_significance']);
			echo "\n";

			echo "Adventure Sports\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_adventure_sports']);
			echo "\n";

			echo "Staying Connected\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_staying_connected']);
			echo "\n";

			echo "Local Sports Stadiums\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_local_sports_stadium']);
			echo "\n";

			echo "Weather Seasonality\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_weather_seasonality']);
			echo "\n";

			echo "Transportation Costs\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_transportation_costs']);
			echo "\n";

			echo "Essential Vaccination\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['city_essential_vaccination']);
			echo "\n";

			echo "Relaxation & Spa\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['relaxation_spa']);
			echo "\n";

			echo "Restaurant & Nightlife\t";
			echo str_replace(array("\r\n", "\n\r", "\n", "\r","\""), array("", "", "", "","'"),$result['restaurant_nightlife']);
			echo "\n";

			echo "Hoho\t";
			if($result['city_hoho']==1){ echo 'YES'; } else { echo 'NO'; }
			echo "\n\n";

			echo "Tags\t";
			echo $tags;
			echo "\n\n";

		    echo "Ratings\t\n";


			foreach ($ratings as $list) {
				
			echo $list['rating_type'];
			echo "\t";
			echo $list['rating'];
			echo "\n";

			}

		    echo "\nPrimary Parameters\t";

		    echo "\nBudget\t";
		   

		    if($result['budget_hotel_per_night_to']==0)
		    {
		    	echo $result['budget_hotel_per_night_from'].'+';	
		    }
		    else
		    {
		    	 echo $result['budget_hotel_per_night_from'].'-'.$result['budget_hotel_per_night_to']."\n";
		    }	

			
			echo "Accomodation Type\t";
		    echo $result['accomodation_type']."\n";
			
			echo "DOI\t";
		    echo $result['doi_type']."\n";
			
			echo "Days\t";
		    if($result['flag_plus']==1){ echo htmlentities($result['days_range']).'+'; } else { echo $result['days_range']; }
		    echo "\n";
			
			echo "Weather\t";

			if($result['weather_temperature_to']>=999999)
		    {
		    	echo $result['weather_temperature_from'].'+';	
		    }
		    else
		    {
		    	echo $result['weather_temperature_from'].'-'.$result['weather_temperature_to'];	
		    }		    
		    echo "\n";
			
			echo "Traveler Time\t";
		    if($result['travel_time_slot_to']==999999)
		    {
		    	echo $result['travel_time_slot_from']."+";
		    }
		    else
		    {
		    	echo $result['travel_time_slot_from'].'-'.$result['travel_time_slot_to'];	
		    }		    
		    echo "\n";
			
			echo "\t\r\n";
								
			$filename = 'city.xls';						
			header('Content-type: application/ms-excel');
			header('Content-Disposition: attachment; filename='.$filename);	
			header("Pragma: no-cache"); 
			header("Expires: 0");	
			//ob_end_clean();	
			exit;

		}
		else
		{
			$data['webpagename'] = 'cityReport';
			$data['main'] = 'admins/adminfiles/Reports/cityReport';
			$data['section'] = 'Reports';
			$data['cities']=$this->Report_m->getAllCities();
			$data['page'] = 'Reports';
			$this->load->vars($data);
			$this->load->view('admins/templates/innermaster');
		}
		
	}

}

/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */