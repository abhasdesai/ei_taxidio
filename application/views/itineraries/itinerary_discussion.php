<div class="container grid-forum inner">

		<div class="full-forum-details iti-discussion">

			<div class="forum-user-image">
			<?php if($question['socialimage']!=''){  ?>

			<img src="<?php echo $question['socialimage']; ?>" />

			<?php }else{ ?>

			<?php if($question['userimage']!='' || !file_exists(FCPATH.'userfiles/userimages/small/'.$question['userimage'])) { ?>

			<img src="<?php echo site_url('userfiles/userimages/small').'/'.$question['userimage']; ?>" />

			<?php }else{ ?>

			<img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" />

			<?php } ?>

			<?php } ?>
				</div>
			<div class="forum-short-details">
			<p class="forum-title"><?php echo $question['question']; ?></p>
			 <p class="published"><span class="forum-time">Asked By</span> <span class="postername"><?php if(isset($question['name']) && $question['name']!=''){ echo $question['name']; }else{ echo 'Taxidio User'; } ?></span> <span class="forum-time">(<?php echo time_ago(strtotime($question['created'])); ?>) </span></p>

				</div>


			<div class="forum-comments">
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



					<?php if($clist['sender_id']==$this->session->userdata('fuserid') ||  $clist['owner_id']==$this->session->userdata('fuserid') || ($this->session->userdata('role_id')!='' && $this->session->userdata('role_id')==1)){ ?>
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

			</div>

	</div>


<?php if($this->session->userdata('id') || $this->session->userdata('fuserid')==''){ ?>
<div class="ask-login">
<a href="javascript:void(0);" id="showLoginForForum">Sign In</a> or <a href="javascript:void(0);" id="showRegisterForForum">create a  account to participate in this discussion.</a>
</div>
<?php }else{ ?>



<form id="postcomment">

	<input type="hidden" name="conversation_id" value="<?php echo $question['id']; ?>"/>
	<?php if(count($comments)){ ?>
		<input type="hidden" name="conversation_lastid" value="<?php echo end($comments)['id']; ?>"/>
	<?php } ?>
	<textarea name="usercomment" id="usercomment" required placeholder="Well listen to this..."></textarea>
	<div class="col-sm-12">
<input type="submit" id="postcommentbtn" class="link-button" value="POST YOUR REPLY"/>
</div>

</form>
<?php } ?>

</div>
