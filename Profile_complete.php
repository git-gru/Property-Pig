<?php
include 'header2.php';
require './mailer/PHPMailerAutoload.php';
require './mailer/main.php';

if(!isset($_SESSION['user_id']))
{
     echo("<script>location.href = '".$base_url."register.php';</script>");
}else{
    
    /*if user session value*/
    if(!empty($_SESSION['qn']))
      {
            $user_id=$_SESSION['user_id'];
            $obj2   = new commonFunctions();
            $dbh2    = $obj2->dbh;
            $status = false;
            $row    = '';
            $query2  = $dbh2->prepare("insert into pig_user_answer_rel(`user_id`) values(:user_id)");
            $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
            $query2->execute();
            $propertyId = $dbh2->lastInsertId();
            
             /*notification code here*/
            $query2  = $dbh2->prepare("select Name from pig_user where `user_id`=:user_id");
            $query2->bindParam(":user_id",$user_id);
            $query2->execute();
            $rows_user = $query2->fetch(PDO::FETCH_ASSOC);
            $uname='';
            if(!empty($rows_user))
            {
                $uname=$rows_user['Name'];
            }
            $notification="New property added by ".$uname;
            $query2  = $dbh2->prepare("insert into pig_notification (`user_id`,`property_id`,`notification`) values ('1',:propertyId,:notification)");
            $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);
            $query2->bindParam(":notification",$notification,PDO::PARAM_STR);

            $query2->execute();
            /*notification code end here*/
            
            
            if(!empty($_SESSION['qn']))
            {
                foreach ($_SESSION['qn'] as $value) 
                {
                     $obj2   = new commonFunctions();
                     $dbh    = $obj2->dbh;
                     $query  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:question_id,:answer,:type,:user_id,:propertyId)");


                    $query->bindParam(":question_id",$value['question_id'],PDO::PARAM_INT);
                    $query->bindParam(":answer",$value['answer'],PDO::PARAM_STR); 
                    $query->bindParam(":type",$value['type'],PDO::PARAM_INT); 
                    $query->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                    $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);

                     $query->execute();
                     //
                 }
            }
            $secret="pig990";
            if ($secret !== "pig990") { echo "Invalid"; }
            if ($secret == "pig990") 
            {
                $obj2   = new commonFunctions();
                $dbh2    = $obj2->dbh;
                $row2   = '';
                $query2  = $dbh2->prepare("select * from pig_user where user_id=:user_id");
                $query2->bindParam(":user_id",$user_id);
                $query2->execute();
                $counts_questions2=$query2->rowCount();
                $name='';
                $email='';
                if ($query2->rowCount() > 0) 
                {
                    $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                    $name=$rows2['Name'];
                    $email=$rows2['email'];
                }
                $query  = $dbh2->prepare("select * from pig_user_answer where user_id=:user_id and property_id=:propertyId");
                $query->bindParam(":user_id",$user_id);
                $query->bindParam(":propertyId",$propertyId);
                $query->execute();
                $counts_questions=$query->rowCount();
                $k=1;
                $house='';$postcode='';$property_type='';$num_bedrooms='';$num_bathrooms='';$num_receptions='';$tenure='';$town='';$line2='';
                if ($query->rowCount() > 0) 
                {
                    while ($rows = $query->fetch(PDO::FETCH_ASSOC))
                    {
                        if($k==1)
                        {
                            $house=$rows['answer'];
                        }
                        if($k==2)
                        {
                             $postcode=str_replace(" ", "", $rows['answer']);
                        }
                        if($k==3)
                        {
                             $num_bedrooms=$rows['answer'];
                        }
                        if($k==4)
                        {
                             $num_bathrooms=$rows['answer'];
                        }
                        if($k==5)
                        {
                             $num_receptions=$rows['answer'];
                        }
                        if($k==6)
                        {
                             $property_type=$rows['answer'];
                        }
                        if($k==7)
                        {
                             $tenure=$rows['answer'];
                        }
  if($k==8)
                        {
if ($rows['answer']) {
                             $town=" ".$rows['answer'];
                      }  }
if($k==9)
                        {
if ($rows['answer']) {
                             $line2=" ".$rows['answer'];
                      }  }
             
           $k++;
                    }
                    // Get the latitude & longitude of submitted postcode
                    $postcode = urlencode($postcode);
                    // generate a string which searches zoopla, and returns a header which has the propertyid in it
                    $string = $house.", ".$postcode;
                    $zoopla = "http://www.zoopla.co.uk/search/?section=house-prices&q=".urlencode($string);
                    $html = file_get_contents($zoopla, false, $context);
                    //extract the propertyid from the header

                    $array2 = $http_response_header;
                    $zooplacode = explode('yr=',$array2[4]);
                    $propertyid = $zooplacode[1];
if (!$propertyid) {
 $string = $house.$line2.", ".$postcode;
                    $zoopla = "http://www.zoopla.co.uk/search/?section=house-prices&q=".urlencode($string);
                    $html = file_get_contents($zoopla, false, $context);
                    //extract the propertyid from the header
                    $array2 = $http_response_header;
                    $zooplacode = explode('yr=',$array2[4]);
                    $propertyid = $zooplacode[1];

}
if (!$propertyid) {
 $string = $house.$line2.$town.", ".$postcode;
                    $zoopla = "http://www.zoopla.co.uk/search/?section=house-prices&q=".urlencode($string);
                    $html = file_get_contents($zoopla, false, $context);
                    //extract the propertyid from the header
                    $array2 = $http_response_header;
                    $zooplacode = explode('yr=',$array2[4]);
                    $propertyid = $zooplacode[1];

}

                    //zoopla api key
                    $apikey = 'tfj69htyzg344yu2dzv634zc';

                    //get session id for valuation call
                    $api1 = 'http://api.zoopla.co.uk/api/v1/get_session_id?api_key='.$apikey;
                    $get_session_id = file_get_contents($api1);


                    //parse the xml response for the session id, we need this to get valuation
                    $p = xml_parser_create();
                    xml_parse_into_struct($p, $get_session_id, $vals, $index);
                    xml_parser_free($p);
                    $sid = $vals[7][value];
                    $api2 = 'http://api.zoopla.co.uk/api/v1/refine_estimate?property_id='.$propertyid.'&api_key='.$apikey.'&session_id='.$sid."&property_type=".$property_type."&tenure=".$tenure."&num_bedrooms=".$num_bedrooms."&num_bathrooms=".$num_bathrooms."&num_receptions=".$num_receptions;
                    $api2result = file_get_contents($api2);
                    $p2 = xml_parser_create();
                    xml_parse_into_struct($p2, $api2result, $vals, $index);
                    xml_parser_free($p2);

                    //get the information from zoopa and display it in an array for the website & app.
                    $confidence = $vals[3][value];
                    $estimate = $vals[7][value];
                    $lowestimate = $vals[9][value];
                    $upperestimate = $vals[19][value];

                    //remove a percentage from the homes value
                    $percent = 22.5;
                    $obj2   = new commonFunctions();
                    $dbh     = $obj2->dbh;
                    $ppo = explode(".",($lowestimate-(($percent/100)*$lowestimate)));
                    $ppo = $ppo[0];
                    if (!empty($confidence)) 
                    {
                        $output = array($confidence, $lowestimate, $estimate, $ppo);
                        $query  = $dbh->prepare("update pig_user_answer_rel set `rate1`=:rate1,`rate2`=:rate2,`rate3`=:rate3,`rate4`=:rate4 where property_id=:propertyId");

                        $query->bindParam(":rate1",$output[0],PDO::PARAM_STR);
                        $query->bindParam(":rate2",$output[1],PDO::PARAM_STR); 
                        $query->bindParam(":rate3",$output[2],PDO::PARAM_STR); 
                        $query->bindParam(":rate4",$output[3],PDO::PARAM_STR); 
                        $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);
                        $query->execute();

                    } 
                    else 
                    {
                       $output[3]="Invalid Response Received ";
                    }
                    
                    $query  = $dbh2->prepare("select email from admin where id=1");
                    $query->execute();
                    $admin_details = $query->fetch(PDO::FETCH_ASSOC);
                    $admin_email='';
                    if(!empty($admin_details))
                    {
                       $admin_email=$admin_details['email'];
                    }

$subject = 'Property Pig Valuation';
 $message = '<div style="background: #EAECED; width: 100%;">
                                <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                    <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                        <div style="text-align: center;">
                                            <img src="'.$server_url.'logo.png" style="width:150px"/>
                                        </div>
                                        <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                            Hi '.$name.',<br /><br /><br />
We just want to let you know that we have received your property details. Our valuations are usually instant and will be available for you to view on our portal or mobile app.<br /><br />
If for any reason we are unable to offer you an instant valuation, we aim to have it with you in less than 24 hours!
                                            <br /><br />
                                            Warm Regards,<br/><br/>
                                            Property Pig Team

                                        </div>
                                            <div style=" background: #555555; color: #fff; text-align: center; height: 40px; border-radius: 0.3em; padding-top: 22px; font-family: sans-serif; font-size: 13px;">
                                            This is a system generated email.
                                            Please do not reply to it.
                                        </div>
                                    </div>
                                </div>
                            </div>';
$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
$query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:email,:subject,:message,'0')");
$query->bindParam(":email",$email,PDO::PARAM_STR); 
$query->bindParam(":subject",$subject,PDO::PARAM_STR); 
$query->bindParam(":message",$message,PDO::PARAM_STR); 

