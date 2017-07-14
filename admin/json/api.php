<?php

// session_start();
// ob_start();

include_once( 'class_functions.php' );
include_once( 'class_admin.php' );


$obj1         = new commonFunctions();
$admin_obj    = new adminClass();
extract( $_REQUEST );

switch($request){
    /***** ADMIN ******/
    case 'adminLogin':
        $output = $admin_obj->adminLogin($username,md5($password));
        break;
    case 'addQuestion':
        $output = $admin_obj->addQuestion($question,$answertype,$questiontype,$option_array,$drop_option_array);
        break;
    case 'deletequestion':
        $output = $admin_obj->deletequestion($questionid);
        break;
//
    case 'editQuestion':
        $output = $admin_obj->editQuestion($question,$answertype,$questionid,$questiontype,$option_array);
        break;
    case 'userstatus':
        $output = $admin_obj->userstatus($userid,$type);
        break;
    case 'reviewstatus':
        $output = $admin_obj->reviewstatus($userid);
        break;
    
    case 'property':
        $output = $admin_obj->propertystatus($userid);
        break;
    
    case 'deleterequest':
        $output = $admin_obj->deleterequest($id);
        break;
    case 'reasons':
        $output = $admin_obj->reasons($txtarea);
        break;
    default:
        $output = 'No Request Type Found!!';
        break;

}
if($request=="property")
{
	echo $output;
}
else
{
	echo json_encode( $output );
}