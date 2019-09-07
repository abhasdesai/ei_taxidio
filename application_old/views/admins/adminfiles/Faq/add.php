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

            <?php if(count($category)){ ?>	

				<?php echo form_open('admins/faq/add',array('id'=>'form1','enctype'=>'multipart/form-data')); ?>

				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Category</label>
							<select id="category_id" name="category_id" class="required select2 form-control">
								<option value="">Select Category</option>
								<?php foreach ($category as $list) {?>
									<option value="<?php echo $list['id'] ?>" <?php echo set_select('category_id', $list['id']); ?> ><?php echo $list['category'] ?></option>
								<?php }?>
							</select>
							<?php echo form_error('category_id'); ?>
						  </div><!-- /.form-group -->
					</div>
					
		      </div>



				  <div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Question</label>
							<textarea name="question" class="form-control required" rows="6"><?php echo set_value('question'); ?></textarea>
							<?php echo form_error('question'); ?>
						  </div><!-- /.form-group -->
					</div>
					
		      </div><!-- /.row -->
              
              <div class="row">
					
					 <div class="col-md-12">
						  <div class="form-group">
							<label>Answer</label>
							<textarea name="answer" id="editor1" class="form-control required"><?php echo set_value('answer'); ?></textarea>
							<?php echo form_error('answer'); ?>
						  </div><!-- /.form-group -->
					</div>
					
					
                  
              </div><!-- /.row -->
              
              
              
              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
					<td><button type="reset" class="btn btn-block btn-danger">Reset</button></td><td>&nbsp;</td>
					<td><a href="<?php echo site_url('admins/faq') ?>" class="btn btn-block btn-default" >Cancel</a></td><td>&nbsp;</td>
				</tr>
			</table>
            
				<?php echo form_close(); ?>
              
		<?php }else{ ?>
			
			<div class="alert alert-info">
				Please add some FAQ Category first.
			</div>

		<?php } ?>		

            </div><!-- /.box-body -->
           
          </div><!-- /.box -->
          
</section><!-- /.content -->
<script>
      $(function () {
        CKEDITOR.replace('editor1');
        $(".textarea").wysihtml5();
      });
    </script>