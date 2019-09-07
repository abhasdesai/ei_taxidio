<div id="pkgpurchase" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<?php /*?><h4 class="modal-title">Purchase Package to Download Treavel Guide</h4><?php */?>
				<h4 class="modal-title">Purchase a package of your choice</h4>
				
				<?php if($this->session->flashdata('pcgmsg')!=''){?>
					<div class="alert alert-info fade in alert-dismissible">
						<?php echo $this->session->flashdata('pcgmsg');?>
						<button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
					</div>
				<?php }?>
				
			</div>
			<div class="modal-body">
				<div class="row">
				<?php foreach($packages as $k=>$p){?>
					<div class="col-sm-4">
						<?php echo form_open('package/purchase',array('name'=>'packageform','id'=>'packageform'));?>
						
						<?php echo form_hidden('package_id',$p->id);?>
						<?php echo form_hidden('city_id',$basic['id']);?>
						<?php echo form_hidden('amount',$p->package_price);?>
						<?php echo form_hidden('bal',$p->package_qty);?>
						
						
						
						<div class="package">
							<?php /*?><div class="margin-bottom-30">
							<a class="tooltip pull-right" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo ($p->description!='')?strip_tags($p->description):'Package Description';?>">
							<i class="fa fa-info-circle"></i>
							</a>
							</div><?php */?>
							
							<div class="package-name"><label class="pull-left">Package Name:</label>&nbsp;<span class="pull-right"><?= $p->package_name?></span></div>
							<div class="package-name"><label class="pull-left">Number of Travel Guides:</label>&nbsp;<span class="pull-right"><?= $p->package_qty?></span></div>
							<div class="package-name"><label class="pull-left">Package Price:</label>&nbsp;<span class="pull-right"><i class="fa fa-inr"></i><?= $p->package_price?></span></div>
							<div class="package-name"><label class="pull-left">Description:</label>&nbsp;<span class="pull-right"><?= $p->description?></span></div>
							<div class="text-center"><?php echo form_submit('btnSubmit','Purchase',array('class'=>'btn btn-success'));?></div>
						</div>
						
						<?php echo form_close();?>
					</div>
				<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
