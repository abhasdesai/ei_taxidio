<div class="countryviewview container-fluid">

	<div class="container">

<?php

	//echo "<pre>";
	//print_r($response['data']['tours']);die;

 if(count($response['data']['tours'])){ ?>

	<?php  foreach($response['data']['tours'] as $list){ ?>

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
						 <input id="input-21f" value="<?php echo $list['overall_rating']; ?>" type="number" class="rating" data-size="xs" readonly>
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

			</div>

		<?php } else { ?>
			<div class="alert alert-info">
					Sorry, we do not have any attractions tickets for this destination.
				</div>
		<?php } ?>

 	</div>

 </div>
