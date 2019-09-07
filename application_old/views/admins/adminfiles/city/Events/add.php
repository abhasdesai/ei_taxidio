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
				<?php echo form_open('admins/city/Events/add/'.$city_id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<div class="row">
				   <div class="col-md-6">
					  <div class="form-group">
						<label>Month</label>
						<select name="month_id" class="required select2 form-control">
							<option value="">Select Month</option>
							<?php foreach ($months as $list) {?>
								<option value="<?php echo $list['id'] ?>" <?php echo set_select('month_id', $list['id']); ?> ><?php echo $list['month_name'] ?></option>
							<?php }?>
							</select>
						<?php echo form_error('month_id'); ?>
					 </div><!-- /.form-group -->
				</div>
				</div>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Event</label>
							<input type="hidden" name="city_id" value="<?php echo $city_id ?>"/>
							<input type="text" id="event_name" name="event_name" class="form-control required" maxlength="300" value="<?php echo set_value('event_name'); ?>" autocomplete="off"/>
							<?php echo form_error('event_name'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->


				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Event Description</label>
							<textarea name="event_description" class="form-control required" rows="8"><?php echo set_value('event_description'); ?></textarea>
							<?php echo form_error('event_description'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

				
              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/city/Events/index').'/'.$city_id ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
