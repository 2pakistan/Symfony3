//Auto positioning step
function preventAnchorScroll() {
    var scrollToTop = function () {
        $(window).scrollTop(0);
    };
    if (window.location.hash) {
        // handler is executed at most once
        $(window).one('scroll', scrollToTop);
    }

    // make sure to release scroll 1 second after document readiness
    // to avoid negative UX
    $(document).ready(function () {
        setTimeout(
            function () {
                $(window).off('scroll', scrollToTop);
            },
            4000
        );
    });
}
preventAnchorScroll();

var anchor = window.location.hash;
$(function () {
    if (anchor) {
        // smooth scroll to the anchor id
        $('html, body').animate({
            scrollTop: $(anchor).offset().top - 75
        }, 2000);
    }
});

//maps
jQuery(function () {

    // Asynchronously Load the map API
    var script = document.createElement('script');
    script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyA8MUOfjQsy5bd9U4L8vYqpGkNjM9qOfYw&?sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {

    var JsVars = jQuery('#js-vars').data('vars');
    var markers = JsVars.stepMarkers;
    var stepData = JsVars.stepData;
    var stepMedias = JsVars.stepMedias;

    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };

    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);

    // Info Window Content
    var infoWindowContent = [];
    for (i = 0; i < markers.length; i++) {
        var content = [];
        var markup =
            '<div class="info_content">' +
            '<h3>' + markers[i][0] + '</h3>' +
            '<p>' + stepData[i][0] + '</p>';
        for (var j = 0; j < stepMedias[i].length; j++) {
            var pathMedia = stepMedias[i][j].pathMedia;
            markup += '<img src="http://localhost/symfonyroadtrip2/web/img/users/etapes/' + pathMedia + '"  style="max-width:75%; margin-bottom: 2rem" class="img-responsive img img-center"/>'; //TODO : GET VICH ASSET PATH
        }
        content.push(markup);
        infoWindowContent.push(content);
    }

    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;

    // Loop through our array of markers & place each one on the map  
    for (i = 0; i < markers.length; i++) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });

        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
        this.setZoom(3);
        google.maps.event.removeListener(boundsListener);
    });

}