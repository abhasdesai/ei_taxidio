<?php
	if(isset($_POST['upload'])){
?>
<script>
	$(window).load(function(){
		$('#contestModal').modal('show');
	});
</script>
<?php
	}
?>

<!-- <?php// echo form_open_multipart('upload/upload_file');?> -->
<style type="text/css">
	.heading{padding: 30px; width: 600px; margin: 170px auto 0 auto; border-radius: 15px; -webkit-box-shadow: 1px 1px 44px 1px rgba(105,105,105,1);
-moz-box-shadow: 1px 1px 44px 1px rgba(105,105,105,1);
box-shadow: 1px 1px 44px 1px rgba(105,105,105,1);}

	.heading h3{text-align: center; font-size: 22px; line-height: 40px; }

</style>
            
			<!-- <p class="col-md-12 contest">There are no contests live currently. We’ll be back with something interesting soon.</a></p> --> 
			<div class="heading">
			<h3 class="sub-heading">There are no contests live currently.<br /> We’ll be back with something interesting soon.</h3>
			</div>
			<!-- <p class="col-md-offset-5 col-md-2"><a class="link-button modal-link"><input type="file" name="userfile"/> </a></p> -->
			
			<!-- <br /><br /><br /><br />
			<p class="col-md-offset-5 col-md-2"><input type="file" name="userfile"/></p>
			<br /><br />
			 <?php //if($this->session->userdata('fuserid')!= ""){ ?>
			 <p class="col-md-offset-5 col-md-2"><input type="submit" name="upload" id="upload" value="Upload" class="link-button modal-link" onclick="$('#contestModal').modal('show');" /></p>
			 <?php  //}else{ ?>
			 <p class="col-md-offset-5 col-md-2"><input type="button" class="link-button modal-link" name="stop" onclick="$('#myModal').modal('show');" value="Upload" /></p>
			<?php //} ?> -->
</form>

<div class="modal fade" id="contestModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="box box-primary">
					<div class="box-body">
						<h4 style="margin-bottom: 20px;">Your file has been uploaded successfully. All the best!</h4>
					</div>

					<div class="box-footer">
						<button type="button" data-dismiss="modal" class="link-button">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="contestModal1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="box box-primary">
					<div class="box-body">
						<h4 style="margin-bottom: 20px;">Oops! Looks like something went wrong. Please upload your file again.</h4>
					</div>

					<div class="box-footer">
						<button type="button" data-dismiss="modal" class="link-button">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>