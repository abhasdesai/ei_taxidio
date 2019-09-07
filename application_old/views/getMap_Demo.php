<?php if(count($attractioncities)){ ?>

<div class="explore-country container no-padding">
  <h1>Explore Japan</h1>
</div>

 <div class="inner-mainview container">
  
   <div class="row">
          
          <ul id="sortable">

            <?php $c=0; foreach($attractioncities as $list){ $c++; ?>

            <li class="ui-state-default <?php if($c==1){ echo 'active-li'; } ?>" onclick="funOpen('<?php echo md5($list['id']) ?>')"><a href="javascript:void(0);"><?php echo $list['city_name']; ?></a>
            <span class="destination-time">5hrs 13min<br/><i class="fa fa-fighter-jet" aria-hidden="true"></i></span> 
            </li>

            <?php } ?>
        </ul>

    </div>


<div id="bindTab">
        <div class="sidebar col-md-4">
             <div id='listings' class='listings'></div>
            </div>
           <div id="map" class="map col-md-8"> </div>

           <div class="col-md-12 inner-links">
            <p class="text-left">
              <a class="link-button modal-link" data-toggle="modal" data-target="#demo" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New Location</a>
              <a id="showall" idattr="<?php echo md5($attractioncities[0]['id']); ?>" class="link-button modal-link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i>View All</a>
            </p>
          </div>
     
</div>

</div>

<div class="container">
  <div class="row button-collections">
    <div class="col-md-12">
      <ul class="button-ul">
        <li><a href="#" class="link-button">Attraction Tickets<i class="fa fa-map-signs" aria-hidden="true"></i></a></li>
        <li><a href="#" class="link-button">Live with Locals<i class="fa fa-heart" aria-hidden="true"></i></a></li>
        <li><a href="#" class="link-button">Hotel Bookings<i class="fa fa-hand-o-right" aria-hidden="true"></i></a></li>
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
              zoom: 10,

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

                 if(marker.isselected==1)
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

            function createPopUp(currentFeature) {
              //alert(2);
              //alert(currentFeature.properties.address);
              var popUps = document.getElementsByClassName('mapboxgl-popup');
              if (popUps[0]) popUps[0].remove();


            /*  var popup = new mapboxgl.Popup({closeOnClick: false})
                    .setLngLat(currentFeature.geometry.coordinates)
                    .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                      '<h4>'+currentFeature.properties.address+'</h4>' + 
                      '<a href="javascript:void(0);" onClick="showDetails(\'' + currentFeature.properties.attractionid + '\')">Read More</a>')
                    .addTo(map);
            }
           */


            if(currentFeature.properties.address!='')
            { 
                var latlongstring=currentFeature.geometry.coordinates.toString().replace(',','/');
               
                if(currentFeature.properties.getyourguide==1)
                {
                    var popup = new mapboxgl.Popup({closeOnClick: false})
                      .setLngLat(currentFeature.geometry.coordinates)
                      .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                        '<h4>'+currentFeature.properties.address+'</h4>' + 
                        '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + '\')">Read More</a><span><a href="<?php echo site_url("attractionsFromGYG") ?>/'+latlongstring+'" target="_blank">Buy Tickets</a></span>')
                      .addTo(map);
                }
                else
                {
                    var popup = new mapboxgl.Popup({closeOnClick: false})
                      .setLngLat(currentFeature.geometry.coordinates)
                      .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                        '<h4>'+currentFeature.properties.address+'</h4>' + 
                        '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + '\')">Read More</a>')
                      .addTo(map);
                }

                
            }
            else
            {
               if(currentFeature.properties.getyourguide==1)
               {
                  var popup = new mapboxgl.Popup({closeOnClick: false})
                        .setLngLat(currentFeature.geometry.coordinates)
                        .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                          '<h4>'+currentFeature.properties.address+'</h4><span><a href="<?php echo site_url("attractionsFromGYG") ?>/'+latlongstring+'" target="_blank">Buy Tickets</a></span>')
                        .addTo(map);
               }
               else
               {
                  var popup = new mapboxgl.Popup({closeOnClick: false})
                        .setLngLat(currentFeature.geometry.coordinates)
                        .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                          '<h4>'+currentFeature.properties.address+'</h4>')
                        .addTo(map);
               }         
            }

            }

            function buildLocationList(data) {
              for (i = 0; i < data.features.length; i++) {
                var currentFeature = data.features[i];
                var prop = currentFeature.properties;

                if(currentFeature.isselected==1 || currentFeature.tempremoved==1)
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
                    linkadd.innerHTML = '<i class="fa fa-plus" aria-hidden="true" onclick="addMainAttraction(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';    
                }

                if(currentFeature.tempremoved==0)
                {
                    var linkdel = linkh3.appendChild(document.createElement('a'));
                    linkdel.href = 'javascript:void(0)';
                    linkdel.id = prop.attractionid;
                    linkdel.className = 'delete-tab';
                    linkdel.dataPosition = i;
                    linkdel.innerHTML = '<i class="fa fa-trash-o" aria-hidden="true" onclick="deleteMainAttraction(\'' + prop.attractionid + '\',\'' + prop.cityid + '\')"></i>';
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
           
         setTimeout(function(){  $.LoadingOverlay("hide",true); }, 5000);



  });
  
   function showAttractionDetails(cityid,attractionid)
  {
      if(cityid!='' && attractionid!='')
      {
          $.LoadingOverlay("show");
          $.ajax({
              type:'POST',
              url:'<?php echo site_url("getAttractionData") ?>',
              data:'cityid='+cityid+'&attractionid='+attractionid,
              success:function(data)
              {
                  $("#infmdl").modal('show');
                  var jsonresponse=$.parseJSON(data);
                  $("#showattractiontitle").html(jsonresponse.name);
                  $("#showattractiondetails").html(jsonresponse.details);
                  $.LoadingOverlay("hide",true);
              }
          })
      }
  }

  function funOpen(id)
  {
     $("li").removeClass('active-li');
     $.ajax({
          type:'POST',
          url:'<?php echo site_url("city-attractions-ajax") ?>',
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
    $(this).siblings('li').removeClass('active-li');
    //$(this).siblings('li').css('pointer-events','auto');
    $(this).addClass('active-li');
    //$(this).css('pointer-events','none');

  });

  
  
  
    </script>

    <script>

$(document).ready(function(){
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    var cityid="<?php echo md5($attractioncities[0]['id']); ?>";
    $('#listings').sortable({
            axis: 'y',
            items: "div.backgroundclr",
            update: function (event, ui) {
                  var data = $(this).sortable('serialize');
                  $.ajax({
                      data: data+'&cityid='+cityid,
                      type: 'POST',
                      url: '<?php echo site_url("saveOrder") ?>'
                  });
            }
       });
      $( "#listings" ).disableSelection();
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
        <p  id="showattractiondetails"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>