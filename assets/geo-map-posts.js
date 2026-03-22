/**
 * WP Tutorials : Google Maps
 *
 * https://wp-tutorials.tech/add-functionality/wordpress-google-maps-tutorial/
 */
if (typeof wptgmapData !== 'undefined') {
  // Uncomment this to confirm that the script has laoded and we've
  // picked up wptgmapData from wp_localize_script()
  console.log('WPTGMAP Init');
  // console.log(wptgmapData);

  /**
   * This function gets called after the Maps JavaScript has loaded and it's
   * ready to be used.
   */
  function wptgmapInitMaps() {
    // Uncomment this to confirm that we've loaded the code from Google.
    console.log('Maps API loaded. Starting our callback : wptgmapInitMaps()');

    // Select all map elements using the CSS Selector we've passed from
    // the backend.
    document.querySelectorAll(wptgmapData.mapSelector).forEach(function (container) {
      // Extract this map's configuration from the data-map="..." propertyl.
      const mapDef = JSON.parse(container.dataset.map);

      // Create the Google Map instance.
      const gmap = new google.maps.Map(container, mapDef.map);

      if (Array.isArray(mapDef.markers) && mapDef.markers.length > 0) {
        mapDef.markers.forEach(function (markerDef) {
          // ...
          if (markerDef.lat !== 0.0 && markerDef.lon !== 0.0) {
            let thisLatlng = new google.maps.LatLng(markerDef.lat, markerDef.lon);
            let marker = new google.maps.Marker({
              position: thisLatlng,
              title: 'Hello World!',
            });
            marker.setMap(gmap);

            // If we've set the info parameter in the shortcode...
            if (mapDef.showInfoWindows) {
              if (!infowindow) {
                infowindow = new google.maps.InfoWindow();
              }

              // Create the HTML snippet for the info window.
              // <div class="post-excerpt">${markerDef.excerpt}</div>
              //               let content = `
              // <div class="geo-post-map-marker">
              //    <a href="${markerDef.url}" class="post-thumb-link"><img src="${markerDef.imageUrls.thumb}" /></a>
              //    <div class="post-info">
              //       <div class="post-title"><a href="${markerDef.url}">${markerDef.title}</a></div>
              //    </div>
              // </div>
              // `;
              let content = `
<div class="geo-post-map-marker">
   <div class="post-info">
      <div class="post-title"><a href="${markerDef.url}">${markerDef.title}</a></div>
      <div class="post-excerpt">${markerDef.excerpt}</div>
   </div>
</div>
`;
              // Show the info window when the marker is clicked.
              google.maps.event.addListener(
                marker,
                'click',
                (function (marker, content, infowindow) {
                  return function () {
                    infowindow.setContent(content);
                    infowindow.open(gmap, marker);
                  };
                })(marker, content, infowindow),
              );
            }
          }
        });
      }

      // let myLatlng = new google.maps.LatLng(-25.363882, 131.044922);
      // let myMarker = new google.maps.Marker({
      //   position: myLatlng,
      //   title: 'Hello World!',
      // });
      // myMarker.setMap(gmap);

      // Loop through any markers we've defined.
      if (false && Array.isArray(mapDef.markers) && mapDef.markers.length > 0) {
        const service = new google.maps.places.PlacesService(gmap);
        var infowindow = null;

        mapDef.markers.forEach(function (markerDef) {
          const request = {
            placeId: markerDef.placeId,
          };

          service.getDetails(request, function (result, status) {
            // Dump the result to the JS console, so you can see what
            // comes back from the PlaceId call.
            // console.log(result);

            if (status != google.maps.places.PlacesServiceStatus.OK) {
              // Error querying the PlaceId.
              alert(status);
            } else {
              // Create the marker.
              var marker = new google.maps.Marker({
                map: gmap,
                place: {
                  placeId: result.place_id,
                  location: result.geometry.location,
                },
              });

              // If we've set the info parameter in the shortcode...
              if (mapDef.showInfoWindows) {
                if (!infowindow) {
                  infowindow = new google.maps.InfoWindow();
                }

                console.log(`INFO WINDOW: ${result.title}`);

                // Create the HTML snippet for the info window.
                //                 content = `
                // <div style="width:10em;height:3em;margin-bottom:0.5em;margin-left:auto;margin-right:auto;">
                //    <img src="${result.icon}" style="width:100%;height:100%;object-fit:contain;" />
                // </div>
                // <div style="text-align:center">
                //    <strong>${result.name}</strong><br />
                //    <span>${result.place_id}</span>
                // </div>
                // `;
                content = `
<div style="text-align:center">
   <div class="place-name">${result.title}</div>
   <div class="pace-excerpt">${result.excerpt}</div>
</div>
`;

                // Show the info window when the marker is clicked.
                google.maps.event.addListener(
                  marker,
                  'click',
                  (function (marker, content, infowindow) {
                    return function () {
                      infowindow.setContent(content);
                      infowindow.open(gmap, marker);
                    };
                  })(marker, content, infowindow),
                );
              }
            }
          });
        });
      }
    });
  }
}
