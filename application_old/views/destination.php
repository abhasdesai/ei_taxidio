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

      <?php foreach($destination as $list){ $counter++; $cities=$this->Destination_fm->getCities($list['id']); ?>

      <?php if($counter==1 || $flag==1){ $flag=0; ?>
         <div class="row">
      <?php } ?>

         <div class="col-md-4">
    			<a href="javascript:void(0);" class="country-thumbnail">
    			<div class="overlay--region"><span class="destination-country"><?php echo $list['country_name']; ?></span> <br/> <span class="link-button destination-page" onclick="opencities(<?php echo $list['id'] ?>)">View Cities</span></div>


				<?php if($list['countryimage']!='' && file_exists(FCPATH.'/userfiles/countries/small'.'/'.$list['countryimage'])){ ?>

    			<img src="<?php echo site_url('userfiles/countries/small').'/'.$list['countryimage'] ?>" class="country-img" width="100%" /></a>

    			<?php } else { ?>

    			<img src="<?php echo site_url('assets/images/desnoimage.jpg'); ?>" class="country-img" width="100%" /></a>

    			<?php } ?>


    			<div class="related-cities" id="<?php echo $list['id'] ?>" style="display:none;">

    			<?php foreach($cities as $ilist){ ?>
    				<span><?php echo $ilist['city_name'] ?></span>
    		    <?php } ?>

    			</div>

    		</div>

       <?php if($counter%3==0 || $counter==count($destination)){ $flag=1; ?>
          </div><!--End row-->
      <?php } ?>



     <?php } ?>



 <?php }  ?>



</div>

<script>

function opencities(id)
{
	$(".related-cities").hide();
	$("#"+id).show();
}

</script>
