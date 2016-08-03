<?php
	require_once "base_function.php";
	f_dbConnect();
	if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	$user = $_COOKIE['user'];
?>
	

<!DOCTYPE HTML>
<html lang="en">
    <head>
            <title>Environment Upgrade Setting</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Environment Configuration">
            <meta name="author" content="Autobots">
            <!--bootstrap-->
            <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
            <link href="tac.css" rel="stylesheet">

            <script src="js/jquery-1.11.0.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
						<script src="js/jquery.cookie.js"></script>
						<script async src="tac.js"></script>
    </head>	
	<body>
		<div id="env_config" class="container-fluid">
			<div class="row">
				<div class="col-md-12" id="autoupgrade">
					<h2 class="sub-header inline_header">Env Upgrade Setting</h2>
					<!--
					<div class="search_input pull-right col-xs-5">
							<input id="history_search" class="form-control" placeholder="Search Test History">
					</div>
						<br><br><br>
					-->
					<table class="table">
						<thead>
							<tr>
								<th>Env</th>
								<th>Application</th>
								<th>Version</th>
								<th>Autoupgrade</th>
								<th>Upgrade Status</th>
							</tr>
						</thead>
						<tbody id="autoupgrade_searchable">
							<?php f_tableEnvAutoupgrade(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>