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
						<th>Sort for Home Page Planned Itinerary</th>
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

	  var value= confirm('Are you sure you want to delete this Month ?');

	  if(value)
	  {
		 window.location = '<?php echo site_url("admins/Months/delete") ?>'+"/"+id;
	  }
	}

/*
	function renderaction(data, type, row)
	{
		arr = data.split('-');
		 if(arr[0]==2)
		 {
		    var value = "<a href='<?php echo site_url('admins/Planneditineraries/edit') ?>"+'/'+arr[1]+"' title='' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><span class='glyphicon glyphicon-edit'></span></a>";
		 }else{
		 	var value = "";
		 }
			return value;
	}*/

	function renderinput(data, type, row)
	{
		arr = data.split('-');
		if(arr[1]=='99999')
		{
			data=0;
		}
		else
		{
			data=arr[1];
		}
		if(arr[0]==2)
		{
			return "<input type='text' id='sortorder' name='sortorder[]' class='form-control' onkeypress='return numbersonly(event)' value='"+data+"'/><input type='hidden' name='id[]' id='id' value='"+row[0]+"'/>"
		}
		else
		{
		 	return "";
		}
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
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderTrip,"sWidth": "30%" },
            			{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "15%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderItiType,"sWidth": "10%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "5%", "mRender":renderMode},
            			{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "5%", "mRender":renderucwords},
						{ "bVisible": true, "bSearchable": true, "bSortable": false, "mRender":renderinput,"sWidth": "10%" }
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
			url:'<?php echo site_url("admins/Planneditineraries/updateSortOrder") ?>',
			data:'id='+id+'&order='+order,
			success:function(data)
			{
				$("#suc").hide();
				$("#order").show().delay(4000).slideUp(1000);
			}
		});

}


</script>
