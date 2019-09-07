<div class="container-fluid single-country-image no-padding">

		<div class="col-md-12 no-padding city-img">
			<figure class="tint">
            <?php if($info['banner']!=''){ ?>

              <?php if($info['city_id']==0){ ?>

                <?php if(file_exists(FCPATH.'userfiles/countries/banner/'.$info['banner'])){ ?>
                  <img src="<?php echo site_url('userfiles/countries/banner').'/'.$info['banner'] ?>" alt="" width="100%" height="200px">
                <?php }else{ ?>
                  <img src="<?php echo site_url('userfiles/countries/banner').'/'.$info['banner'] ?>" alt="" width="100%" height="200px">
                <?php } ?>

              <?php } else { ?>


                    <?php if(file_exists(FCPATH.'userfiles/cities/banner/'.$info['banner'])){ ?>
                      <img src="<?php echo site_url('userfiles/cities/banner').'/'.$info['banner'] ?>" alt="" width="100%" height="200px">
                    <?php }else{ ?>
                      <img src="<?php echo site_url('userfiles/cities/banner').'/'.$info['banner'] ?>" alt="" width="100%" height="200px">
                    <?php } ?>

              <?php } ?>
          <?php }else{ ?>



            <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" alt="" width="100%" height="200px"/>

            <?php } ?>

    		</figure>
		</div>
		<div class="overlay-city-img"></div>
	<div class="container country-details">
    	<div class="row">
        	<div class="col-md-12 text-center">
            	<h2 class="country-name1"><?php echo $info['name'];?></h2>
            </div>
        </div>
    </div>
    </div>

    <div class="container">
    	<div class="row">
    		<div class="col-md-12 country_conclusion">
    			<p class="country-para"><?php echo $info['description'];?></p>
    		</div>
    	</div>
    </div>

    <div class="container get-your-guides-list">

      <?php if(count($json)){ ?>

      	<?php foreach($json as $list){ ?>

      		<a href="<?php echo $list['url']; ?>" target="_blank">
      			<div class="row gygattractions">

      				<div class="col-md-3">
      					<img src="<?php echo str_replace('[format_id]',66,$list['pictures'][0]['url']); ?>" alt="<?php if(isset($list['pictures'][0]['description']) && $list['pictures'][0]['description']!=''){ echo $list['pictures'][0]['description']; }  ?>" />

      				</div>

      				<div class="col-md-9">
      					<div class="heading-gyg">
      						<h1><?php echo $list['title']; ?></h1>
      					</div>

      					<?php if($list['overall_rating']!='' || $list['overall_rating']>0){ ?>
      						<div class="rating-gyg">
      						 <input id="input-21f" value="<?php echo $list['overall_rating']; ?>" type="number" class="rating input-21f" data-size="xs" readonly>
      						</div>
      					<?php } ?>

      					<?php if($list['number_of_ratings']>0){ ?>
      					<div class="reviews-gyg">
      						<p><?php echo $list['number_of_ratings'].' Reviews'; ?></p>
      					</div>
      					<?php } ?>
      					<div class="abstract-gyg">
      						<?php echo $list['abstract']; ?>
      					</div>
      					<div class="abstract-duration">
      						<p><span>Duration : </span><?php echo $list['durations'][0]['duration'].' '.$list['durations'][0]['unit']; ?></p>
      					</div>
      					<div class="price-gyg">
      						<p><i class="fa fa-usd" aria-hidden="true"></i><?php echo $list['price']['values']['amount']; ?></p>
      					</div>

      					</div>
      				</div>

      				</a>


      	<?php } ?>


      <?php } else { ?>

      <div class="alert alert-info">
      	Sorry..! No Attractions are available for this Search.
      </div>

      <?php } ?>

      <script>

      	$(document).ready(function(){
      		$(".input-21f").rating();
      	});

      </script>

    </div>
