<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Taxidio</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/style.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/AdminLTE.min.css'); ?>">
		<script src="<?php echo site_url('assets/admin/js/jQuery-2.1.4.min.js'); ?>"></script>
	</head>

    <body class="hold-transition login-page">
		<?php $this->load->view($main);?>
    <script src="<?php echo site_url('assets/admin/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/admin/js/jquery.validate.min.js'); ?>"></script>
	<script>
		$(document).ready(function(){
			$("#form1").validate();
		});

	</script>
	</body>

</html>
