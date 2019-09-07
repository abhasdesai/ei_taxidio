<div class="container">

<div class="tabbable" id="creditmove">
	<ul class="nav nav-tabs creditul">
		<li class="active"><a href="#1" data-toggle="tab">Attractions</a></li>
		<li><a href="#2" data-toggle="tab">Relaxation & Spa</a></li>
		<li><a href="#3" data-toggle="tab">Restaurant & Nightlife</a></li>
		<li><a href="#4" data-toggle="tab">Adventure</a></li>
		<li><a href="#5" data-toggle="tab">Sports & Stadiums</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="1">
			<div id="bind1"></div>
		</div>
		<div class="tab-pane" id="2">
			<div id="bind2"></div>
		</div>
		<div class="tab-pane" id="3">
			<div id="bind3"></div>
		</div>
		<div class="tab-pane" id="4">
			<div id="bind4"></div>
		</div>
		<div class="tab-pane" id="5">
			<div id="bind5"></div>
		</div>
	</div>
</div>


</div>

<script>



$(".creditul li").click(function(){

	var r=$(this).find('a').attr('href');
	var category=r.substr(1);
	$("#categoryid").val(category);
	$.ajax({
	          type:'POST',
	          url:'<?php echo site_url("cms/credit_ajax") ?>',
	          data:'category='+category,
	          beforeSend: function()
	          {
	                     $.LoadingOverlay("show");
	          },
	          complete: function()
	          {
	              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
	          },
	          success:function(data)
	          {
	                 $("#bind"+category).html(data);
		              $("body, html").animate({
		               scrollTop: $('#creditmove').offset().top
		             }, 800);
	          }
	});


});


$(document).ready(function(){
       $.ajax({
              type:'POST',
              url:'<?php echo site_url("cms/credit_ajax") ?>',
              data:'category=1',
              beforeSend: function()
              {
                         $.LoadingOverlay("show");
              },
              complete: function()
              {
                  setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
              },
              success:function(data)
              {
                  $("#bind1").html(data);
              }
            });
});


$(document).on('click','div.pagination-container ul li a',function(e){
  var this_url=$(this).attr('href');
  var category=$("#categoryid").val();
  $.ajax({
          type:'POST',
          url:this_url,
          data:'category='+category,
          beforeSend: function()
          {
          	   $.LoadingOverlay("show");
          },
          complete: function()
          {
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 1000);
          },
          success:function(data)
          {
          	  $("#bind"+category).html(data);
              $("body, html").animate({
               scrollTop: $('#creditmove').offset().top
             }, 800);
          }
        });

  return false;

});

</script>
