function initAutocomplete() {

    var input = /** @type {!HTMLInputElement} */(document.getElementById('autocomplete'));

    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');

            var addressParts = orderData(place.address_components);
            fillAddressFields(addressParts);
            //console.log(place);
            //console.log(address);
        } else {
            clearFields();
        }

    });

    function orderData(placeInfos) {
        var orderedData = [];
        orderedData['locality'] = "" ;
        orderedData['country'] = "" ;
        orderedData['country_short'] = "" ;
        orderedData['administrative_area_level_1'] = "" ;
        orderedData['administrative_area_level_2'] = "" ;
        orderedData['street_number'] = "" ;
        orderedData['route'] = "" ;
        orderedData['postal_code'] = "" ;

        for (var i = 0; i < placeInfos.length; i++) {
            var component = placeInfos[i]
            var typeComponent = component.types[0];
console.log(component);
            switch (typeComponent) {
                case 'locality':
                    orderedData['locality'] = component.long_name;
                    break;
                case 'country':
                    orderedData['country'] = component.long_name;
                    orderedData['country_short'] = component.short_name;
                    break;
                case 'administrative_area_level_1':
                    orderedData['administrative_area_level_1'] = component.long_name;
                    break;
                case 'administrative_area_level_2':
                    orderedData['administrative_area_level_2'] = component.long_name;
                    break;
                case 'street_number':
                    orderedData['street_number'] = component.long_name;
                    break;
                case 'route':
                    orderedData['route'] = component.long_name;
                    break;
                case 'postal_code':
                    orderedData['postal_code'] = component.long_name;
                    break;
                default:
                    break;
            }
        }
        return orderedData;
    }

    // @param array
    // return void
    function fillAddressFields(placeInfos) {
        var country = placeInfos['country'];
        var city = placeInfos['locality'];
        var streetNb = placeInfos['street_number'];
        var adress = placeInfos['route'];
        var area = placeInfos['administrative_area_level_1'];

        //Fill fields with different parts of the address
        var streetNbInput = (document.getElementById('streetnumber'));
        streetNbInput.value = streetNb;

        var adressInput = (document.getElementById('adress'));
        adressInput.value = adress;

        var cityInput = (document.getElementById('city'));
        cityInput.value = city;

        var countryInput = (document.getElementById('country'));
        countryInput.value = country;

        var areaInput = (document.getElementById('area'));
        areaInput.value = area;

    }

    function clearFields() {
        //clear fields
        document.getElementById('streetnumber').value = "";
        document.getElementById('adress').value = "";
        document.getElementById('city').value = "";
        document.getElementById('country').value = "";
    }

}