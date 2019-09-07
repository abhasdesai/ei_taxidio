//var getUrl = window.location;
//var baseurl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]+'/';
var baseurl=$("#siteurl").val();
function getData(id) 
{
    if(id!='')
    {
        $.ajax({
            type:'POST',
            url:baseurl+'getData',
            data:'id='+id,
            success:function(data)
            {
                $("#bindData").html(data.body);
               // initialize(lat,lon);
            }
        })
    }
}

$("#showall").click(function(){

     $.ajax({
        url: baseurl+'getAllAttractionsOfCity',
        type: 'POST',
        data: 'id='+$(this).attr('idattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });
    
});


$(".loginwrapper li").click(function(){

  $("#myTabContent div.tab-pane").removeClass('active in');
  $("#myTab li").removeClass('active');

  if($(this).attr('id')=='signinli')
  {
      $("#signin").addClass('active in');
      $("#signintabli").addClass('active');
  }
  else if($(this).attr('id')=='signupli')
  {
    $("#signup").addClass('active in');
    $("#signuptabli").addClass('active');
  }
  

});



function addAttraction(attractionid,cityid)
{
   /*
    var divtoaddid=$("#"+attractionid).closest("div").attr('id');
    var divtoselectedid=$("#listings").find('div').not('.backgroundclr').first().attr('id');
    if(divtoaddid.toString() == divtoselectedid.toString())
    {
        $('#'+divtoaddid).addClass('backgroundclr');
        $("#"+attractionid).removeClass('delete-tab');
        $("#"+attractionid).addClass('add-tab');
        $("#"+attractionid+' i').removeClass('fa fa-plus');
        $("#"+attractionid+' i').addClass('fa fa-trash-o');
        $("#"+attractionid+' i').attr("onclick","deleteAttraction('"+attractionid+"','"+cityid+"')");  
    }
    else
    {
        var div2 = ($('#'+divtoaddid)).detach();
        $("#"+divtoselectedid).before(div2);
        $('#'+divtoaddid).addClass('backgroundclr');
        $("#"+attractionid).removeClass('delete-tab');
        $("#"+attractionid).addClass('add-tab');
        $("#"+attractionid+' i').removeClass('fa fa-plus');
        $("#"+attractionid+' i').addClass('fa fa-trash-o');
        $("#"+attractionid+' i').attr("onclick","deleteAttraction('"+attractionid+"','"+cityid+"')");  
    }
    */

     $.ajax({
        type: 'POST',
        url:baseurl+'alterAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function deleteAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}

function addMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function deleteMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}


$("#ckk").click(function(){
if($("#addNewActivityForm").length)
{
  $("#addNewActivityForm")[0].reset();
}
if($("#addNewActivityMultiForm").length)
{
  $("#addNewActivityMultiForm")[0].reset();
}

 $('#mapModal').modal({
    backdrop: 'static',
    keyboard: false
  })
});

$("#singlemodel").click(function(){
  $("#addNewActivitySingleForm")[0].reset();
 $('#mapModal').modal({
    backdrop: 'static',
    keyboard: false
  })
});

$("#addNewActivityForm").submit(function(){

  if($("#addNewActivityForm")[0].checkValidity())
  {
       $("#btnaddac").css('pointer-events','none');
       $.ajax({
                type:'POST',
                url:baseurl+'addNewActivity',
                data:$("#addNewActivityForm").serialize(),
                beforeSend: function(){
                  $.LoadingOverlay("show");
                },
                complete: function(){
                    setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                },
                success:function(data)
                {
                    $("#addNewActivityForm")[0].reset();
                    $("#exlat").val('');
                    $("#exlong").val('');
                    //$("#citypostid").val('');
                    $("#isall").val('0');
                    $("#bindTab").html(data.body);
                    $("#btnaddac").css('pointer-events','auto');
                    $('#mapModal').modal('hide');
                    $("#btnaddac").hide();
                     
                }

      });
  }
    return false;
});

$("#addNewActivityMultiForm").submit(function(){

  if($("#addNewActivityMultiForm")[0].checkValidity())
  {
       $("#btnaddac").css('pointer-events','none');
       $.ajax({
                type:'POST',
                url:baseurl+'addNewActivityMulti',
                data:$("#addNewActivityMultiForm").serialize(),
                beforeSend: function(){
                  $.LoadingOverlay("show");
                },
                complete: function(){
                    setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                },
                success:function(data)
                {
                    $("#addNewActivityMultiForm")[0].reset();
                    $("#exlat").val('');
                    $("#exlong").val('');
                    //$("#citypostid").val('');
                    $("#isall").val('0');
                    $("#bindTab").html(data.body);
                    $("#btnaddac").css('pointer-events','auto');
                    $('#mapModal').modal('hide');
                    $("#btnaddac").hide();
                     
                }

      });
  }
    return false;
});



$("#addNewActivitySingleForm").submit(function(){

  if($("#addNewActivitySingleForm")[0].checkValidity())
  {
       $("#btnaddac").css('pointer-events','none');
       $.ajax({
                type:'POST',
                url:baseurl+'addNewActivitySingle',
                data:$("#addNewActivitySingleForm").serialize(),
                beforeSend: function(){
                  $.LoadingOverlay("show");
                },
                complete: function(){
                    setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                },
                success:function(data)
                {
                    $("#addNewActivitySingleForm")[0].reset();
                    $("#exlat").val('');
                    $("#exlong").val('');
                    //$("#citypostid").val('');
                    $("#isall").val('0');
                    $("#bindTab").html(data.body);
                    $("#btnaddac").css('pointer-events','auto');
                    $('#mapModal').modal('hide');
                    $("#btnaddac").hide();
                     
                }

      });
  }
    return false;
});




$("#saveActivity").click(function(){
    $.ajax({

            type:'POST',
            url:baseurl+'addNewActivity',
    });

});

$("#showallSingle").click(function(){
  $.ajax({
        url: baseurl+'getAllAttractionsOfSingleCity',
        type: 'POST',
        data: 'id='+$(this).attr('idattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });

});



function addMainAttractionSingle(attractionid,cityid)
{
    $.ajax({
        type:'POST',
        url:baseurl+'alterMainAttractionSingle',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1&ismain=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function deleteMainAttractionSingle(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttractionSingle',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0&ismain=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}

function addInnerAttractionSingle(attractionid,cityid)
{
    $.ajax({
        type:'POST',
        url:baseurl+'alterMainAttractionSingle',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1&ismain=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function deleteInnerAttractionSingle(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttractionSingle',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0&ismain=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}


function getDataForNewCountry(liid,countryid)
{
    $("#"+liid).siblings('li').removeClass('main-li-active');
    $("#"+liid).siblings('li').css('pointer-events','auto');
    $("#"+liid).addClass('main-li-active');
    $("#"+liid).css('pointer-events','none');
    var url = window.location.href;
    var result= url.split('/');
    var Param = result[result.length-1];

    $("#"+liid).siblings('li').removeClass('main-li-active');
    $("#"+liid).addClass('main-li-active');

    $.ajax({
        type: 'POST',
        url:baseurl+'getDataForNewCountry',
        data:'countryid='+countryid+'&key='+encodeURIComponent(Param),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindMainPage").html(data.body);
          }
       
    });
}



/* Multicountry */

function deleteMultiMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addMultiMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}

$("#showmultiall").click(function(){

     $.ajax({
        url: baseurl+'getAllAttractionsOfMultiCity',
        type: 'POST',
        data: 'id='+$(this).attr('idattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });
    
});

function deleteMultiAttraction(attractionid,cityid)
{
   $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=0&flag=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addMultiAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=0&flag=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}


function deleteNewMultiAttraction(attractionid,cityid)
{
   $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=0',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addNewMultiAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=1',
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function removeCity(cityid)
{
   
    var cnf=confirm('Are you sure you want to delete this city ?'); 
    if(cnf)
    {
         $.ajax({
            type: 'POST',
            url:baseurl+'alterCity',
            data:'cityname='+cityid+'&addordelete=0',
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     
}


function addCity(countryid)
{

    $("#cityModal").modal('show');
    $("#countryofcity").val(countryid);
}

$("#addNewCityForm").submit(function(){

    if($("#addNewCityForm")[0].checkValidity())
    {
        var data = $('#addNewCityForm').serializeArray();
        data.push({name: 'addordelete', value: 1});


        $.ajax({
            type: 'POST',
            url:baseurl+'alterCity',
            data:data,
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     $("#cityModal").modal('hide');

    return false;

});

function addCityInMultiCocuntry(countryid)
{

    $("#cityModal").modal('show');
    $("#countryofcity").val(countryid);
}


$("#addNewCityFormMultiCountry").submit(function(){

    if($("#addNewCityFormMultiCountry")[0].checkValidity())
    {
        var data = $('#addNewCityFormMultiCountry').serializeArray();
        data.push({name: 'addordelete', value: 1});

        $.ajax({
            type: 'POST',
            url:baseurl+'alterMultiCountryCity',
            data:data,
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     $("#cityModal").modal('hide');

    return false;

});

function removeCityFromMultiCountry(cityid)
{
    var cnf=confirm('Are you sure you want to delete this city ?'); 
    if(cnf)
    {
         $.ajax({
            type: 'POST',
            url:baseurl+'alterMultiCountryCity',
            data:'cityname='+cityid+'&addordelete=0',
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
}


$("#showLogonForm").click(function(){
    $("#myModal").modal('show');
     $("body").removeAttr('style');

});

$(".openloginform").click(function(){

   $("#myModal").modal('show');
     $("body").removeAttr('style');

});





$("#registerUserForm").submit(function(){

  if($("#registerUserForm")[0].checkValidity())
  {
     $.ajax({
                type:'POST',
                url:baseurl+'signupUser',
                data:$("#registerUserForm").serialize(),
                success:function(data)
                {
                     if(data==1)
                    {
                       window.location.reload();
                    }
                    else
                    {
                       $("#appendregistererrors").html(data);
                    }
                }
      });
  }

  return false;

});


$("#signinForm").submit(function(){

if (grecaptcha.getResponse() == "")
{
  $("#errorcaptcha").html("Please fill up the captcha");
} 
else 
{
    if($("#signinForm")[0].checkValidity())
    {
       $.ajax({
                  type:'POST',
                  url:baseurl+'signinUser',
                  data:$("#signinForm").serialize(),
                  success:function(data)
                  {
                      if(data==1)
                      {
                         window.location.reload();
                      }
                      else
                      {
                         $("#appendsigninerrors").html('Invalid Email/Password Combination.');
                      }
                  }
        });
    }

}

return false;

});


$("#citydropdown").change(function(){

      var currentUrl=window.location.href;
      var param=currentUrl.split('/').pop();

      $.ajax({
            type:'POST',
            url:baseurl+'hotels/getHotelsajax/'+encodeURI(param),
            data:'city_id='+$(this).val()+'&drop=1',
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                $("#bindajax").html(data);
            }
      });

});

$("#searchcitydropdown").change(function() {
    
     var currentUrl=window.location.href;
      var param=currentUrl.split('/').pop();
      var recommendation = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
      $.ajax({
            type:'POST',
            url:baseurl+'hotels/showSearchedCityHotelsajax/'+encodeURI(param),
            data:'cityid='+$(this).val()+'&drop=1&recommendation='+recommendation,
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                $("#bindajax").html(data);
            }
      });

});

$("#multicitydropdown").change(function(){

      var currentUrl=window.location.href;
      var param=currentUrl.split('/').pop();

      $.ajax({
            type:'POST',
            url:baseurl+'hotels/getMultiCountryHotelsajax/'+encodeURI(param),
            data:'ids='+$(this).val()+'&drop=1',
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 4000);
            },
            success:function(data)
            {
                $("#bindajax").html(data);
            }
      });

});

$("#showallSaved").click(function(){

     $.ajax({
        url: baseurl+'getAllAttractionsOfCitySaved',
        type: 'POST',
        data: 'id='+$(this).attr('idattr')+'&iti='+$(this).attr('classattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });
    
});


// Saved

function deleteMainAttractionSaved(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttractionSaved',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0&ismain=1&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function deleteInnerAttractionSaved(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterMainAttractionSaved',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=0&ismain=0&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}

function addMainAttractionSaved(attractionid,cityid)
{
    $.ajax({
        type:'POST',
        url:baseurl+'alterMainAttractionSaved',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1&ismain=1&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addAllAttractionSaved(attractionid,cityid)
{
    $.ajax({
        type:'POST',
        url:baseurl+'alterMainAttractionSaved',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&flag=1&ismain=0&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

$("#addNewSavedCityForm").submit(function(){

    if($("#addNewSavedCityForm")[0].checkValidity())
    {
        var data = $('#addNewSavedCityForm').serializeArray();
        data.push({name: 'addordelete', value: 1});


        $.ajax({
            type: 'POST',
            url:baseurl+'alterSavedCity',
            data:data,
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     $("#cityModal").modal('hide');

    return false;

});

function removeSavedCity(cityid)
{
   
    var cnf=confirm('Are you sure you want to delete this city ?'); 
    if(cnf)
    {
         $.ajax({
            type: 'POST',
            url:baseurl+'alterSavedCity',
            data:'cityname='+cityid+'&addordelete=0',
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     
}

function deleteSavedMultiMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterSavedMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=0&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addSavedMultiMainAttraction(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterSavedMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=1&flag=1&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}





function deleteSavedMultiMainAttractionAll(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterSavedMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=0&flag=0&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
}

function addSavedMultiMainAttractionAll(attractionid,cityid)
{
    $.ajax({
        type: 'POST',
        url:baseurl+'alterSavedMultiAttraction',
        data:'attractionid='+attractionid+'&cityid='+cityid+'&ismain=0&flag=1&iti='+$('#iti').val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });

}

$("#addNewCityFormSavedMultiCountry").submit(function(){

    if($("#addNewCityFormSavedMultiCountry")[0].checkValidity())
    {
        var iti=$("#iti").val();
        var data = $('#addNewCityFormSavedMultiCountry').serializeArray();
        data.push({name: 'addordelete', value: 1});
        data.push({name: 'iti', value: iti});

        $.ajax({
            type: 'POST',
            url:baseurl+'alterSavedMultiCountryCity',
            data:data,
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
     $("#cityModal").modal('hide');

    return false;

});

function getDataForNewCountryMultiSaved(liid,countryid)
{
    $("#"+liid).siblings('li').removeClass('main-li-active');
    $("#"+liid).siblings('li').css('pointer-events','auto');
    $("#"+liid).addClass('main-li-active');
    $("#"+liid).css('pointer-events','none');
    var url = window.location.href;
    var result= url.split('/');
    var Param = result[result.length-1];

    $("#"+liid).siblings('li').removeClass('main-li-active');
    $("#"+liid).addClass('main-li-active');

    $.ajax({
        type: 'POST',
        url:baseurl+'getDataForNewCountryMultiSaved',
        data:'countryid='+countryid+'&iti='+$("#iti").val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindMainPage").html(data.body);
          }
       
    });
}

function removeCityFromMultiCountrySaved(cityid)
{
    var cnf=confirm('Are you sure you want to delete this city ?'); 
    if(cnf)
    {
         $.ajax({
            type: 'POST',
            url:baseurl+'alterSavedMultiCountryCity',
            data:'cityname='+cityid+'&addordelete=0'+'&iti='+$("#iti").val(),
            beforeSend: function()
            {
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
                if(data==2)
                {
                  alert('something wrong');
                }
                else
                {
                  $("#bindMainPage").html(data.body); 
                }
                
            }
        });   
    }
}

$("#showmultiallSaved").click(function(){

     $.ajax({
        url: baseurl+'getAllAttractionsOfMultiCitySaved',
        type: 'POST',
        data: 'id='+$(this).attr('idattr')+'&iti='+$("#iti").val(),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });
    
});

$("#forgotpasswordform").submit(function(){
	
		$.ajax({
				type:'POST',
				url:baseurl+'Home/forgotPassword',
				data:$("#forgotpasswordform").serialize(),
				success:function(data)
				{
					if(data==1)
					 {
						$("#forgotpasswordform")[0].reset();
						$("#forgotpassorderrdisnonde").hide();
						$("#forgotpassordsucdisnonde").show();
					 }
					 else
					 {
						 $("#forgotpassordsucdisnonde").hide();
						 $("#forgotpassorderrdisnonde").show();
				
					 }
				}	
		
		});
		
		return false;
});


$("#attractionsearchform").submit(function(){

    if($("#attractionsearchform")[0].checkValidity())
    {
      $.ajax({
          type:'POST',
          url:baseurl+'seachAttractionsFromGYG',
          data:$("#attractionsearchform").serialize(),
          beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
             $("#scriptsearchedattractions").hide();
             $("#bindsearchedattractions").show();
             $("#bindsearchedattractions").html(data.body);
          }
        });

    }
    return false;


});

$(".addcity").click(function(){

    var cityid=$(this).attr('id');
    $.ajax({
          type:'POST',
          url:baseurl+'addExtraCity',
          data:'cityid='+cityid,
          beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
            $("#bindMainPage").html(data.body);
          }
        });

})

function openpopup()
{
   $("body").removeAttr('style');
  //$(".modal-sm").css("display","none");
  $("#forgotpasswordmodal").modal("show");
  $("#myModal").modal('hide');
  $("#forgotpassorderrdisnonde").hide();
  $("#forgotpassordsucdisnonde").hide();
  
}
function back(){

  $("#forgotpasswordmodal").modal("hide");
  //alert('hi');
  $(".modal-sm").css("display","block");
}
$("#forgotclose,#forgotback").click(function(){ 
  $("body").removeAttr('style');
  $("#myModal").modal('show');
  $("#forgotpassordsucdisnonde").css("display","none");
  $("#forgotpassorderrdisnonde").css("display","none");
});

$("#forgotcancel").click(function(){
  $("#myModal").modal('hide');
  $("#forgotpasswordmodal").modal('hide');
});

$(".addsearchcity").click(function(){
    var cityid=$(this).attr('id');
    $.ajax({
          type:'POST',
          url:baseurl+'addExtraCity',
          data:'cityid='+cityid,
          beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
            $("#bindMainPage").html(data.body);
          }
        });
});

function removeExtraCity(cityid)
{
    var cnf=confirm('Are you sure you want to delete this city ?'); 
    if(cnf)
    {
      $.ajax({
            type:'POST',
            url:baseurl+'removeExtraCity',
            data:'cityid='+cityid,
            beforeSend: function(){
               $.LoadingOverlay("show");
            },
            complete: function(){
                setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
            },
            success:function(data)
            {
              $("#bindMainPage").html(data.body);
            }
      });
    } 
}

/*
$("#refreshcapcha").click(function(){
   $.get(baseurl+'refresh', function(data){
              $('#captImg').html(data);
    });
});
*/



