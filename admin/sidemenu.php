<?php $path = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Property Pig</title>
            <!-------Bootstrap Plugin---------->
            <link rel="stylesheet" href="css/bootstrap.min.css" />
            <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
            <!-------UI Developers Costimised Css Tool------> 
            <link rel="stylesheet" href="css/break_bs_v_1_2.css" />
            <!------- font awsome / font css ------>
            <link href="css/plugins/social-buttons.css" rel="stylesheet"/>
            <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Nunito:400,700,900" rel="stylesheet"  />

            <!------ui developer costamised css---->
            <link rel="stylesheet" href="css/admin-style.css" />
            <!-- jQuery Version 1.11.0 -->
            <script src="js/jquery-1.11.0.js"></script>

            <script>
                $(document).ready(function () {
                    $('.have-child').click(function () {
                       $(this).children('ul.child-menu').slideToggle();
                    });
                $.fn.dataTable.ext.errMode = 'none';    
                /*datatable code code here*/
                 $('#example').DataTable();
                 $('#dataTables-example').DataTable();
                /*datatable code code end here*/
                });
                
                
            </script>
            <style>
                .logout-button-holder > i{color: #fff!important;}
                .paginate_button.active a{background:#6d4ebe!important; }
                .fa{color: #6d4ebe!important;}
                button{background-color: #6d4ebe;color: #fff;}
                #page-wrapper{padding-left: 20px;}
                ul.child-menu {
                    list-style-type: none;
                    padding: 15px 0px 0px 15px;
                    display:none;
                    line-height: 26px;
                }
                                .have-child span{
                                    line-height: 38px;
                                    /*font-size: 22px;*/
                                }
           
            #wait{overflow-x: hidden;width: 100%;min-height: 2000px;background-color: rgba(0, 0, 0, 0.29);z-index: 9998;position: absolute;}
            #wait img{padding-left: 43%;padding-top: 20%;}
            .activebutton{background: #6d4ebe;color: #fff;}
        </style>
    </head>
    <body>
       <div  id="wait" style="display: none;"><img src="../gif.gif"></div>     
        <div class="ndiv page-holder-admin">

            <div class="ndiv side-panel-holder">
                <div class="ndiv side-panel-logo-holder">
                    <img class="imgres" src="image/logo_s.png" />
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <div class="ndiv side-panel-menu-holder">
                    <ul class="bullet_free menu-side-bar">
                        <li class="<?php
                        if ($path == 'listofcustomer.php') {
                            echo 'active';
                        }
                        ?>">
                            <i class="fa fa-list" aria-hidden="true"></i> 
                            <a href="listofcustomer.php">List of customers</a>
                        </li>
                        <li class="have-child">
                            <i class="fa fa-flag fa-fw"></i>
                            <a>Reports <span class="fa fa-angle-down pull-right"></span></a>
                            <ul class="child-menu">
                                <li>
                                    <a href="Report_based_status.php">Reports based on status</a>
                                </li>
                                <li>
                                    <a href="Report_based_leads.php">Reports based on leads</a>
                                </li>
                            </ul>
                        </li>
                        <li class="have-child">
                            <i class="fa fa-question-circle fa-fw"></i> 
                            <a href="javascript:void(0)" class="have-child">Questions<span class="fa fa-angle-down pull-right"></span></a>
                            <ul class="child-menu">
                                <li>
                                    <a href="viewquestion.php"> View questions </a>
                                </li>
                                <li>
                                    <a href="questions.php"> Add new questions </a>
                                </li>
                            </ul>
                        </li>
                        <li class="have-child">
                            <i class="fa fa-question-circle fa-fw"></i>
                            <a href="javascript:void(0)" class=" have-child">Estimate Requests<span class="fa fa-angle-down pull-right"></span></a>
                            <ul class="child-menu">
                                <li>
                                    <a href="pending_request.php">Requests </a>
                                </li>

                            </ul>
                        </li>
                        <li class="have-child">
                            <i class="fa fa-question-circle fa-fw"></i>
                            <a href="javascript:void(0)" class="have-child">Reject Reasons<span class="fa fa-angle-down pull-right"></span></a>
                            <ul class="child-menu">
                                <li>
                                    <a href="viewreasons.php"> View Reasons </a>
                                </li>
                                <li>
                                    <a href="reasons.php"> Add new Reasons </a>
                                </li>
                            </ul>
                        </li>
<!--                        <li>
                            <i class="fa fa-sign-out fa-fw" ></i>
                            <a href="logout.php">Logout</a>
                        </li>-->
                    </ul>
                </div>
            </div>
            <div class="ndiv main-panel-holder">
                <!-----header section----->
                <div class="ndiv header-section-1">
                    <div class="ndiv menu-icon-holder">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </div>
                    <a href="#">
<!--                        <div class="ndiv notisfication-icon-holder">
                        <i class="fa fa-bell-o" aria-hidden="true"></i>
                       
                             <?php
                            
//                            if(isset($_SESSION['user_id']))
//                            {
//                                ?> <span class="notic-count"><?php
//                                $obj2 = new commonFunctions();
//                                $dbh2 = $obj2->dbh;
//                                $query2 = $dbh2->prepare("select count(id) as count from pig_notification where `user_id`='" .$_SESSION['userid']. "' and status=0");
//                                $query2->execute();
//                                if ($query2->rowCount() > 0) {
//                                    $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
//                                    if($rows2['count']!=0)
//                                    {
//                                        $not_count= $rows2['count'];
//                                    }
//                                    else
//                                    {
//                                        $not_count='';
//                                    }
//                                }
//                                else
//                                {
//                                    $not_count='';
//                                }
//                                ?> </span><?php
//                            }
                            ?> 
                       
                    </div>-->
                    </a>
                    <div class="ndiv search-bar-holder">
                        <div class="ndiv search-bar-and-button">
                            
                        </div>
                    </div>
                </div>
                <!----/header section----->
