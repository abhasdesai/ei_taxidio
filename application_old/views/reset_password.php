<div class="container contact-page">
	<div class="row">
		<?php echo form_open('update_reset_password/'.$id.'/'.$token,array('class' => 'form-horizontal','role'=>'form','id'=>'form1')); ?>

		<!-- Name input-->
		<div class="col-md-4 col-md-offset-4">
			<label class="col-md-12 control-label" for="password">Password</label>
			<div class="col-md-12">
				<input type="password" class="form-control required" minlength="6" maxlength="30" name="password" id="password" autofocus />
			</div>
		</div>

		<!-- Email input-->
		<div class="col-md-4 col-md-offset-4">
			<label class="col-md-12 control-label" for="password">Re-enter Password</label>
			<div class="col-md-12">
				<input type="password" class="form-control required" name="cpassword" id="cpassword" equalTo="#password" />
				<p><?php echo form_error('cpassword'); ?></p>
			</div>
		</div>




		<!-- Form actions -->
		<div class="col-md-4 col-md-offset-4 contact-submit">
		<label class="col-md-12 control-label" for="cpassword"></label>
			<div class="col-md-12 forgot-link-button">
				<!--<button type="submit" class="link-button">Submit</button>-->
				<input type="submit" class="link-button" value="Submit" name="btnsubmit" >
			</div>
		</div>
	</form>
</div>
</div>
