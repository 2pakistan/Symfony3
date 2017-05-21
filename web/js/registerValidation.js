jQuery(document).ready(function () {

    var routeUsername = Routing.generate('checkUsername');
    var routeMail = Routing.generate('checkMail');

    var componentsForm = {
        username: 'username',
        nom:'nom',
        prenom:'prenom',
        email:'email',
        plainPassword_first:'plainPassword_first',
        plainPassword_second:'plainPassword_second'
    };

    var errors = [];

    for (var component in componentsForm){
        errors[component]= false;
    }
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
                    errors['username'] = true;
                } else {
                    $("#fos_user_registration_form_username").css('border', '1px solid lightgreen');
                    $("#fos_user_registration_form_username_error").fadeOut("slow", "linear");
                    errors['username'] = false;
                }
            }
        });
    };

    $("#fos_user_registration_form_username").blur(function () {
        if ($("#fos_user_registration_form_username").val().length >= 2) {
            var username = $("#fos_user_registration_form_username").val();
            checkUsername(username);
        } else {
            $("#fos_user_registration_form_username_error").text('Your username must have at least 2 letters');
            $("#fos_user_registration_form_username_error").fadeIn();
            $("#fos_user_registration_form_username").css('border', '1px solid red');
            $("#fos_user_registration_form_username_error").removeClass('hidden');
            errors['username'] = true;
        }
    });

    //ASSERT LASTNAME
    $("#fos_user_registration_form_nom").blur(function () {
        if ($("#fos_user_registration_form_nom").val().length < 2) {
            $("#fos_user_registration_form_nom_error").text('Your name must have 2 letters');
            $("#fos_user_registration_form_nom_error").fadeIn();
            $("#fos_user_registration_form_nom").css('border', '1px solid red');
            $("#fos_user_registration_form_nom_error").removeClass('hidden');
            errors['nom'] = true;
        } else {
            $("#fos_user_registration_form_nom").css('border', '1px solid lightgreen');
            $("#fos_user_registration_form_nom_error").fadeOut();
            errors['nom'] = false;

        }
    });
    //ASSERT FIRSTNAME
    $("#fos_user_registration_form_prenom").blur(function () {

        if ($("#fos_user_registration_form_prenom").val().length < 2) {
            $("#fos_user_registration_form_prenom_error").text('Your first name must have 2 letters');
            $("#fos_user_registration_form_prenom_error").fadeIn();
            $("#fos_user_registration_form_prenom").css('border', '1px solid red');
            $("#fos_user_registration_form_prenom_error").removeClass('hidden');
            errors['prenom'] = true;
        } else {
            $("#fos_user_registration_form_prenom").css('border', '1px solid lightgreen');
            $("#fos_user_registration_form_prenom_error").fadeOut();
            errors['prenom'] = false;
        }
    });

    //ASSERT EMAIL
    //Regex RFC 5322 Official Standard
    function isEmail(email) {

        return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
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
                    errors['email'] = true;
                } else {
                    $("#fos_user_registration_form_email").css('border', '1px solid lightgreen');
                    $("#fos_user_registration_form_email_error").fadeOut("slow", "linear");
                    errors['email'] = false;
                }
            }
        });
    };
    $("#fos_user_registration_form_email").blur(function () {

        if(isEmail($("#fos_user_registration_form_email").val())){
            $("#fos_user_registration_form_email_error").fadeOut("slow", "linear");
            checkMail($("#fos_user_registration_form_email").val());
        }else{
            $("#fos_user_registration_form_email_error").fadeIn("slow", "linear");
            $("#fos_user_registration_form_email").css('border', '1px solid red');
            $("#fos_user_registration_form_email_error").text('You must provide a valid email');
            $("#fos_user_registration_form_email_error").removeClass('hidden');
            errors['email'] = true;
        }
    });

    //ASSERT PASSWORD LENGTH
    $("#fos_user_registration_form_plainPassword_first").blur(function () {
        if ($("#fos_user_registration_form_plainPassword_first").val().length < 3) {
            $("#fos_user_registration_form_plainPassword_first_error").fadeIn("slow", "linear");
            $("#fos_user_registration_form_plainPassword_first").css('border', '1px solid red');
            $("#fos_user_registration_form_plainPassword_first_error").removeClass('hidden');
            errors['plainPassword_first'] = true;
        }else{
            $("#fos_user_registration_form_plainPassword_first_error").fadeOut("slow", "linear");
            $("#fos_user_registration_form_plainPassword_first").css('border', '1px solid lightgreen');
            errors['plainPassword_first'] = false;
        }
    });
    //ASSERT PASSWORD EQUALITY
    $("#fos_user_registration_form_plainPassword_second").blur(function () {
        if ($("#fos_user_registration_form_plainPassword_second").val() !== $("#fos_user_registration_form_plainPassword_first").val()) {
            $("#fos_user_registration_form_plainPassword_second_error").fadeIn("slow", "linear");
            $("#fos_user_registration_form_plainPassword_second_error").removeClass('hidden');
            $("#fos_user_registration_form_plainPassword_second").css('border', '1px solid red');
            errors['plainPassword_second'] = true;

        }else{
            $("#fos_user_registration_form_plainPassword_second_error").fadeOut("slow", "linear");
            $("#fos_user_registration_form_plainPassword_second").css('border', '1px solid lightgreen');
            errors['plainPassword_second'] = false;
        }
    });

    console.log('totototo');
    //MANAGE FORM SUBMIT - ERRORS
    $('.fos_user_registration_register').on('submit' , function(e){

        var isFormValid = true ;
        console.log('submitted ! ');
        console.log(errors);

        for (var error in errors){
           var hasComponentError = errors[error];
           if(hasComponentError){
               isFormValid = false;
           }
        }
        if(!isFormValid){
            e.preventDefault();
            console.log('form bloqué');
        }else{
            e.submit();
            console.log('form good');
        }
    });
});
