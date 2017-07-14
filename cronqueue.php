<?php
include 'admin/json/settings.php'; 
require './mailer/PHPMailerAutoload.php';
include './mailer/main.php';
$dt=date("Y-m-d H:i:s");
$obj1   = new commonFunctions();
$dbh    = $obj1->dbh;
$status = false;
$row    = '';
$query  = $dbh->prepare("select * from pig_queue where sent='0' order by id asc limit 5");
$query->execute();
$counts_questions=$query->rowCount();
$i=0;
if ($query->rowCount() > 0) 
{
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'ross.gilmour@propertypig.co.uk';                 // SMTP username
$mail->Password = 'd3m3tr1.';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('noreply@propertypig.co.uk', 'Property Pig');
$mail->addAddress($row['to']);               // Name is optional
$mail->addBCC('info@propertypig.co.uk');
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $row['subject'];
$mail->Body    = $row['message'];
if(!$mail->send()) {
} else {
$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
$status = false;
$row    = $row['id'];
$query2  = $dbh2->prepare("update pig_queue set sent=1, time='$dt' where id='$row' order by id asc limit 10");
$query2->execute();
}
}
}
?>

