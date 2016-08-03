<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ISE- RFC file Generator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="css/niceforms-default.css" />
</head>

<body><div id="container">

<?php

$people= array(	0=>"Adrian Favio",
				1=>"Akbar Maatra",
				2=>"Alfano Joseph V.",
				3=>"Avedissian Mickael",
				4=>"Callender Stevenson",
				5=>"Carpio Luis",
				6=>"Chung Kwokman",
				7=>"D'Ambola Bret",
				8=>"DeSilva Tharaka",
				9=>"Dhanraj Michelle",
				10=>"Ganeshan Girish",
				11=>"Gu Han",
				12=>"Iyer Sangeetha",
				13=>"Klotz P. Robert",
				14=>"Lin Andy",
				15=>"Loewinger Martin",
				16=>"Mathen Reeba",
				17=>"Mishra Arpit",
				18=>"Moncada Steven",
				19=>"Moret Rafael",
				20=>"Otterbein Karl",
				21=>"Raghunandan Vick",
				22=>"Rozentsvay Dmitriy",
				23=>"Sapienza Daniel");

if(!isset($_POST['submit'])){

echo "<!-- Form generation -->";
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' class='niceform'>";
echo "	<fieldset>";
echo "    	<legend>Contact Info</legend>";
echo "        <dl>";
echo "        	<dt><label for='rfc'>RFC Number:</label></dt>";
echo "            <dd><input type='text' name='rfc' id='rfc' value='' size='32' maxlength='128' /></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='creator'>Creator:</label></dt>";
echo "            <dd>";
echo "            	<select size='1' name='creator' id='creator'>";
echo "                    <option name='creator' value=''>Select Creator</option>";
		for($i=0; $i<=count($people); $i++){
echo "                    <option value='$people[$i]'>$people[$i]</option>";
		}
echo "            	</select>";
echo "            </dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='assignee'>Assignee:</label></dt>";
echo "            <dd>";
echo "            	<select size='1' name='assignee' id='assignee'>";
echo "                	<option name='assignee' value=''>Select Assignee</option>";
		for($i=0; $i<=count($people); $i++){
echo "                    <option name='assignee' value='$people[$i]'>$people[$i]</option>";
		}
echo "                </select>";
echo "            </dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='phone'>Phone Number:</label></dt>";
echo "            <dd><input type='text' name='phone' id='phone' value=''size='32' maxlength='32' /></dd>";
echo "        </dl>";
echo "    </fieldset>";
echo "    <fieldset>";
echo "    	<legend>Procedure</legend>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Implementation:</label></dt>";
echo "            <dd><textarea name='plan' id='plan' value='' rows='5' cols='60'></textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Location:</label></dt>";
echo "            <dd><textarea name='location' id='location' value='' rows='5' cols='60'></textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Verification:</label></dt>";
echo "            <dd><textarea name='verification' id='verification' value='' rows='5' cols='60'></textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Fall back:</label></dt>";
echo "            <dd><textarea name='fallback' id='fallback' value='' rows='5' cols='60'></textarea></dd>";
echo "        </dl>";
echo "    </fieldset>";
echo "    <fieldset class='action'>";
echo "    	<input type='submit' name='submit' id='submit' value='Generate my RFC file' />";
echo "    </fieldset>";
echo "</form>";
echo "<!-- End Form generation -->";

}else{
if ((!isset($_POST['rfc']) || ($_POST['rfc']=='')) || (!isset($_POST['creator']) || ($_POST['creator']=='')) || (!isset($_POST['assignee']) || ($_POST['assignee']=='')) || 
(!isset($_POST['phone']) || ($_POST['phone']=='')) || (!isset($_POST['plan']) || ($_POST['plan']=='')) || (!isset($_POST['location']) || ($_POST['location']=='')) || 
(!isset($_POST['verification']) || ($_POST['verification']=='')) || (!isset($_POST['fallback']) || ($_POST['fallback']==''))){

$rfc=$_POST['rfc'];
$creator=$_POST['creator'];
$assignee=$_POST['assignee'];
$phone=$_POST['phone'];
$plan=$_POST['plan'];
$location=$_POST['location'];
$verification=$_POST['verification'];
$fallback=$_POST['fallback'];

echo "<div id='error'>Please enter all the fields!</div>";

echo "<!-- Form generation -->";
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' class='niceform'>";
echo "	<fieldset>";
echo "    	<legend>Contact Info</legend>";
echo "        <dl>";
echo "        	<dt><label for='rfc'>RFC Number:</label></dt>";
echo "            <dd><input type='text' name='rfc' id='rfc' value='$rfc' size='32' maxlength='128' /></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='creator'>Creator:</label></dt>";
echo "            <dd>";
echo "            	<select size='1' name='creator' id='creator'>";
echo "                    <option name='creator' value=''>Select Creator</option>";
		for($i=0; $i<=count($people); $i++){
echo "                    <option value='$people[$i]'>$people[$i]</option>";
		}
echo "            	</select>";
echo "            </dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='assignee'>Assignee:</label></dt>";
echo "            <dd>";
echo "            	<select size='1' name='assignee' id='assignee'>";
echo "                	<option name='assignee' value=''>Select Assignee</option>";
		for($i=0; $i<=count($people); $i++){
echo "                    <option name='assignee' value='$people[$i]'>$people[$i]</option>";
		}
echo "                </select>";
echo "            </dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='phone'>Phone Number:</label></dt>";
echo "            <dd><input type='text' name='phone' id='phone' value='$phone' size='32' maxlength='32' /></dd>";
echo "        </dl>";
echo "    </fieldset>";
echo "    <fieldset>";
echo "    	<legend>Procedure</legend>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Implementation:</label></dt>";
echo "            <dd><textarea name='plan' id='plan' rows='5' cols='60'>$plan</textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Location:</label></dt>";
echo "            <dd><textarea name='location' id='location' rows='5' cols='60'>$location</textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Verification:</label></dt>";
echo "            <dd><textarea name='verification' id='verification' rows='5' cols='60'>$verification</textarea></dd>";
echo "        </dl>";
echo "        <dl>";
echo "        	<dt><label for='plan'>Fall back:</label></dt>";
echo "            <dd><textarea name='fallback' id='fallback' rows='5' cols='60'>$fallback</textarea></dd>";
echo "        </dl>";
echo "    </fieldset>";
echo "    <fieldset class='action'>";
echo "    	<input type='submit' name='submit' id='submit' value='Generate my RFC file' />";
echo "    </fieldset>";
echo "</form>";
echo "<!-- End Form generation -->";

}else{
require_once 'PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('Template.docx');

$rfc=$_POST['rfc'];
$creator=$_POST['creator'];
$assignee=$_POST['assignee'];
$phone=$_POST['phone'];
$plan=$_POST['plan'];
$location=$_POST['location'];
$verification=$_POST['verification'];
$fallback=$_POST['fallback'];

$document->setValue('Value1', $rfc);
$document->setValue('Value2', $creator);
$document->setValue('Value3', $assignee);
$document->setValue('Value4', $phone);
$document->setValue('Value5', date('F j, Y'));
$document->setValue('Value6', $plan);
$document->setValue('Value7', $location);
$document->setValue('Value8', $verification);
$document->setValue('Value9', $fallback);

$document->save('rfc_created\\'.$rfc.'.docx');

echo "<form action='".$_SERVER['PHP_SELF']."' method='post' class='niceform'>";
echo "	<fieldset>";
echo "    	<legend>RFC generated</legend>";
echo "        <dl>";
echo "        	<label for='rfc'>Your RFC #$rfc has been created.<BR/> You can download the file <a href='http://asg.ise.com/ASWiki/rfc/rfc_created/".$rfc.".docx'>here.</a></label>";
echo "        </dl>";
echo "	</fieldset>";
echo "</form>";
}
}

?>


</div></body>
</html>
