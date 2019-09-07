var baseurl = $("#siteurl").val();

function getData(id, token) {
    if (id != '') {
        $.ajax({
            type: 'POST',
            url: baseurl + 'getData',
            data: 'id=' + id + '&token=' + token,
            success: function(data) {
                $("#bindData").html(data.body);
            }
        })
    }
}
$("#showall").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfCity',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});
$(".loginwrapper li").click(function() {
    $("#myTabContent div.tab-pane").removeClass('active in');
    $("#myTab li").removeClass('active');
    if ($(this).attr('id') == 'signinli') {
        $("#signin").addClass('active in');
        $("#signintabli").addClass('active');
    } else if ($(this).attr('id') == 'signupli') {
        $("#signup").addClass('active in');
        $("#signuptabli").addClass('active');
    }
});

function addAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}
$("#ckk").click(function() {
    if ($("#addNewActivityForm").length) {
        $("#addNewActivityForm")[0].reset();
    }
    if ($("#addNewActivityMultiForm").length) {
        $("#addNewActivityMultiForm")[0].reset();
    }
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    })
});
$("#singlemodel").click(function() {
    $("#addNewActivitySingleForm")[0].reset();
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    })
});
$("#singlemodelsaved").click(function() {
    $("#addNewActivitySearchedFormSaved")[0].reset();
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    })
});
$("#addNewActivityForm").submit(function() {
    if ($("#addNewActivityForm")[0].checkValidity()) {
        $("#btnaddac").css('pointer-events', 'none');
        var uid = $('#uid').val();
        var data = $('#addNewActivityForm').serializeArray();
        data.push({
            name: 'uniqueid',
            value: uid
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addNewActivity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#addNewActivityForm")[0].reset();
                $("#exlat").val('');
                $("#exlong").val('');
                $("#isall").val('0');
                $("#bindTab").html(data.body);
                $("#btnaddac").css('pointer-events', 'auto');
                $('#mapModal').modal('hide');
                $("#btnaddac").hide();
            }
        });
    }
    return false;
});
$("#addNewActivityMultiForm").submit(function() {
    if ($("#addNewActivityMultiForm")[0].checkValidity()) {
        $("#btnaddac").css('pointer-events', 'none');
        var uid = $('#uid').val();
        var data = $('#addNewActivityMultiForm').serializeArray();
        data.push({
            name: 'uniqueid',
            value: uid
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addNewActivityMulti',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#addNewActivityMultiForm")[0].reset();
                $("#exlat").val('');
                $("#exlong").val('');
                $("#isall").val('0');
                $("#bindTab").html(data.body);
                $("#btnaddac").css('pointer-events', 'auto');
                $('#mapModal').modal('hide');
                $("#btnaddac").hide();
            }
        });
    }
    return false;
});
$("#addNewActivitySingleForm").submit(function() {
    if ($("#addNewActivitySingleForm")[0].checkValidity()) {
        $("#btnaddac").css('pointer-events', 'none');
        var uid = $('#uid').val();
        var data = $('#addNewActivitySingleForm').serializeArray();
        data.push({
            name: 'uniqueid',
            value: uid
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addNewActivitySingle',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#addNewActivitySingleForm")[0].reset();
                $("#exlat").val('');
                $("#exlong").val('');
                $("#isall").val('0');
                $("#bindTab").html(data.body);
                $("#btnaddac").css('pointer-events', 'auto');
                $('#mapModal').modal('hide');
                $("#btnaddac").hide();
            }
        });
    }
    return false;
});
$("#saveActivity").click(function() {
    $.ajax({
        type: 'POST',
        url: baseurl + 'addNewActivity',
    });
});
$("#showallSingle").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfSingleCity',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});
$("#showallSearchedSaved").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfSingleCitySaved',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});

function addMainAttractionSingle(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingle',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&ismain=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteMainAttractionSingle(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingle',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addInnerAttractionSingle(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingle',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&ismain=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteInnerAttractionSingle(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingle',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addMainAttractionSingleSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingleSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&ismain=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteMainAttractionSingleSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingleSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addInnerAttractionSingleSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingleSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&ismain=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteInnerAttractionSingleSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSingleSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function getDataForNewCountry(liid, countryid) {
    $("#" + liid).siblings('li').removeClass('main-li-active');
    $("#" + liid).siblings('li').css('pointer-events', 'auto');
    $("#" + liid).addClass('main-li-active');
    $("#" + liid).css('pointer-events', 'none');
    var url = window.location.href;
    var result = url.split('/');
    var Param = result[result.length - 1];
    $("#" + liid).siblings('li').removeClass('main-li-active');
    $("#" + liid).addClass('main-li-active');
    $.ajax({
        type: 'POST',
        url: baseurl + 'getDataForNewCountry',
        data: 'countryid=' + countryid + '&key=' + encodeURIComponent(Param) + '&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindMainPage").html(data.body);
        }
    });
}

function deleteMultiMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addMultiMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}
$("#showmultiall").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfMultiCity',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});

function deleteMultiAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addMultiAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteNewMultiAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=0&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addNewMultiAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=1&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function removeCity(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterCity',
            data: 'cityname=' + cityid + '&addordelete=0&uniqueid=' + $('#uid').val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
}
$("#addNewCityForm").submit(function() {
    if ($("#addNewCityForm")[0].checkValidity()) {
        var uid = $('#uid').val();
        var data = $('#addNewCityForm').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        data.push({
            name: 'uniqueid',
            value: uid
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
    $("#cityModal").modal('hide');
    return false;
});
$("#addNewCityFormSaved").submit(function() {
    if ($("#addNewCityFormSaved")[0].checkValidity()) {
        var iti = $('#iti').val();
        var data = $('#addNewCityFormSaved').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        data.push({
            name: 'iti',
            value: iti
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedSingleCountryCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
    $("#cityModal").modal('hide');
    return false;
});

function addCityInMultiCountry(countryid) {
    $("#cityModal").modal('show');
    $("#countryofcity").val(countryid);
}
$("#addNewCityFormMultiCountry").submit(function() {
    if ($("#addNewCityFormMultiCountry")[0].checkValidity()) {
        var uid = $('#uid').val();
        var data = $('#addNewCityFormMultiCountry').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        data.push({
            name: 'uniqueid',
            value: uid
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterMultiCountryCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
    $("#cityModal").modal('hide');
    return false;
});

function removeCityFromMultiCountry(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterMultiCountryCity',
            data: 'cityname=' + cityid + '&addordelete=0&uniqueid=' + $('#uid').val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
}
$("#showLogonForm").click(function() {
    $("#myModal").modal('show');
});
$(".openloginform").click(function() {
    $("#myModal").modal('show');
    $("body").removeAttr('style');
});


$("#showLoginForForum").click(function() {
     $("#signintabli").addClass('active');	
     $("#signuptabli").removeClass('active');	
     $("#signup").removeClass('active in');	
     $("#signin").addClass('active in');
     $("#myModal").modal('show');
});

$("#showRegisterForForum").click(function() {
     $("#signintabli").removeClass('active');	
     $("#signuptabli").addClass('active');	
     $("#signup").addClass('active in');	
     $("#signin").removeClass('active in');
     $("#myModal").modal('show');
});



$("#registerUserForm").submit(function() {
    if ($("#registerUserForm").valid()) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'signupUser',
            data: $("#registerUserForm").serialize(),
            success: function(data) {
                if (data == 1) {
                    window.location.reload();
                } else {
                    $("#appendregistererrors").html(data);
                }
            }
        });
    }
    return false;
});
$("#signinForm").submit(function() {
    if ($('#g-recaptcha-response').val() == "") {
        $("#signinForm").valid();
        $("#errorcaptcha").html("We love the idea of robots. But we need you to be a human to access this website.");
    } else {
        if ($("#signinForm").valid()) {
            $.ajax({
                type: 'POST',
                url: baseurl + 'signinUser',
                data: $("#signinForm").serialize(),
                success: function(data) {
                    if (data == 1) {
                        window.location.reload();
                    } else {
                        $("#appendsigninerrors").html('Invalid Email/Password Combination.');
                    }
                }
            });
        }
    }
    return false;
});
$("#citydropdownSaved").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    $.ajax({
        type: 'POST',
        url: baseurl + 'Triphotels/hotelListsForSavedCountry-ajax/' + encodeURI(param),
        data: 'city_id=' + $(this).val() + '&drop=1',
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#citydropdown").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    $.ajax({
        type: 'POST',
        url: baseurl + 'hotels/getHotelsajax/' + encodeURI(param),
        data: 'city_id=' + $(this).val() + '&drop=1',
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#searchcitydropdown").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    var recommendation = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
    $.ajax({
        type: 'POST',
        url: baseurl + 'hotels/showSearchedCityHotelsajax/' + encodeURI(param),
        data: 'cityid=' + $(this).val() + '&drop=1&recommendation=' + recommendation,
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#searchcitydropdownSaved").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    var recommendation = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
    $.ajax({
        type: 'POST',
        url: baseurl + 'triphotels/hotelListsForsearchedCity-ajax/' + encodeURI(param),
        data: 'cityid=' + $(this).val() + '&drop=1&recommendation=' + recommendation,
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#multicitydropdownSaved").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    $.ajax({
        type: 'POST',
        url: baseurl + 'triphotels/hotelListsForSavedMultiCountry-ajax/' + encodeURI(param),
        data: 'ids=' + $(this).val() + '&drop=1&iti=' + $('#dropuniqueid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 4000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#multicitydropdown").change(function() {
    var currentUrl = window.location.href;
    var param = currentUrl.split('/').pop();
    $.ajax({
        type: 'POST',
        url: baseurl + 'hotels/getMultiCountryHotelsajax/' + encodeURI(param),
        data: 'ids=' + $(this).val() + '&drop=1&uniqueid=' + $('#dropuniqueid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 4000);
        },
        success: function(data) {
            $("#bindajax").html(data);
        }
    });
});
$("#showallSaved").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfCitySaved',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&iti=' + $(this).attr('classattr'),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});
$("#addNewSavedCityForm").submit(function() {
    if ($("#addNewSavedCityForm")[0].checkValidity()) {
        var data = $('#addNewSavedCityForm').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
    $("#cityModal").modal('hide');
    return false;
});

function removeSavedCity(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedCity',
            data: 'cityname=' + cityid + '&addordelete=0',
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
}

function deleteSavedMultiMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterSavedMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addSavedMultiMainAttraction(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterSavedMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=1&flag=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteSavedMultiMainAttractionAll(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterSavedMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addSavedMultiMainAttractionAll(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterSavedMultiAttraction',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}
$("#addNewCityFormSavedMultiCountry").submit(function() {
    if ($("#addNewCityFormSavedMultiCountry")[0].checkValidity()) {
        var iti = $("#iti").val();
        var data = $('#addNewCityFormSavedMultiCountry').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        data.push({
            name: 'iti',
            value: iti
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedMultiCountryCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
    $("#cityModal").modal('hide');
    return false;
});

function getDataForNewCountryMultiSaved(liid, countryid) {
    $("#" + liid).siblings('li').removeClass('main-li-active');
    $("#" + liid).siblings('li').css('pointer-events', 'auto');
    $("#" + liid).addClass('main-li-active');
    $("#" + liid).css('pointer-events', 'none');
    var url = window.location.href;
    var result = url.split('/');
    var Param = result[result.length - 1];
    $("#" + liid).siblings('li').removeClass('main-li-active');
    $("#" + liid).addClass('main-li-active');
    $.ajax({
        type: 'POST',
        url: baseurl + 'getDataForNewCountryMultiSaved',
        data: 'countryid=' + countryid + '&iti=' + $("#iti").val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindMainPage").html(data.body);
        }
    });
}

function removeCityFromMultiCountrySaved(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedMultiCountryCity',
            data: 'cityname=' + cityid + '&addordelete=0' + '&iti=' + $("#iti").val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
}
$("#showmultiallSaved").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfMultiCitySaved',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&iti=' + $("#iti").val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});
$("#forgotpasswordform").submit(function() {
    $.ajax({
        type: 'POST',
        url: baseurl + 'forgotPassword',
        data: $("#forgotpasswordform").serialize(),
        success: function(data) {
            if (data == 1) {
                $("#forgotpasswordform")[0].reset();
                $("#forgotpassorderrdisnonde").hide();
                $("#forgotpassordsucdisnonde").show();
            } else {
                $("#forgotpassordsucdisnonde").hide();
                $("#forgotpassorderrdisnonde").show();
            }
        }
    });
    return false;
});
$("#attractionsearchform").submit(function() {
    if ($("#attractionsearchform")[0].checkValidity()) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'seachAttractionsFromGYG',
            data: $("#attractionsearchform").serialize(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#scriptsearchedattractions").hide();
                $("#bindsearchedattractions").show();
                $("#bindsearchedattractions").html(data.body);
            }
        });
    }
    return false;
});
$(".addcity").click(function() {
    var cityid = $(this).attr('id');
    $.ajax({
        type: 'POST',
        url: baseurl + 'addExtraCity',
        data: 'cityid=' + cityid,
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindMainPage").html(data.body);
        }
    });
})
$("#forgotcancel").click(function() {
    $("#forgotpassordsucdisnonde").hide();
    $("#forgotpassorderrdisnonde").hide();
    $('#forgotpasswordmodal').modal('toggle');
});
$(".addsearchcity").click(function() {
    var cityid = $(this).attr('id');
    $.ajax({
        type: 'POST',
        url: baseurl + 'addExtraCity',
        data: 'cityid=' + cityid + '&uniqueid=' + $('#uid').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindMainPage").html(data.body);
        }
    });
});

function removeExtraCity(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'removeExtraCity',
            data: 'cityid=' + cityid + '&uniqueid=' + $('#uid').val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#bindMainPage").html(data.body);
            }
        });
    }
}
$(".addsearchcitysaved").click(function() {
    var cityid = $(this).attr('id');
    $.ajax({
        type: 'POST',
        url: baseurl + 'addExtraCityInSaved',
        data: 'cityid=' + cityid + '&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindMainPage").html(data.body);
        }
    });
});

function removeExtraCityFromSave(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'removeExtraCityFromSave',
            data: 'cityid=' + cityid + '&iti=' + $('#iti').val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#bindMainPage").html(data.body);
            }
        });
    }
}
$("#buttonsearch").click(function() {
    if ($("#recformsubmit").validationEngine('validate')) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'searchformsubmit',
            data: 'token=' + $('#token').val(),
            success: function(data) {
                $("#recformsubmit").submit();
            }
        });
    }
});
$("#recommendationbtn").click(function() {
    if ($("#recommendationform").validationEngine('validate')) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'recommendedformsubmit',
            data: 'token=' + $('#rectoken').val(),
            success: function(data) {
                $("#recommendationform").submit();
            }
        });
    }
});

function deleteMainAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteInnerAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addMainAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=1&ismain=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=1&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function deleteAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&ismain=0&flag=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}

function addAllAttractionSaved(attractionid, cityid) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'alterMainAttractionSaved',
        data: 'attractionid=' + attractionid + '&cityid=' + cityid + '&flag=0&ismain=0&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
        }
    });
}
$("#showallSingleSaved").click(function() {
    $.ajax({
        url: baseurl + 'getAllAttractionsOfCitySaved',
        type: 'POST',
        data: 'id=' + $(this).attr('idattr') + '&iti=' + $('#iti').val(),
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function() {
            setTimeout(function() {
                $.LoadingOverlay("hide", true);
            }, 3000);
        },
        success: function(data) {
            $("#bindTab").html(data.body);
            $("#isall").val(1);
        }
    });
});

function removeSavedSingleCountryCity(cityid) {
    var cnf = confirm('Are you sure you want to delete this city ?');
    if (cnf) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'alterSavedSingleCountryCity',
            data: 'cityname=' + cityid + '&addordelete=0&iti=' + $('#iti').val(),
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindMainPage").html(data.body);
                }
            }
        });
    }
}

function addCity(countryid) {
    $("#cityModal").modal('show');
    $("#countryofcity").val(countryid);
}
$("#addUserActivityToSingleCountrySaved").submit(function() {
    if ($("#addUserActivityToSingleCountrySaved")[0].checkValidity()) {
        var iti = $('#iti').val();
        var data = $('#addUserActivityToSingleCountrySaved').serializeArray();
        data.push({
            name: 'addordelete',
            value: 1
        });
        data.push({
            name: 'iti',
            value: iti
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addUserActivityToSingleCountrySaved',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                if (data == 2) {
                    alert('something wrong');
                } else {
                    $("#bindTab").html(data.body);
                }
            }
        });
    }
    $("#mapModal").modal('hide');
    return false;
});
$("#addNewActivitySearchedFormSaved").submit(function() {
    if ($("#addNewActivitySearchedFormSaved")[0].checkValidity()) {
        $("#btnaddac").css('pointer-events', 'none');
        var iti = $('#iti').val();
        var data = $('#addNewActivitySearchedFormSaved').serializeArray();
        data.push({
            name: 'iti',
            value: iti
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addNewActivityToSavedSearchedCity',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#addNewActivitySearchedFormSaved")[0].reset();
                $("#exlat").val('');
                $("#exlong").val('');
                $("#isall").val('0');
                $("#bindTab").html(data.body);
                $("#btnaddac").css('pointer-events', 'auto');
                $('#mapModal').modal('hide');
                $("#btnaddac").hide();
            }
        });
    }
    return false;
});
$("#ckkSingleSaved").click(function() {
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#isall").val(0);
    $("#addUserActivityToSingleCountrySaved")[0].reset();
});
$("#ckkMultiSaved").click(function() {
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#isall").val(0);
    $("#addNewActivitySavedMultiForm")[0].reset();
});
$("#ckkMultiSaved").click(function() {
    $('#mapModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#isall").val(0);
    $("#addNewActivitySavedMultiForm")[0].reset();
});
$("#addNewActivitySavedMultiForm").submit(function() {
    if ($("#addNewActivitySavedMultiForm")[0].checkValidity()) {
        $("#btnaddac").css('pointer-events', 'none');
        var iti = $('#iti').val();
        var data = $('#addNewActivitySavedMultiForm').serializeArray();
        data.push({
            name: 'iti',
            value: iti
        });
        $.ajax({
            type: 'POST',
            url: baseurl + 'addNewActivitySavedMultiCountry',
            data: data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                setTimeout(function() {
                    $.LoadingOverlay("hide", true);
                }, 3000);
            },
            success: function(data) {
                $("#addNewActivitySavedMultiForm")[0].reset();
                $("#exlat").val('');
                $("#exlong").val('');
                $("#isall").val(0);
                $("#bindTab").html(data.body);
                $("#btnaddac").css('pointer-events', 'auto');
                $('#mapModal').modal('hide');
                $("#btnaddac").hide();
            }
        });
    }
    return false;
});
$("#subscriberform").submit(function() {
    $("#msgsub").hide();
    if ($("#subscriberform")[0].checkValidity()) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'addSubscriber',
            data: $(this).serialize(),
            success: function(data) {
                $("#msgsub").show();
                if (data == 1) {
                    $('#subscriberform')[0].reset();
                    $("#msgsub").removeClass('suberror');
                    $("#msgsub").addClass('subsuccess');
                    $("#msgsub").html("You are subscribed to our newsletter.");
                } else if (data == 3) {
                    $("#msgsub").removeClass('suberror');
                    $("#msgsub").addClass('subsuccess');
                    $("#msgsub").html("You are already subscribed to our newsletter.");
                } else {
                    $("#msgsub").removeClass('subsuccess');
                    $("#msgsub").addClass('suberror');
                    $("#msgsub").html("Please enter valid email.");
                }
            }
        });
    }
    return false;
});

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}


