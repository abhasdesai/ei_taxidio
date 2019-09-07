<section class="content">
	<!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
			   <div class="box-body">
				<?php if ($this->session->flashdata('success')) {?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php }?>
			<?php if ($this->session->flashdata('error')) {?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php }?>
            <div class="box-header">
                  <h3 class="box-title"><?php echo $section; ?></h3>
                  <hr class="hrstyle">
                </div><!-- /.box-header -->
				<div class="pull-right marrgt20">
					<a class="btn btn-block btn-info btnadd" href="<?php echo site_url('admins/city/Cities/add'); ?>">Add New</a>
                </div>
            <div class="box-body">

			 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th>Id</th>
						<th>City</th>
						<th>Country</th>
						<th>Events</th>
						<th>Attractions</th>
						<th>Relaxation & Spa</th>
						<th>Restaurants & Nightlife</th>
						<th>Sports & Stadiums</th>
						<th>Adventure</th>
						<th>Weather</th>
						<th>Clothes</th>
						<!--<th>Destination/Optional Tags</th>-->
						<th>Mandatory Tags</th>
						<th>Hotel Cost</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                  <?php /* ?>
<div class="alert alert-success" id="order" style="display:none;">
Sort order updated.
</div>
<div class="pull-right marrgt20">
<input type="button" class="btn btn-block btn-primary btn-flat" value="Update Order" style="width:100%" onclick="updatesortorder()"/>
</div>
<?php */?>
				</div>
			</div><!-- /.box-body -->
          </div><!-- /.box -->
	</section><!-- /.content -->

<script type="text/javascript" charset="utf-8">

	function confirm_delete(id) {

	  var value= confirm('Are you sure you want to delete this City ?');

	  if(value)
	  {
		 window.location = '<?php echo site_url("admins/city/Cities/delete") ?>'+"/"+id;
	  }
	}


	function renderaction(data, type, row)
	{
		    var value = "<a href='<?php echo site_url('admins/city/Cities/edit') ?>"+'/'+data+"' title='' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><span class='glyphicon glyphicon-edit'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='confirm_delete("+data+");' title='' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><span class='glyphicon glyphicon-remove'></span></a>  ";
			return value;
	}

	function renderEventRedirect(data, type, row){
	    var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Events' href='<?php echo site_url('admins/city/Events/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-bell'></span></a>";
		return value;
	}

	function renderRelaxationRedirect(data, type, row){
	    var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Relaxation & Spa' href='<?php echo site_url('admins/city/Relaxationspa/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-child'></span></a>";
		return value;
	}

	function renderPaidAttractionRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Attractions' href='<?php echo site_url('admins/city/Cityattractions/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-camera'></span></a>";
		return value;	
	}

	function renderStadiumsRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Attractions' href='<?php echo site_url('admins/city/Stadiums/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-futbol-o'></span></a>";
		return value;	
	}

	function renderRestaurantRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Restaurants' href='<?php echo site_url('admins/city/Restaurants/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-hotel'></span></a>";
		return value;	
	}

	function renderClothesRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Restaurants' href='<?php echo site_url('admins/city/Clothes/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-user-secret'></span></a>";
		return value;	
	}

	function renderWeatherRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Restaurants' href='<?php echo site_url('admins/city/Weathers/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-cloud'></span></a>";
		return value;	
	}

	function renderAdventureRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Restaurants' href='<?php echo site_url('admins/city/Adventures/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-space-shuttle'></span></a>";
		return value;	
	}



	function renderMandatorytagRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Mandatory Tags' href='<?php echo site_url('admins/city/MandatoryTags/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-warning'></span></a>";
		return value;	
	}

	function renderHotelCostRedirect(data, type, row)
	{
		var value = "<a title='' data-placement='top' data-toggle='tooltip' data-original-title='Manage Hotel Costs' href='<?php echo site_url('admins/city/Hotelcosts/index') ?>"+'/'+row[0]+"'></i><span class='fa fa-fw fa-money'></span></a>";
		return value;		
	}
	

	function renderucwords(data, type, row){
	    return data;
	}

	function renderdata(data, type, row){
	    return data;
	}




			$(document).ready(function()
			{
				$('#example1').dataTable({
						"bProcessing": true,
						"responsive": true,
						"scrollX": true,
						"bServerSide": true,
						"sServerMethod": "POST",
						"sAjaxSource": "<?php echo site_url('admins/city/Cities/getTable') ?>",
						"sAjaxDataProp": "data",
						"sPaginationType": "full_numbers",
						"iDisplayLength": 10,
						"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"aaSorting": [[0, 'desc']],
						"bScrollCollapse": true,
						"bJQueryUI": true,
						"stateSave": true,
					    stateSaveCallback: function(settings,data) {
					    localStorage.setItem('DataTables_'+window.location.pathname, JSON.stringify(data) )
					    },
					    stateLoadCallback: function(settings) {
					    return JSON.parse( localStorage.getItem( 'DataTables_'+window.location.pathname) )
					    },
						"oLanguage": {
									"sLengthMenu": 'Show <select name="example1_length" aria-controls="example1" class="form-control input-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries'
						},
						"fnDrawCallback": function (oSettings) {
							$('a').tooltip();
						},
						"aoColumns": [
						{ "bVisible": false, "bSearchable": false, "bSortable": true},
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "12%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "12%" },
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderEventRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderPaidAttractionRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderRelaxationRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderRestaurantRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderStadiumsRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderAdventureRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderWeatherRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderClothesRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderMandatorytagRedirect},
						{ "bVisible": true, "bSearchable": false, "bSortable": false, "mRender":renderHotelCostRedirect},
						{ "bVisible": true, "bSearchable": true, "bSortable": false, "mRender":renderaction}
						]
				});
			});


</script>
