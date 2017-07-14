<?php
session_start();
include 'admin/json/settings.php';
$assets=$base_url."assets/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Property pig</title>
<!-------Bootstrap Plugin---------->
<link rel="stylesheet" href="<?php echo $assets; ?>css/bootstrap.min.css" />
<!-------UI Developers Costimised Css Tool------> 
<link rel="stylesheet" href="<?php echo $assets; ?>css/break_bs_v_1_2.css" />
<!------- font awsome / font css ------>
<link rel="stylesheet" href="<?php echo $assets; ?>css/font-awesome.min.css" />
<link href="https://fonts.googleapis.com/css?family=Nunito:400,700,900" rel="stylesheet"  />
<link href="https://fonts.googleapis.com/css?family=Baloo+Bhaina" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oxygen:700" rel="stylesheet"/>
<!------Styles css-------->
<link rel="stylesheet" href="<?php echo $assets; ?>css/style_property_pig.css" />
<link rel="stylesheet" href="<?php echo $assets; ?>css/custom.css" />
<style>
    #wait{overflow-x: hidden;width: 100%;min-height: 2000px;background-color: rgba(0, 0, 0, 0.29);z-index: 9998;position: absolute;}
    #wait img{padding-left: 43%;padding-top: 20%;}
    /*.home-icon-holder.active { background-color: #3a0256;}*/
	.topnav{
		font-family: Arial, Helvetica, sans-serif;
		font-size:15px;
		font-weight:bold;	
		color:#fff;
	}
	
	.topnav:hover{
		color:#f9e400;
	}
	
	.topnav-active{
		color:#f9e400 !important;
	}
        .for-side-c{
            
            max-width: 100%;
        }
        .for-side-i{float: right;
            width: calc(100% - 60px);
            padding: 0px 30px;}
        .back_button{    margin-top: 20px;
    width: 60px;
    height: 25px;
    border-radius: 14px;}
</style>
</head>
    <body>
        <div  id="wait" style="display: none;"><img src="gif.gif"></div>
	<div class="ndiv page-holder">
    	
        <!-----header section---->
    	<div class="ndiv header-section ">
        	<div class="ndiv primary-center-holder for-side-c">
                    <button id="admin_url" class="back_button" style="float: left;">Back</button>
            	<div class="ndiv primary-center-in for-side-i">
                    
                    <a href="<?php echo $base_url; ?>">
                        <div class="ndiv logo-holder">
                            <img class="imgres logoclass" src="<?php echo $assets; ?>image/logo.png" alt="property pig logo" />
                        </div>
                    </a>
                    
                    <div class="ndiv top-menu-holder">
                        <ul class="menu-ul top-menu">
                            
                             <?php
                            
                            if(isset($_SESSION['user_id']))
                            {
                                $obj2 = new commonFunctions();
                                $dbh2 = $obj2->dbh;
                                $user_id=$_SESSION['user_id'];
                                $query2 = $dbh2->prepare("select count(id) as count from pig_notification where `user_id`=:user_id and status=0");
                                $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 

                                $query2->execute();
                                if ($query2->rowCount() > 0) {
                                    $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                                    if($rows2['count']!=0)
                                    {
                                        $not_count= $rows2['count'];
                                    }
                                    else
                                    {
                                        $not_count='';
                                    }
                                }
                                else
                                {
                                    $not_count='';
                                }
                            ?> 
                             
                            <li class="already-member" style="color:#fff;">
                                        <a class="topnav" href="logout.php">Signout</a>
                                    </li>
                           
                           
                            <?php } else{
                             
                              ?> 
                                 <li class="already-member">
                                Already Register? <button id="login">Login</button> 
                            </li>    
                                
                            <?php  } ?>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
    	<!----/header section----->
        
        
        
         <!----------popup--------->
        
        <div class="ndiv popup-holder">
        
        	<div class="ndiv popup-center"><!---------sst the width of popup panel------->
            	<div class="ndiv popup-in">
                
                	<div class="ndiv popup-single sample1" id="target-id"><!--for call pop up use the functiomn $"target-id").popup('closeit');-->
                            <p class="popup-header"></p><!------popup header----->
                            <p class="popup-message"></p><!------popup message----->
                            <div class="popupbuttonholder">
                                    <button class="popup-button close-it">ok</button>
                            </div>
                        </div>

                    
                </div>
            </div>
        
        </div>
        
        <!----------popup--------->