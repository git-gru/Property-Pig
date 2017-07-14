<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userid']) && $_SESSION['userid'] == '') {
    console . log($_SESSION['userid']);
    header('location:index.php');
}
?>

<?php include( 'sidemenu.php' ); ?>
<div id="wrapper">

    <!-----header section----->
    <div class="ndiv header-section-2">
        <div class="ndiv logo-b-holder">
            <img class="imgres" src="image/logo_b.png" alt="logo" />
        </div>
        <a href="logout.php">
            <div class="ndiv logout-button-holder">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </div>
        </a> 
    </div>
    <!----/header section----->

    <!---- menu tab section--->

    <div class="ndiv tab-menu-section">
        <ul class="menu-ul tab-menu-ul">
            <li class="active">list of customers</li>
<!--            <li>reports</li>
            <li>Questions</li>-->
        </ul>
    </div>

    <!----/menu tab section--->

    <!----contetn section----->
    <div class="ndiv tab-content-section">

        <div class="ndiv tab active tab-sec-one">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Zip code</th>
                        <th>Status</th>
                        <th>Active/Inactive</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Zip code</th>
                        <th>Status</th>
                        <th>Active/Inactive</th>
                        <th>Details</th>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>Jibin</td>
                        <td>9876543210</td>
                        <td>123456</td>
                        <td>Reviewed user</td>
                        <td><button class="activebutton">Inactive</button></td>
                        <td>
                            <p class="pmzero details-icon">
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                                <i class="fa fa-paragraph" aria-hidden="true"></i>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="ndiv tab tab-sec-two">
            section 2 content
        </div>

        <div class="ndiv tab tab-sec-three">
            section 3 content
        </div>

    </div>
    <!---/contetn section----->

</div>

<!-- /#wrapper -->



<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<!--<script src="js/plugins/metisMenu/metisMenu.min.js"></script>-->



<!-- Custom Theme JavaScript -->
<!--<script src="js/sb-admin-2.js"></script>-->
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    $('.menu-icon-holder, .side-panel-logo-holder > i').click(function (e) {

        if (parseInt($('.side-panel-holder').css('left')) < 0)
        {
            $('.side-panel-holder').css('left', 0);
        } else
        {
            $('.side-panel-holder').css('left', 0 - parseInt($('.side-panel-holder').innerWidth()));
        }
    });

    $('.tab-menu-ul > li').click(function (e) {
        console.log('vjjj');
        $(this).siblings('li').removeClass('active');
        $(this).addClass('active');
        $('.tab-content-section > .tab').each(function (index, element) {
            $(this).removeClass('active');
        });
        $('.tab-content-section > .tab:nth-child(' + ($(this).index() + 1) + ')').addClass('active');

    });

</script>
</body>

</html>
