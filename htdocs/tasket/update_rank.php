<?php
	require_once "base_function.php";
	f_dbConnect();
	
	$newRank = $_POST["rank"];
	$sir = $_POST["sir"];
	$app = $_POST["app"];
	
	f_updateRank($newRank,$sir,$app);
	echo $newRank;

?>