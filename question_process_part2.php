<?php 
session_start();
include 'admin/json/settings.php'; 
$obj1   = new commonFunctions();
$dbh    = $obj1->dbh;
$status = false;
$row    = '';
$query  = $dbh->prepare("select * from pig_questions where peradd='0' order by id asc");
$query->execute();
$counts_questions=$query->rowCount();
if ($query->rowCount() > 0) 
{
        $status = true;
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            if($row['type']==1)
            {
                 $answer=$_REQUEST['optionsRadios'.$row['id']];
            }
            else if($row['type']==2)
            {
                $answer=$_REQUEST['txt'.$row['id']];
            }
            else if($row['type']==3)
            {
                $answer=$_REQUEST['options'.$row['id']];
            }
            else if($row['type']==4)
            {
                $answer=$_REQUEST['dropdownval'.$row['id']];
            }
            $_SESSION['qn']['question'.$row['id']]=array("question_id"=>$row['id'],"type"=>$row['type'],"answer"=>$answer);
            
            
                if(!empty($_SESSION['qn']))
                {
                     $obj3   = new commonFunctions();
                     $dbh1    = $obj3->dbh;
                     $id=$_SESSION['user_id'];
                     $query1  = $dbh1->prepare("insert into pig_user_answer_rel(`user_id`) values(:id)");
                     $query1->bindParam(":id",$id,PDO::PARAM_INT); 
                     $query1->execute();
                     $propertyId = $dbh1->lastInsertId();
                     foreach ($_SESSION['qn'] as $value) 
                     {

                         $obj2   = new commonFunctions();
                         $dbh    = $obj2->dbh;
                         $query  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:question_id,:answer,:type,:id,:propertyId)");
                         $query->bindParam(":question_id",$value['question_id'],PDO::PARAM_INT);
                         $query->bindParam(":answer",$value['answer'],PDO::PARAM_STR); 
                         $query->bindParam(":type",$value['type'],PDO::PARAM_INT); 
                         $query->bindParam(":id",$id,PDO::PARAM_INT); 
                         $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);


                         $query->execute();
                         //
                      }
                         $_SESSION['qn']='';
                 }
                else
                {
                    echo "0";
                   
                }
            
        }
        echo "1";
}
else
{
    echo "0";
}
?>