//var url = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '/')+'taxidio/';

var url = window.location.origin+'/';

$("#imgupload").click(function(){
	
	$("#image_name").val('');
	$("#uploadimagebtn").hide()
	$("#error-msg").hide();
	$(".cropControlUpload").show();
	$(".croppedImg").removeAttr('src');
	$(".croppedImg").attr('src');
	$("#imagemodal").modal('show');
	
});


$("#uploadimagebtn").click(function(){
	
	var imnm=$("#image_name").val();
	
	$.ajax({
		type:'POST',
		url:url+'uploadImage',
		data:'image='+imnm,
		success:function(data)
		{
			$("#bindpic").html(data.body);
			$("#image_name").val('');
			$("#imagemodal").modal('hide');

			
			
		}
	});
	
});


$("#rmvimage").click(function(){
	$.ajax({
		type:'POST',
		url:url+'removeProfileImage',
		success:function(data)
		{
			$("#bindpic").html(data.body);
			$("#image_name").val('');
		}
	});
});