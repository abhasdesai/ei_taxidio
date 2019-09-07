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
				<?php echo form_open('admins/Packages/edit/' . $id, array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Package Name:</label>
							<input type="text" id="name" name="name" class="form-control required" value="<?php echo $package['package_name'] ?>"/>
							<?php echo form_error('name'); ?>
						</div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="qty">Package Quantity:</label>
							<input type="number" id="qty" name="qty" class="form-control required" value="<?php echo $package['package_qty'] ?>"/>
							<?php echo form_error('qty'); ?>
						</div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="price">Package Price:</label>
							<input type="number" id="price" name="price" class="form-control required" value="<?php echo $package['package_price'] ?>"/>
							<?php echo form_error('price'); ?>
						</div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Package Status:</label>&nbsp;
							<input type="radio" value=1 id="status_a" name="status" class="minimal" <?php echo ($package['status']==1)?'checked':'';?> />&nbsp;<label for="status_a">Active</label>&nbsp;
							<input type="radio" value=0 id="status_ina" name="status" class="minimal" <?php echo ($package['status']==0)?'checked':'';?>/>&nbsp;<label for="status_ina">In-active</label>
							<?php echo form_error('status'); ?>
						</div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Package Description:</label>&nbsp;
							<textarea name="description" id="editor1" class="form-control required"><?php echo $package['description'];?></textarea>
							<?php echo form_error('decsription'); ?>
						</div><!-- /.form-group -->
					</div>
				</div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/Packages') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