function deleteComment(id)
{

	var cnf=confirm('Are you sure you want to delete ?');
	if(cnf)
	{
		$.ajax({
				type: 'POST',
	            url: baseurl + 'deleteComment',
	            data: 'id='+id,
	            success: function(data) 
	            {
	            	res=IsJsonString(data);
	            	if(res)
					{
						var jsn = JSON.parse(data);
						window.location=baseurl + '404';
					}
					else
					{
						$("#maincomment"+id).hide();	
					}
	            	
	            }
			  });
	}

}


function editComment(id)
{
	$("#cmtbx"+id).hide();
	$("#h"+id).show();
	
}

function cancelEdit(id)
{
	$("#h"+id).hide();
	$("#cmtbx"+id).show();
}



function updateComment(id)
{
	$.ajax({
			type: 'POST',
            url: baseurl + 'editComment',
            data: 'id='+id+'&comment='+$("#h"+id).find('textarea').val(),
            success: function(data) 
            {
            	res=IsJsonString(data);
            	if(res)
				{
					var jsn = JSON.parse(data);
					window.location=baseurl + '404';
				}
				else
				{
					$("#h"+id).hide();
					$("#cmtbx"+id).show();
					$("#cmtbx"+id).html($("#h"+id).find('textarea').val());
				}
            	
	            }
			  });
}


$("#postcomment").submit(function(){

	if($("#postcomment")[0].checkValidity() && $("#usercomment").val()!='')
	{
		var url = window.location.href;
		var array = url.split('/');
		var slug = array[array.length-1];

		var data = $('#postcomment').serializeArray();
        data.push({
            name: 'slug',
            value: slug
        });

		$.ajax({
			type: 'POST',
            url: baseurl + 'postComment',
            data: data,
            success: function(data) 
            {
            	res=IsJsonString(data);
            	if(res)
				{
					var jsn = JSON.parse(data);
					window.location=baseurl + '404';
				}
				else
				{
					$("#postcomment")[0].reset();
					$("#comment").html(data.body);
				}
            	
	        }
		 });
	}

	return false;

});

$("#formLogin").submit(function(){

     if ($('#g-recaptcha-response-1').val() == "") 
     {
        $("#errorcaptchafrm").html("We love the idea of robots. But we need you to be a human to access this website.");
        return false;
     }
     return true;

});


