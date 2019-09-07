<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>


<div class="container our-team-block our-team-block-attractions"> </div>
  <div class="container our-team-block our-team-block-attractions"> </div>
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
    <?php echo form_open('#',array('id'=>'attractionsearchform1','class'=>'form-horizontal','target'=>'_blank'));  ?>
    <div class="city-form">
      <div class="row">
       <!-- <div class="col-md-4 no-padding col-md-offset-4"> --><!-- <label class="col-md-12 control-label" for="name">City</label>-->
          <div class="col-md-12">
           <p class="text-center">Jump the long queues. <br>Book your attraction tickets in advance.</p>
           <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
            <input id="searchattraction1" name="keyword" type="text" placeholder="Enter City" class="form-control">
            
            <input class="locallatitude" name="locallatitude" type="hidden">
            <input class="locallongitude" name="locallongitude" type="hidden">
          </div>
        <!--</div>-->
        <div class="contact-submit">
          <div class="text-center">
            <!-- <a id="attractionsearch_r" target="_blank" style="display: none"></a> -->
            <input type="submit" onClick="ga('send', 'event', { eventCategory: 'general', eventAction: 'attractionclick', eventLabel: 'attractionsearch', eventValue: 0});" class="link-button" value="Search" >
          </div>
        </div>
       
      </div>
    </div>
    
  <?php echo form_close(); ?>
  <br>
  <ul class="attraction-list">
    <li>The charm of new destinations lies in their uniqueness, which may not only be in the form of breath-taking sceneries.</li>
    <li>Architectural wonders, iconic museums, heritage sites and national parks are few of the many sightseeing places that may demand an entry fee along with other allied charges.</li>
    <li>So instead of whiling away your precious time waiting in long lines, we’d rather have you spend your limited vacation days doing what you love and making the most of your trip.</li>
  </ul>
<p class="martop40">    <!--Since we are here to simplify your travel experience, we not only prompt you with all the paid attractions you can visit while you create your itinerary, but also enable you to purchase their tickets right away. We also enable you to book your sightseeing tickets separately, so that you have the maximum flexibility while planning your trip. Contrary to buying your tickets on-day, advance booking is a guaranteed way of saving money and availing several offers and discounts on attraction tickets. Be it a day trip exploring the Pyramids of Giza or nudging your inner child in amusement parks like Disneyland, we offer a range of tours, packages and single attraction tickets that can be booked beforehand.--></p>
    </div>     

<div class="col-sm-9 right-attraction-block">
  <div class="search-attractions">
  <?php echo $body; ?>
  </div>
       <div class="countryviewview container-fluid" >
    
        <h1>Discounted Attraction Tickets</h1>
        <div id="bindsearchedattractions"> </div>
       <div class="attraction-city-block">
       <div class="row"></div>
        <div class="row">
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getLondonGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in London</font></b></a></u>
            </div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getLondonGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/London_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getMumbaiGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Mumbai</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getMumbaiGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Mumbai_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getParisGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Paris</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getParisGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Paris_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
        </div>
      </div>
       <div class="attraction-city-block">
       <div class="row"></div>
        <div class="row">
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getRomeGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Rome</font></b></a></u>
            </div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getRomeGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Rome_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getNewYorkGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in New York</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getNewYorkGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/New York City_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getTorontoGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Toronto</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getTorontoGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Toronto_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
        </div>
      </div>
       <div class="attraction-city-block">
       <div class="row"></div>
        <div class="row">
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getSingaporeGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Singapore</font></b></a></u>
            </div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getSingaporeGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Singapore_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getBarcelonaGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Barcelona</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getBarcelonaGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('userfiles/cities/small/148457801327.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
          <div class="col-sm-4">
            <div class="sub-heading-attraction text-center"><u><a href="<?php echo site_url('Attractions/getHongKongGYGAttractions') ?>" target="_blank"><b><font size="4">Top Attractions in Hong Kong</font></b></a></u></div>
          <div class="city-block">
          <a href="<?php echo site_url('Attractions/getHongKongGYGAttractions') ?>" target="_blank"><img src="<?php echo site_url('assets/images2/Hong Kong_L.jpg') ?>" class="img-responsive center-block"></a>
          </div>
          </div>
        </div>
      </div>
</div>
</div>
</div>
  </div>
   <script>

function initialize() {

    var input = document.getElementById('searchattraction');
    var options={};
      var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      $(".adventure_lat").val(place.geometry.location.lat());
      $(".adventure_long").val(place.geometry.location.lng());
      $(".locallatitude").val(place.geometry.location.lat());
      $(".locallongitude").val(place.geometry.location.lng());
      $("#searchattraction1").val($("#searchattraction").val());
    });

   var input1 = document.getElementById('searchattraction1');
   var options1={};
    var autocomplete1 = new google.maps.places.Autocomplete(input1, options1);
    google.maps.event.addListener(autocomplete1, 'place_changed', function() {
      var place1 = autocomplete1.getPlace();
      //alert(place1.name);
      $(".adventure_lat").val(place1.geometry.location.lat());
      $(".adventure_long").val(place1.geometry.location.lng());
      $(".locallatitude").val(place1.geometry.location.lat());
      $(".locallongitude").val(place1.geometry.location.lng());
      $("#attractionsearchform1").attr("action",$('#base_url').val() + 'searched-attraction/' + place1.name.toLowerCase().replace(/ /g, '-') + '-attraction-tickets');
      $("#searchattraction").val($("#searchattraction1").val());
    });
}

google.maps.event.addDomListener(window, 'load', initialize);


</script>