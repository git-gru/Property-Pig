<?php
include_once( 'json/class_functions.php' );
include_once( 'json/class_admin.php' );
$k = 1;
$obj1 = new commonFunctions();
$i = 1;
$dbh = $obj1->dbh;
$status = false;
$row = '';
$user_id=$_REQUEST['user_id'];
$query = $dbh->prepare("select * from pig_user_answer_rel where user_id=:user_id");
$query->bindParam(":user_id",$user_id);
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
        //$query1 = $dbh1->prepare("select * from pig_user_answer_rel where user_id='".$user_id."'");
        $query1 = $dbh1->prepare("select * from pig_user_answer where property_id=:property_id and  user_id=:user_id order by id asc limit 1");
$query1->bindParam(":user_id",$row['user_id']);
$query1->bindParam(":property_id",$row['property_id']);
        $query1->execute();
        $counts_questions1 = $query1->rowCount();
        if ($query1->rowCount() > 0) {
            $rows1 = $query1->fetch(PDO::FETCH_ASSOC);
            echo @$rows1['answer'];
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
                                                                ?><i class="fa fa-gbp" aria-hidden="true"></i> <?php echo $row['rate4'];
                                                            } else {
                                                                ?><i class="fa fa-gbp" aria-hidden="true"></i> <?php echo $row['rate4'];
                                                            }
                                                            ?>
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
                            <button type="submit" style="margin-top: 10px;margin-left: 0px;padding: 5px 10px;border-radius: 3px;" onclick="return save_offer_zone('<?php echo $row['user_id']; ?>','<?php echo $row['property_id']; ?>');">Apply Offer</button></p>
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
                            if ($counts_status > 0) 
                            {
                                $rows_status = $query3->fetch(PDO::FETCH_ASSOC);
                                $counts_status='';
                                if(!empty($rows_status))
                                {
                                    $counts_status=$rows_status['status'];
                                }
                             }
                             else
                             {
                                 $counts_status='';
                             }
                            
                        ?>
                        <div class="row">
                            <p>Choose The Status <br/>
                                <select class="form-control" id="status_control<?php echo $row['property_id']; ?>">
                                    <option <?php if($counts_status=='new'){ echo "selected"; }  ?> value="new">New</option>
                                    <option <?php if($counts_status=='passed_to_exit_strategy'){ echo "selected"; }  ?> value="passed_to_exit_strategy">Passed to Exit Strategy</option>
                                    <option <?php if($counts_status=='lost'){ echo "selected"; }  ?> value="lost">Lost</option>
                                    <option <?php if($counts_status=='sold'){ echo "selected"; }  ?> value="sold">Sold</option>
                                </select>    
                            <button type="submit" style="margin-top: 10px;margin-left: 0px;padding: 5px 10px;border-radius: 3px;" onclick="return change_status('<?php echo $row['user_id']; ?>','<?php echo $row['property_id']; ?>');">Save changes</button></p>
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