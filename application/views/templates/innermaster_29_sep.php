<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

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

<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.min.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/star-rating.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.scrollbar.css') ?>" type="text/css" />

<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" />

<?php if(isset($webpage) && $webpage=='planneditineraries'){ ?>
<link rel="stylesheet" href="<?php echo site_url('assets/css/star-rating.css') ?>" type="text/css" />
<?php } ?>

<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions' || $webpage=='planneditineraries')){ ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>

<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.32.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.32.0/mapbox-gl.css' rel='stylesheet' />


<?php if($webpage=='cityattractions' || $webpage=='planneditineraries'){ ?>
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick-theme.css') ?>" type="text/css" />
 <?php } ?>

<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui.css') ?>" type="text/css" />
<?php } ?>

<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />
<?php if(isset($webpage) && $webpage=='country_recommendation'){ ?>
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.bxslider.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/tooltip.css') ?>" type="text/css" />
<?php } ?>

<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-1.11.1.min.js') ?>"></script>
<script>
  $(window).load(function(){
  $('.loader').fadeOut();
});

</script>
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

<body class="<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ echo "attractionbody"; }?>" >
<div id="minbody">
<div class="loader"></div>

<div class="container-fluid topwrapper">
   <?php $this->load->view('includes/common/topnavi'); ?>
</div>



<div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   <?php $this->load->view('includes/common/login'); ?>
</div>

<nav class="navbar navbar-default">
  <?php $this->load->view('includes/common/mainnavi'); ?>
</nav>

<?php
  $exploreclass='';
  if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions' || $webpage=='planneditineraries'))
  {
      $exploreclass='single-country-image no-padding';
  }
  else if(isset($webpage) && $webpage=='country_recommendation')
  {
     $exploreclass='single-country-recommendation recommendation-banner';
  }
  else if(isset($webpage) && $webpage=='destination')
  {
     $exploreclass='single-country-recommendation destination-banner';
  }
  else if(isset($webpage) && $webpage=='planneditineraries')
  {
      $exploreclass='single-country-recommendation planneditineraries-banner';
  }
  else if(isset($webpage) && $webpage=='hotels')
  {
      $exploreclass='single-country-recommendation hotel-banner';
  }
  else if(isset($webpage) && $webpage=='allattractions')
  {
      $exploreclass='single-country-recommendation allattractions-banner';
  }
  else if(isset($webpage) && $webpage=='Itineraries')
  {
      $exploreclass='single-country-recommendation allattractions-banner';
  }
  else if(isset($webpage) && ($webpage=='city' || $webpage=='attractionsFromGYG' || $webpage=='hotel_bookings'))
  {
     $exploreclass='city';
  }
  else if(isset($webpage) && $webpage=='cms')
  {
      if(isset($page) && ($page=='contactus' || $page=='thankyou'))
      {
        $exploreclass='single-country-recommendation contactus-banner';
      }
      else if(isset($page) && ($page=='terms_and_condition' || $page=='privacy_policy' || $page=='cookie' || $page=='credit'))
      {
        $exploreclass='single-country-recommendation policy-banner';
      }
      else if(isset($page) && $page=='faq')
      {
         $exploreclass='single-country-recommendation faq-banner';
      }
      else if(isset($page) && $page=='team')
      {
         $exploreclass='single-country-recommendation career-banner';
      }
      else if(isset($page) && $page=='discover_taxidio')
      {
         $exploreclass='single-country-recommendation discover-banner';
      }
      else
      {
        $exploreclass='single-country-recommendation';
      }

  }
  else if(isset($webpage) && $webpage=='Forum')
  {
    $exploreclass='single-country-recommendation planneditinerary-banner';
  }
  else if(isset($webpage) && ($webpage=='auth' || $webpage=='country'))
  {
	  $exploreclass='';
  }
  else
  {
    $exploreclass='single-country-recommendation';
  }

?>


<div class="container-fluid <?php echo $exploreclass; ?>">
      <?php $this->load->view('includes/common/explore'); ?>
</div>

<?php

   if(isset($webpage) && $webpage=='hotel_bookings')
  {
    ?>

<div class="container-fluid recommendation">

      <?php $this->load->view('includes/sortby'); ?>

</div>


    <?php
  }


 ?>
  <?php $this->load->view($main); ?>
 </div>
<div class="container-fluid footerwrapper">
  <?php $this->load->view('includes/common/footer'); ?>
</div>


<script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap.min.js') ?>"></script>


