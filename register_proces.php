<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST['txtname']!='' && $_REQUEST['txtphone']!='')
{
    $txtname=$_REQUEST['txtname'];
    $txtphone=$_REQUEST['txtphone'];
    $txtemail=$_REQUEST['txtemail'];
    $txtzip=$_REQUEST['txtzip'];
    $txtaddress=$_REQUEST['txtaddress'];
    $txtaddress1=$_REQUEST['txtaddress1'];
    $txtusername=$_REQUEST['txtusername'];
    $txtpassword=base64_encode($_REQUEST['txtpassword']);

    
    $obj1   = new commonFunctions();$i=1;
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Address1`,`Password`,`reviewStatus`,`userStatus`) values(:txtname,:txtusername,:txtphone,:txtemail,:txtzip,:txtaddress,:txtaddress1,:txtpassword,'0','0')");

    $query->bindParam(":txtname",$txtname,PDO::PARAM_STR);
    $query->bindParam(":txtusername",$txtusername,PDO::PARAM_STR); 
    $query->bindParam(":txtphone",$txtphone,PDO::PARAM_STR); 
    $query->bindParam(":txtemail",$txtemail,PDO::PARAM_STR); 
    $query->bindParam(":txtzip",$txtzip,PDO::PARAM_STR); 
    $query->bindParam(":txtaddress",$txtaddress,PDO::PARAM_STR); 
    $query->bindParam(":txtpassword",$txtpassword,PDO::PARAM_STR); 
    $query->bindParam(":txtaddress1",$txtaddress1,PDO::PARAM_STR); 

    $query->execute();

    $input = $user_id = $dbh->lastInsertId();
    $counts_questions=$query->rowCount();

    function encrypt_string($input)
    {     
        $inputlen = strlen($input);// Counts number characters in string $input
        $randkey = rand(1, 9); // Gets a random number between 1 and 9     
        $i = 0;
        while ($i < $inputlen)        {     
            $inputchr[$i] = (ord($input[$i]) - $randkey);//encrpytion      
            $i++; // For the loop to function
        }     
        //Puts the $inputchr array togtheir in a string with the $randkey add to the end of the string
        $encrypted = implode('.', $inputchr) . '.' . (ord($randkey)+50);
        return $encrypted;
    }
    $encrypted = encrypt_string($input);

    if ($query->rowCount() > 0) 
    {
       
        $subject = "Verify Your Property Pig E-Mail";
        $message = '<div style="background: #EAECED; width: 100%;">
                                <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                    <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                        <div style="text-align: center;">
                                            <img src="https://www.propertypig.co.uk/assets/image/logo.png" style="width:150px"/>
                                        </div>
                                        <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                            Hi '.$txtname.',<br /><br /><br />
                                            Thank You for registering to Property Pig. We are so glad you joined us!<br />
                                            Please click on the link below to activate your account.<br />
                                            Click Here : <a href="'.$base_url.'valid_check.php?id='.$encrypted.'">Click Here</a>
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
    $query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:txtemail,:subject,:message,'0')");
    $query->bindParam(":txtemail",$txtemail,PDO::PARAM_STR); 
    $query->bindParam(":subject",$subject,PDO::PARAM_STR); 
    $query->bindParam(":message",$message,PDO::PARAM_STR); 
    $query->execute();

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <sajansabu999@gmail.com>' . "\r\n";
    mail($txtemail,$subject,$message,$headers);

    echo "1";
    }
    else
    {
        echo "0";
    }
}
?>
