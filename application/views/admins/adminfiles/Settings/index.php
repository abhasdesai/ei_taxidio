<section class="content">
		<?php if($this->session->flashdata('success')){ ?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php } ?>  
			<?php if($this->session->flashdata('error')){ ?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php } ?>  
            
          
				<div class="box box-default">
            <!--<div class="box-header with-border">
              <h3 class="box-title"><?php echo $section; ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->
           
            <div class="box-body">
				<?php echo form_open('admins/Settings/saveContent',array('id'=>'form1','enctype'=>'multipart/form-data')); ?>
				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Email</label>
							<input type="text" name="email" class="form-control required" maxlength="100" value="<?php if(isset($settings['email']) && $settings['email']!=''){ echo $settings['email']; }else{ echo set_value('email'); } ?>"/>
							<?php echo form_error('email'); ?>
							<input type="hidden" name="id" value="<?php echo md5($settings['id']) ?>" />
						  </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
              
              <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Phone No</label>
							<input type="text" name="phone_no" class="form-control required" maxlength="20" value="<?php if(isset($settings['phone_no']) && $settings['phone_no']!=''){ echo $settings['phone_no']; }else{ echo set_value('phone_no'); } ?>"/>
							<?php echo form_error('phone_no'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
             
              <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Address</label>
							<textarea name="address" class="form-control required" maxlength="150"><?php if(isset($settings['address']) && $settings['address']!=''){ echo $settings['address']; }else{ echo set_value('address'); } ?></textarea>	
							<?php echo form_error('address'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
              <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Facebook Link</label>
							<input type="text" name="facebook_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['facebook_link']) && $settings['facebook_link']!=''){ echo $settings['facebook_link']; }else{ echo set_value('facebook_link'); } ?>"/>
							<?php echo form_error('facebook_link'); ?>
						</div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
              <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Twitter Link</label>
							<input type="text" name="twitter_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['twitter_link']) && $settings['twitter_link']!=''){ echo $settings['twitter_link']; }else{ echo set_value('twitter_link'); } ?>"/>
							<?php echo form_error('twitter_link'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
		      <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Google Link</label>
							<input type="text" name="google_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['google_link']) && $settings['google_link']!=''){ echo $settings['google_link']; }else{ echo set_value('google_link'); } ?>"/>
							<?php echo form_error('google_link'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
		       <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Linkedin Link</label>
							<input type="text" name="linkedin_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['linkedin_link']) && $settings['linkedin_link']!=''){ echo $settings['linkedin_link']; }else{ echo set_value('linkedin_link'); } ?>"/>
							<?php echo form_error('linkedin_link'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
		       <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Instagram Link</label>
							<input type="text" name="instagram_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['instagram_link']) && $settings['instagram_link']!=''){ echo $settings['instagram_link']; }else{ echo set_value('instagram_link'); } ?>"/>
							<?php echo form_error('instagram_link'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->

		      <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Pinterest Link</label>
							<input type="text" name="pinterest_link" class="form-control url" maxlength="500" value="<?php if(isset($settings['pinterest_link']) && $settings['pinterest_link']!=''){ echo $settings['pinterest_link']; }else{ echo set_value('pinterest_link'); } ?>"/>
							<?php echo form_error('pinterest_link'); ?>
						 </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->

		      
              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/faq') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>
            
				<?php echo form_close(); ?>
              
            </div><!-- /.box-body -->
           
          </div><!-- /.box -->
          
</section><!-- /.content -->
