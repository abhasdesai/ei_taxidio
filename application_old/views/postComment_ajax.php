<?php if(count($comments)){ ?>
<?php foreach($comments as $clist){ ?>

<div class="subcomments" id="maincomment<?php echo $clist['id']; ?>">
	<div class="forum-user-image">

		<?php if($clist['socialimage']!=''){ ?>

		<img src="<?php echo $clist['socialimage']; ?>" />

		<?php }else{ ?>

		<?php if($clist['userimage']!='' || !file_exists(FCPATH.'userfiles/userimages/small/'.$clist['userimage'])) { ?>
		<img src="<?php echo site_url('userfiles/userimages/small').'/'.$clist['userimage']; ?>" />
		<?php }else{ ?>
		<img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" />
		<?php } ?>

		<?php } ?>

	</div>

	<div class="forum-short-details">

		<div class="comment-name">
			<p class="comment-user-name"><?php echo $clist['name']; ?></p>
			<p class="comment-user-time"><?php echo time_ago(strtotime($clist['created'])); ?></p>
		</div>

		<?php if($clist['sender_id']==$this->session->userdata('fuserid')){ ?>
		<div class="action-btn">
			<a href="javascript:void(0);" onclick="editComment(<?php echo $clist['id']; ?>)"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			<a href="javascript:void(0);" onclick="deleteComment(<?php echo $clist['id']; ?>)"><i class="fa fa-trash" aria-hidden="true"></i></a>
		</div>
		<?php } ?>

		<div class="commentbox" id="<?php echo 'cmtbx'.$clist['id']; ?>">
			<?php echo $clist['comment']; ?>
		</div>

		<div class="commentbox" id="<?php echo 'h'.$clist['id']; ?>" style="display:none;">
			<textarea><?php echo $clist['comment']; ?></textarea>
			<input type="button" class="link-button cancel" value="Cancel" onclick="cancelEdit('<?php echo $clist['id']; ?>')"/>
			<input type="button" class="link-button" value="Update" onclick="updateComment('<?php echo $clist['id']; ?>')"/>
		</div>



	</div>
</div>

<?php } ?>

<?php } ?>
<div id="comment"></div>
