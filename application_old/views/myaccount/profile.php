<div class="wraper container">
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

               <div class="alert bg-danger">
                    <?php echo $this->session->flashdata('error'); ?>
               </div>

         <?php } ?>

            <div class="profile-detail card-box">
                <div>

                <?php if($this->session->userdata('issocial')==1){ ?>

                    <div class="img_user">
                         <img src="<?php echo $this->session->userdata('socialimage'); ?>" class="img-circle" alt="profile-image">
                        </div>

                <?php }else{ ?>

                  <div id="bindpic">
                      <?php if ($user['userimage'] != '' && file_exists(FCPATH.'userfiles/userimages/small/'.$user['userimage'])) {?>
                        <div class="img_user">
                        <img src="<?php echo site_url('userfiles/userimages/small').'/'.$user['userimage'] ?>" class="img-circle" alt="profile-image">
                         <span><a id="imgupload" href="javascript:void(0);" data-toggle="tooltip" title="Upload Image"><i class="fa fa-2x fa-upload"></i></a><a href="javascript:void(0);"  data-toggle="tooltip" title="Remove" id="rmvimage"><i class="fa fa-2x fa-trash rmvimage"></i></a></span>
                         </div>
                        <?php } else {?>
                        <div class="img_user">
                         <img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" class="img-circle" alt="profile-image">
                         <span><a id="imgupload" href="javascript:void(0);" data-toggle="tooltip" title="Upload Image"><i class="fa fa-2x fa-upload"></i></a></span>
                        </div>
                      <?php }?>
                 </div>

                 <?php } ?>




                   <!--
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

                    <hr>
                    -->
                    <?php echo form_open('editUser',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data')); ?>
                    <div class="row">
                    <div class="text-left">
                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Name :</strong> <span class="m-l-15"><input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required maxlength="200" <?php if($this->session->userdata('issocial')==1){ ?> readonly="true" <?php } ?>></span></p>
                            <p class="text-error"><?php echo form_error('name'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15"><input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required maxlength="email" <?php if($this->session->userdata('issocial')==1){ ?> readonly="true" <?php } ?>></span></p>
                            <p class="text-error"><?php echo form_error('email'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Date Of Birth (dd/mm/yyyy) :</strong> <span class="m-l-15"><input type="text" class="form-control past" name="dob" value="<?php if(isset($user['dob']) && $user['dob']!='' && strtotime($user['dob'])>0){ echo date('d/m/Y',strtotime($user['dob'])); }else{ echo set_value('dob'); } ?>" maxlength="10" required></span></p>
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
