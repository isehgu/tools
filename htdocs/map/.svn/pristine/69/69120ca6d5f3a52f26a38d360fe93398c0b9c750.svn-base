<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

if (!securePage($_SERVER['PHP_SELF'])){die();}

//Links for logged in user
if(isUserLoggedIn()) {
	echo "
	<ul>
	<li><a href='$websiteUrl'>MAP Home</a></li><br>
	<li><a href='uc_account.php'>Account Home</a></li>
	<li><a href='uc_user_settings.php'>My Settings</a></li>
	<li><a href='uc_logout.php'>Logout</a></li>
	</ul>";

	//Links for permission level 2 (default admin)
	if ($loggedInUser->checkPermission('Administrator')){
	echo "
	<ul>
	<li><a href='admin_configuration.php'>Admin Configuration</a></li>
	<li><a href='admin_users.php'>Admin Users</a></li>
	<li><a href='admin_permissions.php'>Admin Permissions</a></li>
	<li><a href='admin_pages.php'>Admin Pages</a></li>
	</ul>";
	}
}
//Links for users not logged in
else {
	echo "
	<ul>
	<li><a href='index.php'>Home</a></li>
	<li><a href='uc_login.php'>Login</a></li>
	<li><a href='uc_register.php'>Register</a></li>
	<li><a href='uc_forgot-password.php'>Forgot Password</a></li>";
	if ($emailActivation)
	{
	echo "<li><a href='uc_resend-activation.php'>Resend Activation Email</a></li>";
	}
	echo "</ul>";
}

?>
