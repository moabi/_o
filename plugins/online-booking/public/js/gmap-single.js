

function initSingleMap() {
    lattitude = parseInt(jQuery('#map').attr('data-lat'));
    longitude = parseInt(jQuery('#map').attr('data-lng'));
    // Create the map.
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: {lat: lattitude, lng: longitude},
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });

    // Construct the circle for each value in citymap.
    var citymap = {
        activity: {
            center: {lat: lattitude, lng: longitude}
        }
    };
    // Note: We scale the area of the circle based on the population.
    for (var city in citymap) {
        // Add the circle for this city to the map.
        var cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: citymap[city].center,
            radius: 6000
        });
    }
}

