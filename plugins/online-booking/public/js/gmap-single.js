/**
 *
 */
function initSingleMap() {

    $map = jQuery('#map');
    var attr = $map.attr('data-lat');
    $map_exist = false;

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

}



function initMap() {
    $map = jQuery('#map');
    singleLat = parseFloat($map.attr('data-lat'));
    singleLng = parseFloat($map.attr('data-lng'));

    var activities = {
        activity: {
            center: {lat: singleLat, lng: singleLng}
        }
    };

    var userAddress = $map.attr('data-address')
    var myLatLng = {lat: singleLat, lng: singleLng};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: myLatLng
    });
    if(userAddress){
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Adresse de la prestation'
        });
    }

}

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

            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var latlng = new google.maps.LatLng(latitude, longitude);
            map.setCenter(latlng);

            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                zoom:10
            });
            jQuery('#address-lat').val(latitude);
            jQuery('#address-long').val(longitude);
        } else {
            console.warn('Geocode was not successful for the following reason: ' + status);
            $('input#address').css('background-color','rgba(255, 0, 0, 0.14)');
        }
    });
}
jQuery('.js-show-gmap').click(function(){
    setTimeout(function(){
        initMap();
    },300);

});
jQuery('#gmap-geocoding-btn').click(function(e) {
    e.preventDefault();
    var gmapAdress = $('#gmap-geocoding').val();
    var geocoder = new google.maps.Geocoder();
    geocodeAddress(geocoder, map);

});


