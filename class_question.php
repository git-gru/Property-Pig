<?php
require './mailer/PHPMailerAutoload.php';
include_once('class_functions.php');

class QuestionClass {

    function viewQuestion() {

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT q.*,p.question_type,p.id as typeid FROM `pig_questions` q join pig_question_type p on p.id=q.type order by q.id " );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                 
                $querycheck = $dbh->prepare("select * from pig_answer where question_id=:qid");
                $querycheck->bindParam(":qid",$row['id']);
                $querycheck->execute();

                $row['options']=array();
                while($rowcheck = $querycheck->fetch(PDO::FETCH_ASSOC))
                {
                    $row['options'][]=$rowcheck['answer'];
                }
                $question[]=$row;
            }
        }
        $data = array(
            'status'   => $status,
            'question' => $question
        );
        return $data;
    }
    function registeruser($name,$phoneno,$email,$username,$pass,$address,$zipcode,$permanentquestion,$tempquestion)
    {
//        echo $name,"1",$phoneno,"2",$email,"3",$username,"4",$pass,"5",$address,"6",$zipcode,"7",$permanentquestion,"8",$tempquestion;
        $permanentquestion_array=json_decode($permanentquestion);
        $tempquestion_array=json_decode($tempquestion);
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $row    = '';
        $password = base64_encode($pass);
        

        $check_query = $dbh->prepare("select * from pig_user where email=:email");
        $check_query->bindParam(":email",$email);
        $check_query->execute();

        $count  = $check_query->rowCount();
        if ($count == 0 ) {
            $zoopladetails = $obj1->zoopla_property($permanentquestion_array);
//            echo "$zoopladetails";
//        }
            if($zoopladetails=="Invalid Response Received" || $zoopladetails=='invalid')
            {

                $status="Invalid details";

            }
            else{
//            $status="true";
            $query  = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Password`,`reviewStatus`,`userStatus`) values(:name,:username,:phoneno,:email,:zipcode,:address,:password,'0','0')");

            $query->bindParam(":name",$name,PDO::PARAM_STR);
            $query->bindParam(":username",$username,PDO::PARAM_STR);
            $query->bindParam(":email",$email,PDO::PARAM_STR);
            $query->bindParam(":phoneno",$phoneno,PDO::PARAM_STR);
            $query->bindParam(":zipcode",$zipcode,PDO::PARAM_STR);
            $query->bindParam(":address",$address,PDO::PARAM_STR); 
            $query->bindParam(":password",$password,PDO::PARAM_STR); 


        if($query->execute())
        {
          $user_id = $dbh->lastInsertId();
            $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`,`rate1`,`rate2`,`rate3`,`rate4`) values(:user_id,:rate1,:rate2,:rate3,:rate4)");
            
            $query1->bindParam(":user_id",$user_id,PDO::PARAM_STR);
            $query1->bindParam(":rate1",$zoopladetails[0],PDO::PARAM_STR);
            $query1->bindParam(":rate2",$zoopladetails[1],PDO::PARAM_STR);
            $query1->bindParam(":rate3",$zoopladetails[2],PDO::PARAM_STR);
            $query1->bindParam(":rate4",$zoopladetails[3],PDO::PARAM_STR); 

            if($query1->execute())
            $propertyId = $dbh->lastInsertId();
            foreach( $permanentquestion_array as $val )
            {
                foreach( $val as $key => $item )
                {
                     
                    $check_type = $dbh->prepare("select type from type where id=:key");
                    $check_type->bindParam(":key",$key);
                    $check_type->execute();

                    $type = $check_type->fetch(PDO::FETCH_ASSOC);
                    $typeid=$type['type'];

                    $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:key,:item,:typeid,:user_id,:propertyId)");

                    $query2->bindParam(":key",$key,PDO::PARAM_INT);
                    $query2->bindParam(":item",$item,PDO::PARAM_STR); 
                    $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                    $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                    $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT); 

                    $query2->execute();
                }

            }
            foreach( $tempquestion_array as $val )
            {
                
                $check_type = $dbh->prepare("select type from pig_questions where id=:key");
                $check_type->bindParam(":key",$key);
                $check_type->execute();
                $type = $check_type->fetch(PDO::FETCH_ASSOC);
                $typeid=$type['type'];
                $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:id,:answer,:typeid,:user_id,:propertyId)");

                $query2->bindParam(":id",$val->id,PDO::PARAM_INT);
                $query2->bindParam(":answer",$val->answer,PDO::PARAM_STR); 
                $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);

                $query2->execute();
            }
            $subject = 'Welcome to Property Pig';
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

