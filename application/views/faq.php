<div class="container">
       
<?php if(count($faq)){ ?>

  <div class="accordion">
   
   <?php foreach($faq as $key=>$list){ ?>

   <h3 class="faqcategory"><?php echo $key; ?></h3>

    <dl>

    <?php foreach($list as $faq){ ?>

     <dt>
        <a href="#<?php echo $faq['id']; ?>" aria-expanded="false" aria-controls="accordion1" class="accordion-title accordionTitle js-accordionTrigger"><?php echo $faq['question']; ?></a>
      </dt>
      <dd class="accordion-content accordionItem is-collapsed" id="accordion<?php echo $faq['id']; ?>" aria-hidden="true">
        <?php echo $faq['answer']; ?>
      </dd>

     <?php } ?> 

    </dl>

    <?php } ?>


  </div>

<?php } ?>

</div>
