<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Taxidio - User Dashboard</title>

        <link href="<?php echo site_url('assets/dashboard/plugins/fullcalendar/css/fullcalendar.min.css'); ?>" rel="stylesheet" />
        <link href="<?php echo site_url('assets/dashboard/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/daterangepicker.css'); ?>" rel="stylesheet" type="text/css" />
<?php if($new_invited_trips>0){ ?>
<link href="<?php echo site_url('assets/dashboard/plugins/bootstrap-sweetalert/sweet-alert.css'); ?>" rel="stylesheet" type="text/css" />
<?php } ?>
        <link href="<?php echo site_url('assets/dashboard/css/core.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/components.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/pages.css'); ?>" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />
        <link href="<?php echo site_url('assets/dashboard/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />
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

        <div class="container-fluid footerwrapper">
                  <?php $this->load->view('includes/common/footer'); ?>
                </div>



        <!-- END wrapper -->

</div>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo site_url('assets/dashboard/js/jquery.min.js'); ?>"></script>

        <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine.js') ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine-en.js') ?>"></script>


        <script src="<?php echo site_url('assets/dashboard/js/bootstrap.min.js'); ?>"></script>
        
        <script src="<?php echo site_url('assets/dashboard/js/moment.min.js'); ?>"></script>
        
        <script src="<?php echo site_url('assets/dashboard/js/detect.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/fastclick.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/js/jquery.slimscroll.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.blockUI.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/waves.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/wow.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.nicescroll.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.scrollTo.min.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/plugins/peity/jquery.peity.min.js'); ?>"></script>

        <!-- jQuery  -->
        <script src="<?php echo site_url('assets/dashboard/plugins/waypoints/lib/jquery.waypoints.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/plugins/counterup/jquery.counterup.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/plugins/raphael/raphael-min.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/plugins/jquery-knob/jquery.knob.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/pages/jquery.dashboard.js'); ?>"></script>

        <script src="<?php echo site_url('assets/dashboard/js/jquery.core.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/jquery.app.js'); ?>"></script>
        <script type="text/javascript">


            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                $(".knob").knob();

                
            });

            

        </script>
        <script src="<?php echo site_url('assets/dashboard/plugins/moment/moment.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/daterangepicker.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/plugins/fullcalendar/js/fullcalendar.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/dashboard/js/scripts.js'); ?>"></script>


<script>

<?php /*?>$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
    },
    events: <?php echo $calendartrip; ?>,
    slotEventOverlap:false,
    eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
    },
});<?php */?>
var date = new Date();
date.setDate(date.getDate());

$('.daterange').daterangepicker({
	"applyClass":"btn btn-primary",
	"cancleClass":"btn btn-default",
  locale:{

    format: "YYYY-MM-DD",
    autoclose: true,
    startDate: date
  },
  
});

$('#calendar').fullCalendar({
    header: {
        right: 'prev,next today',
        left: 'addNoteButton',
        center: 'title',
    },
    customButtons:{
    addNoteButton: {
      text: "Add Note",
      click: function(){
        $('#event-modal').modal('show');
      },
    },
  },
    events: <?php echo $calendartrip; ?>,
    slotEventOverlap:false,
    eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
        else
        {
			$.ajax({
				url: url+"taxidio/getCalEventDetails",
				type: "POST",
				data:"noteId="+event.id,
				success:function(data){
					data = $.parseJSON(data);
					
					$('#update-note').find('#date').val(data.startdate+" - "+data.enddate);
					$('#update-note').find('#event_subject').val(data.subject);
					$('#update-note').find('#event_desc').val(data.description);
					$('#update-note').find('#noteID').val(data.id);
					$('#update-note').modal('show');
				},
			});
			
		}
    },
});



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

  });


