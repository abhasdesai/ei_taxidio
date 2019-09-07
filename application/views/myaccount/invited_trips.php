<div id="iti-share" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div id="iti_share" class="modal-dialog modal-sm share-dialog">
</div>
</div>
<div class="wraper container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Invited Trips</h4>
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
                                        <?php foreach($trips as $k=>$list){

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
                                                   
                                                    <i title="Co-Traveller Details" class="fa fa-users ctd" onclick="getCoTravellers('<?php echo $itiid; ?>')"></i>
                                                    &nbsp;<?php echo word_limiter($tripname_main_name,4); ?> </div>

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
                                             <td class="q-n-r"><a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug'] ?>"><?php echo str_pad($list['total'], 2, '0', STR_PAD_LEFT); ?></a></td>
                                             <?php }else{ ?>
                                               <td class="q-n-r"><?php echo 'N/A'; ?></td>
                                             <?php } ?>
                                             <td class="q-n-r">
                                               <?php if($list['trip_mode']==2){ echo number_format((float)$list['rating'], 1, '.', ''); }else{ echo 'N/A'; } ?>
                                             </td>
                                            <td class="middle-align trip_action">
                                                 <?php if(!empty($list['status'])){ ?>
                                                <a href="<?php echo $url ?>" target="_blank" class="view-btn" data-toggle="tooltip" title="View Trip”"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                 <a href="<?php echo site_url('editTrip').'/'.$list['id'] ?>" class="view-btn" data-toggle="tooltip" title="Edit Trip Details"><i class="glyphicon glyphicon-edit"></i></a>
                                                 <?php }else{ ?>
                                                <a href="javascript:void(0);" class="view-btn" onClick="statusUpdateAlert('<?php echo $itiid; ?>')" data-toggle="tooltip" title="Edit Trip"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                 <a href="javascript:void(0);" onClick="statusUpdateAlert('<?php echo $itiid; ?>')" class="view-btn" data-toggle="tooltip" title="Edit Trip Name &amp; Date"><i class="glyphicon glyphicon-edit"></i></a>
                                                 <?php } ?>

                                                <a href="javascript:void(0);" class="view-btn" onClick="confirmAlert('<?php echo $itiid; ?>')" data-toggle="tooltip" title="Delete Trip"><i class="glyphicon glyphicon-trash"></i></a>
                                                <!-- <a href="javascript:void(0);" class="view-btn" data-toggle="modal" data-target="#tripinfo<?php echo $k; ?>" data-toggle="tooltip" title="Trip Owner's Details"><i class="glyphicon glyphicon-info-sign"></i></a> -->
                                                <div id="tripinfo<?php echo $k; ?>" class="trip-info destmodal modal fade" role="dialog">
                                                  <div class="modal-dialog modal-sm">
                                                    <!-- Modal content-->
                                                    <div class="modal-content inv-trip-modal">
                                                      <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Trip Owner's Details</h4>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                          <div class="col-sm-12">
                                                            <table class="inv-trip-tbl">
                                                                <tr>
                                                                    <td class="tblh"><strong>Name:</strong></td>
                                                                    <td><?php echo $list['name']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblh"><strong>Date of Birth:</strong></td>
                                                                    <td><?php if(isset($list['dob']) && $list['dob']!='' && strtotime($list['dob'])>0){ echo date('d/m/Y',strtotime($list['dob'])); }else{ echo "N/A"; } ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblh"><strong>Gender:</strong></td>
                                                                    <td>
                                                                    <?php 
                                                                    if($list['gender']==1 ||$list['gender']==0){ 
                                                                      echo 'Male'; 
                                                                    }else{ 
                                                                      echo 'Female'; 
                                                                    } ?>
                                                                      </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblh"><strong>Email:</strong></td>
                                                                    <td><?php echo $list['email']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblh"><strong>Phone No:</strong></td>
                                                                    <td><?php echo $list['phone']?$list['phone']:'N/A'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblh"><strong>Passport Number:</strong></td>
                                                                    <td><?php echo $list['passport']?$list['passport']:'N/A'; ?></td>
                                                                </tr>
                                                            </table>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <!-- <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                      </div> -->
                                                    </div>
                                                  </div>
                                                </div>
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

  function getCoTravellers(d)
  {
     var iti=d;
     $.ajax({
          type:'POST',
          url:'<?php echo site_url("getCoTravellers") ?>',
          data:{iti_id: iti }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
              $("#iti_share").html(data);
              $("#iti-share").modal();
          },
          error: function (request, status, error) {
            $(".loader").hide();
          }
        });
  }

$(document).on('click', 'div#cotraveller ul li a',function(e){
  e.preventDefault();
  var this_url=$(this).attr('href');
  $.ajax({
      type:'POST',
      url:this_url,
      data:{iti_id: $('#cot_iti_id').val() }, 
      beforeSend: function()
      {
        $(".loader").show();
      },
      complete: function()
      {
        $(".loader").hide();
      },
      success:function(data)
      {
          $("#iti_share").html(data);
      },
      error: function (request, status, error) {
        $(".loader").hide();
      }
  });

});

function confirmAlert(tripid)
{
    swal({
            title: "Are you sure?",
            text: "Once deleted, you won’t be able to recover this trip.",
            type: "error",
            showCancelButton: true,
            cancelButtonClass: 'btn-white btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light btn-pop-delete',
            confirmButtonText: 'Delete Trip'
        });

    $(".btn-pop-delete").click(function(){
        window.location="<?php echo site_url('deleteInvitedTrip') ?>"+"/"+tripid;
    })
}

</script>

<script>

function statusUpdateAlert(tripid)
{
    swal({
  title: "Are you sure?",
  <?php /*?>text: "Please accept the trip first to make edits.",<?php */?>
  text: "Please accept the trip invitation in order to view this trip or make changes.",
  type: "warning",
  showCancelButton: true,
  cancelButtonClass: 'btn-white btn-md waves-effect',
  confirmButtonClass: 'btn-success btn-md waves-effect waves-light btn-pop-accept',
  confirmButtonText: "Yes",
  closeOnConfirm: false
},
function() {
    $.ajax({
          type:'POST',
          dataType: 'json',
          url:'<?php echo site_url("invited-trip-status-update") ?>',
          data:{iti_id: tripid,status:1 }, 
          beforeSend: function()
          {
            $(".loader").show();
          },
          complete: function()
          {
            $(".loader").hide();
          },
          success:function(data)
          {
            swal({title:"Done!", text:"You now have full access to this trip itinerary.", type:"success"},function() {location.reload();})            
          },
          error: function (request, status, error) {
            swal("Error", "something went wrong.", "error");
          }
        });
    });
}

</script>
