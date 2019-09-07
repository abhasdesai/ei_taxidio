<div class="container">
	<p><?php echo $city['city_conclusion'] ?></p>
</div>    

<section class="box box-no-bottom" data-box-img="https://placehold.it/1980x800/eee/aaa">


<?php 
	$city_print_data = array();
	$flag=1;
	if($city['city_geographical_location']!=""){
		$city_print_data[$flag]['name']='Geographical Location';
		$city_print_data[$flag]['description']=$city['city_geographical_location'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-compass"></i>';
		$flag++;	
	}
	if($city['city_neighbours']!=""){
		$city_print_data[$flag]['name']='Neighbours';
		$city_print_data[$flag]['description']=$city['city_neighbours'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-globe"></i>';
		$flag++;	
	}
	if($city['city_cultural_identity']!=""){
		$city_print_data[$flag]['name']='Cultural Identity';
		$city_print_data[$flag]['description']=$city['city_cultural_identity'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-map-signs"></i>';
		$flag++;	
	}
	if($city['city_baggage_allowance']!=""){
		$city_print_data[$flag]['name']='Baggage Allowance';
		$city_print_data[$flag]['description']=$city['city_baggage_allowance'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_food']!=""){
		$city_print_data[$flag]['name']='Food';
		$city_print_data[$flag]['description']=$city['city_food'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_avg_cost_meal_drink']!=""){
		$city_print_data[$flag]['name']='Meal Drink';
		$city_print_data[$flag]['description']=$city['city_avg_cost_meal_drink'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_shopping']!=""){
		$city_print_data[$flag]['name']='Shopping';
		$city_print_data[$flag]['description']=$city['city_shopping'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_toursit_benefits']!=""){
		$city_print_data[$flag]['name']='Toursit Benefits';
		$city_print_data[$flag]['description']=$city['city_toursit_benefits'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_essential_local_apps']!=""){
		$city_print_data[$flag]['name']='Essential Local Apps';
		$city_print_data[$flag]['description']=$city['city_essential_local_apps'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_driving']!=""){
		$city_print_data[$flag]['name']='Driving';
		$city_print_data[$flag]['description']=$city['city_driving'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_travel_essentials']!=""){
		$city_print_data[$flag]['name']='Travel Essentials';
		$city_print_data[$flag]['description']=$city['city_travel_essentials'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_local_currency']!=""){
		$city_print_data[$flag]['name']='Currency';
		$city_print_data[$flag]['description']=$city['city_local_currency'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_vendor_atm_commission']!=""){
		$city_print_data[$flag]['name']='Vendor ATM Commission';
		$city_print_data[$flag]['description']=$city['city_vendor_atm_commission'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_time_zone']!=""){
		$city_print_data[$flag]['name']='Time Zone';
		$city_print_data[$flag]['description']=$city['city_time_zone'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_language_spoken']!=""){
		$city_print_data[$flag]['name']='Language Spoken';
		$city_print_data[$flag]['description']=$city['city_language_spoken'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_political_scenario']!=""){
		$city_print_data[$flag]['name']='Political Scenario';
		$city_print_data[$flag]['description']=$city['city_political_scenario'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_economic_scenario']!=""){
		$city_print_data[$flag]['name']='Economic Scenario';
		$city_print_data[$flag]['description']=$city['city_economic_scenario'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_religion_belief']!=""){
		$city_print_data[$flag]['name']='Religion Belief';
		$city_print_data[$flag]['description']=$city['city_religion_belief'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_visa_requirements']!=""){
		$city_print_data[$flag]['name']='Visa Requirements';
		$city_print_data[$flag]['description']=$city['city_visa_requirements'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_safety']!=""){
		$city_print_data[$flag]['name']='Safety';
		$city_print_data[$flag]['description']=$city['city_safety'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_embassies_consulates']!=""){
		$city_print_data[$flag]['name']='Embassies Consulates';
		$city_print_data[$flag]['description']=$city['city_embassies_consulates'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_restricted_accessibility']!=""){
		$city_print_data[$flag]['name']='Restricted Accessibility';
		$city_print_data[$flag]['description']=$city['city_restricted_accessibility'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_emergencies']!=""){
		$city_print_data[$flag]['name']='Emergencies';
		$city_print_data[$flag]['description']=$city['city_emergencies'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_dos_donts']!=""){
		$city_print_data[$flag]['name']='Dos Donts';
		$city_print_data[$flag]['description']=$city['city_dos_donts'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_tipping']!=""){
		$city_print_data[$flag]['name']='Tipping';
		$city_print_data[$flag]['description']=$city['city_tipping'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_pet_imp_policies']!=""){
		$city_print_data[$flag]['name']='Pet IMP Policies';
		$city_print_data[$flag]['description']=$city['city_pet_imp_policies'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_conclusion']!=""){
		$city_print_data[$flag]['name']='Conclusion';
		$city_print_data[$flag]['description']=$city['city_conclusion'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_significance']!=""){
		$city_print_data[$flag]['name']='Significance';
		$city_print_data[$flag]['description']=$city['city_significance'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_weather_seasonality']!=""){
		$city_print_data[$flag]['name']='Weather Seasonality';
		$city_print_data[$flag]['description']=$city['city_weather_seasonality'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_transportation_costs']!=""){
		$city_print_data[$flag]['name']='Transportation Costs';
		$city_print_data[$flag]['description']=$city['city_transportation_costs'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_essential_vaccination']!=""){
		$city_print_data[$flag]['name']='Essential Vaccination';
		$city_print_data[$flag]['description']=$city['city_essential_vaccination'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_staying_connected']!=""){
		$city_print_data[$flag]['name']='Staying Connected';
		$city_print_data[$flag]['description']=$city['city_staying_connected'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_local_sports_stadium']!=""){
		$city_print_data[$flag]['name']='Local Sports Stadium';
		$city_print_data[$flag]['description']=$city['city_local_sports_stadium'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['restaurant_nightlife']!=""){
		$city_print_data[$flag]['name']='Restaurant Nightlife';
		$city_print_data[$flag]['description']=$city['restaurant_nightlife'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
	if($city['city_guides_tours']!=""){
		$city_print_data[$flag]['name']='Guides Tours';
		$city_print_data[$flag]['description']=$city['city_guides_tours'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-map-signs"></i>';
		$flag++;	
	}
	if($city['city_transportation_hubs']!=""){
		$city_print_data[$flag]['name']='Transportation Hubs';
		$city_print_data[$flag]['description']=$city['city_transportation_hubs'];
		$city_print_data[$flag]['faiocn']='<i aria-hidden="true" class="fa fa-sun-o"></i>';
		$flag++;	
	}
?>	
		<?php if(count($city)){ ?>

			<div class="box-img"><span></span></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div id="big-tabs-nav" class="slide-navigation align-center">
							
							<div><span class="counter">Showing <span><b  id="currentval">1</b></span> Of <?php echo count($city_print_data); ?></span></div>	
							
							<ul class="inline-list">
								<li>
									<a href="#" class="bg-white" data-target="prev" data-counter="<?php echo count($city_print_data); ?>" id="prevbtn" onClick="getPrev($(this).attr('data-counter'))">
										<i class="fa fa-angle-left" aria-hidden="true"></i>
									</a>
								</li>
								<li>
									<a href="#" class="bg-white" data-target="next" data-counter="1" id="nextbtn" onClick="getNext($(this).attr('data-counter'))">
										<i class="fa fa-angle-right" aria-hidden="true"></i>
									</a>
								</li>
							</ul>
						</div>
						<div class="big-tabs" data-sudo-slider='{"slideCount":5, "moveCount":1, "customLink":"#big-tabs-nav a, .big-tabs li", "continuous":true}'>
							<ul class="clean-list">
							<?php 
							   $static_first_display = (count($city_print_data)-1);
							   $static_secound_display = count($city_print_data);
							?>		
							<li data-target="<?php echo $static_first_display; ?>" onclick="getSelectedIndex($(this).attr('data-target'))">
									<div class="tab-item uppercase text-center">
										<div class="shape-square bg-white">
											<?php echo $city_print_data[$static_first_display]['faiocn'];?>
										</div>
										<h6 class="font-alpha"><small><?php echo $city_print_data[$static_first_display]['name'] ?></small></h6>
									</div>
							</li>	
							<li data-target="<?php echo $static_secound_display; ?>" onclick="getSelectedIndex($(this).attr('data-target'))">
									<div class="tab-item uppercase text-center">
										<div class="shape-square bg-white">
											<?php echo $city_print_data[$static_secound_display]['faiocn'];?>
										</div>
										<h6 class="font-alpha"><small><?php echo $city_print_data[$static_secound_display]['name'] ?></small></h6>
									</div>
							</li>	
							<?php for($i=1;$i<=(count($city_print_data)-2);$i++){ ?>
								<li data-target="<?php echo $i; ?>" <?php if($i==0){ echo "class='active-big-tab'";} ?> onclick="getSelectedIndex($(this).attr('data-target'))">
									<div class="tab-item uppercase text-center">
										<div class="shape-square bg-white">
											<?php echo $city_print_data[$i]['faiocn'];?>
										</div>
										<h6 class="font-alpha"><small><?php echo $city_print_data[$i]['name'] ?></small></h6>
									</div>
								</li>
							<?php } ?>		
							</ul>
						</div>

						<div class="big-tabs-content" data-sudo-slider='{"customLink":"#big-tabs-nav a, .big-tabs li", "continuous":true}'>
						<ul class="inline-list">	
							<?php for($i=1;$i<=count($city_print_data);$i++){ ?>	
							<li class="row">
								<div class="col-md-5">
									<h4><?php echo $city_print_data[$i]['name'] ?></h4>
									<p><?php echo $city_print_data[$i]['description'] ?></p>
								</div>
								<div class="col-md-7">
									<figure class="text-center no-margin">
										<img src="https://placehold.it/400x400/ddd/aaa" alt="big tabs">
									</figure>
								</div>
							</li>
							<?php } ?>
						</ul>	
						</div>
					</div>
				</div> <!-- /.row -->
			</div> <!-- /.container -->

			<?php } else { ?>

			<div class="alert alert-info">
				Nothing To Show.
			</div>

			<?php } ?>

		</section> <!-- /.box -->
    
    