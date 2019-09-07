<div class="sidebar col-md-4 scrollbar-inner">
   <div id='listings' class='listings'></div>
  </div>
 <div id="map" class="map col-md-8"> </div>

 <div class="col-md-12 inner-links">
  <div class="text-left">
     <div class="col-md-4 attractions-btn">
     <a class="link-button modal-link" data-toggle="modal" id="singlemodel" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New Location</a>
    </div>
    <div class="col-md-4 attractions-btn">
     <a id="showallSingle" idattr="<?php echo $cityid; ?>" class="link-button modal-link" href="javascript:void(0);"><i class="fa fa-plus-circle" aria-hidden="true"></i>View All</a>
    </div>
    <div class="col-md-4 attractions-btn">
      <?php if($this->session->userdata('fuserid')!=''){ ?>
                <a href="<?php echo site_url('save-searched-itinerary').'/'.string_encode($filenm.'-'.$uid); ?>" class="link-button modal-link">Save<i class="fa fa-map-signs" aria-hidden="true"></i></a>
              <?php } else { ?>
                <a href="javascript:void(0);" id="showLogonForm" class="link-button modal-link">Save<i class="fa fa-map-signs" aria-hidden="true"></i></a>
              <?php } ?>
              </div>

  </div>
</div>


<script type="text/javascript">


  $(document).ready(function(){

           if (!('remove' in Element.prototype)) {
              Element.prototype.remove = function() {
                if (this.parentNode) {
                    this.parentNode.removeChild(this);
                }
              };
            }


            mapboxgl.accessToken = 'pk.eyJ1IjoiZWlqaW5hYWwiLCJhIjoiY2l0eWR3NGF4MDAzMDQ1b2FpZmlmdHQzdyJ9.zONIJ0N7SED6ayhXFSR37g';

            var map = new mapboxgl.Map({
              container: 'map',
              style: 'mapbox://styles/mapbox/streets-v8',
              center: [parseFloat(<?php echo $longitude ?>),parseFloat(<?php echo $latitude ?>)],
              zoom: 12,
              minZoom:12,
              pitch: 45,
              bearing: -17.6,

            });

            map.addControl(new mapboxgl.NavigationControl());

            var stringified='<?php echo $filestore; ?>';
            var filestore=JSON.parse(stringified);
            var stores = {
              "type": "FeatureCollection",
              "features":filestore
              };
            map.on('load', function (e) {
              map.addSource("places", {
                "type": "geojson",
                "data": stores
              });
              buildLocationList(stores);

              monthArrayr=[];
              stores.features.forEach(function(marker, i){
                if(marker.isselected==1 && marker.properties.isplace==1)
                 {

                    monthArrayr.push([parseFloat(marker.geometry.coordinates[0]),parseFloat(marker.geometry.coordinates[1])]);
                 }

              });


              map.addLayer({
                'id': '3d-buildings',
                'source': 'composite',
                'source-layer': 'building',
                'filter': ['==', 'extrude', 'true'],
                'type': 'fill-extrusion',
                'minzoom': 15,
                'paint': {
                    'fill-extrusion-color': '#aaa',
                    'fill-extrusion-height': {
                        'type': 'identity',
                        'property': 'height'
                    },
                    'fill-extrusion-base': {
                        'type': 'identity',
                        'property': 'min_height'
                    },
                    'fill-extrusion-opacity': .6
                }
          });

      });


            stores.features.forEach(function(marker, i) {

              if(marker.isselected==1)
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
                  var att = document.createAttribute("data-toggle");
                   att.value = "tooltip";
                   el.setAttributeNode(att);
                   el.title =marker.properties.name;


                  el.style.left='-28px';
                  el.style.top='-46px';
                  new mapboxgl.Marker(el)
                      .setLngLat(marker.geometry.coordinates)
                      .addTo(map);

                  el.addEventListener('click', function(e){
                      flyToStore(marker);

                      createPopUp(marker);

                      var activeItem = document.getElementsByClassName('active');

                      e.stopPropagation();
                      if (activeItem[0]) {
                         activeItem[0].classList.remove('active');
                      }

                      var listing = document.getElementById('listing-' + i);
                      $('#listing-' + i+' h3').addClass('active');
                  });
                }

            });


           function flyToStore(currentFeature)
             {

               $("h3").removeClass('active');
               map.flyTo({
                  center: currentFeature.geometry.coordinates,
                  zoom: 17
                });

               if(currentFeature.properties.isplace==1)
               {
                   var scrollid = $('a:contains("'+currentFeature.properties.name+'")').parent().parent().attr('id');
                   if(typeof scrollid !== "undefined")
                   {
                      $('.scrollbar-inner').animate({
                        scrollTop:$("#"+scrollid).position().top - $('#'+$("#listings div").eq(0).attr('id')).position().top
                       }, '1000');
                   }
                }
            }

            function createPopUp(currentFeature)
            {

              var popUps = document.getElementsByClassName('mapboxgl-popup');
              if (popUps[0]) popUps[0].remove();
              var latlong=currentFeature.geometry.coordinates.toString().replace(',','/');
              var latlongstring=currentFeature.properties.cityid+'/'+latlong;
                if(currentFeature.properties.getyourguide==1)
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

                    if(currentFeature.properties.isplace==1)
                    {
                      var popup = new mapboxgl.Popup({closeOnClick: false})
                        .setLngLat(currentFeature.geometry.coordinates)
                        .setHTML('<a href="javascript:void(0);" class="popupclose"><i class="fa fa-remove"></i></a>'+'<h3>'+currentFeature.properties.name+'</h3>' +
                          '<span class="knownfor">Known For</span><h4>'+currentFeature.properties.known_tags+'</h4>' +
                          '<a href="javascript:void(0);" onClick="showAttractionDetails(\'' + currentFeature.properties.cityid + "\',\'" + currentFeature.properties.attractionid + "\',\'" + currentFeature.properties.category + '\')">Read More</a>')
                        .addTo(map);
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
            $("h3").removeClass('active');
            var popUps = document.getElementsByClassName('mapboxgl-popup');
             if (popUps[0]) popUps[0].remove();
               map.flyTo({
                  center: stores.features[0].devgeometry.devcoordinates,
                  zoom: 12,
                });

               $('.scrollbar-inner').animate({
                        scrollTop:$('#'+$("#listings div").eq(0).attr('id')).position().top
                       }, '1000');

            });



             function buildLocationList(data) {
              for (i = 0; i < data.features.length; i++) {
                var currentFeature = data.features[i];
                var prop = currentFeature.properties;

                 if(currentFeature.isselected==1  && currentFeature.properties.isplace==1)
                {

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
                 if(currentFeature.properties.known_tags==0)
                {
                  link.title = 'My Activity';
                }
                else
                {
                  link.title = 'Known For : '+currentFeature.properties.known_tags;
                }

                link.dataPosition = i;

                var att = document.createAttribute("data-toggle");
                att.value = "tooltip";
                link.setAttributeNode(att);


                 if(currentFeature.properties.tag_star==1 || currentFeature.properties.tag_star==2)
                {

                  if(currentFeature.properties.ispaid==1)
                  {
                    link.innerHTML = '<span class="paidattraction"><i class="fa fa-usd" aria-hidden="true"></i></span><span class="placenm">'+prop.name+'</span><span class="starattraction"><i class="fa fa-star" aria-hidden="true"></i></span>';
                  }
                  else
                  {
                    link.innerHTML = '<span class="placenm">'+prop.name+'</span><span class="starattraction"><i class="fa fa-star" aria-hidden="true"></i></span>';
                  }
                }
                else
                {
                  if(currentFeature.properties.ispaid==1)
                  {
                    link.innerHTML = '<span class="paidattraction"><i class="fa fa-usd" aria-hidden="true"></i></span><span class="placenm">'+prop.name+'</span>';
                  }
                  else
                  {
                    link.innerHTML = '<span class="placenm">'+prop.name+'</span>';
                  }
                }

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
                      var clickedListing = data.features[this.dataPosition];

                     flyToStore(clickedListing);

                     createPopUp(clickedListing);

                      var activeItem = document.getElementsByClassName('active');

                        if (activeItem[0]) {
                           activeItem[0].classList.remove('active');
                        }
                       this.parentNode.classList.add('active');

                  });

                }
              }
            }


         var travelguide="<?php if(isset($basic['travelguide']) && $basic['travelguide']!=''){ echo site_url('userfiles/travelguide').'/'.$basic['travelguide'];  }else{ echo '1'; } ?>";
         if(travelguide!=1)
         {
          $(".search-travelguide").show();
          $("#travelguidea").show();
          $("#travelguidea").html("<i class='fa fa-file-pdf-o' aria-hidden='true'></i>Download Travel Guide");
          $("#travelguidea").attr("href",travelguide);
         }
         else
         {
          if($(".checkadd")[0])
          {}
          else
          {
            $(".search-travelguide").hide();
          }
          $("#travelguidea").hide();
          $("#travelguidea").html("");
          $("#travelguidea").attr("href","");
         }


  });


   $(document).ready(function(){
    $('.scrollbar-inner').scrollbar();
 });

  $("#showallSingle").click(function(){

     $.ajax({
        url: '<?php echo site_url("getAllAttractionsOfSingleCity") ?>',
        type: 'POST',
        data: 'id='+$(this).attr('idattr')+'&uniqueid='+$('#uid').val(),
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
              },
              complete: function(){
             },
              success:function(data)
              {
                  $("#infmdl").modal('show');
                  $("#showattractiontitle").html('');
                  $("#showattractiondetails").html('');
                  $("#showattractionaddress").html('');
                  $("#showattractioncontact").html('');
                  $("#showattractionwebsite").html('');
                  $("#showattractiontransport").html('');
                  $("#showattractiontiming").html('');
                  $("#showattractiontimereq").html('');
                  $("#showattractionwaittime").html('');
                  $("#showattractionknown").html('');
                  $("#showattractiontimefees").html('');

                   var jsonresponse=JSON.parse(data);
                   var name='N/A';var attraction_address='N/A';var attraction_contact='N/A';var attraction_website='N/A';var attraction_public_transport='N/A';var fees='N/A';
                   var attraction_timing='N/A';var attraction_time_required='N/A';var attraction_wait_time='N/A';var tag_name='N/A';var details='N/A';
                    $("#showattractionwebsite").html(attraction_website);
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
                      $("#showattractionwebsite").html('<a href="" target="_blank">'+attraction_website+'</a>');
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
                   if(jsonresponse.attraction_admissionfee!='')
                  {
                     $(".hideattr-group").show();
                     $(".displayattr-group").removeClass('col-md-6').addClass('col-md-3');
                     fees=jsonresponse.attraction_admissionfee;
                  }
                  else
                  {
                     $(".hideattr-group").hide();
                     $(".displayattr-group").removeClass('col-md-3').addClass('col-md-6');
                  }
                  $("#showattractiontitle").html(name);
                  $("#showattractiondetails").html(details);
                  $("#showattractionaddress").html(attraction_address);
                  $("#showattractioncontact").html(attraction_contact);

                  $("#showattractiontransport").html(attraction_public_transport);
                  $("#showattractiontiming").html(attraction_timing);
                  $("#showattractiontimereq").html(attraction_time_required);
                  $("#showattractionwaittime").html(attraction_wait_time);
                  $("#showattractionknown").html(tag_name);
                  $("#showattractiontimefees").html(fees);

              }
          });
      }
  }


  function funOpen(id)
  {
     $.LoadingOverlay("show");
     $("li").removeClass('active-li');
      $("ul#sortable li").click(function(){
          if($(this).attr('id')!='lastdragli')
          {
            $(this).siblings('li').removeClass('active-li');
            $(this).addClass('active-li');
          }
     });
     $.ajax({

          type:'POST',
          url:'<?php echo site_url("single-city-attractions-ajax") ?>',
          data:'id='+id+'&uniqueid='+$('#uid').val(),
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



$("#showMy").click(function(){
  $.ajax({
        url: '<?php echo site_url("city-attractions-ajax") ?>',
        type: 'POST',
        data: 'id='+$(this).attr('idattr')+'&uniqueid='+$('#uid').val(),
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

$("#singlemodel").click(function(){
   $("#addNewActivitySingleForm")[0].reset();
   $("#isall").val(0);
   $('#mapModal').modal({
      backdrop: 'static',
      keyboard: false
    })
});

    </script>
<script>

  $("#showLogonForm").click(function(){
    $("#myModal").modal('show');

});

$(document).ready(function(){

  $( "#sortable" ).sortable({
      appendTo: "parent",
      axis: false,
      containment: 'document',
      cursor: 'move',
      cursorAt: false,
      dropOnEmpty: true,
      forceHelperSize: true,
      forcePlaceholderSize: true,
      iframeFix: true,
      items: 'div.dragableBlock',
      greedy: true,
      grid: false,
      helper: "clone",
      opacity: 0.45,
      placeholder: 'ui-block-placeholder',
      revert: false,
      scroll: true,
      scrollSensitivity: 20,
      scrollSpeed: 20,
      scope: "default",
      tolerance: "pointer",
      zIndex: 9999,
      start: function (event, ui) { ui.item.bind("click.prevent",
              function(event) { event.preventDefault(); });},
      stop: function (event, ui) { ui.item.unbind("click.prevent"); },
      items: "li.drag-x",
      update: function (event, ui) {
            var data = $(this).sortable('serialize');
            $.ajax({
                data: data+'&uniqueid='+$('#uid').val(),
                type: 'POST',
                url: '<?php echo site_url("saveSearchCityXOrder") ?>',
                beforeSend: function(){
                   $.LoadingOverlay("show");
                },
                complete: function(){
                    setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
                },
                success:function(data)
                {
                    res=IsJsonString(data);
                    if(res)
                    {
                       var jsn = JSON.parse(data);
                       if(jsn.login==0)
                       {
                           $("#signintabli").addClass('active');
                           $("#signuptabli").removeClass('active');
                           $("#signup").removeClass('active in');
                           $("#signin").addClass('active in');
                           $('#myModal').modal({
                              backdrop: 'static',
                              keyboard: true
                           });
                       }
                       else if(jsn.tripdelete==1)
                       {
                         window.location="<?php echo site_url('trips') ?>";
                       }
                       return false;
                    }
                    $("#bindMainPage").html(data.body);
                }
            });
      }
   });
  $( "#sortable" ).disableSelection();

    var cityid="<?php echo $cityid; ?>";
    var url='<?php echo site_url("saveOrderSingle") ?>';

    $('#listings').sortable({
            axis: 'y',
            items: "div.backgroundclr",
            update: function (event, ui) {
                  var data = $(this).sortable('serialize');
                  $.ajax({
                      data: data+'&cityid='+cityid+'&uniqueid='+$('#uid').val(),
                      type: 'POST',
                      url: url,
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
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
