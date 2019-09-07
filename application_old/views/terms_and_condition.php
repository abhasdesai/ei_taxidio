<div class="container">
       
<?php if(count($cms) && $cms['content']!=''){ ?>

      <h2><?php echo $cms['title']; ?></h2>

      <?php echo $cms['content']; ?>


<?php } else { ?>

    <div class="alert alert-info">
      Nothing To Show.
    </div>

<?php } ?>
</div>
