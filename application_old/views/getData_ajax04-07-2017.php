<style>

#map-canvas {
  width:400px;
  height:280px;
}
</style>
<div class="modal-content">
<div class="mainwrapper">
          <div class="country-name text-center text-uppercase">
              <h3><?php echo $countrydetails['country_name']; ?></h3>
            </div>
            <div class="country-image">

                 <?php if($countrydetails['countryimage']!=''){ ?>
                    <img src="<?php echo site_url('userfiles/countries/small').'/'.$countrydetails['countryimage'] ?>" width="100%" alt="" /></a>
                    <?php } else { ?>
                    <img src="<?php echo site_url('assets/images/kangaroo.png') ?>" width="100%" alt="" /></a>
                    <?php } ?>

            </div>
            <div class="location-title text-center">
              <h4>Geographical Location</h4>
            </div>
            <div class="country-map text-center">

            	<div id="<?php echo 'm'.$countrydetails['id']; ?>" style="width:auto;height:200px"></div>
                <script>
                   var map = L.mapbox.map('<?php echo "m".$countrydetails["id"]; ?>', 'mapbox.streets')
                         .setView([<?php echo $countrydetails['countrylatitude']; ?>,<?php echo $countrydetails['countrylongitude']; ?>], 6);

                  $(document).ready(function(){
                  	  $('#myModal1').on('shown.bs.modal', function () {

               			 L.marker([<?php echo $countrydetails['countrylatitude']; ?>,<?php echo $countrydetails['countrylongitude']; ?>]).addTo(map);
               			  map.invalidateSize();
			          });

                  	  $("#myModal1").modal('show');
                 });


				</script>

				</script>
	        </div>
            <div class="time-to-reach text-center text-capitalize">
              <h4>time taken to reach</h4>
                <span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $countrydetails['timetoreach']; ?></span>
            </div>
             <div class="main-cities text-center text-capitalize">
              <h4>Main Cities</h4>
                <div class="slider centerpopup single-item1 responsive">



                   <?php foreach($cityimages as $citylist){ ?>
                   <?php //echo site_url('city').'/'.$citylist['cityslug']; ?>
                    <div><a href="javascript:void(0);">

                      <?php if($citylist['cityimage']!=''){ ?>
                        <img src="<?php echo site_url('userfiles/cities/small').'/'.$citylist['cityimage']; ?>" alt="" target="_blank"/><p><?php echo $citylist['city_name']; ?></p>
                     <?php }else{ ?>
                         <img src="<?php echo site_url('assets/images/attraction1.jpg') ?>" alt="<?php echo $citylist['city_name']; ?>"/>
                      <?php } ?>
                     </a></div>



                    <?php } ?>
    </div>
            </div>
            <div class="conclusion text-center text-capitalize">
              <h4>Conclusion</h4>
              <p class="conclusion-text"><?php echo character_limiter($countrydetails['country_conclusion'],30); ?></p>
        <a class="tooltip" href="#"><i class="fa fa-info-circle"></i><span class="tooltip-content"><span class="tooltip-text"><span class="tooltip-inner"><?php echo $countrydetails['country_conclusion']; ?></span></span></span></a>
   </div>
        </div>
<div class="modal-footer">
  <button type="button" class="link-button" data-dismiss="modal">Close</button>
</div>
</div>
<script>


 $('.modal').on('shown.bs.modal', function (e) {
    $('.single-item1').slick({infinite: false});
 });

</script>
