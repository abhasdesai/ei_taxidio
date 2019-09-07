<!doctype html>
<html>
<head>
<meta charset="utf-8">

<?php if(isset($meta_title) && $meta_title!=''){ ?>
<title><?php echo $meta_title; ?></title>
<?php }else{?>
<title>Taxidio - The Where, Why & How Traveler</title>
<?php } ?>

<?php if(isset($meta_keywords) && $meta_keywords!=''){ ?>
  <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<?php } ?>

<?php if(isset($meta_description) && $meta_description!=''){ ?>
   <meta name="description" content="<?php echo $meta_description; ?>" />
<?php } ?>



<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.min.css') ?>" type="text/css" />

<link rel="stylesheet" href="<?php echo site_url('assets/css/slick.css') ?>" type="text/css" />

<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-datepicker.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/validationEngine.jquery.css') ?>" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>

<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" />

<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-1.11.1.min.js') ?>"></script>

<style>
  #forgotpasswordmodal {
  z-index: 1080 !important;
}
</style>
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
</head>

<body>

<div id="minbody">

<div class="loader"></div>
<div class="container-fluid topwrapper">

    <?php $this->load->view('includes/common/topnavi'); ?>

</div>

<div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="false">
    <?php $this->load->view('includes/common/login'); ?>
</div>


<nav class="navbar navbar-default">

     <?php $this->load->view('includes/common/mainnavi'); ?>

</nav>


<div class="container-fluid videowrapper no-padding">
    <video height="500" autoplay loop muted>
  <source src="<?php echo site_url('assets/images/video2.mp4') ?>" type="video/mp4">.
</video>
</div>

<div class="container-fluid tabwrapper no-padding" id="tab-div">

     <?php $this->load->view($main);?>

</div>



<div class="container-fluid mapwrapper text-center no-padding">

    <?php $this->load->view('includes/map'); ?>

</div>




<div class="container-fluid travel-diaries">

      <?php $this->load->view('includes/travel-diaries'); ?>

</div>


<div class="container-fluid blog-area">
     <?php $this->load->view('includes/blog-diaries'); ?>
</div>



<div class="container-fluid download-app">
       <?php $this->load->view('includes/playstore'); ?>
</div>


<div class="container-fluid newsletter">
      <?php $this->load->view('includes/newsletter'); ?>
</div>

</div>
<div class="container-fluid footerwrapper">
      <?php $this->load->view('includes/common/footer'); ?>
</div>



<script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-datepicker.js') ?>"></script>
<script>
var date = new Date();
date.setDate(date.getDate());

 $('.future').datepicker({
  format: "dd/mm/yyyy",
  autoclose: true,
  startDate: date
  });
</script>






<script type="text/javascript">
$('.reco #radioBtn a').on('click', function(){

    if($(this).hasClass('notActive'))
    {
        $(this).removeClass('notActive');
        $(this).addClass('active');
        $("#triptype").val(0);

    }
    else
    {
       $(this).removeClass('active');
       $(this).addClass('notActive');
        $("#triptype").val(1);
    }
});

$('.reco1 #radioBtn a').on('click', function(){

    if($(this).hasClass('notActive'))
    {
        $(this).removeClass('notActive');
        $(this).addClass('active');
        $("#triptype1").val(0);

    }
    else
    {
       $(this).removeClass('active');
       $(this).addClass('notActive');
        $("#triptype1").val(1);
    }
});




$('.domestic #radioBtn a').on('click', function(){

    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#isdomestic').prop('value', sel);

    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');

});

</script>

<script type="text/javascript">
  $("#qty-minus").click(function(){
  var qty = $("#input-quantity").val();

  if(qty > 1) {
    qty = qty - 1;
  }

  $("#input-quantity").val(qty);
});

$("#qty-plus").click(function(){
  var qty = $("#input-quantity").val();

  qty = Number(qty) + Number(1);

  $("#input-quantity").val(qty);

});

</script>


<!-- Scripts -->
<script type="text/javascript" src="<?php echo site_url('assets/js/hammer.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.easing.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.mousewheel.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/smoothscroll.js') ?>"></script>


