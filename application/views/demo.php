<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Sortable - Display as grid</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.21.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.21.0/mapbox-gl.css' rel='stylesheet' />

  <style>

  #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
  #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }


 .sidebar {
  position:relative;
  width:33.3333%;
  height:100%;
  top:0;left:0;
  overflow:hidden;
  border-right:1px solid rgba(0,0,0,0.25);
}
.pad2 {
  padding:20px;
}

.map {
  position:absolute;
  left:33.3333%;
  width:66.6666%;
  top:0;
  bottom:0;
}

h1 {
  font-size:22px;
  margin:0;
  font-weight:400;
  line-height: 20px;
  padding: 20px 2px;
}

a {
  color:#404040;
  text-decoration:none;
}

a:hover { 
  color:#101010; 
}

.heading {
  background:#fff;
  border-bottom:1px solid #eee;
  min-height:60px;
  line-height:60px;
  padding:0 10px;
}

.listings {
  height:100%;
  overflow:auto;
  padding-bottom:60px;
}

.listings .item {
  display:block;
  border-bottom:1px solid #eee;
  padding:10px;
  text-decoration:none;
}

.listings .item:last-child { border-bottom:none; }
.listings .item .title {
  display:block;
  color:#00853e;
  font-weight:700;
}

.listings .item .title small { font-weight:400; }
.listings .item.active .title,
.listings .item .title:hover { color:#8cc63f; }
.listings .item.active {
  background-color:#f8f8f8;
}
::-webkit-scrollbar {
  width:3px;
  height:3px;
  border-left:0;
  background:rgba(0,0,0,0.1);
}
::-webkit-scrollbar-track {
  background:none;
}
::-webkit-scrollbar-thumb {
  background:#00853e;
  border-radius:0;
}

.marker {
  border: none;
  cursor: pointer;
  height: 56px;
  width: 56px;
  background-image: url(http://reesort.com/taxidioreesort/assets/images/marker.png);
  background-color: rgba(0, 0, 0, 0);
  transform: translate(28px, 56px, 0);
}

.clearfix { display:block; }
.clearfix:after {
  content:'.';
  display:block;
  height:0;
  clear:both;
  visibility:hidden;
}

/* Marker tweaks */


.mapboxgl-popup {
  padding-bottom: 50px;
}

.mapboxgl-popup-close-button {
  display:none;
}
.mapboxgl-popup-content {
  font:400 15px/22px 'Source Sans Pro', 'Helvetica Neue', Sans-serif;
  padding:0;
  width:180px;
}
.mapboxgl-popup-content-wrapper {
  padding:1%;
}
.mapboxgl-popup-content h3 {
  background:#91c949;
  color:#fff;
  margin:0;
  display:block;
  padding:10px;
  border-radius:3px 3px 0 0;
  font-weight:700;
  margin-top:-15px;
}

.mapboxgl-popup-content h4 {
  margin:0;
  display:block;
  padding: 10px 10px 10px 10px;
  font-weight:400;
  margin: 0;
}

.mapboxgl-popup-content div {
  padding:10px;
}

.mapboxgl-container .leaflet-marker-icon { 
  cursor:pointer; 
}

.mapboxgl-popup-anchor-top > .mapboxgl-popup-content {
  margin-top: 15px;
}

.mapboxgl-popup-anchor-top > .mapboxgl-popup-tip {
  border-bottom-color: #91c949;
}     
    
  </style>
</head>
<body>
<div class="container-fluid">
  
    <div class="row">
          
          <ul id="sortable">
            <li class="ui-state-default"><a href="javascript:void(0);" onclick="funOpen(1)">1</a></li>
            <li class="ui-state-default"><a href="javascript:void(0);" onclick="funOpen(2)">2</a></li>
            <li class="ui-state-default"><a href="javascript:void(0);" onclick="funOpen(3)">3</a></li>
            <li class="ui-state-default"><a href="javascript:void(0);" onclick="funOpen(4)">4</a></li>
            <li class="ui-state-default"><a href="javascript:void(0);" onclick="funOpen(5)">5</a></li>
        </ul>

    </div>

</div>



<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>


<div class="container-fluid" id="bindTab">
    <div class="row">

         <div class='sidebar'>
              <div class='heading'>
                <h1>Our Attractions</h1>
              </div>
             <div id='listings' class='listings'></div>
            </div>
           <div id='map' class='map'> </div>
     </div>
</div>


 <script>
  // This will let you use the .remove() function later on
  if (!('remove' in Element.prototype)) {
    Element.prototype.remove = function() {
      if (this.parentNode) {
          this.parentNode.removeChild(this);
      }
    };
  }

  mapboxgl.accessToken = 'pk.eyJ1IjoiZXhhbXBsZXMiLCJhIjoiY2lqbmpqazdlMDBsdnRva284cWd3bm11byJ9.V6Hg2oYJwMAxeoR9GEzkAA';

  // This adds the map
  var map = new mapboxgl.Map({
    // container id specified in the HTML
    container: 'map', 
    // style URL
    style: 'mapbox://styles/mapbox/light-v9', 
    // initial position in [long, lat] format
    center: [78.96288000000004,20.593684], 
    // initial zoom
    zoom: 4
  });

  var stores = {
    "type": "FeatureCollection",
    "features": [
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
           73.18121870000004,
           22.3071588
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 234-7336",
          "phone": "2022347336",
          "address": "1471 P St NW",
          "city": "Vadodara",
          "country": "India",
          "crossStreet": "at 15th St NW",
          "postalCode": "20005",
          "state": "D.C."
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            72.87765590000004,
            19.0759837
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 507-8357",
          "phone": "2025078357",
          "address": "2221 I St NW",
          "city": "Mumbai",
          "country": "India",
          "crossStreet": "at 22nd St NW",
          "postalCode": "20037",
          "state": "D.C."
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            77.59456269999998,
            12.9715987
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 387-9338",
          "phone": "2023879338",
          "address": "1512 Connecticut Ave NW",
          "city": "Bengaluru",
          "country": "United States",
          "crossStreet": "at Dupont Circle",
          "postalCode": "20036",
          "state": "D.C."
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            88.36389499999996,
            22.572646
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 337-9338",
          "phone": "2023379338",
          "address": "3333 M St NW",
          "city": "Kolkata",
          "country": "United States",
          "crossStreet": "at 34th St NW",
          "postalCode": "20007",
          "state": "D.C."
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            78.486671,
            17.385044
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 547-9338",
          "phone": "2025479338",
          "address": "221 Pennsylvania Ave SE",
          "city": "Hyderabad",
          "country": "United States",
          "crossStreet": "btwn 2nd & 3rd Sts. SE",
          "postalCode": "20003",
          "state": "D.C."
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            76.7794179,
            30.7333148
          ]
        },
        "properties": {
          "address": "8204 Baltimore Ave",
          "city": "Chandigarh",
          "country": "United States",
          "postalCode": "20740",
          "state": "MD"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
           73.85674369999992,
            18.5204303
          ]
        },
        "properties": {
          "phoneFormatted": "(301) 654-7336",
          "phone": "3016547336",
          "address": "4831 Bethesda Ave",
          "cc": "US",
          "city": "Pune",
          "country": "United States",
          "postalCode": "20814",
          "state": "MD"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            76.27108329999999,
            10.8505159
          ]
        },
        "properties": {
          "phoneFormatted": "(571) 203-0082",
          "phone": "5712030082",
          "address": "11935 Democracy Dr",
          "city": "Kerala",
          "country": "United States",
          "crossStreet": "btw Explorer & Library",
          "postalCode": "20190",
          "state": "VA"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            74.12399600000003,
            15.2993265
          ]
        },
        "properties": {
          "phoneFormatted": "(703) 522-2016",
          "phone": "7035222016",
          "address": "4075 Wilson Blvd",
          "city": "Goa",
          "country": "United States",
          "crossStreet": "at N Randolph St.",
          "postalCode": "22203",
          "state": "VA"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            77.18871450000006,
            32.2396325
          ]
        },
        "properties": {
          "phoneFormatted": "(610) 642-9400",
          "phone": "6106429400",
          "address": "68 Coulter Ave",
          "city": "Manali",
          "country": "United States",
          "postalCode": "19003",
          "state": "PA"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            85.30956200000003,
            23.3440997
          ]
        },
        "properties": {
          "phoneFormatted": "(215) 386-1365",
          "phone": "2153861365",
          "address": "3925 Walnut St",
          "city": "Ranchi",
          "country": "United States",
          "postalCode": "19104",
          "state": "PA"
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            75.85772580000003,
            22.7195687
          ]
        },
        "properties": {
          "phoneFormatted": "(202) 331-3355",
          "phone": "2023313355",
          "address": "1901 L St. NW",
          "city": "Indore",
          "country": "United States",
          "crossStreet": "at 19th St",
          "postalCode": "20036",
          "state": "D.C."
        }
      }
     ]
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
    // Create an img element for the marker
    var el = document.createElement('div');
    el.id = "marker-" + i;
    el.className = 'marker';
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
        listing.classList.add('active');

    });
  });

  
  function flyToStore(currentFeature) {
    map.flyTo({
        center: currentFeature.geometry.coordinates,
        zoom: 12
      }); 
  }

  function createPopUp(currentFeature) {
    //alert(currentFeature.properties.address);
    var popUps = document.getElementsByClassName('mapboxgl-popup');
    if (popUps[0]) popUps[0].remove();


    var popup = new mapboxgl.Popup({closeOnClick: false})
          .setLngLat(currentFeature.geometry.coordinates)
          .setHTML('<h3>'+currentFeature.properties.city+'</h3>' + 
            '<h4>' + currentFeature.properties.address + '</h4>')
          .addTo(map);
  }
 

  function buildLocationList(data) {
    for (i = 0; i < data.features.length; i++) {
      var currentFeature = data.features[i];
      var prop = currentFeature.properties;
      
      var listings = document.getElementById('listings');
      var listing = listings.appendChild(document.createElement('div'));
      listing.className = 'item group';
      listing.id = "listing-" + i;
      
      var linkh3 = listing.appendChild(document.createElement('h3'));

      

      var link = linkh3.appendChild(document.createElement('a'));
      link.href = '#';
      link.className = 'title';
      link.dataPosition = i;
      link.innerHTML = prop.address;  

         
      
      var details = listing.appendChild(document.createElement('div'));
      details.innerHTML = prop.city;
      if (prop.phone) {
        details.innerHTML += ' &middot; ' + prop.phoneFormatted;
      }



      link.addEventListener('click', function(e){
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

  function funOpen(id)
  {
     $.ajax({

          type:'POST',
          url:'<?php echo site_url("Home/getMap") ?>',
          data:'id='+id,
          success:function(data)
          {
              $("#bindTab").html(data.body);
          }
     });
  }

  
    </script>

 
</body>
</html>


