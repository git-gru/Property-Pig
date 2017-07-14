<?php
include_once( 'json/class_functions.php' );
include_once( 'json/class_admin.php' );
$k = 1;
$obj1 = new commonFunctions();
$i = 1;
$dbh = $obj1->dbh;
$status = false;
$row = '';
$status = $_REQUEST['status'];

if ($status == 'new') {
    $query = $dbh->prepare("select * from pig_status right join pig_user_answer_rel on pig_status.property_id=pig_user_answer_rel.property_id where pig_status.status<=>NULL  OR pig_status.status='new'");
} else {
    $query = $dbh->prepare("select * from pig_status right join pig_user_answer_rel on pig_status.property_id=pig_user_answer_rel.property_id where  pig_status.status=:status");
$query->bindParam(":status",$status);

}
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
                    invalid Response
                    <?php
                } else {
                    ?><i class="fa fa-gbp" aria-hidden="true"></i> <?php
            echo $row['rate4'];
        }
                ?>
            </td>
        </tr>



        <?php
        $k++;
    }
}
?>