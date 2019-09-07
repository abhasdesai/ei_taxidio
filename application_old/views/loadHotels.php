<?php if(count($hotels)){ ?>
<div class="titles">
	<div class="col-md-4">
    	 <?php if(isset($order) && $order!=''){ ?>
        
        <h4>HOTELS
        <i class="fa fa-long-arrow-up" id="up1" style="<?php if($order=='ASC'){ echo'display:none'; } ?>" ></i>
        <i class="fa fa-long-arrow-down" id="down1" style="<?php if($order=='DESC'){ echo 'display:none'; } ?>">
        </i>
        </h4>

        <?php } else { ?>

        <h4>HOTELS<i class="fa fa-long-arrow-up" id="up1"></i><i class="fa fa-long-arrow-down" id="down1" style="display:none;"></i></h4>

        <?php } ?>
    </div>
    <div class="col-md-6">
    	<h4>AMENITIES</h4>
    </div>
	<div class="col-md-2">
    	<h4>RATE</h4>
    </div>
	</div>

    <?php foreach ($hotels as $list) { 

           $rates=array();
          if(isset($checkin) && $checkin!='')
          {
            
            $myrate=0;
            $rates=array($list['SEASON1START'],$list['SEASON1END'],$list['SEASON2START'],$list['SEASON2END'],$list['SEASON3START'],$list['SEASON3END'],$list['SEASON4START'],$list['SEASON4END'],$list['SEASON5START'],$list['SEASON5END']);

            $chunk=array_chunk($rates,2);
            $newchunk=array_filter(array_merge(array(0), $chunk));
          

            for($i=1;$i<=5;$i++)
            { 
              if($checkin>=$newchunk[$i][0] && $checkin<=$newchunk[$i][1])
              {
                 break;
              }
            } 
            if($i>5)
            {
              $level='LRA_S1_RT1_SGL';  
            }
            else
            {
              $level='LRA_S'.($i).'_RT1_SGL';  
            }
            $hrate=$this->Hotels_fm->getRate($level,$list['slug']);
         }
          else
          {
              $hrate=$list['LRA_S1_RT1_SGL'];

          }

          //echo $myrate.'->'.$level.'->'.$hrate.'<br/>';
    
          

           if($list['BREAK_TYPE']=='C')
           {    
                $type='(Continental)';
           }
          else if($list['BREAK_TYPE']=='B')
           {
                $type='(Buffet)';
           }
           else if($list['BREAK_TYPE']=='F')
           {
                $type='(Full American)';
           }    
           else
           {
                $type='';
           }  

            $new_array = array();
            $cnt=0;
            $newlist=$list;
            unset($newlist['BREAK_TYPE']);
            
            foreach ($newlist as $key => $value) 
            {
                //if ($value != 'N' && strlen($value)==1 && !ctype_digit($value)) 
                if ($value == 'Y' || $value == 'y' || $value == ' Y'|| $value == ' y') 
                {
                        $new_array[$key] = $value;
                }


            }
            //echo "<pre>";print_r($new_array);die;


        ?> <div class="hotel-search">
         <div class="col-md-4 hotel-desc">
        <div class="col-md-5">

            

             <?php if($list['image']){ ?>
              <img src="<?php echo site_url('userfiles/images/small').'/'.$list['image'] ?>" width="100%" />
            <?php } else { ?>
              <a href="<?php echo site_url('hotelinfo').'/'.$list['slug']; ?>"><img src="<?php echo site_url('assets/images/no-image.jpg'); ?>" width="100%" /></a>
            <?php } ?>


           
        </div>
        <div class="col-md-7">
            <h4 class="hotel-name"><a class="linkclr" href="<?php echo site_url('hotelinfo').'/'.$list['slug']; ?>"><?php echo $list['PROPNAME']; ?></a></h4>
            <p>
              <?php 
                  $add='';
                    if($list['PROPADD1'])
                    {
                      $add.=$list['PROPADD1'].', ';
                    }
                    if($list['PROPCITY'])
                    {
                      $add.=$list['PROPCITY'].', ';
                    }
                    if($list['PROPPOSTCODE'])
                    {
                      $add.=$list['PROPPOSTCODE'].', ';
                    }
                    if($list['PROPSTATEPROV'])
                    {
                      $add.=$list['PROPSTATEPROV'].', ';
                    }
                    if($list['PROPCOUNTRY'])
                    {
                      $add.=$list['PROPCOUNTRY'];
                    }

                    echo $add;
                  

               ?>
          </p>
        </div>
    </div>
    <div class="col-md-6" >
        
    <?php $c=0; if(count($new_array)){ 

        $keys=array_keys($new_array); ?>

         
       
                <?php if(isset($keys[0]) || isset($keys[1])){ ?>
                 <div class="col-md-6 amenities1">
                        <ul>
                            <?php if(isset($keys[0])){ ?>
                                <li style="color:#B6A56F"><span style="color:#000;">
                                  <?php 
                                      if($keys[0]=='Breakfast')
                                      {
                                        echo $keys[0].' '.$type;
                                      }
                                      else
                                      {
                                        echo $keys[0];
                                      }
                                   ?></span></li>
                                    
                            <?php } ?>
                              <?php if(isset($keys[1])){ ?>
                                <li style="color:#B6A56F"><span style="color:#000;"><?php 

                                      if($keys[1]=='Breakfast')
                                      {
                                        echo $keys[1].' '.$type;
                                      }
                                      else
                                      {
                                        echo $keys[1];
                                      }

                                 ?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                    
                <?php if(isset($keys[2]) || isset($keys[3])){ ?>
                 <div class="col-md-6 amenities1">
                        <ul>
                             <?php if(isset($keys[2])){ ?>
                                <li style="color:#B6A56F"><span style="color:#000;"><?php 

                                     if($keys[2]=='Breakfast')
                                      {
                                        echo $keys[2].' '.$type;
                                      }
                                      else
                                      {
                                        echo $keys[2];
                                      }

                                 ?></span></li>
                            <?php } ?>
                              <?php if(isset($keys[3])){ ?>
                                <li style="color:#B6A56F"><span style="color:#000;"><?php 

                                    if($keys[3]=='Breakfast')
                                      {
                                        echo $keys[3].' '.$type;
                                      }
                                      else
                                      {
                                        echo $keys[3];
                                      }

                                 ?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
          
   
    
       <div class="col-md-12 nopadding">
            <a class="linkclr" href="<?php echo site_url('hotelinfo').'/'.$list['slug']; ?>" class="view-all">View All</a>
        </div>
         <?php } ?>
    </div>
    <div class="col-md-2 text-center rate-col">
        <h2 class="rate"><?php echo $hrate.' '.$list['RATE_CURR']; ?></h2>
        <span class="rate-span">(Rate Per Night)</span>
        <a href="<?php echo site_url('hotelinfo').'/'.$list['slug'].'/book'; ?>" ><button type="button" value="Submit">BOOK NOW</button></a>
    </div>
    </div>
 
    <?php } ?>

<div class="pagination col-md-12 text-center">
<?php echo $pagination; ?>
</div>
<?php } else { ?>
<br/><br/><br/>
<div class="alert alert-info">
  Ooop..! No search found
</div>

<?php } ?>
<script>
  
$(document).ready(function(){

  var total='<?php echo $trows ?>';
  $("#hotelcnt").html(n(total));

});

function n(n) {
  return n > 9 ? "" + n : "0" + n;
}

</script>
<script>

var hotel='<?php echo $hotel; ?>';
var nearby='<?php echo $nearby; ?>';
var checkin='<?php echo $checkin; ?>';
var checkout='<?php echo $checkout; ?>';


$("#up1").click(function(){
                $("#prop").val('ASC');
                $("#up1").show();
                $("#down1").hide();
                var prop=$("#prop").val();
                 $.post('<?php echo site_url("searchedhotels_ajax") ?>','hotel='+hotel+'&nearby='+nearby+'&checkin='+checkin+'&checkout='+checkin+'&prop='+prop,function(data){
                    $("#bindajax").html(data);
                 });

            });

            $("#down1").click(function(){
                $("#prop").val('DESC');
                $("#up1").hide();
                $("#down1").show();
                var prop=$("#prop").val();
                 $.post('<?php echo site_url("searchedhotels_ajax") ?>','hotel='+hotel+'&nearby='+nearby+'&checkin='+checkin+'&checkout='+checkin+'&prop='+prop,function(data){
                    $("#bindajax").html(data);
                 });

            });  
</script>