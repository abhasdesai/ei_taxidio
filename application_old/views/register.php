<div class="container-fluid">
</div>
<div class="container image-gallery" id="book">
	<div class="col-md-12">
    	<div id="agent-form1">
    	<h3 class="title-h3" style="padding:3% 0;">PLEASE FILL BELOW FORM TO BECOME OUR AGENT</h3>
        
        <div class="alert alert-success" id="ralr" style="display:none;">
            Your request has been submited. Your account will be activated by administration soon.
        </div>

        

         <form class="search-form clearfix" id="form2" onsubmit="return addAgent();">  		
            	<div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                     <input type="text" name="first_name" class="form-control" maxlength="60" required placeholder="First Name"/>
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                   <input type="text" name="last_name" class="form-control required" maxlength="60" placeholder="Last Name"/>
				</div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                   <input type="text" name="phone" class="form-control" placeholder="Phone" maxlength="30" required/>
				</div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                   <input type="email" name="email" id="agentemail" class="form-control" maxlength="100" placeholder="Email"/>
                   <div id="msg" style="display:none;" style="color:red">This email already exists.</div>
				</div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                   <input type="password" name="password" class="form-control" maxlength="30" required placeholder="Password"/>
				</div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon room right-addon">
                	<textarea name="address" class="form-control required" maxlength="400" required placeholder="Address"></textarea>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                  <input type="text" name="city" class="form-control required" maxlength="100" placeholder="City"/>
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                  <input type="text" name="state" class="form-control required" maxlength="100" placeholder="State"/>
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon room right-addon">
                    <input type="text" name="postcode" class="form-control required" maxlength="60"  placeholder="Postcode"/>
                </div>

                 <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                  <input type="text" name="country" class="form-control required" maxlength="100"  placeholder="Country"/>
                </div>


                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                  <input type="text" name="gds" class="form-control required" maxlength="200" placeholder="GDS" required/>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon right-addon">
                  <input type="text" name="psuedo_code" class="form-control required" maxlength="100"  placeholder="Psuedo Code"/>
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12 nopadding inner-addon room right-addon">
                    <input type="text" name="iata" class="form-control required" maxlength="200"  placeholder="Iata"/>
                </div>
                
              <div class="col-md-12 agent">
                 <button type="submit" style="margin-top:0;">Submit</button>
                 </div>
                </form>
                </div>
    </div>
   
</div>

<script type="text/javascript">
    
$(document).ready(function(){
    if($("#flag").val()==1)
    {
        $('html, body').animate({
            scrollTop: $("#book").offset().top
        }, 2000);
    }
});

function addAgent()
{
    if($("#form2")[0].checkValidity())
    {

        $.ajax({
                type:'POST',
                url:'<?php echo site_url("checkEmail") ?>',
                data:'email='+$("#agentemail").val(),
                success:function(data)
                {
                    if(data==0)
                    {
                         $("#msg").hide();
                         var form2=$("#form2");
                         $.ajax({
                                type:'POST',
                                url:'<?php echo site_url("addAgent") ?>',
                                data:form2.serialize(),
                                success:function(data)
                                {
                                    $("#ralr").show()
                                }
                            });
                           
        
                    }
                    else
                    {
                        $("#msg").show();
                    }
                }
            });
            

        
    }
    return false;
}

$("#agentemail").blur(function(){

    if($("#agentemail").val()!='')
    {
        $.ajax({
                type:'POST',
                url:'<?php echo site_url("checkEmail") ?>',
                data:'email='+$("#agentemail").val(),
                success:function(data)
                {
                    if(data==0)
                    {
                         $("#msg").hide();
                     
                    }
                    else
                    {
                        $("#msg").show();
                    }
                }
            });
    }

});


</script>
