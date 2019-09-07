<section class="content">
	<!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
			   <div class="box-body">
				<?php if ($this->session->flashdata('success')) {?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php }?>
			<?php if ($this->session->flashdata('error')) {?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php }?>
            <div class="box-header">
                  <h3 class="box-title"><?php echo $section; ?></h3>
                  <hr class="hrstyle">
                </div><!-- /.box-header -->
				
            <div class="box-body">

			 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th>Sr#</th>
						<th>Username</th>
						<th>Email</th>
						<th>Package</th>
						<th>Package Quantity</th>
						<th>Package Price</th>
						<th>Purchased Date</th>
						<th>Total Bill Paid</th>
                      </tr>
                    </thead>
                    <tbody>
						<?php if(count($orders) > 0){?>
						<?php $c=0;foreach($orders as $o){?>
							<tr>
								<td><?= ++$c;?></td>
								<td><?= $o->name;?></td>
								<td><a href="mailto:<?= $o->email?>"><?= $o->email;?></a></td>
								<td><?= $o->package_name;?></td>
								<td><?= $o->package_qty;?></td>
								<td><?= $o->package_price;?></td>
								<td><?= date('l M j, Y',strtotime($o->purchsed_date));?></td>
								<td><?= $o->amount;?></td>
							</tr>
							
						<?php }?>
						<?php }?>
                    </tbody>
                  </table>
				</div>
			</div><!-- /.box-body -->
          </div><!-- /.box -->
	</section><!-- /.content -->
	
	<script src="<?php echo site_url();?>assets/admin/js/dataTables.bootstrap.min.js"></script>
	
	<script type="text/javascript"  charset="utf-8">
		$(document).ready(function(){
			$('#example1').dataTable({});
		});
	</script>

