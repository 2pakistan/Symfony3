$(document).ready(function () {

    $('.overlay-member-cover').hide();
    $('#btn-valid-cover').hide();

    $('.member-cover').mouseenter(function () {
        $('.overlay-member-cover').fadeIn();
    });
    $('.member-cover').mouseleave(function () {
        $('.overlay-member-cover').fadeOut();
    });
    $(function () {
        $(".overlay-member-cover input:file").change(function () {
            console.log('there is a file !! ');
            $('#btn-valid-cover').fadeIn();
            $('#wrapper-btn-cover').removeProp('hide');

            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $('#coverPicMembre').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);

        });
    });

    //manage tabs
    var $tabContent = $(".tab-content"),
        $tabs = $("ul.tabs li"),
        tabId;

    $tabContent.hide();
    $("ul.tabs li:first").addClass("active").show();
    $tabContent.first().show();

    $tabs.click(function () {
        var $this = $(this);
        $tabs.removeClass("active");
        $this.addClass("active");
        $tabContent.hide();
        var activeTab = $this.find("a").attr("href");
        $(activeTab).fadeIn();
        //return false;
    });

    // Grab the ID of the .tab-content that the hash is referring to
    tabId = $(window.location.hash).closest('.tab-content').attr('id');

    // Find the anchor element to "click", and click it
    $tabs.find('a[href=#' + tabId + ']').click();

});

