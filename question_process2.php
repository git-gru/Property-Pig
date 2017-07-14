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
         }
        echo "1";
}
else
{
    echo "0";
}
?>