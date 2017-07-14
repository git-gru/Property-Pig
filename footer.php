

        
<!-----footer section---->
    	<div class="ndiv footer-section">
        
        	<div class="ndiv footer-first-section">
            	<div class="ndiv center-section-one">
                	<div class="ndiv center-section-in">
                    	<img class="imgres letter-icon" src="<?php echo $assets; ?>image/letter-icon.png" alt="letter icon" />
                		<p class="pmzero get-in-touch">GET IN TOUCH</p>
                        <p class="pmzero get-in-touch-sub">I’d love to hear from you.</p>
                        <img class="imgres pig-home" src="<?php echo $assets; ?>image/footer-pig-home.png" alt="pig home" />
                	</div>
                </div>
            </div>
            
            <div class="ndiv social-icon-holder">
            	<ul class="menu-ul social-icon-ul">
                    <li><i class="fa fa-facebook" aria-hidden="true"></i></li>
<!--                    <li><i class="fa fa-instagram" aria-hidden="true"></i></li>-->
<!--                    <li><i class="fa fa-dribbble" aria-hidden="true"></i></li>-->
                    <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
                    <li><i class="fa fa-linkedin" aria-hidden="true"></i></li>
                </ul>
            </div>
            
            <div class="ndiv footer-logo-holder">
            	<img class="footer-logo" src="<?php echo $assets; ?>image/footer-name-only-icon.png" alt="property pig logo" />
            </div>
            
            <div class="ndiv copy-right-section">
            	<p class="pmzero copy-right-text">&copy; Copyright 2017 Property Pig. Brought to you with the help of Zoopla.</p>
            </div>
            
        </div>
    	<!----/footer section----->
        
    </div>
<!------Script section------>

<!------java script library------>
<script src="<?php echo $assets; ?>js/jquery-1.11.0.min.js"></script>
<!----- bootstrap Js------------->
<script src="<?php echo $assets; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $assets; ?>js/custom.js"></script>
<!----- popup script ------------>
<script src="<?php echo $assets; ?>js/break-bs-popup.js"></script>
</body>
</html>
<script type="text/javascript">
/*ajax loader*/
$(document).ajaxStart(function(){
    $("#wait").css("display", "block");
    });

    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
 /*ajax loader end her*/   
    
$(document).ready(function(){
    $('.yello-menu').click(function(e) {
        $('#target-id').callpopup('.close-it'); //$(YOUR POPUP ID).callpopup(CLOSE BUTTON ID);
    });


    $("#login").click(function(){
        window.location.replace("<?php echo $base_url."signin.php"  ?>");
    });
     $("#home_index").click(function(){
        window.location.replace("<?php echo $base_url; ?>");
    });
    $("#register").click(function(){
        window.location.replace("<?php echo $base_url."register.php";  ?>");
    });
    
     $("#home").click(function(){
        window.location.replace("<?php echo $base_url."signin.php"  ?>");
    });
    
    $("#admin_url").click(function(){
        window.location.replace("<?php echo $base_url."admin/listofcustomer.php"  ?>");
    });
    
    
});
</script>
