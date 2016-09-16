

function initSingleMap() {
    singleLat = parseFloat(jQuery('#map').attr('data-lat'));
    singleLng = parseFloat(jQuery('#map').attr('data-lng'));
    console.log(singleLng);
    // Create the map.
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: {lat: singleLat, lng: singleLng}
        //mapTypeId: google.maps.MapTypeId.TERRAIN
    });

    // Construct the circle for each value in citymap.
    var citymap = {
        activity: {
            center: {lat: singleLat, lng: singleLng}
        }
    };
    // Note: We scale the area of the circle based on the population.
    for (var city in citymap) {
        // Add the circle for this city to the map.
        var cityCircle = new google.maps.Circle({
            strokeColor: '#e88708',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#e88708',
            fillOpacity: 0.35,
            map: map,
            center: citymap[city].center,
            radius: 6000
        });
    }
}

