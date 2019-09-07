<div id="iti-share" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div id="iti_share" class="modal-dialog modal-sm share-dialog">
</div>
</div>
<div class="wraper container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">My Trips</h4>
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
                    <div class="row">
                        <div class="col-md-12">
							<h3 class="text-center">
								Current Trips
							</h3>
                            <div class="card-box" id="currenttrip"></div>

    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
							<h3 class="text-center">
								Upcoming Trips
							</h3>
                            <div class="card-box" id="upcommingtrip"></div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
							<h3 class="text-center">
								Completed Trips
							</h3>
                            <div class="card-box" id="completedtrip"></div>
 
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div> <!-- container -->
  <script>
  jQuery(document).ready(function($){
    $('body').click(function(){
  
      if($('.share-box').is(':visible') && $('.share-box').hasClass('active1')){ 
          $('.share-box').hide();
      }
    });
  });

  function open_share_box(d)
  {
      $('.share-box').hide().removeClass('active1');
      d.find('.share-box').toggle();

      setTimeout(function(){
       d.find('.share-box').addClass('active1');   
      },500)
  }

  function getCoTravellers(d)
  {
     var iti=d;
     $.ajax({
          type:'POST',
          url:'<?php echo site_url("getCoTravellers") ?>',
          data:{iti_id: iti }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#iti_share").html(data);
              $("#iti-share").modal();
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });
  }

  function check_is_public(d)
  {
     var iti=d.parent().attr('iti');
     $.ajax({
          type:'POST',
          dataType: 'json',
          url:'<?php echo site_url("check-iti-is-public") ?>',
          data:{iti_id: iti }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#iti_share").html(data);
              $("#iti-share").modal();
          },
          error: function (request, status, error) {
              $(".loader").hide();
          }
        });
  }

  function member_share(d)
  {
     var iti=d.parent().attr('iti');
     $.ajax({
          type:'POST',
          dataType: 'json',
          url:'<?php echo site_url("share-iti-with-member-form") ?>',
          data:{iti_id: iti }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#iti_share").html(data);
              $("#iti-share").modal();
          },
          error: function (request, status, error) {
              $(".loader").hide();
          }
        });
  }

  function email_share(d)
  {
     var iti=d.parent().attr('iti');
     $.ajax({
          type:'POST',
          dataType: 'json',
          url:'<?php echo site_url("share-iti-with-email") ?>',
          data:{iti_id: iti }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#iti_share").html(data);
              $("#iti-share").modal();
          },
          error: function (request, status, error) {
              $(".loader").hide();
          }
        });
  }

function confirmAlert(tripid)
{
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this trip",
            type: "error",
            showCancelButton: true,
            cancelButtonClass: 'btn-white btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light btn-pop-delete',
            confirmButtonText: 'Yes!'
        });

    $(".btn-pop-delete").click(function(){
        window.location="<?php echo site_url('deleteTrip') ?>"+"/"+tripid;
    })


}


$(document).ready(function() {
     $.ajax({
          type:'GET',
          url:'<?php echo site_url("currenttrip") ?>',
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#currenttrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });
     
     $.ajax({
          type:'GET',
          url:'<?php echo site_url("upcommingtrip") ?>',
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#upcommingtrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

     $.ajax({
          type:'GET',
          url:'<?php echo site_url("completedtrip") ?>',
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#completedtrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

});

$(document).on('click', 'div#cotraveller ul li a',function(e){
  e.preventDefault();
  var this_url=$(this).attr('href');
  $.ajax({
      type:'POST',
      url:this_url,
      data:{iti_id: $('#cot_iti_id').val() }, 
      beforeSend: function()
      {
        $(".loader").show();
      },
      complete: function()
      {
        $(".loader").hide();
      },
      success:function(data)
      {
          $("#iti_share").html(data);
      },
      error: function (request, status, error) {
        $(".loader").hide();
      }
  });

});

$(document).on('click','div#cur_trip ul li a',function(e){
  var this_url=$(this).attr('href');
  $.ajax({
          type:'POST',
          url:this_url,
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#currenttrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

  return false;

});


$(document).on('click','div#upcom_trip ul li a',function(e){
  var this_url=$(this).attr('href');
  $.ajax({
          type:'POST',
          url:this_url,
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#upcommingtrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

  return false;

});


$(document).on('click','div#com_trip ul li a',function(e){
  var this_url=$(this).attr('href');
  $.ajax({
          type:'POST',
          url:this_url,
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#completedtrip").html(data);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

  return false;

});

  </script>
