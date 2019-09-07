<div class="wraper container">
<?php //echo "<pre>";print_r($trip);die; ?>
<!-- Page-Title -->
<div class="row">
      <div class="col-sm-12">
             <h4 class="page-title">Edit Trip</h4>
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
              <div class="col-md-12">
                  <div class="card-box">


                      <table class="table">
                          <thead>
                              <tr>
                                  <th>Trip</th>
                                  <th>Date</th>
                              </tr>
                          </thead>

                          <tbody>
                              <?php
                              $tripname_main='';
                                $json=json_decode($trip['inputs'],TRUE);
                                $codes=explode('-',$trip['citiorcountries']);
                              $tripname=explode('-',$trip['tripname']);
                              if(isset($trip['user_trip_name']) && $trip['user_trip_name']!='')
                              {
                                  $tripname_main_name=$trip['user_trip_name'];
                              }
                              else
                              {
                                   if($trip['trip_type']==1 || $trip['trip_type']==3)
                                  {
                                      $tripname_main=$this->Trip_fm->getContinentCountryName($trip['country_id']);
                                  }
                                  else
                                  {
                                      $tripname_main=$this->Trip_fm->getContinentName($trip['tripname']);
                                  }



                                  $tripname_main_name='Trip '.$tripname_main['country_name'];
                              }

                              if($trip['trip_type']!=3)
                              {
                                $startdate=$json['start_date'];
                                $ttldays=$json['days']-1;
                              }
                              else if($trip['trip_type']==3)
                              {
                                $startdate=$json['sstart_date'];
                                $ttldays=$json['sdays']-1;
                              }


                               $startdateformat=explode('/',$startdate);
                               $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];
                              ?>
                              <tr>
                                  <td class="middle-align">
                                      <div class="trip-with-code">
                                        <div class="trip-name" data-toggle="tooltip" title="<?php echo $tripname_main_name; ?>"><?php echo word_limiter($tripname_main_name,4); ?></div>

                                          <div class="trip-city-country">
                                          <?php for($i=0;$i<count($tripname);$i++){ ?>
                                          <span class="trip-code" data-toggle="tooltip" title="<?php if(isset($codes[$i]) && $codes[$i]!=''){ echo $codes[$i]; }else{ echo $tripname[$i]; } ?>"><?php echo $tripname[$i]; ?></span>
                                          <?php } ?>
                                          </div>
                                      </div>
                                  </td>
                                  <td>

                                      <div class="trip-time">
                                          <div class="trip-time">
                                              <a href="#"><?php echo date('d-M-Y',strtotime($startdateymd)).' - '.date('d-M-Y', strtotime($startdateymd. " + $ttldays days")); ?> </a>
                                          </div>
                                      </div>
                                  </td>


                              </tr>


                          </tbody>
                      </table>

                  </div>
              </div>
          </div>
      </div>

              <?php echo form_open('updateTrip/'.$trip['id'],array('class'=>'form-horizontal frmmarbtm','id'=>'formtovalidate','role'=>'form','enctype'=>'multipart/form-data')); ?>

                                  <div class="text-left">

                                      <div class="row">
                                              <div class="col-md-6">
                                                      <p class="text-muted font-13"><strong>Trip Name :</strong> <span class="">
                                                      <input type="text" class="form-control" name="user_trip_name" value="<?php echo $trip['user_trip_name']; ?>" maxlength="500"></span></p>
                                              <?php echo form_error('user_trip_name'); ?>
                                              </div>
                                          </div>



                                        <div class="row">
                                              <div class="col-md-6">
                                                      <p class="text-muted font-13"><strong>Start Date (dd/mm/yyyy) :</strong> <span class=""><input type="text" class="form-control future" name="start_date" required value="<?php echo date('d/m/Y',strtotime($startdateymd)) ?>" readonly></span></p>
                                  <?php echo form_error('start_date'); ?>
                                              </div>
                                            </div>

                                          <div class="row">
                                              <div class="col-md-6">
                                                      <p class="text-muted font-13"><strong>End Date (dd/mm/yyyy) :</strong> <span class=""><input type="text" class="form-control future" name="end_date" required minlength="6" value="<?php echo date('d/m/Y', strtotime($startdateymd. " + $ttldays days")) ?>" readonly></span></p>
                                                      <?php echo form_error('end_date'); ?>

                                              </div>

                                        </div>


                                        <div class="row">
                                          <p class="text-muted font-13"> <span class="m-l-15">
                                          <input type="radio" class="" name="trip_mode" value="1" <?php if($trip['trip_mode']==1){ echo "checked"; } ?> onclick="checkEmail()"> Private
                                          <input type="radio" class="" name="trip_mode" id="trip_mode_public" value="2" <?php if($trip['trip_mode']==2){ echo "checked"; } ?> onclick="checkEmail()"> Public
                                          </span>
                                          </p>
                                          <p class="text-error"><?php echo form_error('trip_mode'); ?></p>
                                            </div>

                                            <div class="row" id="usemail" style="display:none">
                                                    <div class="col-md-6">
                                                            <p class="text-muted font-13"><strong>Email :</strong> <span class="">
                                                            <input type="email" class="form-control required email" name="email" maxlength="450" value="<?php echo set_value('email') ?>"></span></p>
                                                            <?php echo form_error('email'); ?>
                                                            <span class="text-error-blue">
                                                              We need your email before making this trip public.
                                                            </span>
                                                    </div>

                                                </div>

                                  </div>





                                  <div class="row">
                                        <div class="col-md-12">
                                              <input type="submit" name="btnsubmit" value="Save" class="btn btn-purple btnfloat">
                                              <a href="<?php echo site_url('trips') ?>" class="btn btn-default waves-effect waves-light m-l-5 btnfloat">Cancel</a>
                                          </div>
                                  </div>

                             <?php echo form_close(); ?>

                          </div>
                  </div>

          </div>
</div>
</div>

<script>
  function checkEmail()
  {
    var chk=$("input[name='trip_mode']:checked").val();
    var askformeamil='<?php echo $this->session->userdata('askforemail'); ?>';
    if(chk==2 && askformeamil==1)
    {
      $("#usemail").show();
    }
    else {
      $("#usemail").hide();
    }
  }

  $(document).ready(function(){
    var isemail='<?php if(isset($_POST['email'])){ echo "1"; }else{ echo "2"; } ?>';
    if(isemail==1)
    {
        $("#usemail").show();
    }
    else
    {
      $("#usemail").hide();
    }
  });


</script>
