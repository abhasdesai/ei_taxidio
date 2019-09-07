<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/jquery-ui.css'); ?>">
<section class="content">
		<?php if ($this->session->flashdata('success')) {?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php }?>
			<?php if ($this->session->flashdata('error')) {?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php }?>

				<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $section; ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->

            <div class="box-body">
				<?php echo form_open('admins/city/Cities/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
			 
			

			<div class="row">
			  <div class="col-md-6">
				  <div class="form-group">
					<label>Country</label>
					<select name="country_id" class="required select2 form-control" id="country_id">
						<option value="">Select Country</option>
						<?php foreach ($countries as $list) {?>
							<option value="<?php echo $list['id'] ?>"><?php echo $list['country_name'] ?></option>
						<?php }?>
					</select>
					<?php echo form_error('country_id'); ?>
				 </div><!-- /.form-group -->
			 </div>	
			</div>

			

			 <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>City</label>
							<input type="text" id="typeaheadkeywords" name="city_name" class="form-control required" maxlength="100" value="<?php echo set_value('city_name') ?>" autocomplete="off"/>
							<p class="text-light-blue">[Do not change autocomplete name]</p>
							<?php echo form_error('city_name'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>City Code</label>
							<input type="text" name="code" class="form-control required" maxlength="4" value="<?php echo set_value('code') ?>" autocomplete="off"/>
							<?php echo form_error('code'); ?>
						 </div><!-- /.form-group -->
					</div>
					
			  </div><!-- /.row -->

			   <div class="row">
				 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label>
							<input type="hidden" name="romecountryname" id="romecountryname">
							<input type="hidden" name="romecountrycode" id="romecountrycode">
							
							<input type="text" id="latitude" name="latitude" class="form-control required" maxlength="50" value="<?php echo set_value('latitude') ?>" autocomplete="off" readonly/>
							<?php echo form_error('latitude'); ?>
						 </div><!-- /.form-group -->
					</div>

			    <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label>
							<input type="text" id="longitude" name="longitude" class="form-control required" maxlength="50" value="<?php echo set_value('longitude') ?>" autocomplete="off" readonly/>
							<?php echo form_error('longitude'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
				    
				    <div class="col-md-4">
					    <div class="form-group">
						    <label>Background Image</label>
							   <input type="file" name="cityimage" accept=".jpg,.jpeg,.png.gif"/>
							   <p class="text-light-blue">[size must be 1920px X 1080px]</p>
						</div><!-- /.form-group -->
					</div>

				    <div class="col-md-4">
					    <div class="form-group">
						    <label>Banner</label>
							   <input type="file" name="citybanner" accept=".jpg,.jpeg,.png.gif"/>
							   <p class="text-light-blue">[size must be 1920px X 350px]</p>
						</div><!-- /.form-group -->
					</div>

					<div class="col-md-4">
						  <div class="form-group">
							<label>Travel Guide</label>
							<input type="file" name="travelguide" class="" accept=".pdf">
							<p class="text-info">[Only .PDF]</p>
						 </div><!-- /.form-group -->
					</div>
				</div>

				
			  <div class="row">
			  	<div class="col-md-12">
						  <div class="form-group">
							<label>Geographical Location</label>
							<textarea  id="city_geographical_location" name="city_geographical_location" class="form-control required" rows="6"><?php echo set_value('city_geographical_location'); ?></textarea>
							<?php echo form_error('city_geographical_location'); ?>
						 </div><!-- /.form-group -->
					</div>
			  </div>

			  <div class="row">
			  	<div class="col-md-12">
						  <div class="form-group">
						  <label>Neighbours</label>
						   <textarea  id="city_neighbours" name="city_neighbours" class="form-control required" rows="4" maxlength="1500"><?php echo set_value('city_neighbours'); ?></textarea>
						   <?php echo form_error('city_neighbours'); ?>
						</div><!-- /.form-group -->
					</div>
			  </div>

			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Significance</label>
							<textarea name="city_significance" class="form-control" rows="6" ><?php echo set_value('city_significance'); ?></textarea>
							<?php echo form_error('city_significance'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Weather Seasonality</label>
							<textarea name="city_weather_seasonality" class="form-control" rows="6" ><?php echo set_value('city_weather_seasonality'); ?></textarea>
							<?php echo form_error('city_weather_seasonality'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->



			   <div class="row">
			   		<div class="col-md-12">
			   			<div class="form-group">
							<label>History</label>
							<textarea name="city_history" class="form-control" rows="8"><?php echo set_value('city_history'); ?></textarea>
						</div>
			   		</div>
			   </div> 

				 <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Cultural Identity</label>
							<textarea name="city_cultural_identity" class="form-control" rows="6"><?php echo set_value('city_cultural_identity'); ?></textarea>
							<?php echo form_error('city_cultural_identity'); ?>
						 </div><!-- /.form-group -->
					</div>
			    
			    	<div class="col-md-6">
						  <div class="form-group">
							<label>Natural Resources</label>
							<textarea name="city_natural_resources" class="form-control" rows="6"><?php echo set_value('city_natural_resources'); ?></textarea>
							<?php echo form_error('city_natural_resources'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->



				 <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Adventure Sports</label>
							<textarea name="city_adventure_sports" class="form-control" rows="6" ><?php echo set_value('city_adventure_sports'); ?></textarea>
							<?php echo form_error('city_adventure_sports'); ?>
						 </div><!-- /.form-group -->
					</div>
			    
			    	<div class="col-md-6">
						  <div class="form-group">
							<label>Local Sports Stadiums</label>
							<textarea name="city_local_sports_stadium" class="form-control" rows="6"><?php echo set_value('city_local_sports_stadium'); ?></textarea>
							<?php echo form_error('city_local_sports_stadium'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>City Transportation Hubs</label>
							<textarea name="city_transportation_hubs" class="form-control" rows="6"><?php echo set_value('city_transportation_hubs'); ?></textarea>
							<?php echo form_error('city_transportation_hubs'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Transportation Costs</label>
							<textarea name="city_transportation_costs" class="form-control" rows="6" ><?php echo set_value('city_transportation_costs'); ?></textarea>
							<?php echo form_error('city_transportation_costs'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>	
					
				  <div class="row">	
					<div class="col-md-6">
						  <div class="form-group">
							<label>Baggage Allowance</label>
							<textarea name="city_baggage_allowance" class="form-control" rows="6"><?php echo set_value('city_baggage_allowance'); ?></textarea>
							<?php echo form_error('city_baggage_allowance'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Food</label>
							<textarea name="city_food" class="form-control" rows="6" ><?php echo set_value('city_food'); ?></textarea>
							<?php echo form_error('city_food'); ?>
						 </div><!-- /.form-group -->
					</div>

			   </div><!-- /.row -->


			   <div class="row">
					<div class="col-md-6">
						  <div class="form-group">
							<label>Average Cost Meal Drink</label>
							<textarea name="city_avg_cost_meal_drink" class="form-control" rows="6" ><?php echo set_value('city_avg_cost_meal_drink'); ?></textarea>
							<?php echo form_error('city_avg_cost_meal_drink'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Shopping</label>
							<textarea name="city_shopping" class="form-control" rows="6" ><?php echo set_value('city_shopping'); ?></textarea>
							<?php echo form_error('city_shopping'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->




			   <div class="row">

			   		 <div class="col-md-6">
						  <div class="form-group">
							<label>City Guides Tours</label>
							<textarea name="city_guides_tours" class="form-control" rows="6" ><?php echo set_value('city_guides_tours'); ?></textarea>
							<?php echo form_error('city_guides_tours'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Toursit Benefits</label>
							<textarea name="city_toursit_benefits" class="form-control" rows="6" ><?php echo set_value('city_toursit_benefits'); ?></textarea>
							<?php echo form_error('city_toursit_benefits'); ?>
						 </div><!-- /.form-group -->
					</div>					
				
			   </div><!-- /.row -->

			     <div class="row">

			   		 <div class="col-md-6">
						  <div class="form-group">
							<label>Relaxation & Spa</label>
							<textarea  id="relaxation_spa" name="relaxation_spa" class="form-control" rows="6"><?php echo set_value('relaxation_spa') ?></textarea>
							<?php echo form_error('relaxation_spa'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Restaurant & Nightlife</label>
							<textarea  id="restaurant_nightlife" name="restaurant_nightlife" class="form-control" rows="6"><?php echo set_value('restaurant_nightlife') ?></textarea>
							<?php echo form_error('restaurant_nightlife'); ?>
						 </div><!-- /.form-group -->
					</div>
			      </div><!-- /.row -->


			  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Essential Local Apps</label>
							<textarea name="city_essential_local_apps" class="form-control"><?php echo set_value('city_essential_local_apps'); ?></textarea>
							<?php echo form_error('city_essential_local_apps'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Driving Requirement</label>
							<textarea name="city_driving" class="form-control" rows="6" ><?php echo set_value('city_driving'); ?></textarea>
								<?php echo form_error('city_driving'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
			   		
			   		 <div class="col-md-12">
						  <div class="form-group">
							<label>Travel Essentials</label>
							<textarea name="city_travel_essentials" class="form-control" rows="6" ><?php echo set_value('city_travel_essentials'); ?></textarea>
							<?php echo form_error('city_travel_essentials'); ?>
						 </div><!-- /.form-group -->
					</div>
			 </div>

			   <div class="row">

			   		 <div class="col-md-6">
						  <div class="form-group">
							<label>Local Currency</label>
							<textarea  id="city_local_currency" name="city_local_currency" class="form-control" rows="6" maxlength="1000"><?php echo set_value('city_local_currency') ?></textarea>
							<?php echo form_error('city_local_currency'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Vendor ATM Commission</label>
							<textarea  id="city_vendor_atm_commission" name="city_vendor_atm_commission" class="form-control" rows="6"><?php echo set_value('city_vendor_atm_commission') ?></textarea>
							<?php echo form_error('city_vendor_atm_commission'); ?>
						 </div><!-- /.form-group -->
					</div>
			      </div><!-- /.row -->

			  
			   <div class="row">
					  <div class="col-md-6">
						  <div class="form-group">
							<label>Staying Connected</label>
							<textarea name="city_staying_connected" class="form-control" rows="6" ><?php echo set_value('city_staying_connected'); ?></textarea>
							<?php echo form_error('city_staying_connected'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Time Zone</label>
							<textarea name="city_time_zone" class="form-control required" rows="6" maxlength="1000"><?php echo set_value('city_time_zone') ?></textarea>
							<?php echo form_error('city_time_zone'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			    <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Political Scenario</label>
							<textarea name="city_political_scenario" class="form-control" maxlength="2500" row="6"><?php echo set_value('city_political_scenario'); ?></textarea>
							<?php echo form_error('city_political_scenario'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Economic Scenario</label>
							<textarea name="city_economic_scenario" class="form-control" rows="6" ><?php echo set_value('city_economic_scenario'); ?></textarea>
							<?php echo form_error('city_economic_scenario'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->


			   <div class="row">
					<div class="col-md-12">
						  <div class="form-group">
							<label>Language Spoken</label>
							<textarea  id="city_language_spoken" name="city_language_spoken" class="form-control" rows="6" maxlength="1500"><?php echo set_value('city_language_spoken') ?></textarea>
							<?php echo form_error('city_language_spoken'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			     <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Religion Belief</label>
							<textarea name="city_religion_belief" class="form-control" rows="6" ><?php echo set_value('city_religion_belief') ?></textarea>
							<?php echo form_error('city_religion_belief'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Visa Requirements</label>
							<textarea name="city_visa_requirements" class="form-control" rows="6" ><?php echo set_value('city_visa_requirements'); ?></textarea>
							<?php echo form_error('city_visa_requirements'); ?>
						 </div><!-- /.form-group -->
					</div>
					
			   </div><!-- /.row -->

			    <div class="row">
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Essential Vaccination</label>
							<textarea name="city_essential_vaccination" class="form-control" rows="6" ><?php echo set_value('city_essential_vaccination'); ?></textarea>
							<?php echo form_error('city_essential_vaccination'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Safety</label>
							<textarea name="city_safety" class="form-control" rows="6" ><?php echo set_value('city_safety'); ?></textarea>
							<?php echo form_error('city_safety'); ?>
						 </div><!-- /.form-group -->
					</div>

			   </div><!-- /.row -->


			    <div class="row">
					<div class="col-md-6">
						  <div class="form-group">
							<label>Embassy Consulates</label>
							<textarea name="city_embassies_consulates" class="form-control" rows="6"><?php echo set_value('city_embassies_consulates'); ?></textarea>
							<?php echo form_error('city_embassies_consulates'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Restricted Accessibility</label>
							<textarea name="city_restricted_accessibility" class="form-control" maxlength="3000" rows="6"><?php echo set_value('city_restricted_accessibility'); ?></textarea>
							<?php echo form_error('city_restricted_accessibility'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->

			    <div class="row">
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Emergencies</label>
							<textarea name="city_emergencies" class="form-control" rows="6" ><?php echo set_value('city_emergencies'); ?></textarea>
							<?php echo form_error('city_emergencies'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Conclusion</label>
							<textarea name="city_conclusion" class="form-control" rows="6" ><?php echo set_value('city_conclusion'); ?></textarea>
							<?php echo form_error('city_conclusion'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			    <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Do and Donts</label>
							<textarea name="city_dos_donts" id="editor1" class="form-control" rows="6"><?php echo set_value('city_dos_donts'); ?></textarea>
							<?php echo form_error('city_dos_donts'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>	

			    <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Tipping</label>
							<textarea name="city_tipping" class="form-control" rows="6" ><?php echo set_value('city_tipping'); ?></textarea>
							<?php echo form_error('city_tipping'); ?>
						 </div><!-- /.form-group -->
					</div>
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Pet Important Policies</label>
							<textarea name="city_pet_imp_policies" class="form-control" rows="6" ><?php echo set_value('city_pet_imp_policies'); ?></textarea>
							<?php echo form_error('city_pet_imp_policies'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->


			 <div class="row">
				 <div class="col-md-12">
					<div class="form-group">
					<label>Tags</label>
	                   <select name="tag_id[]" class="form-control required select2" multiple>
	                   		<option value="">Select Tags</option>
	                   		<?php foreach($tags as $tlist){ ?>
	                   		<option value="<?php echo $tlist['id'] ?>"><?php echo $tlist['tag_name'] ?></option>
	                   		<?php } ?>

	                   </select>
	              </div>
	              </div>
				</div>	
		
				<div class="row">
				 <div class="col-md-6">
					<div class="form-group">
	                    <label>
	                     Hoho&nbsp;
	                      <input type="radio" name="city_hoho" class="minimal" checked value="1">&nbsp;&nbsp;Yes
	                    </label>&nbsp;&nbsp;&nbsp;
	                    <label>
	                      <input type="radio" name="city_hoho" class="minimal" value="0">&nbsp;&nbsp;NO
	                    </label>
	              </div>
	              </div>
				</div>

		 <h3 class="box-title">Ratings</h3>
         <hr class="hrstyle">	

         <?php if(count($ratings)){ 
         	$flag=0;
         		$total=count($ratings);
         		if($total%3!=0)
         		{
         			$flag=1;
         			$total=$total-1;
         			if($total%3!=0)
         			{
         				$flag=2;
         				$total=$total-1;
         			}	
         		}
         		
         		$mod=$total%3;
         		$first_ex=0;
         		$second_ex=0;
         		if($flag==1)
         		{
         			$first_ex=1;
         		}
         		else if($flag==2)
         		{
         			$first_ex=1;
         			$second_ex=1;
         		}
         		$third=$total/3;
         		$second=$total/3+$second_ex;
         		$first=$total/3+$first_ex;
         		
         ?>

        <div class="row">

          <div class="col-md-4">
        		  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px"></th>
                      <th>Type</th>
                      <th style="width: 100px">Rating</th>
                    </tr>
                     <?php for($i=0;$i<$first;$i++){ ?>
                     	<tr>
	                      <td><input type="checkbox" id="chk<?php echo $ratings[$i]['id']; ?>" name="rating_id[]" value="<?php echo $ratings[$i]['id']; ?>" onclick="checkreadonly(this.value)"/></td>
	                      <td><?php echo $ratings[$i]['rating_type']; ?></td>
	                      <td><input type="text" id="<?php echo $ratings[$i]['id']; ?>" name="rating[<?php echo $ratings[$i]['id']; ?>]" class="form-control number" readonly/></td>
                    	</tr>
       				 <?php }?>
                  </table>
            </div>

               <div class="col-md-4">
        		  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px"></th>
                      <th>Type</th>
                      <th style="width: 100px">Rating</th>
                    </tr>
                     <?php for($i=$first;$i<($first+$second);$i++){ ?>
                     	<tr>
	                      <td><input type="checkbox" id="chk<?php echo $ratings[$i]['id']; ?>" name="rating_id[]" value="<?php echo $ratings[$i]['id']; ?>" onclick="checkreadonly(this.value)"/></td>
	                      <td><?php echo $ratings[$i]['rating_type']; ?></td>
	                      <td><input type="text" id="<?php echo $ratings[$i]['id']; ?>" name="rating[<?php echo $ratings[$i]['id']; ?>]" class="form-control number" readonly/></td>
                    	</tr>
       				 <?php } ?>
                  </table>
            </div>

             <div class="col-md-4">
        		  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px"></th>
                      <th>Type</th>
                      <th style="width: 100px">Rating</th>
                    </tr>
                     <?php for($i=($first+$second);$i<($first+$second+$third);$i++){ ?>
                     	<tr>
	                      <td><input type="checkbox" id="chk<?php echo $ratings[$i]['id']; ?>" name="rating_id[]" value="<?php echo $ratings[$i]['id']; ?>" onclick="checkreadonly(this.value)"/></td>
	                      <td><?php echo $ratings[$i]['rating_type']; ?></td>
	                      <td><input type="text" id="<?php echo $ratings[$i]['id']; ?>" name="rating[<?php echo $ratings[$i]['id']; ?>]" class="form-control number" readonly/></td>
                    	</tr>
       				 <?php } ?>
                  </table>
            </div>

        </div>

        <?php } ?>

		   <h3 class="box-title">Primary Parameters</h3>
             <hr class="hrstyle">

			<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Budget</label>
							<select name="budget_id" class="required select2 form-control" style="width: 100%;">
								<option value="">Select Budget</option>
								<?php foreach ($budget as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('budget_id', $list['id']); ?> ><?php echo $list['budget'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('budget_id'); ?>
						 </div><!-- /.form-group -->
					</div>

			    	 <div class="col-md-6">
						  <div class="form-group">
							<label>Accomodation Type</label>
							<select name="accomodation_id" class="required select2 form-control">
								<option value="">Select Accomodation</option>
								<?php foreach ($accomodation as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('accomodation_id', $list['id']); ?> ><?php echo $list['accomodation_type'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('accomodation_id'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
			  
			   <?php /* ?>
			    	 <div class="col-md-6">
						  <div class="form-group">
							<label>Days</label>
							<select name="days_id" class="required select2 form-control">
								<option value="">Select Days</option>
								<?php foreach ($days as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('days_id', $list['id']); ?> ><?php echo $list['days_range'] ?><?php if($list['flag_plus']==1){ echo '+'; } ?></option>
								<?php }?>
								</select>
							<?php echo form_error('days_id'); ?>
						 </div><!-- /.form-group -->
					</div>
			<?php */ ?>		

					<div class="col-md-6">
						<div class="form-group">
							<label>Buffer Days</label>
							<input type="text" name="buffer_days" class="form-control required number" value="<?php if(set_value('buffer_days')){ echo set_value('buffer_days'); }else{ echo '0'; } ?>"/>
							<?php echo form_error('buffer_days'); ?>
						 </div>

					</div>
					
			   </div><!-- /.row -->


			   <div class="row">
					
			   <?php /* ?>
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Weather</label>
							<select name="weather_id" class="required select2 form-control">
								<option value="">Select Weather</option>
								<?php foreach ($weather as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('weather_id', $list['id']); ?> ><?php echo $list['weather'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('weather_id'); ?>
						 </div><!-- /.form-group -->
					</div>
					<?php */ ?>

					<?php /* ?>
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Traveler Time</label>
							<select name="travel_time_id" class="required select2 form-control">
								<option value="">Select Traveler Time</option>
								<?php foreach ($time as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('traveler_id', $list['id']); ?> ><?php echo $list['traveltime'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('travel_time_id'); ?>
						 </div>				
					</div>
					<?php */ ?>

			   </div><!-- /.row -->

 <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/Cities') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->

<script>

	$("#continent_id").change(function(){
		$("#country_id").select2('val','');
		$.ajax({
				url: '<?php echo site_url("admins/city/Cities/getCountries") ?>',
				type: 'POST',
				data: 'continent_id='+$(this).val(),
				success: function (data) 
				{
					$("#country_id").html(data);
				}
			});
	});

	function checkreadonly(id)
	{
		if($("#chk"+id).is(':checked'))
		{
			$("#"+id).removeAttr('readonly');
		}
		else
		{
			$("#"+id).val('');
			$("#"+id).attr('readonly', 'readonly');
		}
	}

</script>
