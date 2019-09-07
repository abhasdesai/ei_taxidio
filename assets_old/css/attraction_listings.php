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
              <a class="link-button modal-link" data-toggle="modal" data-target="#demo" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Activity</a>
              <a class="link-button modal-link" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i> Show All</a>
            </p>
          </div>
     
</div>

</div>

<div class="container">
  <div class="row button-collections">
    <div class="col-md-12">
      <ul class="button-ul">
        <li><a href="#" class="link-button">Attractions<i class="fa fa-map-signs" aria-hidden="true"></i></a></li>
        <li><a href="#" class="link-button">Stay with a local<i class="fa fa-heart" aria-hidden="true"></i></a></li>
        <li><a href="#" class="link-button">Proceed to book hotels<i class="fa fa-hand-o-right" aria-hidden="true"></i></a></li>
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
              style: 'mapbox://styles/mapbox/streets-v9', 
              // initial position in [long, lat] format
              center: [<?php echo $longitude ?>,<?php echo $latitude ?>], 
              // initial zoom
              zoom: 10,

            });

            alert('<?php echo $waypoints; ?>');
            /*
            var directions = new mapboxgl.Directions({
                unit: 'metric',
                profile: 'driving'
            }); 

            directions.setOrigin([73.18121870000004,22.3071588]);
            directions.addWaypoint(0, [73.18121870000004, 22.3071588]);
            directions.addWaypoint(1, [72.87765590000004, 19.0759837]);
            directions.addWaypoint(2, [74.12399600000003, 15.2993265]);
            directions.setDestination([74.12399600000003, 15.2993265]);
            map.addControl(directions);
            */
            var str='<?php echo $filestore; ?>';

            var filestore=JSON.parse(str);
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
             
            });


            // This is where your interactions with the symbol layer used to be
            // Now you have interactions with DOM markers instead
            stores.features.forEach(function(marker, i) {
              
              //getyourguide
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


            if(currentFeature.properties.address.length!='')
            { 
               var popup = new mapboxgl.Popup({closeOnClick: false})
                      .setLngLat(currentFeature.geometry.coordinates)
                      .setHTML('<h3>'+currentFeature.properties.name+'</h3>' + 
                        '<h4>'+currentFeature.properties.address+'</h4>' + 
                        '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + '\')">Read More</a>')
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

            function buildLocationList(data) {
              for (i = 0; i < data.features.length; i++) {
                var currentFeature = data.features[i];
                var prop = currentFeature.properties;
                //alert(prop.);
                var listings = document.getElementById('listings');
                var listing = listings.appendChild(document.createElement('div'));
                if(prop.getyourguide==1)
                {
                   listing.className = 'item group divgyg';
                }
                else
                {
                   listing.className = 'item group divtax';
                }
                listing.id = "listing-" + i;
                
                var linkh3 = listing.appendChild(document.createElement('h3'));

                

                var link = linkh3.appendChild(document.createElement('a'));
                link.href = 'javascript:void(0)';
                link.className = 'title';
                link.dataPosition = i;
                link.innerHTML = prop.name;  
                        
                var linkdel = linkh3.appendChild(document.createElement('a'));
                linkdel.href = 'javascript:void(0)';
                linkdel.className = 'delete-tab';
                linkdel.dataPosition = i;
                linkdel.innerHTML = '<i class="fa fa-trash-o" aria-hidden="true"></i>';  

                 

                /*
                var details = listing.appendChild(document.createElement('div'));
                details.innerHTML = prop.city;
                if (prop.phone) {
                  details.innerHTML += ' &middot; ' + prop.phoneFormatted;
                }
          */


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
           
         setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);



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
    $(this).addClass('active-li');

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