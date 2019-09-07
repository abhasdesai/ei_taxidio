 <div class="container contact-page">
    	<div class="row">
        	<div class="col-md-9">
            	<!--<h3 class="send-a-message">Queries, feedbacks or just a hello – we’d love to here from you.</h3>-->
         <!--<form class="form-horizontal" action="" method="post">-->
		<?php //echo CI_VERSION; ?>
		<?php
			if($this->session->flashdata('success')){
				echo "<div class='alert alert-success fade in alert-dismissable'>".$this->session->flashdata('success')."</div>";
			}else if($this->session->flashdata('error')){
				echo "<div class='alert alert-danger fade in alert-dismissable'>".$this->session->flashdata('error')."</div>";
			}
		?>

		  <?php echo form_open('postcontactus', array('id' => 'contactform-notify', 'enctype' => 'multipart/form-data','class'=>'form-horizontal')); ?>

          <fieldset>


            <!-- Name input-->
            <div class="form-group col-md-4 no-padding">
              <label class="col-md-12 control-label" for="name">Name</label>
              <div class="col-md-12">
                <input id="name" name="name" type="text" placeholder="Name" class="form-control" value="<?php echo set_value('name') ?>" required>
                <p><?php echo form_error('name'); ?></p>
              </div>
            </div>

            <!-- Email input-->
            <div class="form-group col-md-4 no-padding">
              <label class="col-md-12 control-label" for="email">E-mail</label>
              <div class="col-md-12">
                <input id="email" name="email" type="text" placeholder="Email" class="form-control" value="<?php echo set_value('email') ?>" required>
                <p><?php echo form_error('email'); ?></p>
              </div>
            </div>

            <!-- Message body -->
            <div class="form-group col-md-4 no-padding">
              <label class="col-md-12 control-label" for="message">Phone</label>
              <div class="col-md-12">
                <input id="phone" name="phone" class="form-control" type="text" value="<?php echo set_value('phone') ?>" placeholder="Phone" required>
                 <p><?php echo form_error('phone'); ?></p>
              </div>
            </div>

            <div class="form-group col-md-12 padding-right-0">
              <label class="col-md-12 control-label" for="message">message</label>
              <div class="col-md-12 padding-right-0">
                <textarea class="form-control" id="message" name="message" placeholder="Please enter your message here..." rows="5" required> <?php echo set_value('message') ?></textarea>
                 <p><?php echo form_error('message'); ?></p>

              </div>
            </div>

            <!-- Form actions -->
            <div class="form-group contact-submit">
              <div class="col-md-12 text-right">
                <!--<button type="submit" class="link-button">Submit</button>-->
				<input type="submit" class="link-button" value="Submit" name="contactsubmit" >
              </div>
            </div>
          </fieldset>
          </form>
            </div>
            <div class="col-md-3">
            	<h5 class="sub-heading">Knock on our door </h5>
                <!--<p>Taxidio Travel India Pvt. Ltd.<br/>
302, Doli Chambers, <br/>Arthur Bunder Road,<br/> Colaba – 400 005, Mumbai, India.</p>-->
        <?php echo nl2br($settings['address']); ?>
				<h5 class="sub-heading">Give us a ring </h5>
                <p><a href="tel:+91 9967756777" class="tel-number"><?php echo $settings['phone_no'] ?></a></p>
                <h5 class="sub-heading">Shoot out a mail </h5>
                <p><a href="mailto:<?php echo $settings['email'] ?>" class="e-mail"><?php echo $settings['email'] ?></a></p>
                <h5 class="sub-heading">Follow Us </h5>
                <ul class="socialwrapper">
                    <?php if($settings['facebook_link']!=''){ ?>
                    <li><a href="<?php echo $settings['facebook_link']; ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <?php } ?>

                    <?php if($settings['twitter_link']!=''){ ?>
                    <li><a href="<?php echo $settings['twitter_link']; ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                     <?php } ?>

                    <?php if($settings['instagram_link']!=''){ ?>
                      <li><a href="<?php echo $settings['instagram_link']; ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                    <?php } ?>


                     <?php if($settings['pinterest_link']!=''){ ?>
                        <li><a href="<?php echo $settings['pinterest_link']; ?>" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                     <?php } ?>

                   <?php if($settings['google_link']!=''){ ?>
                    <li><a href="<?php echo $settings['google_link']; ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                    <?php } ?>
      			</ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12 no-padding">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1887.1483809303616!2d72.82952188971939!3d18.918254179323476!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7d1bf828f8d7f%3A0x8e9a2e6c39b4a28e!2sApollo+Bandar+Rd%2C+Apollo+Bandar%2C+Colaba%2C+Mumbai%2C+Maharashtra!5e0!3m2!1sen!2sin!4v1483015562873" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
