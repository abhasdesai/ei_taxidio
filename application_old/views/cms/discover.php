<div class="container-fluid no-padding">
<?php if(count($cms) && $cms['content']!=''){ ?>
  
 	<?php echo $cms['content']; ?> 
 	
<?php } else { ?>
  
  <div class="alert alert-info">
    Nothing To Show. 
  </div>


<?php } ?>
</div>
