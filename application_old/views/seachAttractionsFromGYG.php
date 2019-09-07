<?php if(count($json)){ ?>

	<?php foreach($json as $list){ ?>

		<a href="<?php echo $list['url']; ?>" target="_blank">
			<div class="row gygattractions">

				<div class="col-md-3">
					<img src="<?php echo str_replace('[format_id]',66,$list['pictures'][0]['url']); ?>" alt="<?php if(isset($list['pictures'][0]['description']) && $list['pictures'][0]['description']!=''){ echo $list['pictures'][0]['description']; }  ?>" />

				</div>

				<div class="col-md-9">
					<div class="heading-gyg">
						<h1><?php echo $list['title']; ?></h1>
					</div>
					
					<?php if($list['overall_rating']!='' || $list['overall_rating']>0){ ?>
						<div class="rating-gyg">
						 <input id="input-21f" value="<?php echo $list['overall_rating']; ?>" type="number" class="rating input-21f" data-size="xs" readonly>
						</div>
					<?php } ?>

					<?php if($list['number_of_ratings']>0){ ?>
					<div class="reviews-gyg">
						<p><?php echo $list['number_of_ratings'].' Reviews'; ?></p>
					</div>
					<?php } ?>
					<div class="abstract-gyg">
						<?php echo $list['abstract']; ?>
					</div>	
					<div class="abstract-duration">
						<p><span>Duration : </span><?php echo $list['durations'][0]['duration'].' '.$list['durations'][0]['unit']; ?></p>
					</div>
					<div class="price-gyg">
						<p><i class="fa fa-usd" aria-hidden="true"></i><?php echo $list['price']['values']['amount']; ?></p>
					</div>
					
					</div>
				</div>

				</a>


	<?php } ?>
	

<?php } else { ?>

<div class="alert alert-info">
	Sorry..! No Attractions are available for this Search.
</div>

<?php } ?>

<script>
	
	$(document).ready(function(){
		$(".input-21f").rating();
	});

</script>