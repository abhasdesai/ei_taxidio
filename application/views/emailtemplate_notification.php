<html>
<head></head>
<body>
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
								<?php /*?><p>Hello <?php echo $name; ?></p><?php */?>
								<p>Hey there!</p><br>
								<p>Your trip to <?= $trip_name?><?php /* Replace with the trip name */?> is only 1 day away. Are you ready to travel?</p>
								<p>
									You can make last minute changes to your trip, share your itinerary with your co-travelers, add notes and much more. If you need more information about the destination you are traveling to, you can instantly purchase and download Travel Guides of your choice.
								</p>
								<p>If you need any further assistance, drop us an email on <a href="mailto:info@taxidio.com">info@taxidio.com.</a></p>
			  				<p>From :<?php
			  					echo $from_date;
			  				 ?></p>
			  				 <p>To :<?php
			  					echo $to_date;
			  				 ?></p>
			  				</div>
							<p style="font-size: 15px; text-align: left;"><br/>Safe travels,<br>Team Taxidio.</p>
			  			</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	
</tbody>
</table>
</body>
</html>
