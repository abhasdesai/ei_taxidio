<div class="container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-9">
            <h4 class="page-title">Feedback History</h4>
        </div>
         <div class="col-sm-3 create-feedback">
           <a href="<?php echo site_url('createFeedback'); ?>" class="btn btn-default waves-effect waves-light pull-right">New Feedback</a>
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


        
           <div class="col-sm-12">
            <div class="card-box">
                 
                <div class="table-rep-plugin">
                    <?php if(count($feedbacks)){ ?>
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table  table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th data-priority="1">Created</th>
                                    <th data-priority="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($feedbacks as $list){ ?>
                                <tr>
                                    <td><?php echo $list['subject']; ?></td>
                                    <td><?php echo $list['created']; ?></td>
                                    <td><a href="<?php echo site_url('viewFeedback').'/'.md5($list['id']) ?>" class="view-btn"><i class="glyphicon glyphicon-eye-open"></i></a>
                                    <?php $id= "'".md5($list['id'])."'"; ?>
                                    <a href="javascript:void(0);" class="delete-btn" onClick="deleteFeedback(<?php echo $id; ?>)"><i class="glyphicon glyphicon-trash"></i></a>
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

