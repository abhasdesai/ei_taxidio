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

              <div class="row">
                  <div class="col-md-8">
                      <h3 class="box-title"><?php echo $section ?></h3>
                  </div>
                  <div class="col-md-4 text-right">
                      <a href="<?php echo site_url('admins/users') ?>" class="btn bg-maroon margin">Back</a>
                  </div>
                </div>

               <hr class="hrstyle">
		   </div><!-- /.box-header -->

            <div class="box-body">

            <?php if(isset($user['userimage']) && $user['userimage']!='' && file_exists(FCPATH.'userfiles/userimages/medium/'.$user['userimage'])){ ?>
            <img class="profile-user-img img-responsive img-circle" src="<?php echo site_url('userfiles/userimages/medium').'/'.$user['userimage'] ?>" alt="User profile picture">
          <?php } ?>

              <table class="table table-bordered">

               <tr>
                 <th width="30%" align="center">Name</th>
                 <td align="center"><?php echo $user['name']; ?></td>
               </tr>

               <tr>
                 <th align="center">Email</th>
                 <td align="center"><?php echo $user['email']; ?></td>
               </tr>

               <?php if(isset($user['country_name']) && $user['country_name']!=''){ ?>
                <tr>
                  <th align="center">Country</th>
                  <td align="center"><?php echo $user['country_name']; ?></td>
                </tr>
               <?php } ?>

              <?php if(isset($user['dob']) && $user['dob']!='' && $user['dob']!='0000-00-00'){ ?>
               <tr>
                 <th align="center">Date Of Birth </th>
                 <td align="center"><?php echo date('d-F-Y',strtotime($user['email'])); ?></td>
               </tr>
              <?php } ?>

              <?php if(isset($user['phone']) && $user['phone']!=''){ ?>
               <tr>
                 <th align="center">Phone</th>
                 <td align="center"><?php echo $user['phone']; ?></td>
               </tr>
              <?php } ?>

              <?php if(isset($user['gender']) && $user['gender']!=''){ ?>
               <tr>
                 <th align="center">Gender</th>
                 <td align="center">
                   <?php
                      if($user['gender']==1)
                      {
                         echo "Male";
                      }
                      else if($user['gender']==2)
                      {
                         echo "Female";
                      }
                      else
                      {
                         echo "-";
                      }
                      ?>
                 </td>
               </tr>
              <?php } ?>

              <tr>
                <th align="center">Login/Register With</th>
                <td align="center">
                  <?php
                        if($user['googleid']!='')
                        {
                            echo "Google";
                        }
                        else if($user['facebookid']!='')
                        {
                            echo "Facebook";
                        }
                        else {
                           echo "Website";
                        }
                   ?>
                </td>
              </tr>

              <?php if(isset($user['passport']) && $user['passport']!=''){ ?>
               <tr>
                 <th align="center">Passport </th>
                 <td align="center"><?php echo $user['passport']; ?></td>
               </tr>
              <?php } ?>


              <tr>
                <th align="center">Created</th>
                <td align="center"><?php echo date('d-F-Y H:i:s',strtotime($user['created'])); ?></td>
              </tr>

							<?php if($user['last_login']!='0000-00-00 00:00:00'){?>
              <tr>
                <th align="center">Last Login</th>
                <td align="center"><?php echo date('d-F-Y H:i:s',strtotime($user['last_login'])); ?></td>
              </tr>
							<?php } ?>

             </table>


            </div><!-- /.box-body -->

          </div><!-- /.box -->

</section><!-- /.content -->
