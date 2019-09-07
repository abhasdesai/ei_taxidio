<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/images/favicon_1.ico">
        <title>Taxidio - User Dashboard</title>
        <link href="<?php echo site_url('assets/plugins/fullcalendar/css/fullcalendar.min.css') ?>" rel="stylesheet" />
        <link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
        
        <script href="<?php echo site_url('assets/js/modernizr.min.js'); ?>"></script>

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
                            <i class="icon-c-logo"> <img href="<?php echo site_url('assets/images/logo_sm.png'); ?>" alt="logo" height="42"/> </i>
                            <span><img href="<?php echo site_url('assets/images/logo.png'); ?>" alt="logo"/></span>
                        </a>
                    </div>
                </div>
                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <?php $this->load->view('includes/topbar'); ?>
                </div>
            </div>
            <!-- Top Bar End -->
            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    
                    <?php $this->load->view('includes/sidebar'); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                
                <?php $this->load->view($main); ?>
                <!-- content -->
                <footer class="footer text-right">
                    Copyright©All Rights Reserved 2016 Taxidio
                </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <script>
        var resizefunc = [];
        </script>
         <script href="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
       
        <!-- jQuery  -->
        <script href="<?php echo site_url('assets/js/bootstrap.min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/detect.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/fastclick.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.slimscroll.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.blockUI.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/waves.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/wow.min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.nicescroll.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/plugins/peity/jquery.peity.min.js'); ?>"></script>
        <!-- jQuery  -->
        <script href="<?php echo site_url('assets/plugins/waypoints/lib/jquery.waypoints.js'); ?>"></script>
        <script href="<?php echo site_url('assets/plugins/counterup/jquery.counterup.min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/plugins/raphael/raphael-min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/plugins/jquery-knob/jquery.knob.js'); ?>"></script>
        <script href="<?php echo site_url('assets/pages/jquery.dashboard.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.core.js'); ?>"></script>
        <script href="<?php echo site_url('assets/js/jquery.app.js'); ?>"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                $(".knob").knob();

            });
        </script>
        <script href="<?php echo site_url('assets/plugins/moment/moment.js'); ?>"></script>
        <script href="<?php echo site_url('assets/plugins/fullcalendar/js/fullcalendar.min.js'); ?>"></script>
        <script href="<?php echo site_url('assets/pages/jquery.fullcalendar.js'); ?>"></script>
    </body>
</html>