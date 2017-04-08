jQuery(document).ready(function() {

    var inputSearch = $('#form_nomDestination');
    var route = Routing.generate('searchPlace' );

    $('#form-search').on('submit',function(e){
        e.preventDefault();
    });

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
        inputSearch.easyAutocomplete(options);

    $('.easy-autocomplete-container ul li').click(function(event){
        $('#form-search')[0].submit();
    });

});