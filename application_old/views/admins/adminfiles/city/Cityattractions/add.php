<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>
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
		   <a href="<?php echo site_url('admins/city/Cities'); ?>" class="btn bg-navy margin">Back to Cities</a>

            <div class="box-body">
				<?php echo form_open('admins/city/Cityattractions/add/'.$city_id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				 <div class="form-group">
                     <label>
                   Is Paid ?
                       <input type="radio" name="ispaid" class="minimal paid" value="1" checked>&nbsp;Yes&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="ispaid" class="minimal paid" value="0" >&nbsp;No&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="ispaid" class="minimal paid" value="2" >&nbsp;Other&nbsp;
                     </label>
                </div>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Attraction</label>
							<input type="hidden" name="city_id" value="<?php echo $city_id ?>"/>
							<input type="text" id="searchTextField" name="attraction_name" class="form-control required" maxlength="300" value="<?php echo set_value('attraction_name') ?>" autocomplete="off" placeholder=""/>
							<?php echo form_error('attraction_name'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Image</label>
							<input type="file" name="image" accept=".png,.jpg,.jpeg,.gif"/>
							<p class="text-info">[Only .jpg,.jpeg,.png,.gif]</p>
						   </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->
				 <div class="row option3">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Details</label>
							<textarea name="attraction_details" class="form-control" rows="8"><?php echo set_value('attraction_details') ?></textarea>
							<?php echo form_error('attraction_details'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				  <?php
				 	$def=array();
				 	if($default['default_tags']!='' || $default['default_tags']!=0)
				 	{	
				 		$def=explode(',',$default['default_tags']);
				 	}	
				 	//print_r($def);die;
				  ?>


				<div class="row">	
					<div class="col-md-12">
						  <div class="form-group">
							<label>Known For</label>
							<select name="attraction_knownfor[]" class="required form-control select2" multiple>
								<option value="">Select Known For Tags</option>
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'], $def)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>
								<?php } ?>
							</select>
							<?php echo form_error('attraction_knownfor'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				<div class="row option3">	
					<div class="col-md-6">
						  <div class="form-group">
							<label>Address</label>
							<textarea name="attraction_address" class="form-control required" rows="4" maxlength="800"><?php echo set_value('attraction_address') ?></textarea>
							<?php echo form_error('attraction_address'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Zipcode</label>
							<input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php echo set_value('zipcode') ?>" autocomplete="off" maxlength="30"/>
							
						 </div><!-- /.form-group -->
					</div>
					
				 </div><!-- /.row -->

				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label>
							<input type="text" id="attraction_lat" name="attraction_lat" class="form-control required number" value="<?php echo set_value('attraction_lat') ?>" autocomplete="off"/>
							<?php echo form_error('attraction_lat'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label>
							<input type="text" id="attraction_long" name="attraction_long" class="form-control required number" value="<?php echo set_value('attraction_long') ?>" autocomplete="off"/>
							<?php echo form_error('attraction_long'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row option3">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Contact</label>
							<input type="text" id="attraction_contact" name="attraction_contact" class="form-control" maxlength="100" value="<?php echo set_value('attraction_contact') ?>" autocomplete="off"/>
							<?php echo form_error('attraction_contact'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Website</label>
							<input type="text" id="attraction_website" name="attraction_website" class="form-control url" maxlength="300" value="<?php echo set_value('attraction_website') ?>" autocomplete="off"/>
							<?php echo form_error('attraction_website'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				  <div class="row option3">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Nearest Public Station</label>
							<textarea name="attraction_public_transport" class="form-control" rows="4" maxlength="500"><?php echo set_value('attraction_public_transport') ?></textarea>
							<?php echo form_error('attraction_public_transport'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6 ispaidflag">
						  <div class="form-group">
							<label>Admission Fee</label>
							<textarea name="attraction_admissionfee" class="form-control" rows="4" maxlength="1000"><?php echo set_value('attraction_admissionfee') ?></textarea>
							<?php echo form_error('attraction_admissionfee'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				  <div class="row option3">
					 <div class="col-md-4">
						  <div class="form-group">
							<label>Open/Close Timing</label>
							<textarea name="attraction_timing" class="form-control" rows="6" maxlength="1000"><?php echo set_value('attraction_timing') ?></textarea>
							<?php echo form_error('attraction_timing'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-4">
						  <div class="form-group">
							<label>Time Required (In Hours)</label>
							<input type="text" name="attraction_time_required" class="form-control required number" value="<?php echo set_value('attraction_time_required') ?>" maxlength="4"/>
							<?php echo form_error('attraction_time_required'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-4">
						  <div class="form-group">
							<label>Wait Time</label>
							<textarea name="attraction_wait_time" class="form-control" rows="6" maxlength="1000"><?php echo set_value('attraction_wait_time') ?></textarea>
							<?php echo form_error('attraction_wait_time'); ?>
						 </div><!-- /.form-group -->
					</div>
					
				 </div><!-- /.row -->

				  <div class="form-group ispaidflag option3">
                     <label>
                     Buy Ticket ?
                       <input type="radio" name="attraction_buy_ticket" class="minimal" value="1" checked>&nbsp;Yes&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="attraction_buy_ticket" class="minimal" value="0">&nbsp;No&nbsp;
                     </label>
                </div>

                  <div class="form-group ispaidflag option3">
                     <label>
                   Get Your Guide ?
                       <input type="radio" name="attraction_getyourguid" class="minimal" value="1" checked>&nbsp;Yes&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="attraction_getyourguid" class="minimal" value="0">&nbsp;No&nbsp;
                     </label>
                </div>

                 <div class="form-group">
                     <label>
                      Tag Star
                       <input type="radio" name="tag_star" class="minimal" value="0" checked>&nbsp;0&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="tag_star" class="minimal" value="1">&nbsp;1&nbsp;
                     </label>

                     <label>
                       <input type="radio" name="tag_star" class="minimal" value="2"  <?php if($check>0){ ?>disabled="disabled" <?php } ?>>&nbsp;2&nbsp;
                     </label>
                     <?php echo form_error('tag_star'); ?>
                </div>

                <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Credit</label>
							<input type="text" name="credit" maxlength="2000" class="form-control" value="<?php echo set_value('credit'); ?>"/>
							<?php echo form_error('credit'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div>

      			 <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/Cityattractions/index').'/'.$city_id  ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

<script>
		
	$(".paid").on('ifChecked',function(){
		if($(this).val()==1)
		{
			$(".option3").show();
			$(".ispaidflag").show();
		}
		else if($(this).val()==0)
		{

			$(".option3").show();
			$(".ispaidflag").hide();
		}
		else
		{
			$(".ispaidflag").show();
			$(".option3").hide();
		}

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


<script>
  
function initialize() {
   
    var input = document.getElementById('searchTextField');
    var options = {componentRestrictions: {}};
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
   		var place = autocomplete.getPlace();
   		$("#attraction_lat").val(place.geometry.location.lat());
    	$("#attraction_long").val(place.geometry.location.lng()); 
    });
}
             
google.maps.event.addDomListener(window, 'load', initialize);


</script>

</section><!-- /.content -->
