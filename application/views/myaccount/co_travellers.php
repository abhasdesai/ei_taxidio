
      <!-- Modal content-->
      <div class="modal-content inv-trip-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Co-traveler Details</h4>
        </div>

     <?php  foreach($co_travellers as $list){ ?>
     <input type="hidden" id="cot_iti_id" value="<?php echo string_encode($list['itinerary_id']); ?>">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <table class="inv-trip-tbl">
                  <tr>
                      <td class="tblh"><strong>Name:</strong></td>
                      <td><?php echo $list['name']; ?></td>
                  </tr>
                  <?php /*?><tr>
                      <td class="tblh"><strong>Date of Birth:</strong></td>
                      <td><?php if(isset($list['dob']) && $list['dob']!='' && strtotime($list['dob'])>0){ echo date('d/m/Y',strtotime($list['dob'])); }else{ echo "N/A"; } ?></td>
                  </tr>
                  <tr>
                      <td class="tblh"><strong>Gender:</strong></td>
                      <td>
                      <?php 
                      if($list['gender']==1 ||$list['gender']==0){ 
                        echo 'Male'; 
                      }else{ 
                        echo 'Female'; 
                      } ?>
                        </td>
                  </tr><?php */?>
                  <tr>
                      <td class="tblh"><strong>Email:</strong></td>
                      <td><?php echo $list['email']; ?></td>
                  </tr>
                  <!-- <tr>
                      <td class="tblh"><strong>Phone No:</strong></td>
                      <td><?php echo $list['phone']?$list['phone']:'N/A'; ?></td>
                  </tr>
                  <tr>
                      <td class="tblh"><strong>Passport Number:</strong></td>
                      <td><?php echo $list['passport']?$list['passport']:'N/A'; ?></td>
                  </tr> -->
              </table>
            </div>
          </div>
        </div>
     <?php } ?>
        <div class="modal-footer">
           <div id="cotraveller" class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
            <?php echo $pagination; ?>
           </div>
        </div>
      </div>
