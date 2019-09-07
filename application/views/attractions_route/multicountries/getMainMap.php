<div class="sidebar col-md-4">
   <div id='listings' class='listings'></div>
  </div>
 <div id="map" class="map col-md-8"> </div>

 <div class="col-md-12 inner-links">
  <div class="text-left">
    <a class="link-button modal-link" data-toggle="modal" id="ckk" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New Location</a>
     <a id="showmultiall" idattr="<?php echo $cityid; ?>" class="link-button modal-link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i>View All</a>
      <?php if($this->session->userdata('fuserid')!=''){ ?>
        <a class="link-button modal-link" href="<?php echo site_url('save-multi-itinerary').'/'.$secretkey; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Save</a>
    <?php } else { ?> 
         <a href="javascript:void(0);" id="showLogonForm" class="link-button modal-link">Save<i class="fa fa-map-signs" aria-hidden="true"></i></a>
    <?php } ?> 
  </div>
</div>
     

<script type="text/javascript">


  $(document).ready(function(){

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

            

            //map.scrollZoom.disable();
            var stringified='<?php echo $filestore; ?>';
            var filestore=JSON.parse(stringified);
            
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
                
              if(marker.isselected==1 || marker.tempremoved==1)
              {
                  // Create an img element for the marker
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
                    linkadd.innerHTML = '<i class="fa fa-plus" aria-hidden="true" onclick="addMultiMainAttraction(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';    
                }

                if(currentFeature.tempremoved==0)
                {
                    var linkdel = linkh3.appendChild(document.createElement('a'));
                    linkdel.href = 'javascript:void(0)';
                    linkdel.id = prop.attractionid;
                    linkdel.className = 'delete-tab';
                    linkdel.dataPosition = i;
                    linkdel.innerHTML = '<i class="fa fa-trash-o" aria-hidden="true" onclick="deleteMultiMainAttraction(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';
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
           
      
  });

  $(document).ready(
  function() { 
     $(".sidebar").niceScroll({cursorcolor:"#5a4371",cursorwidth:"8px"});
     $( "#listings" ).sortable();
     $( "#listings" ).disableSelection();
   }
  );

  $("#showmultiall").click(function(){

     $.ajax({
        url: '<?php echo site_url("getAllAttractionsOfMultiCity") ?>',
        type: 'POST',
        data: 'id='+$(this).attr('idattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
              $("#isall").val(1);
          }
       
    });
    
});

  $("#showMy").click(function(){
  $.ajax({
        url: '<?php echo site_url("city-attractions-ajax") ?>',
        type: 'POST',
        data: 'id='+$(this).attr('idattr'),
        beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
       
    });
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
                     attraction_address=jsonresponse.name;
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
     $.LoadingOverlay("show");
     $("li").removeClass('active-li');
     $.ajax({

          type:'POST',
          url:'<?php echo site_url("multicity-attractions-ajax") ?>',
          data:'id='+id,
          beforeSend: function(){
             $.LoadingOverlay("show");
          },
          complete: function(){
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
          },
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
     });

  }

 $("ul#sortable li").click(function(){
    if($(this).attr('id')!='lastdragli')
    {
      $(this).siblings('li').removeClass('active-li');
      $(this).addClass('active-li');
    }
  });





    </script>
<script>

$(document).ready(function(){
    $( "#sortable" ).sortable({ cancel: ".nodrag" });
    $( "#sortable" ).disableSelection();
    var cityid="<?php echo $cityid; ?>";
    $('#listings').sortable({
            axis: 'y',
            items: "div.backgroundclr",
            update: function (event, ui) {
                  var data = $(this).sortable('serialize');
                  $.ajax({
                      data: data+'&cityid='+cityid,
                      type: 'POST',
                      url: '<?php echo site_url("saveMultiOrder") ?>',
                      beforeSend: function(){
                         $.LoadingOverlay("show");
                      },
                      complete: function(){
                          setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                      },
                      success:function(data)
                      {
                          $("#bindTab").html(data.body);
                      }
                  });
            }
       });
      $( "#listings" ).disableSelection();

});

$("#ckk").click(function(){
  $("#addNewActivityMultiForm")[0].reset();
 $('#mapModal').modal({
    backdrop: 'static',
    keyboard: false
  })
});
</script>