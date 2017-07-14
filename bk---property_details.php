<?php 
include 'header.php'; 
if(!isset($_SESSION['user_id']))
{
     echo("<script>location.href = '".$base_url."signin.php';</script>");
}
?>  <style>.property-querys-holder{padding-top: 55px;}</style>
        
        <!-----page section----->
    	<div class="ndiv page-section">
        	<div class="ndiv image-baaner">
                <p class="pmzero page-title">Properties</p>
            </div>
            <div class="ndiv primary-center-holder">
            	<div class="ndiv primary-center-in">
                	<div class="ndiv property-detail-section">
                    	<table class="property-detail-table">
                        	<tr>
                            	<th class="new">New</th>
                                <th>House No</th>
                                <th>Zip Code</th>
                                <th>Price</th>
                                <th>Property status</th>
                                <th>Admin status</th>
                                <th></th>
                            </tr>
                            
                            <?php
                                $k = 1;
                                $obj1 = new commonFunctions();
                                $i = 1;
                                $dbh = $obj1->dbh;
                                $status = false;
                                $row = '';
                                $query = $dbh->prepare("select * from pig_user_answer_rel where user_id='".$_SESSION['user_id']."' and property_id='".$_REQUEST['id']."'  order by property_id asc");
                                $query->execute();
                                $counts_questions = $query->rowCount();
                                if ($query->rowCount() > 0) {
                                    $status = true;
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                    <tr>
                                                         <td>
                                                            <?php
                                                            $obj2 = new commonFunctions();
                                                            $dbh2 = $obj2->dbh;
                                                            $query2 = $dbh2->prepare("select * from pig_user_answer where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "' order by id desc limit 1");
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
                                                    $obj1 = new commonFunctions();
                                                    $dbh1 = $obj1->dbh;
                                                    $query1 = $dbh1->prepare("select * from pig_user_answer where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "' order by id asc limit 1");
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
                                                            $query2 = $dbh2->prepare("select * from pig_user_answer where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "' order by id asc limit 1,1");
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
                                                            if ($row['rate4'] == 0 || $row['rate4'] == '') {
                                                                ?>
                                                                <div>
                                                                    <button onclick="return  get_rate(<?php echo $row['user_id']; ?>,<?php echo $row['property_id']; ?>);" id="get_rate<?php echo $row['property_id']; ?>" value="Get Rate">Get Rate</button>
                                                                    <div id="get_rate_value<?php echo $row['property_id']; ?>"></div>
                                                                </div>
                                                                <?php
                                                            } else { ?><i class="fa fa-gbp" aria-hidden="true"></i> <?php
                                                                echo $row['rate4'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td id="resulten_td">
                                                        <?php
                                                            $obj2 = new commonFunctions();
                                                            $dbh2 = $obj2->dbh;
                                                            $query2 = $dbh2->prepare("select * from pig_property_status where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "'");
                                                            $query2->execute();
                                                            $counts_questions2 = $query2->rowCount();
                                                            if ($query2->rowCount() > 0) {
                                                                $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                                                                if($rows2['status']==1)
                                                                {
                                                                    echo "Accepted";
                                                                }
                                                                else
                                                                {
                                                                    echo "Rejected";
                                                                }
                                                            }
                                                            else{
                                                            ?>
                                                        
                                                       
                                                            <div class="ndiv button-acceptace" style="margin-top: 0px;">
                                                                <button class="buttons-property red" id="reject_btn" style="width: initial;height: 20px;">Reject</button>
                                                                <button class="buttons-property green"  id="accept_btn" style="width: initial;height: 20px;">Accept</button>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $obj2 = new commonFunctions();
                                                            $dbh2 = $obj2->dbh;
                                                            $query2 = $dbh2->prepare("select * from pig_property_status where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "'");
                                                            $query2->execute();
                                                            $counts_questions2 = $query2->rowCount();
                                                            if ($query2->rowCount() > 0) {
                                                                echo "Pending";
                                                            }
                                                            else
                                                            {
                                                                echo "Accepted";
                                                            }
                                                         ?>
                                                        </td>
                                                        <?php
                                                            $obj3 = new commonFunctions();
                                                            $dbh3 = $obj3->dbh;
                                                            $query3 = $dbh3->prepare("select * from pig_property_status where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "' and status='1'");
                                                            $query3->execute();
                                                            $counts_active = $query3->rowCount();
                                                            if ($counts_active>0) {
                                                                ?>
                                                        <td id="link_td"><a class="answer-choise-yes-no"  style='width: auto;padding: 7px 10px;color:#fff;background-color: #8173c4;'  href='upload.php?id=<?php echo $_REQUEST['id']; ?>' alt='Upload'>Upload Document</a></td>
                                                                <?php
                                                            } else {
                                                               ?>
                                                                <td id="link_td"></td>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tr>



                                                            <?php
                                                            $k++;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                    <tr><td colspan="5"><div style="margin-top: 25px;">No Result Found</div></td></tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    
                                                    
                                                    

                            
                            
                            
                        </table>
                    </div>
                    
                    <div class="ndiv property-querys-holder">
                    	<div class="ndiv property-querys-center">
                    		<div class="ndiv property-querys-in">
                    			
                                
                                
                                    <?php
                                    $querys = $dbh3->prepare("select * from pig_user_answer where user_id='" . $_SESSION['user_id'] . "' and property_id='" . $_REQUEST['id'] . "' ORDER BY id ASC");
                                    $querys->execute();
                                    $my_counts_questions2 = $querys->rowCount();

									//echo "my_counts_questions2 :".$my_counts_questions2;
 									/*while($querys_rows = $querys->fetch(PDO::FETCH_ASSOC))
                                    {
										print_r($querys_rows);
									}*/

                                    $obj2 = new commonFunctions();
                                    $dbh2 = $obj2->dbh;
                                    $query2 = $dbh2->prepare("select * from pig_questions order by id asc");
                                    $query2->execute();
                                    $counts_questions2 = $query2->rowCount();
									
                                    if ($query2->rowCount() > 0) {
                                        $k=0;
                                        while($rows_questions = $query2->fetch(PDO::FETCH_ASSOC))
                                        {
											
											//echo "ID: ".$rows_questions['id']."; Qn: ".$rows_questions['question']."<br>";
                                            ?>
                                    <div id="hide<?php echo $rows_questions['id']; ?>" style="display: <?php if($k!=0){echo "none;";} ?>" >
                                        <div class="ndiv property-query-title">
                                                    Property Details
                                        </div>        
                                        <div class="ndiv property-query-questions">
                                                <div class="ndiv question-single">
                                                    <p class="pmzero question"><?php echo ucfirst($rows_questions['question']); ?></p>
                                                        <div class="ndiv">
                                                            <style>
                                                                .ans-show-but{border: 1px solid #ccc;
    text-align: center;
    padding: 7px;
    min-width: 100px;
    max-width: none;
    width: auto;margin: auto;
    float: none;
    display: table;}
                                                            </style>
                                                           <div class="ndiv yes-no-but-holder">
                                                               
                                                               <div class="ndiv ans-show-but"> 
                                                                   <?php
                                                                        $m=$k+1;
                                                                        $obj0 = new commonFunctions();
                                                                        $dbh0 = $obj0->dbh;
                                                                        $query0 = $dbh0->prepare("select * from pig_user_answer where user_id='".$_SESSION['user_id']."' and property_id='".$_REQUEST['id']."' ORDER BY id ASC LIMIT $k,1");
                                                                        $query0->execute(); 
																		
                                                                        if ($query0->rowCount() > 0) {
                                                                        $rows_answers = $query0->fetch(PDO::FETCH_ASSOC);
                                                                            echo str_replace("_"," ",$rows_answers['answer']);
                                                                        }
                                                                        ?>
                                                                </div>
                                                        </div>
                                                   </div>
                                               </div>
                                                    </div>
                                <?php if($my_counts_questions2>$m){ ?>
                                
                                        <style>.property-query-questions{ min-height: 163px;}</style>
                                    <div class="ndiv yes-no-but-holder" style="height: 20px;width: initial;margin-bottom: 20px;">
                                        <?php
                                        if($m>1)
                                        {
                                        ?>
                                        <button class="answer-choise-yes-no" onclick="go_back('<?php echo $rows_questions['id']; ?>','<?php  echo $counts_questions2; ?>','<?php  echo $k; ?>')" style="color:#fff;font-weight: bold;padding: 5px 10px;background: #8173c4;">Back</button>
                                        <?php }
                                        echo $my_counts_questions2."---".$m."---".$k;
                                        
                                        ?>
                                        
                                        
                                        <button class="answer-choise-yes-no" style="color:#fff;font-weight: bold;padding: 5px 10px;background: #8173c4;" onclick="hide_me('<?php echo $rows_questions['id']; ?>','<?php  echo $counts_questions2; ?>','<?php  echo $k; ?>')">Next</button>
                                    </div>
                                <?php } else {?>   
                                <style>.property-query-questions{ min-height: 163px;}</style>
                                    <div class="ndiv yes-no-but-holder" style="height: 20px;width: initial;margin-bottom: 20px;">
                                        <?php
                                        if($m>1)
                                        {
                                        ?>
                                        <button class="answer-choise-yes-no" onclick="go_back('<?php echo $rows_questions['id']; ?>','<?php  echo $counts_questions2; ?>','<?php  echo $k; ?>')" style="color:#fff;font-weight: bold;padding: 5px 10px;background: #8173c4;">Back</button>
                                        <?php } ?>
                                        
                                       </div>
                                <?php } ?>   
                                        
                                </div>
                                            <?php
                                            $k++;
                                        }
                                        
                                    }
                                    ?>
                                </div>
                    	</div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    	<!----/page section----->
        
        <!---- Partner Section ---->
        
        <div class="ndiv partner-section">
        	<div class="ndiv primary-center-holder">
            	<div class="ndiv primary-center-in">
                	<div class="ndiv partner-images-holder">
                    	<div class="ndiv partnerimage partnerimage1">
                        </div>
                        <div class="ndiv partnerimage partnerimage2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!---- /Partner Section --->
        
<!------Script section------>
<!----/page section----->

<?php include 'footer.php'; ?> 
<!----- popup script ------------>
<script src="<?php echo $assets; ?>js/break-bs-popup.js"></script>
<!----------select - js --------->
<script src="<?php echo $assets; ?>js/amazing_select.js"></script>

<script>
  function hide_me(id,end,cur)
  {
      $('#hide'+id).css('display','none');
      var ids=eval(id)+1;
      $('#hide'+ids).css('display','block');
     
  }
    
  function go_back(id,end,cur)
  {
      $('#hide'+id).css('display','none');
      var ids=eval(id)-1;
      $('#hide'+ids).css('display','block');
     
  }  
    
 $('#accept_btn').click(function(){
      $('.popup-message').html('');
      $('.yello-menu').trigger('click'); 
      $('.popup-header').html('NOTIFICATION');
      var html_data="<p>Please enter any comments/notes<p><textarea style='border: 1px solid #000;' cols='100' rows='100' class='register-fields' id='txtaccept' name='txtaccept'/><br/><input type='hidden' value='accept' name='accept' id='accept'>";
      $('.popup-message').html('');  
      $('.popup-message').html(html_data);
      var link_data="<a class='answer-choise-yes-no' style='width: auto;padding: 7px 10px;color:#fff;background-color: #8173c4;' href='upload.php?id=<?php echo $_REQUEST['id']; ?>' alt='Upload'>Upload Document</a>";
      $('#link_td').html(link_data);
      
 });   
  $('#reject_btn').click(function(){
      $('.popup-message').html('');
     $('.yello-menu').trigger('click'); 
      $('.popup-header').html('NOTIFICATION');
      var html_data="<p>Please give your reason for rejecting</p><select id='reason_for' name='reason_for'><option value=''>Select</option>";
      <?php
        $query1 = $dbh1->prepare("select * from pig_reasons order by id asc");
        $query1->execute();
        if ($query1->rowCount() > 0) {
            while($rows_reason = $query1->fetch(PDO::FETCH_ASSOC))
            {
                ?>
                 html_data+="<option value='<?php echo $rows_reason['reason'] ?>'><?php echo $rows_reason['reason'] ?></option>";    //$rows_reason   
                <?php
            }
          
        }
      
      ?>
      
      
      html_data+="</select><br/><input type='hidden' value='reject' name='accept' id='accept'>";
      $('.popup-message').html('');
      $('.popup-message').html(html_data);
 });   
 
$('.close-it').click(function(){
     if($('#accept').val()=='accept')
     {
          var status=1;
          var resulten='Accepted';
          if($('#txtaccept').val()=='')
          {
              $('.popup-message').html('');
              $('.popup-message').html('Please Enter The Reason');
              return false;
          }
          var reason=$('#txtaccept').val();
     }
     else if($('#accept').val()=='reject')
     {
        var status=0;
        var resulten='Rejected';
        if($('#reason_for').val()=='')
          {
              $('.popup-message').html('');
              $('.popup-message').html('Please Choose The Reason');
              return false;
          }
          var reason=$('#reason_for').val();
     }
     else
     {
         return false;
     }
     var property_id='<?php echo $_REQUEST['id'] ?>';
     $.ajax({
            type: "POST",
            data: "reason=" + reason + "&property_id=" + property_id + "&status=" + status,
            url: "<?php echo $base_url; ?>update_property.php",
            success: function (result) {
                console.log(result);
                $('#resulten_td').html('');
                $('#resulten_td').html(resulten);
                $('.popup-message').html('');
                $('.yello-menu').trigger('click'); 
                $('.popup-header').html('NOTIFICATION');
                $('.popup-message').html('Thank you for the comment');
                return false;
                

            }
        });
     
 });  

 
    
    
 function get_rate(user_id, property_id)
    {
        $.ajax({
            type: "POST",
            data: "user_id=" + user_id + "&property_id=" + property_id,
            url: "<?php echo $base_url; ?>admin/zoopla_process.php",
            success: function (result) {
                console.log(result);
                $('#get_rate_value'+property_id).html(result);
                $('#get_rate'+property_id).hide();

            }
        });
    }
$(document).ready(function(e) {
	$('#amazing-select-1').amazing_select();
});

</script>


</body>
</html>
