<div class="container text-center">
	<div class="row">
    	<div class="col-md-12">
            <h2>Top Trending Destinations</h2>
            <p>You have been contemplating a vacation since a while now, but don’t know where to go. Here’s where Taxidio steps in as the ideal holiday itinerary planner that creates an auto-generated list of the most popular destinations in the world. You can view the trending travel hotspots of the season to get some inspiration for your next trip and create an itinerary to your liking.</p>
        </div>
    </div>
    <div class="row destinations">
    	<div class="col-md-12 margin-bottom-30 frame-yellow no-padding">

            <?php if(count($blueprints)){ ?>

                <?php foreach($blueprints as $key=>$list){ ?>

                      <?php if($key+1==1 || $key+1==4){ ?>

                      <div class="col-md-12 margin-bottom-30 frame-yellow no-padding">
                        <div class="col-md-6 padding-right-half">
                            <a href="<?php echo site_url('country').'/'.$list['slug'];?>"><img src="<?php echo site_url('userfiles/travelblueprint').'/'.$list['image'] ?>" width="100%" alt="" />
                            <h3><?php echo $list['country_name'] ?></h3></a>
                        </div>

                      <?php }else if($key+1==3){ ?>

                      <div class="col-md-12 margin-bottom-30 frame-white">
            <a href="<?php echo site_url('country').'/'.$list['slug'];?>"><img src="<?php echo site_url('userfiles/travelblueprint').'/'.$list['image'] ?>" width="100%" alt="" />
                            <h3><?php echo $list['country_name'] ?></h3></a>
        </div>

                      <?php }else{ ?>

                       <div class="col-md-6 padding-left-half">
                 <a href="<?php echo site_url('country').'/'.$list['slug'];?>"><img src="<?php echo site_url('userfiles/travelblueprint').'/'.$list['image'] ?>" width="100%" alt="" />
                            <h3><?php echo $list['country_name'] ?></h3></a>
            </div>
        </div>

                      <?php } ?>

                <?php } ?>

            <?php } ?>




    </div>
</div>
