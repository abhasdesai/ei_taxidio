<div id="iti-share" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div id="iti_share" class="modal-dialog modal-sm share-dialog">
</div>
</div>
<div class="wraper container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Notificaions</h4>
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
                <table class="table  table-striped">
                            <thead>
                                <tr>

                                    <td data-priority="1" ><b>Trip Name</b></td>
                                    <td data-priority="2" ><b>From Date</b></td>
                                    <td data-priority="2" ><b>To Date</b></td>

                                    

                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($trip_details as $list){ ?>

                            <?php
                            $from_date =  date('d-m-Y', strtotime($list['from_date']. ' + 1 days'));
                            //$data['days'] = $value['days'];
                            $to_date =  date('d-m-Y', strtotime($list['end_date']. ' + 1 days'));



                            /*$from_date = date('d-m-Y',strtotime($list['from_date']));
                            $to_date =  date('d-m-Y', strtotime($from_date. ' + '.$list['days'].' days'));*/
                             ?>
                                <tr>
                                    <td><?php echo $list['user_trip_name']/*.' -By '.$list['name']*/; ?></td>
                                    
                                    <td><?php echo $from_date; ?></td>
                                     <td><?php echo $to_date; ?></td>
                                    
                                   
                                </tr>

                             <?php } ?>   
                                
                            </tbody>
                        </table>
            </div>

        </div>

    </div>
    </div> <!-- container -->

