<?php 
session_start();
include 'json/settings.php'; 
if($_REQUEST['status']=='sucess' && $_REQUEST['response']=='001') 
{
    $new_password=mt_rand();
    $encryption_key=md5($new_password);
    $obj = new commonFunctions();
    $dbh = $obj->dbh;
    $query  = $dbh->prepare("update admin set password=:encryption_key where id=1");
    $query->bindParam(":encryption_key",$encryption_key,PDO::PARAM_STR); 
    $query->execute();
    $query  = $dbh->prepare("select username,email from admin where id=1");
    $query->execute();
    $admin_details = $query->fetch(PDO::FETCH_ASSOC);
    $admin_email='';
        if(!empty($admin_details))
        {
            $admin_email=$admin_details['email'];
            $subject = "Reset Admin Password Notification";
            $message = "
            <html>
            <head>
            </head>
            <body>
            <p>Dear ".$admin_details['username']."</p>
            <p>We have received your request for the change password. Your new password <b>".$new_password."</b>, </p>
            <br/><br/><br/>
            <p><a href='https://www.propertypig.co.uk/index.php'>Click Here</a</p>

            <p>Regards,</p>
            <p>Property Pig</p>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <noreply@propertypig.co.uk>' . "\r\n";
            mail($admin_email,$subject,$message,$headers); 
            echo("<script>alert('Password reseted sucecssfully');</script>");

        }
    }
    else
    {
         echo("<script>alert('Invalid Request');location.href = 'index.php';</script>");
    }

?>
