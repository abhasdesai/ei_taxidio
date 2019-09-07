<section class="content">
		<div class="alert alert-success fade in" id="msg" style="display:none">
					Transaction Successful.
				</div>

				<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $section; ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->
		    <a href="<?php echo site_url('admins/city/Cities'); ?>" class="btn bg-navy margin">Back to Cities</a>

            <div class="box-body">
				<?php echo form_open('admins/Continents/add', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				
				<div class="row">
				 <div class="col-md-6">
				      <input type="hidden" name="city_id" value="<?php echo $id ?>"/>
					  <div class="form-group">
						<label>Hotel Type</label>
						<select id="hoteltype_id" name="hoteltype_id" class="required select2 form-control">
							<option value="">Select Hotel Type</option>
							<?php foreach ($hoteltypes as $list) {?>
								<option value="<?php echo $list['id'] ?>"><?php echo $list['hotel_type'] ?></option>
							<?php }?>
							</select>
					 </div><!-- /.form-group -->
				</div>
				</div>

				<div id="bindajax"></div>

             
				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

<script>
	
$("#hoteltype_id").change(function(){

if($("#form1").valid())
{
	$.ajax({
			type:'POST',
			url:'<?php echo site_url("admins/city/Hotelcosts/getData") ?>',
			data:$("#form1").serialize(),
			success:function(data)
			{
				$("#bindajax").html(data.body);
			}
	});
}
});

</script>          

</section><!-- /.content -->
