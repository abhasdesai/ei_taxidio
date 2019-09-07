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
				<?php echo form_open('admins/city/DestinationOptionTags/add/'.$city_id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<div class="row">
				 	
					<div class="col-md-6">
					  <div class="form-group">
						<label>Tag Type</label>
						<input type="hidden" name="city_id" value="<?php echo $city_id ?>"/>
						<select name="option_id" class="required select2 form-control">
							<option value="">Select Type</option>
							<?php foreach ($options as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('option_id', $list['id']); ?> ><?php echo $list['tag_type'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('option_id'); ?>
					 </div><!-- /.form-group -->
					 </div>


				 	<div class="col-md-6">
					  <div class="form-group">
						<label>Tag</label>
						<select name="tag_id" class="required select2 form-control">
							<option value="">Select Tag</option>
							<?php foreach ($tags as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('tag_id', $list['id']); ?> ><?php echo $list['tag_name'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('tag_id'); ?>
					 </div><!-- /.form-group -->
					 </div>

				</div>


				
				 <div class="row">

				 	 <div class="col-md-6">
						  <div class="form-group">
							<label>Dest</label>
							<input type="text" id="tag_dest" name="tag_dest" class="form-control required" maxlength="200" value="<?php echo set_value('tag_dest') ?>" autocomplete="off"/>
							<?php echo form_error('tag_dest'); ?>
						 </div><!-- /.form-group -->
					</div>

					
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Time</label>
							<input type="text" id="tag_time" name="tag_time" class="form-control" maxlength="20" value="<?php echo set_value('tag_time') ?>" autocomplete="off"/>
							<?php echo form_error('tag_time'); ?>
						 </div><!-- /.form-group -->
					</div>

				 </div><!-- /.row -->

				
				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Latitude</label>
							<input type="text" id="tag_lat" name="tag_lat" class="form-control required number" maxlength="11" value="<?php echo set_value('tag_lat') ?>" autocomplete="off"/>
							<?php echo form_error('tag_lat'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-6">
						  <div class="form-group">
							<label>Longitude</label>
							<input type="text" id="tag_long" name="tag_long" class="form-control required number" maxlength="11" value="<?php echo set_value('tag_long') ?>" autocomplete="off"/>
							<?php echo form_error('tag_long'); ?>
						 </div><!-- /.form-group -->
					</div>
				 </div><!-- /.row -->

				 <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Dest Id</label>
							<input type="text" id="dest_id" name="dest_id" class="form-control" maxlength="30" value="<?php echo set_value('dest_id') ?>" autocomplete="off"/>
							<?php echo form_error('dest_id'); ?>
						 </div><!-- /.form-group -->
					</div>

				</div><!-- /.row -->

			   <div class="form-group">
			    <label>Tag Star</label>
                 <label>
                   <input type="radio" name="tag_star" class="minimal" value="0" checked>
                    &nbsp;0&nbsp;&nbsp;
                 </label>
                 <label>
                   <input type="radio" name="tag_star" class="minimal" value="1">
                   &nbsp;1&nbsp;&nbsp;
                 </label>
               </div>

				  
				
              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/DestinationOptionTags/index').'/'.$city_id  ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
