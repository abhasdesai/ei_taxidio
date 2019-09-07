<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>

<?php if(count($topcities) || count($topcountries)){ ?>

<?php echo form_open('#',array('id'=>'attractionsearchform','class'=>'form-horizontal'));  ?>


<div class="container city-form">

 <div class="row">

 <div class="col-md-4 no-padding col-md-offset-4">
	 <!-- <label class="col-md-12 control-label" for="name">City</label>-->
	  <div class="col-md-12">
      <input id="searchattraction" name="keyword" type="text" placeholder="Enter City" class="form-control">
      <p class="text-center">Jump the long queues. Book your attraction tickets in advance.</p>
      <input class="locallatitude" name="locallatitude" type="hidden">
      <input class="locallongitude" name="locallongitude" type="hidden">

	  </div>
</div>

<div class="contact-submit col-md-4 col-md-offset-4">
      <div class="text-center">
        <input type="submit" onClick="ga('send', 'event', { eventCategory: 'general', eventAction: 'attractionclick', eventLabel: 'attractionsearch', eventValue: 0});" class="link-button" value="Search">
      </div>
</div>

</div>
</div>

<?php echo form_close(); ?>
<?php } ?>

<div class="container our-team-block our-team-block-attractions">

  <?php $counter=0; if(count($topcountries)){  ?>

    <h2>Top Countries</h2>
      <?php foreach($topcountries as $list){ $counter++;
       if($counter==1 || $flag==1){ $flag=0; ?>
         <div class="row">
      <?php } ?>

         <div class="col-md-4">
    			<a href="<?php echo site_url('attractions-info').'/'.$list['slug'] ?>" class="country-thumbnail thumbnail-attractions">
    			<div class="overlay--region"><span class="destination-country"><?php echo $list['country_name']; ?></span></div>


				<?php if($list['countryimage']!='' && file_exists(FCPATH.'/userfiles/countries/small'.'/'.$list['countryimage'])){ ?>

    			<img src="<?php echo site_url('userfiles/countries/small').'/'.$list['countryimage'] ?>" class="country-img" width="100%" /></a>

    			<?php } else { ?>

    			<img src="<?php echo site_url('assets/images/desnoimage.jpg'); ?>" class="country-img" width="100%" /></a>

    			<?php } ?>

	       </div>

     <?php if($counter%3==0 || $counter==count($topcountries)){  $flag=1;  ?>
          </div>
      <?php } ?>



     <?php } ?>



 <?php }  ?>



</div>


<div class="container our-team-block our-team-block-attractions">

  <?php $counter=0; if(count($topcities)){  ?>
    <h2>Top Cities</h2>
      <?php foreach($topcities as $list){ $counter++;
       if($counter==1 || $flag==1){ $flag=0; ?>
         <div class="row">
      <?php } ?>

         <div class="col-md-4">
    			<a href="<?php echo site_url('attractions-info').'/'.$list['slug'] ?>" class="country-thumbnail thumbnail-attractions">
    			<div class="overlay--region"><span class="destination-country"><?php echo $list['city_name']; ?></span></div>


				<?php if($list['cityimage']!='' && file_exists(FCPATH.'/userfiles/cities/small'.'/'.$list['cityimage'])){ ?>

    			<img src="<?php echo site_url('userfiles/cities/small').'/'.$list['cityimage'] ?>" class="country-img" width="100%" /></a>

    			<?php } else { ?>

    			<img src="<?php echo site_url('assets/images/desnoimage.jpg'); ?>" class="country-img" width="100%" /></a>

    			<?php } ?>

	       </div>

     <?php if($counter%3==0 || $counter==count($topcities)){  $flag=1;  ?>
          </div>
      <?php } ?>



     <?php } ?>



 <?php }  ?>



</div>

<?php echo form_open('#',array('id'=>'attractionsearchform1','class'=>'form-horizontal'));  ?>


<div class="container city-form">

 <div class="row">

 <div class="col-md-4 no-padding col-md-offset-4">
	 <!-- <label class="col-md-12 control-label" for="name">City</label>-->
	  <div class="col-md-12">
      <input id="searchattraction1" name="keyword" type="text" placeholder="Enter City" class="form-control">
      <p class="text-center">Jump the long queues. Book your attraction tickets in advance.</p>
      <input class="locallatitude" name="locallatitude" type="hidden">
      <input class="locallongitude" name="locallongitude" type="hidden">

	  </div>
</div>

<div class="contact-submit col-md-4 col-md-offset-4">
      <div class="text-center">
        <input type="submit" onClick="ga('send', 'event', { eventCategory: 'general', eventAction: 'attractionclick', eventLabel: 'attractionsearch', eventValue: 0});" class="link-button" value="Search" >
      </div>
</div>

</div>
</div>

<?php echo form_close(); ?>

<div class="container">
  <div class="row">


<p class='martop40'>
  The charm of new destinations lies in their uniqueness, which may not only be in the form of breath-taking sceneries. Architectural wonders, iconic museums, heritage sites and national parks are few of the many sightseeing places that may demand an entry fee along with other allied charges. So instead of whiling away your precious time waiting in long lines, we’d rather have you spend your limited vacation days doing what you love and making the most of your trip. Since we are here to simplify your travel experience, we not only prompt you with all the paid attractions you can visit while you create your itinerary, but also enable you to purchase their tickets right away. We also enable you to book your sightseeing tickets separately, so that you have the maximum flexibility while planning your trip. Contrary to buying your tickets on-day, advance booking is a guaranteed way of saving money and availing several offers and discounts on attraction tickets. Be it a day trip exploring the Pyramids of Giza or nudging your inner child in amusement parks like Disneyland, we offer a range of tours, packages and single attraction tickets that can be booked beforehand.
</p>
</div>
</div>


<div class="countryviewview container-fluid" >

	<div class="container">
  <div class="row">
  <h1>Discounted Attraction Tickets</h1>
    <div id="bindsearchedattractions">

    </div>
    </div>
</div>

</div>

<div class="container-fluid get-your-guides-list" id="scriptsearchedattractions">
	<div class="search-attractions">

	</div>
	  <div class="container">
      <div class="row">
<div id="gyg-widget-5878d7c05f28e"></div><script async defer src="//widget.getyourguide.com/v2/core.js" onload="GYG.Widget(document.getElementById('gyg-widget-5878d7c05f28e'),{'currency':'USD','localeCode':'en-US','partnerId':'4SS3131','q':'London'});"></script>
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
      $(".adventure_lat").val(place1.geometry.location.lat());
      $(".adventure_long").val(place1.geometry.location.lng());
      $(".locallatitude").val(place1.geometry.location.lat());
      $(".locallongitude").val(place1.geometry.location.lng());
      $("#searchattraction").val($("#searchattraction1").val());
    });
}

google.maps.event.addDomListener(window, 'load', initialize);


</script>