<script type="text/javascript" src="<?php echo site_url('assets/js/slick.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/prism.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-ui.js') ?>"></script>
<script type="text/javascript">
$('.responsive').slick({
  dots: false,
  infinite: false,
  speed: 2000,
  slidesToShow: 5,
  slidesToScroll: 5,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: false,
        dots: false
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
<script type="text/javascript">
var affixElement = '.navbar';

$(affixElement).affix({
  offset: {
    top: function () {
      return (this.top = $(affixElement).offset().top)
    },

  }
});



$("#typeaheadkeywords").autocomplete( {
    source: function(request,response) {
      $.ajax ({
          url:'<?php echo site_url("getAutoSuggestion") ?>',
          method:'POST',
          data:'q='+$("#typeaheadkeywords").val(),
          dataType: "json",
          delay: 0,
          selectFirst: true,
          minLength: 2,
          success: function(data)
          {
              var values = [];
              if(data.places.length==0)
              {

                response({
                         "value":"No Matches Found",
                       });
              }
              else {
                response($.map(data.places, function (el, ui) {
                       return {
                           label: el.longName,
                           value: el.longName,
                           lat: el.lat,
                           lon: el.lng,
                           ccode:el.countryCode,
                       };
                }));
              }



          }
    })
  },
  select: function (event, ui) {
        if(ui.item.ccode==undefined){
          $("#typeaheadkeywords").val("");
          return false;
        }
        else
        {
          $("#code").val(ui.item.ccode);
          $(this).val(ui.item ? ui.item : " ");
        }
    },

  change: function (event, ui) {
      if (!ui.item) {
          this.value = '';}
      else{
      }
    }
});

$( "#starctcityfromcountry" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          type:'POST',
          url: "<?php echo site_url('getSuggestedCities') ?>",
          dataType: "json",
          data: {
            q: request.term
          },
          success: function( data ) {
             if(!data.length){
                   var result = [
                     {
                       label: 'No matches found',
                       value: response.term
                     }
                   ];
                     response(result);
                   }
                   else{
                      response($.map(data, function (item) {
                       return {
                         label: item.name,
                         value: item.name
                       }
                     }));
            }

          },

        });
      },
      minLength: 1,
      delay: 0,
      select: function (event, ui) {
      if(ui.item.label=='No matches found')
       {
          $("#starctcityfromcountry").val('');
          return false;
       }
     },
     change: function (event, ui) {
         if (!ui.item) {
              this.value = '';}
          else{
          }
        }

    });


</script>
<input type="hidden" id="siteurl" value="<?php echo site_url() ?>" />

<?php $flash=$this->session->userdata('norecordmsg');

if($flash==1 || $flash==3 || $flash==4 || $this->session->flashdata('passsuccess') || $this->session->flashdata('searchmsg')){

   if($this->session->flashdata('passsuccess')=='')
   {
      $this->session->set_userdata('norecordmsg',2);
   }



 ?>

<script>

  $(document).ready(function(){
    $("#infoModal").modal('show');
  })

</script>

<div id="infoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php if($this->session->flashdata('passsuccess')){ ?>

        <div class="alert alert-info alert-norecommendation">
              <p><?php echo $this->session->userdata('passsuccess'); ?></p>
         </div>

        <?php }else if($this->session->flashdata('searchmsg'))
        {
          ?>

          <div class="alert alert-info alert-norecommendation">
              <p><?php echo $this->session->userdata('searchmsg'); ?></p>
         </div>

        <?php
      }
        else{ ?>

            <?php
            if($flash==3)
            {
               echo validation_errors();
            ?>
               <script>
                  $("#tab2").removeClass('active in');
                  $("#tab1").addClass('active in');
                  $("#defaultinactive").removeClass('active');
                  $("#defaultactive").addClass('active');
               </script>

            <?php
            }
            else if($flash==4)
            {
               echo validation_errors();
            ?>
                <script>
                  $("#tab1").removeClass('active in');
                  $("#tab2").addClass('active in');
                  $("#defaultactive").removeClass('active');
                  $("#defaultinactive").addClass('active');
               </script>

            <?php
             }
            else
            {
            ?>
                <div class="alert alert-info alert-norecommendation">
                  <p>Modify a few parameters to help us give you better recommendations.</p>
                </div>
            <?php } ?>
        <?php } ?>
      </div>

    </div>

  </div>
