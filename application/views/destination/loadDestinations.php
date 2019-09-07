
<link rel="stylesheet" href="<?php echo site_url('assets/css/shorten.css'); ?>">
  <style type="text/css">
  
    .desc {
      line-height: 1.7;
      font-size:18px;
    }

    .desc.shorten_expand {
      height: auto;
    }

  </style>
<?php if(count($country_details)){ 
  $i=0;
  //echo "<pre>";print_r($country_details);die;
 foreach($country_details as $list){
$i++;
    ?>

    <div class="col-md-12 taxidio-padbox">
      <div class="item-listing bg-grey taxidio-padimg">
        <div class="row">
          <div class="col-md-3">
            <a class="text-left taxidio-alink" href="javascript:void(0);">
              <h3 class="taxidio-h3"><?php echo $list['country_name'] ?></h3>
            </a>
            <span class="zoom" id="ex1">
            <?php
                  if($list['countryimage']!='' && file_exists(FCPATH.'/userfiles/countries/small/'.$list['countryimage']))
                  { ?>
              <img src="<?php echo site_url('userfiles/countries/small/'.$list['countryimage']); ?>" />
              <?php }else{ ?>
              <img src="<?php echo site_url('assets/images/image300.jpg') ?>" />
              <?php } ?>
            </span>
          </div>
          <div class="col-md-9 listing-title">
            <p class="taxidio-highlmar desc"><?php echo $list['country_conclusion'] ?></p>
            
          </div>
          <div class="col-sm-12" style="margin-top:20px;">
            <div class="city-box">
              <div class="city-list">
                <div class="taxidio-borderpanel">
                  <div class="panel-body text-center">
                    <div class="taxidio-circle text-center">
                      <i class="flaticon-government taxidio-fontcen" ></i>
                    </div>
                  </div>
                  <div class="taxidio-middletitle text-center" >
                    <strong class="taxidio-titlepanel">Capital</strong>
                  </div>
                  <div class="panel-footer">
                    <input type="checkbox" class="read-more-state" id="post-2" />
                    <ul class="attraction-list ">
                      <li><?php echo $list['country_capital'] ?></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="city-list">
                <div class="taxidio-borderpanel">
                  <div class="panel-body text-center">
                    <div class="taxidio-circle text-center">
                      <i class="flaticon-city taxidio-fontcen" ></i>
                    </div>
                  </div>
                  <div class="taxidio-middletitle text-center">
                    <strong class="taxidio-titlepanel">Cities Covered </strong>
                  </div>
                  <div class="panel-footer">
                    <ul class="attraction-list read-more-wrap">
                      <?php $cities=$this->Destination_fm->getCities($list['id']); ?>
                       <?php if(isset($cities[0]['city_name']) && !empty($cities[0]['city_name'])){ ?>
                      <li><?php echo $cities[0]['city_name']; ?></li>
                      <?php }else{ ?>
                      <li>N/A</li>
                      <?php } ?>
                    </ul>
                    <?php if(count($cities)>1){ ?>
                    <a href="#" data-toggle="modal" data-target="#citymodal<?php echo $i; ?>">More Cities</a>
                    <div id="citymodal<?php echo $i; ?>" class="destmodal modal fade" role="dialog">
                      <div class="modal-dialog modal-sm">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Cities</h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-sm-12">
                                <ul class="attraction-list">
                                <?php 
                                foreach ($cities as $city) {
                                 ?>
                                  <li><?php echo $city['city_name'] ?></li>
                                  <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="city-list">
                <div class="taxidio-borderpanel">
                  <div class="panel-body text-center">
                    <div class="taxidio-circle text-center">
                      <i class="flaticon-famous-place taxidio-fontcen" ></i>
                    </div>
                  </div>
                  <div class="taxidio-middletitle text-center" >
                    <strong class="taxidio-titlepanel">Key Attractions</strong>
                  </div>
                  <div class="panel-footer ">
                    <ul class="attraction-list ">
                    <?php $attractions=$this->Destination_fm->getAllCountryattractions($list['id']);
                    //print_r($attractions);die; ?>
                    <?php if(isset($attractions[0]['attraction_name']) && !empty($attractions[0]['attraction_name'])){ ?>
                      <li><?php echo $attractions[0]['attraction_name']; ?></li>
                      <?php }else{ ?>
                      <li>N/A</li>
                      <?php } ?>
                    </ul>
                    <?php if(count($attractions)>1){ ?>
                    <a href="#" data-toggle="modal" data-target="#famousmodal<?php echo $i; ?>">More Attractions</a>
                    <div id="famousmodal<?php echo $i; ?>" class="destmodal modal fade" role="dialog">
                      <div class="modal-dialog modal-sm">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Famous About</h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-sm-12">
                                <ul class="attraction-list">
                                <?php 
                                foreach ($attractions as $attraction) {
                                 ?>
                                  <li><?php echo $attraction['attraction_name'] ?></li>
                                  <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="city-list">
                <div class="taxidio-borderpanel">
                  <div class="panel-body text-center">
                    <div class="taxidio-circle text-center">
                      <i class="flaticon-money taxidio-fontcen " ></i>
                    </div>
                  </div>
                  <div class="taxidio-middletitle text-center">
                    <strong class="taxidio-titlepanel">Currency</strong>
                  </div>
                  <div class="panel-footer">
                    <ul class="attraction-list read-more-wrap ">
                      <li><?php echo $list['country_currency'] ?></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="city-list">
                <div class="taxidio-borderpanel">
                  <div class="panel-body text-center">
                    <div class="taxidio-circle text-center">
                      <i class="flaticon-time-zone taxidio-fontcen" ></i>
                    </div>
                  </div>
                  <div class="taxidio-middletitle text-center">
                    <strong class="taxidio-titlepanel">Time Zone</strong>
                  </div>
                  <div class="panel-footer">
                    <ul class="attraction-list read-more-wrap ">
                    <?php if(isset($list['timezone']) && !empty($list['timezone'])){ ?>
                      <li><?php echo $list['timezone']; ?></li>
                      <?php }else{ ?>
                      <li>N/A</li>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php } ?>


<div class="col-md-12" align="center">
 <div class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
  <?php echo $pagination; ?>
 </div>
 </div>

<script src="<?php echo site_url('assets/js/jquery-shorten.js');?>"></script>
  <script>
    jQuery(function($) {
      $('.desc').shorten();
    });
  </script>


<?php } else { ?>

<div class="row">
<div class="col-md-12">


<div class="alert alert-info">
Nothing To Show....
</div>
</div>
</div>

<?php } ?>

