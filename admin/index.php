<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['userid']) && $_SESSION['userid'] != '') {
    header('location:home.php');
}
include 'json/settings.php';
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Welcome to property pig</title>
        <style>
            #wait{overflow-x: hidden;width: 100%;min-height: 2000px;background-color: rgba(0, 0, 0, 0.29);z-index: 9998;position: absolute;}
            #wait img{padding-left: 43%;padding-top: 20%;}
        </style>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <!--<link href="css/sb-admin-2.css" rel="stylesheet">-->

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-------UI Developers Costimised Css Tool------> 
        <link rel="stylesheet" href="css/break_bs_v_1_2.css" />
        <link rel="stylesheet" type="text/css" href="css/sweetalert.css">
        
        <!------ui developer costamised css---->
            <link rel="stylesheet" href="css/admin-style.css" />
            
            

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>
        <div  id="wait" style="display: none;"><img src="../gif.gif"></div>
        <div class="ndiv page-holder-admin" page="signin">

            <div class="ndiv signin-logo-holder">
                <img class="imgres signin-logo" src="image/signin_logo.png" /> 
            </div>

            <div class="ndiv signin-form-holder">
                <div class="ndiv signin-form-center">
                    <form role="form">
                        <div class="ndiv signin-form-in">
                            <p class="pmzero label-signin">Username</p>
                            <div class="ndiv input-holder focused">
                                <input class="signin-field" id="email" placeholder="Username" name="email" type="text" autofocus>
                               <!--<input class="signin-field" placeholder="PremiumPixels" />-->
                            </div>

                            <p class="pmzero label-signin">Password <span class="fup-signin">
                                    <a href="javascript:void(0);" id="forget_password" >Forget your password?</a>
                                </span></p>
                            <div class="ndiv input-holder focused">
                                <input class="signin-field" id="password"  placeholder="Password" name="password" type="password" autofocus >
<!--                                <input class="signin-field" placeholder="" />-->
                            </div>



                        </div>
                        <div class="ndiv button-section-signin">

                            <div class="ndiv keep-me-signedin">
                                <input name="remember" value="Remember Me" class="keep" type="checkbox"  /> 

                                <span>Keep me signed in</span>
                            </div>

                            <div class="ndiv keep-me-signedin">
                                <button type="button" class="signin-button" id="submit_login">Login</button>
                                <!--<button class="signin-button">Login</button>-->
                            </div>

                        </div>
                    </form
                </div>
            </div>


        </div>
    </div>

<!--    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                                <div class="form-group" method="post">
                                    <input class="form-control" id="email" placeholder="Username" name="email" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password"  placeholder="Password" name="password" type="password" autofocus >
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                 Change this to a button or input when using this as a form 
                                <button type="button" class="btn btn-lg btn-success btn-block" id="submit_login">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script type="text/javascript" src="js/sweetalert-dev.js"></script>
    <script>
        /*ajax loader*/
        $(document).ajaxStart(function(){
            $("#wait").css("display", "block");
            });

            $(document).ajaxComplete(function(){
                $("#wait").css("display", "none");
            });
         /*ajax loader end her*/  
 
        /*password reset code here*/
            $('#forget_password').on('click', function () {
                var data="";
                $.ajax({
                    url: "<?php echo $admin_url; ?>/reset_password.php",
                    data: data,
                    success: function (result) {
                        $('#mypopup').trigger('click');
                    }
                });
            });
        /*password reset code end here*/
        
        $('#submit_login').on('click', function () {
            var username = $("#email").val();
            var password = $("#password").val();
            if (username == '') {
                swal({
                    title: "<span style='color: #234A79;'>SIGNIN</span>",
                    text: "<span style='color: #D8670A;'>Please enter email</span>",
                    html: true
                });
                return;
            } else if (password == '') {
                swal({
                    title: "<span style='color: #234A79;'>SIGNIN</span>",
                    text: "<span style='color: #D8670A;'>Please enter password</span>",
                    html: true
                });
                return;
            } else {
                //                alert("hai");
                data = "username=" + username + "&password=" + password + "&request=adminLogin";
                $.ajax({
                    url: "<?php echo $admin_url; ?>/json/api.php",
                    dataType: "json",
                    data: data,
                    async: true,
                    success: function (result) {
                        console.log(result);
                        if (result.status == true) {

                            localStorage.setItem('userid', result.userdata.id);
                            document.location.href = 'listofcustomer.php';
                        } else {
                            swal("SIGNIN", "Login Failed", "error");
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>
<button type="button" class="btn btn-info btn-lg" style="visibility: hidden;" id="mypopup" data-toggle="modal"  data-target="#myModal2">Open Modal</button>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Notification</h4>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="panel-body" id="targetquestion">
                        <p>Your Password changed successfully, Please check your email</p>

                    </div>
                    <!-- /.panel-body -->

                </div>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
