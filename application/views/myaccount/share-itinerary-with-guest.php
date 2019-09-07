<!doctype html>
<html>
<head>
	<title>Share Itinerary with Guest</title>
</head>
<body>
<table align="center" cellspacing="0" cellpadding="0" border="0" width="600">
  <tbody>
    <tr>
      <td align="left" valign="top"><div style="height:90px;text-align:center;">
          <center>
            <img width="176" height="69" src="<?php echo site_url('assets/images/logo.png'); ?>">
          </center>
        </div></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#f1f69d" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; padding: 10px; border-width: 2px 2px 1px; border-style: solid; border-color: -moz-use-text-color rgb(253, 143, 17) rgb(255, 255, 255); color: rgb(253, 143, 17); background-color: transparent; border-bottom: 2px solid;"><table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:10px;">
          <tbody>
            <tr>
              <td align="left" valign="top" style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; color: rgb(0, 0, 0);"><div style="font-size: 15px; text-align: left;">
                  <p> Hey there!</p>
                  
                  <p>Take a look at our trip itinerary <span style="color: #f58634">“</span><strong style="color: #4e356b;"><?php echo $user_trip_name; ?></strong><span style="color: #f58634">”</span>. Register, go to <span style="color: #f58634">“</span><strong style="color: #4e356b;">My Account ->  Invited Trips</strong><span style="color: #f58634">”</span> and accept the invitation to access the itinerary.<br><br>
                  </p>
                  <p style="margin-bottom: 35px;">
                   You can make changes to the trip too. Just scroll, select and start! Simple, isn’t it?<br>
                  <a href="<?php echo site_url(); ?>" target="_blank">www.taxidio.com</a>
                  </p>
                <p>
                  <span style="color:#3e3b3b;font-family:Helvetica;font-size:14px">Awaiting an adventure,</span><br>
                  <span style="color:#3e3b3b;font-family:Helvetica;font-size:14px"><?php echo $_SESSION['name']; ?>.</span>
                </p>
                </div></td>
            </tr>
            <tr>
            	<td>
            		<!-- <p style="text-align: center;">
                  <a href="<?php echo site_url(); ?>" target="_blank"><img src="<?php echo site_url('assets/images/sign-up.png'); ?>" style="width:25%;"></a>
            		</p> -->
            		<p style="text-align: center;">
                  <a href="https://play.google.com/store/apps/details?id=com.taxidio" target="_blank"><img src="<?php echo site_url('assets/images/android-store.png'); ?>" style="width:25%;"></a>&nbsp;&nbsp;&nbsp;
                  <a href="https://itunes.apple.com/us/app/taxidio/id1355656223?ls=1&mt=8" target="_blank"><img src="<?php echo site_url('assets/images/apple-store.png'); ?>" style="width:25%;"></a>
            		</p>
            	</td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>