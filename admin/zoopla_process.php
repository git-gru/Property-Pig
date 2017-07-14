<?php 
session_start();
include 'json/settings.php'; 
$user_id=$_REQUEST['user_id'];
$property_id=$_REQUEST['property_id'];
ini_set("display_errors", "0"); error_reporting(0);

// FORCE THE USER TO USE SSL
//if($_SERVER["HTTPS"] != "on")
//{
//    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
//    exit();
//}

$secret="pig990";
// SECURE THE API WITH A PASSWORD
if ($secret !== "pig990") { echo "Invalid"; }
if ($secret == "pig990") 
{
//    $house = $_REQUEST['house'];
//    $postcode = str_replace(" ", "", $_REQUEST['postcode']);
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
       
        $obj1   = new commonFunctions();$i=1;
        $dbh    = $obj1->dbh;
        $row    = '';
        $query  = $dbh->prepare("select * from pig_user_answer where user_id=:user_id and property_id=:property_id");
        $query->bindParam(":user_id",$user_id);
        $query->bindParam(":property_id",$property_id);
        $query->execute();
        $counts_questions=$query->rowCount();
        $k=1;
        $house='';$postcode='';$property_type='';$num_bedrooms='';$num_bathrooms='';$num_receptions='';$tenure='';$town='';
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
if (!$rows['answer']) {
                             $town=" ".$rows['answer'];
                      }  }
if($k==9)
                        {
if (!$rows['answer']) {
                             $line2=" ".$rows['answer'];
                      }  }

                        $k++;
                    }
                    //die($postcode);
                    // Get the latitude & longitude of submitted postcode
                    $postcode = urlencode($postcode);
                    // generate a string which searches zoopla, and returns a header which has the propertyid in it
                    $string = $house.$town.", ".$postcode;
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
 $string = $house.$line2.$town", ".$postcode;
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
                $query  = $dbh->prepare("update pig_user_answer_rel set `rate1`=:rate1,`rate2`=:rate2,`rate3`=:rate3,`rate4`=:rate4 where property_id=:property_id");
                $query->bindParam(":property_id",$property_id,PDO::PARAM_STR);
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
 $query = $dbh2->prepare("insert into pig_queue (`to`,`subject`,`message`,`sent`) values(:email,:subject,:message','0')");
  $query->bindParam(":email",$email,PDO::PARAM_STR);
                $query->bindParam(":subject",$subject,PDO::PARAM_STR);
                $query->bindParam(":message",$message,PDO::PARAM_STR);           
$query->execute();
               echo @$output[3];
               
    }
}

?>
