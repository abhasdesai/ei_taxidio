<html>

	<body>
		
		<?php echo $this->session->flashdata('error'); ?>
		
		<?php echo form_open('admin/login',array('class' => 'basketForm')); ?>
		
		<input type="text" name="username" />
		<input type="password" name="password" />
		<input type="submit" name="btnsubmit" value="Submit"/>
			
		<?php echo form_close(); ?>
	</body>
</html>
