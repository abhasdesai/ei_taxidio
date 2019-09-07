<div class="container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-9">
            <h4 class="page-title">Forum</h4>
        </div>
        <div class="col-sm-3 create-feedback">
           <a href="<?php echo site_url('myquestions'); ?>" class="btn btn-warning waves-effect waves-light pull-right">Back</a>
    </div>
    </div>
    
    
    <div class="row">
        
           <div class="col-sm-12">
            <div class="card-box">
                <?php echo form_open('addQuestion/'.$itineraryinfo['id'],array('enctype'=>'multipart/form-data','class'=>'form-horizontal')) ?>
                
                    <div class="row">
                         <?php if($this->session->flashdata('error')){  ?>
                             <div class="alert bg-danger"">
                                <?php echo $this->session->flashdata('error'); ?>
                           </div>
                        <?php } ?> 

                    <div class="col-lg-12">

                  
                  <p class="iti-title-account" ><strong>Itinerary :</strong><?php echo $itineraryinfo['user_trip_name']; ?></p>
              
                 
                  <div class="form-group ask-que">
                     <label for="elm1">Ask Question</label>
                     <textarea name="question" class="form-control" rows="3" required maxlength="2000"><?php echo set_value('question'); ?></textarea>
                     <p><?php echo form_error('question') ?></p>
                  </div>
                   <div class="form-group ask-que">
                        <div class="m-t-15">
                            <input type="submit" value="Ask" class="btn btn-primary"/>
                             <a href="<?php echo site_url('myquestions'); ?>" class="btn btn-danger waves-effect waves-light">Cancel</a>
                        </div>
                    </div>

                    </div>
                   </div> 


                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- End row -->
</div> <!-- container -->