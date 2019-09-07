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
				<?php echo form_open('admins/city/Restaurants/edit/'.$id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Restaurant & Nightlife</label>
							<input type="hidden" name="city_id" value="<?php echo $restaurant['city_id'] ?>"/>
							<input type="hidden" name="country_id" value="<?php echo $restaurant['country_id'] ?>"/>
							<input type="hidden" name="id" value="<?php echo $id; ?>"/>
							<input type="text" id="searchTextField" name="ran_name" class="form-control required" maxlength="200" value="<?php echo $restaurant['ran_name'] ?>" autocomplete="off" placeholder=""/>
							<?php echo form_error('ran_name'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row">
				 <div class="col-md-6">
					<div class="form-group">
	                    <label>
	                     Ran Display&nbsp;
	                      <input type="radio" name="ran_display" class="minimal" value="1" <?php if($restaurant['ran_display']==1){ echo 'checked'; } ?>>&nbsp;&nbsp;1
	                    </label>&nbsp;&nbsp;&nbsp;
	                    <label>
	                      <input type="radio" name="ran_display" class="minimal" value="2" <?php if($restaurant['ran_display']==2){ echo 'checked'; } ?>>&nbsp;&nbsp;2
	                    </label>
	              </div>
	              </div>
				</div>
				 
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Image</label>
							<?php if($restaurant['image']!=''){ ?>
								<img src="<?php echo site_url('userfiles/images/'.$restaurant['image']) ?>" width="100"/>
							<?php } ?>
							<input type="file" name="image" accept=".png,.jpg,.jpeg,.gif"/>
							<p class="text-info">[Only .jpg,.jpeg,.png,.gif]</p>
						   </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->
				 
				 <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Details</label>
							<textarea name="ran_description" class="form-control" rows="8"><?php echo $restaurant['ran_description'] ?></textarea>
							<?php echo form_error('ran_description'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				   <?php
				 		$selected=array();
				 		if($restaurant['known_for']!='')
				 		{
				 			$selected=explode(',',$restaurant['known_for']);
				 		}

				  ?>
				 <div class="row">	
					<div class="col-md-12">
						  <div class="form-group">
							<label>Known For</label>
							<select name="knownfor[]" class="required form-control select2" multiple>
								<option value="">Select Known For Tags</option>
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>"

								<?php for($i=0;$i<count($selected);$i++){ 

										if($selected[$i]==$list['id'])
										{
											echo 'selected';
										}

								 } ?>

								><?php echo $list['tag_name']; ?></option>
								<?php } ?>
							</select>
							<?php echo form_error('known_for'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row">
					
					<div class="col-md-6">
						  <div class="form-group">
							<label>Address</label>
							<textarea name="ran_address" class="form-control required" rows="4" maxlength="800"><?php echo $restaurant['ran_address'] ?></textarea>
							<?php echo form_error('ran_address'); ?>
						 </div><!-- /.form-group -->
					</div>
					<div class="col-md-6">
						  <div class="form-group">
							<label>Zipcode</label>
							<input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php echo $restaurant['zipcode'] ?>" autocomplete="off" maxlength="30"/>
							<input type="hidden" id="oldzipcode" name="oldzipcode" class="form-control" value="<?php echo $restaurant['zipcode'] ?>" autocomplete="off" maxlength="30"/>
							
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 

				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label>
							<input type="text" id="ran_lat" name="ran_lat" class="form-control required number" value="<?php echo $restaurant['ran_lat'] ?>" autocomplete="off"/>
							<?php echo form_error('ran_lat'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label>
							<input type="text" id="ran_long" name="ran_long" class="form-control required number" value="<?php echo $restaurant['ran_long'] ?>" autocomplete="off"/>
							<?php echo form_error('ran_long'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Contact</label>
							<input type="text" id="ran_contact" name="ran_contact" class="form-control" maxlength="100" value="<?php echo $restaurant['ran_contact'] ?>" autocomplete="off"/>
							<?php echo form_error('ran_contact'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Website</label>
							<input type="text" id="ran_website" name="ran_website" class="form-control url" maxlength="300" value="<?php echo $restaurant['ran_website'] ?>" autocomplete="off"/>
							<?php echo form_error('ran_website'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				  
				  <div class="row">
					 <div class="col-md-4">
						  <div class="form-group">
							<label>Open/Close Timing</label>
							<textarea name="ran_timing" class="form-control" rows="6" maxlength="1000"><?php echo $restaurant['ran_timing'] ?></textarea>
							<?php echo form_error('ran_timing'); ?>
						 </div><!-- /.form-group -->
					</div>

				 </div><!-- /.row -->

				<div class="form-group">
                     <label>
                   Tag Star
                       <input type="radio" name="tag_star" class="minimal" value="1"  <?php if($restaurant['tag_star']==1){ echo 'checked'; } ?>>&nbsp;Yes&nbsp;
                     </label>
                     <label>
                       <input type="radio" name="tag_star" class="minimal" value="0"  <?php if($restaurant['tag_star']==0){ echo 'checked'; } ?>>&nbsp;No&nbsp;                     </label>
                </div>

                 <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Credit</label>
							<input type="text" name="credit" maxlength="2000" class="form-control" value="<?php echo $restaurant['credit'] ?>"/>
							<?php echo form_error('credit'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div>

				
              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/Restaurants/index').'/'.$restaurant['city_id']  ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->
<script>
function checkreadonly(id)
	{
		if($("#chk"+id).is(':checked'))
		{
			$("#"+id).removeAttr('readonly');
			if($(".temp"+id).val()!='')
			{
				$("#"+id).val($(".temp"+id).val());
			}
			else
			{	
				$("#"+id).val('');
			}
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
   		$("#ran_lat").val(place.geometry.location.lat());
    	$("#ran_long").val(place.geometry.location.lng()); 
    });
}
             
google.maps.event.addDomListener(window, 'load', initialize);


</script>
</section><!-- /.content -->