</script>
<script type="text/javascript">
	$('#eventForm').submit(function(e){
		e.preventDefault();

		var startdate = new Date($('.daterange').data('daterangepicker').startDate._d);
		var enddate = new Date($('.daterange').data('daterangepicker').endDate._d);

		startdate = moment(startdate).format('YYYY-MM-DD');;
		enddate = moment(enddate).format('YYYY-MM-DD');

		var description = $('#event_desc').val();
		var subject = $('#event_subject').val();
		$.ajax({
		  type:'POST',
		  url:url+'taxidio/addCalendarEvent',
		  data:"startdate="+ startdate +'&enddate='+ enddate +'&description='+ description +'&subject='+ subject,
		  success:function(data)
		  {
			data = $.parseJSON(data);
			
			if(data.result==true)
			{
			  $('#eventSuccess').css('display','block');
			  $('#eventForm')[0].reset();
			  $('#calendar').fullCalendar('renderEvent',{
					id: data.noteId,
					title: subject,
					start: startdate,
					end: enddate,
					color: "#34d3eb",
					allDay: true,
				},true);
				$('#event-modal').modal('hide');
			}
			else
			{
			  $('#eventError').css('display','block');
			}
		  },
		});
	});

	$('#updateEventForm').submit(function(e){
		e.preventDefault();
		
		
		
		$.ajax({
			url:url+"taxidio/deleteCalNote",
			type:"POST",
			data:"noteID="+$('#noteID').val(),
			success:function(data){
				if(data=='1'){
					$('#update-not').modal('hide');
					$('#response').modal('show');
				}
				else
				{
					$('#response').find('.alert').html("Some Error Occurred");
					$('#response').modal('show');
				}
			},
		});
	});
	
	$('#event-modal').on('shown.bs.modal',function(e){
		$('#eventForm')[0].reset();
		var date = new Date();
		date.setDate(date.getDate());

		$('.daterange').daterangepicker({
			"applyClass":"btn btn-primary",
			"cancleClass":"btn btn-default",
		  locale:{

			format: "YYYY-MM-DD",
			autoclose: true,
			startDate: date
		  },
		  
		});
	});
	
	<?php /*?>$('#update-note').on('hidden.bs.modal',function(e){
		$('#eventForm')[0].reset();
		var date = new Date();
		date.setDate(date.getDate());

		$('.daterange').daterangepicker({
		  locale:{

			format: "YYYY-MM-DD",
			autoclose: true,
			startDate: date
		  },
		  
		});
		$('#eventSuccess').css('display','none');
		$('#eventError').css('display','none');
	});<?php */?>

	$('#event-modal').on('hidden.bs.modal',function(e){
		$('#eventForm')[0].reset();
		var date = new Date();
		date.setDate(date.getDate());

		$('.daterange').daterangepicker({
			"applyClass":"btn btn-primary",
			"cancleClass":"btn btn-default",
		  locale:{

			format: "YYYY-MM-DD",
			autoclose: true,
			startDate: date
		  },
		  
		});
	});
	
	<?php /*?>$('#update-note').on('hidden.bs.modal',function(e){
		$('#updateEventForm')[0].reset();
		var date = new Date();
		date.setDate(date.getDate());

		$('.daterange').daterangepicker({
		  locale:{

			format: "YYYY-MM-DD",
			autoclose: true,
			startDate: date
		  },
		  
		});
		$('#eventSuccess').css('display','none');
		$('#eventError').css('display','none');
	});<?php */?>

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

  var affixElement = '.navbar';

$(affixElement).affix({
  offset: {
    top: function () {
      return (this.top = $(affixElement).offset().top)
    },

  }
});



  </script>

<?php if($new_invited_trips>0){ ?>
<script type="text/javascript">
function confirmAlert()
{
    swal({
<?php if($new_invited_trips==1){ ?>
            title: "You have <?php echo $new_invited_trips; ?> new invited trip!",
<?php }else{ ?>
            title: "You have <?php echo $new_invited_trips; ?> new invited trips!",
<?php } ?>
            type: "info",
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light btn-pop-view',
            confirmButtonText: 'OK!'
        },
function() {
          $.ajax({
              type:'GET',
              url:'<?php echo site_url("notification-viewed"); ?>',
              beforeSend: function()
              {
                $(".loader").show();
              },
              complete: function()
              {
                $(".loader").hide();
              },
              success:function(data)
              {

              },
              error: function (request, status, error) {
                $(".loader").hide();
              }
          });
    })
}
setTimeout(function(){
confirmAlert(); 
},500);
</script>
<script src="<?php echo site_url('assets/dashboard/plugins/bootstrap-sweetalert/sweet-alert.min.js'); ?>"></script>
<?php } ?>
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

    </body>
</html>
