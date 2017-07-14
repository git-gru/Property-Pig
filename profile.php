<?php
include 'header.php';
//session_start();
//echo $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    echo("<script>location.href = '" . $base_url . "register.php';</script>");
} else {
    $obj1 = new commonFunctions();
    $dbh = $obj1->dbh;
    $status = false;
    $row = '';
    $user_id=$_SESSION['user_id'];
    $query = $dbh->prepare("select * from pig_user where user_id=:user_id");
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
<style>
    .rstpwd:hover{color: #8173c4!important;}
</style>
<!-----body section---->
<div class="ndiv body-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Profile</p>
    </div>
    <div class="ndiv primary-center-holder">
        <div class="ndiv primary-center-in">



            <div class="ndiv fileds-section">
                <div class="ndiv halfsectionoregister">

                    <div class="ndiv files-of-registration" page="register">
                        <form action="javascript:void(0);" id="form_register"   name="form_register"  method="POST" onsubmit="return update_profile();">
                            <div class="ndiv filelds-holder">
                                <input class="register-fields" placeholder="Name" id="txtname" value="<?php echo @$rows['Name']; ?>" name="txtname">
                                <input class="register-fields" pattern="^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$"  placeholder="Phone" value="<?php echo @$rows['phoneNumber']; ?>" id="txtphone" name="txtphone">
                                <input class="register-fields" type="email" placeholder="Email Id"  value="<?php echo @$rows['email']; ?>" id="txtemail" name="txtemail">
                                <input class="register-fields" placeholder="User Name"  value="<?php echo @$rows['userName']; ?>" id="txtusername" name="txtusername">
                                <?php
                                if($rows['fb_id']=='NULL' && $rows['tw_id']=='NULL' && $rows['lk_id']=='NULL')
                                {}
                                else
                                    { ?><style>#txtusername{margin-bottom: 10px;}</style>
                                    <br/><p><u><a  class="rstpwd" style="    padding-left: 12px;margin: 0px 0px 0px 4px;font-weight: bold;" href="reset_password.php">Reset Password</a></u></p><br/>
                                    <?php
                                }
                                ?>
                                <input class="register-fields" placeholder="Address Line 1" value="<?php echo @$rows['Address']; ?>"  id="txtaddress" name="txtaddress">
                                <input class="register-fields" placeholder="Address Line 2" value="<?php echo @$rows['Address1']; ?>" id="txtaddress1" name="txtaddress1">
                                <input class="register-fields" placeholder="Postcode"  value="<?php echo @$rows['zipCode']; ?>"  id="txtzip" name="txtzip">

                                <button type="submit" class="get-my-cash-offer">
                                    Update
                                </button>
                                <input  type="hidden" value="001"  id="fb_id" name="fb_id">
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
<!----/body section---->
<script>
    function update_profile()
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

        if ($('#txtusername').val() == '')
        {
            $('#txtusername').css("border", "1px solid red");
            setTimeout(function () {
                $('#txtusername').css("border", "none");
            }, 3000);
            $('#txtusername').focus();
            return false;
        }

         <?php
        if($rows['fb_id']!='' || $rows['tw_id']!='' || $rows['lk_id']!='')
        {}
        else
        { ?>

        if ($('#txtpassword').val() != '' || $('#txtcpassword').val() != '')
        {
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
        }
        
       <?php } ?>
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


        if ($('#fb_id').val() != '')
        {  
            $.ajax({
                type: "POST",
                data: $('#form_register').serialize(),
                url: "register_update_procesed.php",
                success: function (result) {   
                    console.log(result);
                    $('.yello-menu').trigger('click');
                    if (result == 1)
                    {
                        $('#form_register')[0].reset();
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Profile Updated Successfully...');
                        setTimeout(function () {
                            window.location.href = "<?php echo $base_url . "dashboard.php"; ?>";
                        }, 3000);
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




</script>
<?php include 'footer.php'; ?>
