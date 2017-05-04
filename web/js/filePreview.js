/**
 * Created by sheitan666 on 08/02/2017.
 */
$(document).ready(function() {

    $(document).on('click', '#close-preview', function(){
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
                $('.image-preview').popover('show');
            },
            function () {
                $('.image-preview').popover('hide');
            }
        );
    });



    $(function() {

        // Set the popover default content
        $('#profile-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Profile picture selected</strong>",
            content: "Il n'y a pas d'images",
            placement:'right'
        });
        // Clear event
        $('#profile-preview-clear').click(function(){
            $('#profile-preview').attr("data-content","").popover('hide');
            $('#profile-preview-filename').val("");
            $('#profile-preview-clear').hide();
            $('#profile-preview-input input:file').val("");
            $("#profile-preview-input-title").text("Browse");
        });
        // Create the preview image
        $("#profile-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200
            });

            var file = this.files[0];
            console.log(this.files);
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $("#profile-preview-input-title").text("Change");
                $("#profile-preview-clear").show();
                $("#profile-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $("#profile-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $("#cover-preview").attr("data-content",$(img)[0].outerHTML).popover("hide");
            }
            reader.readAsDataURL(file);
        });
    });

    $(function() {

        // Set the popover default content
        $('#cover-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Cover picture selected</strong>",
            content: "Il n'y a pas d'images",
            placement:'right'
        });
        // Clear event
        $('#cover-preview-clear').click(function(){
            $('#cover-preview').attr("data-content","").popover('hide');
            $('#cover-preview-filename').val("");
            $('#cover-preview-clear').hide();
            $('#cover-preview-input input:file').val("");
            $("#cover-preview-input-title").text("Browse");
        });
        // Create the preview image
        $("#cover-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200
            });
            var file = this.files[this.files.length-1];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $("#cover-preview-input-title").text("Change");
                $("#cover-preview-clear").show();
                $("#cover-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $("#cover-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $("#profile-preview").attr("data-content",$(img)[0].outerHTML).popover("hide");
            }
            reader.readAsDataURL(file);
        });
    });

});




