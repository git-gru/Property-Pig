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
<style>
    #validation_needed {margin-left: 10px;}
</style>
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
            <li class="active">Add Question</li>
        </ul>
    </div>

    <!----/menu tab section--->

    <!----contetn section----->
    <div class="ndiv tab-content-section">

        <div class="ndiv tab active tab-sec-one">
            <div id="example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="panel-body">

                    <div class="row " style="margin-bottom: 20px">
                        <div class="col-lg-12 ">
                            <label >Question Type</label>
                        </div>
                        <div class="col-lg-12 ">
                            <div class="col-lg-8 ">
                                <select  class="form-control" id="questiontype" >
                                    <option name=""  value="1">Permanent Question</option>
                                    <option name=""  value="0">Additional Question</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-6 ">
                            <div class="form-group i" style="margin-bottom: 11px;">
                                <div> <input class="form-control" placeholder="Enter Your Question" type="text" name="question" id="question"></div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row " style="margin-bottom: 20px">
                        <div class="col-lg-12 ">
                            <?php
                                $qusetionType = $admin_obj->questiontype();
                                $qusetionType_array = [];
                                $qusetionType_array = $qusetionType['questiontype'];
                                ?>

                                <label style="margin-bottom: 11px;">Selects Your Answer Type</label>
                        </div>
                        <div class="col-lg-12 ">
                            <div class="col-lg-8 ">
                                <select class="form-control" id="answertype">
                                    <?php
                                    foreach ($qusetionType_array as $val) {
                                        echo '<option name="' . $val['question_type'] . '" value="' . $val['id'] . '">' . $val['question_type'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    

                    <div class="row 4 boxs ">
                        <div class="col-lg-6 ">
                            <div class="form-group input_fields_wrap1">
                                <button class="add_field_button1  btn btn-default"  style="margin-bottom: 5px !important;">Add More</button>
                                <div class="margin-bot" style="margin-bottom: 10px !important;"> <input class="form-control" placeholder="Enter Your Dropdown value" type="text" name="drop_option[]" id="drop_option"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row 1 box ">
                        <div class="col-lg-6 ">
                            <div class="form-group input_fields_wrap">
                                <button class="add_field_button  btn btn-default"  style="margin-bottom: 5px !important;">Add More</button>
                                <div class="margin-bot" style="margin-bottom: 10px !important;"> <input class="form-control" placeholder="Enter Your Option" type="text" name="option[]" id="option"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row 4 boxs ">
                        <div class="col-lg-6 ">
                            <div class="form-group input_fields_wrap1">
                                <button class="add_field_button1  btn btn-default"  style="margin-bottom: 5px !important;">Add More</button>
                                <div class="margin-bot" style="margin-bottom: 10px !important;"> <input class="form-control" placeholder="Enter Your Dropdown value" type="text" name="drop_option[]" id="drop_option"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom: 20px">
                        <div class="col-lg-6 ">
                            <label >Does validation Needed?</label>
                            <input type="checkbox" value="1" name="validation_needed" id="validation_needed">
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
    $(document).ready(function () {
        $('.boxs').hide();
        $('#dataTables-example').dataTable();
//      selct options
        $("#answertype").change(function () {
            $(this).find("option:selected").each(function () {
                var optionValue = $(this).attr("value");

                if (optionValue == 1) {
                    $(".box").not("." + optionValue).hide();
                    $(".boxs").not("." + optionValue).hide();
                    $("." + optionValue).show();

                } else if (optionValue == 4) {

                    $(".boxs").not("." + optionValue).hide();
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();

                } else {
                    $(".box").hide();
                    $(".boxs").hide();
                }
            });
        }).change();

    });
    var max_fields = 10;
    var x = 1;
    $(".add_field_button").click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++;
            $(".input_fields_wrap").append('<div class="margin-bot" style="margin-bottom: 10px !important;display: -webkit-box;"><input class="form-control" type="text" name="option[]"  placeholder="Enter Your Option" /><a style="vertical-align: -webkit-baseline-middle; margin-left: 10px;" href="#" class="remove_field">Remove</a></div>'); //add input box
            if (x == 10)
            {
                $(".add_field_button").hide();
            }

        }
    });
    $(".input_fields_wrap").on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
        $(".add_field_button").show();
    })

    $(".add_field_button1").click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++;
            $(".input_fields_wrap1").append('<div class="margin-bot" style="margin-bottom: 10px !important;display: -webkit-box;"><input class="form-control" type="text" name="drop_option[]"  placeholder="Enter Your Dropdown value" /><a style="vertical-align: -webkit-baseline-middle; margin-left: 10px;" href="#" class="remove_field1">Remove</a></div>'); //add input box
            if (x == 10)
            {
                $(".add_field_button1").hide();
            }

        }
    });

    $(".input_fields_wrap1").on("click", ".remove_field1", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
        $(".add_field_button1").show();
    })



    $('#submit_question').on('click', function () {

        var question = $("#question").val();
        var answertype = $("#answertype").val();
        var questiontype = $("#questiontype").val();

        //validation_needed
        if ($('#validation_needed:checked').val() !== undefined)
        {
            var validation = "1";
        } else
        {
            var validation = "0";
        }

        var option_array = new Array();
        var drop_option_array = new Array();

        $('input[name^="drop_option"]').each(function () {
            drop_option_array.push($(this).val());
        });

        $('input[name^="option"]').each(function () {
            option_array.push($(this).val());
        });
        console.log(option_array);
        if (question == '') {
            swal({
                title: "<span style='color: #234A79;'>Add New Question</span>",
                text: "<span style='color: #D8670A;'>Please enter your Question</span>",
                html: true
            });
            return;
        } else if (answertype == 1 && option_array.length < 3) {
            swal({
                title: "<span style='color: #234A79;'>Add New Question</span>",
                text: "<span style='color: #D8670A;'>Please enter atleast three Options</span>",
                html: true
            });
            return;
        } else {
//            console.log(option_array);
//            console.log("hds");
            var option = JSON.stringify(option_array);
            console.log(option);
            //data = "question=" + question + "&answertype=" + answertype + "&option_array=" + option + "&request=addQuestion"

            $.ajax({
                url: "https://www.propertypig.co.uk/admin/json/api.php",
                data: {question: question, answertype: answertype, questiontype: questiontype, option_array: option, request: 'addQuestion', drop_option_array: drop_option_array, validation: validation},
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    if (result.status === "true") {
                        document.location.href = 'viewquestion.php';
                    } else {
                        swal("Add New Question", "Please try again", "error");
                    }
                }
            });
        }
    });


</script>

</body>

</html>
