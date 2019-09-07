<?php $testimonials = $this->Cms_fm->getAllTestimonials();?>
<div class="nicdark_space3 nicdark_bg_gradient"></div>
<div class="fbg"><center><img src="<?php echo site_url('assets/img/fbg.png') ?>"></center></div>
<!--start section-->
<section class="nicdark_section nicdark_bg_greydark footer">

    <!--start nicdark_container-->
    <div class="nicdark_container nicdark_clearfix footerwrap">

        <div class="nicdark_space30"></div>



        <div class="grid grid_3 nomargin percentage">

            <div class="nicdark_space30"></div>

            <div class="nicdark_marginleft10">
                <h4 class="white">Testimonials</h4>
                <div class="nicdark_space20"></div>

            </div>



            <div class="flink1">


			<div style="width:100%;">

			  <ul class="bxslider fontul">

				  <?php foreach ($testimonials as $tlist) {?>
				   <li>
					<blockquote><?php echo trim_by_words($tlist['title'], 0, 100); ?>
					<p class="pfont">- <?php echo $tlist['name']; ?></p>
					</blockquote>
					</li>
				 <?php }?>

				  </ul>
				  <a href="<?php echo site_url('testimonials') ?>" style="color:#fff; font-weight:bold; font-size:17px;">View All</a>
				 </div>
			</div>

        </div>

        <div class="grid grid_3 nomargin percentage">

            <div class="nicdark_space20"></div>

            <div class="nicdark_margin10">
                <h4 class="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Links</h4>
                <div class="nicdark_space20"></div>

				<div class="flink">
					<ul>
					<li><a href="<?php echo site_url(); ?>">Home</a></li>
					<li><a href="<?php echo site_url('about-best-school-in-baroda'); ?>">About Us</a></li>
					<li><a href="<?php echo site_url('academics/icse-school-vadodara') ?>">Academics</a></li>
					<li><a href="<?php echo site_url('icse-schools-baroda-newsletter') ?>">Newsletter</a></li>
					<li><a href="<?php echo site_url('careers') ?>">Careers</a></li>
					 <li><a href="<?php echo site_url('online-payment') ?>">Online Payment</a></li>
					<li><a href="<?php echo site_url('best-icse-school-vadodara-contact-us'); ?>">Contact Us</a></li>
					<img src="<?php echo site_url('assets/img/certification.jpg'); ?>" width="100%"/>
					</ul>
					</div>


            </div>
        </div>

        <div class="grid grid_3 nomargin percentage">

            <div class="nicdark_space20"></div>

            <div class="nicdark_margin10">
                <h4 class="white">Contact us</h4>
                <div class="nicdark_space20"></div>
                <div class="flink loclink">
					<p class="padtp"><i class="fa fa-phone"></i> Phone : +91 265 - 6533855</p>
					<p class="padtp"><i class="fa fa-envelope"></i> Email : <a href="mailto:info@nalandaschool.org">info@nalandaschool.org</a> </p>
					<p class="padtp"><i class="fa fa-home"></i> Address :</p>
					<p class="padtp"> <?php echo nl2br('Nalanda International School
						Sevasi-Mahapura Road,
						Vadodara- 391 101.') ?></p>
					<p class="padtp"><a href="https://www.google.co.in/maps/place/Nalanda+International+School/@22.3129538,73.1042591,15z/data=!4m2!3m1!1s0x0:0xb66c9fef68c1e3f5?sa=X&ved=0ahUKEwjf4fCQ6o_KAhWUwo4KHZ0cA_gQ_BIIdDAN" target="_blank"><i class="fa fa-map-marker"></i> : View in Google Map</a></p>
					<br/>
					<div class="sociallinksfooter">
					<a href="https://www.facebook.com/NalandaInternationalSchoolVadodara" target="_blank"><i class="fa fa-facebook"></i></a>
					<a href="https://plus.google.com/108456495904439470378/about" target="_blank"><i class="fa fa-google-plus"></i></a>
					</div>

				</div>
			</div>

		</div>

        <div class="grid grid_3 nomargin percentage">

            <div class="nicdark_space20"></div>

            <div class="nicdark_margin10">
                <h4 class="white">Quick Contact</h4>
                <div class="nicdark_space20"></div>




            <div class="flink">
				<div class="alr" style="display:none;">
						<div class="nicdark_btn nicdark_bg_green medium nicdark_shadow nicdark_radius white nicdark_pres" style="width:100%">Thanks for inquiry.</div>
						 <div class="nicdark_space20"></div>
				</div>
				<div class="alr1" style="display:none;">
						<div class="nicdark_btn nicdark_bg_red medium nicdark_shadow nicdark_radius white nicdark_pres" style="width:100%">Please Fill All The Fields.</div>
						 <div class="nicdark_space20"></div>
				</div>

				<form id="formf" onsubmit="return sendFooterEmail();">
				   <input class="nicdark_bg_grey2 nicdark_radius nicdark_shadow grey small subtitle" type="text" value="" size="200" placeholder="NAME" name="fname" required>
                    <div class="nicdark_space10"></div>
                    <input class="nicdark_bg_grey2 nicdark_radius nicdark_shadow grey small subtitle" type="email" value="" size="200" placeholder="EMAIL" name="femail" required>
                    <div class="nicdark_space10"></div>
                    <textarea rows="3" class="nicdark_bg_grey2 nicdark_radius nicdark_shadow grey small subtitle" placeholder="MESSAGE" name="fmessage" required></textarea>
                    <div class="nicdark_space10"></div>
                    <input id="sendml" class="nicdark_btn nicdark_bg_green medium nicdark_shadow nicdark_radius white" type="submit" name="btnsubmit" value="SEND" >
                </form>

            </div>
        </div>

        <div class="nicdark_space50"></div>

    </div> </div>
    <!--end nicdark_container-->

</section>
<!--end section-->


<!--start section-->
<div class="nicdark_section nicdark_bg_greydark2 nicdark_copyrightlogo">

    <!--start nicdark_container-->

    <div class="tfooter">

    <div class="nicdark_container nicdark_clearfix">



   </div>

    <!--end nicdark_container-->

</div>
<?php if(isset($webpage) && $webpage=='city'){ ?>
	<script data-main="/assets/scripts/options" src="<?=base_url();?>assets/scripts/components/require.js"></script>

<?php } ?>
<script>

function sendFooterEmail()
{
	if($("#formf")[0].checkValidity())
	{
		$("#sendml").css('pointer-events','none');
		var fform=$("#formf");
		$.ajax({
				type:'POST',
				url:'<?php echo site_url("footerSendEmail") ?>',
				data:fform.serialize(),
				success:function(data)
				{
					if(data==1)
					{
						$(".alr1").show();
						$("#sendml").css('pointer-events','auto');

					}
					else
					{
						$(".alr").show().delay(4000).fadeOut();
						$("#formf")[0].reset();
						$("#sendml").css('pointer-events','auto');
					}
				}
			});
			return false;

	}
	return false;
}

function addFooterNewsletter()
{
	var email=$("#femail").val();
	if($("#formn")[0].checkValidity())
	{
		$.ajax({
				type:'POST',
				url:'<?php echo site_url("addNewsletter") ?>',
				data:'email='+email,
				success:function(data)
				{
					if(data==1)
					{
						$("#femail").val('');
						$("#fmsg2").hide();
						$("#fmsg1").show();
					}
					if(data==2)
					{
						$("#fmsg1").hide();
						$("#fmsg2").show();
					}
				}
			});

	}
	return false;


}

</script>

<?php

function text_cut1($text, $length = 180, $dots = true) {
	$cnt = 0;
	$strl = strlen($text);
	if ($strl <= 180) {
		return $text;
	}
	for ($i = 180; $i > 0; $i--) {
		if ($text[$i] == '.') {
			return getwords1($text, $i);
			break;
		}
	}

}

function trim_by_words($string) {
	$s = substr($string, 0, 105);
	$result = substr($s, 0, strrpos($s, ' '));
	return $result . '...';
}

function getwords1($text, $pos) {
	return substr($text, 0, $pos) . '......';
}

?>

