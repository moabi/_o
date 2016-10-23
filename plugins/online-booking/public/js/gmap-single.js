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
            center: {lat: singleLat, lng: singleLng}
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
    } else {
        console.log('no map');
    }

}

