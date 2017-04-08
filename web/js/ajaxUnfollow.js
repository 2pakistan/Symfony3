jQuery(document).ready(function () {
    var routeUnfollow = Routing.generate('unfollowUser');

    var JsVars = jQuery('#js-vars').data('vars');
    var nbFollowed = parseInt($('#nbFollowed').text());

    $('.unfollowBtn').click(function(){
        var unFollowed = $(this).parents('tr');
        var strId = unFollowed.attr('id');
        var idUser =strId.replace('followed_','');

        //AJAX REQUEST UNFOLLOW
        $.ajax({
            type: "post",
            url: routeUnfollow,
            data: {'idFollowed': idUser},
            success: function (data) {
                unFollowed.fadeOut(500);
                var nbFollowersMinus = nbFollowed - 1;
                $('#nbFollowed').text(nbFollowersMinus);
            }
        });
    });
});