$mail->addAddress($email);
$mail->addBCC('info@propertypig.co.uk');
$mail->Subject = 'Welcome to Property Pig!';
        if ( $mail == 1 ) {
            $status    = true;
        }
            $status    = "true";
       }
            else{
                $status='false' ;
            }
      }
      }
       else{
           $status='alreadyexit' ;
       }
        $data = array(
            'status'   => $status,
        );
        return $data;
    }
    function login($email,$pass,$deviceid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $password = base64_encode($pass);
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `email`=:email and Password=:password");
        $check_query->bindParam(":email",$email);
        $check_query->bindParam(":password",$password);
        $check_query->execute();
        $count  = $check_query->rowCount();
        if($count!=0)
        {
            $userdetails= $check_query->fetch(PDO::FETCH_ASSOC);
            if($userdetails['userStatus']=="1" || $userdetails['userStatus']==1)
            {
                $status = "true";
                $userid=$userdetails['user_id'];
                $querydevice  = $dbh->prepare("UPDATE `pig_user` SET `devicetoken`=:deviceid WHERE `user_id`=:userid");
                $querydevice->bindParam(":deviceid",$deviceid,PDO::PARAM_STR);
                $querydevice->bindParam(":userid",$userid,PDO::PARAM_INT);
                $querydevice->execute();
                $userdetails['Password']=base64_decode($userdetails['Password']);
                $property_query  = $dbh->prepare( "SELECT r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_user_answer_rel` r left join pig_property_status p on p.property_id=r.property_id  WHERE r.user_id=:userid");
                $property_query->bindParam(":userid",$userid);
                $property_query->execute();


                $Propertydetails=array();
                while($propertyetails= $property_query->fetch(PDO::FETCH_ASSOC))
                {
//                    print_r($propertyetails);echo "hai";
                    $propertyid=$propertyetails['property_id'];
                    $answer_query  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.property_id=:propertyid" );
                    $answer_query->bindParam(":propertyid",$propertyid);
                    $answer_query->execute();
                    $answers=array();
                    while($answerdetails= $answer_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $answers[]=$answerdetails;
                    }
                    $propertyetails['propertyanswer']=$answers;
                    $doc_query  = $dbh->prepare( "SELECT * FROM `pig_documents` where property_id=:propertyid" );
                    $doc_query->bindParam(":propertyid",$propertyid);
                    $doc_query->execute();
                    $doc=array();
                    while($docdetails= $doc_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $docname['img']=$docdetails['document'];
                        $docname['docid']=$docdetails['id'];
                        $doc[]=$docname;
                    }
                    $propertyetails['doc']=$doc;
                    $Propertydetails[]=$propertyetails;
                }


            }
            else{
                $status = "verifyemail";
            }
        }
        else{
            $status = "checkurdeatils";
        }
        $data = array(
            'status'   => $status,
            'userdetails' =>$userdetails,
            'propertydetails'=>$Propertydetails
        );
        return $data;
    }
    function forgotPassword($email)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $query = $dbh->prepare("SELECT * FROM `pig_user` WHERE `email`=:email" );
        $query->bindParam(":email",$email);
        $query->execute();
        if ( $query->rowCount() > 0 ) {

            $row    = $query->fetch(PDO::FETCH_ASSOC);
            $username = $row['userName'];
            $pass      = rand(100000, 999999);
            $password  = base64_encode($pass);

            $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Password`=:password WHERE `email`=:email" );
            $query1->bindParam(":password",$password,PDO::PARAM_STR);
            $query1->bindParam(":email",$email,PDO::PARAM_STR);
            $query1->execute();

            $subject = 'Reset your password on Property Pig';
            $message = '<div style="background: #EAECED; width: 100%;">
                            <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                    <div style="text-align: center;" >
                                    <div style="width:100px !important; height:100px !important;margin:auto">
                                       <img src="'.$server_url.'logo.png" style="width:100%; height:100%"/>
                                    </div></div>
                                    <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                        Dear '.$username.',<br /><br /><br />
                                        <b>Welcome to Property Pig</b><br /><br />
                                        Here is your login info : <br />
                                        Email : '.$email.'<br />
                                        Password : '.$pass.'<br /><br />
                                        Your new password for the account has been set.<br />
                                        You can able to logged in to your current account using this auto generated keyword as password<br /><br />
                                        Thanks<br/>
                                        Property Pig
                                    </div>
                                        <div style="background: #555555; color: #fff; text-align: center; height: 40px; border-radius: 0.3em; padding-top: 22px; font-family: sans-serif; font-size: 13px;">
                                        This is a system generated email.
                                        Please do not reply to it.
                                    </div>
                                </div>
                            </div>
                        </div>';
$mail->addAddress($email);               // Name is optional
$mail->addBCC('info@propertypig.co.uk');
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Welcome to Property Pig!';
$mail->Body    = $message;
if(!$mail->send()) {
            $status = "nouserfound";
}
        }
        else{
            $status = "nouserfound";
        }
        $data = array(
            'status'   => $status,
        );
        return $data;

    }
    function Sociallogin($id,$name,$email,$deviceid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        if($email==null)
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`=:id");
        else
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `email`=:email and `fb_id`=:id");
        $check_query->bindParam(":id",$id);
        $check_query->bindParam(":email",$email);
        $check_query->execute();
        $count  = $check_query->rowCount();
        if($count!=0)
        {
            $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Name`=:name,`userName`=:name,`devicetoken`=:deviceid WHERE `fb_id`=:id" );
            $query1->bindParam(":name",$name,PDO::PARAM_STR);
            $query1->bindParam(":deviceid",$deviceid,PDO::PARAM_INT);
            $query1->bindParam(":id",$id,PDO::PARAM_STR); 

            $query1->execute();
            $userdetails= $check_query->fetch(PDO::FETCH_ASSOC);

            if($userdetails['userStatus']=="1" || $userdetails['userStatus']==1)
            {
                $status = "true";
                $userid=$userdetails['user_id'];
                $userdetails['Password']=base64_decode($userdetails['Password']);
                $property_query  = $dbh->prepare( "SELECT r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_user_answer_rel` r left join pig_property_status p on p.property_id=r.property_id  WHERE r.user_id=:userid");
                $property_query->bindParam(":userid",$userid);
                $property_query->execute();
                $Propertydetails=array();
                while($propertyetails= $property_query->fetch(PDO::FETCH_ASSOC))
                {
//                    print_r($propertyetails);echo "hai";
                    $propertyid=$propertyetails['property_id'];
                    $answer_query  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.property_id=:propertyid" );
                    $answer_query->bindParam(":propertyid",$propertyid);
                    $answer_query->execute();
                    $answers=array();
                    while($answerdetails= $answer_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $answers[]=$answerdetails;
                    }
                    $propertyetails['propertyanswer']=$answers;
                    $doc_query  = $dbh->prepare( "SELECT * FROM `pig_documents` where property_id==:propertyid" );
                    $doc_query->bindParam(":propertyid",$propertyid);
                    $doc_query->execute();
                    $doc=array();
                    while($docdetails= $doc_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $docname['img']=$docdetails['document'];
                        $docname['docid']=$docdetails['id'];
                        $doc[]=$docname;
                    }
                    $propertyetails['doc']=$doc;
                    $Propertydetails[]=$propertyetails;
                }


            }
            else{
                $status = "verifyemail";
            }
        }
        else{
            $status = "checkurdeatils";
        }
        $data = array(
            'status'   => $status,
            'userdetails' =>$userdetails,
            'propertydetails'=>$Propertydetails
        );
        return $data;
    }
    function SocialRegister($id,$name,$phoneno,$email,$username,$pass,$address,$zipcode,$permanentquestion,$tempquestion)
    {
        $permanentquestion_array=json_decode($permanentquestion);
        $tempquestion_array=json_decode($tempquestion);
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $pass      = rand(100000, 999999);
        $password  = base64_encode($pass);
        $status = "false";
        $row    = '';
        $password = base64_encode($pass);
        if($email==null)
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`=:id");
        else
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `email`=:email and `fb_id`=:id");
        $check_query->bindParam(":id",$id);
        $check_query->bindParam(":email",$email);
        $check_query->execute();
        $count  = $check_query->rowCount();
        if ($count == 0 ) {
            $zoopladetails = $obj1->zoopla_property($permanentquestion_array);
//            echo "$zoopladetails";
//        }
            if($zoopladetails=="Invalid Response Received" || $zoopladetails=='invalid')
            {

                $status="Invalid details";

            }
            else{
            $query  = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Password`,`reviewStatus`,`userStatus`,`fb_id`) values(:name,:username,:phoneno,:email,:zipcode,:address,:password,'0','0',:id)");

            $query->bindParam(":name",$name,PDO::PARAM_STR);
            $query->bindParam(":username",$username,PDO::PARAM_STR);
            $query->bindParam(":phoneno",$phoneno,PDO::PARAM_STR);
            $query->bindParam(":email",$email,PDO::PARAM_STR);
            $query->bindParam(":zipcode",$zipcode,PDO::PARAM_STR);
            $query->bindParam(":address",$address,PDO::PARAM_STR); 
            $query->bindParam(":id",$id,PDO::PARAM_STR); 
            $query->bindParam(":password",$password,PDO::PARAM_STR); 

            if($query->execute())
            {
                $user_id = $dbh->lastInsertId();
//                $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`) values('$user_id')");
                $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`,`rate1`,`rate2`,`rate3`,`rate4`) values(:user_id,:rate1,:rate2,:rate3,:rate4)");
            
                $query1->bindParam(":user_id",$user_id,PDO::PARAM_STR);
                $query1->bindParam(":rate1",$zoopladetails[0],PDO::PARAM_STR);
                $query1->bindParam(":rate2",$zoopladetails[1],PDO::PARAM_STR);
                $query1->bindParam(":rate3",$zoopladetails[2],PDO::PARAM_STR);
                $query1->bindParam(":rate4",$zoopladetails[3],PDO::PARAM_STR); 

                $query1->execute();
                $propertyId = $dbh->lastInsertId();
                foreach( $permanentquestion_array as $val )
                {
                    foreach( $val as $key => $item )
                    {
                        $check_type  = $dbh->prepare( "SELECT type FROM `pig_questions` WHERE `id`=:key" );
                        $check_type->bindParam(":key",$key);
                        $check_type->execute();
                        $type = $check_type->fetch(PDO::FETCH_ASSOC);
                        $typeid=$type['type'];
                        
                        $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:key,:item,:typeid,:user_id,:propertyId)");

                        $query2->bindParam(":key",$key,PDO::PARAM_INT);
                        $query2->bindParam(":item",$item,PDO::PARAM_STR); 
                        $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                        $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                        $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT); 

                        $query2->execute();
                    }

                }
                foreach( $tempquestion_array as $val )
                {
                    $check_type  = $dbh->prepare( "SELECT type FROM `pig_questions` WHERE `id`=:key" );
                    $check_type->bindParam(":key",$key);
                    $check_type->execute();
                    $type = $check_type->fetch(PDO::FETCH_ASSOC);
                    $typeid=$type['type'];
                    $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:id,:answer,:typeid,:user_id,:propertyId)");

                    $query2->bindParam(":id",$val->id,PDO::PARAM_INT);
                    $query2->bindParam(":answer",$val->answer,PDO::PARAM_STR); 
                    $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                    $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                    $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);

                    $query2->execute();
                }
                $subject = 'Welcome to Property pig';
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

                $from = 'jibin.bs07@gmail.com';
                $to   = $email;
                $mail = $obj1->mailFunction($from,$to,$subject,$message);
                if ( $mail == 1 ) {
                    $status    = true;
                }
                $status    = "true";
            }
            else{
                $status='false' ;
            }
           }
        }
        else{
            $status='alreadyexit' ;
        }
        $data = array(
            'status'   => $status,
        );
        return $data;
    }
    function reason()
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $reasons=array();
        $reason_query  = $dbh->prepare( "SELECT * FROM `pig_reasons`");
        if($reason_query->execute())
        {

        while($reasondetails= $reason_query->fetch(PDO::FETCH_ASSOC))
        {
            $reasons[]=$reasondetails;
        }
            $status = "true";
        }
        $data = array(
            'status'   => $status,
            'reason' =>$reasons
        );
        return $data;
    }
    function Updates($userid,$name,$phoneno,$email,$username,$pass,$address,$zipcode)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $row    = '';
        $password = base64_encode($pass);
            $query  = $dbh->prepare("UPDATE `pig_user` SET `Name`=:name,`userName`=:username,`phoneNumber`=:phoneno,`email`=:email,`zipCode`=:zipcode,`Address`=:address,`Password`=:password WHERE `user_id`=:userid");
            $query->bindParam(":name",$name,PDO::PARAM_STR);
            $query->bindParam(":username",$username,PDO::PARAM_STR);
            $query->bindParam(":email",$email,PDO::PARAM_STR);
            $query->bindParam(":phoneno",$phoneno,PDO::PARAM_STR);
            $query->bindParam(":zipcode",$zipcode,PDO::PARAM_STR);
            $query->bindParam(":address",$address,PDO::PARAM_STR); 
            $query->bindParam(":password",$password,PDO::PARAM_STR); 
            $query->bindParam(":userid",$userid,PDO::PARAM_INT); 

            if($query->execute())
            {
                $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `user_id`=:userid" );
                $check_query->bindParam(":user_id",$user_id);

                $check_query->execute();
                $userdetails = $check_query->fetch(PDO::FETCH_ASSOC);
                $userdetails['Password']=base64_decode($userdetails['Password']);
                $status    = "true";
            }
            else{
                $status='false' ;
            }

        $data = array(
            'status'   => $status,
            'userdetails'=>$userdetails
        );
        return $data;
    }
    function addnewproperty($userid,$permanentquestion,$tempquestion)
    {
        $permanentquestion_array=json_decode($permanentquestion);
        $tempquestion_array=json_decode($tempquestion);
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $Propertydetails=array();
        $zoopladetails = $obj1->zoopla_property($permanentquestion_array);
//        echo $zoopladetails;
        if($zoopladetails=="Invalid Response Received" || $zoopladetails=='invalid')
        {

            $status="Invalid details";

        }
        else{
//            $status="true";
                $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`,`rate1`,`rate2`,`rate3`,`rate4`) values(:user_id,:rate1,:rate2,:rate3,:rate4)");
            
                $query1->bindParam(":user_id",$user_id,PDO::PARAM_INT);
                $query1->bindParam(":rate1",$zoopladetails[0],PDO::PARAM_STR);
                $query1->bindParam(":rate2",$zoopladetails[1],PDO::PARAM_STR);
                $query1->bindParam(":rate3",$zoopladetails[2],PDO::PARAM_STR);
                $query1->bindParam(":rate4",$zoopladetails[3],PDO::PARAM_STR);  

                if($query1->execute())
                {
                    $propertyId = $dbh->lastInsertId();
                    foreach( $permanentquestion_array as $val )
                    {
                        foreach( $val as $key => $item )
                        {
                            $check_type  = $dbh->prepare( "SELECT type FROM `pig_questions` WHERE `id`=:key" );
                            $check_type->bindParam(":key",$key);
                            $check_type->execute();
                            $type = $check_type->fetch(PDO::FETCH_ASSOC);
                            $typeid=$type['type'];

                            $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:key,:item,:typeid,:user_id,:propertyId)");

                            $query2->bindParam(":key",$key,PDO::PARAM_INT);
                            $query2->bindParam(":item",$item,PDO::PARAM_STR); 
                            $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                            $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                            $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);


                            $query2->execute();
                        }

                    }
                    foreach( $tempquestion_array as $val )
                    {
                        $check_type  = $dbh->prepare( "SELECT type FROM `pig_questions` WHERE `id`=:key" );
                        $check_type->bindParam(":key",$key);
                        $check_type->execute();
                        $type = $check_type->fetch(PDO::FETCH_ASSOC);
                        $typeid=$type['type'];
                        $query2  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:id,:answer,:typeid,:user_id,:propertyId)");

                        $query2->bindParam(":id",$val->id,PDO::PARAM_INT);
                        $query2->bindParam(":answer",$val->answer,PDO::PARAM_STR); 
                        $query2->bindParam(":typeid",$typeid,PDO::PARAM_INT); 
                        $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                        $query2->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);

                        $query2->execute();
                    }
                        $property_query  = $dbh->prepare( "SELECT r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_user_answer_rel` r left join pig_property_status p on p.property_id=r.property_id  WHERE r.user_id=:userid");
                        $property_query->bindParam(":userid",$userid);
                        $property_query->execute();
                    while($propertyetails= $property_query->fetch(PDO::FETCH_ASSOC))
                    {
            //                    print_r($propertyetails);echo "hai";
                        $propertyid=$propertyetails['property_id'];
                        $answer_query  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.property_id=:propertyid" );
                        $answer_query->bindParam(":propertyid",$propertyid);
                        $answer_query->execute();
                        $answers=array();
                        while($answerdetails= $answer_query->fetch(PDO::FETCH_ASSOC))
                        {
                            $answers[]=$answerdetails;
                        }
                        $propertyetails['propertyanswer']=$answers;
                        $doc_query  = $dbh->prepare( "SELECT * FROM `pig_documents` where property_id=:propertyid" );
                        $doc_query->bindParam(":propertyid",$propertyid);
                        $doc_query->execute();
                        $doc=array();
                        while($docdetails= $doc_query->fetch(PDO::FETCH_ASSOC))
                        {
                            $docname['img']=$docdetails['document'];
                            $docname['docid']=$docdetails['id'];
                            $doc[]=$docname;
                        }
                        $propertyetails['doc']=$doc;
                        $Propertydetails[]=$propertyetails;
                    }
                        $status="true";
               }
               else{
                    $status="false";
               }
        }

        $data = array(
            'status'   => $status,
            'propertydetails'=>$Propertydetails
        );
        return $data;
    }
    function viewproperty($userid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";

        $property_query  = $dbh->prepare( "SELECT r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_user_answer_rel` r left join pig_property_status p on p.property_id=r.property_id  WHERE r.user_id=:userid");
            $property_query->bindParam(":userid",$userid);
        if($property_query->execute()){
            $Propertydetails=array();
            while($propertyetails= $property_query->fetch(PDO::FETCH_ASSOC))
            {
//                    print_r($propertyetails);echo "hai";
                $propertyid=$propertyetails['property_id'];
                $answer_query  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.property_id=:propertyid" );
                $answer_query->bindParam(":propertyid",$propertyid);
                $answer_query->execute();
                $answers=array();
                while($answerdetails= $answer_query->fetch(PDO::FETCH_ASSOC))
                {
                    $answers[]=$answerdetails;
                }
                $propertyetails['propertyanswer']=$answers;
                $doc_query  = $dbh->prepare( "SELECT * FROM `pig_documents` where property_id=:propertyid" );
                $doc_query->bindParam(":propertyid",$propertyid);
                $doc_query->execute();
                $doc=array();
                while($docdetails= $doc_query->fetch(PDO::FETCH_ASSOC))
                {
                    $docname['img']=$docdetails['document'];
                    $docname['docid']=$docdetails['id'];
                    $doc[]=$docname;
                }
                $propertyetails['doc']=$doc;
                $Propertydetails[]=$propertyetails;
            }
            $status="true";
            }
        else{
            $status="false";
        }
        $data = array(
            'status'   => $status,
            'propertydetails'=>$Propertydetails
        );
        return $data;
    }
    function acceptreject($userid,$propertyid,$propertyreason,$status1)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $Propertydetails=array();
        $query1  = $dbh->prepare("insert into pig_property_status(`user_id`,`property_id`,`status_reason`,`status`) values(:userid,:propertyid,:propertyreason,:status1)"); 
            
        $query1->bindParam(":userid",$userid,PDO::PARAM_INT);
        $query1->bindParam(":propertyid",$propertyid,PDO::PARAM_INT); 
        $query1->bindParam(":propertyreason",$propertyreason,PDO::PARAM_STR); 
        $query1->bindParam(":status1",$status1,PDO::PARAM_INT); 


        if($query1->execute())
        {
            $property_query  = $dbh->prepare( "SELECT r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_user_answer_rel` r left join pig_property_status p on p.property_id=r.property_id  WHERE r.user_id=:userid");
            $property_query->bindParam(":userid",$userid);
            if($property_query->execute()){
//                $Propertydetails=array();
                while($propertyetails= $property_query->fetch(PDO::FETCH_ASSOC))
                {
//                    print_r($propertyetails);echo "hai";
                    $propertyid=$propertyetails['property_id'];
                    $answer_query  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.property_id=:propertyid" );
                    $answer_query->bindParam(":propertyid",$propertyid);
                    $answer_query->execute();
                    $answers=array();
                    while($answerdetails= $answer_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $answers[]=$answerdetails;
                    }
                    $propertyetails['propertyanswer']=$answers;
                    $doc_query  = $dbh->prepare( "SELECT * FROM `pig_documents` where property_id=:propertyid" );
                    $doc_query->bindParam(":propertyid",$propertyid);
                    $doc_query->execute();
                    $doc=array();
                    while($docdetails= $doc_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $docname['img']=$docdetails['document'];
                        $docname['docid']=$docdetails['id'];
                        $doc[]=$docname;
                    }
                    $propertyetails['doc']=$doc;
                    $Propertydetails[]=$propertyetails;
                }
                $status="true";
            }
        }
        else{
            $status="false";
        }
        $data = array(
            'status'   => $status,
            'propertydetails'=>$Propertydetails
        );
        return $data;

    }
    function deletedoc($docid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $query  = $dbh->prepare("Delete from pig_documents WHERE `id`=:docid");
        $query->bindParam(":docid",$docid);

        if($query->execute())
        {
                $status="true";
        }
        else
        {
            $status="false";
        }
        $data = array(
            'status'   => $status
        );
        return $data;
    }
   function devicedb($userid,$deviceid)
   {
       $obj1   = new commonFunctions();
       $dbh    = $obj1->dbh;
       $status = "false";
       $query  = $dbh->prepare("UPDATE `pig_user` SET `devicetoken`=:deviceid WHERE `user_id`=:userid");
       $query->bindParam(":deviceid",$deviceid,PDO::PARAM_STR);
       $query->bindParam(":userid",$userid,PDO::PARAM_INT);

       if($query->execute())
       {
           $status="true";
       }
       else
       {
           $status="false";
       }
       $data = array(
           'status'   => $status
       );
       return $data;
   }

 }


?>
