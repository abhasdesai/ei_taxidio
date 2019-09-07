<?php if(count($months) && $_POST['hoteltype_id']!=''){ ?>
	 <div class="row">
	   <?php $i=0; foreach($months as $list){
		 $cost=$this->Hotelcost_m->getMonthWiseCost($list['id'],$_POST['city_id'],$_POST['hoteltype_id']);
	   ?>

	 	 <div class="col-md-6">
			  <div class="form-group">
				<label><?php echo $list['month_name']; ?></label>
				<input type="hidden" name="month_id[]" value="<?php echo $list['id']; ?>"/>
				<input type="text" id="cost" name="cost[]" class="form-control number" maxlength="11" value="<?php if(isset($cost['cost']) && $cost['cost']!=''){ echo $cost['cost']; } ?>" autocomplete="off" onkeypress="return numbersonly(event)"/>
				<?php echo form_error('cost'); ?>
			 </div><!-- /.form-group -->
		</div>
		<?php $i++; } ?>	
	</div>	

	 <table>
				<tr>
					<td><input type="button" class="btn btn-block btn-primary" id="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
				</tr>
			</table>


<script>
	
$("#btnsubmit").click(function(){
if($("#form1").valid())
{	
	$.ajax({
			type:'POST',
			url:'<?php echo site_url("admins/city/Hotelcosts/saveData") ?>',
			data:$("#form1").serialize(),
			success:function(data)
			{
				$("#msg").show();
				$('html, body').animate({
			        				scrollTop: $(".main-header").offset().top
			    				}, 800);
			}
	});
}
});	

function numbersonly(e)
 {
	
	
	var unicode=e.charCode? e.charCode : e.keyCode
	if (unicode!=8 )
	{
		if (unicode<48||unicode>57 ) 
		{
			
			 if(unicode==9 || unicode==8 || unicode==46 || unicode==37 || unicode==39  || unicode==116)
			{
				
				return true;
			}
			else
			{
				
				return false 
			}
		}
	}
}
</script>

	
<?php } ?>