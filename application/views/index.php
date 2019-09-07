<ul class="nav nav-tabs">
  <li id="defaultactive" class="active">
    <a data-toggle="tab" href="#tab1"><i class="fa fa-search" aria-hidden="true"></i>FIND ME A DESTINATION<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up"></span></a>
  </li>
  <li id="defaultinactive">
    <a data-toggle="tab" href="#tab2"><i class="fa fa-search" aria-hidden="true"></i>I WANT TO TRAVEL TO<span class="glyphicon glyphicon-menu-down"></span> <span class="glyphicon glyphicon-menu-up"></span></a>
</li>
</ul>
<div class="tab-content">
<div id="tab1" class="tab-pane fade in active">
  <?php echo form_open('country-recommendation',array('enctype'=>'multipart/form-data','method'=>'GET','autocomplete'=>'on','class'=>'recform','id'=>'recommendationform')); ?>
  <div class="main-search">

    <div class="row margin-0">

       <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">
          <input type="text" name="start_city" class="form-control searchTextField typeaheadkeywords validate[required]" placeholder="Starting From" autocomplete="off" id="typeaheadkeywords" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onkeypress="$(this).validationEngine('hide');" value="<?php echo set_value('start_city') ?>">
         </div>
      </div>

      <div class="col-md-3 col-sm-6">
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

      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">
          <input class="future form-control validate[required]" name="start_date" id="dp1" type="text" placeholder="Date of Departure" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" autocomplete="off" value="<?php echo set_value('start_date') ?>">
        </div>
      </div>

       <?php if(count($accomodation)){ ?>

      <div class="col-md-3 col-sm-6 five-column">
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




      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">

            <label for="traveltime">Travel Time (Hours)</label>
            <input type="text" id="traveltime" class="range-style" readonly style="border:0; color:#f6931f; font-weight:bold;">
            <div id="traveltime-range" class="sliding-range-style"></div>
            <input type="hidden" name="traveltime" id="traveltimeinp" value="1-15"/>
        </div>
      </div>


      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">

          <label for="days">No. of Days</label>
          <input type="text" id="days" class="range-style" readonly style="border:0; color:#f6931f; font-weight:bold;">
          <div id="slider-days" class="sliding-range-style"></div>
          <input type="hidden" name="days" id="nodays" value="10"/>
      </div>
      </div>



       <input type="hidden" id="rectoken" name="token" value="<?php echo time(); ?>"/>


      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">

            <label for="amount">Temperature</label>
            <input type="text" id="weather" class="range-style" readonly style="border:0; color:#f6931f; font-weight:bold;">
            <div id="weather-range" class="sliding-range-style"></div>
            <!--<input type="hidden" name="weather" id="weatherinp" value="1-20"/>-->
            <input type="hidden" name="weather" id="weatherinp" value="0-20"/>
        </div>
      </div>


      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">

          <label for="accomodation">Budget Per Night (USD)</label>
            <input type="text" class="range-style" id="budget" readonly style="border:0; color:#f6931f; font-weight:bold;">
            <div id="budget-range" class="sliding-range-style"></div>
            <!--<input type="hidden" name="budget" id="budgetinp" value="75-300"/> -->
            <input type="hidden" name="budget" id="budgetinp" value="150-300"/>
       </div>
      </div>


      <input type="hidden" name="ocode" id="code" value="<?php echo set_value('ocode') ?>"/>

  </div>

  </div>

  <div class="col-md-12 text-center form-divider">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" alt=""/>
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
        <input type="button" id="recommendationbtn" onClick="ga('send', 'event', { eventCategory: 'general', eventAction: 'buttonclick', eventLabel: 'buttonlead', eventValue: 0});" class="link-button purple" value="Begin Your Journey"/>
      </div>
    </div>
  </div>

</form>
</div>
<div id="tab2" class="tab-pane fade">
<?php echo form_open('cityAttractions',array('enctype'=>'multipart/form-data','method'=>'GET','autocomplete'=>'on','class'=>'recform','id'=>'recformsubmit')); ?>
  <div class="main-search">

    <div class="row margin-0">
      <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
          <input type="text" class="form-control validate[required]" id="starctcityfromcountry" name="sdestination" placeholder="Destination" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" onkeypress="$(this).validationEngine('hide');" value="<?php echo set_value('sdestination') ?>">
        </div>
      </div>
    </div>
    <div class="row margin-0">



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



      <div class="col-md-3 col-sm-6 five-column">
        <div class="form-group">
          <input class="future form-control validate[required]" name="sstart_date" id="dp2" type="text" placeholder="Date of Departure" data-prompt-position="bottomLeft" data-errormessage-value-missing="You left me out!" autocomplete="off" value="<?php echo set_value('sstart_date') ?>">
        </div>
      </div>

      <input type="hidden" id="token" name="token" value="<?php echo time(); ?>"/>

     </div>

  </div>

  <div class="col-md-12 text-center">
    <img src="<?php echo site_url('assets/images/title-after1.png') ?>" alt=""/>
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
        <input type="button" id="buttonsearch" onClick="ga('send', 'event', { eventCategory: 'general', eventAction: 'buttonclick', eventLabel: 'buttonlead', eventValue: 0});" class="btn btn-primary submit-bh" value="Begin Your Journey">
      </div>
    </div>
  </div>

</form>
</div>
</div>
