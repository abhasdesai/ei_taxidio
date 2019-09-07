<?php if(isset($webpage) && $webpage=='country_add_edit'){ ?>
<script type="text/javascript">
        $("#form1").submit(function(){
          $("#country_neighbours").tagit({
            fieldName: "tags[]"
          });
          if ($('label[name="country_neighbourserr"]').length) {   
              $('label[name="country_neighbourserr"]').remove();
          }
          if($("#country_neighbours").tagit("assignedTags").length>0){
            
          }
          else{
              $( "#country_neighbours" ).after( "<label name='country_neighbourserr' id='country_neighbourserr' for='country_capital'>This field is required.</label>");
              return false;
          }
        });
      
</script>
<?php } ?>
<script src="<?php echo site_url('assets/admin/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/select2.full.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/jquery.inputmask.date.extensions.js'); ?>"></script>  
<script src="<?php echo site_url('assets/admin/js/jquery.inputmask.extensions.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/moment.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/daterangepicker.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/bootstrap-colorpicker.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/bootstrap-timepicker.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/iCheck/icheck.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/customscripts.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/app.min.js'); ?>"></script>
 <script src="<?php echo site_url('assets/admin/js/jquery-ui.js'); ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo site_url('assets/admin/dist/js/demo.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/tag-it.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/ckeditor/ckeditor.js'); ?>"></script>



<script>

      $(function () {

         $('.singleFieldTags2').tagit({
              singleField: true,
              singleFieldNode: $('.singleFieldTags2'),
              allowSpaces: true,
            });

         $('#country_neighbours').tagit({
              singleField: true,
              singleFieldNode: $('#country_neighbours'),
              allowSpaces: true,
            });

   
     
        $(".select2").select2();

        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        $("[data-mask]").inputmask();

        $('#reservation').daterangepicker();
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        $('#daterange-btn').daterangepicker(
                {
                  ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  },
                  startDate: moment().subtract(29, 'days'),
                  endDate: moment()
                },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

       $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        $(".my-colorpicker1").colorpicker();
        $(".my-colorpicker2").colorpicker();

        $(".timepicker").timepicker({
          showInputs: false
        });
      });

    $("#typeaheadkeywords").autocomplete( {
    source: function(request,response) {
      $.ajax ({
          url: "https://taxidio.rome2rio.com/api/1.4/json/Autocomplete?key=iWe3aBSN&query="+$("#typeaheadkeywords").val(),
          dataType: "json",
          delay: 0,
          selectFirst: true,
          minLength: 0,
          success: function(data) 
          {
              var values = [];
              response($.map(data.places, function (el, ui) {
                     console.log(el);
       
                     return {
                         label: el.longName,
                         value: el.longName,
                         lat: el.lat,
                         lon: el.lng,
                         cname:el.countryName,
                         ccode:el.countryCode,
                     };
              }));
                  
          } 
    }) 
  },
  select: function (event, ui) {
        $("#latitude").val(ui.item.lat);
        $("#longitude").val(ui.item.lon);
        $("#romecountryname").val(ui.item.cname);
        $("#rome2rio_code").val(ui.item.ccode);
        $(this).val(ui.item ? ui.item : " ");},
    
  change: function (event, ui) {
      if (!ui.item) {
          this.value = '';}
      else{
      }
    }
});

</script>


<script>

  CKEDITOR.replace('editor1',{
    "allowedContent":true,
    "extraAllowedContent":  'img[alt,border,width,height,align,vspace,hspace,!src];',
    "extraPlugins" : 'image2,dialog,widget,lineutils,imgbrowse',
    "filebrowserImageBrowseUrl": '<?php echo site_url("assets/admin/ckeditor/plugins/imgbrowse/imgbrowse.html?imgroot=userfiles/chkfiles") ?>',
    "filebrowserImageUploadUrl": '<?php echo site_url("assets/admin/ckeditor/plugins/imgbrowse/imgupload.php") ?>'
  });

CKEDITOR.replace('editor2').config.allowedContent = true;
  </script>
