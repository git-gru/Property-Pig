<?php
if(!isset($_SESSION)){
    session_start();
}

if( !isset($_SESSION['userid']) && $_SESSION['userid']=='' ){
    console.log($_SESSION['userid']);
    header( 'location:index.php' );
}
    include_once( 'json/class_admin.php' );
    $admin_obj    = new adminClass();
 ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Property Pig</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/plugins/social-buttons.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">
    <!-- Navigation -->
    <?php include( 'sidemenu.php' ); ?>
    <div id="wrapper">
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
    <!-----header section----->
    <div class="ndiv header-section-2">
        <div class="ndiv logo-b-holder">
            <img class="imgres" src="image/logo_b.png" alt="logo" />
        </div>
        <a href="logout.php">
            <div class="ndiv logout-button-holder">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </div>
        </a> 
    </div>
    <!----/header section----->

    <!---- menu tab section--->

    <div class="ndiv tab-menu-section">
        <ul class="menu-ul tab-menu-ul">
            <li class="active">list of documents</li>
<!--            <li>reports</li>
            <li>Questions</li>-->
        </ul>
    </div>

    <!----/menu tab section--->

    <!----contetn section----->
    <div class="ndiv tab-content-section">

        <div class="ndiv tab active tab-sec-one">
            <div class="panel-body">
                <?php
                $userid = $_REQUEST['id'];
                $documents   = $admin_obj->getdocuments($userid);
//                $documentlist=array();
                $documentlist=$documents['documentlist'];
//                print_r($documents)
                ?>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                                <ul class="page-breadcrumb breadcrumb">
                                    <li>
                                        <i class="fa fa-home"></i>
                                        <a href="home.php"> Home </a>
                                        <i class="fa fa-angle-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa fa-list fa-fw"></i>
                                        <a href="listofcustomer.php"> List of customer </a>
                                    </li>
                                    <li>
                                       <i class="fa fa-file-text" ></i>
                                        <a href="documents.php"> Documents </a>
                                    </li>
                                </ul>
                                <!-- END PAGE TITLE & BREADCRUMB-->
                            </div>
                        </div>
                        <?php
                        if($documentlist)
                        {
                        foreach ( $documentlist as $val ) {?>
                        <div class="alert alert-success">
                         <a href="#" data-toggle="modal" data-target="#myModal" class="alert-link" onclick="documentpopup('<?php echo $val['document']?>')">Click here to view</a>.
                        </div>
                        <?php
                        }
                        }
                        else{?>
                            <div><p style="margin-left:10px">No documents found.</p>
                                <?php }?>
<!--                        <div class="alert alert-info">-->


                    </div>
                    <!-- /.panel -->
                </div>
                <!-- Modal profile -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Document</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="targetDIV">
                                    <!-- /.panel-body -->

                                </div>
                            </div>

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal profile -->
            </div>

        </div>
        </div>

        

    </div>
    <!---/contetn section----->

</div>
        
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script type="text/javascript" src="js/sweetalert-dev.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
//      selct options
    });
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()

        function documentpopup(item)
        {
            console.log(item);
            $("#targetDIV").html('<div class="panel-body" style="height: 500px"><iframe src="https://www.propertypig.co.uk/assets/docs/'+item+'" width="100%" height="100%"><p>Your browser does not support iframes.</p> </iframe> </div>');

        }

    </script>

</body>

</html>
