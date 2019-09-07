<div class="container">
  <div class="row">
      <div class="col-md-6 no-padding">
           <?php  if(isset($countrynm) && count($countrynm)>0){ ?>
            <h3>Recommended Hotels for <span id="connm"><?php echo $countrynm['country_name']; ?></span></h3>
          <?php } else { ?>
            <h3>No Recommendation</h3>
          <?php } ?>
        </div>
        
        <?php if(count($cities) && $type==1){ ?>
        <div class="col-md-6 no-padding sort text-right">
               <div class="form-group">
                        <select class="form-control" id="<?php if(isset($account) && $account==1){ echo 'citydropdownSaved'; }else{ echo 'citydropdown'; } ?>">
                          <?php foreach($cities as $list){ ?>
                            <option value="<?php echo $list['id']; ?>" <?php if($cityid==$list['id']){ echo 'selected'; } ?> ><?php echo $list['city_name']; ?></option>
                          <?php } ?>
                          
                        </select>
               </div>
        </div>
        <?php } else if(count($cities) && $type==2){ ?>

            <div class="col-md-6 no-padding sort text-right">
               <div class="form-group">
                       <select class="form-control" id="<?php if(isset($account) && $account==1){ echo 'multicitydropdownSaved'; }else{ echo 'multicitydropdown'; } ?>">
                          <?php foreach($cities as $key=>$list){ ?>
                              <optgroup label="<?php echo $key; ?>">
                                <?php foreach($list as $l){ 
                                    $exp=explode('-',$l['id']);
                                 ?>
                                  <option value="<?php echo $l['id']; ?>" <?php if($cityid==$exp[1]){ echo 'selected'; } ?>><?php echo $l['city_name']; ?></option>
                                <?php } ?>
                              </optgroup>
                          <?php } ?>
                        </select>
               </div>
        </div>

         <?php } else if(count($cities) && $type==3){ ?>

            <div class="col-md-6 no-padding sort text-right">
               <div class="form-group">

                        <select class="form-control" id="<?php if(isset($account) && $account==1){ echo 'searchcitydropdownSaved'; }else{ echo 'searchcitydropdown'; } ?>">
                          <?php foreach($cities as $key=>$list){ ?>
                              <option value="<?php echo $list['plainid']; ?>" <?php if(md5($cityid)==$list['id']){ echo 'selected'; } ?>><?php echo $list['city_name']; ?></option>
                              
                          <?php } ?>
                        </select>
               </div>
        </div>

        <?php } ?>
    
    </div>
</div>
