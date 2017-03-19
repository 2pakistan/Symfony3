jQuery(document).ready(function() {

    // AJAX REQUEST
    var inputSearch = $('#form_nomDestination');
    var route = Routing.generate('searchPlace' );

    $('#form-search').on('submit',function(e){
        e.preventDefault();
    });
    $('#autocompleteTest').on('click','li.list-group-item',function(event){
        var placeChosen = $(this).find('#center').text();
        $('#form_nomDestination').val(placeChosen);
        $('#form-search')[0].submit();
        $('#autocompleteTest').empty();
    });

    inputSearch.keypress(function () {
        //generate absolute(true) sf route
        if( inputSearch.val().length >= 2) {
            $.ajax({
                type: "post",
                url: route,
                data: {'string': inputSearch.val()},
                delay: 250,
                success: function (data) {
                    var countries = data['countries'];
                    var places = data['places'];

                    console.log(countries);
                    //AUTOCOMPLETE COUNTRIES
                    $(function () {
                        $('#form_nomDestination').autocomplete({
                            search: function (event, ui) {
                                $('#autocompleteTest').empty();
                            },
                            close: function () {
                                if ($('#form_nomDestination').val() == '') {
                                    $('#autocompleteTest').empty();
                                }
                            },
                            minLength: 1,
                            source: countries,
                        }).data('ui-autocomplete')._renderItem = function (ul, item) {
                            return $('<div/>')
                                .data('ui-autocomplete-item', item)
                                .append("<li class='list-group-item'> <div class='row'> <div class='col-md-12'><a href='#'> <span class='glyphicon glyphicon-globe'></span> </a> <div id='center'>" + item.value + " </div> </div> </div> </li>")
                                .appendTo($('#autocompleteTest'));
                        };
                    });
                },
            });
        }
    });


});