$query->execute();                    
                    
                    
                    
                }
                
                
                
            }
            
            
            $_SESSION['qn']='';
            $_SESSION['valid_user']='set';
            $_SESSION['user_id']=$user_id;
            
            
    }
    
    /*if user session value*/
    
    
    
    
    
    $obj1   = new commonFunctions();
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $user_id=$_SESSION['user_id'];
    $query  = $dbh->prepare("select * from pig_user where user_id=:user_id");
    $query->bindParam(":user_id",$user_id);
    $query->execute();
    $counts_questions=$query->rowCount();
    if ($query->rowCount() > 0) 
    {
            $rows = $query->fetch(PDO::FETCH_ASSOC);
            if($rows['profile_status']=='1')
            {
               echo"<script>window.location.href = 'https://www.propertypig.co.uk/dashboard.php';</script>";
            }
    }
    else
    {
        $rows=array();
    }
}

?>

<!-----body section---->
<div class="ndiv body-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Complete your profile to sign in</p>
    </div>
    <div class="ndiv primary-center-holder" align="middle">
        <div class="ndiv primary-center-in">

<b>Please complete your details below and press Update Profile.</b>

            <div class="ndiv fileds-section" align="middle">
                <div class="ndiv halfsectionoregister" align="middle">
                    <div class="ndiv files-of-registration" page="register">
                        <form action="javascript:void(0);" id="form_register"   name="form_register"  method="POST" onsubmit="return valid_form_register_complete();">
                            <div class="ndiv filelds-holder">
                                <input class="register-fields" placeholder="Name" id="txtname" value="<?php echo @$rows['Name'];  ?>" name="txtname">
                                <input class="register-fields" pattern="^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$" placeholder="Phone" value="<?php echo @$rows['phoneNumber'];  ?>" id="txtphone" name="txtphone">
                                <input class="register-fields" type="email" placeholder="Email Id"  value="<?php echo @$rows['email'];  ?>" id="txtemail" name="txtemail">
                                <input class="register-fields" placeholder="User Name"  value="<?php echo @$rows['userName'];  ?>" id="txtusername" name="txtusername">
                                <?php
                                if($rows['fb_id']!='' || $rows['tw_id']!='' || $rows['lk_id']!='')
                                {}else{
                                ?>
                                    <input class="register-fields" placeholder="Password"  type="Password" id="txtpassword" name="txtpassword">
                                    <input class="register-fields" placeholder="Password" type="Confirm Password" id="txtcpassword" name="txtcpassword">
                                <?php
                                }
                                ?>
                                
                                <input class="register-fields" placeholder="Address Line 1" value="<?php echo @$rows['Address'];  ?>"  id="txtaddress" name="txtaddress">
                                <input class="register-fields" placeholder="Town/City" id="txtaddress1" name="txtaddress1">
                                <input class="register-fields" placeholder="Post Code"  value="<?php echo @$rows['zipCode'];  ?>"  id="txtzip" name="txtzip">
                                <input value="1" type="hidden" id="profile_status" name="profile_status">

                                <button type="submit" class="get-my-cash-offer">
                                    Update Profile
                                </button>
                                <input  type="hidden" value="<?php echo @$rows['fb_id'];  ?>"  id="fb_id" name="fb_id">
                                <input  type="hidden" value="<?php echo @$rows['tw_id'];  ?>"  id="tw_id" name="tw_id">
                                <input  type="hidden" value="<?php echo @$rows['lk_id'];  ?>"  id="lk_id" name="lk_id">
                            </div>
                        </form>
                    </div>

                </div>
