<?php if(count($hotels)){ ?>
<input type="hidden" id="postcityid" value="<?php echo $postcityid; ?>"/>
<?php foreach($hotels as $list){ ?>

<div class="single-hotel-wrapper">
  <div class="col-md-12 no-padding hotel-name">
      <h3><?php echo $list['hotel_name']; ?></h3>
    </div>
    <div class="col-md-3 padding-left-0">
      <a href="<?php echo $list['hotel_url'].'?aid=1219471'; ?>" class="" target="_blank"><img src="<?php echo $list['photo_url']; ?>" width="100%" alt="" class="img-responsive hotel-img"/></a>
        
    </div>




    <div class="col-md-5 text-center">
     
        <?php if($list['description']!=''){ ?>	
        	<p class="hotel-description"><?php echo $list['description']; ?></p>
    	<?php } ?>

    	<p class="hotel-address"><span>Address : </span><?php echo $list['address'].'<br/>'.$list['zip'].' '.$list['city_hotel']; ?></p>
        <?php if($list['latitude']!='' && $list['longitude']){ ?>
         
        <?php

         $url='http://www.google.com/maps/place/'.$list['latitude'].','.$list['longitude'].'/@'.$list['latitude'].','.$list['longitude'].',17z';

         ?>

         <a href="<?php echo $url; ?>" class="" target="_blank">Show On Map<i class="fa fa-map-marker" aria-hidden="true"></i></a> 


       
        
        <?php } ?>

       

		
	

    </div>

<div class="col-md-3 text-center reviews">
    	 <?php 

        	$price='';
          if($list['minrate']!='' && $list['maxrate']!='')
          {
            $price=$list['currencycode'].' '.number_format($list['minrate'],2).' - '.$list['currencycode'].' '.number_format($list['maxrate'],2);
          }
          else if($list['minrate']!='' && $list['maxrate']=='')
          {
            $price=$list['currencycode'].' '.number_format($list['minrate'],2); 
          }
          else if($list['minrate']=='' && $list['maxrate']!='')
          {
            $price=$list['currencycode'].' '.number_format($list['maxrate'],2); 
          } 
        	?>
        <?php if($list['minrate']!='' || $list['maxrate']!=''){ ?>	
        	<span class="price-for-hotels"><span><?php echo $price; ?></span></span>
    	<?php } ?>
        <a href="<?php echo $list['hotel_url'].'?aid=1219471'; ?>" class="link-button-small" target="_blank">Book Hotel<i class="fa fa-map-marker" aria-hidden="true"></i></a>
        
    </div>
</div>

<?php } ?>

<div class="col-md-12" align="center">
 <div class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
	<?php echo $pagination; ?>
 </div>
 </div>

 <script>

 $(document).ready(function(){

         var cityimage='<?php if(isset($citybanner) && $citybanner!=""){ echo $citybanner; }else{ echo ""; } ?>';
         if(cityimage!="")
         {
            var cityname='<?php if(isset($citynm)){ echo $citynm; } ?>';
            $("#cityb").attr("src",cityimage);
            $("#cityhotelnm").text("HOTELS IN "+cityname);
         }  

 });

 </script>


<?php } else { ?>

<div class="alert alert-info">
  Sorry..!We dont have any hotel recommendation for you.
</div>


<?php } ?>


