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

<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />
<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>

<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.32.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.32.0/mapbox-gl.css' rel='stylesheet' />

<?php if($webpage=='cityattractions'){ ?>
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
<?php } ?>

<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-1.11.1.min.js') ?>"></script> 
<script>
  $(window).load(function(){
  $('.loader').fadeOut();
});

</script>
</head>

<body class="<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ echo "attractionbody"; }?>" >

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
  if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions'))
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
  else if(isset($webpage) && ($webpage=='city' || $webpage=='attractionsFromGYG' || $webpage=='hotel_bookings'))
  {
     $exploreclass='city';
  }
  else if(isset($webpage) && $webpage=='cms')
  {
      if(isset($page) && $page=='contactus')
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
  else
  {
    $exploreclass='single-country-recommendation';
  }

?>


<div class="container-fluid <?php echo $exploreclass; ?>" id="singlecountry-rec">
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
 
<div class="container-fluid footerwrapper">
  <?php $this->load->view('includes/common/footer'); ?> 
</div>


<script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap.min.js') ?>"></script>




<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ ?>
 <script type="text/javascript" src="<?php echo site_url('assets/js/jquery-ui.js') ?>"></script>

<script>

$(document).ready(function(){
    $( "#sortable" ).sortable({ cancel: ".nodrag" });
    $( "#sortable" ).disableSelection();
    var cityid="<?php echo md5($attractioncities[0]['id']); ?>";
    
    var flagcountry='<?php if(isset($flagpage) && $flagpage==1){ echo "1"; }else{ echo "0"; } ?>';
    var uid='';
    if($('#uid').length)
    {
       uid=$('#uid').val();
    }
    
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

$('.single-item').slick();



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
        var scrollTrigger = 100, // px
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
      <div class="modal-body">
        <?php if($this->session->userdata('itisavesuccess')){ ?>
          <div class="alert alert-info alert-norecommendation">
            <p><?php echo $this->session->userdata('itisavesuccess'); ?></p>
          </div>
         <?php } ?> 
         <?php if($this->session->userdata('itisavefail')){ ?>
           <div class="alert alert-info alert-msg-fail">
            <p><?php echo $this->session->userdata('itisavefail'); ?></p>
          </div>
           <?php } ?> 
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<?php if(isset($webpage) && $webpage=='faq'){ ?>

  <script>
(function(){
  var d = document,
  accordionToggles = d.querySelectorAll('.js-accordionTrigger'),
  setAria,
  setAccordionAria,
  switchAccordion,
  touchSupported = ('ontouchstart' in window),
  pointerSupported = ('pointerdown' in window);
  
  skipClickDelay = function(e){
    e.preventDefault();
    e.target.click();
  }

    setAriaAttr = function(el, ariaType, newProperty){
    el.setAttribute(ariaType, newProperty);
  };
  setAccordionAria = function(el1, el2, expanded){
    switch(expanded) {
      case "true":
        setAriaAttr(el1, 'aria-expanded', 'true');
        setAriaAttr(el2, 'aria-hidden', 'false');
        break;
      case "false":
        setAriaAttr(el1, 'aria-expanded', 'false');
        setAriaAttr(el2, 'aria-hidden', 'true');
        break;
      default:
        break;
    }
  };

switchAccordion = function(e) {
  console.log("triggered");
  e.preventDefault();
  var thisAnswer = e.target.parentNode.nextElementSibling;
  var thisQuestion = e.target;
  if(thisAnswer.classList.contains('is-collapsed')) {
    setAccordionAria(thisQuestion, thisAnswer, 'true');
  } else {
    setAccordionAria(thisQuestion, thisAnswer, 'false');
  }
    thisQuestion.classList.toggle('is-collapsed');
    thisQuestion.classList.toggle('is-expanded');
    thisAnswer.classList.toggle('is-collapsed');
    thisAnswer.classList.toggle('is-expanded');
  
    thisAnswer.classList.toggle('animateIn');
  };
  for (var i=0,len=accordionToggles.length; i<len; i++) {
    if(touchSupported) {
      accordionToggles[i].addEventListener('touchstart', skipClickDelay, false);
    }
    if(pointerSupported){
      accordionToggles[i].addEventListener('pointerdown', skipClickDelay, false);
    }
    accordionToggles[i].addEventListener('click', switchAccordion, false);
  }
})();
</script>

<?php } ?>

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
<script type="text/javascript" src="<?php echo site_url('assets/admin/js/jquery.validate.min.js') ?>"></script>

</body>
<?php if(isset($webpage) && $webpage=='city'){ ?>
	<script data-main="/assets/scripts/options" src="<?=base_url();?>assets/scripts/components/require.js"></script>

<?php } ?>
</html>
