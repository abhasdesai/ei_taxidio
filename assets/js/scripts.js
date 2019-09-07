function getData(t, e) {
  "" != t && ($(".loader").fadeIn(), $.ajax({
    type: "POST",
    url: baseurl + "getData",
    data: "id=" + t + "&token=" + e,
    success: function(t) {
      $("#bindData").html(t.body), $(".loader").fadeOut()
    }
  }))
}
function addAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function deleteAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addNote(attractionid, city_id) 
{
  $('#note_attraction_id').val(attractionid);
  $('#note_city_id').val(city_id);
   $.ajax({
    type: "POST",
    url: baseurl + "getNote",
    data: "attractionid=" + attractionid + "&city_id=" + city_id,
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
       if(t!='')
      {
        var wrapper = $('.adddeletesection');
        $(wrapper).html('<button class="link-button" id="deletenote" onclick="delete_note();" name="delete" type="button">Delete</button>'); 
        $("#note").val(t)
      }
      
    }
  })
  $("#noteModal").modal();
  //return flase;
 
}
function deleteMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addMainAttractionSingle(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingle",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&ismain=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function deleteMainAttractionSingle(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingle",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addInnerAttractionSingle(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingle",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&ismain=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function deleteInnerAttractionSingle(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingle",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addMainAttractionSingleSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingleSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&ismain=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function deleteMainAttractionSingleSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingleSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addInnerAttractionSingleSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingleSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&ismain=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function deleteInnerAttractionSingleSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSingleSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function getDataForNewCountry(t, e,city_name) {
  
  $("a#viator_city_name").attr("href", "https://www.partner.viator.com/en/67434/search/"+city_name);
   $("#" + t).siblings("li").removeClass("main-li-active"), $("#" + t).siblings("li").css("pointer-events", "auto"), $("#" + t).addClass("main-li-active"), $("#" + t).css("pointer-events", "none");
  var i = window.location.href.split("/"),
    a = i[i.length - 1];
  $("#" + t).siblings("li").removeClass("main-li-active"), $("#" + t).addClass("main-li-active"), $.ajax({
    type: "POST",
    url: baseurl + "getDataForNewCountry",
    data: "countryid=" + e + "&key=" + encodeURIComponent(a) + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindMainPage").html(t.body)
    }
  })
}
function deleteMultiMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addMultiMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function deleteMultiAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addMultiAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function deleteNewMultiAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function addNewMultiAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=1&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body)
    }
  })
}
function removeCity(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "alterCity",
    data: "cityname=" + t + "&addordelete=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
    }
  })
}
function addCityInMultiCountry(t) {
  $("#cityModal").modal("show"), $("#countryofcity").val(t)
}
function removeCityFromMultiCountry(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "alterMultiCountryCity",
    data: "cityname=" + t + "&addordelete=0&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
    }
  })
}
function removeSavedCity(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "alterSavedCity",
    data: "cityname=" + t + "&addordelete=0",
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
    }
  })
}
function deleteSavedMultiMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterSavedMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addSavedMultiMainAttraction(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterSavedMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=1&flag=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function deleteSavedMultiMainAttractionAll(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterSavedMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addSavedMultiMainAttractionAll(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterSavedMultiAttraction",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function getDataForNewCountryMultiSaved(t, e) {
  $("#" + t).siblings("li").removeClass("main-li-active"), $("#" + t).siblings("li").css("pointer-events", "auto"), $("#" + t).addClass("main-li-active"), $("#" + t).css("pointer-events", "none");
  var i = window.location.href.split("/");
  i[i.length - 1], $("#" + t).siblings("li").removeClass("main-li-active"), $("#" + t).addClass("main-li-active"), $.ajax({
    type: "POST",
    url: baseurl + "getDataForNewCountryMultiSaved",
    data: "countryid=" + e + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindMainPage").html(t.body)
    }
  })
}
function getNewCountryDataFromitinerary(id,t)
{
  $("#" + t).siblings("li").css("pointer-events", "auto");
  $("#" + t).siblings("li").removeClass("main-li-active");
  $("#" + t).addClass("main-li-active");
  $("#" + t).css("pointer-events", "none");
  $.ajax({
           type:'POST',
           url: baseurl + 'getNewCountryDataFromitinerary',
           data:'countryid='+id+'&iti='+ $("#iti").val(),
           beforeSend: function(){
              $.LoadingOverlay("show");
           },
           complete: function(){
               setTimeout(function(){  $.LoadingOverlay("hide", !0)}, 3e3)
           },
           success:function(t)
           {
              $("#bindMainPage").html(t.body);
           }
      });
}
function removeCityFromMultiCountrySaved(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "alterSavedMultiCountryCity",
    data: "cityname=" + t + "&addordelete=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
    }
  })
}
function removeExtraCity(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "removeExtraCity",
    data: "cityid=" + t + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindMainPage").html(t.body)
    }
  })
}
function removeExtraCityFromSave(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "removeExtraCityFromSave",
    data: "cityid=" + t + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindMainPage").html(t.body)
    }
  })
}
function deleteMainAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function deleteInnerAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addMainAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=1&ismain=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=1&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function deleteAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&ismain=0&flag=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function addAllAttractionSaved(t, e) {
  $.ajax({
    type: "POST",
    url: baseurl + "alterMainAttractionSaved",
    data: "attractionid=" + t + "&cityid=" + e + "&flag=0&ismain=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body)
    }
  })
}
function removeSavedSingleCountryCity(t) {
  confirm("Are you sure you want to delete this city ?") && $.ajax({
    type: "POST",
    url: baseurl + "alterSavedSingleCountryCity",
    data: "cityname=" + t + "&addordelete=0&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
    }
  })
}
function addCity(t) {
  $("#cityModal").modal("show"), $("#countryofcity").val(t)
}
function IsJsonString(t) {
  try {
    JSON.parse(t)
  } catch (t) {
    return !1
  }
  return !0
}
function deleteComment(t) {
  confirm("Are you sure you want to delete ?") && $.ajax({
    type: "POST",
    url: baseurl + "deleteComment",
    data: "id=" + t,
    success: function(e) {
      res = IsJsonString(e), res ? (JSON.parse(e), window.location = baseurl + "404") : $("#maincomment" + t).hide()
    }
  })
}
function editComment(t) {
  $("#cmtbx" + t).hide(), $("#h" + t).show()
}
function cancelEdit(t) {
  $("#h" + t).hide(), $("#cmtbx" + t).show()
}
function updateComment(t) {
  $.ajax({
    type: "POST",
    url: baseurl + "editComment",
    data: "id=" + t + "&comment=" + $("#h" + t).find("textarea").val(),
    success: function(e) {
      res = IsJsonString(e), res ? (JSON.parse(e), window.location = baseurl + "404") : ($("#h" + t).hide(), $("#cmtbx" + t).show(), $("#cmtbx" + t).html($("#h" + t).find("textarea").val()))
    }
  })
}
function openPopUpForLogin(t) {
  if (res = IsJsonString(t), res) {
    var e = JSON.parse(t);
    return 0 == e.login ? ($("#signintabli").addClass("active"), $("#signuptabli").removeClass("active"), $("#signup").removeClass("active in"), $("#signin").addClass("active in"), $("#myModal").modal({
      backdrop: "static",
      keyboard: !0
    })) : 1 == e.tripdelete && (window.location = "<?php echo site_url('trips') ?>"), !1
  }
  return !0
}
var baseurl = $("#siteurl").val();
function delete_note(){
  
    var t = $("#note_attraction_id").val();
    var city = $("#note_city_id").val();
    var note = $("#note").val();
    $.ajax({
    type: "POST",
    url: baseurl + "delNote",
    data: "attraction_id=" + t + "&city_id=" + city +"&note="+note,
    success: function(e) {
      location.reload();
    }
    })


 
}

