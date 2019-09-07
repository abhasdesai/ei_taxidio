<div class="container">
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoRMxiPsqJ9SUuaK1KsCAjd3gqnecjlBw"></script>
  <div class="row">
    <div class="slider responsive">
  <?php if (count($countries)) {
  ?>
 
      <?php foreach ($countries as $list) {
      ?>
      <div class="col-md-4">
        <div class="mainwrapper">
          <div class="country-name text-center text-uppercase">
            <h3><?php echo $list['country_name'] ?></h3>
          </div>
          <div class="country-image">
            <img src="<?php echo site_url('assets/images/kangaroo.png') ?>" width="100%" alt="" />
          </div>
          <div class="location-title text-center">
            <h4>Geographical Location</h4>
          </div>
          <div class="country-map text-center">
            <script>
            var myCenter<?php echo $list['id'];
            ?>=new google.maps.LatLng(<?php echo $list['countrylatitude']; ?>,<?php echo $list['countrylongitude']; ?>);
            function initialize<?php echo $list['id']; ?>()
            {
            var mapProp = {
            center:myCenter<?php echo $list['id']; ?>,
            zoom:4,
            mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var map=new google.maps.Map(document.getElementById(<?php echo $list['id']; ?>),mapProp);
            var marker=new google.maps.Marker({
            position:myCenter<?php echo $list['id']; ?>,
            title: '<?php echo $list['country_name']; ?>',
            });
            marker.setMap(map);
            }
            google.maps.event.addDomListener(window, 'load', initialize<?php echo $list['id']; ?>);
            </script>
            <div id="<?php echo $list['id']; ?>" style="width:100%;height:200px;"></div>
          </div>
          <div class="time-to-reach text-center text-capitalize">
            <h4>time taken to reach</h4>
            <span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $list['timetoreach'] ?></span>
          </div>
          <div class="country-introduction text-center">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.</p>
          </div>
          <div class="main-cities text-center text-capitalize">
            <h4>Main Cities</h4>
            <div class="slider center">
              <div><img src="<?php echo site_url('assets/images/attraction1.jpg') ?>" alt="" /></div>
              <div><img src="<?php echo site_url('assets/images/attraction2.jpg') ?>" alt="" /></div>
              <div><img src="<?php echo site_url('assets/images/attraction3.jpg') ?>" alt="" /></div>
              <div><img src="<?php echo site_url('assets/images/attraction4.jpg') ?>" alt="" /></div>
              <div><img src="<?php echo site_url('assets/images/attraction5.jpg') ?>" alt="" /></div>
              <div><img src="<?php echo site_url('assets/images/attraction6.jpg') ?>" alt="" /></div>
            </div>
          </div>
          <div class="conclusion text-center text-capitalize">
            <h4>Conclusion</h4>
            <p class="conclusion-text">Lorem ipsum dolor amet ipsum.</p>
            <a class="tooltip" href="#"><i class="fa fa-info-circle"></i><span class="tooltip-content"><span class="tooltip-text"><span class="tooltip-inner">Howdy, Ben!<br /> There are 13 unread messages in your inbox.</span></span></span></a>
          </div>
        </div>
      </div>
      <?php }?>
   
  <?php }?>
   </div>
  </div>
</div>

<?php if(count($multicountries)){ ?>

<div id="multi-countries" class="container-fluid multi-country-recommendation">
  
  <div class="col-md-12 text-center">
      <h2>Multi Country Recommendation</h2>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>Lorem Ipsum has been the industry's standard.</p>
    </div>
</div>

<div class="container multi-country-slider">

<?php $counter=0; foreach($multicountries as $key=>$multilist){ $counter++; ?>
    
     
          <div class="row">
            <div class="col-md-12">
              <h2 class="recommendation">Recommendation <?php echo $counter; ?></h2>
                  <ul class="bxslider 1">

                      <?php for($i=0;$i<count($multicountries[$key]);$i++){ 

                      
                        ?>

                        <li class="col-md-3 text-center" onclick="getData(<?php echo $multilist[$i]['country'] ?>)">
                          <div class="country-intro">
                             <a href="javascript:void(0);" >
                               <img src="<?php echo site_url('assets/images/kangaroo.png'); ?>" alt="country1" />
                               <span class="multi-country-name"><?php echo $multilist[$i]['country_name'] ?></span>
                             </a>
                          </div>
                        </li>

                        <?php } ?>
                        
                  </ul>
              </div>
       </div>

<?php } ?>

  <div class="row button-collections">
      <div class="col-md-12">
          <ul class="button-ul">
                <li><a href="#" class="link-button">Attractions<i class="fa fa-map-signs" aria-hidden="true"></i></a></li>
                <li><a href="#" class="link-button">Stay with a local<i class="fa fa-heart" aria-hidden="true"></i></a></li>
                <li><a href="#" class="link-button">Proceed to book hotels<i class="fa fa-hand-o-right" aria-hidden="true"></i></a></li>
              </ul>
        </div>
    </div>
    
</div>
<?php } ?>