<?php

function connect_sir(){

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<div class='table'>";

//echo "<label>Login</label><BR />";
//echo "<input type='text' id='login' name='login' /><BR />";
//echo "<label>Password</label><BR />";
//echo "<input type='password' id='password' name='password' /><BR />";


echo "<div id='login-box'>";
echo "<form method='post' action='process_sir.php' id='SireForm'>";
echo "<H2>Login</H2>";
echo "Please use your SIRe credentials to retrieve the last containers created.";
echo "<br />";
echo "<br />";
echo "<div id='login-box-name' style='margin-top:20px;'>Login:</div><div id='login-box-field' style='margin-top:20px;'><input id='login' name='login' class='form-login' title='login' value='' size='30' /></div>";
echo "<div id='login-box-name'>Password:</div><div id='login-box-field'><input id='password' name='password' type='password' class='form-login' title='Password' value='' size='30' /></div>";
echo "<br />";
echo "<input type='image' src='img/login-btn.png' name='submit' value='submit' width='103' height='42' style='margin-left:90px;' /></form>";
echo "</div>";

echo "</div>";
echo "</div>";
echo "</div>";

}

?>