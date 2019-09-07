<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>
<!--<div class="container">-->
<ul class="nav nav-tabs">
  <li class="active">
    <a data-toggle="tab" href="#tab1"><i class="fa fa-search" aria-hidden="true"></i>RECOMMENDATION<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up"></span></a>
  </li>
  <li>
    <a data-toggle="tab" href="#tab2"><i class="fa fa-search" aria-hidden="true"></i>SEARCH<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up
  "></span></a>
</li>
</ul>
<div class="tab-content">
<div id="tab1" class="tab-pane fade in active">
  <?php echo form_open('country-recommendation',array('enctype'=>'multipart/form-data','method'=>'GET','role'=>'form')); ?>
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

      <?php if(count($budget)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control" name="budget">
            <option>Budget</option>
            <?php foreach($budget as $list){ ?>
              <option value="<?php echo $list['budget']; ?>"><?php echo $list['budget']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>


       <div class="col-md-3">
        <div class="form-group">
          
          <div class="col-sm-12 col-md-12 no-padding domestic">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="isdomestic" data-title="1">Domestic</a>
                <a class="btn btn-primary btn-sm notActive" data-toggle="isdomestic" data-title="0">International</a>
              </div>
              <input type="hidden" name="isdomestic" id="isdomestic">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 five-column reco">
        <div class="form-group">
           <div class="col-sm-12 col-md-12 no-padding">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="triptype" data-title="1"> Senior Citizen</a>
                <a class="btn btn-primary btn-sm notActive" data-toggle="triptype" data-title="0">Child</a>
              </div>
              <input type="hidden" name="triptype" id="triptype" value="1">
            </div>
          </div>
        </div>
      </div>

     
      <div class="col-md-3 five-column">
        <div class="form-group">
          <input type="text" name="start_city" class="form-control searchTextField typeaheadkeywords" placeholder="Start City" autocomplete="off" id="typeaheadkeywords">
          <input class="form-control" id="lat" name="lat" type="hidden"/>
          <input class="form-control" id="long" name="long" type="hidden"/>
        </div>
      </div>
   
      
      <?php if(count($days)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control" name="days">
            <option>Days</option>
            <?php foreach($days as $list){ ?>
              <option value="<?php echo $list['days_range']; ?>"><?php echo $list['days_range']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>

       <?php if(count($weather)){ ?>
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control" name="weather">
            <option>Weather</option>
            <?php foreach($weather as $wlist){ ?>
              <option value="<?php echo $wlist['id']; ?>"><?php echo $wlist['weather_temperature_from'].' - '. $wlist['weather_temperature_to']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <?php } ?>

      <?php /* ?>
      <div class="col-md-3">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Weather Conditions" name="weather">
        </div>
      </div>
      <?php */ ?>
      
      
      
      <div class="col-md-3 five-column">
        <div class="form-group">
          <input type="text" name="traveltime" class="form-control" placeholder="Travel Time" autocomplete="off">
        </div>
      </div>
      
      <div class="col-md-3 five-column">
        <div class="form-group">
          <input class="future form-control" name="start_date" id="dp1" type="text" placeholder="Start Date">
        </div>
      </div>
    </div>
    
  </div>
  
  <div class="col-md-12 text-center form-divider">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" />
  </div>
  
  <?php if(count($tags)){ ?>
  <div class="row filter-wrap margin-0">
    <ul class="list list--horizontal-filter slider responsive">
      <?php foreach($tags as $tlist){ ?>
      <li class="custom-radio--label">
        <input name="tags[]" id="<?php echo 'r'.$tlist['id'] ?>" value="<?php echo $tlist['tag_name']; ?>" type="checkbox">
        <label for="<?php echo 'r'.$tlist['id'] ?>"><?php echo $tlist['tag_name']; ?></label>
      </li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  
  <div class="row margin-0">
    <div class="col-md-3 float-right">
      <div class="form-group">
        <button type="submit" class="btn btn-primary submit-bh">Let's Plan Your Trip</button>
      </div>
    </div>
  </div>
  
</form>
</div>
<div id="tab2" class="tab-pane fade">
<form role="form">
  <div class="main-search">
    <!--<div class="row margin-0">
      <div class="col-md-3">
        <div class="form-group">
          <input type="text" class="form-control" id="usr" placeholder="Location">
        </div>
      </div>
    </div>-->
    <div class="row margin-0">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group">
          <input type="text" class="form-control" id="usr" placeholder="Location">
        </div>
      </div>
    </div>
    <div class="row margin-0">
     
      
      <div class="col-md-3 five-column">
        <div class="form-group">
          
          <div class="col-sm-12 col-md-12 no-padding">
            <div class="input-group swap-input">
              <div id="radioBtn" class="btn-group">
                <a class="btn btn-primary btn-sm active" data-toggle="ischild" data-title="1"> Senior Citizen</a>
                <a class="btn btn-primary btn-sm notActive" data-toggle="ischild" data-title="0">Child</a>
              </div>
              <input type="hidden" name="triptype" id="triptype1">
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 five-column">
          <div class="form-group">
            <input type="text" class="form-control typeaheadkeywords" placeholder="Start City"  autocomplete="off">
          </div>
        </div>
      
      
      <div class="col-md-3 five-column">
        <div class="form-group">
          <select class="form-control">
            <option>Days</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
          </select>
        </div>
      </div>
      
      <div class="col-md-3 five-column">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Travel Time">
        </div>
      </div>

      <div class="col-md-3 five-column">
        <div class="form-group">
          <input class="future form-control" name="strat_date" id="dp1" type="text" placeholder="Start Date">
        </div>
      </div>
      
    </div>
    
  </div>
  
  <div class="col-md-12 text-center">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" />
  </div>
  
  <div class="row filter-wrap margin-0">
    <ul class="list list--horizontal-filter slider responsive">
      <li class="custom-radio--label">
        <input name="months[]" id="month_january" value="january" type="checkbox">
        <label for="month_january">World Heritage/Architecture</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_february" value="february" checked="" type="checkbox">
        <label for="month_february">History</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_march" value="march" type="checkbox">
        <label for="month_march">Museum/ Castles</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_april" value="april" type="checkbox">
        <label for="month_april">Beach / Islands</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_may" value="may" type="checkbox">
        <label for="month_may">High / Altitude</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_june" value="june" type="checkbox">
        <label for="month_june">Nature / Wildlife / Ecotourism</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_july" value="july" type="checkbox">
        <label for="month_july">Child Attraction</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_august" value="august" type="checkbox">
        <label for="month_august">Romance</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_september" value="september" type="checkbox">
        <label for="month_september">Sports & Adventure</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_october" value="october" type="checkbox">
        <label for="month_october">Health & Spas</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_november" value="november" type="checkbox">
        <label for="month_november">Festivals</label>
      </li>
      <li class="custom-radio--label">
        <input name="months[]" id="month_december" value="december" type="checkbox">
        <label for="month_december">Food & Nightlife</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december1" value="december1" type="checkbox">
        <label for="month_december1">Community Tourism</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december2" value="december2" type="checkbox">
        <label for="month_december2">Medical Tourism</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december3" value="december3" type="checkbox">
        <label for="month_december3">Business Tourism</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december4" value="december4" type="checkbox">
        <label for="month_december4">Archeological</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december5" value="december5" type="checkbox">
        <label for="month_december5">Art & Culture</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december6" value="december6" type="checkbox">
        <label for="month_december6">Shopping</label>
      </li>
      
      <li class="custom-radio--label">
        <input name="months[]" id="month_december7" value="december7" type="checkbox">
        <label for="month_december7">Spirituality & Religion</label>
      </li>
    </ul>
  </div>
  
  <div class="row margin-0">
    <div class="col-md-3 float-right">
      <div class="form-group">
        <button type="submit" class="btn btn-primary submit-bh">Let's Plan Your Trip</button>
      </div>
    </div>
  </div>
  
</form>
</div>
</div>
<!--</div>-->