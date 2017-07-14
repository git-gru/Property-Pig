<?php
require './mailer/PHPMailerAutoload.php';
require './mailer/main.php';
        $subject = "Verify Your Property Pig E-Mail";
 $message = '<div style="background: #EAECED; width: 100%;">
                                <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                    <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                        <div style="text-align: center;">
                                            <img src="'.$server_url.'logo.png" style="width:150px"/>
                                        </div>
                                        <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                            Hi '.$name.',<br /><br /><br />
                                            Thank You for registering to Property Pig. We are so glad you joined us!<br />
                                            Please click on the link below to activate your account.<br />
                                            Click Here : <a href="'.$server_url.'valid_check.php?id='.$user_id.'">Click Here </a>
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
$mail->addAddress('ross@bookthatlook.com');               // Name is optional
$mail->addBCC('info@propertypig.co.uk');
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Welcome to Property Pig!';
$mail->Body    = $message;
print_r($mail->send());
print $mail->ErrorInfo;
?>
