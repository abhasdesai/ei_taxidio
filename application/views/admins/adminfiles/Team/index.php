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
					<a class="btn btn-block btn-info btnadd" href="<?php echo site_url('admins/Team/add'); ?>">Add New</a>
                </div>
            <div class="box-body">

			 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th>Id</th>
						<th>Person</th>
						<th>Designation</th>
						<th>Sortorder</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                  
				<div class="alert alert-success" id="order" style="display:none;">
				Sort order updated.
				</div>
				<div class="pull-right marrgt20">
				<input type="button" class="btn btn-block btn-primary btn-flat" value="Update Order" style="width:100%" onclick="updatesortorder()"/>
				</div>

				</div>
			</div><!-- /.box-body -->
          </div><!-- /.box -->
	</section><!-- /.content -->

<script type="text/javascript" charset="utf-8">

	function confirm_delete(id) {

	  var value= confirm('Are you sure you want to delete this Team Member ?');

	  if(value)
	  {
		 window.location = '<?php echo site_url("admins/Team/delete") ?>'+"/"+id;
	  }
	}


	function renderaction(data, type, row)
	{
		    var value = "<a href='<?php echo site_url('admins/Team/edit') ?>"+'/'+data+"' title='' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><span class='glyphicon glyphicon-edit'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='confirm_delete("+data+");' title='' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><span class='glyphicon glyphicon-remove'></span></a>  ";
			return value;
	}

	function renderinput(data, type, row)
	{
		if(data=='99999')
		{
			data=0;
		}
		return "<input type='text' id='sortorder' name='sortorder[]' class='form-control' onkeypress='return numbersonly(event)' value='"+data+"'/><input type='hidden' name='id[]' id='id' value='"+row[0]+"'/>"
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
						"sAjaxSource": "<?php echo site_url('admins/Team/getTable') ?>",
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
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "22%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderinput,"sWidth": "20%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": false, "mRender":renderaction,"sWidth": "5%" }
						]
				});
			});



function numbersonly(e)
{
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode<48||unicode>57 )
	{

		if(unicode==9 || unicode==8 || unicode==46 || unicode==37 || unicode==39 || unicode==8)
		{

			return true;
		}
		else
		{

			return false
		}
	}

}

function updatesortorder()
{

	var order = $("input[id='sortorder']").map(function(){return $(this).val();}).get();
	var id = $("input[id='id']").map(function(){return $(this).val();}).get();

	$.ajax({
			type:'POST',
			url:'<?php echo site_url("admins/Team/updateSortOrder") ?>',
			data:'id='+id+'&order='+order,
			success:function(data)
			{
				$("#suc").hide();
				$("#order").show();
			}
		});

}

</script>
