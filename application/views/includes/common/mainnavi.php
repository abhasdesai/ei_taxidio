<div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand dashboard-logo" href="<?php echo site_url(); ?>"><img src="<?php echo site_url('assets/images/logo.png') ?>" alt="Taxidio - The Where, Why & How Traveler"/>
    
    </a> 

    </div>

  <div id="navbar" class="navbar-collapse collapse">

    <ul class="nav navbar-nav navbar-right" itemscope itemtype="http://www.schema.org/SiteNavigationElement">
      <li class="dropdown active toggle-display" itemprop="name"><a href="#" itemprop="url" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Home <span class="sr-only">(current)</span></a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url() ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if(isset($webpage) && $webpage=='innerleft'){ echo 'active'; } ?>">Home</a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url('discover-taxidio') ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if(isset($page) && $page=='discover_taxidio'){ echo 'active'; } ?>">Discover Taxidio</a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url('destination') ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if(isset($webpage) && $webpage=='destination'){ echo 'active'; } ?>">Destinations</a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url('hotel-search-engine') ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if(isset($webpage) && $webpage=='hotels'){ echo 'active'; } ?>">Hotels</a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url('discount-attraction-tickets') ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if((isset($webpage) && $webpage=='allattractions') || (isset($main) && $main=='attractions_info') ){ echo 'active'; } ?>">Attractions</a></li>
      <li class="" itemprop="name"><a href="<?php echo site_url('blog') ?>" class="hvr-shutter-in-horizontal" target="_blank">Blog</a></li>
      <li class="hvr-shutter-in-horizontal" itemprop="name"><a href="<?php echo site_url('planned-itinerary'); ?>" itemprop="url" class="hvr-shutter-in-horizontal <?php if(isset($webpage) && ($webpage=='planneditineraries' || $webpage=='Forum')){ echo 'active'; } ?>">Planned Itinerary</a></li>


    </ul>
  </div>

</div>
