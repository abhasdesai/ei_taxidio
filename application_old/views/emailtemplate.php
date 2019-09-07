<html><head></head><body>
	<table align="center" cellspacing="0" cellpadding="0" border="0" width="600">
<tbody>
	<tr>
		<td align="left" valign="top"><div style="height:90px;text-align:center;"><center><img width="178" height="82" src="<?php echo site_url('assets/images/logo.png') ?>"></center></div></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#f1f69d" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; padding: 10px; border-width: 2px 2px 1px; border-style: solid; border-color: -moz-use-text-color rgb(253, 143, 17) rgb(255, 255, 255); color: rgb(253, 143, 17); background-color: transparent; border-bottom: 2px solid;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:10px;">
		<tbody>
			<tr>
			<td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: rgb(0, 0, 0);">
				<div style="font-size: 15px; text-align: left;">
  <h4>You have received a new inquiry from a Website</h4>
				Name: <span style="color: rgb(0, 0, 0);"> <?php echo $_POST['name']; ?></span><br><br>
				Email: <span style="color: rgb(0, 0, 0);"> <?php echo $_POST['email']; ?></span><br><br>
				Phone: <span style="color: rgb(0, 0, 0);"> <?php echo $_POST['phone']; ?></span><br><br>
				Message:<span style="color: rgb(0, 0, 0);"> <?php echo $_POST['message']; ?></span><br><br>
				</div>
			</td>
			</tr>
		</tbody>
			</table>
		</td>
	</tr>
	
</tbody>
</table>
</body></html>