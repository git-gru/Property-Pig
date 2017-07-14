<?php 
if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: login-twitter.php");
    } 
}
include 'header.php';
?>
<!-----body section----->
<div class="ndiv body-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Login</p>
    </div>
    <div class="ndiv primary-center-holder">
        <div class="ndiv primary-center-in">



            <div class="ndiv fileds-section">
                <div class="ndiv halfsectionoregister">
                    <form action="javascript:void(0);" id="form_register"   name="form_register"  method="POST" onsubmit="return valid_me();">
                        <div class="ndiv files-of-registration" page="signin">
                            <div class="ndiv filelds-holder">
                                <b><div class="result_class_ligin">Error Found!, Try Agian...</div></b>
                                <input class="register-fields" id="txtname" name="txtname"  placeholder="Name">
                                <input class="register-fields" id="txtpassword" name="txtpassword" placeholder="Password" type="Password">
                                <p class="pmzero remember-pass">
                                    <span class="remember">
                                        <check class="check-lable"></check>
                                        Remember me
                                        <input class="remember-checkbox" type="checkbox" />
                                    </span>

                                    <span class="fotgot-password">
                                        <a href="#">Forgot Password?</a>
                                    </span>

                                </p>
                                <button type="submit" class="get-my-cash-offer">
                                    SIGN IN
                                </button>

                            </div>
                        </div>
                    </form> 
                </div>

                <div class="ndiv halfsectionoregister">

                    <div class="ndiv image-with-socio">
                        <div class="ndiv image-with-socio-in">
                            <img class="img-responsive pig-to-sign" alt="pig on signup" src="<?php echo $assets; ?>image/signinpig.png" />

                            <p class="pmzero sign-in-with">SignUp with</p>

                            <div class="ndiv sign-up-with">
                                <ul class="bullet_free menu-ul social-ul">
                                    <li >
                                        <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">
                                            <i class="fa fa-facebook" aria-hidden="true">                                             
                                            </i>
                                        </a>
                                    </li>
                                   <div id="status"></div> <div id="userData"></div>
                                    <li>
                                        <a href="?login&oauth_provider=twitter">
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="social/linkedin/process.php">
                                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>



        </div>
    </div>

    <div class="ndiv register-here">
        <div class="ndiv register-center">
            <div class="ndiv register-center-in">
                <p class="pmzero new-here">New Here</p>
                <button <?php if(!empty($_SESSION['user_id'])){ ?> id="home" <?php }else{?> id="home_index" <?php } ?> class="get-my-cash-offer" page="signin-reg">
                    Register
                </button>
            </div>
        </div>
    </div>

</div>
<!----/body section----->
<script>
    window.fbAsyncInit = function () {
// FB JavaScript SDK configuration and setup
        FB.init({
            appId: '415670272164650', // FB App ID
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true, // parse social plugins on this page
            version: 'v2.8' // use graph api version 2.8
        });
    };

// Load the JavaScript SDK asynchronously
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
    function fbLogin() {
        FB.login(function (response) {
            if (response.authResponse) {
                // Get and display the user profile data
                getFbUserData();
            } else {
                document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
            }
        }, {scope: 'email'});
    }

// Fetch the user profile data from facebook
    function getFbUserData() {
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture,ids_for_business'},
                function (response)
                {
                        //console.log(response);
                        var id='';
                        if(response.id!='undefined')
                        {
                            var id=response.id;
                        }
                        var first_name='';
                        if(response.first_name!='undefined')
                        {
                            var first_name=response.first_name;
                        }
                        var last_name='';
                        if(response.last_name!='undefined')
                        {
                            var last_name=response.last_name;
                        }
                        var email='';
                         if(response.email!='undefined')
                        {
                            var email=response.email;
                        }
                        
                        $.ajax({
                        type: "POST",
                        data: "fb_id=" + id + "&name=" + first_name + " " + last_name + "&email=" +email,
                        url: "<?php echo $base_url; ?>fb_process.php",
                        success: function (result) {
                            
                            console.log(result);
                            if (result == 1)
                            {
                                setTimeout(function () {
                                    window.location.href = "<?php echo $base_url; ?>Profile_complete.php";
                                }, 1000);
                                return false;

                            }
                            else if (result == 2)
                            {
                                setTimeout(function () {
                                    window.location.href = "<?php echo $base_url; ?>Profile_complete.php";
                                }, 1000);
                                return false;

                            }
                            else
                            {
                                $('.yello-menu').trigger('click');
                                $('.popup-header').html('NOTIFICATION');
                                $('.popup-message').html('Please make sure you entered the correct details');
                                return false;
                            }

                        }
                    });
                        return false;
                });
    }


</script>
<?php include 'footer.php'; ?>



