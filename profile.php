<div class="wraper container">

                        <!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
       <h4 class="page-title">My Profile</h4>
    </div>
</div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
              
      <?php if($this->session->flashdata('success')){ ?>
                <div class="alert bg-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>

         <?php  }else if($this->session->flashdata('error')){  ?>

               <div class="alert bg-danger"">
                    <?php echo $this->session->flashdata('error'); ?>
               </div>

         <?php } ?> 

            <div class="profile-detail card-box">
                <div>
                   <?php /* ?>
                    <img src="<?php echo site_url('assets/dashboard/images/users/avatar-8.jpg') ?>" class="img-circle" alt="profile-image">

                    <ul class="list-inline status-list m-t-20">
                        <li>
                            <h3 class="text-primary m-b-5">8</h3>
                            <p class="text-muted">Trips</p>
                        </li>

                        <li>
                            <h3 class="text-success m-b-5">160</h3>
                            <p class="text-muted">Attractions</p>
                        </li>
                    </ul>
                    <?php */ ?>

                    <hr>
                    <?php echo form_open('editUser',array('class'=>'form-horizontal','role'=>'form')); ?>
                    <div class="row">
                    <div class="text-left">
                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Name :</strong> <span class="m-l-15"><input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required maxlength="200"></span></p>
                            <p class="text-error"><?php echo form_error('name'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15"><input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required maxlength="email"></span></p>
                            <p class="text-error"><?php echo form_error('email'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Date Of Birth (dd/mm/YYYY) :</strong> <span class="m-l-15"><input type="text" class="form-control past" name="dob" value="<?php if(isset($user['dob'])){ echo date('d/m/Y',strtotime($user['dob'])); }else{ echo set_value('dob'); } ?>" maxlength="10" required></span></p>
                            <p class="text-error"><?php echo form_error('dob'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Phone :</strong> <span class="m-l-15"><input type="text" class="form-control" name="phone" value="<?php if(isset($user['phone']) && $user['phone']!=''){ echo $user['phone']; }else{ echo set_value('phone'); } ?>" required maxlength="15"></span></p>
                            <p class="text-error"><?php echo form_error('phone'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15">
                            <input type="radio" class="" name="gender" value="1" <?php if(isset($user['gender']) && $user['gender']==1){ echo 'checked'; } ?>> Male
                            <input type="radio" class="" name="gender" value="2" <?php if(isset($user['gender']) && $user['gender']==2){ echo 'checked'; } ?>> Female
                            </span>
                            </p>
                            <p class="text-error"><?php echo form_error('gender'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Passport Number :</strong> <span class="m-l-15"><input type="text" class="form-control" name="passport" value="<?php if(isset($user['passport'])){ echo $user['passport']; }else{ echo set_value('passport'); } ?>" maxlength="100"></span></p>
                            <p class="text-error"><?php echo form_error('passport'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Country :</strong> <span class="m-l-15">
                                <select class="form-control" name="country_id" required>
                                            <option value="">Country</option>
                                            <?php foreach($countries as $list){ ?>
                                                <option value="<?php echo $list['id']; ?>" <?php if($user['country_id']!=0 && $user['country_id']==$list['id']){ echo 'selected'; }else{ echo set_select('country_id', $list['id']); } ?>><?php echo $list['name']; ?></option>
                                            <?php } ?>
                                        </select>
                            </span></p>
                            <p class="text-error"><?php echo form_error('country_id'); ?></p>
                        </div>



                      </div>
                    </div>

                    <div class="row">
                            <div class="col-md-6">
                                <input type="submit" name="btnsubmit" value="save" class="btn btn-purple">
                                </div>
                        </div>
                    
                    <?php echo form_close(); ?>
                    
                </div>

            </div>

            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>Travel Profile</b></h4>

                <div class="friend-list">
                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-1.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-2.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-3.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-4.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-5.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-6.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>

                    <a href="#">
                        <img src="<?php echo site_url('assets/dashboard/images/users/avatar-7.jpg') ?>" class="img-circle thumb-lg" alt="friend">
                    </a>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12">
            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>Your Feedback</b></h4>
                
                <form method="post" class="well">
                <span class="input-icon icon-right">
                    <textarea rows="2" class="form-control" placeholder="Your Message"></textarea>
                </span>
                <div class="p-t-10 pull-right">
                    <a class="btn btn-sm btn-primary waves-effect waves-light">Send</a>
                </div>
                

            </form>
                
            </div>
        </div>
    </div>



</div>

<div class="wraper container">

    <!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
       <h4 class="page-title">Profile</h4>
    </div>
</div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="profile-detail card-box">
                <div>

                    <div class="row">

                     <?php if($this->session->flashdata('success')){ ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>

                     <?php  }else if($this->session->flashdata('error')){  ?>

                           <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                           </div>

                     <?php } ?> 



                    <?php echo form_open('editUser',array('class'=>'form-horizontal','role'=>'form')); ?>
                        <div class="col-md-6">
                                                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Name</label>
                                    <div class="col-md-10">
                                        <input type="name" class="form-control" name="name" value="<?php echo $user['name']; ?>" required maxlength="100">
                                    </div>
                                    <?php echo form_error('name'); ?>
                                </div>

                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Passport Number</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="passport" value="<?php if(isset($user['passport'])){ echo $user['passport']; } ?>" maxlength="100">
                                    </div>
                                    <?php echo form_error('passport'); ?>
                                </div>

                                 <div class="form-group">
                                    <label class="col-md-2 control-label">Date Of Birth</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control past" name="dob" value="<?php if(isset($user['dob'])){ echo date('d/m/Y',strtotime($user['dob'])); } ?>" placeholder="dd/mm/YYYY" required>
                                    </div>
                                    <?php echo form_error('dob'); ?>
                                </div>
                                                         
                        </div>
                        
                        <div class="col-md-6">
                                                              
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="example-email">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" id="email" class="form-control" name="email" placeholder="Email" required value="<?php echo $user['email']; ?>" maxlength="250">
                                    </div>
                                    <?php echo form_error('email'); ?>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="country_id" required>
                                            <option value="">Country</option>
                                            <?php foreach($countries as $list){ ?>
                                                <option value="<?php echo $list['id']; ?>" <?php if($user['country_id']!=0 && $user['country_id']==$list['id']){ echo 'selected'; } ?>><?php echo $list['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error('country_id'); ?>
                                </div>

                         </div>
                        
                         <input type="submit" name="btnsubmit" value="save" class="btn btn-purple">
                        
                        </form>

                    </div>

                 </div>

            </div>

            
        </div>


        
    </div>



</div> <!-- container -->
           
