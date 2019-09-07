<section class="content">
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

				<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $section; ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->
		   <a href="<?php echo site_url('admins/city/Cities'); ?>" class="btn bg-navy margin">Back to Cities</a>

            <div class="box-body table-responsive">
					

				<?php if(count($months)){ ?>
	

				<?php echo form_open('admins/city/Weathers/saveData', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				 <div class="row">
				  <input type="hidden" name="city_id" value="<?php echo $id; ?>"/>
		       		<table class="table table-hover">
	                 	  <tr>
		                      <th>Month</th>
		                      <th>High</th>
		                      <th>Low</th>
		                      <th>Average</th>
		                      <th>Precipitation</th>
		                      <th>Rain</th>
		                      <th>Snow</th>
		                      <th>Sunrise</th>
		                      <th>Sunset</th>
						 </tr>

							<?php foreach($months as $list){

								$weather=$this->Weather_m->getMonthWiseWeather($list['id'],$id);
							 ?>
								 <tr>
			                      <td><?php echo $list['month_name']; ?><input type="hidden" name="month_id[]" class="form-control" value="<?php echo $list['id']; ?>"/></td>
			                      <td><input type="text" name="weather_high[]" class="form-control" value="<?php if(isset($weather['weather_high']) && $weather['weather_high']!=''){ echo $weather['weather_high']; } ?>"/></td>
			                      <td><input type="text" name="weather_low[]" class="form-control"/ value="<?php if(isset($weather['weather_low']) && $weather['weather_low']!=''){ echo $weather['weather_low']; } ?>"></td>
			                      <td><input type="text" name="weather_avg[]" class="form-control" value="<?php if(isset($weather['weather_avg']) && $weather['weather_avg']!=''){ echo $weather['weather_avg']; } ?>"/></td>
			                      <td><input type="text" name="weather_precipitation[]" class="form-control" value="<?php if(isset($weather['weather_precipitation']) && $weather['weather_precipitation']!=''){ echo $weather['weather_precipitation']; } ?>"/></td>
			                      <td><input type="text" name="weather_days_rain[]" class="form-control" value="<?php if(isset($weather['weather_days_rain']) && $weather['weather_days_rain']!=''){ echo $weather['weather_days_rain']; } ?>"/></td>
			                      <td><input type="text" name="weather_days_snow[]" class="form-control" value="<?php if(isset($weather['weather_days_snow']) && $weather['weather_days_snow']!=''){ echo $weather['weather_days_snow']; } ?>"/></td>
			                      <td><input type="text" name="weather_sunrise[]" class="form-control" value="<?php if(isset($weather['weather_sunrise']) && $weather['weather_sunrise']!=''){ echo $weather['weather_sunrise']; } ?>"/></td>
			                      <td><input type="text" name="weather_sunset[]" class="form-control" value="<?php if(isset($weather['weather_sunset']) && $weather['weather_sunset']!=''){ echo $weather['weather_sunset']; } ?>"/></td>
			                    </tr>
			                   
			                  
							
						<?php } ?>
					</table>
			</div><!-- /.row -->	

              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Save"/></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

				<?php }else{ ?>
					<div class="alert alert-info" role="alert">
						Months are not exists.
					</div>
				<?php } ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
