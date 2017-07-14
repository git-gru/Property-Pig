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
                <p class="pmzero page-title">Notifications</p>
            </div>
            <div class="ndiv primary-center-holder">
            	<div class="ndiv primary-center-in">
                	<div class="ndiv property-detail-section">
                    	<table class="property-detail-table">
                        	<tr>
                                <th>Property</th>
                                <th>Post Code</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                            
                            <?php
                                $k = 1;
                                $obj1 = new commonFunctions();
                                $i = 1;
                                $dbh = $obj1->dbh;
                                $status = false;
                                $row = '';
                                $user_id = $_SESSION['user_id'];
                                $query = $dbh->prepare("select * from pig_notification where user_id=:user_id and status=1 group by property_id order by id asc");
                                $query->bindParam(":user_id",$user_id);

                                $query->execute();
                                $counts_questions = $query->rowCount();
                                if ($query->rowCount() > 0) {
                                    $status = true;
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                <tr id="row_<?php echo $row['property_id']; ?>">
<!--                                                        <td class="name">
                                                             <?php
                                                            $obj2 = new commonFunctions();
                                                            $dbh2 = $obj2->dbh;
                                                       //     $query2 = $dbh2->prepare("select * from pig_user_answer where property_id='" . $row['property_id'] . "' and  user_id='" . $row['user_id'] . "' order by id desc limit 1");
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
                                                    $query1 = $dbh1->prepare("select * from pig_user_answer where property_id=:property_id and user_id=:user_id order by id asc limit 1");
                                                    $query1->bindParam(":property_id",$row['property_id']);
                                                    $query1->bindParam(":user_id",$row['user_id']);

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
                                                            $query2->bindParam(":property_id",$row['property_id']);
                                                            $query2->bindParam(":user_id",$row['user_id']);
                                                            $query2->execute();
                                                            $counts_questions2 = $query2->rowCount();
                                                            if ($query2->rowCount() > 0) {
                                                                $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                                                                echo $rows2['answer'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-gbp" aria-hidden="true"></i>
                                                             <?php
                                                                $obj2 = new commonFunctions();
                                                                $dbh2 = $obj2->dbh;
                                                                $query2 = $dbh2->prepare("select offerPrice from pig_property_offer where offerpropertyId=:property_id and  userid=:user_id order by offerId desc limit 1");
                                                                $query2->bindParam(":property_id",$row['property_id']);
                                                                $query2->bindParam(":user_id",$row['user_id']);
                                                                $query2->execute();
                                                                if ($query2->rowCount() > 0) {
                                                                    $rows2 = $query2->fetch(PDO::FETCH_ASSOC);
                                                                    $offer_price='';
                                                                    echo $offer_price=$rows2['offerPrice'];
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="ndiv button-acceptace" style="margin-top: 0px;">
                                                                <button class="buttons-property red" id="reject_btn" onclick="return accept('<?php echo $row['property_id']; ?>','4','0');" style="width: initial;height: 20px;">Reject</button>
                                                                <button class="buttons-property green"  id="accept_btn" onclick="return accept('<?php echo $row['property_id']; ?>','3','<?php echo $offer_price; ?>');" style="width: initial;height: 20px;">Accept</button>
                                                            </div>
                                                        </td>
                                                         
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
                    
                    <div class="ndiv property-add-button-holder" style="display: none;">
                    
                    	<div class="ndiv property-add-button-and-info">
                        
                            <button class="property-add-button" onclick="redi()">+</button>
                        	<span class="info-button">i</span>
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
    /*accept and reject functionality*/
    function accept(property_id,status,offer)
    {
        $.ajax({
            type: "POST",
            data: "property_id=" + property_id+"&status="+status+"&offer="+offer,
            url: "<?php echo $base_url; ?>property_process.php",
            success: function (result) {
                console.log(result);
                $('.yello-menu').trigger('click');
                $('.popup-header').html('');
                $('.popup-message').html('');
                $('.popup-header').html('Notification');
                if(status==3)
                {
                    $('.popup-message').html('Offer Added Successfully');
                }
                else
                {
                    $('.popup-message').html('Offer Rejected');
                }
                
                setTimeout(function () {  window.location.href = "dashboard.php";}, 3000);                   

            }
        });
    }
    /*accept and reject functionality end here*/
    $(document).ready(function (e) {
        $('#amazing-select-1').amazing_select();
    });

</script>


