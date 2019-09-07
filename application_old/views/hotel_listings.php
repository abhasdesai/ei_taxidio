<?php $this->load->view('includes/innersearch'); ?>

<div class="container-fluid search-title">
	<div class="container">
    <div class="col-md-12">
	<h4>WE FOUND <?php echo str_pad($total, 2, '0', STR_PAD_LEFT); ?> HOTELS</h4>
    </div>
    </div>
</div>
<input type="hidden" id="prop" value="">
<div class="container grid-hotels" id="bindajax">
	
    
</div>

<script>
 
var prop=$("#prop").val();


 $(document).ready(function(){
                $.post('<?php echo site_url("hotel_listings_ajax") ?>','prop='+prop,function(data){
                $("#bindajax").html(data);
         });
        
            $(document).on('click','div.pagination ul li a',function(e){
                e.preventDefault();
                var prop=$("#prop").val();
                 var this_url=$(this).attr('href');
                 $.post(this_url,'prop='+prop,function(data){
                     $("#bindajax").html(data);
                     $('html, body').animate({
                        scrollTop: $("#move").offset().top
                    }, 1000);
                });
                
                return false;
            });
            
           
             
            
        });

 </script>

