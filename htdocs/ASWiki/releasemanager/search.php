<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Release Webgister</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<style media="all" type="text/css">@import "css/all.css";</style>

<script type="text/javascript" src="js/checkAll.js"></script> 
<script type="text/javascript" src="js/ShowHide.js"></script> 

</head>
<body>
<?php 
	include 'inc/func_drop_down_menu.php';
	include 'inc/func_print_search_page.php';
	include 'inc/func_updates.php';
?>

<div id="main">
	<div id="header">
			<?php number_updated(); ?>
		<div id="right-corner">
			<?php last_update(); ?>
		</div>
		<!-- <a href="index.html" class="logo">Release Register Website</a> -->
		<ul id="top-navigation">
			<li><span><span><a href="index.php">Homepage</a></span></span></li>
			<li class="active"><span><span><a href="search.php">Search</a></span></span></li>
			<li><span><span><a href="latest.php">Latest installed in Prod</a></span></span></li>
			<li><span><span><a href="sir_import.php">Latest Sire container imported</a></span></span></li>
		</ul>
	</div>
		<?php print_search_home(); ?>
	<div id="footer"></div>
</div>


</body>
</html>
