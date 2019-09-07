<div class="wraper container">
    <?php //echo "<pre>";print_r(strtotime($user['dob']));die; ?>
        <!-- Page-Title -->
        <div class="row">
                <div class="col-sm-12">
                       <h4 class="page-title">Change Password</h4>
                </div>
        </div>
            <div class="row">
                    <div class="col-md-12 col-lg-12">
                              
                <?php if($this->session->flashdata('success')){ ?>
                                <div class="alert bg-success">
                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                <?php  }else if($this->session->flashdata('error')){  ?>
                               <div class="alert bg-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                               </div>
                <?php } ?>
                            <div class="profile-detail card-box">
                                    <div>
                                        
                        <?php echo form_open('changepassword',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data')); ?>
                                            
                                            <div class="text-left">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                                <p class="text-muted font-13"><strong>Current Password :</strong> <span class="m-l-15"><input type="password" class="form-control" name="cpassword" required></span>
                                                                <?php echo form_error('cpassword'); ?>
                                                                </p>
                                            
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                                <p class="text-muted font-13"><strong>New Password :</strong> <span class="m-l-15"><input type="password" class="form-control" name="newpassword" value="" required minlength="6"></span>
                                                                <?php echo form_error('newpassword'); ?>
                                                                </p>
                                                                
                                   
                                                        </div>
                                                       
                                                  </div>

                                                  <div class="row">
                                                        <div class="col-md-6">
                                                                <p class="text-muted font-13"><strong>Re-type Password :</strong> <span class="m-l-15"><input type="password" class="form-control" name="rnewpassword"required ></span>
                                                                <?php echo form_error('rnewpassword'); ?>
                                                                </p>
                                                                
                                  
                                                        </div>
                                                       
                                                  </div>

                                            </div>
                                            <div class="row">
                                                        <div class="col-md-12">
                                                                <input type="submit" name="btnsubmit" value="Save" class="btn btn-purple btnfloat">
                                                            </div>
                                            </div>
                                            
                        <?php echo form_close(); ?>
                                            
                                    </div>
                            </div>
                            
                    </div>
          </div>
    </div>
    <!-- container -->
