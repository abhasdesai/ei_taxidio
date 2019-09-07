<script>
	window.onload = function() {
		var d = new Date().getTime();
		document.getElementById("tid").value = d;
	};
</script>
<div class="container">
		<?php echo form_open('package/ccavRequestHandler',array('name'=>'customerData','id'=>'customerData','class'=>'form-horizontal')); ?>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Package Purchased:</label>
				<div class="col-sm-6"><?php echo $package_details->package_name;?></div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Number Of Travel Guides You Can Download:</label>
				<div class="col-sm-6"><?php echo $package_details->package_qty;?></div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-sm-12 text-center">
					<h2>Mandatory information</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">TID</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="tid" id="tid" readonly />
				</div>
			</div>
		</div>
		<input class="form-control" type="hidden" name="merchant_id" value="188710"/>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Order Id</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" readonly name="order_id" value="<?php echo uniqid(); ?>" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<?php 
					$amount = $package_details->package_price+(($package_details->package_price*18)/100);
				?>
				
				<label class="col-sm-6 control-label">Amount(Including GST)</label>
				
				<div class="col-sm-6">
					<input readonly class="form-control" type="text" name="amount" value="<?php echo number_format((float)$amount, 2, '.', ''); ?>"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Currency</label>
				
				<div class="col-sm-6">
					<input readonly class="form-control" type="text" name="currency" value="INR"/>
				</div>
			</div>
		</div>
		<input class="form-control" type="hidden" name="redirect_url" value="<?php echo site_url('package/returnurl') ?>"/>
		<input class="form-control" type="hidden" name="cancel_url" value="<?php echo site_url(); ?>"/>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Language</label>
				
				<div class="col-sm-6">
					<input readonly class="form-control" type="text" name="language" value="EN"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-sm-12 text-center">
					<h2>Billing information</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Name<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_name" value="<?php echo $this->session->userdata('name');?>"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Address<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_address" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing City<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_city" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing State<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_state" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Zip<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_zip" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Country<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_country" value="India"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Tel<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="text" name="billing_tel" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Billing Email<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input required class="form-control" type="email" name="billing_email" value="<?php echo $this->session->userdata('email');?>"/>
				</div>
			</div>
		</div>
		<?php ?><div class="row">
			<div class="form-group">
			<div class="col-sm-12 text-center">
				<div class="alert alert-info">
				<strong>Shipping information(optional)</strong>
				</div>
			</div>
			</div>
		</div><?php ?>
		<?php ?><div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping Name<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_name" value="Chaplin"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping Address<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_address" value="room no.701 near bus stand"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping City<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_city" value="Hyderabad"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping State<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_state" value="Andhra"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping Zip<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_zip" value="425001"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping Country<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_country" value="India"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Shipping Tel<sup>*</sup></label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="delivery_tel" value="9876543210"/>
				</div>
			</div>
		</div>
		* <?php ?>
		<?php /*?>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Promo Code</label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="promo_code" value=""/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label">Vault Info.</label>
				
				<div class="col-sm-6">
					<input class="form-control" type="text" name="customer_identifier" value=""/>
				</div>
			</div>
		</div>
		* <?php */?>
		<div class="row">
			<div class="form-group">
			<div class="text-center">
				<input class="link-button" type="submit" value="Proceed to Pay">
			</div>
			</div>
		</div>
      <?php echo form_close();?>
</div>
