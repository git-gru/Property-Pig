<?php  ;
ob_start();
require './mailer/PHPMailerAutoload.php';
include 'admin/json/settings.php'; 
if($_REQUEST)
{  
    $id=$_REQUEST['uid'];
    $obj1   = new commonFunctions();
    $dbh1    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query1  = $dbh1->prepare("insert into pig_property_status(`user_id`,`property_id`,`status_reason`,`status`) values(:userid,:propertyid,:propertyreason,:status)"); 
            
    $query1->bindParam(":userid",$id,PDO::PARAM_INT);
    $query1->bindParam(":propertyid",$_REQUEST['property_id'],PDO::PARAM_INT); 
    $query1->bindParam(":propertyreason",$_REQUEST['reason'],PDO::PARAM_STR); 
    $query1->bindParam(":status",$_REQUEST['status'],PDO::PARAM_INT); 


    $query1->execute();
    $reasonId = $dbh1->lastInsertId();
    if($reasonId) {

        if ($_REQUEST['status'] == 1) { $status = 'Accepted'; } else { $status = 'Rejected'; }
        $subject = 'Offer has been '.$status;
        $message = 'Property '.$_REQUEST[property_id].' has been '.$status.'<p>Notes: '.$_REQUEST[reason];
        $obj2   = new commonFunctions();
        $dbh2    = $obj2->dbh;
        $query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:email,:subject,:message,'0')");
        $query->bindParam(":email",$email,PDO::PARAM_STR); 
        $query->bindParam(":subject",$subject,PDO::PARAM_STR); 
        $query->bindParam(":message",$message,PDO::PARAM_STR); 

        $query->execute(){
        	echo "1";
        }
            else {
                echo "0";
        }
}
?>
