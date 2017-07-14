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
            <li class="active">Add new reasons</li>
            <!--            <li>reports</li>
                        <li>Questions</li>-->
        </ul>
    </div>

    <!----/menu tab section--->

    <!----contetn section----->
    <div class="ndiv tab-content-section">

        <div class="ndiv tab active tab-sec-one">
            <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="panel-body">

                    <div class="row " style="margin-bottom: 20px">
                        <div class="col-lg-12">
                            <label >Reason</label>
                        </div>
                        <div class="col-lg-12" >
                            <textarea  class="form-control" cols="50" rows="10" id="txtarea" name="txtarea" placeholder="Enter Reason Here"></textarea>
                        </div>
                    </div>


                    <div class="row" style="text-align: center;">
                        <div class="col-lg-6 ">
                            <p>
                                <button type="button" class="btn btn-primary" id="submit_question">Submit</button></p>
                        </div>
                    </div>

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



    $('#submit_question').on('click', function () {
        var txtarea = $("#txtarea").val();
        if (txtarea == '') {
            swal({
                title: "<span style='color: #234A79;'>Add New Reason</span>",
                text: "<span style='color: #D8670A;'>Please enter your Reason</span>",
                html: true
            });
            return;
        } else {

            $.ajax({
                url: "https://www.propertypig.co.uk/admin/reason_api.php",
                data: {txtarea: txtarea, request: 'reasons'},
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    var final_res = $.trim(result);
                    $("#txtarea").val('');
                    if (final_res == "true") {
                        document.location.href = 'viewreasons.php';
                    } else {
                        swal("Add New Reason", "Please try again", "error");
                    }
                }
            });
        }
    });


</script>

</body>

</html>
