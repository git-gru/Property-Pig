var base_url = "http://www.sicsglobal.com/HybridApp/property_pig_new/";
function valid_me()
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
    if ($('#txtpassword').val() == '')
    {
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
                window.location.href = base_url + "dashboard.php";
                return false;
            } else
            {
                $('#result_class_ligin').css("display", "block");
                setTimeout(function () {
                    $('#result_class_ligin').css("display", "none");
                }, 10000);
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
    $.ajax({
        type: "POST",
        data: $('#form_register').serialize(),
        url: base_url+"register_proces.php",
        success: function (result) {
            console.log(result);
            $('#form_register')[0].reset();
            if (result == 1)
            {
                alert("Thankyou for registering! Please check your email to activate your account.");
                return false;
            } else
            {
                alert("Error Found!! Try Again After Sometime");
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