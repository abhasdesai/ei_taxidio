<div class="container contact-page">
     <div class="row">
         <div class="col-md-9">

           <?php
       			if($this->session->flashdata('success')){
       				echo "<div class='alert alert-success fade in alert-dismissable'>".$this->session->flashdata('success')."</div>";
       			}else if($this->session->flashdata('error')){
       				echo "<div class='alert alert-danger fade in alert-dismissable'>".$this->session->flashdata('error')."</div>";
       			}
       		?>	

          <div class="thank-you">


           <h2>Thank You for Reaching Out!</h2>
           <p>
             Your message just showed up in our inbox. We'll be in touch shortly.
           </p>

            <div class="link-wrappers">
              <a class="link-button" href="<?php echo site_url() ?>">Back to Home</a>
              <a class="link-button" href="<?php echo site_url('contactus') ?>">Contact Us</a></div>
           </div>

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
