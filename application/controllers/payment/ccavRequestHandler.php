<html>
<head>
<title> Custom Form Kit </title>
</head>
<body onload="document.getElementById('redirect').submit();">
<center>


<?php 

	$merchant_data='';
	$working_key=$this->config->item('workingKey');//Shared by CCAVENUES
	$access_code=$this->config->item('access_code');//Shared by CCAVENUES
	
	
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.urlencode($value).'&';
	}

	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.
?>
<form method="post" id="redirect" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
 
</body>
</html>

