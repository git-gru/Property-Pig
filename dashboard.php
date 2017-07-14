<?php 
include 'header.php'; 
if(!isset($_SESSION['user_id']))
{
     echo("<script>location.href = '".$base_url."signin.php';</script>");
}
?>
<!----/header section----->

<!-----page section---->
<div class="ndiv page-section">
        	<div class="ndiv image-baaner">
                <p class="pmzero page-title">Properties</p>
            </div>
            <div class="ndiv primary-center-holder">
            	<div class="ndiv primary-center-in">
                	<div class="ndiv property-detail-section">
                    	<table class="property-detail-table">
                        	<tr>
                                <th>Property</th>
                                <th>Post Code</th>
                                <th>Our Offer</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            
                            <?php
                                $k = 1;
                                $obj1 = new commonFunctions();
                                $i = 1;
                                $dbh = $obj1->dbh;
                                $status = false;
                                $row = '';
                                $user_id=$_SESSION['user_id'];
                                $query = $dbh->prepare("select * from pig_user_answer_rel where user_id=:user_id order by property_id asc");
                                $query->bindParam(":user_id",$user_id);

                                $query->execute();
                                $counts_questions = $query->rowCount();
                                if ($query->rowCount() > 0) {
                                    $status = true;
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                    <tr>
<!--                                                        <td class="name">
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
                                                            
                                                        </td>-->
                                                        <td>
                                                    <?php
                                                    $obj1 = new commonFunctions();
                                                    $dbh1 = $obj1->dbh;
                                                    $user_id = $row['user_id'] ;
                                                    $property_id = $row['property_id'] ;
                                                    $query1 = $dbh1->prepare("select * from pig_user_answer where property_id=:property_id and user_id=:user_id order by id asc limit 1");
                                                    $query1->bindParam(":user_id",$user_id);
                                                    $query1->bindParam(":property_id",$property_id);
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
                                                            $user_id = $row['user_id'] ;
                                                            $property_id = $row['property_id'] ;
                                                            $query2 = $dbh2->prepare("select * from pig_user_answer where property_id=:property_id and user_id=:user_id order by id asc limit 1,1");
                                                            $query2->bindParam(":user_id",$user_id);
                                                            $query2->bindParam(":property_id",$property_id);
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
                                                                Invalid Response
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
                                                            $user_id = $row['user_id'] ;
                                                            $property_id = $row['property_id'] ;
                                                            $query2 = $dbh2->prepare("select * from pig_property_status where property_id=:property_id and user_id=:user_id");
                                                            $query2->bindParam(":user_id",$user_id);
                                                            $query2->bindParam(":property_id",$property_id);
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
                                                            else
                                                            {
                                                                echo "Pending";
                                                            }
                                                           
                                                            ?>
                                                        </td>
                                                        
                                                            <?php
                                                            if ($row['rate4'] == 0 || $row['rate4'] == '') {
                                                                ?>
                                                                <td class="arrow-next"></td>
                                                                <?php
                                                            } else {
                                                               ?>
                                                                <td class="arrow-next" id="results"><a href="<?php echo $base_url; ?>property_details.php?id=<?php echo $row['property_id']; ?>">&gt;</a></td>
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
                    
                    <div class="ndiv property-add-button-holder">
                    
                    	<div class="ndiv property-add-button-and-info">
                        
                            <button class="property-add-button" onclick="redi()">+</button>
<!--                        	<span class="info-button">i</span>-->
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
<?php include 'footer.php'; ?> 


<!----------select - js --------->
<script src="<?php echo $assets; ?>js/amazing_select.js"></script>

<script>
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
                var results_wr ="<a href='<?php echo $base_url; ?>property_details.php?id="+property_id+"'>&gt;</a>";
                $('.arrow-next').html(results_wr);

            }
        });
    }
    function redi()
    {
           window.location.href ='<?php echo $base_url."add_property.php";  ?>' ;
    }
    $(document).ready(function (e) {
        $('#amazing-select-1').amazing_select();
    });

</script>