$(document).on("hidden.bs.modal", function(t) {
  $(".modal:visible").length && $("body").addClass("modal-open")
}), $("#showall").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfCity",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $(".loginwrapper li").click(function() {
  $("#myTabContent div.tab-pane").removeClass("active in"), $("#myTab li").removeClass("active"), "signinli" == $(this).attr("id") ? ($("#signin").addClass("active in"), $("#signintabli").addClass("active")) : "signupli" == $(this).attr("id") && ($("#signup").addClass("active in"), $("#signuptabli").addClass("active"))
}), $("#ckk").click(function() {
  $("#addNewActivityForm").length && $("#addNewActivityForm")[0].reset(), $("#addNewActivityMultiForm").length && $("#addNewActivityMultiForm")[0].reset(), $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  })
}), $("#singlemodel").click(function() {
  $("#addNewActivitySingleForm")[0].reset(), $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  })
}), $("#singlemodelsaved").click(function() {
  $("#addNewActivitySearchedFormSaved")[0].reset(), $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  })
}), $("#addNewActivityForm").submit(function() {
  if ($("#addNewActivityForm")[0].checkValidity()) {
    $("#btnaddac").css("pointer-events", "none");
    var t = $("#uid").val(),
      e = $("#addNewActivityForm").serializeArray();
    e.push({
      name: "uniqueid",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewActivity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        $("#addNewActivityForm")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val("0"), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  }
  return !1
}),$("#btnaddnote").click(function() {
  
    /* e.preventDefault();
     return false;*/

  
    var t = $("#note_attraction_id").val();
    var city = $("#note_city_id").val(),
    e = $("#addNewNoteForm").serializeArray();
    e.push({
      name: "attraction_id",
      value: t
    }),e.push({
      name: "city_id",
      value: city
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewNote",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        location.reload();
        //$("#addNewActivityForm")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val("0"), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  
}), $("#addNewActivityMultiForm").submit(function() {
  if ($("#addNewActivityMultiForm")[0].checkValidity()) {
    $("#btnaddac").css("pointer-events", "none");
    var t = $("#uid").val(),
      e = $("#addNewActivityMultiForm").serializeArray();
    e.push({
      name: "uniqueid",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewActivityMulti",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        $("#addNewActivityMultiForm")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val("0"), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  }
  return !1
}), $("#addNewActivitySingleForm").submit(function() {
  if ($("#addNewActivitySingleForm")[0].checkValidity()) {
    $("#btnaddac").css("pointer-events", "none");
    var t = $("#uid").val(),
      e = $("#addNewActivitySingleForm").serializeArray();
    e.push({
      name: "uniqueid",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewActivitySingle",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        $("#addNewActivitySingleForm")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val("0"), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  }
  return !1
}), $("#saveActivity").click(function() {
  $.ajax({
    type: "POST",
    url: baseurl + "addNewActivity"
  })
}), $("#showallSingle").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfSingleCity",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#showallSearchedSaved").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfSingleCitySaved",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#showmultiall").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfMultiCity",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#addNewCityForm").submit(function() {
  if ($("#addNewCityForm")[0].checkValidity()) {
    var t = $("#uid").val(),
      e = $("#addNewCityForm").serializeArray();
    e.push({
      name: "addordelete",
      value: 1
    }), e.push({
      name: "uniqueid",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "alterCity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
      }
    })
  }
  return $("#cityModal").modal("hide"), !1
}), $("#addNewCityFormSaved").submit(function() {
  if ($("#addNewCityFormSaved")[0].checkValidity()) {
    var t = $("#iti").val(),
      e = $("#addNewCityFormSaved").serializeArray();
    e.push({
      name: "addordelete",
      value: 1
    }), e.push({
      name: "iti",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "alterSavedSingleCountryCity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
      }
    })
  }
  return $("#cityModal").modal("hide"), !1
}), $("#addNewCityFormMultiCountry").submit(function() {
  if ($("#addNewCityFormMultiCountry")[0].checkValidity()) {
    var t = $("#uid").val(),
      e = $("#addNewCityFormMultiCountry").serializeArray();
    e.push({
      name: "addordelete",
      value: 1
    }), e.push({
      name: "uniqueid",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "alterMultiCountryCity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
      }
    })
  }
  return $("#cityModal").modal("hide"), !1
}), $(document).on("click", "#showLogonForm", function() {
  $("#myModal").modal("show"), $("#signintabli").addClass("active"), $("#signuptabli").removeClass("active"), $("#signup").removeClass("active in"), $("#signin").addClass("active in"), $("#myModal").modal("show")
}), $(".openloginform").click(function() {
  $("#myModal").modal("show"), $("body").removeAttr("style")
}), $("#showLoginForForum").click(function() {
  $("#signintabli").addClass("active"), $("#signuptabli").removeClass("active"), $("#signup").removeClass("active in"), $("#signin").addClass("active in"), $("#myModal").modal("show")
}), $("#showRegisterForForum").click(function() {
  $("#signintabli").removeClass("active"), $("#signuptabli").addClass("active"), $("#signup").addClass("active in"), $("#signin").removeClass("active in"), $("#myModal").modal("show")
}), $(".showLoginForForum_cr").click(function() {
  $("#signintabli").addClass("active"), $("#signuptabli").removeClass("active"), $("#signup").removeClass("active in"), $("#signin").addClass("active in"), $("#myModal").modal("show")
}), $(".showRegisterForForum_cr").click(function() {
  $("#signintabli").removeClass("active"), $("#signuptabli").addClass("active"), $("#signup").addClass("active in"), $("#signin").removeClass("active in"), $("#myModal").modal("show")
}), $("#registerUserForm").submit(function() {
  return $("#registerUserForm").valid() && $.ajax({
    type: "POST",
    url: baseurl + "signupUser",
    data: $("#registerUserForm").serialize(),
    success: function(t) {
      1 == t ? window.location.reload() : $("#appendregistererrors").html(t)
    }
  }), !1
}), $("#signinForm").submit(function() {
  return "" == $("#g-recaptcha-response").val() ? ($("#signinForm").valid(), $("#errorcaptcha").html("We love the idea of robots. But we need you to be a human to access this website.")) : $("#signinForm").valid() && $.ajax({
    type: "POST",
    url: baseurl + "signinUser",
    data: $("#signinForm").serialize(),
    success: function(t) {
      1 == t ? window.location.reload() : $("#appendsigninerrors").html("Your username or password is incorrect. Please re-enter.")
    }
  }), !1
}), $("#citydropdownSaved").change(function() {
  var t = window.location.href.split("/").pop();
  $.ajax({
    type: "POST",
    url: baseurl + "Triphotels/hotelListsForSavedCountry-ajax/" + encodeURI(t),
    data: "city_id=" + $(this).val() + "&drop=1",
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindajax").html(t)
    }
  })
}), $("#citydropdown").change(function() {
  var t = window.location.href.split("/").pop();
  $.ajax({
    type: "POST",
    url: baseurl + "hotels/getHotelsajax/" + encodeURI(t),
    data: "city_id=" + $(this).val() + "&drop=1",
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindajax").html(t)
    }
  })
}), $("#searchcitydropdown").change(function() {
  var t = window.location.href,
    e = t.split("/").pop(),
    i = t.substring(t.lastIndexOf("/") + 1);
    $('#connm').html($("#searchcitydropdown option:selected").text());
  $.ajax({
    type: "POST",
    url: baseurl + "hotels/showSearchedCityHotelsajax/" + encodeURI(e),
    data: "cityid=" + $(this).val() + "&drop=1&recommendation=" + i,
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindajax").html(t)
    }
  })
}), $("#searchcitydropdownSaved").change(function() {
  var t = window.location.href,
    e = t.split("/").pop(),
    i = t.substring(t.lastIndexOf("/") + 1);
    $('#connm').html($("#searchcitydropdownSaved option:selected").text());
  $.ajax({
    type: "POST",
    url: baseurl + "triphotels/hotelListsForsearchedCity-ajax/" + encodeURI(e),
    data: "cityid=" + $(this).val() + "&drop=1&recommendation=" + i,
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindajax").html(t)
    }
  })
}), $("#multicitydropdownSaved").change(function() {
  var t = window.location.href.split("/").pop();
  $.ajax({
    type: "POST",
    url: baseurl + "triphotels/hotelListsForSavedMultiCountry-ajax/" + encodeURI(t),
    data: "ids=" + $(this).val() + "&drop=1&iti=" + $("#dropuniqueid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 4e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindajax").html(t)
    }
  })
}), $("#multicitydropdown").change(function() {
  var t = window.location.href.split("/").pop();
  $.ajax({
    type: "POST",
    url: baseurl + "hotels/getMultiCountryHotelsajax/" + encodeURI(t),
    data: "ids=" + $(this).val() + "&drop=1&uniqueid=" + $("#dropuniqueid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 4e3)
    },
    success: function(t) {
      $("#bindajax").html(t)
    }
  })
}), $("#showallSaved").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfCitySaved",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&iti=" + $(this).attr("classattr"),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#addNewSavedCityForm").submit(function() {
  if ($("#addNewSavedCityForm")[0].checkValidity()) {
    var t = $("#addNewSavedCityForm").serializeArray();
    t.push({
      name: "addordelete",
      value: 1
    }), $.ajax({
      type: "POST",
      url: baseurl + "alterSavedCity",
      data: t,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
      }
    })
  }
  return $("#cityModal").modal("hide"), !1
}), $("#addNewCityFormSavedMultiCountry").submit(function() {
  if ($("#addNewCityFormSavedMultiCountry")[0].checkValidity()) {
    var t = $("#iti").val(),
      e = $("#addNewCityFormSavedMultiCountry").serializeArray();
    e.push({
      name: "addordelete",
      value: 1
    }), e.push({
      name: "iti",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "alterSavedMultiCountryCity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindMainPage").html(t.body)
      }
    })
  }
  return $("#cityModal").modal("hide"), !1
}), $("#showmultiallSaved").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfMultiCitySaved",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#forgotpasswordform").submit(function() {
  return $.ajax({
    type: "POST",
    url: baseurl + "forgotPassword",
    data: $("#forgotpasswordform").serialize(),
    success: function(t) {
      1 == t ? ($("#forgotpasswordform")[0].reset(), $("#forgotpassorderrdisnonde").hide(), $("#forgotpassordsucdisnonde").show()) : ($("#forgotpassordsucdisnonde").hide(), $("#forgotpassorderrdisnonde").show())
    }
  }), !1
}), $("#attractionsearchform").submit(function() {
  return $("#attractionsearchform")[0].checkValidity() && $.ajax({
    type: "POST",
    url: baseurl + "searchAttractionsFromGYG",
    data: $("#attractionsearchform").serialize(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#scriptsearchedattractions").hide(), $("#bindsearchedattractions").show(), $("#bindsearchedattractions").html(t.body), $("html, body").animate({
        scrollTop: $(".countryviewview").offset().top
      }, 2e3)
    }
  }), !1
}), $(".addcity").click(function() {
  var t = $(this).attr("id");
  $.ajax({
    type: "POST",
    url: baseurl + "addExtraCity",
    data: "cityid=" + t,
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindMainPage").html(t.body)
    }
  })
}), $("#forgotcancel").click(function() {
  $("#forgotpassordsucdisnonde").hide(), $("#forgotpassorderrdisnonde").hide(), $("#forgotpasswordmodal").modal("toggle")
}), $(".addsearchcity").click(function() {
  var t = $(this).attr("id");
  $.ajax({
    type: "POST",
    url: baseurl + "addExtraCity",
    data: "cityid=" + t + "&uniqueid=" + $("#uid").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      $("#bindMainPage").html(t.body)
    }
  })
}), $(".addsearchcitysaved").click(function() {
  var t = $(this).attr("id");
  $.ajax({
    type: "POST",
    url: baseurl + "addExtraCityInSaved",
    data: "cityid=" + t + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindMainPage").html(t.body)
    }
  })
}), $("#buttonsearch").click(function() {
  $("#recformsubmit").validationEngine("validate") && $.ajax({
    type: "GET",
    url: baseurl + "searchformsubmit",
    data: $("#recformsubmit").serialize(),
    beforeSend: function() {
      $(".loader").fadeIn()
    },
    complete: function() {},
    success: function(t) {
      var e = $.parseJSON(t);
      1 == e.status && 2 == e.staytocurrenturl ? $("#recformsubmit").submit() : e.status && 1 == e.staytocurrenturl && ($("#msgsearch").html(e.msg), $("#searchModal").modal("show"), $("#searchhref").attr("href", e.url), $(".loader").fadeOut())
    }
  })
}), $("#recommendationbtn").click(function() {
  $("#recommendationform").validationEngine("validate") && $.ajax({
    type: "POST",
    url: baseurl + "recommendedformsubmit",
    data: "token=" + $("#rectoken").val(),
    success: function(t) {
      $("#recommendationform").submit()
    }
  })
}), $("#showallSingleSaved").click(function() {
  $.ajax({
    url: baseurl + "getAllAttractionsOfCitySaved",
    type: "POST",
    data: "id=" + $(this).attr("idattr") + "&iti=" + $("#iti").val(),
    beforeSend: function() {
      $.LoadingOverlay("show")
    },
    complete: function() {
      setTimeout(function() {
        $.LoadingOverlay("hide", !0)
      }, 3e3)
    },
    success: function(t) {
      openPopUpForLogin(t), $("#bindTab").html(t.body), $("#isall").val(1)
    }
  })
}), $("#addUserActivityToSingleCountrySaved").submit(function() {
  if ($("#addUserActivityToSingleCountrySaved")[0].checkValidity()) {
    var t = $("#iti").val(),
      e = $("#addUserActivityToSingleCountrySaved").serializeArray();
    e.push({
      name: "addordelete",
      value: 1
    }), e.push({
      name: "iti",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addUserActivityToSingleCountrySaved",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), 2 == t ? alert("something wrong") : $("#bindTab").html(t.body)
      }
    })
  }
  return $("#mapModal").modal("hide"), !1
}), $("#addNewActivitySearchedFormSaved").submit(function() {
  if ($("#addNewActivitySearchedFormSaved")[0].checkValidity()) {
    $("#btnaddac").css("pointer-events", "none");
    var t = $("#iti").val(),
      e = $("#addNewActivitySearchedFormSaved").serializeArray();
    e.push({
      name: "iti",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewActivityToSavedSearchedCity",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), $("#addNewActivitySearchedFormSaved")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val("0"), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  }
  return !1
}), $("#ckkSingleSaved").click(function() {
  $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  }), $("#isall").val(0), $("#addUserActivityToSingleCountrySaved")[0].reset()
}), $("#ckkMultiSaved").click(function() {
  $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  }), $("#isall").val(0), $("#addNewActivitySavedMultiForm")[0].reset()
}), $("#ckkMultiSaved").click(function() {
  $("#mapModal").modal({
    backdrop: "static",
    keyboard: !1
  }), $("#isall").val(0), $("#addNewActivitySavedMultiForm")[0].reset()
}), $("#addNewActivitySavedMultiForm").submit(function() {
  if ($("#addNewActivitySavedMultiForm")[0].checkValidity()) {
    $("#btnaddac").css("pointer-events", "none");
    var t = $("#iti").val(),
      e = $("#addNewActivitySavedMultiForm").serializeArray();
    e.push({
      name: "iti",
      value: t
    }), $.ajax({
      type: "POST",
      url: baseurl + "addNewActivitySavedMultiCountry",
      data: e,
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      complete: function() {
        setTimeout(function() {
          $.LoadingOverlay("hide", !0)
        }, 3e3)
      },
      success: function(t) {
        openPopUpForLogin(t), $("#addNewActivitySavedMultiForm")[0].reset(), $("#exlat").val(""), $("#exlong").val(""), $("#isall").val(0), $("#bindTab").html(t.body), $("#btnaddac").css("pointer-events", "auto"), $("#mapModal").modal("hide"), $("#btnaddac").hide()
      }
    })
  }
  return !1
}), $("#subscriberform").submit(function() {
  return $("#msgsub").hide(), $("#subscriberform")[0].checkValidity() && $.ajax({
    type: "POST",
    url: baseurl + "addSubscriber",
    data: $(this).serialize(),
    success: function(t) {
      $("#msgsub").show(), 1 == t ? ($("#subscriberform")[0].reset(), $("#msgsub").removeClass("suberror"), $("#msgsub").addClass("subsuccess"), $("#msgsub").html("Thank you for subscribing!")) : 3 == t ? ($("#msgsub").removeClass("subsuccess"), $("#msgsub").addClass("suberror"), $("#msgsub").html("You have already subscribed to us with the same email address.")) : ($("#msgsub").removeClass("subsuccess"), $("#msgsub").addClass("suberror"), $("#msgsub").html("Please enter valid email."))
    }
  }), !1
}), $("#postcomment").submit(function() {
  if ($("#postcomment")[0].checkValidity() && "" != $("#usercomment").val()) {
    $("#postcommentbtn").hide();
    var t = window.location.href.split("/"),
      e = t[t.length - 1],
      i = $("#postcomment").serializeArray();
    i.push({
      name: "iti",
      value: e
    }), $.ajax({
      type: "POST",
      url: baseurl + "postComment",
      data: i,
      beforeSend: function()
      {
        $(".loader").show();
      },
      complete: function() {
        $(".loader").hide();
      },
      error: function(data) {
        $(".loader").hide();
        $("#postcommentbtn").show();
     },
      success: function(t) {
        $("#postcommentbtn").show();
        res = IsJsonString(t), res ? (JSON.parse(t), window.location = baseurl + "404") : ($("#postcomment")[0].reset(), $("#comment").html(t.body))
      }
    })
  }
  return !1
}), $("#formLogin").submit(function() {
  return "" != $("#g-recaptcha-response-1").val() || ($("#errorcaptchafrm").html("We love the idea of robots. But we need you to be a human to access this website."), !1)
}), $("#googlelogin").click(function() {
  return $.ajax({
    type: "POST",
    url: baseurl + "rememberUrl",
    data: "url=" + encodeURIComponent(window.location.href.split("?")[0].toString() + "" + window.location.search.toString()),
    async: !1,
    success: function(t) {
      window.location = $("#googlelogin").attr("href")
    }
  }), !1
}), $("#facebooklogin").click(function() {
  return $.ajax({
    type: "POST",
    url: baseurl + "rememberUrl",
    data: "url=" + encodeURIComponent(window.location.href.split("?")[0].toString() + "" + window.location.search.toString()),
    async: !1,
    success: function(t) {
      window.location = $("#facebooklogin").attr("href")
    }
  }), !1
}), $("#searchedit").click(function() {
  $("#searchModal").modal("hide")
}), $(".js-accordionTrigger").length && function() {
  var t, e, i = document.querySelectorAll(".js-accordionTrigger"),
    a = "ontouchstart" in window,
    n = "pointerdown" in window;
  skipClickDelay = function(t) {
    t.preventDefault(), t.target.click()
  }, setAriaAttr = function(t, e, i) {
    t.setAttribute(e, i)
  }, t = function(t, e, i) {
    switch (i) {
      case "true":
        setAriaAttr(t, "aria-expanded", "true"), setAriaAttr(e, "aria-hidden", "false");
        break;
      case "false":
        setAriaAttr(t, "aria-expanded", "false"), setAriaAttr(e, "aria-hidden", "true")
    }
  }, e = function(e) {
    e.preventDefault();
    var i = e.target.parentNode.nextElementSibling,
      a = e.target;
    i.classList.contains("is-collapsed") ? t(a, i, "true") : t(a, i, "false"), a.classList.toggle("is-collapsed"), a.classList.toggle("is-expanded"), i.classList.toggle("is-collapsed"), i.classList.toggle("is-expanded"), i.classList.toggle("animateIn")
  };
  for (var o = 0, d = i.length; d > o; o++) a && i[o].addEventListener("touchstart", skipClickDelay, !1), n && i[o].addEventListener("pointerdown", skipClickDelay, !1), i[o].addEventListener("click", e, !1)
}(), $("#forgotpasswordmodal").on("shown.bs.modal", function() {
  $("#forgotpassorderrdisnonde").hide(), $("#forgotpasswordform")[0].reset()
}), $(".team-info").click(function() {
  $(".team-info").not(this).css("opacity", 0), 0 == $(this).css("opacity") ? $(this).css("opacity", 1) : $(this).css("opacity", 0)
});


