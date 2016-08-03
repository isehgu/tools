<?php
	require_once "base_function.php";
	f_dbConnect();
?>
	

<!DOCTYPE HTML>
<html lang="en">
    <head>
            <title>Test Automation Controller</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Test Automation Controller">
            <meta name="author" content="Autobots">
            <!--bootstrap-->
            <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
            <link href="tac.css" rel="stylesheet">

            <script src="js/jquery-1.11.0.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
						<script src="tac.js"></script>
    </head>	
    <body>
	<nav id="topnav" class="navbar navbar-default navbar-fixed-top navbar-inverse">
		<div class="container-fluid">
			<p class="text-center" id="slogan">
				<button id="runbtn" class="btn btn-primary btn-lg navbar-btn">Run</button> T.A.C Run!
			</p>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li><a id="progress_link" href="#progress">Test in Progress</a></li>
					<li><a href="#queue">Test in Queue</a></li>
					<li><a href="#history">Test History</a></li>
					<li><a href="#env">Env Setting</a></li>
					<li><a href="#main">Back to Top</a></li>
				</ul>
			</div>
		</div>
		<div id="main" class="col-sm-9 col-sm-offset-3 col-md10 col-md-offset-2">
			<form id="test_request" method="get" action="test_request.php">
				
		<!-- Label as input field
				<div class="row">
					<div class='col-xs-5'>
						<h2 id='test_label'>Test Rquest Label</h2>
						<input class='form-control input-lg' type='text' name='label' placeholder='Label Your Test Request'>
					</div>
				</div>
		-->
		
				<h2 class="sub-header inline_header">Test Suites</h2>
				
						<!--<input id="test_request_search" class="form-control" placeholder="Filter for Test Suite and Test Cases">-->

				<table class="table">
						<thead>
								<tr>
									<th>Test Suite Name</th>
									<th>Description</th>
								</tr>			
						</thead>
						<tbody id="test_suite_table" class="test_request_searchable">
							
							<!--
							<tr>
								<td><input type="checkbox"> PAT IORS</td>
								<td>PAT Regression for IORS that includes rampup, steady load, and various BSI setup</td>

							</tr>
							<tr>
								<td><input type="checkbox"> OAT ISEApps</td>
								<td>All ISEApps related OAT test cases</td>
							</tr>
						-->
						</tbody>
					</table>
				
				<h2 class="sub-header">Test Cases</h2>
					<table class="table">
						<thead>
								<tr>
									<th>Test Name</th>
									<th>Description</th>
								</tr>					
						</thead>
						<tbody id="test_table" class="test_request_searchable">
							
							<!--
							<tr>
								<td><input type="checkbox"> PAT-PT101</td>
								<td>This is a very long description of this test case.</td>
							</tr>
							<tr>
								<td><input type="checkbox"> PAT-PT102 Long name</td>
								<td>this is a a description</td>
							</tr>
							-->
						</tbody>
					</table>
			</form>
			<hr/>
			
			<div id="progress">
				<h2 class="sub-header inline_header">Test in Progress</h2>

				<table class="table">
					<thead>
						<tr>
							<th>Action</th>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Process ID</th>
							<th>Start Time</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableProgress(); ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div id="queue">
				<h2 class="sub-header inline_header">Test in Queue</h2>
				<form>
						<input id="history_search" class="form-control" placeholder="Filter Test History">
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Action</th>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Request Time</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableQueue(); ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div id="history">
				<h2 class="sub-header inline_header">Test History</h2>
				<form>
						<input id="history_search" class="form-control" placeholder="Filter Test History">
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Test Status</th>
							<th>Request Time</th>
							<th>Start Time</th>
							<th>End Time</th>
							<th>Report</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableHistory(); ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div id="env">
				<h2 class="sub-header">Env Setting</h2>
				<table class="table">
					<thead>
						<tr>
							<th>Action</th>
							<th>Env Name</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableEnv() ?>
					</tbody>
				</table>
			</div>
		</div><!-- end of main-->
	</div>
    </body>
	
</html>