<?php if(isset($webpage) && $webpage=='planneditineraries'){ ?>

  <script type="text/javascript" src="<?php echo site_url('assets/js/star-rating.js') ?>"></script>
   <script type="text/javascript" src="<?php echo site_url('assets/js/jquery-ui.js') ?>"></script>
   <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.scrollbar.min.js') ?>"></script>
   <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay.js') ?>"></script>
   <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay_progress.min.js') ?>"></script>

   <script>

  $(document).ready(function(){
     $('.scrollbar-inner').scrollbar();
  });
  </script>

<?php } ?>


<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions') && $webpage!='planneditineraries'){ ?>
 <script type="text/javascript" src="<?php echo site_url('assets/js/jquery-ui.js') ?>"></script>

<script>

$(document).ready(function(){

    var cityid="<?php echo md5($attractioncities[0]['id']); ?>";

    var flagcountry='<?php if(isset($flagpage) && $flagpage==1){ echo "1"; }else{ echo "0"; } ?>';
    var uid='';
    var coid='';
    if($('#coid').length)
    {
        coid=$('#coid').val();
    }

    if($('#uid').length)
    {
       uid=$('#uid').val();
    }
    var iti=0;
    if($("#sortable").hasClass('singlecountry-x'))
    {
       var xurl='<?php echo site_url("saveSingleCountryXOrder") ?>';
    }
    else if($("#sortable").hasClass('searchcity-x'))
    {
       var xurl='<?php echo site_url("saveSearchCityXOrder") ?>';
    }
    else if($("#sortable").hasClass('multicountry-x'))
    {
       var xurl='<?php echo site_url("saveMultiCountryXOrder") ?>';
    }
    else if($("#sortable").hasClass('savedSearchcity-x'))
    {
       var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
       var xurl='<?php echo site_url("savedSearchedCityXOrder") ?>';
    }
    else if($("#sortable").hasClass('savedsingleountry-x'))
    {
       var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
       var xurl='<?php echo site_url("savedSingleCountryXOrder") ?>';
    }
    else if($("#sortable").hasClass('multicountry-x'))
    {
      var xurl='<?php echo site_url("saveMultiCountryXOrder") ?>';
    }
    else if($("#sortable").hasClass('savedmulticountry-x'))
    {
      var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
      var xurl='<?php echo site_url("savedMultiCountryXOrder") ?>';
    }

    $( "#sortable" ).sortable({
        appendTo: "parent",
        axis: false,
        containment: 'document',
        cursor: 'move',
        cursorAt: false,
        dropOnEmpty: true,
        forceHelperSize: true,
        forcePlaceholderSize: true,
        iframeFix: true,
        items: 'div.dragableBlock',
        greedy: true,
        grid: false,
        helper: "clone",
        opacity: 0.45,
        placeholder: 'ui-block-placeholder',
        revert: false,
        scroll: true,
        scrollSensitivity: 20,
        scrollSpeed: 20,
        scope: "default",
        tolerance: "pointer",
        zIndex: 2700,
        start: function (event, ui) { ui.item.bind("click.prevent",
                function(event) { event.preventDefault(); });},
        stop: function (event, ui) { ui.item.unbind("click.prevent"); },
        items: "li.drag-x",
        update: function (event, ui) {
              var data = $(this).sortable('serialize');
              $.ajax({
                  data: data+'&iti='+iti+'&uniqueid='+uid+'&coid='+coid,
                  type: 'POST',
                  url: xurl,
                  beforeSend: function(){
                     $.LoadingOverlay("show");
                  },
                  complete: function(){
                      setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                  },
                  success:function(data)
                  {
                      res=IsJsonString(data);
                      if(res)
                      {
                         var jsn = JSON.parse(data);
                         if(jsn.login==0)
                         {
                             $("#signintabli").addClass('active');
                             $("#signuptabli").removeClass('active');
                             $("#signup").removeClass('active in');
                             $("#signin").addClass('active in');
                             $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: true
                             });
                         }
                         else if(jsn.tripdelete==1)
                         {
                           window.location="<?php echo site_url('trips') ?>";
                         }
                         return false;
                      }
                      $("#bindMainPage").html(data.body);
                  }
              });
        }
     });
    $( "#sortable" ).disableSelection();



    if(flagcountry==1)
    {
        var url='<?php echo site_url("saveMultiOrder") ?>';
    }
    else
    {
        var url='<?php echo site_url("saveOrder") ?>';
        if($("#listings").hasClass('singlebar'))
        {
          var url='<?php echo site_url("saveOrderSingle") ?>';
        }
        else if($("#listings").hasClass('savebar'))
        {
           var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
          var url='<?php echo site_url("saveOrderSaved") ?>';
        }
        else if($("#listings").hasClass('multilisting'))
        {
           var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
          var url='<?php echo site_url("saveMultiOrderSaved") ?>';
        }
        else if($("#listings").hasClass('searchsavedbar'))
        {
           var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
           var url='<?php echo site_url("saveOrderSingleSaved") ?>';
        }
        else if($("#listings").hasClass('saveSingleListing'))
        {
          var iti='<?php if(isset($itineraryid) && $itineraryid!=""){ echo $itineraryid; }else{ echo "0"; } ?>';
          var url='<?php echo site_url("saveSingleListing") ?>';
        }




    }
    $('#listings').sortable({
            axis: 'y',
            items: "div.backgroundclr",
            update: function (event, ui) {
                  var data = $(this).sortable('serialize');
                  $.ajax({
                      data: data+'&cityid='+cityid+'&iti='+iti+'&uniqueid='+uid,
                      type: 'POST',
                      url: url,
                      beforeSend: function(){
                         $.LoadingOverlay("show");
                      },
                      complete: function(){
                          setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                      },
                      success:function(data)
                      {
                          res=IsJsonString(data);
                          if(res)
                          {
                             var jsn = JSON.parse(data);
                             if(jsn.login==0)
                             {
                                 $("#signintabli").addClass('active');
                                 $("#signuptabli").removeClass('active');
                                 $("#signup").removeClass('active in');
                                 $("#signin").addClass('active in');
                                 $('#myModal').modal({
                                    backdrop: 'static',
                                    keyboard: true
                                 });
                             }
                             else if(jsn.tripdelete==1)
                             {
                               window.location="<?php echo site_url('trips') ?>";
                             }
                             return false;
                          }
                          $("#bindTab").html(data.body);
                      }
                  });
            }
       });
      $( "#listings" ).disableSelection();


});
</script>

  <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay_progress.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.scrollbar.min.js') ?>"></script>
  <script>

 $(document).ready(function(){
    $('.scrollbar-inner').scrollbar();
 });

  $(document).ready(
  function() {
     $(".sidebar").removeAttr('style');
     $(".sidebar").removeAttr('tabindex');
   }
);
  </script>