<!--
                <div class="ndiv halfsectionoregister">

                    <div class="ndiv image-with-socio">
                        <div class="ndiv image-with-socio-in">
                            <img class="img-responsive pig-to-sign" alt="pig on signup" src="<?php echo $assets; ?>image/signinpig.png" />

<!--                            <p class="pmzero sign-in-with">SignUp with</p>-->

                            <div class="ndiv sign-up-with">
<!--                                <ul class="bullet_free menu-ul social-ul">
                                     <li >
                                        <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">
                                            <i class="fa fa-facebook" aria-hidden="true">                                             
                                            </i>
                                        </a>
                                    </li>
                                    <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-linkedin" aria-hidden="true"></i></li>
                                </ul>-->
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
<!----/body section----->
<script>
    function valid_form_register_complete()
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
    {}else{
    ?>
    
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
    
    <?php
    }
    ?>
    
    
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
    
    
    
    if($('#fb_id').val()=='')
    {
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
                                    data: $('#form_register').serialize(),
                                    url: base_url+"register_update_proces.php",
                                    success: function (result) {
                                        console.log(result);
                                        $('.yello-menu').trigger('click');
                                        if (result == 1)
                                        {
                                                $('#form_register')[0].reset();
                                                $('.popup-header').html('');
                                                $('.popup-message').html('');
                                                $('.popup-header').html('NOTIFICATION');
                                                $('.popup-message').html('Thankyou for registering.');
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
    });
    
    }
    else
    {
                $.ajax({
                    type: "POST",
                    data: $('#form_register').serialize(),
                    url: base_url+"register_update_proces.php",
                    success: function (result) {
                        console.log(result);
                        $('.yello-menu').trigger('click');
                        
                        if (result == 1)
                        {
                                $('#form_register')[0].reset();
                                $('.popup-header').html('');
                                $('.popup-message').html('');
                                $('.popup-header').html('NOTIFICATION');
                                $('.popup-message').html('Thankyou for registering.');
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
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
                function (response)
                {
                    $.ajax({
                        type: "POST",
                        data: "fb_id=" + response.id + "&name=" + response.first_name + " " + response.last_name + "&email=" + response.email,
                        url: "<?php echo $base_url; ?>fb_process.php",
                        success: function (result) {
                            $('.yello-menu').trigger('click');
                            console.log(result);
                            if (result == 1)
                            {
                                //alert("Thankyou for registering.");

                                $('.popup-header').html('');
                                $('.popup-message').html('');
                                $('.popup-header').html('NOTIFICATION');
                                $('.popup-message').html('Thankyou for registering.');
                                setTimeout(function () {
                                    window.location.href = "<?php echo $base_url . "Profile_complete.php"; ?>";
                                }, 3000);
                                return false;

                            } else
                            {
                                $('.popup-header').html('NOTIFICATION');
                                $('.popup-message').html('Error Found, Try Again');
                                return false;
                            }
//                         alert("please sign up the form");
//                         $('#myModal_list').trigger('click')
                        }
                    });
//document.getElementById('userData').innerHTML = '<p><b>FB ID:</b> '+response.id+'</p><p><b>Name:</b> '+response.first_name+' '+response.last_name+'</p><p><b>Email:</b> '+response.email+'</p><p><b>Gender:</b> '+response.gender+'</p><p><b>Locale:</b> '+response.locale+'</p><p><b>Picture:</b> <img src="'+response.picture.data.url+'"/></p><p><b>FB Profile:</b> <a target="_blank" href="'+response.link+'">click to view profile</a></p>';
                });
    }


</script>
<?php include 'footer.php'; ?>
