<?php

include ("connection.php");
	
$oauth_provider = $_GET['oauth_provider'];
$uid 			= $_GET['uid'];
$username		= $_GET['username'];
$email			= $_GET['email'];

if($username){
$user = explode(' ', $username);
$fname = $user[0];
$lname = $user[1];
}

	if($_REQUEST['flag']=='register'){

			$sqlreg						=	"insert into mng_registration set 	
			fname						=	'".mysql_real_escape_string($_POST['fname'])."',
			lname						=	'".mysql_real_escape_string($_POST['lname'])."',
			email						=	'".mysql_real_escape_string($_POST['email'])."',
			oauth_uid					=	'".mysql_real_escape_string($_POST['uid'])."',
			oauth_provider				=	'".mysql_real_escape_string($_POST['oauth_provider'])."',
			twitter_oauth_token			=	'".mysql_real_escape_string($_POST['oauth_token'])."',
			twitter_oauth_token_secret	= 	'".mysql_real_escape_string($_POST['oauth_token_secret'])."',	
			entry_date					=	now()";
			$rs							=	mysql_query($sqlreg);
					
			header('Location:welcome.php');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register with twitter</title>
</head>
<body>
<form action="register_twitter.php?flag=register" method="post" name="signup">
<input type="hidden" name="uid" size="20" value="<?php print $uid; ?>">
<input type="hidden" name="oauth_provider" size="20" value="<?php print $oauth_provider; ?>">
<input type="hidden" name="oauth_token" size="20" value="<?php print $_SESSION['oauth_token']; ?>">
<input type="hidden" name="oauth_token_secret" size="20" value="<?php print $_SESSION['oauth_token_secret']; ?>"> 

<div align="center">
	<table border="0" width="550" cellpadding="2" cellspacing="3">
		<tr>
			<td width="150">First Name</td>
			<td>
        	<input type="text" name="fname" class="input1"  value="<?php print $fname; ?>" style="width: 300px;"/></td>
		</tr>
		<tr>
			<td width="150">Last Name</td>
			<td>
        	<input type="text" name="lname" class="input1" value="<?php print $lname; ?>" style="width: 300px;" /></td>
		</tr>
		<tr>
			<td width="150">Email </td>
			<td>
        	<input type="text" name="email" id="email" class="input1" value="" style="width: 300px;" /></td>
		</tr>
		<tr>
			<td width="150">&nbsp;</td>
			<td>
        	<input type="submit" value="Submit" name="B1"></td>
		</tr>
	</table>
</div>
</form>
</body>
</html>