<?php } ?>




<?php if((isset($webpage) && $webpage=='allattractions')  || (isset($page) && $page=='credit') ){ ?>
  <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay_progress.min.js') ?>"></script>
<?php } ?>

<?php if(isset($webpage) && $webpage=='hotel_bookings'){ ?>

   <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay.js') ?>"></script>
   <script type="text/javascript" src="<?php echo site_url('assets/js/loadingoverlay_progress.min.js') ?>"></script>

<?php } ?>

<?php if(isset($webpage) && $webpage=='country_recommendation'){ ?>

  <script type="text/javascript" src="<?php echo site_url('assets/js/jquery-migrate-1.2.1.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/modernizr.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/slick.js') ?>"></script>
 <script type="text/javascript" src="<?php echo site_url('assets/js/prism.js') ?>"></script>
  <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.bxslider.min.js') ?>"></script>


  <script type="text/javascript">


    var maxSlides,
  width = $(window).width();

 if (width < 640) {
      $('.1').bxSlider({
      minSlides: 1,
      maxSlides: 1,
      slideWidth: 360,
      slideMargin: 10,
      controls: true,
      pager: false,
	  infiniteLoop: false,
	  hideControlOnEnd: true,
	  moveSlides: 1
    });
  } else {
     $('.1').bxSlider({
      minSlides: 3,
      maxSlides: 3,
      slideWidth: 360,
      slideMargin: 10,
      controls: true,
      pager: false,
    });
  }


</script>

<?php } ?>


   <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog" id="bindData">

    </div>
  </div>

