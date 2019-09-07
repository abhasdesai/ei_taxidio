<?php if(count($credits)){ ?>
<input type="hidden" id="categoryid" value="<?php echo $categoryid; ?>"/>

<div class="row">
	<div class="col-md-2 col-sm-3">
		<h2>Image</h2>
	</div>
	<div class="col-md-3 col-sm-3">
		<h2>Name</h2>
	</div>
	<div class="col-md-2 col-sm-3">
		<h2>City</h2>
	</div>
	<div class="col-md-5 col-sm-3">
		<h2>Credit</h2>
	</div>
</div>
	
      		
       <?php foreach($credits as $list){ ?>
<div class="row credit-row">
       <div class="col-md-2 col-sm-3">
			<div class="image-wrapper-credit">
			<img src="<?php echo site_url('userfiles/images/small/').'/'.$list['image'] ?>"/>
			</div>	
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="image-name-credit">
			<?php echo $list['name'] ?>	
			</div>
		</div>
		<div class="col-md-2 col-sm-3">
			<div class="image-cityname-credit">		
			<?php echo $list['city_name'] ?>
			</div>
		</div>
		<div class="col-md-5 col-sm-3">
			<div class="image-credit-credit">
			<?php 
			if (filter_var($list['credit'], FILTER_VALIDATE_URL)) { ?>

				<a href="<?php echo $list['credit'] ?>" target="_blank"><?php echo $list['credit'] ?></a>

			<?php }else{ echo $list['credit']; }?>
			
			</div>
		</div>
</div>
	<?php } ?>
	

	

<div class="col-md-12" align="center">
 <div class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
	<?php echo $pagination; ?>
 </div>
 </div>

<?php }else{ ?>


<div class="alert alert-info">
		Nothing to show.
</div>


<?php } ?>