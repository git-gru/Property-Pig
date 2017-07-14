<?php

// session_start();
// ob_start();

include_once( 'class_functions.php' );
include_once( 'class_question.php' );

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: enctype");
$obj1         = new commonFunctions();
$Question_obj    = new QuestionClass();
extract( $_REQUEST );

switch($request){

    case 'viewQuestion':
        $output = $Question_obj->viewQuestion();
        break;
    case 'login':
        $output = $Question_obj->login($email,$pass,$deviceid,$devicename);
        break;
    case 'register':
        $output = $Question_obj->registeruser($name,$phoneno,$email,$username,$pass,$address,$zipcode,$permanentquestion,$tempquestion,$propertyplace);
        break;
    case 'forgotPassword':
        $output = $Question_obj->forgotPassword($email);
        break;
    case 'Updatespassword':
        $output = $Question_obj->Updatespassword($currentpassword,$password,$userid);
        break;
    case 'Updates':
        $output = $Question_obj->Updates($userid,$name,$phoneno,$email,$username,$pass,$address,$zipcode);
        break;
    case 'profilecomplete':
        $output = $Question_obj->profilecomplete($userid,$name,$phoneno,$email,$username,$address,$zipcode);
        break;
    case 'reason':
        $output = $Question_obj->reason();
        break;
    case 'Sociallogin':
        $output = $Question_obj->Sociallogin($loginthrought,$id,$name,$email,$deviceid,$devicename);
        break;
    case 'SocialRegister':
        $output = $Question_obj->SocialRegister($loginthrought,$id,$name,$phoneno,$email,$username,$pass,$address,$zipcode,$propertyplace,$permanentquestion,$tempquestion);
        break;
    case 'addnewproperty':
        $output = $Question_obj->addnewproperty($userid,$propertyplace,$permanentquestion,$tempquestion);
        break;
    case 'viewproperty':
        $output = $Question_obj->viewproperty($userid);
        break;
    case 'acceptreject':
        $output = $Question_obj->acceptreject($userid,$propertyid,$propertyreason,$status);
        break;
    case 'deletedoc' :
        $output = $Question_obj->deletedoc($docid);
        break;
    case 'devicedb' :
        $output = $Question_obj->devicedb($userid,$deviceid,$devicename);
        break;
    case 'propertyoffer':
        $output = $Question_obj->propertyoffer($userid);
        break;
    case 'offerreplay':
        $output = $Question_obj->offerreplay($offerid,$statuss,$price,$propertid);
        break;
    default:
        $output = 'No Request Type Found!!';
        break;

}

echo json_encode( $output );
