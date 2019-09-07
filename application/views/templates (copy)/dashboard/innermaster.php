<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="assets/images/favicon_1.ico">

        <title>Taxidio - User Dashboard</title>

        <link href="<?php echo site_url('assets/dashboard/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/plugins/bootstrap-sweetalert/sweet-alert.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/core.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/components.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/pages.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
         <link href="<?php echo site_url('assets/dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
       <link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />

        <link href="<?php echo site_url('assets/dashboard/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/croppic.css'); ?>" rel="stylesheet" type="text/css" />
        
        

       

       <script src="http://reesort.com/skillzooms/assets/js/jquery.min.js"></script>
        <script src="<?php echo site_url('assets/dashboard/js/croppic.js'); ?>"></script>

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

       <script src="<?php echo site_url('assets/dashboard/js/modernizr.min.js'); ?>"></script>
 
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="<?php echo site_url(); ?>" class="logo">
                          <i class="icon-c-logo"> <img src="<?php echo site_url('assets/dashboard/images/logo_sm.png') ?>" alt="logo" height="42"/> </i>
                            <span><img src="<?php echo site_url('assets/dashboard/images/logo.png') ?>" alt="logo"/></span>
                        </a>
                    </div>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <?php $this->load->view('includes/dashboard/topbar'); ?>
                </div>
            </div>
            <!-- Top Bar End -->



            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <?php $this->load->view('includes/dashboard/sidebar'); ?>
                    <div class="clearfix"></div>
                 </div>
            </div>
            <!-- Left Sidebar End -->


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    
                     <?php $this->load->view($main); ?>

                </div> <!-- content -->

                <?php $this->load->view('includes/dashboard/footer'); ?>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->




        </div>
        <!-- END wrapper -->

       
         <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo site_url('assets/dashboard/js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/detect.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/fastclick.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/js/jquery.slimscroll.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.blockUI.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/waves.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/wow.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.nicescroll.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.scrollTo.min.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/js/jquery.core.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.app.js'); ?>"></script>
       
         <script src="<?php echo site_url('assets/dashboard/js/scripts.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/plugins/bootstrap-sweetalert/sweet-alert.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/pages/jquery.sweet-alert.init.js'); ?>"></script>

        <?php if(isset($webpage) && $webpage=='Feedback'){ ?>

        <script src="<?php echo site_url('assets/dashboard/plugins/tinymce/tinymce.min.js'); ?>"></script>

        
        <?php } ?>
       
       <script>
        $('.past').datepicker({
          format: "dd/mm/yyyy",
          autoclose: true,
          endDate: '+0d',
          });
      </script>

      <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function (e) {
                        $('#profile-img-tag').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#profile-img").change(function(){
                readURL(this);
                $(".temp-img").show();
            });
        </script>
	
	</body>
</html>