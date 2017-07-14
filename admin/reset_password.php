<?php 
session_start();
include 'json/settings.php'; 
$obj   = new commonFunctions();
$dbh    = $obj->dbh;
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
    <p>We have received your request for the change password. Please Follow the link, </p>
    <br/><br/>
    <p><a href='https://www.propertypig.co.uk/admin/reset_pwd.php?status=sucess&response=001'>Click Here</a></p>

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
    echo "1";
}
else
{
    echo "0";
}
       

?>
