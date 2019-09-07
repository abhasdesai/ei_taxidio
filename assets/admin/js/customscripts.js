$(document).ready(function(){
	$("#form1").validate();
});

// folowing function check unique email when adding new user. onBlur

function checkUniqueEmail(url,email)
{
	$.ajax({
		type:'POST',
		url:url,
		data:'email='+email,
		success:function(data)
		{
			if(data>=1)
			{
				$("#emailmsg").show();
			}
			else
			{
				$("#emailmsg").hide();
			}
		}
		});
}

// folowing function check unique username when adding new user. onBlur

function checkUniqueUsername(url,username)
{
	$.ajax({
		type:'POST',
		url:url,
		data:'username='+username,
		success:function(data)
		{
			if(data>=1)
			{
				$("#usernamemsg").show();
			}
			else
			{
				$("#usernamemsg").hide();
			}
		}
		});
}

// folowing function check unique email when editing existing user. onBlur

function checkUniqueEmailForUser(url,email)
{
	var id=$("#id").val();
	$.ajax({
		type:'POST',
		url:url,
		data:'email='+email+'&id='+id,
		success:function(data)
		{
			if(data>=1)
			{
				$("#emailmsg").show();
			}
			else
			{
				$("#emailmsg").hide();
			}
		}
		});
	
}

//following function check current admin password. onblur

function checkCurrentPassword()
{
 $.ajax({
		type:'POST',
		url:$("#websiteurl").val()+'admins/dashboard/checkCurrentPassword',
		data:'currentpassword='+$("#currentpassword").val(),
		success:function(data)
		{
			if(data==1)
			{
				$("#passdisnone").hide();
			}
			else
			{
				$("#passdisnone").show();
			}
		}
		
		});
}

//following function check current admin password. onsubmit

$("#changepasswordbtn").click(function(){

 if($("#changepasswordform").valid())
 {
	var flag=false;
    $.ajax({
		type:'POST',
		url:$("#websiteurl").val()+'admins/dashboard/checkCurrentPassword',
		data:'currentpassword='+$("#currentpassword").val(),
		success:function(data)
		{
			if(data==1)
			{
				$("#passdisnone").hide();
				changpassword($("#newpassword").val());
			}
			else
			{
				$("#passdisnone").show();
				flag=false;
			}
		}
		
		});
		return flag;
    }	
   
	
});

function changpassword(password)
{
	 $.ajax({
		type:'POST',
		url:$("#websiteurl").val()+'admins/dashboard/updateCurrentPassword',
		data:'password='+password,
		success:function(data)
		{
			$("#passdisnonealert").show();
			$("#changepasswordform")[0].reset();
		}
		
		});
}

function openChangePasswordModal()
{
	$('#changepassword').modal('show');
	$("#passdisnonealert").hide();
	$("#changepasswordform")[0].reset();
}


		
