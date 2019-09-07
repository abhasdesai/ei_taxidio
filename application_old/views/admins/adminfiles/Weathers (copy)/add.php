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
				<?php echo form_open('admins/Weathers/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-3">
						  <div class="form-group">
							<label>From</label>
							<input type="text" id="weather_temperature_from" name="weather_temperature_from" class="form-control required" maxlength="11" value="<?php echo set_value('weather_temperature_from') ?>" autocomplete="off"/>
							<?php echo form_error('weather_temperature_from'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-3">
						  <div class="form-group">
							<label>To</label>
							<input type="text" id="weather_temperature_to" name="weather_temperature_to" class="form-control" maxlength="11" value="<?php echo set_value('weather_temperature_to') ?>" autocomplete="off"/>
							<?php echo form_error('weather_temperature_to'); ?>
							<p class="text-red">[keep this field "blank" for more (ie 45+).]</p>
						 </div><!-- /.form-group -->
					</div>

				</div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Weathers') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
