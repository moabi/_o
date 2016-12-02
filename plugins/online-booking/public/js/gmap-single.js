


/**
 * INIT VENDOR MAP
 */
function initSingleMap() {

    $map = jQuery('#map');
    var attr = $map.attr('data-lat');
    $map_exist = false;


    /**
     * get LAT & LNG
     */
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

    /**
     * CREATE MAP
     * ADD RED MARKER
     */
    /*
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




    }
*/
}


var placeSearch, autocomplete;



function initMap() {
    $map = $('#map');
    singleLat = parseFloat($map.attr('data-lat'));
    singleLng = parseFloat($map.attr('data-lng'));
    var markers = [];
    var userAddress = $map.attr('data-address');
    var myLatLng = {lat: singleLat, lng: singleLng};
    var coordinates;

    /**
     * AutoComplete
     */
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('address')),
        {types: ['geocode']});

    /**
     * DRAWING FUNCTIONS
     */

    function deletePolygon() {
        if (onlyooPolygon) {
            onlyooPolygon.setMap(null);
        }
        $('.js-polygon').val('');
        coordinates = '';
    }

    function initPolygon(){
        deletePolygon();
        deleteMarkers();
        onlyooPolygon.setMap(map);
        map.setCenter(myLatLng);
    }
    /**
     * @this {google.maps.Polygon}
     * @param event
     * @returns {Array}
     */
    function showArrays(event) {
        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        var vertices = this.getPath();

        polygonPaths = [];
        // Iterate over the vertices.
        for (var i =0; i < vertices.getLength(); i++) {
            var xy = vertices.getAt(i);
            tpm = [];
            tpm.push(xy.lat());
            tpm.push(xy.lng());
            polygonPaths.push(tpm);
        }
        $('.js-polygon').val(encodeURI(JSON.stringify(polygonPaths)));
    }

    /**
     *
     * @param geocoder
     * @param resultsMap
     */
    function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                var mapOptions = {
                    zoom: 10,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var latlng = new google.maps.LatLng(latitude, longitude);
                map.setCenter(latlng);

                var marker = new google.maps.Marker({
                    map: map,
                    position: latlng,
                    zoom:10
                });
                markers.push(marker);
                $('#address-lat').val(latitude);
                $('#address-long').val(longitude);
            } else {
                console.warn('Geocode was not successful for the following reason: ' + status);
                $('input#address').css('background-color','rgba(255, 0, 0, 0.14)');
            }
        });
    }

    /**
     *
     */
    function geoCode(){
        deletePolygon();
        clearMarkers();
        var gmapAdress = $('#gmap-geocoding').val();
        var geocoder = new google.maps.Geocoder();
        geocodeAddress(geocoder, map);
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        $('#address').val('');
        $('#address-lat').val('');
        $('#address-long').val('');
        clearMarkers();
        markers = [];
    }

    //INIT MAP
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: myLatLng
    });
    /*
    if(userAddress){
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Adresse de la prestation'
        });
        markers.push(marker);
    }
*/
    var polyCoords = [
        {lat: 43.5423, lng: 4.3464},
        {lat: 43.7472, lng: 4.1389},
        {lat: 43.5236, lng: 3.7756},
        {lat: 43.4758, lng: 4.3811},
        {lat: 43.5596, lng: 4.6106}
    ];

    onlyooPolygon = new google.maps.Polygon({
        paths: polyCoords,
        //bounds: bounds,
        editable: true,
        draggable: true,
        fillColor: '#e88708',
        fillOpacity: '0.2',
        strokeColor:'#e88708'
    });

    //DomListeners
    //google.maps.event.addDomListener(document.getElementById('delete-polygon'), 'click', deletePolygon);
    google.maps.event.addDomListener(document.getElementById('init-polygon'), 'click', initPolygon);
    google.maps.event.addDomListener(document.getElementById('gmap-geocoding-btn'), 'click', geoCode);
    google.maps.event.addDomListener(document.getElementById('js-map-rs'), 'click', refreshMap);
    // Add an event listener on the polygon.
    onlyooPolygon.addListener('mouseup', showArrays);

    function refreshMap() {
        setTimeout(function(){
            var center = map.getCenter();
            google.maps.event.trigger(map, 'resize');
            map.setCenter(center);
        },400);

    }

}


//INIT MAP BECAUSE IT'S HIDDEN

/*
jQuery('.js-show-gmap').click(function(){
    setTimeout(function(){
        //initMap();
        $('#map').width('99%');
    },300);

});
*/



