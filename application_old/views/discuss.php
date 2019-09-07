<div class="container grid-forum">

	<a href="<?php echo site_url('ask-question') ?>" class="link-button forum-question">Ask Question</a>
	<?php if(count($forum)){ ?>


		<?php foreach($forum as $list){

			$totalcomments=$this->Forum_m->countTotalComments($list['id']);
			?>

		<div class="full-forum-details">
			<div class="forum-user-image">

			<?php if($list['googleid']!='' || $list['facebookid']!=''){ ?>

						<img src="<?php echo $list['userimage']; ?>" />

						<?php }else{ ?>


			<?php if($list['userimage']!='' || !file_exists(FCPATH.'userfiles/userimages/small/'.$list['userimage'])) { ?>

			<img src="<?php echo site_url('userfiles/userimages/small').'/'.$list['userimage']; ?>" />

			<?php }else{ ?>

			<img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" />

			<?php } ?>

			<?php } ?>

			</div>

			<div class="forum-short-details">

			<p class="forum-title"><a href="<?php echo site_url('forum').'/'.$list['slug']; ?>"><?php echo $list['subject']; ?></a></p>
				<p class="published">Published  <span class="forum-time"><?php echo time_ago(strtotime($list['created'])); ?> By </span> <span class="postername"><?php if(isset($list['name']) && $list['name']!=''){ echo $list['name']; }else{ echo 'Taxidio User'; } ?></span></p>
			<p class="forum-para">
				<?php echo word_limiter($list['question'],110).'...'; ?><span><a class="link-button forum-link" href="<?php echo site_url('forum').'/'.$list['slug']; ?>">Read More</a></span>
			</p>

			</div>

			<div class="form-count-comments">
			<p>
				<?php echo str_pad($totalcomments, 2, '0', STR_PAD_LEFT); ?>
			</p>
			</div>
		</div>

		<?php } ?>


	<div class="col-md-12" align="center">
	 <div class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
		<?php echo $pagination; ?>
	 </div>
	 </div>




<?php } else { ?>

<div class="row">
<div class="col-md-12">


<div class="alert alert-info">
  Nothing To Show....
</div>
</div>
</div>

<?php } ?>





</div>
