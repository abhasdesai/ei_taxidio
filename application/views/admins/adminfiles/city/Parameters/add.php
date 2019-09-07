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
				<?php echo form_open('admins/city/Parameters/add/'.$city_id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Budget</label>
							<input type="hidden" name="city_id" value="<?php echo $city_id ?>"/>
							<select name="budget_id" class="required select2 form-control">
								<option value="">Select Budget</option>
								<?php foreach ($budget as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('budget_id', $list['id']); ?> ><?php echo $list['budget_hotel_per_night'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('budget_id'); ?>
						 </div><!-- /.form-group -->
					</div>

			    	 <div class="col-md-6">
						  <div class="form-group">
							<label>Country</label>
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
					 <div class="col-md-6">
						  <div class="form-group">
							<label>DOI</label>
							<select name="doi_id" class="required select2 form-control">
								<option value="">Select DOI</option>
								<?php foreach ($doi as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('doi_id', $list['id']); ?> ><?php echo $list['doi_type'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('doi_id'); ?>
						 </div><!-- /.form-group -->
					</div>

			    	 <div class="col-md-6">
						  <div class="form-group">
							<label>Days</label>
							<select name="days_id" class="required select2 form-control">
								<option value="">Select Days</option>
								<?php foreach ($days as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('days_id', $list['id']); ?> ><?php echo $list['days_range'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('days_id'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->


			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Weather</label>
							<select name="weather_id" class="required select2 form-control">
								<option value="">Select Weather</option>
								<?php foreach ($weather as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('weather_id', $list['id']); ?> ><?php echo $list['weather_temperature'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('weather_id'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Traveler Age</label>
							<select name="traveler_id" class="required select2 form-control">
								<option value="">Select Traveler Age</option>
								<?php foreach ($age as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('traveler_id', $list['id']); ?> ><?php echo $list['traveler_age'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('traveler_id'); ?>
						 </div>				
					</div>
			   </div><!-- /.row -->




			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Traveler Time</label>
							<select name="travel_time_id" class="required select2 form-control">
								<option value="">Select Traveler Time</option>
								<?php foreach ($time as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('traveler_id', $list['id']); ?> ><?php echo $list['travel_time_slot'] ?></option>
								<?php }?>
								</select>
							<?php echo form_error('travel_time_id'); ?>
						 </div><!-- /.form-group -->
					</div>

			   </div><!-- /.row -->

			 <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/Parameters/index').'/'.$city_id ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->