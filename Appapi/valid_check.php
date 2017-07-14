<?php
include_once('class_functions.php');
if($_REQUEST['id']!='')
{
    $id=$_REQUEST['id'];
    $obj1   = new commonFunctions();
    $dbh    = $obj1->dbh;
    $row    = '';
    $query  = $dbh->prepare("update pig_user set userStatus=1 where user_id=:id");
    $query->bindParam(":id",$id);

    $query->execute();
    $counts_questions=$query->rowCount();
    if ($query->rowCount() > 0)
    {
       echo '<div style="background: #EAECED; width: 100%;">
                            <div style="width: 90%; margin: 0px auto; padding-top: 25px; padding-bottom: 25px;">
                                <div style="background: #fff; padding: 25px; box-shadow: 0px 4px 7px 2px #999; border-radius: 0.2em;">
                                    <div style="text-align: center;" >
                                    <div style="width:100px !important; height:100px !important;margin:auto">
                                       <img src="'.$server_url.'logo.png" style="width:100%; height:100%"/>
                                    </div></div>
                                    <div style="margin-top: 45px; margin-bottom: 40px; font-family: sans-serif; font-size: 15px;">
        Dear User,<br /><br />
                                        <b>Welcome to Property Pig</br><br />
                                        You have successfully completed the activation process.
                                        You may login to see the status of your property value request.</br><br />
                                        Thanks<br/>
                                        Property Pig
    </div>

                                </div>
                            </div>
                        </div>';

    }
       else
       {
           echo "Some error occured!!!";
           die();
       }


}

?>