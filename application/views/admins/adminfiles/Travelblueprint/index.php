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
              <h3 class="box-title"><?php echo $section ?></h3>
               <hr class="hrstyle">
		   </div><!-- /.box-header -->

            <div class="box-body">
				<?php echo form_open('admins/Travelblueprint/store', array('id' => 'form1', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					 <div class="col-md-6">
						  <div class="form-group">
							<label>Country</label>
							<select name="country1" class="form-control required select2">
                <option value="">
                  Select Country
                </option>
                <?php foreach($countries as $list){ ?>
                  <option value="<?php echo $list['id']; ?>" <?php if(count($blueprints) && $list['id']==$blueprints[0]['country_id']){ echo 'selected'; } ?>>
                    <?php echo $list['country_name']; ?>
                  </option>
                <?php } ?>
							</select>
						 </div><!-- /.form-group -->
					</div>

					<div class="col-md-6">
						  <div class="form-group">
							<label>Image</label>
              <?php $class="required"; if(count($blueprints) && isset($blueprints[0]['image'])){
                $class="";
               ?>
                <img src="<?php echo site_url('userfiles/travelblueprint/small').'/'.$blueprints[0]['image'] ?>"/>
              <?php } ?>
							<input type="file" name="image1" class="<?php echo $class ?>" accept=".jpg,.png.gif,.jpeg"/>
						 <p class="text-info">
               Image size must be 577px X 331PX
             </p>
						 </div>
					</div>
			   </div>

    <div class="row">
         <div class="col-md-6">
            <div class="form-group">
            <label>Country</label>
            <select name="country2" class="form-control required select2">
              <option value="">
                Select Country
              </option>
              <?php foreach($countries as $list){ ?>
                <option value="<?php echo $list['id']; ?>" <?php if(count($blueprints) && $list['id']==$blueprints[1]['country_id']){ echo 'selected'; } ?>>
                  <?php echo $list['country_name']; ?>
                </option>
              <?php } ?>
            </select>
           </div><!-- /.form-group -->
        </div>

        <div class="col-md-6">
            <div class="form-group">
            <label>Image</label>
            <?php $class="required"; if(count($blueprints) && isset($blueprints[1]['image'])){  $class="";
               ?>
                <img src="<?php echo site_url('userfiles/travelblueprint/small').'/'.$blueprints[1]['image'] ?>"/>
              <?php } ?>
            <input type="file" name="image2" class="<?php echo $class ?>" accept=".jpg,.png.gif,.jpeg"/>
           <p class="text-info">
             Image size must be 577px X 331PX
           </p>
           </div>
        </div>
       </div>

    
      <div class="row">
       <div class="col-md-6">
          <div class="form-group">
          <label>Country</label>
          <select name="country3" class="form-control required select2">
            <option value="">
              Select Country
            </option>
            <?php foreach($countries as $list){ ?>
              <option value="<?php echo $list['id']; ?>" <?php if(count($blueprints) && $list['id']==$blueprints[2]['country_id']){ echo 'selected'; } ?>>
                <?php echo $list['country_name']; ?>
              </option>
            <?php } ?>
          </select>
         </div><!-- /.form-group -->
      </div>

      <div class="col-md-6">
          <div class="form-group">
          <label>Image</label>
          <?php $class="required"; if(count($blueprints) && isset($blueprints[2]['image'])){  $class="";
               ?>
                <img src="<?php echo site_url('userfiles/travelblueprint/small').'/'.$blueprints[2]['image'] ?>"/>
              <?php } ?>
          <input type="file" name="image3" class="<?php echo $class ?>" accept=".jpg,.png.gif,.jpeg"/>
         <p class="text-info">
           Image size must be 1170px X 331PX
         </p>
         </div>
      </div>
     </div>
    
     <div class="row">
         <div class="col-md-6">
            <div class="form-group">
            <label>Country</label>
            <select name="country4" class="form-control required select2">
              <option value="">
                Select Country
              </option>
              <?php foreach($countries as $list){ ?>
                <option value="<?php echo $list['id']; ?>" <?php if(count($blueprints) && $list['id']==$blueprints[3]['country_id']){ echo 'selected'; } ?>>
                  <?php echo $list['country_name']; ?>
                </option>
              <?php } ?>
            </select>
           </div><!-- /.form-group -->
        </div>

        <div class="col-md-6">
            <div class="form-group">
            <label>Image</label>
            <?php $class="required"; if(count($blueprints) && isset($blueprints[3]['image'])){  $class="";
               ?>
                <img src="<?php echo site_url('userfiles/travelblueprint/small').'/'.$blueprints[3]['image'] ?>"/>
              <?php } ?>
            <input type="file" name="image4" class="<?php echo $class ?>" accept=".jpg,.png.gif,.jpeg"/>
           <p class="text-info">
             Image size must be 577px X 331PX
           </p>
           </div>
        </div>
       </div>

        <div class="row">
         <div class="col-md-6">
            <div class="form-group">
            <label>Country</label>
            <select name="country5" class="form-control required select2">
              <option value="">
                Select Country
              </option>
              <?php foreach($countries as $list){ ?>
                <option value="<?php echo $list['id']; ?>" <?php if(count($blueprints) && $list['id']==$blueprints[4]['country_id']){ echo 'selected'; } ?>>
                  <?php echo $list['country_name']; ?>
                </option>
              <?php } ?>
            </select>
           </div><!-- /.form-group -->
        </div>

        <div class="col-md-6">
            <div class="form-group">
            <label>Image</label>
            <?php $class="required"; if(count($blueprints) && isset($blueprints[4]['image'])){  $class="";
               ?>
                <img src="<?php echo site_url('userfiles/travelblueprint/small').'/'.$blueprints[4]['image'] ?>"/>
              <?php } ?>
            <input type="file" name="image5" class="<?php echo $class ?>" accept=".jpg,.png.gif,.jpeg"/>
           <p class="text-info">
             Image size must be 577px X 331PX
           </p>
           </div>
        </div>
       </div>





              <table>
				<tr>
					<td><input type="submit" class="btn btn-block btn-primary" name="btnsubmit" value="Submit"/></td><td>&nbsp;</td>
				</tr>
			</table>

				<?php echo form_close(); ?>

            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
