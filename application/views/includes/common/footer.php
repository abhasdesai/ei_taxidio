<div class="container">
	<div class="row">
    	<div class="col-md-6">
            <ul class="footer-menu footer-nav">
                <li><a href="<?php echo site_url('our-team'); ?>">Crew &amp; Career</a></li>
                <li><a href="<?php echo site_url('faq'); ?>">FAQs</a></li>
		<li><a href="<?php echo site_url('contest'); ?>" target="_blank">Contest</a></li>
                <?php /* ?><li><a href="<?php echo site_url('media'); ?>">Media</a></li><?php */ ?>
                <li><a href="<?php echo site_url('policies'); ?>">Policies</a></li>
                <li><a href="<?php echo site_url('cookie'); ?>">Cookie</a></li>
                <?php /* ?><li><a href="<?php echo site_url('discuss'); ?>">Forum</a></li><?php */ ?>
                <li><a href="<?php echo site_url('credit'); ?>">Credit</a></li>
                <li><a href="<?php echo site_url('contactus'); ?>">Contact Us</a></li>
                <li><a href="<?php echo site_url('taxidio-for-business'); ?>" target="_blank">Taxidio for Business</a></li>
            </ul>

        </div>
        <div class="col-md-6 text-right">
        	<ul class="footer-menu"><span class="powered">Powered By:</span>
            	<li><a href="https://www.rome2rio.com/" target="_blank"><img src="<?php echo site_url('assets/images/rome-2-rio-small.png') ?>" alt="rome2rio.com"/></a></li>
                <li><a href="https://www.booking.com/" target="_blank"><img src="<?php echo site_url('assets/images/booking-dot-com-small.png') ?>" alt="booking.com"/></a></li>
                <li><a href="https://www.getyourguide.com/" target="_blank"><img src="<?php echo site_url('assets/images/get-your-guide-small.png') ?>" alt="getyourguide.com"/></a></li>
                <?php /* ?><li><a href="#"><img src="<?php echo site_url('assets/images/homestay-small.png') ?>" /></a></li><?php */ ?>
            </ul>
        	<p>Copyright©All Rights Reserved <?php echo date('Y'); ?> Taxidio</p>
        </div>
    </div>
</div>
<div id="forgotpasswordmodal" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="upload-avatar-title" aria-hidden="true">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="forgotclose" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Forgot Password</h4>
      </div>
      <div class="modal-body">

       <div class="box box-primary">
                <!-- form start -->

                <div class="alert alert-success" id="forgotpassordsucdisnonde" style="display:none;">
                    For further instructions, check your email.
                </div>
                 <div class="alert alert-danger" id="forgotpassorderrdisnonde" style="display:none;">
                    Please provide the email id used to register with Taxidio.
                </div>

            <form id="forgotpasswordform" name="forgotpassword_form">
                  <div class="box-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Enter Your Email</label>
                      <input type="email" class="form-control required email" id="email" name="email" placeholder="Email" autocomplete="off" required />
                    </div>
                   <div class="box-footer">

                    <input type="submit" class="link-button" id="forgotpasswordbtn" name="btnsubmit1" value="Submit"/>
                  </div>
                   </div><!-- /.box-body -->
           </form>

       </div><!-- /.box -->

</div>
    </div>

  </div>
</div>

<?php if(isset($webpage) && $webpage=='profile'){ ?>

<div id="imagemodal" class="modal fade dashboard-modal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close dashboard-close-btn" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Upload Profile Image</h4>
    </div>
    <div class="modal-body">

           <div class="row">
                <div class="col-md-12">
                <p id="error-msg"></p>
                  <form id="uploadimage">
                    <div class="row">
                      <div class="col-md-12 col-sm-12">
                       <label class="input-label">Select Image*</label>
                        <div class="row">
                        <div class="col-md-6 col-sm-6">
                       <div id="cropContainerModal" style="width:250px;height:250px;"></div>
                       </div>
                       <div class="col-md-6 col-sm-6">
                        <p class="formateinfo">Supported file format : png, jpg, jpeg, gif - upto 1MB Uploading a new photo will replace the existing photograph.</p>
                       </div>
                       </div>
                       <input type="hidden" id="image_name" name="image_name"/>
                      </div>
                      <div class="col-md-12 text-center uploadbtn">
                         <input class="btn btn-purple btn-lg btnimgsave" id="uploadimagebtn" type="button" value="Upload" >
                      </div>
                    </div>
          </form>
    </div>

      </div>

    </div>

  </div>

</div>
</div>
<script>

  var croppicContainerModalOptions = {
      uploadUrl:'<?php echo site_url("/img_save_to_file_profile") ?>',
      cropUrl:'<?php echo site_url("/img_crop_to_file_profile") ?>',
      modal:true,
      imgEyecandyOpacity:0.4,
      loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
      onAfterImgCrop:function(){ $("#uploadimagebtn").show(); },
      onReset:function(){ $.ajax({ type:'POST',url:'<?php echo site_url("removeProfileImageFromStorage") ?>',data:'imagenm='+$('#image_name').val(),success:function(data){  } }); $("#cropOutput").val(''); },
      onError:function(errormessage){ alert(errormessage); }
  };
  var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);

</script>
<?php } ?>
<script type="text/javascript">

  var onloadCallback = function() {

    if ( $('#example1').length ) {
        grecaptcha.render('example1', {'sitekey' : '6LeLwBYUAAAAALUafWRDJ5tfwKep7WL-Mzh91NAx'
        });
    }
    if ( $('#example2').length ) {
       grecaptcha.render('example2', {'sitekey' : '6LeLwBYUAAAAALUafWRDJ5tfwKep7WL-Mzh91NAx'
       });
    }

};
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
    async defer>
</script>
