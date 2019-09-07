<?php
	if($this->session->flashdata('success')===1){
?>
<script>
	$(window).load(function(){
		$('#apiModal').modal('show');
	});
</script>
<?php
	}
?>

<div class="container api">
	<div class="col-md-7">
		<div class="row">
			<div id="api-page">
				<div class="col-md-12 para" style="text-align: justify;margin-bottom: 20px;">
					<div class="col-md-12">
						<h3>What is Taxidio for Business?</h3>
						<p>We provide a platform for hotel chains to automate their concierge services in order to enhance their customer experience.</p>
					</div>

					<div class="col-md-12">
						<h3>How does it work?</h3>
						<p>Taxidio aims to automate the concierge services across hotel chains, by providing them with an access to our Application Programme Interface (API) which consists of a comprehensive list of attractions within a city. These attractions can be filtered on the basis of a guest’s preferences.</p>
						<ul>
							<li>Your hotel will have its initial location marked by default on the map and on the top of the list of attractions. The list, in addition to the several activities and sightseeing points of the city, will consist of nearby restaurants, cafés, malls or other shopping arcades, supermarkets, ATMs, and so on.</li> 
							<li>The attractions will be marked on a straight line basis, for easy of commuting. Through this system, the hotel concierge can also enable the guest to book attraction tickets in advance.</li>
							<li>The itinerary created by the hotel concierge can be saved, printed and sent to the guest, along with a map.</li>
						</ul>
					</div>

					<div class="col-md-12">
						<h3>How does it help?</h3>
						<ul>
							<li>Automating the process of concierge services across hotels</li>
							<li>Book attraction tickets for guests and benefit from the incremental revenue</li>
							<li>Broaden database through concierge</li>
							<li>Focus on customer loyalty</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-5">
		<?php echo form_open('apimodel', array('name'=> 'form', 'enctype' => 'multipart/form-data', 'id'=>'form_api','class'=>'form-horizontal','style'=>'margin-bottom:20px')); ?>
		<!-- <form name="form" method="post" enctype="multipart/form-data" class="api-form" action="cms/apimodel"> -->

		<fieldset>
			<div class="col-md-6">
					<label class="control-label" for="first_name">First Name <span style="color: red;">*</span></label>
					<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo set_value('first_name') ?>" required>
					<p><?php echo form_error('first_name'); ?></p>
			</div>

			<div class="col-md-6">
				
				<label class="control-label" for="last_name">Last Name <span style="color: red;">*</span></label>
				<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo set_value('last_name') ?>" required>
				<p><?php echo form_error('last_name'); ?></p>
			
			</div>

			<div class="col-md-6">
				
				<label class="control-label" for="company">Company Name <span style="color: red;">*</span></label>
				<input type="text" name="company" id="company" class="form-control" value="<?php echo set_value('company') ?>" required>
				<p><?php echo form_error('company'); ?></p>
			
			</div>

			<div class="col-md-6">
				
				<label class="control-label" for="designation">Designation <span style="color: red;">*</span></label>
				<input type="text" name="designation" id="designation" class="form-control" value="<?php echo set_value('designation') ?>" required>
				<p><?php echo form_error('designation'); ?></p>
			
			</div>

			<div class="col-md-6">
				
				<label class="control-label" for="email">E-mail <span style="color: red;">*</span></label>
				<input type="email" name="email" id="email" class="form-control email" value="<?php echo set_value('email') ?>" required>
				<p><?php echo form_error('email'); ?></p>
			
			</div>

			<div class="col-md-6">
				
				<label class="control-label" for="phone">Phone</label>
				<input type="text" name="phone" id="phone" value="<?php echo set_value('phone') ?>" class="form-control" >
				<p><?php echo form_error('phone'); ?></p>
			
			</div>

			<div class="col-md-12">
				<label class="control-label" for="message">Message <span style="color: red;">*</span></label>
				<textarea rows="4" name="message" id="message" class="form-control" required><?php echo set_value('message') ?></textarea>
				<p><?php echo form_error('message'); ?></p>
			</div>

			<div class="col-md-12 api-submit">
				<input type="submit" name="apisubmit" id="apisubmit" value="Submit" class="link-button">
			</div>	
		</fieldset>
		</form>
	</div>
</div>

<div class="modal fade" id="apiModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="box box-primary">
					<div class="box-body">
						<h4 style="margin-bottom: 20px;">Thank you for your inquiry. Our team will get back to you shortly.</h4>
					</div>

					<div class="box-footer" style="text-align: center;">
						<button type="button" data-dismiss="modal" class="link-button">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 <script type="text/javascript">
 	$('#first_name').val('');
 	$('#last_name').val('');
 	$('#company').val('');
 	$('#designation').val('');
 	$('.email').val('');
 	$('#phone').val('');
 	$('#message').val('');
 </script>