<?php 
ob_start();
session_start();
include 'admin/json/settings.php'; 
ini_set('display_errors', 1);

function decrypt_string($input)
{
    $input_count = strlen($input); 
    $dec = explode(".", $input);// splits up the string to any array
    $x = count($dec);
    $y = $x-1;// To get the key of the last bit in the array  
    $calc = $dec[$y]-50;
    $randkey = chr($calc);// works out the randkey number 
    $i = 0; 
    while ($i < $y)
    { 
        $array[$i] = $dec[$i]+$randkey; // Works out the ascii characters actual numbers
        $real .= chr($array[$i]); //The actual decryption 
        $i++;
    }; 
    $input = $real;
    return $input;
}


if($_REQUEST['id']!='')
{
    $input=$_REQUEST['id'];
    $id = decrypt_string($input);
    
    $obj1   = new commonFunctions();$i=1;
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query  = $dbh->prepare("update pig_user set userStatus=1 where user_id=:id");
    $query->bindParam(":id",$id,PDO::PARAM_INT);
    $query->execute();
    $counts_questions=$query->rowCount();
    $counts_questions=2;
    if ($counts_questions > 0) 
    {
        //echo "<pre>";print_r($_SESSION['qn']);
       echo "You have successfully completed the activation process. You may login to see the status of your property value request.";
      
       
       if(!empty($_SESSION['qn']))
       {
            $obj3   = new commonFunctions();
            $dbh1    = $obj3->dbh;
            $query1  = $dbh1->prepare("insert into pig_user_answer_rel(`user_id`) values(:id)");
            $query1->bindParam(":id",$id,PDO::PARAM_INT); 
            $query1->execute();
            $propertyId = $dbh1->lastInsertId();
            
            /*notification code here*/
            $query1  = $dbh1->prepare("select Name from pig_user where user_id=:id");
            $query1->bindParam(":id",$id,PDO::PARAM_INT);
            $query1->execute();
            $rows_user = $query1->fetch(PDO::FETCH_ASSOC);
            $uname='';
            if(!empty($rows_user))
            {
                $uname=$rows_user['Name'];
            }
            $notification="New property added by ".$uname;
            $query1  = $dbh1->prepare("insert into pig_notification (`user_id`,`property_id`,`notification`) values ('1',:propertyId,:notification)");
            $query1->bindParam(":propertyId",$propertyId,PDO::PARAM_INT); 
            $query1->bindParam(":notification",$notification,PDO::PARAM_STR); 
            $query1->execute();
            
        /*notification code end here*/
           foreach ($_SESSION['qn'] as $value) 
           {
           
                $obj2   = new commonFunctions();
                $dbh    = $obj2->dbh;
                $query  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:question_id,:answer,:type,:id,:propertyId)"); 
            
                $query->bindParam(":question_id",$value['question_id'],PDO::PARAM_STR);
                $query->bindParam(":answer",$value['answer'],PDO::PARAM_STR);
                $query->bindParam(":type",$value['type'],PDO::PARAM_STR);
                $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);
                $query->bindParam(":id",$id,PDO::PARAM_INT); 


                $query->execute();
                //
            }
            $secret="pig990";
            if ($secret !== "pig990") { echo "Invalid"; }
            if ($secret == "pig990") 
            {
                $obj2   = new commonFunctions();
                $dbh2    = $obj2->dbh;
                $row2   = '';
                $query2  = $dbh2->prepare("select * from pig_user where user_id=:id");
                $query2->bindParam(":id",$id);
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
                $query  = $dbh2->prepare("select * from pig_user_answer where user_id=:id and property_id=:propertyId");
                $query->bindParam(":propertyId",$propertyId);
                $query->bindParam(":id",$id);
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

                    $ppo = explode(".",($lowestimate-(($percent/100)*$lowestimate)));
                    $ppo = $ppo[0];
                    if (!empty($confidence)) 
                    {
                        $output = array($confidence, $lowestimate, $estimate, $ppo);
                        $query  = $dbh->prepare("update pig_user_answer_rel set `rate1`=:rate1,`rate2`=:rate2,`rate3`=:rate3,`rate4`=:rate4 where property_id=:propertyId");
                        $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);
                        $query->bindParam(":rate1",$output[0],PDO::PARAM_STR);
                        $query->bindParam(":rate2",$output[1],PDO::PARAM_STR);
                        $query->bindParam(":rate3",$output[2],PDO::PARAM_STR);
                        $query->bindParam(":rate4",$output[3],PDO::PARAM_STR); 
                        $query->execute();

                    } 
                    else 
                    {
                       $output[3]="Invalid Response Received ";
                    }
                    
                    $query  = $dbh->prepare("select email from admin where id=1");
                    $query->execute();
                    $admin_details = $query->fetch(PDO::FETCH_ASSOC);
                    $admin_email='';
                    if(!empty($admin_details))
                    {
                       $admin_email=$admin_details['email'];
                    }


   $subject = "Property details received";
$message = '<div style="background: #EAECED; width: 100%;">
                                <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                    <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                        <div style="text-align: center;">
                                            <img src="https://www.propertypig.co.uk/assets/image/logo.png" style="width:150px"/>
                                        </div>
                                        <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
                                            Hi '.$name.',<br /><br /><br />
                                            Thankyou for submitting your property! Most valuations are available instantly but if not, we aim to get it to you in less than 24 hours. Please check the app or website for your offer.<br />
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
                $_SESSION['user_id']=$id;
                sleep(3);
                echo("<script>location.href = '".$base_url."dashboard.php';</script>");
                ob_end_flush();
       }
       else
       {
           echo "Some error occured!!!";
           sleep(5);
           echo("<script>location.href = '".$base_url."signin.php';</script>");
           ob_end_flush();
           die();
       }
       

    }
    else
    {
       echo "Some error occured!!!";
       die();
    }
}

?>
