<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST['txtname']!='' && $_REQUEST['txtpassword']!='')
{
    $obj1   = new commonFunctions();$i=1;
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $txtpassword =base64_encode($_REQUEST['txtpassword']);
    $txtname =$_REQUEST['txtname'];
    $query  = $dbh->prepare("select * from pig_user where (`email`=:txtname OR `username`=:txtname) and Password=:txtpassword and `userStatus`='1' ");
    $query->bindParam(":txtname",$txtname);
    $query->bindParam(":txtpassword",$txtpassword);

    $query->execute();
    $counts_questions=$query->rowCount();
    if ($query->rowCount() > 0) 
    {
        
            $rows = $query->fetch(PDO::FETCH_ASSOC);
            $user_id=$rows['user_id'];
            
            if(!empty($_SESSION['qn']))
            {
                    $obj2   = new commonFunctions();$i=1;
                    $dbh2    = $obj2->dbh;
                    $status = false;
                    $row    = '';
                    $query2  = $dbh2->prepare("insert into pig_user_answer_rel(`user_id`) values(:user_id)");
                    $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 

                    $query2->execute();
                    $propertyId = $dbh2->lastInsertId();
                   foreach ($_SESSION['qn'] as $value) 
                   {

                        $obj2   = new commonFunctions();
                        $dbh    = $obj2->dbh;
                         
                        $query  = $dbh->prepare("insert into pig_user_answer (`question_id`,`answer`,`question_type`,`user_id`,`property_id`) values(:id,:answer,:typeid,:user_id,:propertyId)");

                        $query->bindParam(":id",$value['question_id'],PDO::PARAM_INT);
                        $query->bindParam(":answer",$value['answer'],PDO::PARAM_STR); 
                        $query->bindParam(":typeid",$value['type'],PDO::PARAM_INT); 
                        $query->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
                        $query->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);


                        $query->execute();
                         
                    }
                    $_SESSION['qn']='';
                    $_SESSION['valid_user']='set';
                    $_SESSION['user_id']=$user_id;
            }
            else
            {
                    $_SESSION['valid_user']='set';
                    $_SESSION['user_id']=$user_id;
            }
            
        echo "1";
       
     }
    else
    {
        echo "0";
    }
}

?>