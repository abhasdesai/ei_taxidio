<div class="wraper container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">My Trips</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <?php if($this->session->flashdata('success')){ ?>
                <div class="alert bg-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>

         <?php  }else if($this->session->flashdata('error')){  ?>

               <div class="alert bg-danger">
                    <?php echo $this->session->flashdata('error'); ?>
               </div>

         <?php } ?>

            <div class="profile-detail card-box">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">

                                <?php if(count($trips)){ ?>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="60%">Trip</th>
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

                                        ?>
                                        <tr>
                                            <td class="middle-align">
                                                <div class="trip-with-code">
                                                    <div class="trip-name" data-toggle="tooltip" title="<?php echo $tripname_main_name; ?>"><?php echo word_limiter($tripname_main_name,4); ?></div>

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
                                             <td><a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug'] ?>"><?php echo str_pad($list['total'], 2, '0', STR_PAD_LEFT); ?></a></td>
                                             <?php }else{ ?>
                                               <td><?php echo 'N/A'; ?></td>
                                             <?php } ?>
                                             <td>
                                               <?php if($list['trip_mode']==2){ echo number_format((float)$list['rating'], 1, '.', ''); }else{ echo 'N/A'; } ?>
                                             </td>
                                            <td class="middle-align">
                                            <?php $itiid= "'".string_encode($list['id'])."'"; ?>
                                                <a href="<?php echo $url ?>" class="view-btn" target="_blank" data-toggle="tooltip" title="Edit Trip"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                 <a href="<?php echo site_url('editTrip').'/'.$list['id'] ?>" class="view-btn" data-toggle="tooltip" title="Edit Trip Name &amp; Date"><i class="glyphicon glyphicon-edit"></i></a>
                                                <a href="javascript:void(0);" class="view-btn" onClick="confirmAlert(<?php echo $itiid; ?>)" data-toggle="tooltip" title="Delete Trip"><i class="glyphicon glyphicon-trash"></i></a>
                                            </td>


                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>

                                <?php echo $pagination; ?>

                                <?php } else { ?>
                                <div class="alert alert-info">
                                     Nothing To Show.
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div> <!-- container -->

  <script>

function confirmAlert(tripid)
{
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this trip",
            type: "error",
            showCancelButton: true,
            cancelButtonClass: 'btn-white btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light btn-pop-delete',
            confirmButtonText: 'Yes!'
        });

    $(".btn-pop-delete").click(function(){
        window.location="<?php echo site_url('deleteTrip') ?>"+"/"+tripid;
    })


}



  </script>
