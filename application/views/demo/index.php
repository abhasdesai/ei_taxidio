
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
        <form role="form">
        <div class="main-search">
        	
            <div class="row margin-0">
            	<div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Budget Per Night">
                    </div>
                </div>
                <div class="col-md-3">
                     <div class="form-group">
                          <select class="form-control">
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
    					<a class="btn btn-primary btn-sm active" data-toggle="happy" data-title="Y">Domestic</a>
    					<a class="btn btn-primary btn-sm notActive" data-toggle="happy" data-title="N">International</a>
    				</div>
    				<input type="hidden" name="happy" id="happy">
    			</div>
    		</div>
    	</div>
                </div>
                
                <div class="row margin-0">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Start City">
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                     <div class="form-group">
                          <select class="form-control">
                            <option>Days</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                          </select>
					</div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Weather Conditions">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="col-md-4 padding-left-0 padding-right-5">
                        <div class="form-group">
                            <input class="form-control" type="number" min="0" step="1" placeholder="Adults" />
                        </div>
                    </div>
                    <div class="col-md-4 padding-right-5 padding-left-5">
                        <div class="form-group">
                            <input class="form-control" type="number" min="0" step="1" placeholder="Child" />
                        </div>
                    </div>
                    <div class="col-md-4 padding-right-5 padding-left-5">
                        <div class="form-group">
                          <select class="form-control days-select">
                            <option>Days</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                          </select>
						</div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Travel Time">
                    </div>
                </div>
                
                <div class="col-md-3">
                	<div class="form-group">
                        <input class="future form-control" id="dp1" type="text" placeholder="Start Date">
                	</div>
                </div>
                </div>
                
                </div>
                
                 <div class="col-md-12 text-center form-divider">
                	<img src="<?php echo site_url('assets/images/title-after1.png') ?>" />
                </div>
                
                
                <div class="row filter-wrap margin-0">
                        <ul class="list list--horizontal-filter slider responsive">
                                  <li class="custom-radio--label">
                      <input name="months[]" id="month_january" value="january" type="checkbox">
                      <label for="month_january">World Heritage/UNESCO/Architecture</label>  
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
            	<div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Budget Per Night">
                    </div>
                </div>
                <div class="col-md-3">
                     <div class="form-group">
                          <select class="form-control">
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
    					<a class="btn btn-primary btn-sm active" data-toggle="happy" data-title="Y">Domestic</a>
    					<a class="btn btn-primary btn-sm notActive" data-toggle="happy" data-title="N">International</a>
    				</div>
    				<input type="hidden" name="happy" id="happy">
    			</div>
    		</div>
    	</div>
                </div>
                
                <div class="row margin-0">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Start City">
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                     <div class="form-group">
                          <select class="form-control">
                            <option>Days</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                          </select>
					</div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Weather Conditions">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="col-md-4 padding-left-0 padding-right-5">
                        <div class="form-group">
                            <input class="form-control" type="number" min="0" step="1" placeholder="Adults" />
                        </div>
                    </div>
                    <div class="col-md-4 padding-right-5 padding-left-5">
                        <div class="form-group">
                            <input class="form-control" type="number" min="0" step="1" placeholder="Child" />
                        </div>
                    </div>
                    <div class="col-md-4 padding-right-5 padding-left-5">
                        <div class="form-group">
                          <select class="form-control">
                            <option>No. Of Days</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                          </select>
						</div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Travel Time">
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
