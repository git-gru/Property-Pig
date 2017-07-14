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
</head>
<body>
	<div class="ndiv page-holder">
    	
        <!-----header section----->
    	<div class="ndiv header-section">
        	<div class="ndiv primary-center-holder">
            	<div class="ndiv primary-center-in">
                	
                    <a href="<?php echo $base_url; ?>">
                        <div class="ndiv logo-holder">
                            <img class="imgres logoclass" src="<?php echo $assets; ?>image/logo.png" alt="property pig logo" />
                        </div>
                    </a>
                    
                    <div class="ndiv top-menu-holder">
                        <ul class="menu-ul top-menu">
                            <li class="yello-menu" style="visibility: hidden;">
                                Start Posting
                            </li>
                            <li class="home-icon-holder" active>
                                <a href="<?php echo $base_url; ?>">
                                    <span class="home-icon-inn">
                                	<i class="fa fa-home home-icon" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </li>
                            
                            <li class="home-icon-holder" active>
                                <a href="<?php echo $base_url."notification.php"; ?>">
                                    <div class="ndiv notisfication-icon-holder">
                                        <i class="fa fa-bell-o" aria-hidden="true"></i>
                                        <span class="notic-count">2</span>
                                    </div>
                                </a>
                            </li>
                            
                            
                            
                            <?php
                            
                            if(!isset($_SESSION['user_id']))
                            {
                            ?>
                            
                            <li class="already-member">
                                Already Register? <button id="login">Login</button> 
                            </li>
                            <?php } else{
                             
                              ?> 
                                    <li class="already-member">
                                        <a href="logout.php">Signout</a>
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
