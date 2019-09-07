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
				<?php echo form_open('admins/Seo/Topcountries/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							 <label>Country</label>
								<select name="country_id" class="required select2 form-control" id="country_id">
									<option value="">Select Country</option>
									<?php foreach ($countries as $list) {?>
										<option value="<?php echo $list['id'] ?>"  <?php echo set_select('country_id', $list['id']); ?> ><?php echo $list['country_name'] ?></option>
									<?php }?>
								</select>
								<?php echo form_error('country_id'); ?>
 						</div>
					</div>


			   </div>


				 <div class="row">
						<div class="col-md-12">
							 <div class="form-group">
								<label>Description</label>
									 <textarea name="description" class="form-control required" rows="10"><?php echo set_value('description') ?></textarea>
								 <?php echo form_error('description'); ?>
						 </div>
					 </div>


					</div>

					<div class="row">
						<div class="col-md-8">
							 <div class="form-group">
								<label>Slug</label>
									 <input name="slug" class="form-control required required" maxlength="500" value="<?php echo set_value('slug') ?>" />
								 <?php echo form_error('slug'); ?>
						 </div>
					 </div>

					</div>

				 <div class="row">
 					 <div class="col-md-12">
 						  <div class="form-group">
 							 <label>Meta Title</label>
 									<input type="text" name="meta_title" class="form-control" maxlength="500" value="<?php echo set_value('meta_title') ?>" />
 								<?php echo form_error('meta_title'); ?>
  						</div>
 					</div>


 			   </div>

				 <div class="row">
						<div class="col-md-12">
							 <div class="form-group">
								<label>Meta Keywords</label>
									 <textarea name="meta_keywords" class="form-control" maxlength="1500" rows="4"><?php echo set_value('meta_keywords') ?></textarea>
								 <?php echo form_error('meta_keywords'); ?>
						 </div>
					 </div>


					</div>


					<div class="row">
						 <div class="col-md-12">
								<div class="form-group">
								 <label>Meta Description</label>
										<textarea name="meta_description" class="form-control" maxlength="5000" rows="4"><?php echo set_value('meta_description') ?></textarea>
									<?php echo form_error('meta_description'); ?>
							</div>
						</div>

					 </div>

					 <div class="row">
 						 <div class="col-md-4">
 								<div class="form-group">
 								 <label>Sort Order</label>
 										<input name="sortorder" class="form-control required number" maxlength="11" value="<?php if(set_value('sortorder')){ echo set_value('sortorder'); }else{ echo '0'; } ?>" />
 									<?php echo form_error('sortorder'); ?>
 							</div>
 						</div>

 					 </div>


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Seo/Topcountries') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
