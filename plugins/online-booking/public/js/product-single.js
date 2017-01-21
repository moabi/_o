/**
 * INIT SINGLE PRODUCT MAP
 */
function initSingleMap() {

    $map = jQuery('#map');
    var attr = $map.attr('data-lat');
    $map_exist = false;
    singleLat = parseFloat($map.attr('data-lat'));
    singleLng = parseFloat($map.attr('data-lng'));
    singleLat = parseFloat($map.attr('data-lat'));
    polygon = $('#polygon').val();
    isMapMarker = $('.map-marker').length;


    /**
     * get LAT & LNG
     */
    if(isMapMarker){
        if (typeof attr !== typeof undefined && attr !== false) {
            $map_exist = 1;
            singleLat = parseFloat($map.attr('data-lat'));
            singleLng = parseFloat($map.attr('data-lng'));

            var activities = {
                activity: {
                    center: {lat: singleLat, lng: singleLng}
                }
            };
        } else if(typeof $activities !== 'undefined') {
            $map_exist = 1;
            singleLat = parseFloat($activities[0].lat);
            singleLng = parseFloat($activities[0].lng);
            activities = $activities
        }
    } else {
        $map_exist = 1;
        singleLat = parseFloat($map.attr('data-lat'));
        singleLng = parseFloat($map.attr('data-lng'));

        polyCoordsUri = decodeURI(polygon);
        polyCoords = JSON.parse(polyCoordsUri);

        for(var i=0; i<polyCoords.length;i++){
            //loop through polyCoordsStrArray[i]
            tmp = polyCoords[i];
            polyCoords[i] = toObject(polyCoords[i]);
            tmpCoord = polyCoords[i];

            for (var key in tmpCoord) {
                if(parseInt(key) === 0) {
                    tmpCoord.lat = tmpCoord[key];
                } else {
                    tmpCoord.lng = tmpCoord[key];
                }
                delete tmpCoord[key];
            }

        }
    }


    /**
     * CREATE MAP
     * ADD RED MARKER
     */
    if($map_exist){
        //console.log(singleLng);
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {lat: singleLat, lng: singleLng},
            scrollwheel: false
            //mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        // Construct the circle for each value in citymap.

        // Note: We scale the area of the circle based on the population.
        for (var activity in activities) {
            // Add the circle for this city to the map.
            var cityCircle = new google.maps.Circle({
                strokeColor: '#e88708',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#e88708',
                fillOpacity: 0.35,
                map: map,
                center: activities[activity].center,
                radius: 3000
            });
        }

        //IF POLYGON INIT POLYGON
        if(typeof polyCoords !== 'undefined'){
            onlyooPolygon = new google.maps.Polygon({
                paths: polyCoords,
                //bounds: bounds,
                editable: false,
                draggable: false,
                fillColor: '#e88708',
                fillOpacity: '0.2',
                strokeColor:'#e88708'
            });
            onlyooPolygon.setMap(map);
            if (!google.maps.Polygon.prototype.getBounds) {
                google.maps.Polygon.prototype.getBounds = function () {
                    var bounds = new google.maps.LatLngBounds();
                    this.getPath().forEach(function (element, index) { bounds.extend(element); });
                    return bounds;
                }
            }

            map.fitBounds(onlyooPolygon.getBounds());
        }

    }


    google.maps.event.addDomListener(document.getElementById('js-map-rs'), 'click', refreshMap);
    function refreshMap() {
        setTimeout(function(){
            var center = map.getCenter();
            google.maps.event.trigger(map, 'resize');
            map.setCenter(center);
        },400);

    }


    /**
     * convert an array to an obj
     * @param arr
     * @returns {{}}
     */
    function toObject(arr) {
        var rv = {};
        for (var i = 0; i < arr.length; ++i)
            rv[i] = arr[i];
        return rv;
    }
}





