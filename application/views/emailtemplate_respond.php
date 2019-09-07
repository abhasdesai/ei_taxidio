<html><head></head><body>
	<table align="center" cellspacing="0" cellpadding="0" border="0" width="600">
<tbody>
	<tr>
		<td align="left" valign="top"><div style="height:100px;text-align:center;"><center><img width="178" height="82" src="<?php echo site_url('assets/images/logo.png') ?>"></center></div></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#f1f69d" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; padding: 10px; border-width: 2px 2px 1px; border-style: solid; border-color: -moz-use-text-color rgb(253, 143, 17) rgb(255, 255, 255); color: rgb(253, 143, 17); background-color: transparent;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:10px;">
		<tbody>
			<tr>
			<td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: rgb(0, 0, 0);">
				<div style="font-size: 15px; text-align: left;">
					<p>Hello <?php echo $_POST['name'].','; ?></p>
  				<h4>Thank you for writing to us.</h4>
					<p>
						We have received your message and you will find our reply in your inbox shortly.
					</p>
  				<p>If your inquiry is urgent, we request you to call us on the number provided below.</p>
  				</div>
				<p style="font-size: 15px; text-align: left;"><br/>Team Taxidio.</p>
  			</td>
			</tr>
		</tbody>
			</table>
		</td>
	</tr>
	<tr style="width: 100%;">
	<td align="left" bgcolor="#005586" valign="top" style="border-left: 2px solid rgb(253, 143, 17); border-right: 2px solid rgb(253, 143, 17); background-color: #492F68; border-bottom: 3px solid rgb(253, 143, 17);">
	<table cellspacing="0" cellpadding="15" border="0" width="100%">
		<tbody>
			<tr style="width: 100%;">
				<td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; text-align: center; font-size: 14px; padding: 10px; width: 33.33%; color: rgb(253, 143, 17); border-right: 1px solid rgb(255, 255, 255); font-weight: bold;">Phone</td>
				<td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; text-align: center; border-right: 1px solid rgb(255, 255, 255); font-size: 14px; padding: 10px; width: 33.33%; color: rgb(253, 143, 17); font-weight: bold;">Email</td>
				<td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; text-align: center; font-size: 14px; padding: 10px; width: 33.33%; color: rgb(253, 143, 17); font-weight: bold;">Address</td>
			</tr>
			<tr style="width: 100%;">
				<td align="left" valign="top" style="color: rgb(255, 255, 255); font-family: Arial,Helvetica,sans-serif; text-align: center; text-decoration: none; border-right: 1px solid rgb(255, 255, 255); font-size: 14px; padding: 0px 0px 10px; width: 33.33%;">
				<a href="javascript:void(0);" style="color:#ffffff;text-decoration: none"><?php echo $taxidio['phone_no']; ?></a>
				</td>
				<td align="left" valign="top" style="color: rgb(255, 255, 255); font-family: Arial,Helvetica,sans-serif; text-align: center; border-right: 1px solid rgb(255, 255, 255); padding: 0px 0px 10px; font-size: 14px; width: 33.33%;">
					<a href="mailto:<?php echo $taxidio['email']; ?>" style="color:#ffffff; text-decoration:none;"><?php echo $taxidio['email']; ?></a>
					</td>
					<td align="left" valign="top" style="color: rgb(255, 255, 255); font-family: Arial,Helvetica,sans-serif; text-align: center; padding: 0px 0px 10px; font-size: 14px; width: 33.33%;">
					<a href="javascript:void(0);" target="_blank" style="color:#ffffff; text-decoration:none;"><?php echo nl2br($taxidio['address']); ?></a>
				</td>
			</tr>
		</tbody>
	</table>
	</td>
	</tr>
</tbody>
</table>
</body></html>
