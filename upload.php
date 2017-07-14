<?php
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    echo("<script>location.href = '" . $base_url . "signin.php';</script>");
}
?>  

<!-----page section---->
<link rel="stylesheet" href="<?php echo $assets; ?>css/lightbox.min.css">
<div class="ndiv page-section">
    <div class="ndiv image-baaner">
        <p class="pmzero page-title">Documents</p>
    </div>
    <div class="ndiv primary-center-holder">
        <div class="ndiv primary-center-in">
            <div class="ndiv property-detail-section">
                <table class="property-detail-table">
                    <tr>
                        <th class="new">SL NO</th>
                        <th>Property</th>
                        <th>Document</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
                    $k = 1;
                    $obj1 = new commonFunctions();
                    $i = 1;
                    $dbh = $obj1->dbh;
                    $status = false;
                    $row = '';
                    $query = $dbh->prepare("select * from pig_documents where `user_id`=:user_id");
                    $query->bindParam(":user_id",$_SESSION['user_id']);
                    $query->execute();
                    $counts_questions = $query->rowCount();
                    if ($query->rowCount() > 0) {
                        $status = true;
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $k; ?>
                                </td>
                                <td>
                                    <?php
                                    $obj2 = new commonFunctions();
                                    $dbh2 = $obj2->dbh;
                                    $query2 = $dbh2->prepare("select * from pig_user_answer where property_id=:property_id and  user_id=:user_id order by id asc limit 1");
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
                                    
                                    <?php
                                    $info = new SplFileInfo($row['document']);
                                    $ext = $info->getExtension();
                                    if($ext=='png' || $ext=='jpg' || $ext=='jpeg' || $ext=='gif'){ ?>
                                        <a style="width: auto;padding: 5px 7px;color: #fff;background-color: #8173c4;border-radius: 13px;" class="example-image-link" href="https://www.propertypig.co.uk/assets/docs/<?php echo $row['document']; ?>" data-lightbox="example-<?php echo $k; ?>"><span class="example-image">Click To Preview</span></a><?php
                                    }
                                    else{ ?>
                                        <a style="width: auto;padding: 5px 7px;color: #fff;background-color: #8173c4;border-radius: 13px;" class="example-image-link" href="https://www.propertypig.co.uk/assets/docs/<?php echo $row['document']; ?>"><span class="example-image">Click To Preview</span></a><?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a onclick="return confirm('This operation will delete this record!')" href="delete_document.php?id=<?php echo $row['id']; ?>&parent_id=<?php echo $_REQUEST['id'] ?>"><i style="font-size: 14px;color: #8173c4;" class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                             
                                </td>


                             </tr>  



                                <?php
                                $k++;
                            }
                        } else {
                            ?>
                        <tr><td colspan="5"><div style="margin-top: 25px;">No Result Found</div></td></tr>
                        <?php
                    }
                    ?>








                </table>
            </div>
            
            <div class="ndiv property-querys-holder" style="padding-top: 75px;">
                <div class="ndiv property-querys-center">
                    <div class="ndiv property-querys-in">
                        <div class="ndiv property-query-title">
                            Upload Documents
                        </div>
                        
                        <form action="upload_document.php?id=<?php echo $_REQUEST['id'] ?>" method="post" onsubmit="return upload_document();" enctype='multipart/form-data'> 
                            <div class="ndiv property-query-questions">
                                <div class="ndiv question-single">
                                    <p class="pmzero question"><input type="file" name="file" id="file"></p>
                                    <input type="hidden" name="property_id" id="property_id" value="<?php if($_REQUEST['id']){echo $_REQUEST['id'];}else{echo "0";} ?>">
                                    <div class="ndiv">
                                        <div class="ndiv yes-no-but-holder">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ndiv button-acceptace" style="float: right;height: 20px;width: initial;margin-bottom: 20px;">
                                <button type="submit" style="padding: 5px 10px;">Upload</button>
                            </div>
                        </form>     

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
<!----/page section---->
<?php include 'footer.php'; ?> 
<script src="<?php echo $assets; ?>js/lightbox.min.js"></script>
<!----- popup script ------------>
<script src="<?php echo $assets; ?>js/break-bs-popup.js"></script>
<!----------select - js -------->
<script src="<?php echo $assets; ?>js/amazing_select.js"></script>

<script>
    /*upload validation code here*/
        function upload_document()
        {
            if($('#file').val()=='')
            {
                $('.yello-menu').trigger('click');
                $('.popup-header').html('');
                $('.popup-message').html('');
                $('.popup-header').html('Warning');
                $('.popup-message').html('Please Upload Document');
                $('#file').focus();
                return false;
            }
        }
    /*upload validation code here*/
</script>


</body>
</html>
