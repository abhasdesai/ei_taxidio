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
				<?php echo form_open('admins/Defaulttags/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>

				<?php
						$dstadiums=array();
						$drestaurant=array();
						$drelaxation=array();
						$dadventure=array();
						$dattraction=array();


						if($stadium['default_tags']!='')
						{
							$dstadiums=explode(',',$stadium['default_tags']);
						}

						if($stadium['default_tags']!='')
						{
							$drestaurant=explode(',',$restaurant['default_tags']);
						}

						if($stadium['default_tags']!='')
						{
							$drelaxation=explode(',',$relaxation['default_tags']);
						}

						if($stadium['default_tags']!='')
						{
							$dadventure=explode(',',$adventure['default_tags']);
						}

						if($stadium['default_tags']!='')
						{
							$dattraction=explode(',',$attraction['default_tags']);
						}
				 ?>

				<div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Sports & Stadium</label>
							<input type="hidden" name="id[]" value="1" />
							<select class="form-control select2" name="stadium[]" multiple>
								<option>Select Tags</option>								
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'],$dstadiums)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>								
								<?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Restaurant & Nightlife</label>
							<input type="hidden" name="id[]" value="2" />
							<select class="form-control select2" name="restaurant[]" multiple>
								<option>Select Tags</option>								
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'],$drestaurant)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>								
								<?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Attraction</label>
							<input type="hidden" name="id[]" value="3" />
							<select class="form-control select2" name="attraction[]" multiple>
								<option>Select Tags</option>								
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'],$dattraction)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>								
								<?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Relaxation & Spa</label>
							<input type="hidden" name="id[]" value="4" />
							<select class="form-control select2" name="relaxation[]" multiple>
								<option>Select Tags</option>								
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'],$drelaxation)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>								
								<?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->

			   <div class="row">
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Sports & Adventure</label>
							<input type="hidden" name="id[]" value="5" />
							<select class="form-control select2" name="adventure[]" multiple>
								<option>Select Tags</option>								
								<?php foreach($tags as $list){ ?>
								<option value="<?php echo $list['id']; ?>" <?php if(in_array($list['id'],$dadventure)){ echo 'selected'; } ?>><?php echo $list['tag_name']; ?></option>								
								<?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>
			   </div><!-- /.row -->


              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
