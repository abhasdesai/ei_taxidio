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
            						<th>Name</th>
            						<th>Email</th>
            						<th>Created</th>
            						<th>Action</th>
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



	function renderaction(data, type, row)
	{
		    var value = "<a href='<?php echo site_url('admins/Users/View') ?>"+'/'+data+"' title='' data-placement='top' data-toggle='tooltip' data-original-title='View'><span class='fa fa-fw fa-eye'></span></a>";
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
						"sAjaxSource": "<?php echo site_url('admins/Users/getTable') ?>",
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
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "22%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderdata,"sWidth": "22%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderdata,"sWidth": "20%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": false, "mRender":renderaction,"sWidth": "5%" }
						]
				});
			});




</script>
