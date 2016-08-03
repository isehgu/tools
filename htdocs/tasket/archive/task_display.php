<?php
	require_once "base_function.php";
	f_dbConnect();
	
	$id = $_POST['id'];
	
	f_displayTask($id);

?>