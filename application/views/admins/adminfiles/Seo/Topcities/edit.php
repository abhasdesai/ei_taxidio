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
				<?php echo form_open('admins/Seo/Topcities/edit/'.$id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							 <label>City</label>
								<select name="city_id" class="required select2 form-control" id="city_id">
									<option value="">Select City</option>
									<?php foreach ($cities as $list) {?>
										<option value="<?php echo $list['id'] ?>" <?php if($list['id']==$topcities['city_id']){ echo 'selected'; } ?> ><?php echo $list['city_name'] ?></option>
									<?php }?>
								</select>
								<?php echo form_error('city_id'); ?>
 						</div>
					</div>


			   </div>


				 <div class="row">
						<div class="col-md-12">
							 <div class="form-group">
								<label>Description</label>
									 <textarea name="description" class="form-control required" rows="10"><?php echo $topcities['description'] ?></textarea>
								 <?php echo form_error('description'); ?>
						 </div>
					 </div>


					</div>

					<div class="row">
						<div class="col-md-8">
							 <div class="form-group">
								<label>Slug</label>
									 <input name="slug" class="form-control required required" maxlength="500" value="<?php echo $topcities['original_title'] ?>" />
								 <?php echo form_error('slug'); ?>
						 </div>
					 </div>

					</div>

				 <div class="row">
 					 <div class="col-md-12">
 						  <div class="form-group">
 							 <label>Meta Title</label>
 									<input type="text" name="meta_title" class="form-control" maxlength="500" value="<?php echo $topcities['meta_title'] ?>" />
 								<?php echo form_error('meta_title'); ?>
  						</div>
 					</div>


 			   </div>

				 <div class="row">
						<div class="col-md-12">
							 <div class="form-group">
								<label>Meta Keywords</label>
									 <textarea name="meta_keywords" class="form-control" maxlength="1500" rows="4"><?php echo $topcities['meta_keywords'] ?></textarea>
								 <?php echo form_error('meta_keywords'); ?>
						 </div>
					 </div>


					</div>


					<div class="row">
						 <div class="col-md-12">
								<div class="form-group">
								 <label>Meta Description</label>
										<textarea name="meta_description" class="form-control" maxlength="5000" rows="4"><?php echo $topcities['meta_description'] ?></textarea>
									<?php echo form_error('meta_description'); ?>
							</div>
						</div>

					 </div>

					 <div class="row">
 						 <div class="col-md-4">
 								<div class="form-group">
 								 <label>Sort Order</label>
 										<input name="sortorder" class="form-control required number" maxlength="11" value="<?php if($topcities['sortorder']=='999999999'){ echo '0'; }else{ echo $topcities['sortorder']; } ?>" />
 									<?php echo form_error('sortorder'); ?>
 							</div>
 						</div>

 					 </div>


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Seo/Topcountries') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
