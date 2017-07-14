<?php
session_start();
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    echo("<script>location.href = '" . $base_url . "signin.php';</script>");
} else {
    $obj1 = new commonFunctions();
    $dbh = $obj1->dbh;
    $status = false;
    $row = '';
    $user_id=$_SESSION['user_id'];
    $query = $dbh->prepare("select Password from pig_user where user_id=:user_id");
    $query->bindParam(":user_id",$user_id);
    $query->execute();
    $counts_questions = $query->rowCount();
    if ($query->rowCount() > 0) {
        $rows = $query->fetch(PDO::FETCH_ASSOC);
       
    } else {
        $rows = array();
    }
}
?>

<!-----body section---->
<div class="ndiv body-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Change Password</p>
    </div>
    <div class="ndiv primary-center-holder">
        <div class="ndiv primary-center-in">



            <div class="ndiv fileds-section">
                <div class="ndiv halfsectionoregister">

                    <div class="ndiv files-of-registration" page="register">
                        <form action="javascript:void(0);" id="form_register"   name="form_register"  method="POST" onsubmit="return update_password();">
                            <div class="ndiv filelds-holder">
                                <input class="register-fields" placeholder="Current password" type="text" id="old_password" name="old_password">
                                <input class="register-fields" placeholder="Password" type="Password" id="txtpassword" name="txtpassword">
                                <input class="register-fields" placeholder="Confirm password" type="Password" id="txtcpassword" name="txtcpassword">

                                <button type="submit" class="get-my-cash-offer">
                                    Update Password
                                </button>
                                <input  type="hidden" value="<?php echo $rows['Password'] ?>"  id="old_pwd" name="old_pwd">
                            </div>
                        </form>
                    </div>

                </div>

                <div class="ndiv halfsectionoregister">

                    <div class="ndiv image-with-socio">
                        <div class="ndiv image-with-socio-in">
                            <img class="img-responsive pig-to-sign" alt="pig on signup" src="<?php echo $assets; ?>image/signinpig.png" />

                            
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
<!----/body section----->
<script>
    function update_password()
    {
        if ($('#old_password').val() == '')
        {
             $('.yello-menu').trigger('click');
             $('.popup-header').html('');
             $('.popup-message').html('');
             $('.popup-header').html('NOTIFICATION');
             $('.popup-message').html('Please Enter Current password');
            $('#old_password').css("border", "1px solid red");
            setTimeout(function () {
                $('#old_password').css("border", "none");
            }, 3000);
            $('#old_password').focus();
            return false;
        }
        
            if ($('#txtpassword').val() == '')
            {
                 $('.yello-menu').trigger('click');
                 $('.popup-header').html('');
                 $('.popup-message').html('');
                 $('.popup-header').html('NOTIFICATION');
                 $('.popup-message').html('Please enter the new password');
                $('#txtpassword').css("border", "1px solid red");
                setTimeout(function () {
                    $('#txtpassword').css("border", "none");
                }, 3000);
                $('#txtpassword').focus();
                return false;
            }
            if ($('#txtcpassword').val() == '')
            {
                $('.yello-menu').trigger('click');
                 $('.popup-header').html('');
                 $('.popup-message').html('');
                 $('.popup-header').html('NOTIFICATION');
                 $('.popup-message').html('Please confirm the new password');
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
       
         $.ajax({
            type: "POST",
            data: $('#form_register').serialize(),
            url: "<?php echo $base_url; ?>update_password_proc.php",
            success: function (result) {
                if(result==1)
                {
                     $('.yello-menu').trigger('click');
                     $('.popup-header').html('');
                     $('.popup-message').html('');
                     $('.popup-header').html('NOTIFICATION');
                     $('.popup-message').html('Password Changed Succesfully');
                     setTimeout(function () {
                                    window.location.href = "<?php echo $base_url; ?>profile.php";
                                }, 2000);
                     return false;
                }
                else
                {
                     $('.yello-menu').trigger('click');
                     $('.popup-header').html('');
                     $('.popup-message').html('');
                     $('.popup-header').html('NOTIFICATION');
                     $('.popup-message').html('Current password is not correct');
                     return false;
                }
                


            }
        });
    }




</script>
<?php include 'footer.php'; ?>
