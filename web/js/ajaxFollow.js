jQuery(document).ready(function () {
    var routeFollow = Routing.generate('followUser');
    var routeUnfollow = Routing.generate('unfollowUser');

    var JsVars = jQuery('#js-vars').data('vars');
    var usr = JsVars.userId;

    // AJAX REQUEST
    $("#toggleButton").change(function () {
        var nbFollowers = parseInt($('#nbFolllowers').text());

        if (this.checked) {
            //AJAX REQUEST FOLLOW
            $.ajax({
                type: "post",
                url: routeFollow,
                data: {'idFollowed': usr},//TODO :this value is based on a dom element. see method with twig
                success: function (data) {
                    var nbFollowersPlus = nbFollowers + 1;
                    $('#nbFolllowers').text(nbFollowersPlus);
                }
            });
        } else {
            //AJAX REQUEST UNFOLLOW
            $.ajax({
                type: "post",
                url: routeUnfollow,
                data: {'idFollowed': usr},
                success: function (data) {
                    var nbFollowersMinus = nbFollowers - 1;
                    $('#nbFolllowers').text(nbFollowersMinus);
                }
            });
        }
    });
});
