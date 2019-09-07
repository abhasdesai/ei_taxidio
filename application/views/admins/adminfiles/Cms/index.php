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
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $section; ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->
           
            <div class="box-body">
				<?php echo form_open('admins/Cms/saveContent',array('id'=>'form1','enctype'=>'multipart/form-data')); ?>
				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Title</label>
							<input type="text" name="title" class="form-control required" maxlength="300" value="<?php if(isset($cms['title']) && $cms['title']!=''){ echo $cms['title']; }else{ echo set_value('title'); } ?>"/>
							<?php echo form_error('title'); ?>
							<input type="hidden" name="id" value="<?php echo md5($cms['id']) ?>" />
						  </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
              
              <div class="row">
					
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Content</label>
							<textarea name="content" id="editor1" class="form-control required"><?php if(isset($cms['title']) && $cms['title']!=''){ echo $cms['content']; }else{ echo set_value('content'); } ?></textarea>
							<?php echo form_error('content'); ?>
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
