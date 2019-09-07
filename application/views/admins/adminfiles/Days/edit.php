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
				<?php echo form_open('admins/Days/edit/' . $id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Days</label>
							<input type="text" id="days_range" name="days_range" class="form-control required" maxlength="11" value="<?php if($range['flag_plus']==1){ echo $range['days_range'].'+'; }else{ echo $range['days_range']; } ?>" autocomplete="off"/>
							<?php echo form_error('days_range'); ?>
						 </div><!-- /.form-group -->
					</div>
			  </div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Days') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
