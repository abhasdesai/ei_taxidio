
<?php if(isset($webpage) && $webpage=='attraction_listings'){  ?>
 <div class="col-md-12 no-padding city-img">
            <figure class="tint">
                <?php if($countrybanner!=''){ ?>
                    <img id="countryimg" src="<?php echo site_url('userfiles/countries/banner').'/'.$countrybanner ?>" alt="" width="100%" height="250px">
                <?php } else { ?>
                    <img id="countryimg" src="<?php echo site_url('assets/images/countrynoimage.jpg'); ?>" alt="" width="100%" height="250px" alt="">
                <?php } ?>

            </figure>
        </div>
<div class="overlay-city-img"></div>
<div class="container country-details country-image-dynamic">
    <div class="row">
        <div class="col-md-12 text-center explorecountry">
            <h2 class="country-name">Explore <?php if(isset($countrynm['country_name']) && $countrynm['country_name']!=''){ echo $countrynm['country_name'];  } ?></h2>
              <p id="pcountry-name"><?php echo $countryconclusion; ?></p>
              <?php /* ?>
              <p id="pcountry-name"><?php echo word_limiter($countryconclusion,110); ?>
              <span class="citycon"><a class="readmore" href="<?php echo site_url('country').'/'.$countrynm['slug']; ?>" target="_blank">Read More</a></span></p>
              <?php */ ?>
        </div>
    </div>
</div>
<?php } else if(isset($webpage) && $webpage=='planneditineraries'){ ?>

  <?php if(isset($countrybanner) && $countrybanner!=''){ ?>

  <div class="col-md-12 no-padding city-img">
             <figure class="tint">
                 <?php if($countrybanner!=''){ ?>
                     <img id="countryimg" src="<?php echo site_url('userfiles/countries/banner').'/'.$countrybanner ?>" alt="" width="100%" height="250px">
                 <?php } else { ?>
                     <img id="countryimg" src="<?php echo site_url('assets/images/countrynoimage.jpg'); ?>" alt="" width="100%" height="250px" alt="">
                 <?php } ?>

             </figure>
         </div>
 <div class="overlay-city-img"></div>
 <div class="container country-details country-image-dynamic">
     <div class="row">
         <div class="col-md-12 text-center explorecountry">
             <h2 class="country-name">Explore <?php if(isset($countryname) && $countryname!=''){ echo $countryname;  } ?></h2>
               <p id="pcountry-name"><?php echo $countryconclusion; ?></p>
               <?php /* ?>
               <p id="pcountry-name"><?php echo word_limiter($countryconclusion,110); ?>
               <span class="citycon"><a class="readmore" href="<?php echo site_url('country').'/'.$countrynm['slug']; ?>" target="_blank">Read More</a></span></p>
               <?php */ ?>
         </div>
     </div>
 </div>

  <?php }else{ ?>

<div class="container">
        <div class="row">
            <div class="col-md-12 text-center booking-hotels explorecountry">
                <h2 class="text-uppercase">Planned Itineraries</h2>

            </div>
        </div>
    </div>

   <?php } ?>


<?php } else if(isset($webpage) && $webpage=='Forum'){ ?>
<div class="container">
      <div class="row">
          <div class="col-md-12 text-center explorecountry">
              <h1>Planned Itinerary</h1>
              <p>
                Design your itineraries the way you want, network with other users and modify their travel schedule according to your preference – this interactive forum is aimed at bringing our users closer, through an exchange of travel experiences.
              </p>
            </div>
        </div>
    </div>
    

<?php } else if(isset($webpage) && $webpage=='country_recommendation'){ ?>
<div class="container">
      <div class="row">
          <div class="col-md-12 text-center explorecountry">
              <h2 id="singlecountry-rec">Single Country Recommendation</h2>
                <p>Depending on your chosen parameters, we recommend a destination that does justice to the duration of your stay. </p>
            </div>
        </div>
    </div>
<?php } else if(isset($webpage) && $webpage=='Payment'){ ?>
<div class="container">
      <div class="row">
          <div class="col-md-12 text-center explorecountry">
              <h2 id="singlecountry-rec">Purchase Travel Guide</h2>
                
            </div>
        </div>
    </div>
<?php } else if(isset($webpage) && $webpage=='allattractions'){ ?>
<div class="container">
      <div class="row">
          <div class="col-md-12 text-center explorecountry">
                <h2>Attractions</h2>
                <p>Certain manmade marvels and alluring points of exploration are the good things in life that don’t come free. We save you from the last-minute mayhem, long queues and also let you avail discounts by enabling you to purchase attraction and sightseeing tickets in advance.</p>
            </div>
        </div>
    </div>
<?php } else if(isset($webpage) && $webpage=='cityattractions'){ ?>


<div class="col-md-12 no-padding city-img">
            <figure class="tint">
                <?php if($countrybanner!=''){ ?>
                    <img src="<?php echo site_url('userfiles/countries/banner').'/'.$countrybanner ?>" alt="" width="100%" height="250px">
                <?php } else { ?>
                    <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" alt="" width="100%" height="250px">
                <?php } ?>

            </figure>
        </div>
<div class="overlay-city-img"></div>
<div class="container country-details country-image-dynamic">
    <div class="row">
        <div class="col-md-12 text-center explorecountry">
            <h2 class="country-name">Explore <?php echo $countryname; ?></h2>
             <p><?php echo $countryconclusion; ?></p>
        </div>
    </div>
</div>


<?php } else if(isset($webpage) && $webpage=='hotels'){ ?>
<div class="container">
        <div class="row">
            <div class="col-md-12 text-center booking-hotels explorecountry">
                <h1 class="text-uppercase">Leading Hotel Search Engine</h1>
                <p>What better than privacy, comfort and leisure blended together, while you find an escape for your humdrum in a new city? From lavish inns to modest motels, we offer you the best hotel recommendations based on your points of interest, budget preferences and style of travel.</p>
            </div>
        </div>
    </div>

<?php } else if(isset($webpage) && $webpage=='destination')
{
?>

<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Destinations</h2>
                <p>Spreading our wings over every continent, we cover destinations across more than 100 countries. From sky-spearing mountains to glistening coastlines, heritage edifices to contemporary structures, our multiple-destination trip planner befits every style of travel.</p>
            </div>
        </div>
    </div>
    </div>

<?php } else if(isset($webpage) && $webpage=='reset_password'){ ?>

<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Password Reset</h2>
            </div>
        </div>
    </div>
    </div>


<?php }else if(isset($webpage) && $webpage=='hotel_bookings'){ ?>



<div class="col-md-12 no-padding city-img">
          <figure class="tint">

               <?php if(isset($citybasic['citybanner']) && $citybasic['citybanner']!=''){ ?>

            <?php if(file_exists(FCPATH.'userfiles/cities/banner/'.$citybasic['citybanner'])){ ?>
                <img id="cityb" src="<?php echo site_url('userfiles/cities/banner').'/'.$citybasic['citybanner'] ?>" />
          <?php }else{ ?>
                <img id="cityb" src="<?php echo site_url('userfiles/cities/banner').'/'.$citybasic['citybanner'] ?>" />
            <?php } ?>

        <?php } else { ?>
        <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" />
        <?php } ?>
     </figure>
  </div>


<div class="overlay-city-img"></div>



<div class="container city-heading">
        <div class="row">
            <div class="col-md-12 text-center booking-hotels explorecountry">
                <h2 class="text-uppercase" id="cityhotelnm">Hotels in <?php echo $citybasic['city_name']; ?></h2>
                <p>Our hand-picked hotels based on your chosen parameters located in close proximity to your travel attractions.</p>
                <?php /* ?>
                 <p id="bindconclusion"><?php echo word_limiter($citybasic['city_conclusion'],110); ?>
                 <span class="citycon"><a class="readmore" href="<?php echo site_url('city').'/'.$citybasic['slug']; ?>" target="_blank">Read More</a></span></p>
                 <?php */ ?>
             </div>
        </div>
    </div>
<?php } else if(isset($webpage) && $webpage=='city'){ ?>



 <div class="col-md-12 no-padding city-img">
          <figure class="tint">

               <?php if(isset($city['citybanner']) && $city['citybanner']!=''){ ?>

            <?php if(file_exists(FCPATH.'userfiles/cities/banner/'.$city['citybanner'])){ ?>
                <img src="<?php echo site_url('userfiles/cities/banner/'.$city['citybanner']) ?>" />
          <?php }else{ ?>
                <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" />
            <?php } ?>

        <?php } else { ?>
        <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" />
        <?php } ?>
     </figure>
  </div>



 <div class="overlay-city-img"></div>

 <div class="container city-heading">
        <div class="row">
            <div class="col-md-12 text-center">
                 <h2><?php echo $city['city_name'] ?></h2>
            </div>
        </div>
    </div>


<?php } else if(isset($webpage) && $webpage=='attractionsFromGYG'){
 ?>

 <div class="col-md-12 no-padding city-img">
          <figure class="tint">

               <?php if(isset($citydetails['citybanner']) && $citydetails['citybanner']!=''){ ?>

            <?php if(file_exists(FCPATH.'userfiles/cities/banner/'.$citydetails['citybanner'])){ ?>
                <img src="<?php echo site_url('userfiles/cities/banner/'.$citydetails['citybanner']) ?>" />
          <?php }else{ ?>
                <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" />
            <?php } ?>

        <?php } else { ?>
        <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" />
        <?php } ?>
     </figure>
  </div>

<div class="overlay-city-img"></div>


 <div class="container city-heading">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2><?php echo 'Attractions In '.$citydetails['city_name']; ?></h2>
                <p><?php //echo $citydetails['city_conclusion']; ?></p>
                <?php /* ?>
                <p><?php echo word_limiter($citydetails['city_conclusion'],110); ?>
                <span class="citycon"><a class="readmore" href="<?php echo site_url('city').'/'.$citydetails['slug']; ?>" target="_blank">Read More</a></span></p><?php */ ?>
            </div>
        </div>
    </div>

<?php } else if(isset($webpage) && $webpage=='forum'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center teamanc explorecountry">
                <h2>Forum</h2>
            </div>
        </div>
    </div>

<?php } else if(isset($webpage) && $webpage=='cms'){


 if(isset($page) && $page=='faq'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>FAQ</h2>
                 <p>All you need to know about Taxidio.</p>
            </div>
        </div>
    </div>

 <?php } else if(isset($page) && $page=='team'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center teamanc explorecountry">
                <h2>Crew & Career</h2>
                 <?php if(count($cms) && $cms['content']!=''){ ?>
                    <?php echo $cms['content']; ?>
                <?php }?>
            </div>
        </div>
    </div>


 <?php } else if(isset($page) && $page=='contactus'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Contact Us</h2>
                <p>Queries, feedbacks or just a hello – we’ d love to hear from you.</p>
            </div>
        </div>
    </div>
  <?php } else if(isset($page) && $page=='thankyou'){ ?>

     <div class="container">
           <div class="row">
               <div class="col-md-12 text-center explorecountry">
                   <h2>Thank You</h2>
              </div>
           </div>
       </div>

 <?php } else if(isset($page) && $page=='api'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Taxidio for Business</h2>
                <p>Maximizing experiences across the hospitality industry.</p>
            </div>
        </div>
    </div>


  <?php } else if(isset($page) && $page=='contest'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Contest</h2>
                <p>No pack of cards, no slot machines, no wheels of fortune, but a chance to win big without losing anything. Try your luck by simply participating.</p>
            </div>
        </div>
    </div>


 <?php } else if(isset($page) && $page=='terms_and_condition'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Terms And Condition</h2>
                <p>Your access to Taxidio and its services are regulated by this section. We advise you to read further before registering with us / using our website.</p>
            </div>
        </div>
    </div>


 <?php } else if(isset($page) && $page=='media'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Media</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>Lorem Ipsum has been the industry's standard.</p>
            </div>
        </div>
    </div>



 <?php } else if(isset($page) && $page=='career'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Career</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>Lorem Ipsum has been the industry's standard.</p>
            </div>
        </div>
    </div>



 <?php } else if(isset($page) && $page=='discover_taxidio'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Discover Taxidio</h2>
                <p>Taxidio is the first global automated trip planner, which seamlessly brings a logical connect to the process of destination selection, itinerary creation, travel guides, booking and sharing. We cater to the entire value chain of a traveler’s requirement, by bridging the gap between travel planning and execution.</p>
            </div>
        </div>
    </div>


<?php } else if(isset($page) && $page=='privacy_policy'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Policies</h2>
                <p>Your access to Taxidio and its services are regulated by this section. We advise you to read further before registering with us / using our website.</p>
            </div>
        </div>
    </div>


 <?php } else if(isset($page) && $page=='user_content'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>User Content & Conduct Policy</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>Lorem Ipsum has been the industry's standard.</p>
            </div>
        </div>
    </div>


 <?php } else if(isset($page) && $page=='cookie'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Cookie</h2>
                <p>Your access to Taxidio and its services are regulated by this section. We advise you to read further before registering with us / using our website.</p>
            </div>
        </div>
    </div>


<?php } else if(isset($page) && $page=='membership'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Membership</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>Lorem Ipsum has been the industry's standard.</p>
            </div>
        </div>
    </div>


<?php } else if(isset($page) && $page=='credit'){ ?>

  <div class="container">
        <div class="row">
            <div class="col-md-12 text-center explorecountry">
                <h2>Credit</h2>
            </div>
        </div>
    </div>


 <?php } ?>



 <?php } ?>
