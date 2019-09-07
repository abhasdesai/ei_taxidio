<!DOCTYPE html>
<html>
<head>
	<title>Invoice</title>
</head>

<body>
    <div style="padding: 30px;">
    	<table align="center" style="border: 1px solid #333;" width="100%">
    		<tr>
    			<td>
					<div class="invoice-header" style="padding-left: 30px;">
						<table width="100%">
							<tr>
								<td width="50%">
									<h2>Tax Invoice</h2>
									<h4>Taxidio Travel India Pvt. Ltd.</h4>
									<p>302,&nbsp;Doli Chambers,<br>
									Arthur Bunder Road,&nbsp;Colaba<br>
									Mumbai - 400 005,<br>
									India<br>
									</p>
									<span>Email:&nbsp;info@taxidio.com</span>
									<br>
									<span>Website:&nbsp;www.taxidio.com</span>

								</td>
								<td width="50%">
									<a href="<?php echo base_url();?>"><img src="assets/images/logo.png" alt="Taxidio - The Where, Why &amp; How Traveler" > </a><br>
									<span>Invoice No:&nbsp;</span><span><?=$invoice['txn_id'];?></span><br>
									<span>Invoice Date:&nbsp;</span><span><?=$invoice['date'];?></span>
								</td>
							</tr>							
						</table>
					</div>
				</td>
			</tr>
		<tr>
		    <td>	
				<div class="invoice-body" style="padding-left: 30px;">
					<table width="100%">						
						<tr>
							<td width="50%">
								<span>Issued To:</span><span></span><br>
								<span>Name:</span>&nbsp;<span><?=$invoice['billing_name'];?></span><br>
								<span>Address:</span>&nbsp;<span><?=$invoice['billing_address'];?></span><br>
								<span>Phone:</span>&nbsp;<span><?=$invoice['billing_phno'];?></span><br>
								<span>Email:</span>&nbsp;<span><?=$invoice['billing_email'];?></span><br>	
								<span>Category</span><br>
								<span>Travel Guide - </span>
								<?php 
								if($invoice['package_qty'] == 1)
									{ ?>
										<span>Single</span>
									<?php
									 }
								else
									{
										?>
										<span>Pack of <?=$invoice['package_qty'];?></span>
										<?php
									}
								?>
								<br>
								<span><b>Sub-total (In Rupees)</b></span>
								
							</td>
							<td width="50%" style="padding-top: 150px;">
								
								<span>Amount&nbsp;(In Rupees)</span><br>
								<?php $GST = ($invoice['amount'] * 18) / 100;?>
								<span><?=$invoice['package_price'];?></span><br>
								<span><?=$invoice['package_price'];?></span><br>
								<?php if(strtolower($invoice['billing_state']) == 'maharashtra')
								{ ?>
									<span>IGST @ 18% &nbsp;</span><span> <?=$GST;?></span><br>
								    
								<?php
								}
								else
								{
								?>
								    <span>CGST @ 9% </span><span> <?=$GST/2;?></span><br>
							     	<span>SGST @ 9% </span><span> <?=$GST/2;?></span><br>
								<?php
								} ?>
								
								<b><span>Total&nbsp;(In Rupees)</span><span> <?=$invoice['amount'];?></span></b>
								
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
		    <td>		
				<div class="invoice-footer">
					<table width="100%">
						<tr>
							<td>
								<h4>If you have any concerns with this invoice, please send an email to info@taxidio.com within 24 hours of receiving the invoice.</h4>
								<h4>This is a computer generated invoice. It does not require a signature or seal for authorization.</h4>
								<h4><?php echo date('d-m-Y H-i-s a')?></h4>
							</td>
						</tr>		
					</table>
				</div>
			</td>
		</tr>		
	</table>
	</div>	
</body>

</html>
