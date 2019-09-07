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
				<?php echo form_open('admins/Team/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Person Name</label>
							<input type="text" id="person" name="person" class="form-control required" maxlength="100" value="<?php echo set_value('person') ?>" autocomplete="off"/>
							<?php echo form_error('person'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Person Designation</label>
							<input type="text" id="designation" name="designation" class="form-control required" maxlength="150" value="<?php echo set_value('designation') ?>" autocomplete="off"/>
							<?php echo form_error('designation'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Details</label>
							<textarea id="details" name="details" class="form-control" maxlength="2000"><?php echo set_value('details') ?></textarea>
							<?php echo form_error('details'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Image</label>
							<input type="file" id="image" name="image" class="form-control required" accept=".png,.jpg,.jpeg,.gif" />
							<p class="text-info">[Image width must be grater than 360px and height must be greater than 400px]</p>
						 </div><!-- /.form-group -->
					</div>
				</div>	

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Sortorder</label>
							<input type="text" id="sortorder" name="sortorder" class="form-control required number" maxlength="11" value="<?php echo set_value('sortorder') ?>" autocomplete="off"/>
							<?php echo form_error('sortorder'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>		

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Facebook URL</label>
							<input type="text" id="facebook" name="facebook" class="form-control url" maxlength="1000" value="<?php echo set_value('facebook') ?>" autocomplete="off"/>
							<?php echo form_error('facebook'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Linkedin URL</label>
							<input type="text" id="linkedin" name="linkedin" class="form-control url" maxlength="1000" value="<?php echo set_value('linkedin') ?>" autocomplete="off"/>
							<?php echo form_error('linkedin'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>	

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Twitter URL</label>
							<input type="text" id="twitter" name="twitter" class="form-control url" maxlength="1000" value="<?php echo set_value('twitter') ?>" autocomplete="off"/>
							<?php echo form_error('twitter'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>	

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Instagram URL</label>
							<input type="text" id="instagram" name="instagram" class="form-control url" maxlength="1000" value="<?php echo set_value('instagram') ?>" autocomplete="off"/>
							<?php echo form_error('instagram'); ?>
						 </div><!-- /.form-group -->
					</div>
				</div>		
					



              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Team') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
