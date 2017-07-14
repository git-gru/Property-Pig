<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userid']) && $_SESSION['userid'] == '') {
    console . log($_SESSION['userid']);
    header('location:index.php');
}
include_once( 'json/class_admin.php' );
$admin_obj = new adminClass();
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


        <!-- Navigation -->
        <?php include( 'sidemenu.php' ); ?>
        <div id="wrapper">

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
                    <li class="active">list of reasons</li>
                </ul>
            </div>

            <!----/menu tab section--->

            <!----contetn section----->
            <div class="ndiv tab-content-section">

                <div class="ndiv tab active tab-sec-one">
                    <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <?php

                                    $get_resons   = $admin_obj->get_resons();
                                    ?>

                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                        <tr>
                                            <th>SL No</th>
                                            <th>Reason</th>
                                            <th>Actions</th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                         <?php
                                         if(!empty($get_resons))
                                         {
                                              
                                             $i=1;
                                                foreach ($get_resons['reasons'] as $val ) 
                                                {
                                                   ?>
                                                        <tr id="question_tr<?php echo $val['id']; ?>">
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $val['reason']; ?></td>
                                                            <td><a href="javascript:void(0);" onclick="return deletequestion(<?php echo $val['id']; ?>);">X</a></td>
                                                        </tr>
                                                    <?php
                                                }
                                         }
                                         ?>
                                        </tbody>
                                    </table>
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
            $(document).ready(function () {
                $('#dataTables-example').dataTable();

            });


            function deletequestion(item) {
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this Question!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        url: "json/api.php",
                        dataType: "json",
                        data: "id=" + item + "&request=deleterequest",
                        async: false,
                        success: function (result) {
                            console.log(result);
                            if (result.status == "true") {
                                swal("Deleted!", "Successfully Deleted", "success");
                                $('#question_tr' + item).hide();
                            }
                        }
                    });
                });
            }

        </script>

    </body>

</html>
