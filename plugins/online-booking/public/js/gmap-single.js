


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


var rectangle;
var map;
var infoWindow;

function initMap() {
    $map = jQuery('#map');
    singleLat = parseFloat($map.attr('data-lat'));
    singleLng = parseFloat($map.attr('data-lng'));

    var all_overlays = [];
    var selectedShape;
    var drawingManager;
    var coordinates;
    var rectangle;
    var infoWindow;


    /**
     * DRAWING FUNCTIONS
     */
    function clearSelection() {
        if (selectedShape) {
            selectedShape.setEditable(false);
            selectedShape = null;
        }
        coordinates = '';
    }

    function setSelection(shape) {
        clearSelection();
        selectedShape = shape;
        shape.setEditable(true);

    }

    function deleteSelectedShape() {
        if (selectedShape) {
            selectedShape.setMap(null);
        }
        coordinates = '';
    }

    function deleteAllShape() {
        for (var i = 0; i < all_overlays.length; i++) {
            all_overlays[i].overlay.setMap(null);
        }
        all_overlays = [];
        coordinates = '';
    }


    //DEFINE SINGLE ACTIVITY
    var activities = {
        activity: {
            center: {lat: singleLat, lng: singleLng}
        }
    };

    var userAddress = $map.attr('data-address')
    var myLatLng = {lat: singleLat, lng: singleLng};
    //INIT MAP
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


    //DRAWING FRONT END FN
    /*
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.MARKER,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.RECTANGLE]
        },
        rectangleOptions: {
            editable: false,
            draggable: false,
            fillColor: '#e88708',
            fillOpacity: '0.2',
            strokeColor:'#e88708'
        }
    });
    drawingManager.setMap(map);

    //One the shape is drawn
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
        all_overlays.push(e);
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function () {
                setSelection(newShape);
            });
            setSelection(newShape);

        }
    });
    //One the rectangle is good
    google.maps.event.addListener(drawingManager, 'rectanglecomplete', function (rectangle) {
        var ne = rectangle.getBounds().getNorthEast();
        var sw = rectangle.getBounds().getSouthWest();
        coordinates = ne +','+ sw;
    });
    //ATTACH BOUNDS CHANGE !!
    google.maps.event.addListener(drawingManager, 'bounds_changed', function (rectangle) {
        console.log('started moving');
        var ne = rectangle.getBounds().getNorthEast();
        var sw = rectangle.getBounds().getSouthWest();
        coordinates = ne +','+ sw;
    });


    //add listeners
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    //google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
    google.maps.event.addDomListener(document.getElementById('delete-all-button'), 'click', deleteAllShape);

    function getCoordinates() {
        console.log(coordinates);
        document.getElementById("result").innerHTML = coordinates;
    }
    //tied to #CoordsButton btn
    google.maps.event.addDomListener(document.getElementById('CoordsButton'), 'click', getCoordinates);
*/
    var bounds = {
        north: 43.612,
        south: 43.475,
        east: 4.249,
        west: 3.972
    };

    // Define the rectangle and set its editable property to true.
    rectangle = new google.maps.Rectangle({
        bounds: bounds,
        editable: true,
        draggable: true,
        fillColor: '#e88708',
        fillOpacity: '0.2',
        strokeColor:'#e88708'
    });

    rectangle.setMap(map);

    // Add an event listener on the rectangle.
    rectangle.addListener('bounds_changed', showNewRect);

    // Define an info window on the map.
    infoWindow = new google.maps.InfoWindow();

    /**
     * @this {google.maps.Rectangle}
     * @param event
     */
    function showNewRect(event) {
        var ne = rectangle.getBounds().getNorthEast();
        var sw = rectangle.getBounds().getSouthWest();

        var contentString = '<b>Nouvelle position Enregistrée</b><br>'
            + ne.lat() + ', ' + ne.lng() + '<br>' +  sw.lat() + ', ' + sw.lng();

        // Set the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(ne);

        infoWindow.open(map);
    }


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

//INIT MAP BECAUSE IT'S HIDDEN
jQuery('.js-show-gmap').click(function(){
    setTimeout(function(){
        initMap();
    },300);

});

//FIND THE COORDINATES
jQuery('#gmap-geocoding-btn').click(function(e) {
    e.preventDefault();
    var gmapAdress = $('#gmap-geocoding').val();
    var geocoder = new google.maps.Geocoder();
    geocodeAddress(geocoder, map);

});


