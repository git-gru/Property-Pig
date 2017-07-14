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

        <!-- Custom CSS -->
        <link href="css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            input[type=number]::-webkit-inner-spin-button, 
            input[type=number]::-webkit-outer-spin-button { 
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                margin: 0; 
            }
            #mytip{
                position:absolute;
                z-index:9999;
                width:150px;
                height:100px;
                padding:10px;
                background: #DCDCDC;
                color: RED;

            }
        </style>
    </head>

    <body>
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
                    <li class="active">list of properties request</li>
                </ul>
            </div>

            <!----/menu tab section--->

            <!----contetn section---->
            <div class="ndiv tab-content-section">

                <div class="ndiv tab active tab-sec-one">
                    <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <?php
                        $Customerlist = $admin_obj->zooplalist();
//                            print_r($Customerlist);
                        $Customerlist_array = [];
                        $Customerlist_array = $Customerlist['customerlist'];
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>House Number</th>
                                        <th>Zip code</th>
                                        <th>User</th>
                                        <th>Rate</th>
                                        <th>Offer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $k = 1;
                                    $obj1 = new commonFunctions();
                                    $i = 1;
                                    $dbh = $obj1->dbh;
                                    $status = false;
                                    $row = '';
                                    $query = $dbh->prepare("select * from pig_user_answer_rel order by property_id asc");
                                    $query->execute();
                                    $counts_questions = $query->rowCount();
                                    if ($query->rowCount() > 0) {
                                        $status = true;
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $k; ?></td>
                                                <td>
                                                    <?php
                                                    $obj1 = new commonFunctions();
                                                    $dbh1 = $obj1->dbh;
                                                    $query1 = $dbh1->prepare("select * from pig_user_answer where property_id=:property_id and  user_id=:user_id order by id asc limit 1");
                                                    $query1->bindParam(":user_id",$row['user_id']);
                                                    $query1->bindParam(":property_id",$row['property_id']);

                                                    $query1->execute();
                                                    $counts_questions1 = $query1->rowCount();
                                                    if ($query1->rowCount() > 0) {
                                                        $rows1 = $query1->fetch(PDO::FETCH_ASSOC);
                                                        echo $rows1['answer'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $obj2 = new commonFunctions();
                                                    $dbh2 = $obj2->dbh;
                                                    $query2 = $dbh2->prepare("select * from pig_user_answer where property_id=:property_id and  user_id=:user_id order by id asc limit 1,1");
                                                    $query2->bindParam(":user_id",$row['user_id']);
                                                    $query2->bindParam(":property_id",$row['property_id']);
                                                    $query2->execute();
                                                    $counts_questions2 = $query2->rowCount();
                                                    if ($query2->rowCount() > 0) {
                                                        $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                                                        echo $rows2['answer'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $obj3 = new commonFunctions();
                                                    $dbh3 = $obj3->dbh;
                                                    $query3 = $dbh3->prepare("select Name from pig_user where user_id=:user_id order by user_id asc limit 1");      
                                                    $query3->bindParam(":user_id",$row['user_id']); 
                                                    $query3->execute();
                                                    $counts_questions3 = $query3->rowCount();
                                                    if ($query3->rowCount() > 0) {
                                                        $rows3 = $query3->fetch(PDO::FETCH_ASSOC);
                                                        echo $rows3['Name'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row['rate4'] == 0 || $row['rate4'] == '') {
                                                        ?>
                                                        Invalid Response
                                                        <?php
                                                    } else {
                                                        echo "£" . $row['rate4'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row['rate4'] == 0 || $row['rate4'] == '') {
                                                        ?>
                                                        <!--                                                                    <button disabled="disabled">Add Offer</button>-->
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <?php
                                                        $query3 = $dbh3->prepare("select offerPrice from pig_property_offer where userid=:user_id and offerpropertyId=:property_id");
                                                        $query3->bindParam(":user_id",$row['user_id']);
                                                        $query3->bindParam(":property_id",$row['property_id']);
                                                        $query3->execute();
                                                        $counts_offer = $query3->rowCount();
                                                        if ($counts_offer > 0) {
                                                            $rows4 = $query3->fetch(PDO::FETCH_ASSOC);
                                                            ?><i class="fa fa-gbp" aria-hidden="true"></i> <?php
                                                            echo $rows4['offerPrice'];
                                                        } else {
                                                            ?>
                                                            <div id="offer_div">
                                                                <button onclick="return add_offer('<?php echo $row['user_id']; ?>', '<?php echo $row['property_id']; ?>', '<?php echo $row['property_id']; ?>')">Add Offer</button>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>



                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div id="update_status_div">
                                                        <button onclick="return update_status('<?php echo $row['user_id']; ?>', '<?php echo $row['property_id']; ?>', '<?php echo $row['property_id']; ?>')">Change Status</button>
                                                    </div>
                                                </td>
                                            </tr>



        <?php
        $k++;
        ?>
                                            <!-- Trigger the modal with a button -->
                                        <button type="button" class="btn btn-info btn-lg" style="height: 2px;width: 2px;visibility: hidden;" data-toggle="modal" id="pop<?php echo $row['property_id']; ?>" data-target="#myModalpop<?php echo $row['property_id']; ?>">Open Modal</button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="myModalpop<?php echo $row['property_id']; ?>" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Update The Offer</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p>Enter The Offer <br/><input  type="number" min="1" class="form-control" id="offer_amount<?php echo $row['property_id']; ?>" name="offer_amount<?php echo $row['property_id']; ?>" placeholder="Offer Amount">
                                                                <button type="submit" style="margin-top: 10px;margin-left: 0px;padding: 5px 10px;border-radius: 3px;" onclick="return save_offer_zone('<?php echo $row['user_id']; ?>', '<?php echo $row['property_id']; ?>');">Apply Offer</button></p>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <button type="button" style="height: 2px;width: 2px;visibility: hidden;" class="btn btn-info btn-lg" data-toggle="modal" id="updatepop_list<?php echo $row['property_id']; ?>" data-target="#myModalpops<?php echo $row['property_id']; ?>">Open Modal</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="myModalpops<?php echo $row['property_id']; ?>" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Update The Status</h4>
                                                    </div>
                                                    <div class="modal-body">
        <?php
        $query3 = $dbh3->prepare("select status from pig_status where user_id=:user_id and property_id=:property_id");
        $query3->bindParam(":user_id",$row['user_id']);
        $query3->bindParam(":property_id",$row['property_id']);
        $query3->execute();
        $counts_status = $query3->rowCount();
        if ($counts_status > 0) {
            $rows_status = $query3->fetch(PDO::FETCH_ASSOC);
            $counts_status = '';
            if (!empty($rows_status)) {
                $counts_status = $rows_status['status'];
            }
        } else {
            $counts_status = '';
        }
        ?>
                                                        <div class="row">
                                                            <p>Choose The Status <br/>
                                                                <select class="form-control" id="status_control<?php echo $row['property_id']; ?>">
                                                                    <option <?php if ($counts_status == 'new') {
                                                    echo "selected";
                                                } ?> value="new">New</option>
                                                                    <option <?php if ($counts_status == 'passed_to_exit_strategy') {
                                                    echo "selected";
                                                } ?> value="passed_to_exit_strategy">Passed to Exit Strategy</option>
                                                                    <option <?php if ($counts_status == 'lost') {
                                                    echo "selected";
                                                } ?> value="lost">Lost</option>
                                                                    <option <?php if ($counts_status == 'sold') {
                                                    echo "selected";
                                                } ?> value="sold">Sold</option>
                                                                </select>    
                                                                <button type="submit" style="margin-top: 10px;margin-left: 0px;padding: 5px 10px;border-radius: 3px;" onclick="return change_status('<?php echo $row['user_id']; ?>', '<?php echo $row['property_id']; ?>');">Save changes</button></p>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
        <?php
    }
}
?>

                                </tbody>
                            </table>
                        </div>


                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Profile details</h4>
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
                        <!-- Modal profile -->
                        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Question and Answers</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row" >
                                            <div class="panel-body" id="targetquestion">


                                            </div>
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
        <!--    https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js-->
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
                                                            $(document).ready(function () {
                                                                $('#dataTables-example').DataTable({
                                                                    buttons: [
                                                                        {
                                                                            extend: 'excel',
                                                                            text: 'Save current page',
                                                                            exportOptions: {
                                                                                modifier: {
                                                                                    page: 'current'
                                                                                }
                                                                            }
                                                                        }
                                                                    ]
                                                                });
                                                            });
                                                            $('.tooltip-demo').tooltip({
                                                                selector: "[data-toggle=tooltip]",
                                                                container: "body"
                                                            })

                                                            function get_rate(user_id, property_id)
                                                            {
                                                                console.log(user_id);
                                                                console.log(property_id);
                                                                $.ajax({
                                                                    type: "POST",
                                                                    data: "user_id=" + user_id + "&property_id=" + property_id,
                                                                    url: "zoopla_process.php",
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        $('#get_rate_value' + property_id).html(result);
                                                                        $('#get_rate' + property_id).hide();

                                                                    }
                                                                });
                                                            }

                                                            /*save offer code here*/
                                                            function save_offer_zone(user_id, property_id)
                                                            {
                                                                var offer_amount = $('#offer_amount' + property_id).val();
                                                                if (offer_amount != '')
                                                                {
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        data: "user_id=" + user_id + "&property_id=" + property_id + "&amount=" + offer_amount,
                                                                        url: "offer.php",
                                                                        success: function (result) {
                                                                            console.log(result);
                                                                            if (result == 1)
                                                                            {
                                                                                alert("Offer Added Successfully...");
                                                                                setTimeout(function () {
                                                                                    window.location.href = "pending_request.php";
                                                                                }, 2000);
                                                                            } else
                                                                            {
                                                                                alert("Error Found!!!");
                                                                                return false;
                                                                            }

                                                                        }
                                                                    });
                                                                } else
                                                                {
                                                                    $('#offer_amount' + property_id).css('border', '3px solid red');
                                                                    $('#offer_amount' + property_id).focus();
                                                                    return false;
                                                                }


                                                            }


                                                            /*save offer code end here*/

                                                            /*add offer  pop up from admin*/

                                                            function add_offer(user_id, property_id, id)
                                                            {
                                                                $('#pop' + id).trigger('click');
                                                            }
                                                            /*add offer from admin code end*/

                                                            /*set the status*/
                                                            function update_status(user_id, property_id, id)
                                                            {
                                                                $('#updatepop_list' + property_id).trigger('click');
                                                            }
                                                            /*set the status end here*/

                                                            /*change status code here*/
                                                            function change_status(user_id, property_id)
                                                            {
                                                                var status = $('#status_control' + property_id).val();
                                                                if (status != '')
                                                                {
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        data: "user_id=" + user_id + "&property_id=" + property_id + "&status=" + status,
                                                                        url: "chnage_status.php",
                                                                        success: function (result) {
                                                                            if (result == 1)
                                                                            {
                                                                                alert("Status Changed Successfully...");
                                                                                setTimeout(function () {
                                                                                    window.location.href = "pending_request.php";
                                                                                }, 2000);
                                                                            } else
                                                                            {
                                                                                alert("Error Found!!!");
                                                                                return false;
                                                                            }

                                                                        }
                                                                    });
                                                                } else
                                                                {
                                                                    $('#offer_amount' + property_id).css('border', '3px solid red');
                                                                    $('#offer_amount' + property_id).focus();
                                                                    return false;
                                                                }


                                                            }


                                                            /*change status code end here*/

        </script>

    </body>

</html>

