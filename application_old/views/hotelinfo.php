<?php $this->load->view('includes/innersearch'); ?>

<?php if(count($hotel)){ ?>

<?php

//For Amenities.

          if($hotel['BREAK_TYPE']=='C')
           {    
                $type='(Continental)';
           }
          else if($hotel['BREAK_TYPE']=='B')
           {
                $type='(Buffet)';
           }
           else if($hotel['BREAK_TYPE']=='F')
           {
                $type='(Full American)';
           }    
           else
           {
                $type='';
           }  


            $new_array = array();
            $cnt=0;
            $newlist=$hotel;
            unset($newlist['BREAK_TYPE']);
            unset($newlist['id']);


            foreach ($newlist as $key => $value) 
            {   
                // echo "<pre>";
              //  print_r($newlist);die;
                //if ($value != 'N' && strlen($value)==1 && !ctype_digit($value)) 
                if ($value == 'Y' || $value == 'y' || $value == ' Y'|| $value == ' y') 
                {
                   
                    $new_array[$key] = $value;
                }
             }
             $c=0; if(count($new_array))
              {
                $keys=array_keys($new_array); 
              } 
              else
              {
                $keys=array();
              }

//For Facilities.

           if(count($facilities))
            {  
                $new_array1 = array();
                $cnt=0;
                $newlist1=$facilities;

                foreach ($newlist1 as $key => $value1) 
                {   

                    // echo "<pre>";
                  //  print_r($newlist);die;
                     //print_r($facilities);die; 
                     if ($value1 == 'Y' || $value1 == 'y' || $value == ' Y'|| $value1 == ' y') 
                    {
                        $new_array1[$key] = $value1;

                    }
                 }
                 $c=0; if(count($new_array1))
                  {
                    $keys1=array_keys($new_array1); 
                  } 
                  else
                  {
                    $keys1=array();
                  }  
            }
            else{
             $keys1=array();
            }            
             
?>

<div class="container-fluid">
<hr />
</div>
<div class="container image-gallery">
	<div class="col-md-8">
    	<div class="col-md-4">
            <?php if($hotel['image']){ ?>
        	<img src="<?php echo site_url('userfiles/images/small').'/'.$hotel['image'] ?>" width="100%" />
            <?php } else { ?>
            <img src="<?php echo site_url('assets/images/no-image.jpg') ?>" width="100%" />
            <?php } ?>
        </div>
        <div class="col-md-8">
        	<h4><?php echo $hotel['PROPNAME']; ?></h4>
            <p> <?php 

                  echo $hotel['PROPADD1'].', '.$hotel['PROPCITY'].', '.$hotel['PROPPOSTCODE'].', '.$hotel['PROPCOUNTRY'].'.';

               ?></p>

               <?php
                  if($hotel['PROP_LOC']=='A')
                  {
                    $loc='Airport';
                  }
                  else if($hotel['PROP_LOC']=='D')
                  {
                    $loc='Downtown/City Center';
                  }
                  else if($hotel['PROP_LOC']=='S')
                  {
                    $loc='Suburb';
                  }
                  else if($hotel['PROP_LOC']=='R')
                  {
                    $loc='Rural';
                  }
                  else if($hotel['PROP_LOC']=='T')
                  {
                    $loc='Resort';
                  }
                  else
                  {
                    $loc='';
                  }

                ?>
                <?php if($loc!=''){ ?>
                   <p><img src="<?php echo site_url('assets/images/location.png') ?>" />  <?php echo "This Property is Near ".$loc."."; ?></p>
               <?php } ?>
        </div>
    </div>
    <?php if(isset($_SESSION['checkin']) && $_SESSION['checkin']!=''){ 
    
      $checkinrate=$this->Hotels_fm->getSeasonPriceFromCheckin($_SESSION['checkin'],$hotel['id']);
      $j=$checkinrate[0];
      $ses1=$checkinrate[1];
      
    ?>
    <div class="col-md-4 text-center">
         <h2 class="rate"><?php echo $ses1['LRA_S'.$j.'_RT1_SGL'].' '.$hotel['RATE_CURR']; ?></h2>
          <span class="rate-span">(Rate Per Night)</span>
      </div>
    <?php 
    }
    else
    {
    ?>
      <div class="col-md-4 text-center">
         <h2 class="rate"><?php echo $hotel['LRA_S1_RT1_SGL'].' '.$hotel['RATE_CURR']; ?></h2>
          <span class="rate-span">(Rate Per Night)</span>
      </div>

    <?php } ?>
    
</div>


<?php if($hotel['PROPOVERVIEW']!=''){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
    <div class="col-md-12">
        <h3>OVERVIEW</h3>
    </div>
    <div class="col-md-12 additional">
       <p><?php echo $hotel['PROPOVERVIEW']; ?></p>
    </div>
   
</div>
<?php } ?>


<?php if(count($keys)){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
    <div class="col-md-12">
        <h3>AMENITIES</h3>
    </div>
    <div class="col-md-12">
        <ul class="amenities nopadding">
                <?php for($i=0;$i<count($keys);$i++){ ?>
                <li style="color:#B6A56F"><span style="color:#000;"><i class="fa fa-circle"></i>
                <?php if($keys[$i]=='Breakfast'){ echo $keys[$i].$type; } else { echo $keys[$i]; } ?></span></li>
                <?php } ?>
                </ul>
    </div>
   
</div>
<?php } ?>


<?php  if(count($keys1)){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
    <div class="col-md-12">
        <h3>FACILITIES</h3>
    </div>
    <div class="col-md-12">
        <ul class="amenities nopadding">
                <?php for($i=0;$i<count($keys1);$i++){ ?>
                <li style="color:#B6A56F"><span style="color:#000;"><i class="fa fa-circle"></i>
                <?php echo str_replace("=",',',$keys1[$i]);  ?></span></li>
                <?php } ?>
                </ul>
    </div>
   
</div>
<?php } ?>



<?php if(count($facilities) && $facilities['ADDL_SVC_ES']!=''){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
    <div class="col-md-12">
        <h3>ADDITIONAL SERVICES / AMENITIES / FACILITIES</h3>
    </div>
    <div class="col-md-12 additional">
       <p><?php echo $facilities['ADDL_SVC_ES']; ?></p>
    </div>
   
</div>
<?php } ?>
<?php if(isset($_SESSION['checkin']) && $_SESSION['checkin']!=''){ ?>
<div class="container-fluid space-height">

</div>

<div class="container image-gallery">
    <div class="col-md-12">
        <h3>RATES</h3>
    </div>

<?php

    $checkinrate=$this->Hotels_fm->getSeasonPriceFromCheckin($_SESSION['checkin'],$hotel['id']);
    $i=$checkinrate[0];
    $ses=$checkinrate[1];
?>

<div class="col-md-12">
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Applicable From<br/>(yyyy-mm-dd)</th>
                <?php if($ses['LRA_S'.$i.'_RT1_SGL']){ ?>
                  <th>Standard Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>

                <?php if($ses['LRA_S'.$i.'_RT1_DBL']){ ?>
                  <th>Standard Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>
                
                <?php if($ses['LRA_S'.$i.'_RT2_SGL']){ ?>  
                   <th>Deluxe/King Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>

                <?php if($ses['LRA_S'.$i.'_RT2_DBL']){ ?>     
                  <th>Deluxe/King Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>

                <?php if($ses['LRA_S'.$i.'_RT3_SGL']){ ?>   
                  <th>Suite/Executive Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>

                <?php if($ses['LRA_S'.$i.'_RT3_DBL']){ ?>   
                  <th>Suite/Executive Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
          <tr>
              <td><?php echo $ses['SEASON'.$i.'START'].' TO '.$ses['SEASON'.$i.'END'];  ?></td>
             
              <?php if($ses['LRA_S'.$i.'_RT1_SGL']){ ?>
                <td><?php if($ses['LRA_S'.$i.'_RT1_SGL']){ echo $ses['LRA_S'.$i.'_RT1_SGL']; } else { echo 'N/A'; } ?></td>
              <?php } ?>

              <?php if($ses['LRA_S'.$i.'_RT1_DBL']){ ?>
              <td><?php if($ses['LRA_S'.$i.'_RT1_DBL']){ echo $ses['LRA_S'.$i.'_RT1_DBL']; } else { echo 'N/A'; } ?></td>
              <?php } ?>

              <?php if($ses['LRA_S'.$i.'_RT2_SGL']){ ?>  
                <td><?php if($ses['LRA_S'.$i.'_RT2_SGL']){ echo $ses['LRA_S'.$i.'_RT2_SGL']; } else { echo 'N/A'; } ?></td>
              <?php } ?>   

              <?php if($ses['LRA_S'.$i.'_RT2_DBL']){ ?>  
               <td><?php if($ses['LRA_S'.$i.'_RT2_DBL']){ echo $ses['LRA_S'.$i.'_RT2_DBL']; } else { echo 'N/A'; } ?></td>
              <?php } ?>

              <?php if($ses['LRA_S'.$i.'_RT3_SGL']){ ?>
                <td><?php if($ses['LRA_S'.$i.'_RT3_SGL']){ echo $ses['LRA_S'.$i.'_RT3_SGL']; } else { echo 'N/A'; } ?></td>
              <?php } ?>

              <?php if($ses['LRA_S'.$i.'_RT3_DBL']){ ?>  
                <td><?php if($ses['LRA_S'.$i.'_RT3_DBL']){ echo $ses['LRA_S'.$i.'_RT3_DBL']; } else { echo 'N/A'; } ?></td>
               <?php } ?>
          </tr>
      </tbody>

    </table>
  </div>
</div>
</div>


<?php } else { ?>


<?php if(isset($end) && $end>0){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
    <div class="col-md-12">
        <h3>RATES</h3>
    </div>
<?php for($i=1;$i<=$end;$i++)
{

$ses=$this->Hotels_fm->getSeasonPrice($i,$hotel['id']);



 ?>

<div class="col-md-12">

<div class="table-responsive">
<table class="table table-bordered table-striped">
 <thead>
    <tr>
        <th>Applicable From<br/>(yyyy-mm-dd)</th>
        <?php if($ses['LRA_S'.$i.'_RT1_SGL']){ ?>
          <th>Standard Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT1_DBL']){ ?>
          <th>Standard Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>
        
        <?php if($ses['LRA_S'.$i.'_RT2_SGL']){ ?>  
           <th>Deluxe/King Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT2_DBL']){ ?>     
          <th>Deluxe/King Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT3_SGL']){ ?>   
          <th>Suite/Executive Room<br/>(Single occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT3_DBL']){ ?>   
          <th>Suite/Executive Room<br/>(Double occupancy)<br/>(<?php echo $ses['RATE_CURR']; ?>)</th>
        <?php } ?>
    </tr>
 </thead>
 <tbody>
    <tr>
        <td><?php echo $ses['SEASON'.$i.'START'].' TO '.$ses['SEASON'.$i.'END'];  ?></td>
       
        <?php if($ses['LRA_S'.$i.'_RT1_SGL']){ ?>
          <td><?php if($ses['LRA_S'.$i.'_RT1_SGL']){ echo $ses['LRA_S'.$i.'_RT1_SGL']; } else { echo 'N/A'; } ?></td>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT1_DBL']){ ?>
        <td><?php if($ses['LRA_S'.$i.'_RT1_DBL']){ echo $ses['LRA_S'.$i.'_RT1_DBL']; } else { echo 'N/A'; } ?></td>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT2_SGL']){ ?>  
          <td><?php if($ses['LRA_S'.$i.'_RT2_SGL']){ echo $ses['LRA_S'.$i.'_RT2_SGL']; } else { echo 'N/A'; } ?></td>
        <?php } ?>   

        <?php if($ses['LRA_S'.$i.'_RT2_DBL']){ ?>  
         <td><?php if($ses['LRA_S'.$i.'_RT2_DBL']){ echo $ses['LRA_S'.$i.'_RT2_DBL']; } else { echo 'N/A'; } ?></td>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT3_SGL']){ ?>
          <td><?php if($ses['LRA_S'.$i.'_RT3_SGL']){ echo $ses['LRA_S'.$i.'_RT3_SGL']; } else { echo 'N/A'; } ?></td>
        <?php } ?>

        <?php if($ses['LRA_S'.$i.'_RT3_DBL']){ ?>  
          <td><?php if($ses['LRA_S'.$i.'_RT3_DBL']){ echo $ses['LRA_S'.$i.'_RT3_DBL']; } else { echo 'N/A'; } ?></td>
         <?php } ?>
    </tr>
     
    </tbody>
</table>
</div>
</div>

<?php } ?>      
</div>
<?php }  ?>

<?php } ?>



<?php if(count($gallery)){ ?>
<div class="container-fluid space-height">

</div>
<div class="container image-gallery">
	<div class="col-md-12">
    	<h3>OUR GALLERY</h3>
    </div>
    <div class="popup-gallery">
    
    <?php foreach ($gallery as $glist) 
    {

    ?>
    <div class="col-md-3 toppadding">
     <a href="<?php echo site_url('userfiles/gallery').'/'.$glist['image'] ?>" title="The Cleaner"><img src="<?php echo site_url('userfiles/gallery/small').'/'.$glist['image'] ?>" width="100%" /></a>
    </div>
    <?php } ?>
    </div>
</div>

<div class="container-fluid space-height">

</div>
<?php } ?>

<div class="container image-gallery" id="book">
	<div class="col-md-12">
    	<div id="agent-form">
    	<h3>PLEASE FILL BELOW FORM TO CONTACT OUR AGENT</h3>
        
        <div class="alert alert-success" id="alr" style="display:none;">
            Thanks for inquiry. We will get back to you soon.
        </div>

         <form class="search-form clearfix" id="form2" onsubmit="return sendInquiry();">  		
            	<div class="col-md-4 nopadding inner-addon right-addon">
                     <input type="text" name="name" class="form-control" placeholder="Name" required maxlength="100" data-toggle="tooltip" data-placement="top" title="Your Name"/>
                    <input type="hidden" id="flag" value="<?php echo $flag; ?>"/>
                     <input type="hidden" name="id" id="hotelid" value="<?php echo $hotel['id']; ?>"/>
				</div>
                
                <div class="col-md-4 nopadding inner-addon right-addon">
                    <input type="email" name="email" class="form-control" placeholder="E-Mail" required maxlength="100"  data-toggle="tooltip" data-placement="top" title="Your Email"/>
				</div>
                
                <div class="col-md-4 nopadding inner-addon right-addon">
                    <input type="text" name="phone" class="form-control" placeholder="Contact No" required maxlength="20"  data-toggle="tooltip" data-placement="top" title="Your Contact Number"/>
				</div>
                
                <div class="col-md-4 nopadding inner-addon right-addon">
                    <input type="text" class="form-control example1 from1" id="checkin" name="checkin" placeholder="Check In" value="<?php if(isset($_SESSION['checkin']) && $_SESSION['checkin']!=''){ echo $_SESSION['checkin']; } ?>" required maxlength="10"  data-toggle="tooltip" data-placement="top" title="Checkin"/>
				</div>
                
                <div class="col-md-4 nopadding inner-addon right-addon">
                    <input type="text" class="form-control example1 to1" id="checkout" name="checkout" placeholder="Check Out" value="<?php if(isset($_SESSION['checkout']) && $_SESSION['checkout']!=''){ echo $_SESSION['checkout']; } ?>" maxlength="10" required  data-toggle="tooltip" data-placement="top" title="Checkout"/>
				</div>
                
                <div class="col-md-4 nopadding inner-addon room right-addon">
                	<input type="number" class="form-control" name="rooms" min="1" max="200" size="4" placeholder="Rooms" required pattern="\d*"  data-toggle="tooltip" data-placement="top" title="No. of Rooms"/>
                     <input type="number" class="form-control" name="adults" min="1" max="200" size="4" placeholder="Adults" required pattern="\d*"  data-toggle="tooltip" data-placement="top" title="No. Of Adult/s"/>
                      <input type="number" class="form-control" name="children" min="0" max="200" size="4" placeholder="Children" required pattern="\d*"   data-toggle="tooltip" data-placement="top" title="No. Of Children"/>
                </div>
                
                <div class="col-md-8 nopadding inner-addon right-addon">
                    
                    <textarea class="form-control" style="resize:none;" name="message" placeholder="Message"  data-toggle="tooltip" data-placement="top" title="Your Message"></textarea>
				</div>
                <div class="col-md-12 agent">
                 <button type="submit">Submit</button>
                 </div>
                </form>
                </div>
    </div>
   
</div>

<script type="text/javascript">
    
$(document).ready(function(){
    if($("#flag").val()==1)
    {
        $('html, body').animate({
            scrollTop: $("#book").offset().top
        }, 1000);
    }
});

function sendInquiry()
{
    if($("#form2")[0].checkValidity())
    {
        var form2=$("#form2");
        $.ajax({
                type:'POST',
                url:'<?php echo site_url("sendInquiry") ?>',
                data:form2.serialize(),
                success:function(data)
                {
                    $("#alr").show().delay(4000).fadeOut();
                    $("#form2")[0].reset();
                }
            });
            return false;
        
    }
    return false;
}


</script>
<?php } else { ?>
<div class="alert alert-info">
    
    Something goes wrong...

</div>
<?php } ?>