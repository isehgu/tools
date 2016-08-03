<?php
	//$x = 10;
	$y = 3;
	//$z = $y/$x;
	//echo (int)$z;
	//echo "wow<br>";
	//$z = (int)(($y/$x) * 100)."%";
	//echo '$z%';
	$beta = 'you';
	$beta = $_POST["$y"];
	
	echo "
	<form action='test.php' method='post'>
		<input type='hidden' name='$y' value='World'>
		<button type='submit'>submit</button>
	</form>";
	
	echo $beta;
?>