<div class="container grid-forum inner">

	<?php if(count($forum)){ ?>
	
			<div class="full-forum-details">
			
			<div class="forum-user-image">
			<?php if($forum['googleid']!='' || $forum['facebookid']!=''){ ?>

			<img src="<?php echo $forum['userimage']; ?>" />

			<?php }else{ ?>

			<?php if($forum['userimage']!='' || !file_exists(FCPATH.'userfiles/userimages/small/'.$forum['userimage'])) { ?>

			<img src="<?php echo site_url('userfiles/userimages/small').'/'.$forum['userimage']; ?>" />

			<?php }else{ ?>

			<img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" />

			<?php } ?>

			<?php } ?>
				</div>
			<div class="forum-short-details">	
			<p class="forum-title"><a href="<?php echo site_url('forum').'/'.$forum['slug']; ?>"><?php echo $forum['subject']; ?></a></p>
			<p class="published">Published  <span class="forum-time"><?php echo time_ago(strtotime($forum['created'])); ?> By </span> <span class="postername"><?php if(isset($forum['name']) && $forum['name']!=''){ echo $forum['name']; }else{ echo 'Taxidio User'; } ?></span></p>
			<p>
				<?php echo $forum['question']; ?>
			</p>
				</div>
			<?php /* ?>
			<div class="form-count-comments">		
			<p class="forum-para">
				<?php echo str_pad($totalcomments, 2, '0', STR_PAD_LEFT); ?>
			</p>
				</div>
				<?php */ ?>
			<div class="forum-comments">
			<?php if(count($comments)){ ?>


				<?php foreach($comments as $clist){ ?>
					
				<div class="subcomments" id="maincomment<?php echo $clist['id']; ?>">	
					<div class="forum-user-image">
					<?php if($clist['googleid']!='' || $clist['facebookid']!=''){ ?>

						<img src="<?php echo $clist['userimage']; ?>" />

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

			</div>

	</div>	


	<?php } ?>	

<?php if($this->session->userdata('fuserid')==''){ ?>
<div class="ask-login">
<a href="javascript:void(0);" id="showLoginForForum">Sign In</a> or <a href="javascript:void(0);" id="showRegisterForForum">create a  account to participate in this discussion.</a>
</div>
<?php }else{ ?>



<form id="postcomment">

	<input type="hidden" name="conversation_id" value="<?php echo $forum['id']; ?>"/>
	<?php if(count($comments)){ ?>
		<input type="hidden" name="conversation_lastid" value="<?php echo end($comments)['id']; ?>"/>
	<?php } ?>
	<textarea name="usercomment" id="usercomment" required placeholder="Well listen to this..."></textarea>
	<input type="submit" class="link-button" value="POST YOUR REPLY"/>
</form>
<?php } ?>

</div>