<?php
if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: login-twitter.php");
    } 
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 1</title>
</head>
<body>
<a href="?login&oauth_provider=twitter"><img border="0" src="images/login_twitter.png" style="cursor:pointer"></a>
</body>
</html>
