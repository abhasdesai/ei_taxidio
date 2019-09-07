<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">


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
        <link media="all" type="text/css" rel="stylesheet" href="<?php echo site_url('assets/dashboard/css/daterangepicker.css'); ?>">
        <link href="<?php echo site_url('assets/dashboard/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/croppic.css'); ?>" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />

<?php if(isset($webpage) && $webpage=='profile'){ ?>
<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/select2.min.css'); ?>">
<?php } ?>

<?php if(isset($webpage) && ($webpage=='trips' || $webpage=='invited_trips')){ ?>
<style type="text/css">
.trip_action{
    padding: 2px !important;
}
</style>
<?php } ?>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/croppic.js'); ?>"></script>

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

       <script src="<?php echo site_url('assets/dashboard/js/modernizr.min.js'); ?>"></script>
    <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-89706226-1', 'auto');
ga('send', 'pageview');

</script>

<script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("bc00cae9d6774ea1f93992fb1cdc546b");</script>

<script type="text/javascript">

window.smartlook||(function(d) {

var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];

var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';

c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);

})(document);

smartlook('init', 'dd53ad31b4eb08ff89ae44aec4fb9e2669c5f0b8');

</script>
<!-- Global site tag (gtag.js) - Google AdWords: 819510635 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-819510635"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-819510635');
</script>
<!-- Event snippet for Campaign 1 Conversion conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-819510635/AmtCCJrk2nsQ6_rihgM',
      'transaction_id': ''
  });
</script>


<!-- Start of HubSpot Embed Code -->
  <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/4329309.js"></script>
<!-- End of HubSpot Embed Code -->
<!-- Hotjar Tracking Code for www.taxidio.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:787362,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '1838416689731308'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=1838416689731308&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

        <?php /* ?>
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
            <?php */ ?>


            <div class="container-fluid topwrapper">
               <?php $this->load->view('includes/common/topnavi'); ?>
            </div>



            <div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
               <?php $this->load->view('includes/common/login'); ?>
            </div>


            <nav class="navbar navbar-default">

                 <?php $this->load->view('includes/common/mainnavi'); ?>

            </nav>



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



            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


<?php if(isset($webpage) && ($webpage=='trips' || $webpage=='invited_trips')){ ?>
<div id="minbody" style="min-height: 0;">
<div class="loader"></div>
</div>
<?php } ?>


<div class="container-fluid footerwrapper">
  <?php $this->load->view('includes/common/footer'); ?>
</div>
        </div>
        <!-- END wrapper -->

<?php if(isset($webpage) && ($webpage=='trips' || $webpage=='invited_trips')){ ?>
<script>
  $(window).load(function(){
  $('.loader').hide();
});

</script>
<?php } ?>

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
        <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validate.min.js') ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.core.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.app.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/moment.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/daterangepicker.js'); ?>"></script>
         <script src="<?php echo site_url('assets/dashboard/js/scripts.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/plugins/bootstrap-sweetalert/sweet-alert.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/pages/jquery.sweet-alert.init.js'); ?>"></script>

