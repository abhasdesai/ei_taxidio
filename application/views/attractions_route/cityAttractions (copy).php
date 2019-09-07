<?php if($cityimage!=''){ ?>
<div class="mainview container-fluid" style="background:url('<?php echo site_url("userfiles/cities")."/".$cityimage ?>');">
<?php } else { ?>
<div class="mainview container-fluid" style="background:url('<?php echo site_url("assets/images/cairo.jpg") ?>');">
<?php } ?>


<style>
  
.pac-container {
    background-color: #FFF;
    z-index: 20;
    position: fixed;
    display: inline-block;
    float: left;
}
.modal{
    z-index: 20;   
}
.modal-backdrop{
    z-index: 10;        
}​

</style>
   
<?php if(count($searchcity)){ ?>

<div class="explore-country container no-padding">
  <h1>Explore Japan</h1>
</div>

 <div class="inner-mainview container">
  
   <div class="row">
          
          <ul id="sortable">

            <?php $c=0; foreach($attractioncities as $list){ $c++; ?>

            <li class="ui-state-default <?php if($c==1){ echo 'active-li'; } ?>" onclick="funOpen('<?php echo md5($list['id']) ?>')"><a href="javascript:void(0);"><?php echo $list['city_name']; ?></a>
            </li>

            <?php } ?>
        </ul>

    </div>


<div id="bindTab">
        <div class="sidebar col-md-4">
             <div id='listings' class='listings singlebar'></div>
            </div>
           <div id="map" class="map col-md-8"> </div>

           <div class="col-md-12 inner-links">
            <p class="text-left">
              <a class="link-button modal-link" data-toggle="modal" id="singlemodel" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New Location</a>
              <a id="showallSingle" idattr="<?php echo md5($attractioncities[0]['id']); ?>" class="link-button modal-link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i>View All</a>
            </p>
          </div>
     
</div>

</div>

<div class="container">
  <div class="row button-collections">
    <div class="col-md-12">
    <?php $param=urlencode($cityname).'/'.$longitude.'/'.$latitude ?>
      <ul class="button-ul">
        <li><a href="<?php echo site_url('cityAttractionFromGYG').'/'.$param; ?>" class="link-button" target="_blank">Attraction Tickets<i class="fa fa-map-signs" aria-hidden="true"></i></a></li>
         <?php if($this->session->userdata('fuserid')!=''){ ?>

        <li><a href="<?php echo site_url('showSearchedCityHotels').'/'.$countryid_encrypt; ?>" class="link-button">Hotel Bookings<i class="fa fa-hand-o-right" aria-hidden="true"></i></a></li>

        <?php }else{ ?>

        <li><a href="javascript:void(0);" class="link-button openloginform">Hotel Bookings<i class="fa fa-hand-o-right" aria-hidden="true"></i></a></li>

        <?php } ?>
      </ul>
    </div>
  </div>
</div>

<script>


  $(document).ready(function(){
           $.LoadingOverlay("show");
          // This will let you use the .remove() function later on
            if (!('remove' in Element.prototype)) {
              Element.prototype.remove = function() {
                if (this.parentNode) {
                    this.parentNode.removeChild(this);
                }
              };
            }


            mapboxgl.accessToken = 'pk.eyJ1IjoiZWlqaW5hYWwiLCJhIjoiY2l0eWR3NGF4MDAzMDQ1b2FpZmlmdHQzdyJ9.zONIJ0N7SED6ayhXFSR37g';

            // This adds the map
            var map = new mapboxgl.Map({
              // container id specified in the HTML
              container: 'map', 
              // style URL
              style: 'mapbox://styles/mapbox/streets-v8', 
              // initial position in [long, lat] format
              center: [parseFloat(<?php echo $longitude ?>),parseFloat(<?php echo $latitude ?>)], 
              // initial zoom
              zoom: 12,
              minZoom:12

            });
            
            var stringified='<?php echo $filestore; ?>';
            var filestore=JSON.parse(stringified);

            //map.scrollZoom.disable();
            var stores = {
              "type": "FeatureCollection",
              "features":filestore
              };
            // This adds the data to the map
            map.on('load', function (e) {
              // Add a GeoJSON source containing place coordinates and information.
              map.addSource("places", {
                "type": "geojson",
                "data": stores
              });
              // This is where your '.addLayer()' used to be
              // Initialize the list
              buildLocationList(stores);

              monthArrayr=[];
              stores.features.forEach(function(marker, i){ 
                if(marker.isselected==1 && marker.properties.isplace==1)
                 {

                    monthArrayr.push([parseFloat(marker.geometry.coordinates[0]),parseFloat(marker.geometry.coordinates[1])]);
                 }
              
              });

             
              map.addSource("route", {
                      "type": "geojson",
                      "data": {
                          "type": "Feature",
                          "geometry": {
                              "type": "LineString",
                              "coordinates": monthArrayr
                          }
                      }
                  });

                   map.addLayer({
                      "id": "route",
                      "type": "line",
                      "source": "route",
                      "layout": {
                          "line-join": "round",
                          "line-cap": "round"
                      },
                      "paint": {
                          "line-opacity": 0.9,
                          "line-color": "#5a4371",
                          "line-width": 4
                      }
                 });

            });


            // This is where your interactions with the symbol layer used to be
            // Now you have interactions with DOM markers instead
            stores.features.forEach(function(marker, i) {
              
              //getyourguide
              // Create an img element for the marker
              //if((marker.isselected==1 || marker.tempremoved==1))
               if(marker.isselected==1 || marker.tempremoved==1)
              {

                    var el = document.createElement('div');
                    el.id = "marker-" + i;
                    if(marker.properties.getyourguide==1)
                    {
                      el.className = 'gyg';
                    }
                    else
                    {
                      el.className = 'marker';
                    }
                    el.style.left='-28px';
                    el.style.top='-46px';
                    // Add markers to the map at all points
                    new mapboxgl.Marker(el)
                        .setLngLat(marker.geometry.coordinates)
                        .addTo(map); 

                    el.addEventListener('click', function(e){

                        // 1. Fly to the point
                        flyToStore(marker);

                        // 2. Close all other popups and display popup for clicked store
                        createPopUp(marker);
                        
                        // 3. Highlight listing in sidebar (and remove highlight for all other listings)
                        var activeItem = document.getElementsByClassName('active');

                        e.stopPropagation();
                        if (activeItem[0]) {
                           activeItem[0].classList.remove('active');
                        }

                        var listing = document.getElementById('listing-' + i);
                        $('#listing-' + i+' h3').addClass('active');
                        //listing.classList.add('active');

                    });
                }
            });

            
            function flyToStore(currentFeature) {
              map.flyTo({
                  center: currentFeature.geometry.coordinates,
                  zoom: 17
                }); 
            }

            function createPopUp(currentFeature) 
            {

              var popUps = document.getElementsByClassName('mapboxgl-popup');
              if (popUps[0]) popUps[0].remove();
              var latlong=currentFeature.geometry.coordinates.toString().replace(',','/');
              var latlongstring=currentFeature.properties.cityid+'/'+latlong; 
                if(currentFeature.properties.getyourguide==1)
                {
                    var popup = new mapboxgl.Popup({closeOnClick: false})
                      .setLngLat(currentFeature.geometry.coordinates)
                      .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' + 
                        '<span class="knownfor">Known For</span><h4>'+currentFeature.properties.known_tags+'</h4>' + 
                        '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + "\',\'" + currentFeature.properties.category + '\')">Read More</a><span><a href="<?php echo site_url("attractionsFromGYG") ?>/'+latlongstring+'" target="_blank">Buy Tickets</a></span>')
                      .addTo(map);
                }
                else
                {
                    
                    if(currentFeature.properties.isplace==1)
                    {
                       if(currentFeature.properties.knownfor==0)
                        {
                             var popup = new mapboxgl.Popup({closeOnClick: false})
                            .setLngLat(currentFeature.geometry.coordinates)
                            .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' + 
                              '<h4 class="noplace">My Activity</h4>')
                            .addTo(map); 
                        }
                        else
                        {
                             var popup = new mapboxgl.Popup({closeOnClick: false})
                            .setLngLat(currentFeature.geometry.coordinates)
                            .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' + 
                              '<span class="knownfor">Known For</span><h4>'+currentFeature.properties.known_tags+'</h4>' + 
                              '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + "\',\'" + currentFeature.properties.category + '\')">Read More</a>')
                            .addTo(map);
                        }
                    }
                    else
                    {
                         if(currentFeature.properties.attractionid.search("_")!="-1")
                         {
                            var popup = new mapboxgl.Popup({closeOnClick: false})
                            .setLngLat(currentFeature.geometry.coordinates)
                            .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' + 
                              '<h4 class="noplace">'+currentFeature.properties.known_tags+'</h4>')
                            .addTo(map); 
                        }
                        else
                        {
                            var popup = new mapboxgl.Popup({closeOnClick: false})
                            .setLngLat(currentFeature.geometry.coordinates)
                            .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' + 
                              '<span class="knownfor">Known For</span><h4 class="noplace">'+currentFeature.properties.known_tags+'</h4>')
                            .addTo(map);  
                        }
                    }

                }

           
            }

           $(document).on('click','.popupclose',function(e){
            var popUps = document.getElementsByClassName('mapboxgl-popup');
              if (popUps[0]) popUps[0].remove();
             map.flyTo({
                  center: stores.features[0].devgeometry.devcoordinates,
                  zoom: 12,
                }); 
              //alert(stores.features[0].devgeometry.devcoordinates);
            });

            function buildLocationList(data) {
              for (i = 0; i < data.features.length; i++) {
                var currentFeature = data.features[i];
                var prop = currentFeature.properties;

                //if((currentFeature.isselected==1 || currentFeature.tempremoved==1) && currentFeature.properties.isplace==1)
                if(currentFeature.isselected==1 && currentFeature.properties.isplace==1)  
                {

                //alert(prop.);
                var listings = document.getElementById('listings');
                var listing = listings.appendChild(document.createElement('div'));
                
                if(prop.getyourguide==1)
                {
                      if(currentFeature.tempremoved==1)
                      {
                        listing.className = 'item group divgyg';
                      } 
                      else
                      {
                        listing.className = 'item group divgyg backgroundclr';  
                      } 

                      
                }
                else
                {
                      if(currentFeature.tempremoved==1)
                      {
                        listing.className = 'item group divtax';
                      } 
                      else
                      {
                        listing.className = 'item group divtax backgroundclr';  
                      }

                   
                }
                listing.id = "listing-" + i;
                
                var linkh3 = listing.appendChild(document.createElement('h3'));

                

                var link = linkh3.appendChild(document.createElement('a'));
                link.href = 'javascript:void(0)';
                link.className = 'title';
                link.dataPosition = i;
                link.innerHTML = prop.name;  

                if(currentFeature.tempremoved==1)
                {
                    var linkadd = linkh3.appendChild(document.createElement('a'));
                    linkadd.href = 'javascript:void(0)';
                    linkadd.className = 'add-tab';
                    linkadd.id = prop.attractionid;
                    linkadd.dataPosition = i;
                    linkadd.innerHTML = '<i class="fa fa-plus" aria-hidden="true" onclick="addMainAttractionSingle(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';    
                }

                if(currentFeature.tempremoved==0)
                {
                    var linkdel = linkh3.appendChild(document.createElement('a'));
                    linkdel.href = 'javascript:void(0)';
                    linkdel.id = prop.attractionid;
                    linkdel.className = 'delete-tab';
                    linkdel.dataPosition = i;
                    linkdel.innerHTML = '<i class="fa fa-trash-o" aria-hidden="true" onclick="deleteMainAttractionSingle(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';
                }       

                        
                

                    link.addEventListener('click', function(e){
                       $(".group h3").removeClass('active');
                      // Update the currentFeature to the store associated with the clicked link
                      var clickedListing = data.features[this.dataPosition];
                      
                      // 1. Fly to the point
                      flyToStore(clickedListing);

                      // 2. Close all other popups and display popup for clicked store
                      createPopUp(clickedListing);
                      
                      // 3. Highlight listing in sidebar (and remove highlight for all other listings)
                      var activeItem = document.getElementsByClassName('active');

                        if (activeItem[0]) {
                           activeItem[0].classList.remove('active');
                        }
                       this.parentNode.classList.add('active');
                  
                  });

                }
              }
            }
           
         setTimeout(function(){  $.LoadingOverlay("hide",true); }, 12000);



  });
  
   function showAttractionDetails(cityid,attractionid,category)
   {
      if(cityid!='' && attractionid!='')
      {
          $.ajax({
              type:'POST',
              url:'<?php echo site_url("getAttractionData") ?>',
              data:'cityid='+cityid+'&attractionid='+attractionid+'&category='+category,
              beforeSend: function(){
                  $.LoadingOverlay("show");
               },
              complete: function(){
                  setTimeout(function(){  $.LoadingOverlay("hide",true); }, 2000);
              },
              success:function(data)
              {
                   $("#infmdl").modal('show');

                  $("#showattractiontitle").html('');
                  $("#showattractiondetails").html('');
                  $("#showattractionaddress").html('');
                  $("#showattractioncontact").html('');
                  $("#showattractionwebsite").html('');
                  $("#showattractionwebsite").html('');
                  $("#showattractiontransport").html('');
                  $("#showattractiontiming").html('');
                  $("#showattractiontimereq").html('');
                  $("#showattractionwaittime").html('');
                  //$("#showattractionbuy").html(jsonresponse.attraction_buy_ticket);
                  $("#showattractionknown").html('');


                   var jsonresponse=JSON.parse(data);
                   var name='N/A';var attraction_address='N/A';var attraction_contact='N/A';var attraction_website='N/A';var attraction_public_transport='N/A';
                   var attraction_timing='N/A';var attraction_time_required='N/A';var attraction_wait_time='N/A';var tag_name='N/A';var details='N/A';

                  if(jsonresponse.image!='' && jsonresponse.image!=null)
                  {
                    image=jsonresponse.image;
                    var url='<?php echo site_url("userfiles/images"); ?>';
                    $("#img").attr('src',url+'/'+image);
                  }
                  else
                  {
                    var url='<?php echo site_url("assets/images/image5.jpg"); ?>';
                    $("#img").attr('src',url);
                  }
                   
                  if(jsonresponse.name!='')
                  {
                     name=jsonresponse.name;
                  }
                  if(jsonresponse.details!='')
                  {
                     details=jsonresponse.details;
                  }
                  if(jsonresponse.attraction_address!='')
                  {
                     attraction_address=jsonresponse.attraction_address;
                  }
                  if(jsonresponse.attraction_contact!='')
                  {
                     attraction_contact=jsonresponse.attraction_contact;
                  }
                  if(jsonresponse.attraction_website!='')
                  {
                     attraction_website=jsonresponse.attraction_website;
                      $("#showattractionwebsite").html('<a href="" target="_blank">Click Here</a>');
                     $("#showattractionwebsite a").attr('href',attraction_website);
                  }
                  if(jsonresponse.attraction_public_transport!='')
                  {
                     attraction_public_transport=jsonresponse.attraction_public_transport;
                  }
                  if(jsonresponse.attraction_timing!='')
                  {
                     attraction_timing=jsonresponse.attraction_timing;
                  }
                  if(jsonresponse.attraction_timing!='')
                  {
                     attraction_timing=jsonresponse.attraction_timing.replace(/\n/g,"<br>");
                  }
                  if(jsonresponse.attraction_time_required!='' && jsonresponse.attraction_time_required>0)
                  {
                    if(jsonresponse.attraction_time_required<2)
                    {
                       var hr=' Hour';
                    }
                    else
                    {
                      var hr=' Hours'; 
                    }

                     attraction_time_required=jsonresponse.attraction_time_required+''+hr;
                  }
                  if(jsonresponse.attraction_wait_time!='')
                  {
                     attraction_wait_time=jsonresponse.attraction_wait_time;
                  }
                  if(jsonresponse.tag_name!='')
                  {
                     tag_name=jsonresponse.tag_name;
                  }

                  $("#showattractiontitle").html(name);
                  $("#showattractiondetails").html(details);
                  $("#showattractionaddress").html(attraction_address);
                  $("#showattractioncontact").html(attraction_contact);
                  
                  $("#showattractiontransport").html(attraction_public_transport);
                  $("#showattractiontiming").html(attraction_timing);
                  $("#showattractiontimereq").html(attraction_time_required);
                  $("#showattractionwaittime").html(attraction_wait_time);
                  //$("#showattractionbuy").html(jsonresponse.attraction_buy_ticket);
                  $("#showattractionknown").html(tag_name);
                  //$("#showattractionimage").html(jsonresponse.image);
                  
                 
              }
          });
      }
  }

  function funOpen(id)
  {
     $("#citypostid").val(id);
     $("li").removeClass('active-li');
     $.ajax({
          type:'POST',
          url:'<?php echo site_url("single-city-attractions-ajax") ?>',
          data:'id='+id,
          beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 12000);
          },
          success:function(data)
          {
             $("#bindTab").html(data.body);
          }
     });

  }

  $("ul#sortable li").click(function(){
    $(this).siblings('li').removeClass('active-li');
    //$(this).siblings('li').css('pointer-events','auto');
    $(this).addClass('active-li');
    //$(this).css('pointer-events','none');

  });

  
  
  
    </script>
 
    <?php } ?>

    <div id="infmdl" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="showattractiontitle">Modal Header</h4>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-6">
          <img id="img" src="<?php echo site_url("assets/images/image5.jpg"); ?>" width="100%" />
        </div>
        <div class="col-md-6">
           <span class="city-popup-title">Known For : </span><p id="showattractionknown"></p>
           <div class="contact-details-popup">
             <span class="city-popup-title">Address : </span><p id="showattractionaddress"></p>
            
           </div>
        </div>
      </div>
        <div class="row conatct-address-popup">
          <div class="col-md-6">
             <span class="city-popup-title">Contact : </span><p id="showattractioncontact"></p>
             
          </div>
          <div class="col-md-6">
            <span class="city-popup-title">Website : </span>
            <p id="showattractionwebsite">
              <a href="" target="_blank">Click Here</a>
            </p>
          </div>
          <div class="city-popup-details">
            <div class="col-md-12">
              <span class="city-popup-title">Details</span>
              <p id="showattractiondetails"></p>
            </div>
          </div>
         <div class="row margin-popup-0">
          <div class="col-md-6">
            <span class="city-popup-title">Public Transport : </span><p id="showattractiontransport"></p>
          </div>
          <div class="col-md-6">
            <span class="city-popup-title">Timing : </span><p id="showattractiontiming"></p>
          </div>
         </div> 
         <div class="row margin-popup-0">
          <div class="col-md-6">
            <span class="city-popup-title">Time Required : </span><p id="showattractiontimereq"></p>
          </div>
          <div class="col-md-6">
            <span class="city-popup-title">Waiting Time : </span><p id="showattractionwaittime"></p>
          </div>
          </div>
          
        </div>
       
         </div>
      <div class="modal-footer">
        <button type="button" class="link-button" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="mapModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Activity</h4>
      </div>
      <div class="modal-body">
          
      <?php echo form_open('',array('id'=>'addNewActivitySingleForm')); ?>

          <div class="row">
            <div class="control-group">
                  <label class="control-label" for="location">Location:</label>
                  <div class="controls">
                    <input required id="searchTextField" name="location" type="text" class="form-control" placeholder="" class="input-medium" required="">
                    <input type="hidden" id="exlat" name="exlat"/>
                    <input type="hidden" id="exlong" name="exlong"/>
                    <input type="hidden" id="isall" name="isall" value="0"/>
                    <input type="hidden" id="citypostid" name="citypostid" value="<?php echo $citypostid ?>"/>
                  </div>
                </div>
          </div>

          <div class="row">
              <div class="control-group">
                  <label class="control-label" for="Save"></label>
                  <div class="controls">
                    <button class="action-button shadow animate blue" id="btnaddac" type="submit">Add This Place</button>
                  </div>
                </div>
          </div>

          <?php echo form_close(); ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
  
function initialize() {
    $("#btnaddac").hide();
    var input = document.getElementById('searchTextField');
    var options = {componentRestrictions: {}};
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      $("#btnaddac").show();
      $("#exlat").val(place.geometry.location.lat());
      $("#exlong").val(place.geometry.location.lng()); 
    });
}
             
google.maps.event.addDomListener(window, 'load', initialize);


</script>
</div>

