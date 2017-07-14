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
            <li class="active">list of questions</li>
        </ul>
    </div>

    <!----/menu tab section--->

    <!----contetn section----->
    <div class="ndiv tab-content-section">

        <div class="ndiv tab active tab-sec-one">
            <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <?php
                $qusetion = $admin_obj->questions();
                $qusetion_array = [];
                $qusetion_array = $qusetion['question'];
                ?>

                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Question Type</th>
                            <th>Answer Type</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($qusetion_array as $val) {
                            $questionEdit = $val;
                            $Text = json_encode($questionEdit);
                            $RequestText = urlencode($Text);

                            echo '<tr id="question_tr' . $val['id'] . '">
												<td >
													' . $val['question'] . '</td>';
                            if ($val['peradd'] == 1) {
                                echo '<td>Permanent</td>';
                            } else {
                                echo '<td>Additional</td>';
                            }
                            echo'<td>' . $val['question_type'] . '</td>
                                                <td style="text-align: center;">
												    <a href="singlequestion.php?questions=' . $RequestText . '" style="padding: 10px;">
												        <i class="fa fa-eye"></i>
											        </a>';
                            if ($val['peradd'] == 0) {
                                echo '<a href="javascript:void(0);"  style="padding: 10px;">
												        <i class="fa fa-trash-o" onclick="deletequestion(' . $val['id'] . ')"></i>
											        </a>';
                            }
                            else{ echo '<a style="visibility:hidden;"> <i style="font-size: 33px;" class="fa fa-trash-o"></i></a>';}
                            echo '</td>
											</tr>';
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
                data: "questionid=" + item + "&request=deletequestion",
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
