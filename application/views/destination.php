
<div class="container our-team-block">
  <h1>Multiple Destination Trip & Travel Planner</h1>
<p>
  The fragrance of a foreign soil, the mystery behind a lesser-known alleyway, the grandeur of sculpted monuments and the magnanimity of nature – there is no substitute for traveling. Whether you wander around quiet streets with a cup of coffee in your hand, dive deep into the sea and discover the majestic life beneath, or find yourself hike up a mountain and admire a new city from atop, travel is the best kind of therapy there can ever be. With territorial boundaries receding each day, cultures are mingling together and destinations hidden in the crevices of our globe are waiting to be explored.
</p>

<p>
  Take this as the virtual version of throwing darts on a map and heading out on an adventure to that destination. All you have to do is select your travel preferences and let us take care of the rest. One country or more, we recommend the best-suited holiday destinations for you and help you create the ideal travel itinerary with our multiple destination trip planner. Providing you with the flexibility to fully customize your itinerary, trip planning has never been easier.
</p>

<p>
  Be it the balmy coastlines of Oceania or the dancing skies of Scandinavia, our multiple destination travel planner is here to make you grab the opportunity to check off as many items as you can from your bucket list. We bring together the best of what the world has to offer; all you have to do is either choose where you want to go or let us suggest holiday destinations that are in line with your selected travel parameters.
</p>

  <?php $counter=0; if(count($destination)){  ?>
                
<!--Row-->
<!--Gridview starts-->
<div class="row">
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-12">
        <label class="search-label">Search Via Region</label>
      </div>
      <div class="col-sm-6">
        <select class="form-control destination-select" id="continent_id">
          <option>Select Region</option>
          <?php foreach ($continent as $key => $value) { ?>
          <option value="<?php echo $value['id']; ?>"><?php echo $value['continent_name']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <button id="browsebycontinent" class="link-button">Search</button>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-2 col-xs-12">
        <label class="search-label">Search via Country</label>
      </div>
      <div class="col-md-3 col-sm-2 hidden-xs">&nbsp;</div>
      <div class="col-md-6 text-right col-sm-6 col-xs-12">
        <input  name="keyword" type="text" id="countryname" placeholder="Enter Country" class="taxidio-searchform"  >
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <button id="browsebycountrydestination" class="link-button">Search</button> 
        </div>
      </div>
    </div>
  </div>
  <div class="row" id='binddestinations'></div>
     
    
 <?php }  ?>



</div>

<script>

function opencities(id)
{
  $(".related-cities").hide();
  $("#"+id).show();
}

$(document).ready(function(){
       $.ajax({
              type:'POST',
              url:'<?php echo site_url("browse-destinations") ?>',
              data:'continent_id=null',
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
                  $("#binddestinations").html(data);
              },
              error: function (request, status, error) {
                $(".loader").hide();
              }
            });
});


$(document).on('click','div.pagination-container ul li a',function(e){
  var this_url=$(this).attr('href');
  var continent_id=$("#continent_id").val();
  $.ajax({
          type:'POST',
          url:this_url,
          data:'continent_id='+continent_id,
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
              $("#binddestinations").html(data);
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