<?php if(isset($webpage) && $webpage=='profile'){ ?>

<script src="<?php echo site_url('assets/admin/js/select2.full.min.js'); ?>"></script>

<script type="text/javascript">
        $(".select2").select2({
            maximumSelectionLength: 7
        });
   
jQuery.validator.addMethod("lettersonly", function(value, element) {
return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Only alphabetical characters");

    $("#userform").validate({
        rules:{
            name: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            "tag_id[]":{
                required: true 
            },
            email: {
                required: true,
                maxlength: 200
            },
            dob: {
                required: true
            },
            phone: {
                number:true,
                required: true,
                maxlength: 15
            },
            gender: {
                required: true
            },
            country_id: {
                required: true
            },
            sos_name: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            sos_relation: {
                required: true,
                minlength: 3
            },
            sos_country_code: {
                required: true
            },
            sos_phone: {
                number:true,
                required: true,
                maxlength: 15
            },
            sos_email: {
                required: true,
                maxlength: 200
            },
            sos_name2: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            sos_relation2: {
                required: true,
                minlength: 3
            },
            sos_country_code2: {
                required: true
            },
            sos_phone2: {
                number:true,
                required: true,
                maxlength: 15
            },
            sos_email2: {
                required: true,
                maxlength: 200
            }                 
        },
        messages: {
            phone: {
              number:"Please enter valid phone number"
            },
            sos_phone: {
              number:"Please enter valid phone number"
            },
            sos_phone2: {
              number:"Please enter valid phone number"
            },
            name: {
              lettersonly:"Please enter valid name"
            },
            sos_name: {
              lettersonly:"Please enter valid name"
            },
            sos_name2: {
              lettersonly:"Please enter valid name"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }   
    }); 

     /*$("#userform").submit(function() {
     $("#tag_id").tagit({
         fieldName: "tags[]"
     });
     if ($('label[name="tag_iderr"]').length) {
         $('label[name="tag_iderr"]').remove();
     }
     if ($("#tag_id").tagit("assignedTags").length > 0) {} else {
         $("#tag_id").after("<label name='tag_iderr' id='tag_iderr' for='tag_id'>This field is required.</label>");
         return false;
     }
 });*/
</script>

<?php } ?>

        <?php if(isset($webpage) && $webpage=='Feedback'){ ?>

        <script src="<?php echo site_url('assets/dashboard/plugins/tinymce/tinymce.min.js'); ?>"></script>


        <?php } ?>

       <script>
       $("#formtovalidate").validate();  
       var date = new Date();
       date.setDate(date.getDate());
        $('.future').datepicker({
          format: "dd/mm/yyyy",
          autoclose: true,
          startDate: date
          });
        $('.past').datepicker({
          format: "dd/mm/yyyy",
          autoclose: true,
          endDate: '+0d'
          });
      </script>

      <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#profile-img-tag').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#profile-img").change(function(){
                readURL(this);
                $(".temp-img").show();
            });
        </script>

        <script>
  (function() {

    "use strict";

    var toggles = document.querySelectorAll(".c-hamburger");

    for (var i = toggles.length - 1; i >= 0; i--) {
      var toggle = toggles[i];
      toggleHandler(toggle);
    };

    function toggleHandler(toggle) {
      toggle.addEventListener( "click", function(e) {
        e.preventDefault();
        (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
      });
    }

  })();

var affixElement = '.navbar';

$(affixElement).affix({
  offset: {
    top: function () {
      return (this.top = $(affixElement).offset().top)
    },

  }
});
</script>

 <script>

  $(".c-hamburger").click(function(){
    if($(".t").hasClass('toggle-display'))
    {
      $(".tagline").hide();
      $(".t").removeClass("toggle-display");
      $(".t").addClass("toggle-display-block");
    }
    else
    {
      $(".tagline").show();
       $(".t").removeClass("toggle-display-block");
        $(".t").addClass("toggle-display");
   }
    });

  </script>

 <script type="application/ld+json">
{ "@context" : "http://schema.org",
  "@type" : "Organization",
  "legalName" : "Taxidio Travel India Pvt. Ltd.",
  "url" : "<?php echo site_url(); ?>",
  "contactPoint" : [{
    "@type" : "ContactPoint",
    "telephone" : "+91 9167756777",
    "contactType" : "customer service"
  }],
  "logo" : "<?php echo site_url('assets/assets/images/logo.png') ?>",
  "sameAs" : [ "https://www.facebook.com/TaxidioTravel/",
    "https://twitter.com/taxidiotravel",
    "https://www.instagram.com/taxidiotravel/"]
}
</script>

<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "WebSite",
  "name" : "Taxidio Travel India Pvt. Ltd.",
  "url" : "<?php echo site_url(); ?>",
  "potentialAction" : {
    "@type" : "SearchAction",
    "target" : "<?php echo site_url(); ?>/?s={search_term}",
    "query-input" : "required name=search_term"
  }
}
</script> 

<?php if(isset($webpage) && $webpage=='profile'){ ?>

  <script type="text/javascript">

  $('.responsive').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 5,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});




</script>

<script type="text/javascript">
  $(function() {
  $('a[href*=#]').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top}, 500, 'linear');
  });
});
</script>
<script type="text/javascript">

    if ($('#back-to-top').length) {
        var scrollTrigger = 100,
                backToTop = function () {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $('#back-to-top').addClass('show');
                    } else {
                        $('#back-to-top').removeClass('show');
                    }
                };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('#back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
        scrollTop: $("#multi-countries").offset().top
    }, 800);
        });
    }

</script>

<?php } ?>


	</body>
</html>
