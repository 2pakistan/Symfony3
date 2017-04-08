var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'short_name',
    postal_code: 'short_name',
    latitude: 'long_name',
    longitude: 'long_name'
};

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
        {types: ['geocode']});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

// [START region_fillform]
function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    var lat = place.geometry.location.lat();
    var lon = place.geometry.location.lng();

    /*for (var component in componentForm) {
     document.getElementById(component).value = '';
     document.getElementById(component).disabled = false;
     }*/

    // Get each component of the address from the place details
    // and fill an array
    var placeParts = [];
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            placeParts[addressType] = val;
        } else {
        }
    }

    //CUSTOM HAND FILLING FIELDS
    document.getElementById('create_step_latitude').value = lat;
    document.getElementById('create_step_longitude').value = lon;
    document.getElementById('create_step_country').value = placeParts['country'];
    document.getElementById('create_step_cities').value = placeParts['locality'];
    document.getElementById('create_step_state').value = placeParts['administrative_area_level_1'];
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}