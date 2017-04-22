jQuery(document).ready(function () {

    var routeUsername = Routing.generate('checkUsername');
    var routeMail = Routing.generate('checkMail');

    // USERNAME AVAILABILITY
    var checkUsername = function (username) {
        $.ajax({
            type: "post",
            url: routeUsername,
            data: {'username': username},
            success: function (data) {
                if (!data.available) {
                    $("#fos_user_registration_form_username_error").fadeIn("slow", "linear");
                    $("#fos_user_registration_form_username_error").text('Username already taken');
                    $("#fos_user_registration_form_username").css('border', '1px solid red');
                    $("#fos_user_registration_form_username_error").removeClass('hidden');
                } else {
                    $("#fos_user_registration_form_username").css('border', '1px solid lightgreen');
                    $("#fos_user_registration_form_username_error").fadeOut("slow", "linear");
                }
            }
        });
    };

    $("#fos_user_registration_form_username").change(function () {
        if ($("#fos_user_registration_form_username").val().length >= 2) {
            var username = $("#fos_user_registration_form_username").val();
            checkUsername(username);
        } else {
            $("#fos_user_registration_form_username_error").text('Your username must have at least 2 letters');
            $("#fos_user_registration_form_username_error").fadeIn();
            $("#fos_user_registration_form_username").css('border', '1px solid red');
            $("#fos_user_registration_form_username_error").removeClass('hidden');
        }
    });

    //ASSERT LASTNAME
    $("#fos_user_registration_form_nom").change(function () {
        if ($("#fos_user_registration_form_nom").val().length < 2) {
            $("#fos_user_registration_form_nom_error").text('Your name must have 2 letters');
            $("#fos_user_registration_form_nom_error").fadeIn();
            $("#fos_user_registration_form_nom").css('border', '1px solid red');
            $("#fos_user_registration_form_nom_error").removeClass('hidden');
        } else {
            $("#fos_user_registration_form_nom").css('border', '1px solid lightgreen');
            $("#fos_user_registration_form_nom_error").fadeOut();
        }
    });

    //ASSERT FIRSTNAME
    $("#fos_user_registration_form_prenom").change(function () {
        $("#fos_user_registration_form_email").css('border', 'inherit');

        if ($("#fos_user_registration_form_prenom").val().length < 2) {
            $("#fos_user_registration_form_prenom_error").text('Your first name must have 2 letters');
            $("#fos_user_registration_form_prenom_error").fadeIn();
            $("#fos_user_registration_form_prenom").css('border', '1px solid red');
            $("#fos_user_registration_form_prenom_error").removeClass('hidden');
        } else {
            $("#fos_user_registration_form_prenom").css('border', '1px solid lightgreen');
            $("#fos_user_registration_form_prenom_error").fadeOut();
        }
    });

    //ASSERT EMAIL
    function isEmail(email) {

        return /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email);
    }

    var checkMail = function(email){
        $.ajax({
            type: "post",
            url: routeMail,
            data: {'email': email},
            success: function (data) {
                if (!data.available) {
                    $("#fos_user_registration_form_email_error").fadeIn("slow", "linear");
                    $("#fos_user_registration_form_email").css('border', '1px solid red');
                    $("#fos_user_registration_form_email_error").text('This email is already taken');
                    $("#fos_user_registration_form_email_error").removeClass('hidden');
                } else {
                    $("#fos_user_registration_form_email").css('border', '1px solid lightgreen');
                    $("#fos_user_registration_form_email_error").fadeOut("slow", "linear");
                }
            }
        });
    };
    $("#fos_user_registration_form_email").change(function () {
        if(isEmail($("#fos_user_registration_form_email").val())){
            $("#fos_user_registration_form_email_error").fadeOut("slow", "linear");
            $("#fos_user_registration_form_email").css('border', 'inherit');
            checkMail($("#fos_user_registration_form_email").val());
        }else{
            $("#fos_user_registration_form_email_error").fadeIn("slow", "linear");
            $("#fos_user_registration_form_email").css('border', '1px solid red');
            $("#fos_user_registration_form_email_error").text('You must provide a valid email');
            $("#fos_user_registration_form_email_error").removeClass('hidden');
        }
    });

    //ASSERT PASSWORD

});
