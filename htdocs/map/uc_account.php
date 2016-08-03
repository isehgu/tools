<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
    header("Location: uc_login.php");
}
require_once("models/header.php");


echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<!--<h1>UserCake</h1>-->
<h1></h1>
<h2>Account</h2>
<div id='left-nav'>";

include("left-nav.php");


$permission_str = '<ul>';
$results = fetchUserPermissions($loggedInUser->user_id);
foreach($results as $permission){
    $details = fetchPermissionDetails($permission['permission_id']);
    $permission_str .= '<li>' . $details['name'] . '</li>';
}
$permission_str .= '</ul>';

echo "
</div>
<div id='main'>
Hey, $loggedInUser->displayname. Your title at the moment is $loggedInUser->title, and that can be changed in the admin panel by an administrator. You currently have these permissions: $permission_str You registered this account on " . date("M d, Y", $loggedInUser->signupTimeStamp()) . ".
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