<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ ?>
  <?php if($webpage=='cityattractions'){ ?>
 <script type="text/javascript" src="<?php echo site_url('assets/js/slick.js') ?>"></script>
 <script>
  $('.single-item').slick({
  dots: false,
  infinite: false,
  speed: 2000,
  slidesToShow: 3,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
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
 <?php } ?>

    <script>
    $(function() {
      var tabs = $( "#tabs" ).tabs();
      tabs.find( ".ui-tabs-nav" ).sortable({
        axis: "x",
        stop: function() {
          tabs.tabs( "refresh" );
        }
      });
    });
  </script>

  <script>
    $(function() {
      $( ".accordion" )
        .accordion({
          header: "> div > h3",
      collapsible: true,
      active: false
        })
        .sortable({
          axis: "y",
          handle: "h3",
          stop: function( event, ui ) {
           ui.item.children( "h3" ).triggerHandler( "focusout" );
          $( this ).accordion( "refresh" );
        $( ".selector" ).accordion( "destroy" );
          }
        });
    });
  </script>

 <?php } ?>


<?php if(isset($webpage) && $webpage=='country_recommendation'){ ?>

  <script type="text/javascript">

  $('.responsive').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 3,
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

$('.responsive2').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 3,
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

$('.single-item').slick({infinite: false});



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



<?php if(isset($webpage) && $webpage=='city'){ ?>
<script>
function getSelectedIndex(index)
{
  $("#currentval").text(index);
  if(index==1)
  {
    nextval=1;
    prevval=5;
  }
  else if(index==5)
  {
    nextval=5;
    prevval=4;
  }
  else
  {
    nextval=parseInt(index);
    prevval=parseInt(nextval)-1;
  }
  $("#nextbtn").attr('data-counter',nextval);
  $("#prevbtn").attr('data-counter',prevval);
}

function getNext(counter)
{

  $("#nextbtn").css('pointer-events','none');
  $("#prevbtn").css('pointer-events','none');
  var nextval=parseInt(counter)+1;
  if(nextval>5)
  {
    nextval=1;
    prevval=5;
  }
  else
  {
    prevval=parseInt(nextval)-1;
  }

  $("#currentval").text(nextval);
  $("#nextbtn").attr('data-counter',nextval);
  $("#prevbtn").attr('data-counter',prevval);

  setTimeout(function(){
      $("#nextbtn").css('pointer-events','auto');
      $("#prevbtn").css('pointer-events','auto');
  }, 400);


}

function getPrev(counter)
{
  $("#nextbtn").css('pointer-events','none');
  $("#prevbtn").css('pointer-events','none');
  var prevval=parseInt(counter)-1;
  if(counter==5)
  {
    nextval=5;
    prevval=4;
  }
  else if(counter==1)
  {
    nextval=1;
    prevval=5;
  }
  else
  {
    var prevval=parseInt(counter)-1;
    var nextval=parseInt(prevval)+1;
  }
  $("#currentval").text(nextval);
  $("#nextbtn").attr('data-counter',nextval);
  $("#prevbtn").attr('data-counter',prevval);
  setTimeout(function(){
      $("#nextbtn").css('pointer-events','auto');
      $("#prevbtn").css('pointer-events','auto');
  }, 400);


}
</script>

<?php } ?>

<?php if($this->session->userdata('itisavesuccess') || $this->session->userdata('itisavefail')){ ?>

<script>

  $(document).ready(function(){
    $("#messgaeModal").modal('show');
  })


</script>

<div id="messgaeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header login-modal">
<button class="close" type="button" data-dismiss="modal">×</button>
</div>
      <div class="modal-body mar-pad-0">
        <?php if($this->session->userdata('itisavesuccess')){ ?>
          <div class="alert alert-info alert-norecommendation">
            <p class="text-center"><?php echo $this->session->userdata('itisavesuccess'); ?></p>
          </div>
           <p class="text-center"><a class="link-button mytrips-link" href="<?php echo site_url('trips') ?>">My Trips</a></p>
         <?php } ?>
         <?php if($this->session->userdata('itisavefail')){ ?>
           <div class="alert alert-info alert-msg-fail">
            <p class="text-center"><?php echo $this->session->userdata('itisavefail'); ?></p>
          </div>
           <?php } ?>

      </div>

    </div>

  </div>
</div>

<?php } ?>



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
</script>

<input type="hidden" id="siteurl" value="<?php echo site_url() ?>" />
<script type="text/javascript" src="<?php echo site_url('assets/js/scripts.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/star-rating.js') ?>"></script>
<script type="text/javascript">

if($('.othercities').length)
{

$('.othercities').slick({
  dots: false,
  infinite: false,
  speed: 2000,
  slidesToShow: 5,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
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
}


</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});


if($(".scrollbtm").length)
{
  $(".scrollbtm").click(function(){
      $("html, body").animate({
          scrollTop: $('#citysuggestionsanc').offset().top
      }, 1200);
  });
}
</script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validate.min.js') ?>"></script>

<script>
  $("#formLogin").validate();
</script>
<?php
/*
if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions'))
  {

    if($this->session->userdata('fuserid')=='')
    {

    ?>

  <script>

  $(document).ready(function(){

    $("#myModal").modal({
      backdrop: 'static',
      keyboard: false
    });
    $(".close").hide();
    $(".close").click(function(){
        return false;
    })

  });

  </script>

  <?php
}
  }
*/
?>

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
<?php if(isset($webpage) && $webpage=='city'){ ?>
	<script data-main="/assets/scripts/options" src="<?=base_url();?>assets/scripts/components/require.js"></script>

<?php } ?>

</html>
