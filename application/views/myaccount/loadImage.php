<?php if ($images['userimage'] != '' && file_exists(FCPATH.'userfiles/userimages/small/'.$images['userimage'])) {?>
<div class="img_user">
    <img id="userimage" src="<?php echo site_url('userfiles/userimages/small').'/'.$images['userimage'] ?>" class="img-circle" alt="profile-image">
    <span><a id="imgupload" href="javascript:void(0);" data-toggle="tooltip" title="Upload Image"><i class="fa fa-2x fa-upload"></i></a><a href="javascript:void(0);"  data-toggle="tooltip" title="Remove" id="rmvimage"><i class="fa fa-2x fa-trash rmvimage"></i></a></span>
</div>
<?php } else {?>
<div class="img_user">
    <img id="userimage" src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" class="img-circle" alt="profile-image">
    <span><a id="imgupload" href="javascript:void(0);" data-toggle="tooltip" title="Upload Image"><i class="fa fa-2x fa-upload"></i></a></span>
</div>
<?php }?>
<script>
$(document).ready(function(){
$('[data-toggle="tooltip"]').tooltip();
var mainsrc=$("#userimage").attr('src');
var newsrc = mainsrc.replace("medium", "small");
$("#simage").attr('src',newsrc);
});
$("#imgupload").click(function(){
$("#image_name").val('');
$("#uploadimagebtn").hide()
$(".croppedImg").removeAttr('src');
$(".croppedImg").attr('src');
$("#imagemodal").modal('show');

});
$("#rmvimage").click(function(){
$.ajax({
type:'POST',
url:"<?php echo site_url('removeProfileImage') ?>",
success:function(data)
{
//$("#simage").attr('src',sp[1]);
$("#bindpic").html(data.body);
$("#image_name").val('');
}
});
});
</script>