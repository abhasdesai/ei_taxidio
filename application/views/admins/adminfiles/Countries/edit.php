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
              <h3 class="box-title"><?php echo $section ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->

            <div class="box-body">
				<?php echo form_open('admins/Countries/edit/' . $id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<div class="row">
					 <div class="col-md-8">
						  <div class="form-group">
							<label>Continent</label><span>*</span>
							<select name="continent_id[]" class="required select2 form-control" multiple>
								<option value="">Select Continent</option>
								<?php foreach ($continents as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php if (in_array($list['id'],$selectedcontinents)) {echo 'selected';}?>><?php echo $list['continent_name'] ?></option>
								<?php }?>
								</select>
							</select>
							<?php echo form_error('continent_id'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>

				<div class="row">	
					
			   	   <div class="col-md-6">
						  <div class="form-group">
							<label>Country</label><span>*</span>
							<input type="text" id="typeaheadkeywords" name="country_name" class="form-control required" maxlength="100" value="<?php echo $country['country_name']; ?>" autocomplete="off"/>
							<p class="text-light-blue">[Do not change autocomplete name]</p>
							<?php echo form_error('country_name'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Country Code</label><span>*</span>
							<input type="text" name="countrycode" class="form-control required" maxlength="50" value="<?php echo $country['countrycode']; ?>" autocomplete="off"/>
							<?php echo form_error('countrycode'); ?>
						 </div><!-- /.form-group -->
					</div>

				 </div><!-- /.row -->

				 <div class="row">
			    	 <div class="col-md-6">
						  <div class="form-group">
						  	<?php
						  			$imploded_name='';
						  			if(count($alternativenames))
						  			{
						  				$name_to_implode=array();
						  				foreach($alternativenames as $list)
						  				{
						  					$name_to_implod[]=$list['name'];
						  				}	
						  				$imploded_name=implode(',',$name_to_implod);
						  			}
						  	 ?>
							<label>Alternative Names</label>
							<input type="text" name="name" class="form-control singleFieldTags2" value="<?php echo $imploded_name ?>" autocomplete="off" />
							<?php echo form_error('name'); ?>
						 </div><!-- /.form-group -->
					</div>
				    <div class="col-md-6">
							  <div class="form-group">
								<label>Time Zone</label><span>*</span>
								<input type="text" id="timezone" name="timezone" class="form-control required" maxlength="50" value="<?php echo $country['timezone']; ?>" autocomplete="off"/>
								<?php echo form_error('timezone'); ?>
							 </div><!-- /.form-group -->
						</div>
			    </div>


				  <div class="row">
				 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label><span>*</span>
							<input type="text" id="latitude" name="latitude" class="form-control required" maxlength="50" value="<?php echo $country['latitude']; ?>" autocomplete="off" readonly/>
							<?php echo form_error('latitude'); ?>
						 </div><!-- /.form-group -->
					</div>

			    <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label><span>*</span>
							<input type="text" id="longitude" name="longitude" class="form-control required" maxlength="50" value="<?php echo $country['longitude']; ?>" autocomplete="off" readonly/>
							<?php echo form_error('longitude'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
				    
				    <div class="col-md-6">
					    <div class="form-group">
					        <?php if($country['countryimage']!=''){ ?>
						       	 <img src="<?php echo site_url('userfiles/countries/tiny').'/'.$country['countryimage'] ?>" width="100px"/>
						    <?php } ?>	
							<label>Image</label>
							   <input type="file" name="countryimage" accept=".jpg,.jpeg,.png.gif"/>
							   <p class="text-light-blue">[size must be 450px X 254px]</p>
						</div><!-- /.form-group -->
					</div>

				    <div class="col-md-6">
					    <div class="form-group">
					        <?php if($country['countrybanner']!=''){ ?>
						       	 <img src="<?php echo site_url('userfiles/countries/tiny').'/'.$country['countrybanner'] ?>" width="100px"/>
						    <?php } ?>	
						    <label>Banner</label>
							   <input type="file" name="countrybanner" accept=".jpg,.jpeg,.png.gif"/>
							   <p class="text-light-blue">[size must be 1920px X 350px]</p>
						</div><!-- /.form-group -->
					</div>
				</div>


				  <div class="row">
				 <div class="col-md-12">
					  <div class="form-group">
						<label>Tag</label><span>*</span>
						<select name="tag_id[]" class="required select2 form-control" multiple>
							<option value="">Select Tag</option>
							<?php foreach ($tags as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('tag_id', $list['id']); ?>

								<?php foreach($countrytags as $countrytagslist){ if($countrytagslist['tag_id']==$list['id']){ echo 'selected'; } } ?>

								  ><?php echo $list['tag_name'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('tag_id'); ?>
					 </div><!-- /.form-group -->
				</div>
			</div>	

				 <div class="row">

			  	 <div class="col-md-6">
						  <div class="form-group">
							<label>Country Capital</label><span>*</span>
							<input type="text" id="country_capital" name="country_capital" class="form-control required" maxlength="100" value="<?php echo $country['country_capital']; ?>" autocomplete="off"/>
							<?php echo form_error('country_capital'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Country Currency</label><span>*</span>
							<input type="text" id="country_currency" name="country_currency" class="form-control required" maxlength="40" value="<?php echo $country['country_currency']; ?>" autocomplete="off"/>
							<?php echo form_error('country_currency'); ?>
						 </div><!-- /.form-group -->
					</div>

					 </div><!-- /.row -->

				   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Country Conclusion</label><span>*</span>
							<textarea id="country_conclusion" name="country_conclusion" class="form-control required" rows="3"><?php echo $country['country_conclusion']; ?></textarea>
							<?php echo form_error('country_conclusion'); ?>
						 </div><!-- /.form-group -->
					</div>

			  </div><!-- /.row -->
	 


				 <div class="row">


			 		 <div class="col-md-12">
						  <div class="form-group">
							<label>Country Neighbour</label><span>*</span>
							<input type="text" id="country_neighbours" name="country_neighbours" class="form-control" maxlength="500" value="<?php echo $country['country_neighbours']; ?>" autocomplete="off"/>
							<?php echo form_error('country_neighbours'); ?>
						 </div><!-- /.form-group -->
					</div>


			   	

				 </div><!-- /.row -->
				 <div class="row">

			  	 <div class="col-md-6">
						  <div class="form-group">
							<label>Country Economic</label>
							<textarea name="country_economic" class="form-control" rows="6" ><?php echo $country['country_economic']; ?></textarea>
							<?php echo form_error('country_economic'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Country History</label>
							<textarea name="country_history" class=" form-control" rows="6"><?php echo $country['country_history']; ?></textarea>
							<?php echo form_error('country_history'); ?>
						 </div><!-- /.form-group -->
					</div>
					 </div><!-- /.row -->
				 <div class="row">

			  	 <div class="col-md-6">
						  <div class="form-group">
							<label>Country Cultural Identity</label>
							<textarea name="country_cultural_identity" maxlength="3000" class=" form-control" rows="6"><?php echo $country['country_cultural_identity']; ?></textarea>
							<?php echo form_error('country_cultural_identity'); ?>
						 </div><!-- /.form-group -->
					</div>

			   	 <div class="col-md-6">
						  <div class="form-group">
							<label>Country National Carrier</label>
							<textarea name="country_national_carrier" maxlength="3000" class=" form-control" rows="6"><?php echo $country['country_national_carrier']; ?></textarea>
							<?php echo form_error('country_national_carrier'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row">
				 	 <div class="col-md-6">
					  	<div class="form-group">
							<label>Country Tourist Rating</label><span>*</span>
							<input type="text" id="country_tourist_rating" name="country_tourist_rating" class="form-control required number" maxlength="4" value="<?php echo $country['country_tourist_rating']; ?>" autocomplete="off"/>
							<?php echo form_error('country_tourist_rating'); ?>
						 </div><!-- /.form-group -->
					</div>


			   		 <div class="col-md-6">
						  <div class="form-group">
							<label>Country No Of Annual Tourists</label><span>*</span>
							<input type="text" id="country_no_of_annual_tourists" name="country_no_of_annual_tourists" class="form-control required number" maxlength="20" value="<?php echo $country['country_no_of_annual_tourists']; ?>" autocomplete="off"/>
							<?php echo form_error('country_no_of_annual_tourists'); ?>
						 </div><!-- /.form-group -->
					</div>

				</div><!-- /.row -->

				 <div class="row">
				 	 <div class="col-md-6">
			  		  <div class="form-group">
							<label>Country Natural Cultural Resources</label>
							<textarea  id="country_natural_cultural_resources" name="country_natural_cultural_resources" class="form-control" maxlength="500"><?php echo $country['country_natural_cultural_resources']; ?></textarea>
							<?php echo form_error('country_natural_cultural_resources'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Country Best Season To Visit</label>
							<input type="text" id="country_best_season_visit" name="country_best_season_visit" class="form-control" maxlength="500" value="<?php echo $country['country_best_season_visit']; ?>" autocomplete="off"/>
							<?php echo form_error('country_best_season_visit'); ?>
							<input type="hidden" name="rome2rio_code" id="rome2rio_code" value="<?php echo $country['rome2rio_code']; ?>"/>
						 </div><!-- /.form-group -->
					</div>
					</div>

				  <div class="row">
				 <div class="col-md-12">
					  <div class="form-group">
						<label>Top Attractions</label>
						<select name="attraction_id[]" class="select2 form-control" multiple>
							<option value="">Select Tag</option>
							<?php foreach ($attractions as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('attraction_id', $list['id']); ?>

								<?php foreach($countryattractions as $countryattractionslist){ if($countryattractionslist['attraction_id']==$list['id']){ echo 'selected'; } } ?>

								  ><?php echo $list['attraction_name'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('attraction_id'); ?>
					 </div><!-- /.form-group -->
				</div>
			</div>	

		  <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Countries') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->
<script type="text/javascript">

 function initialize() {
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();

            $("#latitude").val(place.geometry.location.lat());
            $("#longitude").val(place.geometry.location.lng());
           
		  });
    }

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</section><!-- /.content -->


