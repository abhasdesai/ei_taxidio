<div class="container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Forum</h4>
        </div>
    </div>
    
    
    <div class="row">
      


      
           <div class="col-sm-12">


              <?php if($this->session->flashdata('success')){ ?>
        <div class="alert bg-success">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
        <?php  }else if($this->session->flashdata('error')){  ?>
        <div class="alert bg-danger"">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
        <?php } ?>



            <div class="card-box">
                 
                <div class="table-rep-plugin">
                    <?php if(count($forum)){ ?>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table  table-striped">
                            <thead>
                                <tr>
                                    <th data-priority="1" width="35%">Itinerary</th>
                                    <th data-priority="2" width="40%">Question</th>
                                    <th data-priority="3" width="8%">Total <br/> Comments</th>
                                    <th data-priority="4" width="15%">Created</th>
                                    <th data-priority="5" width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($forum as $list){ ?>
                                <tr>
                                    <td><?php echo $list['user_trip_name'].' -By '.$list['name']; ?></td>
                                    <td><?php echo $list['question']; ?></td>
                                     <td><?php echo str_pad($list['totalcomments'], 2, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo date('dS, M Y',strtotime($list['created'])); ?></td>
                                    <td><a href="<?php echo site_url('itinerary-discussion').'/'.$list['id'] ?>" class="view-btn" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                    </td>
                                </tr>

                             <?php } ?>   
                                
                            </tbody>
                        </table>

                        <?php echo $pagination; ?>

                     </div>
                    <?php }else{ ?>
                         <div class="alert alert-info">
                             Nothing To Show.
                        </div>
                    <?php } ?> 
                </div>
            </div>
        </div>
        
    </div>
    
 </div> <!-- container -->
 
 <script>
     
 function deleteFeedback(id)
 {
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Feedback",
            type: "error",
            showCancelButton: true,
            cancelButtonClass: 'btn-white btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light btn-pop-delete',
            confirmButtonText: 'Yes!'
        });

    $(".btn-pop-delete").click(function(){
        window.location="<?php echo site_url('deleteFeedback') ?>"+"/"+id;
    })
 }

 </script>

