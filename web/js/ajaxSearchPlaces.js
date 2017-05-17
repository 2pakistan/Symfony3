jQuery(document).ready(function() {

    var inputSearch = $('#form_nomDestination');
    var route = Routing.generate('searchPlace' );

        var options = {

        url: function(string) {
            return route;
        },

        getValue: function(element) {
            return element.name;
        },

        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.string = inputSearch.val();
            return data;
        }

    };
        console.log(inputSearch.easyAutocomplete(options));
        inputSearch.easyAutocomplete(options);


    $('.easy-autocomplete-container li').click(function(){
        $('#form-search')[0].submit();
    });

});