</div>

<?php } ?>

<div id="searchModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info alert-norecommendation">
          <p id="msgsearch"></p>
        </div>
        <div class="would-main">
          <div class="would-like">Would you like to modify the number of days ?</div>
           <div class="would-a">
             <a id="searchedit" href="javascript:void(0);" class="link-button">Yes</a>
             <a id="searchhref" href="javascript:void(0);" class="link-button">No</a>
           </div>
       </div>

        </div>

    </div>

  </div>
</div>



<script type="text/javascript" src="<?php echo site_url('assets/js/scripts.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine-en.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.ui.touch-punch.min.js') ?>"></script>


<script>


  $(function() {
    $( "#slider-days" ).slider({
      range: "min",
      value: 10,
      min: 1,
      max: 50,
      slide: function( event, ui ) {
        $( "#days" ).val(ui.value);
        $("#nodays").val(ui.value);
      }
    });
    $( "#days" ).val($("#slider-days").slider( "value" ));
  } );


  $( function() {
    $( "#weather-range" ).slider({
      range: true,
      min: -50,
      max: 50,
      values: [-0,20],
      slide: function( event, ui ) {
        if(ui.values[1] - ui.values[0] < 5)
        {
            return false;
        }
        else
        {
           $( "#weather" ).val(ui.values[0] + "°c - "+ ui.values[1]+"°c" );
           $("#weatherinp").val(ui.values[0]+'-'+ui.values[1]);
        }

      }
    });
    $( "#weather" ).val($( "#weather-range" ).slider( "values", 0 ) +
      "°c - " + $( "#weather-range" ).slider( "values", 1 )+"°c" );
  } );


   $( function() {
    $( "#traveltime-range" ).slider({
      range: true,
      min: 1,
      max: 35,
      values: [1, 15],
      slide: function( event, ui ) {
       if(ui.values[1] - ui.values[0] < 5)
        {
            return false;
        }
        else
        {
            $( "#traveltime" ).val(ui.values[0] + " - " + ui.values[1] );
            $("#traveltimeinp").val(ui.values[0]+'-'+ui.values[1]);
        }

      }
    });
    $( "#traveltime" ).val($( "#traveltime-range" ).slider( "values", 0 ) +
      " - " + $( "#traveltime-range" ).slider( "values", 1 ) );
  } );


   $("#budget-range").slider({
    range: true,
    min: 0,
    max: 500,
    values: [150, 300],
    slide: function (event, ui) {
        var max = $("#budget-range").slider('option', 'max');
       if(ui.values[1] - ui.values[0] < 75)
       {
         return false;
       }
       else
       {

          if (ui.values[1] == max)
          {
            $("#budget" ).val( "$" + ui.values[0] + " - $" + ui.values[1]+'+' );
            $("#budgetinp").val(ui.values[0]+'-'+ui.values[1]);
          }
          else
          {
            $( "#budget" ).val( "$" + ui.values[0] + " - $" + ui.values[1] );
            $("#budgetinp").val(ui.values[0]+'-'+ui.values[1]);
          }

       }

    }
});
$("#budget").val('$'+ $("#budget-range").slider("values", 0) + " - $" + $("#budget-range").slider("values", 1));



</script>

<script>
  $(document).ready(function(){
    $(".recform").validationEngine('attach', {scroll: false,validationEventTrigger: 'submit'});

  });
  $('.future').on('changeDate', function (ev) {
     if($(this).attr('id')=='dp1')
     {
        jQuery('#dp1').validationEngine('hide');
     }
     else
     {
        jQuery('#dp2').validationEngine('hide');
     }
});

</script>

<script>

$('#recformsubmit').on('submit', function(e){
  $('.loader').fadeIn()
});

$('#recommendationform').on('submit', function(e){
  $('.loader').fadeIn();
});

$(window).load(function(){
     $('.loader').fadeOut();
});

</script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validate.min.js') ?>"></script>

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
</div>
</body>
</html>
