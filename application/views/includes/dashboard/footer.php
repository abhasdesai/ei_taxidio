<div id="imagemodal" class="modal fade" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                        <p>Supported file format : png, jpg, jpeg, gif - upto 1MB Uploading a new photo will replace the existing photograph.</p>
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
  }
  var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);

</script>
 <footer class="footer">
    Copyright©All Rights Reserved <?php echo date('Y'); ?> Taxidio
</footer>