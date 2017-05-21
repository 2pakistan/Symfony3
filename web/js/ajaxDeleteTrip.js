jQuery(document).ready(function () {

    var routeDelete = Routing.generate('deleteTrip');

    //get trip id in parent modal

    // AJAX REQUEST
    $(".delete-trip").click(function () {
        const trip = $(this).parents('.modal').attr('data-key');
        var tripDomElement = $(this).parents('.trip-post');

        //AJAX REQUEST UNFOLLOW
        $.ajax({
            type: "post",
            url: routeDelete,
            data: {'trip': trip},
            success: function (data) {
                //close modal
                $('.modal').hide();
                $('.modal-backdrop').hide();
                $('body').removeClass('modal-open');

                tripDomElement.removeClass('trip-post');
                tripDomElement.fadeOut(200);
            }
        });
    });
});
