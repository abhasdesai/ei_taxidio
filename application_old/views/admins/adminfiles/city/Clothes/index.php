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
					

				<?php if(count($months)){ ?>
	

				<?php echo form_open('admins/city/Clothes/saveData', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				 <div class="row">
				<input type="hidden" name="city_id" value="<?php echo $id; ?>"/>
				<?php foreach($months as $list){

					$cloth=$this->Cloth_m->getMonthWiseClothes($list['id'],$id);
				 ?>

				 	 <div class="col-md-6">
						  <div class="form-group">
							<label><?php echo $list['month_name']; ?></label>
							<input type="hidden" name="month_id[]" value="<?php echo $list['id']; ?>"/>
							<input type="text" id="clothes" name="clothes[]" class="form-control" maxlength="400" value="<?php if(isset($cloth['clothes']) && $cloth['clothes']!=''){ echo $cloth['clothes']; } ?>" autocomplete="off"/>
							<?php echo form_error('clothes'); ?>
						 </div><!-- /.form-group -->
					</div>
				
			<?php } ?>
			</div><!-- /.row -->	

              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

				<?php }else{ ?>
					<div class="alert alert-info" role="alert">
						Months are not exists.
					</div>
				<?php } ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
