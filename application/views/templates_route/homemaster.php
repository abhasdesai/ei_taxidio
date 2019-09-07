<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Taxidio</title>
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.min.css') ?>" type="text/css" />



<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style-map.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/map.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/mapplic/mapplic.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/sliding-box.css') ?>" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/slick-theme.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-datepicker.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css') ?>" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700' rel='stylesheet' type='text/css'>

<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />

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
date.setDate(date.getDate()-1);

 $('.future').datepicker({
  format: "dd/mm/yyyy",
  autoclose: true,
  startDate: date
  });
</script>

<script type="text/javascript" src="<?php echo site_url('assets/js/navigation/classie.js') ?>"></script> 
<script type="text/javascript" src="<?php echo site_url('assets/js/navigation/demo9.js') ?>"></script>





<script type="text/javascript">
/*
$('.reco #radioBtn a').on('click', function(){
    
    if($(this).hasClass('notActive'))
    {
       $(this).removeClass('notActive');
       $(this).addClass('active');
    }
    else
    {
       $(this).removeClass('active');
       $(this).addClass('notActive');
    }
    
    if($(".reco #radioBtn a").eq(0).hasClass('active') && $(".reco #radioBtn a").eq(1).hasClass('active'))
    {
       $("#triptype").val(3);
    }
    else if($(".reco #radioBtn a").eq(0).hasClass('active') && $(".reco #radioBtn a").eq(1).hasClass('notActive'))
    {
      $("#triptype").val(1);
    }
    else if($(".reco #radioBtn a").eq(0).hasClass('notActive') && $(".reco #radioBtn a").eq(1).hasClass('active'))
    {
      $("#triptype").val(2);
    }
    else
    {
      $(".reco #radioBtn a").eq(0).addClass('active');
       $("#triptype").val(1);
    }
 
});
*/

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
    <script type="text/javascript" src="<?php echo site_url('assets/mapplic/mapplic.js') ?>"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#mapplic').mapplic({
          source: '<?php echo site_url("assets/world.json") ?>',
          height: 460,
          animate: false,
          sidebar: false,
          minimap: false,
          deeplinking: true,
          fullscreen: true,
          hovertip: true,
          developer: true,
          maxscale: 2,
          skin: 'mapplic-dark',
          zoom: true
        });

        $('.usage').click(function(e) {
          e.preventDefault();
          
          $('.editor-window').slideToggle(200);
        });

        $('.editor-window .window-mockup').click(function() {
          $('.editor-window').slideUp(200);
        });
      });
    </script>

<script type="text/javascript" src="<?php echo site_url('assets/js/slick.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/prism.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery-ui.js') ?>"></script>
<script type="text/javascript">
$('.responsive').slick({
  dots: false,
  infinite: false,
  speed: 2000,
  slidesToShow: 8,
  slidesToScroll: 4,
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
                     };
              }));
                  
          } 
    }) 
  },
  select: function (event, ui) {
        $("#lat").val(ui.item.lat);
        $("#long").val(ui.item.lon);
        $(this).val(ui.item ? ui.item : " ");},
    
  change: function (event, ui) {
      if (!ui.item) {
          this.value = '';}
      else{
       // return your label here
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
            response( data );
          }
        });
      },
      minLength: 1,
      delay: 0,
      select: function (event, ui) {
        //$("#lat").val(ui.item.lat);
        //$("#long").val(ui.item.lon);
        $(this).val(ui.item ? ui.item : " ");},
    
      change: function (event, ui) {
          if (!ui.item) {
              this.value = '';}
          else{
           // return your label here
          }
        }
      
    });


</script>
<input type="hidden" id="siteurl" value="<?php echo site_url() ?>" />

<?php if($this->session->flashdata('norecordmsg')!=''){ ?>

<script>
  
  $(document).ready(function(){
    $("#infoModal").modal('show');
  })

</script>

<div id="infoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <?php  echo $this->session->flashdata('norecordmsg'); ?>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="link-button" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php } ?>

<script type="text/javascript" src="<?php echo site_url('assets/js/scripts.js') ?>"></script>
</body>
</html>
