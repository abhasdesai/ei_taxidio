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
				<?php echo form_open('admins/Attractions/edit/' . $id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Attraction</label>
							<input type="text" id="attraction_name" name="attraction_name" class="form-control required" maxlength="200" value="<?php echo $attraction['attraction_name'] ?>" autocomplete="off"/>
							<?php echo form_error('attraction_name'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Attration Rate</label>
							<input type="text" id="attraction_rate" name="attraction_rate" class="form-control required" maxlength="20" value="<?php echo $attraction['attraction_rate'] ?>" autocomplete="off"/>
							<?php echo form_error('attraction_rate'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Attraction Days</label>
							<input type="text" id="attraction_days" name="attraction_days" class="form-control required" maxlength="20" value="<?php echo $attraction['attraction_days'] ?>" autocomplete="off"/>
							<?php echo form_error('attraction_days'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Attration Phone</label>
							<input type="text" id="attraction_phone" name="attraction_phone" class="form-control" maxlength="30" value="<?php echo $attraction['attraction_phone'] ?>" autocomplete="off"/>
							<?php echo form_error('attraction_phone'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Attraction Address</label>
							<textarea name="attraction_address" rows="4" class="form-control required" maxlength="500"><?php echo $attraction['attraction_address'] ?></textarea>
							<?php echo form_error('attraction_address'); ?>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Attration Website</label>
							<input type="text" id="attraction_website" name="attraction_website" class="form-control url" maxlength="200" value="<?php echo $attraction['attraction_website'] ?>" autocomplete="off"/>
							<?php echo form_error('attraction_website'); ?>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="form-group">
                    <label>
                     Attraction Sports&nbsp;
                      <input type="radio" name="attraction_sports_yes_no" class="minimal" value="1" <?php if ($attraction['attraction_sports_yes_no'] == 1) {echo 'checked';}?>>&nbsp;&nbsp;Yes
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>
                      <input type="radio" name="attraction_sports_yes_no" class="minimal" value="0" <?php if ($attraction['attraction_sports_yes_no'] == 0) {echo 'checked';}?>>&nbsp;&nbsp;NO
                    </label>
              </div>



              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Attractions') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
