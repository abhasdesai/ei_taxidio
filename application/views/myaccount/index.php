<style type="text/css">
    
    .calendar-event-modal > .modal-header > .modal-title {
        font-size: 20px !important;
        color: #fff !important;
    }

    

</style>

<div class="content">
<div class="container">

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box fadeInDown animated">
                <div class="bg-icon bg-icon-primary pull-left">
                    <i class="md md-done-all text-primary"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo str_pad($trips['completed'], 2, '0', STR_PAD_LEFT); ?></b></h3>
                    <?php /*?><p class="text-muted">Trips Completed</p><?php */?>
                    <p class="text-muted">Completed Trips</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-pink pull-left">
                   <i class="md md-directions-walk text-pink"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo str_pad($trips['inprogress'], 2, '0', STR_PAD_LEFT); ?></b></h3>
                    <p class="text-muted">Trips in Progress</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-info pull-left">
                    <i class="md md-event text-info"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo str_pad($trips['upcoming'], 2, '0', STR_PAD_LEFT); ?></b></h3>
                    <p class="text-muted">Upcoming Trips</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        
        <!--
        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-success pull-left">
                    <i class="md md-remove-red-eye text-success"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">64</b></h3>
                    <p class="text-muted">Total Visits</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    -->

	<!-- Calendar Row  -->
    <div class="row">
		<div class="col-md-12">
        	<div class="card-box">
               <div class="colors-for-cal">
				   <div class="suggestions-cal">
						<div class="completed-trip"></div>
						<span class="label label-completed">Completed Trip</span>
					</div>
					<div class="suggestions-cal">
						<div class="inprogress-trip"></div>
						<span class="label label-inprogress">Trips in progress</span>
					</div>

					<div class="suggestions-cal">
						<div class="upcoming-trip"></div>
						<span class="label label-upcoming">Upcoming Trip</span>
					</div>
                </div>
            	<div id="calendar"></div>
            </div>
       </div>
    </div>
    <!-- end row -->
    <!-- BEGIN MODAL -->
            <div class="modal fade" role="dialog" id="event-modal">
                <div class="modal-dialog">
                    <div class="modal-content calendar-event-modal" style="padding:0px !important;">
                        <div class="modal-header">
                            
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><strong>Add Note</strong></h4>
                        </div>
                        <form class="form-horizontal" id="eventForm">
                        <div class="modal-body">

                            <div id="eventSuccess" class="alert alert-success alert-dismissible" style="display: none;">
                                Note Added Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <div id='eventError' class="alert alert-warning alert-dismissible" style="display: none;">
                                Some error occurred
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            
                            <div class="form-group">
                                <?php echo form_label('Select Dates',"date");?>
                                <?php echo form_input('date',set_value('date'),array('class'=>'form-control daterange','id'=>'date','required'=>'true'));?>
                            </div>
                            <div class="form-group">
								<?php echo form_label('Subject','event_subject');?>
								<?php echo form_input('subject',set_value('subject'),array('class'=>'form-control','id'=>'event_subject','required'=>'true'));?>
                            </div>
                            <div class="form-group">
                                <?php echo form_label('Description',"event_desc:");?>
                                <?php 
                                        $data = array(
                                            'name'        => 'event_desc',
                                            'id'          => 'event_desc',
                                            'value'       => set_value('event_desc'),
                                            'class'       => 'form-control',
                                            'rows'        => '5',
                                            'required'    => true,
                                        );

                                        echo form_textarea($data);
                                ?>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success save-event waves-effect waves-light">Add Note</button>
                            <?php /*?><button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button><?php */?>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

			<div class="modal fade none-border" id="update-note">
                <div class="modal-dialog">
                    <div class="modal-content calendar-event-modal">
                        <div class="modal-header">
                            
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><strong>Note Details</strong></h4>
                        </div>
                        <form class="form-horizontal" id="updateEventForm">
                        <div class="modal-body">

                            <div id="eventSuccess" class="alert alert-success alert-dismissible" style="display: none;">
                                Note Updated Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <div id='eventError' class="alert alert-warning alert-dismissible" style="display: none;">
                                Some error occurred
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            
                            <div class="form-group">
								<input type='hidden' name="noteID" id="noteID">
                                <?php echo form_label('Select Dates',"date");?>
                                <?php echo form_input('date',set_value('date'),array('class'=>'form-control daterange','id'=>'date','required'=>'true'));?>
                            </div>
                            <div class="form-group">
								<?php echo form_label('Subject','event_subject');?>
								<?php echo form_input('subject',set_value('subject'),array('class'=>'form-control','id'=>'event_subject','required'=>'true'));?>
                            </div>
                            <div class="form-group">
                                <?php echo form_label('Description',"event_desc:");?>
                                <?php 
                                        $data = array(
                                            'name'        => 'event_desc',
                                            'id'          => 'event_desc',
                                            'value'       => set_value('event_desc'),
                                            'class'       => 'form-control',
                                            'rows'        => '5',
                                            'required'    => true,
                                        );

                                        echo form_textarea($data);
                                ?>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger save-event waves-effect waves-light">Remove Note</button>
                            <?php /*?><button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button><?php */?>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


			<div class="modal fade none-border" id="response">
                <div class="modal-dialog">
                    <div class="modal-content calendar-event-modal">
                        <div class="modal-header">
                            
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
								Note Deleted Successfully
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>

            
            <!-- Modal Add Category -->
            <div class="modal fade none-border" id="add-category">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><strong>Add</strong> a category</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Category Name</label>
                                        <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Choose Category Color</label>
                                        <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                            <option value="success">Category1</option>
                                            <option value="danger">Category2</option>
                                            <option value="info">Category3</option>
                                            <option value="pink">Category4</option>
                                            <option value="primary">Category5</option>
                                            <option value="warning">Category6</option>
                                            <option value="inverse">Category7</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL -->
    
    
   <?php if(count($recenttrips)){ ?>

    <div class="row">

        <!-- col -->

    	<div class="col-lg-12">
    		<div class="card-box">
                
    			<h4 class="text-dark header-title m-t-0">Recent Trips</h4>
    			<p class="text-muted m-b-30 font-13">
					<?php /*?>Trips that you enjoyed with Taxidio !!<?php */?>
					Trips planned with Taxidio
				</p>

    			<div class="nicescroll p-20" style="height: 295px;">
                    <div class="timeline-2">
                        
                    <?php foreach($recenttrips as $list){ ?>

                        <div class="time-item">
                            <div class="item-info">
                                <div class="text-muted"><small><?php  ?><?php echo date('d M Y',strtotime($list['startdate'])) ?> - <?php echo date('d M Y',strtotime($list['enddate'])) ?></small></div>
                                <p><strong><a href="<?php echo $list['url'] ?>" class="text-info" target="_blank"><?php echo $list['tripname'] ?> Trip</a></strong> </p>
                            </div>
                        </div>

                         <?php } ?>   
                        
                    </div>
                </div>


    		</div>
    	</div>
    	<!-- end col -->



    </div>

    <?php } ?>
    <!-- end row -->


</div> <!-- container -->

</div>
</div>
