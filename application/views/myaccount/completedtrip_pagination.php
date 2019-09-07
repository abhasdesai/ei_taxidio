

                                <?php if(count($trips)){ ?>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="60%">Completed Trip</th>
                                            <th width="20%">Date</th>
                                            <th width="5%">Questions</th>
                                            <th width="5%">Rating</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach($trips as $list){

                                        $json=json_decode($list['inputs'],TRUE);
                                        $codes=explode('-',$list['citiorcountries']);
                                        $tripname_main='';
                                        if($list['trip_type']==1 || $list['trip_type']==3)
                                        {

                                                if($list['trip_type']==1)
                                                {
                                                    $url=site_url('userSingleCountryTrip').'/'.string_encode($list['id']);
                                                    $startdate=$json['start_date'];
                                                    $ttldays=$json['days']-1;
                                                }
                                                else if($list['trip_type']==3)
                                                {
                                                    $url=site_url('userSearchedCityTrip').'/'.string_encode($list['id']);
                                                    $startdate=$json['sstart_date'];
                                                    $ttldays=$json['sdays']-1;
                                                }

                                                if(isset($list['user_trip_name']) && $list['user_trip_name']!='')
                                                {
                                                     $tripname_main_name=$list['user_trip_name'];
                                                }
                                                else
                                                {
                                                     $tripname_main=$this->Trip_fm->getContinentCountryName($list['country_id']);
                                                     $tripname_main_name='Trip '.$tripname_main['country_name'];
                                                }


                                        }
                                        else if($list['trip_type']==2)
                                        {

                                            $url=site_url('multicountrytrips').'/'.string_encode($list['id']);
                                            if(isset($list['user_trip_name']) && $list['user_trip_name']!='')
                                            {
                                                 $tripname_main_name=$list['user_trip_name'];
                                            }
                                            else
                                            {
                                                 $tripname_main=$this->Trip_fm->getContinentName($list['tripname']);
                                                 $tripname_main_name='Trip '.$tripname_main['country_name'];
                                            }
                                            //echo "<pre>";print_r($json);die;
                                            $startdate=$json['start_date'];
                                             $ttldays=$json['days']-1;

                                        }
                                        $tripname=explode('-',$list['tripname']);




                                         $startdateformat=explode('/',$startdate);
                                         $startdateymd=$startdateformat[2].'-'.$startdateformat[1].'-'.$startdateformat[0];

                                          $itiid= string_encode($list['id']);
                                        ?>
                                        <tr>
                                            <td class="middle-align">
                                                <div class="trip-with-code">
                                                    <div class="trip-name" data-toggle="tooltip" title="<?php echo $tripname_main_name; ?>">
                                                    <?php if($list['is_co_traveller']==1){ ?>
                                                    <i title="Co-Traveller Details" class="fa fa-users ctd" onclick="getCoTravellers('<?php echo $itiid; ?>')"></i>
                                                    <?php } ?>
                                                    &nbsp;<?php echo word_limiter($tripname_main_name,4); ?></div>

                                                    <div class="trip-city-country">
                                                    <?php for($i=0;$i<count($tripname);$i++){ ?>
                                                    <span class="trip-code" data-toggle="tooltip" title="<?php if(isset($codes[$i]) && $codes[$i]!=''){ echo $codes[$i]; }else{ echo $tripname[$i]; } ?>"><?php echo $tripname[$i]; ?></span>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>

                                                <div class="trip-time">
                                                    <div class="trip-time">
                                                        <a href="#"><?php echo date('d-M-Y',strtotime($startdateymd)).' - '.date('d-M-Y', strtotime($startdateymd. " + $ttldays days")); ?> </a>
                                                    </div>
                                                </div>
                                            </td>
                                             <?php if($list['trip_mode']==2){ ?>
                                             <td class=" middle-align"><a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug'] ?>"><?php echo str_pad($list['total'], 2, '0', STR_PAD_LEFT); ?></a></td>
                                             <?php }else{ ?>
                                               <td class=" middle-align"><?php echo 'N/A'; ?></td>
                                             <?php } ?>
                                             <td class="middle-align">
                                               <?php if($list['trip_mode']==2){ echo number_format((float)$list['rating'], 1, '.', ''); }else{ echo 'N/A'; } ?>
                                             </td>
                                            <td class="middle-align trip_action">
                                                <div>
                                                    <a href="<?php echo $url ?>" class="view-btn" target="_blank" data-toggle="tooltip" title="View Itinerary"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                </div>
                                                <div>
                                                     <a href="<?php echo site_url('editTrip').'/'.$list['id'] ?>" class="view-btn" data-toggle="tooltip" title="Edit Trip Details"><i class="glyphicon glyphicon-edit"></i></a>
                                                </div>
                                                <div>
                                                    <a href="javascript:void(0);" class="view-btn" onClick="confirmAlert('<?php echo $itiid; ?>')" data-toggle="tooltip" title="Delete Trip"><i class="glyphicon glyphicon-trash"></i></a>
                                                </div>
                                            <div class="top-notification" onclick="open_share_box($(this))"> <a href="javascript:void(0);" class="view-btn"><i class="fa fa-share-alt"></i></a>
                                              <div class="share-box" iti='<?php echo $itiid; ?>'>
                                                <a href="javascript:void(0);" onclick="member_share($(this))"><i class="fa fa-user"></i><span>Member</span></a>&nbsp;&nbsp;
                                                <a href="javascript:void(0);" onclick="email_share($(this))"><i class="fa fa-envelope-square"></i><span>Email</span></a>&nbsp;&nbsp;
                                               <?php if($list['trip_mode']==2){
                                                $quote='I just made my travel itinerary “'.$list['user_trip_name'].'”. Take a look and let me know what you think!';
                                                ?>
                                                <a href="https://www.facebook.com/sharer/sharer.php?app_id=1959210047634242&sdk=joey&u=<?php echo site_url('planned-itinerary-forum/'.$list['slug']) ?>&quote=<?php echo rawurlencode($quote); ?>&display=popup&ref=plugin&src=share_button" onclick="return !window.open(this.href, 'Facebook', 'width=640,height=580')"><i class="fa fa-facebook-square"></i><span>Facebook</span></a>   
                                                <?php }else{ ?>
                                                <a href="javascript:void(0);" onclick="check_is_public($(this))"><i class="fa fa-facebook-square"></i><span>Facebook</span></a>
                                                <?php } ?>&nbsp;&nbsp;
                                               <?php if($list['trip_mode']==2){?>
                                                <a href="<?php echo site_url('share-iti-with-twitter').'/'.$itiid ?>" target="_blank"><i class="fa fa-twitter-square"></i><span>Twitter</span></a>
                                                <?php }else{ ?>
                                                <a href="javascript:void(0);" onclick="check_is_public($(this))"><i class="fa fa-twitter-square"></i><span>Twitter</span></a>
                                                <?php } ?>
                                              </div>
                                            </div>
                                            </td>


                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>

                                <div align="center">
                                 <div id="com_trip" class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
                                  <?php echo $pagination; ?>
                                 </div>
                                 </div>

                                <?php } else { ?>
                                <div class="alert alert-info">
                                     There is no Completed Trip found.
                                </div>
                                <?php } ?>
