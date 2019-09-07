<section class="content">
	<!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
			   <div class="box-body">
				<?php if($this->session->flashdata('success')){ ?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php } ?>  
			<?php if($this->session->flashdata('error')){ ?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php } ?>  
            <div class="box-header">
                  <h3 class="box-title">Faq</h3>
                  <hr class="hrstyle">
                </div><!-- /.box-header -->
				<div class="pull-right marrgt20">
					<a class="btn btn-block btn-info btnadd" href="<?php echo site_url('admins/faq/add'); ?>">Add New</a> 
                </div>	
            <div class="box-body">
			 
			 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th>Id</th>
						<th>Category</th>
						<th>Question</th>
                        <th>Answer</th>
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

	function confirm_delete(id) {

	  var value= confirm('Are you sure you want to delete this faq ?');

	  if(value)
	  {
		 window.location = '<?php echo site_url("admins/faq/delete") ?>'+"/"+id;
	  }
	}

	
     function rendercheckbox( data, type, row )
    {
		if (data == 1)
        {
            var checked = "checked";
        }
        else
        {
            var checked = "";
        }
        var value = "<div class='make-switch'><input type='checkbox' " + checked + " onclick='return false' /></div>";
        return value;
    }
    
   
	function renderaction(data, type, row){
		    var value = "<a href='<?php echo site_url('admins/faq/edit') ?>"+'/'+data+"' title='' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><span class='glyphicon glyphicon-edit'></span></a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='confirm_delete("+data+");' title='' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><span class='glyphicon glyphicon-remove'></span></a>  ";
			return value;
		}

	
	function renderucwords(data, type, row){
	    var str=data;
		return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
		});
	}
	
	
	
	 


			$(document).ready(function()
			{
				$('#example1').dataTable({
						"bProcessing": true,
						"responsive": true,
						"scrollX": true,
						"bServerSide": true,
						"sServerMethod": "POST",
						"sAjaxSource": "<?php echo site_url('admins/faq/getTable') ?>",
						"sAjaxDataProp": "data",
						"sPaginationType": "full_numbers",
						"iDisplayLength": 10,
						"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"aaSorting": [[0, 'desc']],
						"bScrollCollapse": true,
						"bJQueryUI": true,
						"oLanguage": {
									"sLengthMenu": 'Show <select name="example1_length" aria-controls="example1" class="form-control input-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries'
						},
						"fnDrawCallback": function (oSettings) {
							$('a').tooltip();
						},
						"aoColumns": [
						{ "bVisible": false, "bSearchable": false, "bSortable": true},
						{ "bVisible": true, "bSearchable": false, "bSortable": false,"mRender":renderucwords,"sWidth": "15%" },
						{ "bVisible": true, "bSearchable": false, "bSortable": false,"mRender":renderucwords,"sWidth": "30%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": true, "mRender":renderucwords,"sWidth": "35%" },
						{ "bVisible": true, "bSearchable": true, "bSortable": false, "mRender":renderaction,"sWidth": "5%" }
						]
				});
			});




</script>
