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
				<?php echo form_open('admins/Budgets/edit/' . $id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div class="row">
					 <div class="col-md-3">
						  <div class="form-group">
							<label>From <i class="fa fa-inr" aria-hidden="true"></i></label>
							<input type="text" id="budget_hotel_per_night_from" name="budget_hotel_per_night_from" class="form-control required" maxlength="11" value="<?php echo $budget['budget_hotel_per_night_from'] ?>" autocomplete="off"/>
							<?php echo form_error('budget_hotel_per_night_from'); ?>
						 </div><!-- /.form-group -->
					</div>

					 <div class="col-md-3">
						  <div class="form-group">
							<label>To <i class="fa fa-inr" aria-hidden="true"></i></label>
							<input type="text" id="budget_hotel_per_night_to" name="budget_hotel_per_night_to" class="form-control required" maxlength="11" value="<?php echo $budget['budget_hotel_per_night_to'] ?>" autocomplete="off"/>
							<?php echo form_error('budget_hotel_per_night_to'); ?>
							<p class="text-red">[keep this field 0 for more (ie 10000+).]</p>
						 </div><!-- /.form-group -->
					</div>

			  </div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Budgets') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
