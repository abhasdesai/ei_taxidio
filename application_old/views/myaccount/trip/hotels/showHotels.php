<div class="container-fluid hotel-list">
  <div class="container">
      <div class="row" id="bindajax">
          


      </div>
    </div>
</div>

<script>
$(document).ready(function(){
      var recommendation='<?php echo $recommendation ?>';
      var cityid='<?php echo $cityid ?>';
      $.ajax({
              type:'POST',
              url:'<?php echo site_url("Triphotels/hotelListsForSavedCountry-ajax") ?>/'+recommendation,
              data:'recommendation='+encodeURI(recommendation)+'&city_id='+cityid,
              beforeSend: function(){
                         $.LoadingOverlay("show");
                      },
              complete: function(){
                  setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
              },
              success:function(data)
              {
                  if(data==2)
                  {  
                     window.location="<?php echo site_url() ?>";
                  }
                  $("#bindajax").html(data);
              }
            });

  });

  $(document).on('click','div.pagination-container ul li a',function(e){
      var this_url=$(this).attr('href');
      var cityid=$("#postcityid").val();
      $.ajax({
              type:'POST',
              url:this_url,
              data:'city_id='+cityid,
              beforeSend: function(){
                         $.LoadingOverlay("show");
                      },
              complete: function(){
                  setTimeout(function(){  $.LoadingOverlay("hide",true); }, 1000);
              },
              success:function(data)
              {
                  if(data==2)
                  {  
                     window.location="<?php echo site_url() ?>";
                  }

                  $("#bindajax").html(data);
                   $("body, html").animate({ 
                   scrollTop: $('.recommendation').offset().top 
                 }, 800);
              }
            });

      return false;

  });


</script>
