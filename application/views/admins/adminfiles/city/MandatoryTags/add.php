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
				<?php echo form_open('admins/city/MandatoryTags/add/'.$city_id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<div class="row">
				 	
					<div class="col-md-6">
					  <div class="form-group">
						<label>Mandatory Tag</label>
						<input type="hidden" name="city_id" value="<?php echo $city_id ?>"/>
						<select name="mandatory_tag_id" class="required select2 form-control">
							<option value="">Select Tag</option>
							<?php foreach ($tags as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('mandatory_tag_id', $list['id']); ?> ><?php echo $list['mandatory_tag'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('mandatory_tag_id'); ?>
					 </div><!-- /.form-group -->
				</div>
				</div>
				<div class="row">
				
				 	<div class="col-md-6">
					  <div class="form-group">
						<label>Dest</label>
							<input type="text" id="searchTextField" name="mandatory_dest" class="form-control required" maxlength="200" value="<?php echo set_value('mandatory_dest') ?>" autocomplete="off" placeholder=""/>
							<?php echo form_error('mandatory_dest'); ?>
					 </div><!-- /.form-group -->
					 </div>

				</div>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label>
							<input type="text" id="mandatory_lat" name="mandatory_lat" class="form-control required number" value="<?php echo set_value('mandatory_lat') ?>" autocomplete="off"/>
							<?php echo form_error('mandatory_lat'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label>
							<input type="text" id="mandatory_long" name="mandatory_long" class="form-control required number" value="<?php echo set_value('mandatory_long') ?>" autocomplete="off"/>
							<?php echo form_error('mandatory_long'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->
				

			<table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/MandatoryTags/index').'/'.$city_id  ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->
<script>
  
function initialize() {
   
    var input = document.getElementById('searchTextField');
    var options = {componentRestrictions: {}};
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
   		var place = autocomplete.getPlace();
   		$("#mandatory_lat").val(place.geometry.location.lat());
    	$("#mandatory_long").val(place.geometry.location.lng()); 
    });
}
             
google.maps.event.addDomListener(window, 'load', initialize);


</script>
</section><!-- /.content -->
