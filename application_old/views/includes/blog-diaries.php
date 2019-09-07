<?php if(count($blogs)){ ?>


<div class="container">

	<div class="row">
    <div class="col-md-12 travel-blogs">
            <h2>Blog</h2>
            <p>We don’t just stop at being your online travel itinerary maker. As frequent wanderers and travel enthusiasts, we bring together intelligent travel pieces that are sure to pique your interest. Encompassing a broad spectrum of topics, our blogs are your window to the world, giving you ample of destinations you can add to your travel bucket list. Be it the basics of trip planning, quick guides to international train routes or the best places you should visit, we have it all covered right here.</p>
        </div>
        <?php foreach($blogs as $list){ ?>

	    	<div class="col-md-4">
	        	<div class="grid">
					<figure class="effect-layla">
						<img src="<?php echo $list['image'] ?>" alt="img06" width="100%"/>
						<figcaption>
							<!--<h2>Classic <span> Europe</span></h2>
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
							<h2><span><?php echo $list['title'] ?></span></h2>
							<a href="<?php echo site_url('blog').'/'.$list['post_name'] ?>" target="_blank">View more</a>
						</figcaption>
					</figure>
				</div>
	        </div>

        <?php } ?>

    </div>



    <div class="row buttonwrapper">
    	<div class="col-md-12 text-center">
    		<a href="<?php echo site_url('blog'); ?>" class="link-button purple" target="_blank">View All<i class="fa fa-eye" aria-hidden="true"></i></a>
        </div>
    </div>

</div>
 <?php } ?>
