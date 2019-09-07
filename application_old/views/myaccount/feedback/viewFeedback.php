<div class="container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Help &amp; Feedback</h4>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-sm-12 create-feedback text-right">
        	<a href="<?php echo site_url('userfeedbacks'); ?>" class="btn btn-warning waves-effect waves-light">Back</a>
		</div>
           <div class="col-sm-12">
            <div class="card-box">
                    
                    <div class="row">
                        

                    <div class="col-lg-12">


                    <div class="form-group">
                        <label for="Subject">Subject</label>
                        <input type="text" id="Subject" name="subject" class="form-control" placeholder="subject" maxlength="500" value="<?php echo $feedback['subject']; ?>" readonly>
                   </div>

                  <div class="form-group">
                     <label for="elm1">Message</label>
                     <textarea id="elm1" name="message" class="form-control" readonly><?php echo $feedback['message']; ?></textarea>
                  </div>
                   

                    </div>
                   </div> 


               
            </div>
        </div>
    </div>
    <!-- End row -->
    
    
    </div> <!-- container -->
   
    <script type="text/javascript">
            $(document).ready(function () {
                if($("#elm1").length > 0){
                    tinymce.init({
                        selector: "textarea#elm1",
                        theme: "modern",
                        height:300,
                        readonly:1,
                        plugins: [
                            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                            "save table contextmenu directionality emoticons template paste textcolor"
                        ],
                        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
                        style_formats: [
                            {title: 'Bold text', inline: 'b'},
                            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                            {title: 'Example 1', inline: 'span', classes: 'example1'},
                            {title: 'Example 2', inline: 'span', classes: 'example2'},
                            {title: 'Table styles'},
                            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                        ]
                    });    
                }  
            });
        </script>