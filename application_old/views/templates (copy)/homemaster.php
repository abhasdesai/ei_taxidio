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
<title>Taxidio- The Where, Why, How Traveler</title>
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.min.css') ?>" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/sliding-box.css') ?>" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-datepicker.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/validationEngine.jquery.css') ?>" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>

<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />

<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-1.11.1.min.js') ?>"></script> 

<style>
  #forgotpasswordmodal {
  z-index: 1080 !important;
}
</style>

</head>

<body>

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
          url: "http://free.rome2rio.com/api/1.2/json/Autocomplete?key=xa3wFHMZ&query="+$("#typeaheadkeywords").val(),
          dataType: "json",
          delay: 0,
          selectFirst: true,
          minLength: 0,
          success: function(data) 
          {
              var values = [];
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
    }) 
  },
  select: function (event, ui) {
        $("#code").val(ui.item.ccode);
        $(this).val(ui.item ? ui.item : " ");},
    
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

if($flash==1 || $flash==3 || $flash==4 || $this->session->flashdata('passsuccess')){
   
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

        <?php }else{ ?>

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
      <div class="modal-footer">
        <button type="button" class="link-button" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php } ?>

<script type="text/javascript" src="<?php echo site_url('assets/js/scripts.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.validationEngine-en.js') ?>"></script>



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
            $( "#budget" ).val( "$" + ui.values[0] + " - $" + ui.values[1]+'+' );
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
<script type="text/javascript" src="<?php echo site_url('assets/admin/js/jquery.validate.min.js') ?>"></script> 
</body>
</html>
