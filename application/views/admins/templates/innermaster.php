<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Taxidio</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php $this->load->view('admins/includes/innercss');?>
   
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $this->load->view('admins/includes/main_navi');?>

      <!-- Left side column. contains the logo and sidebar -->

      <?php $this->load->view('admins/includes/left_sidebar');?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <?php $this->load->view('admins/includes/breadcrumb');?>

        <!-- Main content -->

        <?php $this->load->view($main);?>

      </div><!-- /.content-wrapper -->

      <?php $this->load->view('admins/includes/footer');?>

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

     <?php $this->load->view('admins/includes/innerjs');?>

  </body>
</html>

