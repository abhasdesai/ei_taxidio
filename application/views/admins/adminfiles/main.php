<section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row">
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-aqua">
		<div class="inner">
		  <h3><?php echo $totalcountries; ?></h3>
		  <p>Total Countries</p>
		</div>
		<div class="icon">
		  <i class="fa fa-fw fa-flag"></i>
		</div>
		<a href="<?php echo site_url('admins/countries') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-green">
		<div class="inner">
      <h3><?php echo $totalcities; ?></h3>
      <p>Total cities</p>
		</div>
		<div class="icon">
		  <i class="fa fa-fw fa-building"></i>
		</div>
		<a href="<?php echo site_url('admins/city/Cities') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-yellow">
		<div class="inner">
      <h3><?php echo $totalusers; ?></h3>
      <p>Total Users</p>
		</div>
		<div class="icon">
		  <i class="fa fa-fw fa-user-plus"></i>
		</div>
		<a href="<?php echo site_url('admins/users') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-red">
		<div class="inner">
      <h3><?php echo $totalsubscribers; ?></h3>
      <p>Total subscribers</p>
		</div>
		<div class="icon">
		  <i class="fa fa-fw fa-users"></i>
		</div>
		<a href="<?php echo site_url('admins/subscribers') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div><!-- ./col -->
  </div><!-- /.row -->
  <!-- Main row -->
  <div class="row"></div><!-- /.row (main row) -->

</section><!-- /.content -->
