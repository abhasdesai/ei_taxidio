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

            <div class="box-body">

			 <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
  						<th>Id</th>
  						<th></th>
  						<th>Itineraries</th>
  						<th>Name</th>
              <th>Trip Type</th>
  						<th>Trip Mode</th>
              <th>Created</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

				</div>
			</div><!-- /.box-body -->
          </div><!-- /.box -->
	</section><!-- /.content -->

<script type="text/javascript" charset="utf-8">

	function confirm_delete(id) {

	  var value= confirm('Are you sure you want to delete this Month ?');

	  if(value)
	  {
		 window.location = '<?php echo site_url("admins/Months/delete") ?>'+"/"+id;
	  }
	}


	function renderaction(data, type, row)
	{
		    var value = "<a href='javascript:void(0);' onclick='confirm_delete("+data+");' title='' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><span class='glyphicon glyphicon-remove'></span></a>  ";
			return value;
	}


	function renderucwords(data, type, row){
	    return data;
	}

	function renderTrip(data, type, row)
	{
		var url='<?php echo site_url("planned-itinerary-forum") ?>'+'/'+row[1];
	    return '<a href='+url+' target="_blank">'+data+'</a>';
	}

	function renderCheckbox(data, type, row)
	{
	    if(data==0)
	    {
	    	return '<input type="checkbox" onclick="return false;" disabled="true"/>';
	    }
	    else
	    {
	    	return '<input type="checkbox" checked onclick="return false;" disabled="true"/>';
	    }

	}

	function renderMode(data, type, row)
	{
		 if(data==1)
		 {
		 	return 'Private';
		 }
		 else
		 {
		 	return 'Public';
		 }
	}

  function renderItiType(data, type, row)
  {
      if(data==1)
      {
         return 'Single Country';
      }
      else if(data==2)
      {
         return 'Multi Country';
      }
      else
      {
         return 'Search City';
      }
  }
			$(document).ready(function()
			{
				$('#example1').dataTable({
						"bProcessing": true,
						"responsive": true,
						"scrollX": true,
						"bServerSide": true,
						"sServerMethod": "POST",
						"sAjaxSource": "<?php echo site_url('admins/Planneditineraries/getTable') ?>",
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
						{ "bVisible": false, "bSearchable": false, "bSortable": true},
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderTrip,"sWidth": "35%" },
            { "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "15%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderItiType,"sWidth": "10%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "10%", "mRender":renderMode},
            { "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "10%", "mRender":renderucwords},
          ]
				});
			});


</script>
