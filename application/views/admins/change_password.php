<div class="wrapper">
 <?php $this->load->view('admins/includes/main_navi'); ?>
      <!-- Left side column. contains the logo and sidebar -->
		<?php $this->load->view('admins/includes/left_sidebar'); ?>
     
			<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Change Password
          </h1>
          <ol class="breadcrumb">
         </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
            <?php if($this->session->flashdata('success')){ ?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php } ?>  
			
		  <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Change Password</h3>
                    <hr class="hrstyle">
              </div><!-- /.box-header -->
            
                <div class="box-body">
                 	
				 <?php echo form_open('admins/dashboard/change_password/',array('id'=>'form1','enctype'=>'multipart/form-data','onSubmit'=>'return checkpassword()')); ?>
					
					
					  <div class="form-group">
					   <label>Current Password</label>
                       <input type="password" name="cpassword" id="cpassword" maxlength="15" class="form-control txtwidth required" onblur="checkpassword()"/>
						<div id="msg" style="color:red;display:none;">Your current password is wrong.</div>
					 </div><!-- /.form group -->
                    
                      <div class="form-group">
					   <label>New Password</label>
                       <input type="password" name="password" id="password" maxlength="15" minlength="6" class="form-control txtwidth required" />
					 </div><!-- /.form group -->
                    
                     <div class="form-group">
					   <label>Confirm Password</label>
                       <input type="password" name="conpassword" id="conpassword" minlength="6" equalTo="#password" maxlength="15" class="form-control txtwidth required" />
					 </div><!-- /.form group -->
						
                    
                    <table>
						<tr>
							<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
						</tr>
					</table>
				
				<?php echo form_close(); ?>
            
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		</section><!-- /.content -->
      </div><!-- /.content-wrapper -->
		   
		    
      
      <?php $this->load->view('admins/includes/footer'); ?>

  
      <div class="control-sidebar-bg"></div>
 </div><!-- ./wrapper -->
 
 <script>
 
 function checkpassword()
 {
	 var flag=false;
	 $.ajax({
		 
		 type:'POST',
		 url:'<?php echo site_url("admins/dashboard/checkpassword") ?>',
		 data:'password='+$("#cpassword").val(),
		 async:false,
		 success:function(data)
		 {
				if(data==1)
				{
					$("#msg").hide();
					flag=true;
				}
				else
				{
					$("#msg").show();
					flag=false;
				}
		 }
		 });
		  return flag;
		
 }
 
 $(document).ready(function(){
	 
	 $("#form1").validate();
	 
	 })
 </script>
 
 

    
    
    


 

