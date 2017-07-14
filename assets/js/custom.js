var base_url = "https://www.propertypig.co.uk/";
function valid_me()
{
    if ($('#txtname').val() == '')
    {
        $('.yello-menu').trigger('click');
        $('.popup-header').html('NOTIFICATION');
        $('.popup-message').html('Please enter the email and password');
        $('#txtname').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtname').css("border", "none");
        }, 3000);
        $('#txtname').focus();
        return false;
    }
    if ($('#txtpassword').val() == '')
    {
        $('.yello-menu').trigger('click');
        $('.popup-header').html('NOTIFICATION');
        $('.popup-message').html('Please enter the email and password');
        $('#txtpassword').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtpassword').css("border", "none");
        }, 3000);
        $('#txtpassword').focus();
        return false;
    }
    $.ajax({
        type: "POST",
        data: $('#form_register').serialize(),
        url: base_url + "login_proces.php",
        success: function (result) {
            console.log(result);
             
                if (result == 1)
                {
                    //alert("Thankyou for registering.");

//                    $('.popup-header').html('');
//                    $('.popup-message').html('');
//                    $('.popup-header').html('NOTIFICATION');
//                    $('.popup-message').html('You have Successfully Logined');
                    setTimeout(function () {
                        window.location.href = base_url+"dashboard.php";
                    }, 500);
                    return false;

                } else
                {
                    $('.yello-menu').trigger('click');
                    $('.popup-header').html('NOTIFICATION');
                    $('.popup-message').html('Please make sure you entered the correct details');
                    return false;
                }

        }
    });


}


/*registeration form validation code*/
function valid_form_register()
{
    if ($('#txtname').val() == '')
    {
        $('#txtname').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtname').css("border", "none");
        }, 3000);
        $('#txtname').focus();
        return false;
    }
    if ($('#txtphone').val() == '')
    {
        $('#txtphone').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtphone').css("border", "none");
        }, 3000);
        $('#txtphone').focus();
        return false;
    }
    if ($('#txtemail').val() == '')
    {
        $('#txtemail').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtemail').css("border", "none");
        }, 3000);
        $('#txtemail').focus();
        return false;
    }
    if ($('#txtusername').val() == '')
    {
        $('#txtusername').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtusername').css("border", "none");
        }, 3000);
        $('#txtusername').focus();
        return false;
    }
    if ($('#txtpassword').val() == '')
    {
        $('#txtpassword').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtpassword').css("border", "none");
        }, 3000);
        $('#txtpassword').focus();
        return false;
    }
    if ($('#txtcpassword').val() == '')
    {
        $('#txtcpassword').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtcpassword').css("border", "none");
        }, 3000);
        $('#txtcpassword').focus();
        return false;
    }
    if (($('#txtpassword').val() != $('#txtcpassword').val()))
    {
        $('.yello-menu').trigger('click');
        $('.popup-header').html('');
        $('.popup-message').html('');
        $('.popup-header').html('NOTIFICATION');
        $('.popup-message').html('Password mismatched');                        
        $('#txtpassword').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtpassword').css("border", "none");
        }, 3000);
        $('#txtpassword').focus();
        $('#txtcpassword').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtcpassword').css("border", "none");
        }, 3000);
        return false;
    }
    if ($('#txtaddress').val() == '')
    {
        $('#txtaddress').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtaddress').css("border", "none");
        }, 3000);
        $('#txtaddress').focus();
        return false;
    }
    if ($('#txtaddress1').val() == '')
    {
        $('#txtaddress1').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtaddress1').css("border", "none");
        }, 3000);
        $('#txtaddress1').focus();
        return false;
    }
    if ($('#txtzip').val() == '')
    {
        $('#txtzip').css("border", "1px solid red");
        setTimeout(function () {
            $('#txtzip').css("border", "none");
        }, 3000);
        $('#txtzip').focus();
        return false;
    }
   
    var a=0;
     $.ajax({
        type: "POST",
        data: "username="+$('#txtusername').val(),
        url: base_url+"username_check.php",
        success: function (result) {
            if (result == 1)
            {
                $('.yello-menu').trigger('click');
                $('.popup-header').html('NOTIFICATION');
                $('.popup-message').html('Username Already Exist');
                return false;
            } else
            {
               $.ajax({
                    type: "POST",
                    data: "email="+$('#txtemail').val(),
                    url: base_url+"email_check.php",
                    success: function (result) {
                        if (result == 1)
                        {
                            $('.yello-menu').trigger('click');
                            $('.popup-header').html('NOTIFICATION');
                            $('.popup-message').html('Email Already Exist');
                            return false;
                        } else
                        {   
                            $.ajax({
                                    type: "POST",
                                    data: $('#form_register').serialize(),
                                    url: base_url+"register_proces.php",
                                    success: function (result) {
                                        $('.yello-menu').trigger('click');
                                        $('#form_register')[0].reset();
                                        if (result == 1)
                                        {
                                             $('.popup-header').html('');
                                                $('.popup-message').html('');
                                                $('.popup-header').html('NOTIFICATION');
                                                $('.popup-message').html('Thankyou for registering! Please check your email to activate your account.');
                                                setTimeout(function () { window.location.href = base_url; }, 3000);
                                                return false;

                                        } else
                                        {
                                            $('.popup-header').html('NOTIFICATION');
                                            $('.popup-message').html('Error Found, Try Again');
                                            return false;
                                        }
                                    }
                                });
                            
                            
                        }
                    }
                });
                
                
            }
        }
    });
    
   
    
    
    

}

/*registeration form validation code end*/



/*valid_question form code here*/
function valid_question()
{
    alert("d");
}
/*valid_question form code end here*/
