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

              <!--  <div class="alert bg-danger">
                    <?php echo $this->session->flashdata('error'); ?>
               </div> -->

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

                    <?php echo form_open('editUser',array('id'=>'userform','class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data')); ?>
                    
                  <div class="row">
					  <div class="text-left">
                     <div class="col-md-12">
                            <p class="text-muted font-13"><strong>Travel Preference :</strong>
                            <span class="m-l-15"><select name="tag_id[]" id="tag_id" class="required select2 form-control" multiple>
                                <option value="">Select Tag</option>
                                <?php foreach ($tags as $list) {?>
                                    <option value="<?php echo $list['id'] ?>" <?php echo set_select('tag_id', $list['id']); ?>

                                    <?php foreach($usertags as $countrytagslist){ if($countrytagslist['tag_id']==$list['id']){ echo 'selected'; } } ?>

                                      ><?php echo $list['tag_name'] ?></option>
                                <?php }?>
                                </select></span></p>
                    </div>
                    </div>
                </div>  

                    <div class="row">
                    <div class="text-left">
                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Name :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="name" value="<?php echo $user['name']; ?>" maxlength="200" <?php if($this->session->userdata('issocial')==1){ ?> readonly="true" <?php } ?>></span></p>
                        </div>

                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15"><input type="email" class="required form-control" name="email" value="<?php echo $user['email']; ?>" maxlength="email" <?php if($this->session->userdata('issocial')==1){ ?> readonly="true" <?php } ?>></span></p>
                        </div>

                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Date Of Birth (dd/mm/yyyy) :</strong> <span class="m-l-15"><input type="text" class="required form-control past" name="dob" value="<?php if(isset($user['dob']) && $user['dob']!='' && strtotime($user['dob'])>0){ echo date('d/m/Y',strtotime($user['dob'])); }else{ echo set_value('dob'); } ?>" maxlength="10" ></span></p>
                        </div>

                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Phone :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="phone" value="<?php if(isset($user['phone']) && $user['phone']!=''){ echo $user['phone']; }else{ echo set_value('phone'); } ?>" pattern= "[0-9]+"  title= "Enter Number Only" maxlength="15"></span></p>
                        </div>
                        
                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Country :</strong> <span class="m-l-15">
                                <select class="required form-control" name="country_id" >
                                            <option value="">Country</option>
                                            <?php foreach($countries as $list){ ?>
                                                <option value="<?php echo $list['id']; ?>" <?php if($user['country_id']!=0 && $user['country_id']==$list['id']){ echo 'selected'; }else{ echo set_select('country_id', $list['id']); } ?>><?php echo $list['name']; ?></option>
                                            <?php } ?>
                                        </select>
                            </span></p>
                        </div>
                        
                        <div class="col-md-4">
                            <p class="text-muted font-13"><strong>Passport Number :</strong> <span class="m-l-15"><input type="text" class="form-control" name="passport" value="<?php if(isset($user['passport'])){ echo $user['passport']; }else{ echo set_value('passport'); } ?>" maxlength="100"></span></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15">
                            <input type="radio" class="" name="gender" value="1" <?php if(isset($user['gender']) && $user['gender']==1){ echo 'checked'; }elseif($user['gender']==0) { echo 'checked'; } ?>> Male
                            <input type="radio" class="" name="gender" value="2" <?php if(isset($user['gender']) && $user['gender']==2){ echo 'checked'; } ?>> Female
                            <input type="radio" class="" name="gender" value="3" <?php if(isset($user['gender']) && $user['gender']==3){ echo 'checked'; } ?>> Other
                            </span>
                            </p>
                            <p class="text-error"><?php echo form_error('gender'); ?></p>
                        </div>

                        

                        
                      </div>
                    
                    </div>
                    <div class="sos">
                    <h2 class="sos_title">Emergency Contact Details</h2>
                        <hr></hr>
                    <div class="row">
                        <div class="text-left ecd">
                         <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Name :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_name" value="<?php if(isset($sos[0]['name'])) echo $sos[0]['name']; ?>" maxlength="200" ></span></p>
                            <p class="text-error"><?php //echo form_error('name'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Relation :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_relation" value="<?php if(isset($sos[0]['relation'])) echo $sos[0]['relation']; ?>" maxlength="200" ></span></p>
                            <p class="text-error"><?php //echo form_error('relation'); ?></p>
                        </div>

                        <div class="col-md-6 country_code">
                         <div class="col-md-4">
                             <p class="text-muted font-13"><strong>Country Code:</strong> <span class="m-l-15">

                                <select name='sos_country_code' class='required form-control'>
                                <?php 
                                $country_code=$sos[0]['country_code'];
                                $output = "<option value=''>Select Country Code</option>";
                                foreach($countryCallingCode as $code => $country){
                                    $countryName = ucwords(strtolower($country["name"])); // Making it look good
                                    $output .= "<option value='+".$country["code"]."' ".(("+".$country["code"]==$country_code)?"selected":"").">".$countryName." (+".$country["code"].")</option>";
                                }
                                echo $output;
                                ?>                                
                                </select>
                            </span></p>
                         </div>
                         <div class="col-md-8">
                            <p class="text-muted font-13"><strong>Phone :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_phone" value="<?php if(isset($sos[0]['phone']) && $sos[0]['phone']!=''){ echo $sos[0]['phone']; } ?>" ></span></p>
                         </div>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15"><input type="email" class="required form-control" name="sos_email" value="<?php if(isset($sos[0]['email'])) echo $sos[0]['email']; ?>" ></span></p>
                        </div>

                        
                      </div>
                    </div>
                    </div>

                    <div class="sos2" style="<?php  if(empty($sos) || count($sos)==1){ echo 'display: none;';} ?>">
                    <h2 class="sos_title">Second Emergency Contact Details</h2>
                        <hr></hr>
                    <div class="row">
                        <div class="text-left ecd">
                         <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Name :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_name2" value="<?php if(isset($sos[1]['name'])) echo $sos[1]['name']; ?>" maxlength="200" ></span></p>
                            <p class="text-error"><?php //echo form_error('name'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Relation :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_relation2" value="<?php if(isset($sos[1]['relation'])) echo $sos[1]['relation']; ?>" maxlength="200" ></span></p>
                            <p class="text-error"><?php //echo form_error('name'); ?></p>
                        </div>

                        <div class="col-md-6 country_code">
                         <div class="col-md-4">
                             <p class="text-muted font-13"><strong>Country Code:</strong> <span class="m-l-15">

                                <select name='sos_country_code2' class='required form-control' id="cc2">
                                <?php 
                                $country_code=$sos[1]['country_code'];
                                $output = "<option value=''>Select Country Code</option>";
                                foreach($countryCallingCode as $code => $country){
                                    $countryName = ucwords(strtolower($country["name"])); // Making it look good
                                    $output .= "<option value='+".$country["code"]."' ".(("+".$country["code"]==$country_code)?"selected":"").">".$countryName." (+".$country["code"].")</option>";
                                }
                                echo $output;
                                ?>                                
                                </select>
                            </span></p>
                         </div>
                         <div class="col-md-8">
                            <p class="text-muted font-13"><strong>Phone :</strong> <span class="m-l-15"><input type="text" class="required form-control" name="sos_phone2" value="<?php if(isset($sos[1]['phone']) && $sos[1]['phone']!=''){ echo $sos[1]['phone']; } ?>" ></span></p>
                         </div>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15"><input type="email" class="required form-control" name="sos_email2" value="<?php if(isset($sos[1]['email'])) echo $sos[1]['email']; ?>" maxlength="email" ></span></p>
                        </div>

                        
                      </div>
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary addmore" onclick="addmore()" style="<?php if(count($sos)!=0 && count($sos)!=1){ echo 'display: none;';} ?>">Add Contact <span class="glyphicon glyphicon-plus"></span></button>
                        <button type="button" class="btn btn-danger removerow" onclick="removerow()" style="<?php if(empty($sos) || count($sos)==1){ echo 'display: none;';} ?>">Delete Contact <span class="glyphicon glyphicon-trash"></span></button>
                    </div>
                            <div class="col-md-12 m-t-30">
                                <input type="submit" name="btnsubmit" value="Save" class="btn btn-purple">
                                </div>
                    </div>

                    <?php echo form_close(); ?>

                </div>

            </div>


        </div>

  </div>



</div>
 <!-- container -->
<script type="text/javascript">


    function addmore()
    {
        $(".addmore").hide();
        $(".removerow").show();
        $(".sos2").show();
    }

    function removerow()
    {
        $(".removerow").hide();
        $(".addmore").show();
        $(".sos2").hide();
        $(".sos2 input[type=text], .sos2 input[type=email]").val(""); 
        $('#cc2').prop('selectedIndex',0);
    }

</script>
