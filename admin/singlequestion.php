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
  <!-- Navigation -->
        <?php include( 'sidemenu.php' ); ?>
    <div id="wrapper">
  

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Question</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
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
                            <i class="fa fa-question-circle fa-fw"></i>
                            Question
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <i class="fa fa-question-circle" ></i>
                            <a href="viewquestion.php"> View Question </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <i class="fa fa-question-circle" ></i>
                             Edit Question
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <?php
            $Text = urldecode($_REQUEST['questions']);
            $question = json_decode($Text);
//            print_r($question);

             ?>
            <div class="form-group i" style="display: none">
                <div> <input class="form-control"  type="text"  value="<?php echo $question->id; ?>" name="question" id="questionid"></div>
            </div>
            <div class="panel-body">
                <div class="row " style="margin-bottom: 20px">
                    <div class="col-lg-6 ">
                        <label >Question Type</label>
                        <select  class="form-control" id="questiontype" >
                            <?php   if($question->peradd==1)
                            {?>
                            <option name=""  value="1">Permanent Question</option>
                            <option name=""  value="0">Additional Question</option>
                            <?php }
                            else
                            { ?>
                            <option name=""  value="0">Additional Question</option>
                            <option name=""  value="1">Permanent Question</option>
                            <?php
                            }?>
                        </select>
                    </div></div>
                <div class="row ">
                    <div class="col-lg-6 ">
                        <div class="form-group i">
                            <div> <input class="form-control" placeholder="Enter Your Question" type="text"  value="<?php echo $question->question; ?>" name="question" id="question"></div>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <?php

                            $qusetionType   = $admin_obj->questiontype();
                            $qusetionType_array = [];
                            $qusetionType_array = $qusetionType['questiontype'];
                            ?>

                            <label>Selects Your Answer Type</label>
                            <select class="form-control" id="answertype">
                                <?php
                                echo '<option name="'.$question->question_type.'" value="'.$question->typeid.'">'.$question->question_type.'</option>';
                                foreach ( $qusetionType_array as $val ) {
                                    if($val['id']!=$question->typeid)
                                    echo '<option name="'.$val['question_type'].'" value="'.$val['id'].'">'.$val['question_type'].'</option>';
                                }
                                ?>
                            </select>
<!--                            <label >Answer Type</label>-->
<!--                            <select  class="form-control" id="answertype">-->
<!--                                <option name="--><?php //echo $question->question_type; ?><!--"  value="--><?php //echo $question->typeid; ?><!--">--><?php //echo $question->question_type; ?><!--</option>-->
<!--                            </select>-->
                        </div>
                    </div>

                    </div>
                    <div class="row 1 box ">
                        <div class="col-lg-6 ">
                            <div class="form-group input_fields_wrap">
                                <button class="add_field_button  btn btn-default">Add More</button>
                                <?php
                                if($question->options)
                                {
                                foreach ( $question->options as $val ) {?>
                                <div class="margin-bot" style="margin-bottom: 10px !important;">
                                    <input class="form-control" placeholder="Enter Your Option" type="text" name="option[]" value="<?php echo $val; ?>" id="option">
                                    <a href="#" class="remove_field">Remove</a></div>
                                <?php }
                                }
                                else{?>
                                    <div class="margin-bot" style="margin-bottom: 10px !important;">
                                    <input class="form-control" placeholder="Enter Your Option" type="text" name="option[]" value="" id="option">
                                     </div>
                              <?php  }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: center;">
                        <div class="col-lg-6 ">
                            <p>
                            <button type="button" class="btn btn-primary" id="edit_question">Edit</button></p>
                        </div>
                    </div>

            </div>

        </div>
        <!-- /#page-wrapper -->

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
            $("#answertype").change(function(){
                $(this).find("option:selected").each(function(){
                    var optionValue = $(this).attr("value");
                    if(optionValue){
                        $(".box").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else{
                        $(".box").hide();
                    }
                });
            }).change();

    });
    var max_fields      = 10;
    var option_array = new Array();
    $('input[name^="option"]').each(function(){
        option_array.push($(this).val());
    });

    var x = option_array.length;
    if(x==10)
    {
        $(".add_field_button").hide();
    }
    $(".add_field_button").click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++;
            $(".input_fields_wrap").append('<div class="margin-bot" style="margin-bottom: 10px !important;display: -webkit-box;"><input class="form-control" type="text" name="option[]"  placeholder="Enter Your Option" /><a style="vertical-align: -webkit-baseline-middle; margin-left: 10px;" href="#" class="remove_field">Remove</a></div>'); //add input box
          if(x==10)
          {
              $(".add_field_button").hide();
          }

        }
    });
    $(".input_fields_wrap").on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove();x--;
        $(".add_field_button").show();
    })
    $('#edit_question').on('click', function() {
        console.log("hf");
        var questionid= $( "#questionid" ).val();
        var question = $( "#question" ).val();
        var answertype = $( "#answertype" ).val();
        var questiontype=$( "#questiontype" ).val();
        var option_array = new Array();
        $('input[name^="option"]').each(function(){
            option_array.push($(this).val());
        });
        console.log(questiontype);
        if( question == '' ){
            swal({
                title: "<span style='color: #234A79;'>Edit Question</span>",
                text: "<span style='color: #D8670A;'>Please enter your Question</span>",
                html: true
            });
            return;
        } else if( answertype == 1 && option_array.length<3){
            swal({
                title: "<span style='color: #234A79;'>Edit Question</span>",
                text: "<span style='color: #D8670A;'>Please enter atleast three Options</span>",
                html: true
            });
            return;
        }
        else {
            var option=JSON.stringify(option_array);
              $.ajax({
                url: "https://www.propertypig.co.uk/admin/json/api.php",
                data: {question:question,answertype:answertype,questionid:questionid,questiontype:questiontype,option_array:option,request : 'editQuestion'},
                type: 'POST',
                dataType:'json',
                success: function(result){
                    console.log(result);
                    if( result.status === "true"){
                        swal("Edit Question!", "Successfully Edited", "success");
                        document.location.href = 'viewquestion.php';
                    } else {
                        swal("Edit Question", "Please try again", "error");
                    }
                }
            });
        }
    });


    </script>

</body>

</html>
