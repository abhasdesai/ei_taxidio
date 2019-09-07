<div class="wraper container">
<div class="row">
    <div class="col-sm-12">
       <h4 class="page-title">Refer Taxidio to a friend</h4>
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

               

                    <?php echo form_open('refer-friend',array('id'=>'userform','class'=>'form-horizontal','role'=>'form')); ?>




                    
                  <div class="row">
                     <div class="col-md-2">
                            <p class="text-muted font-13"><strong>Email : </strong>

                            
                    </div>
                     <div class="col-md-10">
                            
                            <input type="email" class="form-control" name="email" id="email"  >
                            <p><?php echo form_error('email'); ?></p>
                    </div>
                </div> 
                <div class="row"> 
                <div class="col-md-12 block-center m-t-15">
                                <input type="submit" name="btnsubmit" value="Refer" class="btn btn-purple">
                </div>
                </div>

                    

                   

                    <?php echo form_close(); ?>

                </div>

            </div>


        </div>

  </div>



</div>
