<?php
	require_once "base_function.php";
	f_dbConnect();
	f_updateTask(); //$_POST should be special global so, all functions have access to it
	
	header("Location: index.php");
?>