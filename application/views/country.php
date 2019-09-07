<?php 
//echo "<pre>";print_r($country);
?>
<div class="container-fluid single-country-image no-padding">
	
		<div class="col-md-12 no-padding city-img">
			<figure class="tint">
            <?php if($country['countrybanner']!='' && file_exists(FCPATH.'userfiles/countries/banner/'.$country['countrybanner'])){ ?>

                <img src="<?php echo site_url('userfiles/countries/banner').'/'.$country['countrybanner'] ?>" alt="" width="100%" height="200px">

            <?php }else{ ?>

         

            <img src="<?php echo site_url('assets/images/countrynoimage.jpg') ?>" alt="" width="100%" height="200px"/>

            <?php } ?>
      			
    		</figure>
		</div>
		<div class="overlay-city-img"></div>
	<div class="container country-details">
    	<div class="row">
        	<div class="col-md-12 text-center">
            	<h2 class="country-name1"><?php echo $country['country_name'];?></h2>
            </div>
        </div>
    </div>
    </div>
    
    
    <div class="container">
    	<div class="row">
    		<div class="col-md-12 country_conclusion">
    			<h2 class="about-country">Description</h2>
    			<p class="country-para"><?php echo $country['country_conclusion'];?></p>
    		</div>
    	</div>
    	
    	<div class="row">
    		
    		<div class="col-md-6">
    			<h3 class="known-for">Known For</h3>
                <?php if(count($tags)){ ?>
        			<ul class="tags">
    					<?php foreach($tags as $list){ ?>
                            <li><a href="javascript:void(0);"><?php echo $list['tag_name']; ?></a></li>
                        <?php } ?>
    				</ul>
                <?php } ?>
    		</div>
    		<?php
    		
				$country_neighbours = explode(",", $country['country_neighbours']);
				
    		?>
    		<div class="col-md-6">
    			<h3 class="known-for">Neighbour Countries</h3>
    			<ul class="neighbour-countries">
					<?php foreach($country_neighbours as $list){ ?>
    				<li class="link-button"><?php echo $list;?></li>
    				<?php } ?>
    			</ul>
    			
    			
    		</div>
    		
    	</div>

        <div class="row">
            
            <div class="col-md-12">
                <h3 class="known-for">Cities Covered</h3>
                <?php if(count($cities)){ ?>
                    <ul class="tags">
                        <?php foreach($cities as $list){ ?>
                            <?php /* ?>
                            <li><a href="<?php echo site_url('city').'/'.$list['slug'] ?>"><?php echo $list['city_name']; ?></a></li><?php */ ?>
                            <li><a href="javascript:void(0);"><?php echo $list['city_name']; ?></a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <?php
            
                $country_neighbours = explode(",", $country['country_neighbours']);
                
            ?>
                       
        </div>
    	
    </div>
