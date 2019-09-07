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
				<?php echo form_open('admins/Reports/cityReport', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>City</label>
							<select name="city_id" class="form-control required select2">
								<option value="">Select City</option>
								<?php foreach($cities as $continent=>$continentlist){ ?>
									 <optgroup label="<?php echo $continent; ?>">
									<?php $c=0; foreach($continentlist as $country=>$countrylist){ ?>
											<optgroup label="<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;".$country; ?>">
											<?php foreach($countrylist as $list){ ?>
												<option value="<?php echo $list['id']; ?>"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$list['city_name']; ?></option>
										<?php } ?> 
										</optgroup>
									<?php } ?>
									 </optgroup>
								<?php } ?>
							</select>
							<?php echo form_error('days_range'); ?>
						 </div><!-- /.form-group -->
					</div>

				  </div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Days') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
