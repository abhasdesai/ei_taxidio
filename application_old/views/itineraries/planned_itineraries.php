<div class="container iti-grid-forum">

      <?php if(count($countries)){ ?>
        <div class="row">

          <div class="col-md-5 col-md-push-7 browsebyitinerary">


          <span class="browse-span">Browse Itinerary by Country</span>
          <select class="form-control" id="browsebycountry">
            <option value="0" selected>
              All Country
            </option>
            <?php foreach($countries as $clist){ ?>
              <option value="<?php echo $clist['id'] ?>"><?php echo $clist['country_name']; ?></option>
            <?php } ?>
          </select>

          </div>

        </div>

          <div class="row" id="msg" style="display:none">

            <div class="col-md-12">

              <div class="alert" id="msgbox">

              </div>

            </div>

          </div>


      <?php } ?>

      <div id="binditinerary" class="binditineraries"></div>




</div>

<script>
$(document).ready(function(){
       $.ajax({
              type:'POST',
              url:'<?php echo site_url("browse-itinerary") ?>',
              data:'country_id=0',
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
                  $("#binditinerary").html(data);
              },
              error: function (request, status, error) {
                $(".loader").hide();
              }
            });
});


$(document).on('click','div.pagination-container ul li a',function(e){
  var this_url=$(this).attr('href');
  var country_id=$("#browsebycountry").val();
  $.ajax({
          type:'POST',
          url:this_url,
          data:'country_id='+country_id,
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
              $("#binditinerary").html(data);
              $("body, html").animate({
               scrollTop: $('#minbody').offset().top
             }, 800);
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });

  return false;

});

</script>
