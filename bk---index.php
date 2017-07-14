<?php include 'header.php'; ?>       
<!-----page section----->
<style>
    label{margin-bottom: 0px;}
    .form-group {margin-bottom: 5px;}
    .radio-but-holder {
    margin-top: 10px;
    width: initial;
    padding: 0px 4px;
}
</style>
<div class="ndiv page-section">

    <div class="ndiv top-banner-section">
        <div class="ndiv primary-center-holder">
            <div class="ndiv primary-center-in">
                <div class="ndiv banner-content-holder">
                    <div class="ndiv banner-text-section">
                        <div class="ndiv text-holder">
                            <p class="pmzero banner-text-one">
                                We Quote the Right Value for your Property 
                            </p>
                            <p class="pmzero banner-text-two">
                                Nunc a condimentum mauris. liquam id erat eget libero mollis varius et et tortor. 
                            </p>
                        </div>
                    </div>
                    <div class="ndiv banner-input-section">
                        <div class="ndiv pig-image-holder">
                            <img class="imgres input-pig-image" alt="register pig" src="<?php echo $assets; ?>image/pigbg.png" />
                        </div>
                        <div class="ndiv pig-input-section-holde">
                            <div class="ndiv files-of-registration">
                                 <div id="part1">
                                <form action="javascript:void(0);" id="form_list" name="form_list" method="POST" onsubmit="return Submit_form();">
                                   
                                    <p class="pmzero register-header">Enter your Property Details</p>
                                    <?php
                                    $obj1 = new commonFunctions();
                                    $i = 1;
                                    $dbh = $obj1->dbh;
                                    $status = false;
                                    $row = '';
                                    $query = $dbh->prepare("select * from pig_questions where peradd='1' order by id asc");
                                    $query->execute();
                                    $counts_questions = $query->rowCount();
                                    if ($query->rowCount() > 0) {
                                        $status = true;
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <div class="ndiv filelds-holder">

                                                 
                                                        <?php if ($row['type'] == 1 || $row['type'] == 3) { ?>
                                                                <div class="form-group">
                                                                    <label><?php echo $row['question']; ?></label>
                                                                </div>
                                                         <?php } ?>    
                                                 
                                                            <?php
                                                            if ($row['type'] == 1) {
                                                                ?>
                                                                 
                                                                <?php
                                                                $querye = $dbh->prepare("select * from pig_answer where question_id='" . $row['id'] . "'");
                                                                $querye->execute();
                                                                if ($querye->rowCount() > 0) {
                                                                    while ($row_questions = $querye->fetch(PDO::FETCH_ASSOC)) {
                                                                        ?>
                                                
                                                                           <div class="ndiv radio-but-holder">
                                                                                <div class="ndiv radio-lable">
                                                                                    <input type="radio" name="optionsRadios<?php echo $row['id']; ?>" id="optionsRadios<?php echo $row['id'] . $row_questions['answer_id']; ?>" value="<?php echo $row_questions['answer']; ?>">
                                                                                </div>
                                                                                <div class="ndiv radio-lable-text">
                                                                                    <?php echo $row_questions['answer']; ?>
                                                                                </div>
                                                                            </div>
                                                
                                                                        
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                     
                                                            <?php } else if ($row['type'] == 2) { ?>
                                                                    <input class="register-fields" placeholder="<?php echo $row['question']; ?>" id="txt<?php echo $row['id']; ?>"  name="txt<?php echo $row['id']; ?>"  />
                                                            <?php } else if ($row['type'] == 3) { ?>
                                                                <input type="radio" name="options<?php echo $row['id']; ?>" id="optionsRadiosYes" value="yes"> Yes
                                                                <span style="margin-left: 10px;"></span>
                                                                <input type="radio" name="options<?php echo $row['id']; ?>" id="optionsRadiosNo" value="no"> No
                                                                <?php } else if ($row['type'] == 4) {
                                                                    ?>
                                                                <select  class="register-fields property-type" id="dropdownval<?php echo $row['id']; ?>" name="dropdownval<?php echo $row['id']; ?>">
                                                                    <option value=""><?php echo $row['question']; ?></option>
                                                                <?php
                                                                $querys = $dbh->prepare("select * from pig_answer where question_id='" . $row['id'] . "'");
                                                                $querys->execute();
                                                                if ($querys->rowCount() > 0) {
                                                                    while ($row_questions_list = $querys->fetch(PDO::FETCH_ASSOC)) {
                                                                        ?><option value="<?php echo $row_questions_list['answer']; ?>"><?php echo ucfirst(str_replace("_"," ",$row_questions_list['answer'])); ?> </option>  <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <?php } else {
                                                                    
                                                                }
                                                                ?>
                                                       
                                                          

                                                
                                            </div>

                                            <?php
                                            $i++;
                                        }
                                        
                                        ?>
                                    
                                    <button class="get-my-cash-offer" type="submit" >Get My Chash Offer</button>
                                    
                                    
                                    
                                    
                                        <?php
                                    }
                                    ?>
                                </form>
                                 </div>
                                 <div id="part2" style="display: none;">

                                        <form action="javascript:void(0);" id="form_list2" name="form_list2" method="POST" onsubmit="return Submit_part2_form();">
                                   
                                                <p class="pmzero register-header">Enter your Property Details</p>
                                                <?php
                                                $obj1 = new commonFunctions();
                                                $i = 1;
                                                $dbh = $obj1->dbh;
                                                $status = false;
                                                $row = '';
                                                $query = $dbh->prepare("select * from pig_questions where peradd='0' order by id asc");
                                                $query->execute();
                                                $counts_questions = $query->rowCount();
                                                if ($query->rowCount() > 0) {
                                                    $status = true;
                                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                        ?>
                                                        <div class="ndiv filelds-holder">


                                                                    <?php if ($row['type'] == 1 || $row['type'] == 3) { ?>
                                                                            <div class="form-group">
                                                                                <label><?php echo $row['question']; ?></label>
                                                                            </div>
                                                                     <?php } ?>    

                                                                        <?php
                                                                        if ($row['type'] == 1) {
                                                                            ?>

                                                                            <?php
                                                                            $querye = $dbh->prepare("select * from pig_answer where question_id='" . $row['id'] . "'");
                                                                            $querye->execute();
                                                                            if ($querye->rowCount() > 0) {
                                                                                while ($row_questions = $querye->fetch(PDO::FETCH_ASSOC)) {
                                                                                    ?>

                                                                                       <div class="ndiv radio-but-holder">
                                                                                            <div class="ndiv radio-lable">
                                                                                                <input type="radio" name="optionsRadios<?php echo $row['id']; ?>" id="optionsRadios<?php echo $row['id'] . $row_questions['answer_id']; ?>" value="<?php echo $row_questions['answer']; ?>">
                                                                                            </div>
                                                                                            <div class="ndiv radio-lable-text">
                                                                                                <?php echo $row_questions['answer']; ?>
                                                                                            </div>
                                                                                        </div>


                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>

                                                                        <?php } else if ($row['type'] == 2) { ?>
                                                                                <input class="register-fields" placeholder="<?php echo $row['question']; ?>" id="txt<?php echo $row['id']; ?>"  name="txt<?php echo $row['id']; ?>"  />
                                                                        <?php } else if ($row['type'] == 3) { ?>
                                                                            <input type="radio" name="options<?php echo $row['id']; ?>" id="optionsRadiosYes" value="yes"> Yes
                                                                            <span style="margin-left: 10px;"></span>
                                                                            <input type="radio" name="options<?php echo $row['id']; ?>" id="optionsRadiosNo" value="no"> No
                                                                            <?php } else if ($row['type'] == 4) {
                                                                                ?>
                                                                            <select  class="register-fields property-type" id="dropdownval<?php echo $row['id']; ?>" name="dropdownval<?php echo $row['id']; ?>">
                                                                                <option value=""><?php echo $row['question']; ?></option>
                                                                            <?php
                                                                            $querys = $dbh->prepare("select * from pig_answer where question_id='" . $row['id'] . "'");
                                                                            $querys->execute();
                                                                            if ($querys->rowCount() > 0) {
                                                                                while ($row_questions_list = $querys->fetch(PDO::FETCH_ASSOC)) {
                                                                                    ?><option value="<?php echo $row_questions_list['answer']; ?>"><?php echo ucfirst(str_replace("_"," ",$row_questions_list['answer'])); ?> </option>  <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            <?php } else {

                                                                            }
                                                                            ?>



                                                                            
                                                        </div>

                                                        <?php
                                                        $i++;
                                                    }

                                                    ?>

                                                <button class="get-my-cash-offer" type="submit" >Get My Chash Offer</button>




                                                    <?php
                                                }
                                                ?>
                                            </form>
                                     
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ndiv sectio-2-content">
        <div class="ndiv primary-center-holder">
            <div class="ndiv primary-center-in">

                <div class="ndiv top-potions">
                    <div class="ndiv ndiv-1in2 sections" blue>
                        <h6 class="pmzero title-text">Welcome to Property Pig</h6>
                        <p class="pmzero content">Donec convallis porta finibus. Quisque lectus nisl, ultrices pharetra tellus nec, sodales eleifend diam. Suspendisse auctor consectetur lorem, eget suscipit est tempus eu. Curabitur tristique, orci sit amet hendrerit gravida, nisi ante tempus mi, eu euismod orci augue ac ante. Praesent sollicitudin venenatis varius. Nam commodo vestibulum accumsan. Aliquam gravida congue nunc, sed lobortis nisl placerat iaculis. Vestibulum hendrerit velit nisi, nec euismod tortor aliquam at. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus suscipit felis non ante condimentum, nec dictum diam porta. Donec magna odio, efficitur id dui quis, ultricies tempus tellus. Aenean quis nisl pharetra, tincidunt enim at, tempor ante.</p>
                    </div>
                    <div class="ndiv ndiv-1in2 sections" green image>
                        <p class="pmzero image-section-header">
                            Get the right property value from us
                        </p>
                        <div class="ndiv explore-but-holder">
                            <button class="btn_click">EXPLORE</button>
                        </div>
                        <div class="ndiv image-section-of-conter">
                            <img class="imgres content-image1" alt="contet image" src="<?php echo $assets; ?>image/image-1.png" />
                        </div>

                    </div>
                </div> 

                <div class="ndiv top-potions">
                    <div class="ndiv ndiv-1in2 sections" vilot image>
                        <p class="pmzero image-section-header">
                            Get the right property value from us
                        </p>
                        <div class="ndiv explore-but-holder">
                            <button class="btn_click">EXPLORE</button>
                        </div>
                        <div class="ndiv image-section-of-conter">
                            <img class="imgres content-image2" alt="contet image" src="<?php echo $assets; ?>image/image-2.png" />
                        </div>
                    </div>
                    <div class="ndiv ndiv-1in2 sections" pink>
                        <h6 class="pmzero title-text">About Us</h6>
                        <p class="pmzero content">Nunc a condimentum mauris. Aliquam id erat eget libero mollis varius et et tortor. Quisque massa felis, efficitur vel porttitor et, ornare et elit. Integer blandit, turpis a hendrerit euismod, ipsum orci dictum neque, vel finibus nisl turpis non ligula. Morbi hendrerit ligula in quam feugiat venenatis. Nunc eget maximus turpis, sit amet luctus ante. Vestibulum dictum fermentum nibh sed commodo. Nulla urna ante, condimentum eget nunc vel, semper accumsan risus. Phasellus leo justo, rutrum eleifend ex vitae, hendrerit ultrices odio. Nulla facilisi. Quisque vestibulum pretium sapien quis aliquam.

                            <span class="push-from-top">Nullam laoreet diam vel magna mollis porta. Mauris dapibus pellentesque ligula, sit amet varius dolor porttitor non. Mauris semper dolor lacus,.</span></p>
                    </div>
                </div> 

            </div>
        </div>
    </div>


</div>



<!--    <select id="amazing-select-1">-->
<!--                                                
                                                	<option>property 1</option>
                                                    <option>property 2</option>
                                                    <option>property 3</option>
                                                    <option>property 4</option>
                                                
                                                </select>    -->
        
        
<!----/page section----->
<?php include 'footer.php'; ?> 

<!----- popup script ------------>
<script src="<?php echo $assets; ?>js/break-bs-popup.js"></script>
<!----------select - js --------->
<script src="<?php echo $assets; ?>js/amazing_select.js"></script>

<script>
$(".btn_click").on("click", function() {
     $("html, body").animate({ scrollTop: 0 }, "slow");
});    
function Submit_part2_form()
{
    <?php
   $query = $dbh->prepare("select * from pig_questions where peradd='0' and validation='1' order by id asc");
   $query->execute();
   $counts_questions = $query->rowCount();
   if ($query->rowCount() > 0) 
   {
        $status = true;
        while ($row = $query->fetch(PDO::FETCH_ASSOC))
        {
           ?> 
                  
                   
                   <?php
            switch($row['type'])
            {
                
                case 1:
                {
                    
                    ?> 
                    if($('[name="optionsRadios'+<?php echo $row['id']; ?>+'"]:checked').length==0)
                   {
                        $('.yello-menu').trigger('click'); 
                       $('.popup-header').html('');
                       $('.popup-message').html('');
                       $('.popup-header').html('NOTIFICATION');
                       $('.popup-message').html('Please Enter The <?php echo $row['question']; ?>');
                       return false;
                   }
                        
                            
                    <?php
                    break;
                }
                case 2:
                {
                    ?>                    
                        
                    if($('#txt'+<?php echo $row['id']; ?>).val()=='')
                    {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Choose The <?php echo $row['question']; ?>');
                        $('#txt'+<?php echo $row['id']; ?>).focus();
                        return false;
                       
                    }       
                            
                    <?php
                    break;
                }
                case 3:
                {
                    ?>                    
                        
                    if($('[name="options'+<?php echo $row['id']; ?>+'"]:checked').length==0)
                    {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Choose The <?php echo $row['question']; ?>');
                        return false;
                        
                    }    
                            
                    <?php
                    break;
                }
                case 4:
                {
                    ?>                    
                        
                     if($('#dropdownval'+<?php echo $row['id']; ?>).val()=='')
                        {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Select The <?php echo $row['question']; ?>');
                        $('#dropdownval'+<?php echo $row['id']; ?>).focus();
                        return false;
                        }      
                            
                    <?php
                    break;
                }
                default:
                {
                     ?>                    
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Invalid Data');
                        return false;
                            
                    <?php
                }
            }
         }
         
         
            if(isset($_SESSION['user_id']))
            {
                      ?>
                              
                        $.ajax({
                         type: "POST",
                             data: $('#form_list2').serialize(),
                             url: "<?php echo $base_url; ?>question_process3.php",
                             success: function(result) {
                                  console.log(result);
                                  $('.yello-menu').trigger('click'); 
                                  if(result==1)
                                  {
                                             
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Property Successfully Added');
                                             setTimeout(function () {  window.location.href = "<?php echo $base_url; ?>dashboard.php";}, 5000);
                                            return false;   
                                  }
                                  else
                                  {
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Error Found, Try Again');
                                             return false;
                                  }




                                }
                        });      
                        
                <?php      
                }
                else
                {
                    ?>   
                           
                            $('.popup-header').html('');
                            $('.popup-message').html('');
                            
                            $.ajax({
                            type: "POST",
                            data: $('#form_list2').serialize(),
                            url: "<?php echo $base_url; ?>question_process2.php",
                            success: function(result) {
                                   $('.yello-menu').trigger('click'); 
                                   $('.popup-header').html('NOTIFICATION');
                                   $('.popup-message').html(result);
                                  if(result==1)
                                  {
                                             
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Please Sign Up');
                                             setTimeout(function () {  window.location.href = "<?php echo $base_url; ?>signin.php";}, 5000);
                                            return false;   
                                  }
                                  else
                                  {
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Error Found, Try Again');
                                             return false;
                                  }

                                }
                            });      
                        
                        
                        
                            return false;   
                    <?php 
                }
         
         
         
   }  
   
   
   
   
   
   
   
   ?>
           
           
           
           
           
}




function Submit_form(id)
{
   <?php
   $query = $dbh->prepare("select * from pig_questions where peradd='1' order by id asc");
   $query->execute();
   $counts_questions = $query->rowCount();
   if ($query->rowCount() > 0) 
   {
        $status = true;
        while ($row = $query->fetch(PDO::FETCH_ASSOC))
        {
           ?> 
                  
                   
                   <?php
            switch($row['type'])
            {
                
                case 1:
                {
                    
                    ?> 
                    if($('[name="optionsRadios'+<?php echo $row['id']; ?>+'"]:checked').length==0)
                   {
                        $('.yello-menu').trigger('click'); 
                       $('.popup-header').html('');
                       $('.popup-message').html('');
                       $('.popup-header').html('NOTIFICATION');
                       $('.popup-message').html('Please Enter The <?php echo $row['question']; ?>');
                       return false;
                   }
                        
                            
                    <?php
                    break;
                }
                case 2:
                {
                    ?>                    
                        
                    if($('#txt'+<?php echo $row['id']; ?>).val()=='')
                    {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Choose The <?php echo $row['question']; ?>');
                        $('#txt'+<?php echo $row['id']; ?>).focus();
                        return false;
                       
                    }       
                            
                    <?php
                    break;
                }
                case 3:
                {
                    ?>                    
                        
                    if($('[name="options'+<?php echo $row['id']; ?>+'"]:checked').length==0)
                    {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Choose The <?php echo $row['question']; ?>');
                        return false;
                        
                    }    
                            
                    <?php
                    break;
                }
                case 4:
                {
                    ?>                    
                        
                     if($('#dropdownval'+<?php echo $row['id']; ?>).val()=='')
                        {
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Please Select The <?php echo $row['question']; ?>');
                        $('#dropdownval'+<?php echo $row['id']; ?>).focus();
                        return false;
                        }      
                            
                    <?php
                    break;
                }
                default:
                {
                     ?>                    
                        $('.yello-menu').trigger('click'); 
                        $('.popup-header').html('');
                        $('.popup-message').html('');
                        $('.popup-header').html('NOTIFICATION');
                        $('.popup-message').html('Invalid Data');
                        return false;
                            
                    <?php
                }
            }
         }
         
                if(isset($_SESSION['user_id']))
                {
                      ?>
                              
                        $.ajax({
                         type: "POST",
                             data: $('#form_list').serialize(),
                             url: "<?php echo $base_url; ?>question_process.php",
                             success: function(result) {
                                  console.log(result);
                                   $('.yello-menu').trigger('click'); 
                                  if(result==1)
                                  {
                                             
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Please Fill Additional Questions');
                                             $('#part1').css('display','none');
                                             $('#part2').css('display','block');
                                             
 
                                  }
                                  else
                                  {
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Error Found, Try Again');
                                             return false;
                                  }
                                }
                        });      
                        
                <?php      
                }
                else
                {
                    ?>   
                           
                            $('.popup-header').html('');
                            $('.popup-message').html('');
                            
                            $.ajax({
                            type: "POST",
                            data: $('#form_list').serialize(),
                            url: "<?php echo $base_url; ?>question_process.php",
                            success: function(result) {
                                console.log(result);
                                
                                   $('.yello-menu').trigger('click'); 
                                  if(result==1)
                                  {
                                             
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Please Fill Additional Questions');
                                             $('#part1').css('display','none');
                                             $('#part2').css('display','block');
                                             
 
                                  }
                                  else
                                  {
                                             $('.popup-header').html('NOTIFICATION');
                                             $('.popup-message').html('Error Found, Try Again');
                                             return false;
                                  }

                                }
                            });      
                        
                        
                        
                            return false;   
                    <?php 
                }
         
         
         
   }
   
   ?>
    
    
    return false;
}



$('.yello-menu').click(function(e) {
    $('#target-id').callpopup('.close-it'); //$(YOUR POPUP ID).callpopup(CLOSE BUTTON ID);
});

$(document).ready(function(e) {
	$('#amazing-select-1').amazing_select();
        $('.radio-lable').each(function(index, element) {
            $(this).attr('name',$(this).children('input').attr('name'));
        });
});



$('.radio-lable').click(function(e) {
	
	var name = $(this).attr('name');
	
	$('.radio-lable').each(function(index, element) {
		if($(this).attr('name') == name)
		{
			$(this).children('input').prop('checked','');
			$(this).removeClass('active');
		}
    });
	$(this).addClass('active');
	$(this).children('input').prop('checked','checked');
});



</script>
