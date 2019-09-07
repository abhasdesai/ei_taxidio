<div class="container-fluid hotel-list">
  <div class="container">
      <input type="hidden" id="dropuniqueid" value="<?php echo $uniqueid; ?>"/>
      <div class="row" id="bindajax">
          


      </div>
    </div>
</div>

<script>
$(document).ready(function(){
	
      var recommendation='<?php echo $recommendation ?>';
      var cityid='<?php echo $cityid ?>';
      var uniqueid='<?php echo $uniqueid ?>';
      var countryid='<?php echo $countryid ?>';
      $.ajax({
              type:'POST',
              url:'<?php echo site_url("hotels/getMultiCountryHotelsajax") ?>/'+recommendation,
              data:'recommendation='+encodeURI(recommendation)+'&cityid='+cityid+'&countryid='+countryid+'&uniqueid='+uniqueid,
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
      var countryid=$("#postcountryid").val();
      var postuniqueid=$("#postuniqueid").val();
      $.ajax({
              type:'POST',
              url:this_url,
              data:'cityid='+cityid+'&countryid='+countryid+'&uniqueid='+postuniqueid,
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
