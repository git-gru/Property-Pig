<?php
if( !isset($_SESSION) ){
    session_start();
}
include_once('class_functions.php');

class adminClass {
    
    /******** check user ********/
    function adminLogin($username,$password) {

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        
        $status = false;
        $row    = '';

        $query  = $dbh->prepare( "SELECT * FROM `admin` WHERE `username`='$username' AND `password`='$password'" );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = true;
            $row    = $query->fetch(PDO::FETCH_ASSOC);
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
        } 
        
        return array(
            'status'   => $status,
            'userdata' => $row
            );

    }
    
    function propertystatus($user_id)
    {
	?>
        
        <table class="property-detail-table">
                        	<tr>
                                <th>Property</th>
                                <th>Post Code</th>
                                <th>Price</th>
                                <th>Property status</th>
                                <th></th>
                            </tr>
                            
                            <?php
                                $k = 1;
                                $obj1 = new commonFunctions();
                                $i = 1;
                                $dbh = $obj1->dbh;
                                $status = false;
                                $row = '';
                                $query = $dbh->prepare("select * from pig_user_answer_rel where user_id='".$user_id."' order by property_id asc");
                                $query->execute();
                                $counts_questions = $query->rowCount();
								//echo $query->rowCount();
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
                                                                    echo "Rejected: ".$rows2['status_reason'];
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
                                                                <td class="arrow-next" id="results"><!--<a href="<?php //echo $base_url; ?>property_details.php?id=<?php //echo $row['property_id']; ?>">&gt;</a>--></td>
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
        
    <?php
        
    }
    

   function questiontype() {


        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT * FROM `pig_question_type` WHERE `status`='1'" );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $questiontype[]=$row;
            }
        }
       $data = array(
            'status'   => $status,
            'questiontype' => $questiontype
        );
        return $data;
    }
    function questions() {


        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT q.*,p.question_type,p.id as typeid FROM `pig_questions` q join pig_question_type p on p.id=q.type " );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $questionid=$row['id'];
                $querycheck  = $dbh->prepare( "SELECT * FROM `pig_answer` where question_id=$questionid " );
                $querycheck->execute();
                $row['options']=array();
                while($rowcheck = $querycheck->fetch(PDO::FETCH_ASSOC))
                {
                    $row['options'][]=$rowcheck['answer'];
                }
                $question[]=$row;
            }
        }
        $data = array(
            'status'   => $status,
            'question' => $question
        );
        return $data;
    }
    
    function reasons($reason)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $option_array=json_decode($option_array);
        $row    = '';
        $query  = $dbh->prepare( "INSERT INTO `pig_reasons`(`reason`) VALUES('$reason')" );
        if ($query->execute()) 
        {
            $status ="true";
        }
        $data = array('status'=>$status);
        return $data;
            
    }
      
    
    function get_resons()
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT * from pig_reasons order by id asc" );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
               $reasons[]=$row;
            }
        }
        $data = array(
            'status'   => $status,
            'reasons' => $reasons
        );
        return $data;
    }
    
    function addQuestion($question,$answertype,$questiontype,$option_array,$drop_option_array) {


        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $option_array=json_decode($option_array);
        $row    = '';
        $query  = $dbh->prepare( "INSERT INTO `pig_questions`(`question`,`type`,`peradd`)
                                                     VALUES('$question','$answertype','$questiontype')" );
//        $query->execute();
        if ($query->execute()) {
            $status ="true";
            $lastid=$dbh->lastInsertId();

            if($answertype==1)
            {

                foreach( $option_array as $val )
                {
                    $answerquery  = $dbh->prepare( "INSERT INTO `pig_answer`(`question_id`,`answer`)
                                                     VALUES('$lastid','$val')" );
                    $answerquery->execute();
                }
            }
            else if($answertype==4)
            {

                foreach( $drop_option_array as $val )
                {
                    $answerquery  = $dbh->prepare( "INSERT INTO `pig_answer`(`question_id`,`answer`)
                                                     VALUES('$lastid','$val')" );
                    $answerquery->execute();
                }
            }
            
        }
        $data = array(
            'status'   => $status
        );
        return $data;
    }
    
    function deleterequest($id)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";

        $query  = $dbh->prepare( "DELETE FROM `pig_reasons` WHERE`id`='$id'" );
        if ( $query->execute() ) {
            $status = "true";
        }

        return array(
            'status' => $status
        );
    }
    
    
    
    function deletequestion($questionid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";

        $query  = $dbh->prepare( "DELETE FROM `pig_questions` WHERE`id`='$questionid'" );
        if ( $query->execute() ) {
            $query_delete  = $dbh->prepare( "DELETE FROM `pig_answer` WHERE `question_id`='$questionid'" );
            $query_delete->execute();
            $status = "true";
        }

        return array(
            'status' => $status
        );
    }

    function editQuestion($question,$answertype,$questionid,$questiontype,$option_array) {

//         return "hia";
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $option_array=json_decode($option_array);
        $row    = '';
//        echo $questionid;echo"hai";echo $answertype;echo"hai";echo $question;echo"hai";

        $query  = $dbh->prepare( "UPDATE `pig_questions` SET `question`='$question',`type`='$answertype',`peradd`='$questiontype' where id='$questionid'");
        if ($query->execute()) {
            $status ="true";
            if($answertype==1)
            {
                $query_delete  = $dbh->prepare( "DELETE FROM `pig_answer` WHERE `question_id`='$questionid'" );
                $query_delete->execute();
                foreach( $option_array as $val )
                {
                    $answerquery  = $dbh->prepare( "INSERT INTO `pig_answer`(`question_id`,`answer`)
                                                     VALUES('$questionid','$val')" );
                    $answerquery->execute();
                }
            }
        }
        $data = array(
            'status'   => $status
        );
        return $data;
    }
    function customerlist() {


        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT * FROM `pig_user` " );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $userid=$row['user_id'];
                $query_question  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.user_id=$userid order by a.property_id" );
                $query_question->execute();
                while($rowanswer = $query_question->fetch(PDO::FETCH_ASSOC))
                {
                    $row['qusandans'][]=$rowanswer;
                }
                $customerlist[]=$row;
            }
        }
        $data = array(
            'status'   => $status,
            'customerlist' => $customerlist
        );
        return $data;
    }
    
    /*zooplalist pending code here*/
    public function zooplalist()
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        $customerlist=array();
        $row    = '';
        $query  = $dbh->prepare("SELECT distinct(user_id) FROM `pig_user_answer`");
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
//                $userid=$row['user_id'];
//                $query_question  = $dbh->prepare( "SELECT a.*,q.question FROM `pig_user_answer` a join pig_questions q on q.id=a.question_id where a.user_id=$userid" );
//                $query_question->execute();
//                while($rowanswer = $query_question->fetch(PDO::FETCH_ASSOC))
//                {
//                    $row['qusandans'][]=$rowanswer;
//                }
                $customerlist[]=array();
            }
        }
        $data = array(
            'status'   => $status,
            'customerlist' => $customerlist
        );
        return $data;
    }
    /*zooplalist pending code end here*/
    function userstatus($userid,$type)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";
        if($type==0)
        {
            $userstatus=1;
        }
        else{
            $userstatus=0;
        }
        $query  = $dbh->prepare( "UPDATE `pig_user` SET `userStatus`=$userstatus where user_id='$userid'");
        if ($query->execute()) {
            $status = "true";

        }
        $data = array(
            'status'   => $status
        );
        return $data;
    }
    function reviewstatus($userid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "false";

        $query  = $dbh->prepare( "UPDATE `pig_user` SET `reviewStatus`=1 where user_id='$userid'");
        if ($query->execute()) {
            $status = "true";

        }
        $data = array(
            'status'   => $status
        );
        return $data;
    }
    function getdocuments($userid)
    {
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "false";
        $row    = '';
        $query  = $dbh->prepare( "SELECT * FROM `pig_documents` where user_id='$userid'" );
        $query->execute();
        if ($query->rowCount() > 0) {
            $status = 'true';
            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $documentlist[]=$row;
            }
        }
        $data = array(
            'status'   => $status,
            'documentlist' => $documentlist
        );
        return $data;
    }
 }


?>
