<?php
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
                $questionid=$row['id'];
                $querycheck  = $dbh->prepare( "SELECT * FROM `pig_answer` where question_id=:qid " );
                $querycheck->bindParam(":qid",$questionid);
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
    function registeruser($name,$phoneno,$email,$username,$pass,$address,$zipcode,$permanentquestion,$tempquestion,$propertyplace)
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
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `email`=:email" );
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
                                            <img src="https://www.propertypig.co.uk/assets/image/logo.png" style="width:150px"/>
                                        </div>
                                        <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                            Hi '.$name.',<br /><br /><br />
                                            Thank You for registering at Property Pig. We are so glad you joined us!<br />
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

$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
$query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:email,:subject,:message','0')");
$query->bindParam(":email",$email,PDO::PARAM_STR); 
$query->bindParam(":subject",$subject,PDO::PARAM_STR); 
$query->bindParam(":message",$message,PDO::PARAM_STR); 

$query->execute();

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
    function login($email,$pass,$deviceid,$devicename)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $password = base64_encode($pass);
         $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `email`=:email and Password=:password");
        $check_query->bindParam(":email",$email);
        $check_query->bindParam(":password",$password);
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
            $pass      = rand(100000, 9999999);
            $password  = base64_encode($pass);

             $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Password`=:password WHERE `email`=:email" );
            $query1->bindParam(":password",$password,PDO::PARAM_STR);
            $query1->bindParam(":email",$email,PDO::PARAM_STR);
            $query1->execute();

            $subject = 'Password Reset';
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
                                        Here is your login details: <br />
                                        Email : '.$email.'<br />
                                        Password : '.$pass.'<br /><br />
                                        Your new password for the account has been set.<br />
                                        You can login straight away using this auto generated password.<br /><br />
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

$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
 
$query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:email,:subject,:message','0')");
$query->bindParam(":email",$email,PDO::PARAM_STR); 
$query->bindParam(":subject",$subject,PDO::PARAM_STR); 
$query->bindParam(":message",$message,PDO::PARAM_STR); 
$query->execute();
                $status = "true";
        }
        else{
            $status = "nouserfound";
        }
        $data = array(
            'status'   => $status,
        );
        return $data;

    }
    function Sociallogin($loginthrought,$id,$name,$email,$deviceid,$devicename)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        if($loginthrought=='twitter')
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `tw_id`=:id or `userName`=:name");
        else if($loginthrought=='facebook')
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`=:id or `email`=:email");
        else
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `lk_id`=:id or `email`=:email");
        $check_query->bindParam(":id",$id);
        $check_query->bindParam(":email",$email);
        $check_query->bindParam(":name",$name);
        $check_query->execute();
        $count  = $check_query->rowCount();
        if($count!=0)
        {
            if($loginthrought=='twitter')
            $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Name`=:name,`userName`=:name,`devicetoken`=:deviceid,`devicename`=:devicename WHERE `tw_id`=:id" );
            else if($loginthrought=='facebook')
            $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Name`=:name,`userName`=:name,`devicetoken`=:deviceid,`devicename`=:devicename WHERE `fb_id`=:id" );
            else
            $query1    = $dbh->prepare( "UPDATE `pig_user` SET `Name`=:name,`userName`=:name,`devicetoken`=:deviceid,`devicename`=:devicename WHERE `lk_id`=:id" );

         $query1->bindParam(":id",$id);
        $query1->bindParam(":deviceid",$deviceid);
        $query1->bindParam(":name",$name);
        $query1->bindParam(":devicename",$devicename);

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
                if($loginthrought=='twitter')
                    $selquery  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `tw_id`='$id' or `userName`=:name");
                else if($loginthrought=='facebook')
                    $selquery  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`='$id' or `email`=:email");
                else
                    $selquery  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `lk_id`='$id' or `email`=:email");

                $selquery->bindParam(":id",$id);
                $selquery->bindParam(":email",$email);
                $selquery->bindParam(":name",$name);

                $selquery->execute();
                $userde= $selquery->fetch(PDO::FETCH_ASSOC);
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
            'userdetails' =>$userde,
            'propertydetails'=>$Propertydetails
        );
        return $data;
    }
    function SocialRegister($loginthrought,$id,$name,$phoneno,$email,$username,$pass,$address,$zipcode,$propertyplace,$permanentquestion,$tempquestion)
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
        if($loginthrought=='twitter')
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `tw_id`=:id or `email`=:email");
        else if($loginthrought=='facebook')
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`=:id or `email`=:email");
        else
        $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `lk_id`=:id or `phoneNumber`= :phoneno or `email`=:email");
        $check_query->bindParam(":id",$id);
        $check_query->bindParam(":email",$email);
        $check_query->bindParam(":phoneno",$phoneno);
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
            if($loginthrought=='twitter')
            $query  = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Password`,`reviewStatus`,`userStatus`,`tw_id`) values(:name,:username,:phoneno,:email,:zipcode,:address,:password,'0','0',:id)");
            else if($loginthrought=='facebook')
            $query  = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Password`,`reviewStatus`,`userStatus`,`fb_id`) values(:name,:username,:phoneno,:email,:zipcode,:address,:password,'0','0',:id)");
            else
            $query  = $dbh->prepare("insert into pig_user (`Name`,`userName`,`phoneNumber`,`email`,`zipCode`,`Address`,`Password`,`reviewStatus`,`userStatus`,`lk_id`) values(:name,:username,:phoneno,:email,:zipcode,:address,:password,'0','0',:id)");

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
                $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`,`rate1`,`rate2`,`rate3`,`rate4`,`propertyname`) values(:user_id,:rate1,:rate2,:rate3,:rate4,:propertyplace)"); 
            
                $query1->bindParam(":user_id",$user_id,PDO::PARAM_STR);
                $query1->bindParam(":propertyplace",$propertyplace,PDO::PARAM_STR);
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
                        $check_type  = $dbh->prepare( "SELECT type FROM `pig_questions` WHERE `id`='$key'" );
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

                $status    = "true";
                if($loginthrought=='twitter')
                    $select_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `tw_id`=:id");
                else if($loginthrought=='facebook')
                    $select_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `fb_id`=:id");
                else
                    $select_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `lk_id`=:id");
                $select_query->bindParam(":id",$id);

                $select_query->execute();
                $userdetails = $select_query->fetch(PDO::FETCH_ASSOC);

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
            'userdetails' =>$userdetails,
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
            //$query  = $dbh->prepare("UPDATE `pig_user` SET `Name`='$name',`userName`='$username',`phoneNumber`='$phoneno',`email`='$email',`zipCode`='$zipcode',`Address`='$address',`Password`='$password' WHERE `user_id`='$userid'");
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
                $check_query->bindParam(":userid",$userid);
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
    function addnewproperty($userid,$propertyplace,$permanentquestion,$tempquestion)
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
                $query1  = $dbh->prepare("insert into pig_user_answer_rel(`user_id`,`rate1`,`rate2`,`rate3`,`rate4`,`propertyname`) values(:user_id,:rate1,:rate2,:rate3,:rate4,:propertyplace)"); 
            
                $query1->bindParam(":user_id",$user_id,PDO::PARAM_INT);
                $query1->bindParam(":propertyplace",$propertyplace,PDO::PARAM_STR);
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
    function devicedb($userid,$deviceid,$devicename)
   {
       $obj1   = new commonFunctions();
       $dbh    = $obj1->dbh;
       $status = "false";    
       $query  = $dbh->prepare("UPDATE `pig_user` SET `devicetoken`=:deviceid,`devicename`=:devicename WHERE `user_id`=:userid");
       $query->bindParam(":deviceid",$deviceid,PDO::PARAM_STR);
       $query->bindParam(":userid",$userid,PDO::PARAM_INT);
       $query->bindParam(":devicename",$devicename,PDO::PARAM_INT);

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
    function propertyoffer($userid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $property_query  = $dbh->prepare( "SELECT o.*,r.*,r.status as adminstatus,p.status as propertystatus  FROM `pig_property_offer` o join pig_user_answer_rel r on o.offerpropertyId=r.property_id left join pig_property_status p on p.property_id=r.property_id  WHERE o.userid=:userid and o.offerStatus=0");
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
        else
        {
            $status="false";
        }
        $data = array(
            'status'   => $status,
            'offerdetails'=>$Propertydetails
        );
        return $data;
    }
  function offerreplay($offerid,$statuss,$price,$propertid)
  {
      $obj1   = new commonFunctions();
      $dbh    = $obj1->dbh;
      $status = "false";
      $query  = $dbh->prepare("UPDATE `pig_property_offer` SET `offerStatus`=:status WHERE `offerId`=:offerid");

      $query->bindParam(":status",$status,PDO::PARAM_STR);
      $query->bindParam(":offerid",$offerid,PDO::PARAM_STR); 

      if($query->execute())
      {
          if($statuss==1)
          {
          $query  = $dbh->prepare("UPDATE `pig_user_answer_rel` SET `rate4`=:price WHERE `property_id`=:propertid");
          $query->bindParam(":price",$price,PDO::PARAM_INT);
          $query->bindParam(":propertid",$propertid,PDO::PARAM_INT); 
          $query->execute();
          }
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
    function profilecomplete($userid,$name,$phoneno,$email,$username,$address,$zipcode)
    {

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $row    = '';
        $userdetails=array();
        $Propertydetails=array();
        $query  = $dbh->prepare("UPDATE `pig_user` SET `Name`=:name,`userName`=:username,`phoneNumber`=:phoneno,`email`=:email,`zipCode`=:zipcode,`Address`=:address,`Password`='Abcd' WHERE `user_id`=:userid");

        $query->bindParam(":name",$name,PDO::PARAM_STR);
        $query->bindParam(":username",$username,PDO::PARAM_STR);
        $query->bindParam(":email",$email,PDO::PARAM_STR);
        $query->bindParam(":phoneno",$phoneno,PDO::PARAM_STR);
        $query->bindParam(":zipcode",$zipcode,PDO::PARAM_STR);
        $query->bindParam(":address",$address,PDO::PARAM_STR); 
        $query->bindParam(":userid",$userid,PDO::PARAM_INT); 


        if($query->execute())
        {

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
            }
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `user_id`=:userid" );
            $check_query->bindParam(":userid",$userid);

            $check_query->execute();
            $userdetails = $check_query->fetch(PDO::FETCH_ASSOC);
            $status    = "true";
        }
        else{
            $status='false' ;
        }
        $data= array(
            'status'   => $status,
            'userdetails' => $userdetails,
            'propertydetails'=> $Propertydetails
        );
        return $data;

    }
    function Updatespassword($currentpassword,$password,$userid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();
        $status = "false";
        $row    = '';
        $currentpassword = base64_encode($currentpassword);
        $password = base64_encode($password);
        $check_pass  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `user_id`=:userid and Password=:currentpassword " );
        $check_pass->bindParam(":userid",$userid);
        $check_pass->bindParam(":currentpassword",$currentpassword);
        
        $check_pass->execute();
        if($check_pass->rowCount() > 0)
        {
        $query  = $dbh->prepare("UPDATE `pig_user` SET `Password`=:password WHERE `user_id`=:userid");
        $query->bindParam(":password",$password);
        $query->bindParam(":userid",$userid);
        $query->execute();
        if($query->rowCount() > 0)
        {
            $check_query  = $dbh->prepare( "SELECT * FROM `pig_user` WHERE `user_id`=:userid" );
            $check_query->bindParam(":userid",$userid);
            $check_query->execute();
            $userdetails = $check_query->fetch(PDO::FETCH_ASSOC);
            $userdetails['Password']=base64_decode($userdetails['Password']);
            $status    = "true";
        }
        else{
            $status='false' ;
        }
        }
        else{
            $status='incorrectpassword' ;
        }

        $data = array(
            'status'   => $status,
            'userdetails'=>$userdetails
        );
        return $data;
    }

 }


?>
