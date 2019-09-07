<script src='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css' rel='stylesheet' />
<script>
      L.mapbox.accessToken = 'pk.eyJ1IjoiZWlqaW5hYWwiLCJhIjoiY2lwM3ZpZnc2MDBsdHRybTM4aTVqZGFmdyJ9.8OoaIhs2xQSl6azZDiGzcw';
</script>
<?php if($isloggedin===1 && $multicountries_count>0){ ?>
<div class="container multicon-btn">
    <div class="row">
          <div class="col-md-12 text-right">
              <a class="link-button" href="#multi-countries"><i class="fa fa-arrow-down" aria-hidden="true"></i>Go To Multi-country Recommendation</a>
          </div>
    </div>
</div>
<?php } ?>

<div class="countryviewview container-fluid">


  <div class="container">


  <div class="row">
<?php if ($isloggedin===0 && $countries_count>3) {?>
<div class="container multicon-btn">
    <div class="row">
          <div class="col-md-12 text-center">
           <div class="ask-login cr_page create-account">
           <p>
                    <a href="javascript:void(0);" class="showLoginForForum_cr">Sign In</a> or <a href="javascript:void(0);" class="showRegisterForForum_cr">Create an Account to See More Single-Country Trip.</a>
                    </p>
              </div>
          </div>
    </div>
</div>
<?php } ?>

  <?php if (count($countries)) {
  ?>
     <div class="slider responsive">
      <?php foreach ($countries as $list) {

            //echo "<pre>";print_r($list);die;

      ?>
      <div class="col-md-4">
        <div class="mainwrapper">
          <div class="country-name text-center text-uppercase">
            <h3><a href="<?php echo site_url('attractions').'/'.$list[0]['uniqueid'].'/'.$list[0]['slug']; ?>" target="_blank"><?php echo $list[0]['country_name'] ?></a></h3>
          </div>
          <div class="country-image">
            <a href="<?php echo site_url('attractions').'/'.$list[0]['uniqueid'].'/'.$list[0]['slug']; ?>" target="_blank">
            <?php if($list[0]['countryimage']!=''){ ?>
            <img src="<?php echo site_url('userfiles/countries/small').'/'.$list[0]['countryimage'] ?>" width="100%" alt="" /></a>
            <?php } else { ?>
            <img src="<?php echo site_url('assets/images/kangaroo.png') ?>" width="100%" alt="" /></a>
            <?php } ?>


          </div>
          <div class="location-title text-center">
            <h4>Geographical Location</h4>
          </div>
          <div class="country-map text-center">
              <div id='<?php echo $list[0]["id"]; ?>' style="width:100%;height:200px;"></div>
           </div>
           <script>

                var map = L.mapbox.map('<?php echo $list[0]["id"]; ?>', 'mapbox.streets')
                        .setView([<?php echo $list[0]['countrylatitude']; ?>,<?php echo $list[0]['countrylongitude']; ?>], 6);
                L.marker([<?php echo $list[0]['countrylatitude']; ?>,<?php echo $list[0]['countrylongitude']; ?>]).addTo(map);

              </script>


          <div class="time-to-reach text-center text-capitalize">
            <h4>time taken to reach</h4>
            <span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $list[0]['timetoreach'] ?></span>
          </div>
          <?php /* ?>
          <div class="country-introduction text-center">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.</p>
          </div>
          <?php */ ?>


          <div class="main-cities text-center text-capitalize">
            <h4>Main Cities</h4>

            <div class="slider single-item">

            <?php foreach($list as $citylist){  ?>
            <?php //echo site_url('city').'/'.$citylist['cityslug']; ?>
              <div><a href="javascript:void(0);">

              <?php if($citylist['cityimage']!=''){ ?>
                <img src="<?php echo site_url('userfiles/cities/small').'/'.$citylist['cityimage']; ?>" alt="<?php echo $citylist['city_name'] ?>" /><p><?php echo $citylist['city_name']; ?></p>
             <?php }else{ ?>
                 <img src="<?php echo site_url('assets/images/image-not-available.jpg') ?>" alt=""/>
                 <p><?php echo $citylist['city_name']; ?></p>
              <?php } ?>
             </a></div>

              <?php } ?>



            </div>
          </div>


          <?php  if($list[0]['country_conclusion']){ ?>
          <div class="conclusion text-center text-capitalize">
            <h4>Conclusion</h4>
            <p class="conclusion-text"><?php echo character_limiter($list[0]['country_conclusion'],30); ?></p>
            <a class="tooltip" href="#"><i class="fa fa-info-circle"></i><span class="tooltip-content"><span class="tooltip-text"><span class="tooltip-inner"><?php echo $list[0]['country_conclusion']; ?></span></span></span></a>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php }?>
       </div>
     <?php } else {  ?>

      <div class="col-md-12">
      <div class="alert alert-info alert-norecommendation">
        <p>Based on the chosen parameters, we suggest a multi-country trip.</p>
      </div>
      </div>

  <?php }?>

  </div>
</div>





<div id="multi-countries" class="container-fluid multi-country-recommendation recommendation-banner">

  <div class="col-md-12 text-center">
      <h2>Multi-country Recommendation</h2>
        <p>Incorporating your travel parameters and length of your trip, we assist you with a detailed search and make recommendations on possible visits to multiple countries.</p>
    </div>
</div>

 <?php if (count($countries)) {
  ?>
<div class="container singlcon-btn">
    <div class="row">
          <div class="col-md-12 text-right">
              <a class="link-button" href="#singlecountry-rec"><i class="fa fa-arrow-up" aria-hidden="true"></i>Go To Single Country Recommendation</a>
          </div>
    </div>
</div>
<?php } ?>
<div class="container multi-country-slider">

<?php if(count($multicountries)){ ?>

<?php $counter=0; foreach($multicountries as $key=>$multilist){ $counter++;
 ?>


          <div class="row">
            <div class="col-md-12">
              <h2 class="recommendation">Recommendation <?php echo $counter; ?></h2>
                  <ul class="bxslider 1">

                      <?php for($i=0;$i<count($multicountries[$key])-1;$i++){


                        ?>



                         <li class="col-md-3 text-center" onclick="getData(<?php echo $multilist[$i]['countryid'] ?>,<?php echo $multilist[0]['uniqueid']; ?>)">
                         <?php //echo $multilist[$i]['continent_id']; ?>
                          <div class="country-intro">
                             <a href="javascript:void(0);" >

                                <?php if($multilist[$i]['countryimage']!=''){ ?>
                                  <img src="<?php echo site_url('userfiles/countries/small').'/'.$multilist[$i]['countryimage'] ?>" width="100%" alt="" /></a>
                                  <?php } else { ?>
                                  <img src="<?php echo site_url('assets/images/kangaroo.png') ?>" width="100%" alt="" /></a>
                                  <?php } ?>



                              <span class="multi-country-name"><?php echo $multilist[$i]['country_name'] ?></span>

                             </a>
                          </div>
                        </li>

                        <?php } ?>

                   </ul>
                  <div class="col-md-12 text-center view-all-button-row">
                    <a href="<?php echo site_url('multicountries').'/'.$multilist[0]['uniqueid'].'/'.$multilist['encryptkey']; ?>" class="link-button" target="_blank">View<i class="fa fa-eye" aria-hidden="true"></i></a>
                  </div>
              </div>
       </div>

<?php } ?>

 <?php } elseif($isloggedin===0 && $multicountries_count>0) {  ?>

  <div class="col-md-12">
    <div class="alert alert-info alert-norecommendatio ask-login create-account">
          <a href="javascript:void(0);" class="showLoginForForum_cr">Sign In</a> or <a href="javascript:void(0);" class="showRegisterForForum_cr">Create an Account to See a Multi-Country Trip.</a>
    </div>
  </div>


<?php }else{ ?>
  
  
   <div class="col-md-12">
      <div class="alert alert-info alert-norecommendation">
        <p>Based on the chosen parameters, we suggest a single-country trip.</p>
      </div>
      </div>

<?php } ?>
</div>
</div>
