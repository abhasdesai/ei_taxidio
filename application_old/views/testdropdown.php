<!--
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>
-->
<!--<div class="container">-->
<ul class="nav nav-tabs">
  <li id="defaultactive" class="active">
    <a data-toggle="tab" href="#tab1"><i class="fa fa-search" aria-hidden="true"></i>RECOMMENDATION<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up"></span></a>
  </li>
  <li id="defaultinactive">
    <a data-toggle="tab" href="#tab2"><i class="fa fa-search" aria-hidden="true"></i>SEARCH<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up
  "></span></a>
</li>
</ul>
<div class="tab-content">
<div id="tab1" class="tab-pane fade in active">
  <?php echo form_open('country-recommendation',array('enctype'=>'multipart/form-data','method'=>'GET','role'=>'form','autocomplete'=>'on','class'=>'recform','id'=>'recommendationform')); ?>
  <div class="main-search">
    
    <div class="row margin-0">
      <?php /* ?>
      <div class="col-md-3">
        <div class="form-group">
          <input type="text" class="form-control" name="budget" placeholder="Budget Per Night">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <select class="form-control" name="accomodation">
            <option>Accomodation</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          
          <div class="col-sm-12 col-md-12 no-padding">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="triptype" data-title="Domestic">Domestic</a>
                <a class="btn btn-primary btn-sm notActive" data-toggle="triptype" data-title="International">International</a>
              </div>
              <input type="hidden" name="triptype" id="triptype">
            </div>
          </div>
        </div>
      </div>
      
      <div class="row margin-0">
        <div class="col-md-3">
          <div class="form-group">
            <input type="text" name="start_city" class="form-control searchTextField typeaheadkeywords" placeholder="Start City" autocomplete="off" id="typeaheadkeywords">
          </div>
        </div>
      </div>
      <?php */ ?>

       <div class="col-md-3 five-column">
        <div class="form-group">
          <input type="text" name="start_city" class="form-control searchTextField typeaheadkeywords validate[required]" placeholder="Starting Point" autocomplete="off" id="typeaheadkeywords" data-prompt-position="topLeft" data-errormessage-value-missing="You left me out!" onkeypress="$(this).validationEngine('hide');" value="<?php echo set_value('start_city') ?>">
         </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          
          <div class="col-sm-12 col-md-12 no-padding domestic">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm notActive" data-toggle="isdomestic" data-title="1">Domestic</a>
                <a class="btn btn-primary btn-sm active" data-toggle="isdomestic" data-title="0">International</a>
              </div>
              <input type="hidden" name="isdomestic" id="isdomestic" value="0">
            </div>
          </div>
        </div>
      </div>

       <?php if(count($traveltime)){ ?>

      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" name="traveltime" data-prompt-position="topLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');">
            <option value="">Length Of Journey (Hours)</option>
            <?php foreach($traveltime as $tlist){ ?>
              <option value="<?php echo $tlist['travel_time']; ?>" <?php echo set_select('traveltime', $tlist['travel_time']); ?>><?php echo $tlist['travel_time']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <?php } ?>

       <?php if(count($days)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" name="days" data-prompt-position="topLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');">
            <option value="">No Of Days</option>
            <?php foreach($days as $list){ ?>
              <option value="<?php echo $list['days_range']; ?>" <?php echo set_select('days', $list['days_range']); ?>><?php echo $list['days_range']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>

      <div class="col-md-3 five-column">
        <div class="form-group">
          <input class="future form-control validate[required]" name="start_date" id="dp1" type="text" placeholder="Date of Departure" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" autocomplete="off" value="<?php echo set_value('start_date') ?>">
        </div>
      </div>

       <input type="hidden" id="rectoken" name="token" value="<?php echo time(); ?>"/>


       <?php if(count($weather)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" id="selectweather"  name="weather" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');" multiple>
           <?php foreach($weather as $wlist){ ?>
              <option value="<?php echo str_replace(array(' '),array(''),$wlist['weather']); ?>" <?php echo set_select('weather', str_replace(array(' '),array(''),$wlist['weather'])); ?>>
              <?php
                    $plus = substr($wlist['weather'],-1);
                    if($plus=='+')
                    {
                        $startweather=$wlist['weather'];
                        $endweather='';
                        echo $startweather.' &deg;c';
                    }
                    else
                    {
                        $weather = explode('-',$wlist['weather']);
                        if(count($weather)==4)
                        {
                          $startweather=-$weather[1];
                          $endweather=-$weather[3];
                        }
                        else if(count($weather)==3)
                        {
                          $startweather=-$weather[1];
                          $endweather=$weather[2];
                        }
                        else
                        {
                          $startweather=$weather[0];
                          $endweather=$weather[1];
                        }
                         echo $startweather.' &deg;c - '.$endweather.' &deg;c';
                    }
                   
              ?>
            

              </option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>

       <?php if(count($accomodation)){ ?>

      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" name="accomodation" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');">
            <option value="">Accomodation Type</option>
            <?php foreach($accomodation as $alist){ ?>
              <option value="<?php echo $alist['id']; ?>" <?php echo set_select('accomodation', $alist['id']); ?>><?php echo $alist['accomodation_type']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <?php } ?>



      <?php if(count($budget)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" id="selectbudget" name="budget" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');" multiple>
            <?php foreach($budget as $list){ ?>
              <option value="<?php echo str_replace('$','',$list['budget']); ?>" <?php echo set_select('budget', str_replace('$','',$list['budget'])); ?>><?php echo $list['budget']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>

      <input type="hidden" name="ocode" id="code" value="<?php echo set_value('ocode') ?>"/>
       

      <?php /* ?> 
      <div class="col-md-3 five-column reco">
        <div class="form-group">
           <div class="col-sm-12 col-md-12 no-padding">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="triptype" data-title="1"> Senior Citizen</a>
              </div>
              <input type="hidden" name="triptype" id="triptype" value="1">
            </div>
          </div>
        </div>
      </div>
    <?php */ ?>
     
     
   
      
     

      
          
     

      

      
      
      
    </div>
    
  </div>
  
  <div class="col-md-12 text-center form-divider">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" />
  </div>
  
  <?php if(count($tags)){ ?>
  <div class="row filter-wrap margin-0">
    <ul class="list list--horizontal-filter slider responsive">
      <?php foreach($tags as $tlist){ 
        
              if(strpos($tlist['tag_name'],'&')>0)
              {
                  $tagnm=str_replace('&','&<br/>',$tlist['tag_name']);
              }
              else
              {
                  $tagnm=$tlist['tag_name'];
              }
        ?>
      <li class="custom-radio--label">
        <input name="tags[]" id="<?php echo 'r'.$tlist['id'] ?>" value="<?php echo $tlist['tag_name']; ?>" type="checkbox">
        <label for="<?php echo 'r'.$tlist['id'] ?>"><?php echo $tagnm; ?></label>
      </li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  
  <div class="row margin-0">
    <div class="col-md-3 float-right">
      <div class="form-group">
        <input type="button" id="recommendationbtn" class="link-button purple" value="Begin Your Journey"/> 
      </div>
    </div>
  </div>
  
</form>
</div>
<div id="tab2" class="tab-pane fade">
<?php echo form_open('cityAttractions',array('enctype'=>'multipart/form-data','method'=>'GET','role'=>'form','autocomplete'=>'on','class'=>'recform','id'=>'recformsubmit')); ?>
  <div class="main-search">
    <!--<div class="row margin-0">
      <div class="col-md-3">
        <div class="form-group">
          <input type="text" class="form-control" id="usr" placeholder="Location">
        </div>
      </div>
    </div>-->
    <div class="row margin-0">
      <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
          <input type="text" class="form-control validate[required]" id="starctcityfromcountry" name="sdestination" placeholder="Destination" data-prompt-position="topLeft" data-errormessage-value-missing="You left me out!" onkeypress="$(this).validationEngine('hide');" value="<?php echo set_value('sdestination') ?>">
        </div>
      </div>
    </div>
    <div class="row margin-0">
     
     <?php /* ?> 
      <div class="col-md-3 five-column">
        <div class="form-group">
          
          <div class="col-sm-12 col-md-12 no-padding reco1">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="ischild" data-title="1"> Senior Citizen</a>
               </div>
              <input type="hidden" name="triptype" id="triptype1">
            </div>
          </div>
        </div>
      </div>
      <?php */ ?>
      
      
      <?php if(count($days)){ ?>
      <div class="col-md-3 col-md-offset-3 five-column">
        <div class="form-group">
          <select class="form-control validate[required]" name="sdays" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onchange="$(this).validationEngine('hide');">
            <option value="">No. of Days</option>
            <?php foreach($days as $list){ ?>
              <option value="<?php echo $list['days_range']; ?>" <?php echo set_select('sdays',$list['days_range']); ?>><?php echo $list['days_range']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>
      
      

      <div class="col-md-3 five-column">
        <div class="form-group">
          <input class="future form-control validate[required]" name="sstart_date" id="dp2" type="text" placeholder="Date of Departure" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" autocomplete="off" value="<?php echo set_value('sstart_date') ?>">
        </div>
      </div>

      <input type="hidden" id="token" name="token" value="<?php echo time(); ?>" value="<?php echo set_value('token') ?>"/>

     </div>
    
  </div>
  
  <div class="col-md-12 text-center">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" />
  </div>
  
  <?php if(count($tags)){ ?>
  <div class="row filter-wrap margin-0">
    <ul class="list list--horizontal-filter slider responsive">
      <?php foreach($tags as $tlist){ ?>
      <li class="custom-radio--label">
        <?php 
              if(strpos($tlist['tag_name'],'&')>0)
              {
                  $tagnm=str_replace('&','&<br/>',$tlist['tag_name']);
              }
              else
              {
                  $tagnm=$tlist['tag_name'];
              }
        ?>
        <input name="searchtags[]" id="<?php echo 's'.$tlist['id'] ?>" value="<?php echo $tlist['tag_name']; ?>" type="checkbox">
        <label for="<?php echo 's'.$tlist['id'] ?>"><?php echo $tagnm; ?></label>
      </li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  
  <div class="row margin-0">
    <div class="col-md-3 float-right">
      <div class="form-group">
        <input type="button" id="buttonsearch" class="btn btn-primary submit-bh" value="Begin Your Journey">
      </div>
    </div>
  </div>
  
</form>
</div>
</div>



<!--</div>-->
