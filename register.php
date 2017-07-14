<?php
include 'header.php';
if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: login-twitter.php");
    } 
}
 ?>
<!-----body section---->
<div class="ndiv body-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Register</p>
    </div>
    <div class="ndiv primary-center-holder">
        <div class="ndiv primary-center-in">

            <div class="ndiv fileds-section">
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

                <div class="ndiv halfsectionoregister">

                    <div class="ndiv image-with-socio">
                      <div class="ndiv files-of-registration" page="register">
                        <form action="javascript:void(0);" id="form_register"   name="form_register"  method="POST" onsubmit="return valid_form_register();">
                            <div class="ndiv filelds-holder">
                                <input class="register-fields" placeholder="Name" id="txtname" name="txtname">
                                <input class="register-fields" pattern="^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$" type="text" placeholder="Phone" id="txtphone" name="txtphone">
                                <input class="register-fields" type="email" placeholder="Email Address" id="txtemail" name="txtemail">
                                <input class="register-fields" placeholder="User Name" type="text" id="txtusername" name="txtusername">
                                <input class="register-fields" placeholder="Password" type="Password" id="txtpassword" name="txtpassword">
                                <input class="register-fields" placeholder="Confirm password" type="Password" id="txtcpassword" name="txtcpassword">
                                <input class="register-fields" placeholder="Address Line 1" id="txtaddress" name="txtaddress">
                                <input class="register-fields" placeholder="Address Line 2" id="txtaddress1" name="txtaddress1">
                                <input class="register-fields" placeholder="Post Code" id="txtzip" name="txtzip">

                                <button type="submit" class="get-my-cash-offer">
                                    SIGN UP
                                </button>

                            </div>
                        </form>
                    </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
<!----/body section---->
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
                console.log(response);
                // Get and display the user profile data
                getFbUserData();
            } else {
                document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
            }
        }, {scope: 'email'});
    }

// Fetch the user profile data from facebook
    function getFbUserData() {
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
                function (response)
                {
                        console.log(response);
                        
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
