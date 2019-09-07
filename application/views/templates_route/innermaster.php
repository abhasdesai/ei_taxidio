<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Taxidio</title>
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.min.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/star-rating.css') ?>" type="text/css" />
<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />
<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbCz5cOHBso9Pzg0mVI0XcxshBIHa92SE&libraries=places"></script>

<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.21.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.21.0/mapbox-gl.css' rel='stylesheet' />

 

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

</head>

<body>



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
     $exploreclass='single-country-recommendation';
  }  
  else if(isset($webpage) && $webpage=='hotel_bookings')
  {
     $exploreclass='book-hotel-heading';
  }
  else if(isset($webpage) && ($webpage=='city' || $webpage=='attractionsFromGYG'))
  {
     $exploreclass='city';
  }
  else if(isset($webpage) && ($webpage=='cms' || $webpage=='hotels' || $webpage=='allattractions' || $webpage=='planneditineraries'))
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
 <?php if(isset($webpage) && $webpage=='city'){ ?>
 <script type="text/javascript" src="<?php echo site_url('assets/scripts/components/require.js') ?>" data-main="<?php echo site_url('assets/scripts/options') ?>"></script>

<?php } ?>

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

        
        

    }

    


    $('#listings').sortable({
            axis: 'y',
            items: "div.backgroundclr",
            update: function (event, ui) {
                  var data = $(this).sortable('serialize');
                  $.ajax({
                      data: data+'&cityid='+cityid+'&iti='+iti,
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
  <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.nicescroll.min.js') ?>"></script>  
  <script>

  $(document).ready(
  function() { 
     $(".sidebar").niceScroll({cursorcolor:"#5a4371",cursorwidth:"8px",autohidemode: false, cursorminheight: 30});
   }
);
  </script>

<?php } ?>



<?php if(isset($webpage) && $webpage=='allattractions'){ ?>
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
    $('.1').bxSlider({
      minSlides: 4,
      maxSlides: 4,
      slideWidth: 360,
      slideMargin: 10,
      controls: true,
      pager: false
    });
</script>

<?php } ?>


   <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog" id="bindData">
    
    </div>
  </div>

<?php if(isset($webpage) && ($webpage=='attraction_listings' || $webpage=='cityattractions')){ ?>
    
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
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children( "h3" ).triggerHandler( "focusout" );
   
            // Refresh accordion to handle new order
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
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
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
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
  
 /* $('.center').slick({
  centerMode: true,
  arrows: true,
  centerPadding: '60px',
  slidesToShow: 3,
  autoplay: true,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 3
      }
    }
  ]
});
*/
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
//function
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
</body>
</html>