function deleteQuestion(id)
{
    if(id!='')
    {
       var cnf=confirm('Are you sure you want to delete ?');
       if(cnf)
       {
            $.ajax({
            type:'POST',
            url:baseurl+'deleteQuestion',
            data:'id='+id,
            success:function(data)
            {
              var res = IsJsonString(data);
              if(res)
              {
                 var obj=JSON.parse(data);
                if(obj.status=='fail')
                {
                  window.location=baseurl+'404';
                }
              }
              else
              {
                 $("#q"+id).hide();
                 $("#q"+id).html('');
              }
            }
         });
       }

    }

}

$("#browsebycountry").change(function(){

        $.ajax({
            type:'POST',
            url:baseurl+'browse-itinerary',
            data:'country_id='+$(this).val(),
            success:function(data)
            {
              $("#binditinerary").html(data);
            },
            beforeSend: function()
            {
              $(".loader").show();
            },
            complete: function() {
              $(".loader").hide();
            },
            error: function (request, status, error) {
              $(".loader").hide();
            }
        });

});

$("#browsebycontinent").click(function(){

        $.ajax({
            type:'POST',
            url:baseurl+'browse-destinations',
            data:'continent_id='+$('#continent_id').val(),
            success:function(data)
            {
              $("#binddestinations").html(data);
            },
            beforeSend: function()
            {
              $(".loader").show();
            },
            complete: function() {
              $(".loader").hide();
            },
            error: function (request, status, error) {
              $(".loader").hide();
            }
        });

});

$("#browsebycountrydestination").click(function(){
        $.ajax({
            type:'POST',
            url:baseurl+'browse-destinations',
            data:'country_name='+$('#countryname').val(),
            success:function(data)
            {
              $("#binddestinations").html(data);
            },
            beforeSend: function()
            {
              $(".loader").show();
            },
            complete: function() {
              $(".loader").hide();
            },
            error: function (request, status, error) {
              $(".loader").hide();
            }
        });

});

$("#ratingform").submit(function(){

    $.ajax({
        type:'POST',
        url:baseurl+'store-rating',
        data:$(this).serialize(),
        success:function(data)
        {
            $("#ratingmsg").show();
            $("#ratingmsg").html('Thank you for your rating.')
        },
        beforeSend: function()
        {
          $(".loader").show();
        },
        complete: function() {
          $(".loader").hide();
        },
        error: function (request, status, error) {
          $(".loader").hide();
        }

    });

    return false;

});
