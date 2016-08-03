<?php
	require_once "base_function_carlos.php";
	f_dbConnect();
	if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	$user = $_COOKIE['user'];
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
            <link href="tac_blue.css" rel="stylesheet">

            <script src="js/jquery-1.11.0.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
						<script src="js/jquery.cookie.js"></script>
						<script async src="tac.js"></script>
    </head>	
    <body>
	<nav id="topnav" class="navbar navbar-default navbar-fixed-top navbar-inverse">
		<div class="container-fluid">
			<p class="text-center" id="slogan">
				<button id="runbtn" class="btn btn-primary btn-lg navbar-btn">Run</button> T.A.C. Run!
			</p>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				
				<div id="greeting" class="btn-group">
					<button id="name_btn" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
						Welcome <?php echo $_COOKIE['user'];?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>
				<ul class="nav nav-sidebar">
					<li><a class="sidelink" href="#test_selection">Test Selection <span id="test_stat" class="badge pull-right"><?php echo f_statCount('test');?></span></a></li>
					<li><a class="sidelink" href="#progress">Tests in Progress <span id="progress_stat" class="badge pull-right"><?php echo f_statCount('progress');?></span></a></li>
					<li><a class="sidelink" href="#queue">Tests in Queue <span id="queue_stat" class="badge pull-right"><?php echo f_statCount('queue');?></span></a></li>
					<li><a class="sidelink" href="#history">Test History <span id="history_stat" class="badge pull-right">200</span></a></li>
					<li><a class="sidelink" href="history_complete.php" target="_blank">Complete History <span id="history_stat" class="badge pull-right"><?php echo f_statCount('history');?></span></a></li>
					<li><a class="sidelink" href="#env">Environment Settings <span id="env_stat" class="badge pull-right"><?php echo f_statCount('env');?></span></a></li>
					<li><a href="upgrade.php" target="_blank">Environment Upgrade</a></li>
					<li><a href="tac_stats.php" target="_blank">Accolades</a></li>
					<li><a href="http://ic-aoc01/isxfiles/" target="_blank">Release Register</a></li>
					<li><a href="http://asg.ise.com/tap/ASG_Library_Keywords_Docs.html" target="_blank">Keyword Docs</a></li>
					
					<!--
					<li><a class="sidelink" href="http://localhost/tac/index.php#main">Test Selection <span id="test_stat" class="badge"><?php echo f_statCount('test');?></span></a></li>
					<li><a class="sidelink" href="http://localhost/tac/index.php#progress">Test in Progress <span id="progress_stat" class="badge"><?php echo f_statCount('progress');?></span></a></li>
					<li><a class="sidelink" href="http://localhost/tac/index.php#queue">Test in Queue <span id="queue_stat" class="badge"><?php echo f_statCount('queue');?></span></a></li>
					<li><a class="sidelink" href="http://localhost/tac/index.php#history">Test History <span id="history_stat" class="badge"><?php echo f_statCount('history');?></span></a></li>
					<li><a class="sidelink" href="http://localhost/tac/index.php#env">Env Setting <span id="env_stat" class="badge"><?php echo f_statCount('env');?></span></a></li>
					<li><a href="http://ic-aoc01/isxfiles/">Release Register</a></li>
				-->
				</ul>
			</div>
		</div>

		<div id="main" class="col-sm-9 col-sm-offset-3 col-md10 col-md-offset-2">
			<div id="banner" class="jumbotron">
			<h2>Test Automation Controller (T.A.C.)</h2>
			<ol id="instruction">
				<li><strong>Make sure you view this page in Firefox or Chrome.</strong></li>
				<li>Select the test suite(s) or test case(s) you wish to run.</li>
				<li>Hit the <button class="btn btn-primary disabled">Run</button> button to run the test.</li>
				<li>View tests, results and env stats in the progress, queue, history and env setting tables.</li>
			</ol>
			<blockquote id="disney" class="blockquote-reverse">
				<p>It's kind of fun to do the impossible.</p>
				<footer>Walt Disney</footer>
			</blockquote>
		</div>
			
			<div id="test_selection">	
				<div id="test_with_lock" class="row">
					<div id="lock_checkbox" class="col-md-5 col-sm-5 checkbox">
						<label><input type='checkbox' name='env_lock' value='1' placeholder='Label Your Test Request' title='Feature not yet available'>
													Lock env with test
											</label>
					</div>
					<div id="lock_reason_input" class='col-md-7 col-sm-7 pull-left'>
						<input class='form-control' type='text' name='lock_reason' placeholder='Lock Reason'>
					</div>
				</div>
				<hr>
		
				<h2 class="sub-header inline_header">Test Suites</h2>
					<div class="search_input pull-right col-xs-5">
						<input id="test_request_search" class="form-control" placeholder="Search Test Suite and Test Cases">
					</div>
					<br><br><br>
				<form id="test_request" method="get" action="test_request.php">
				<table class="table">
						<thead>
								<tr>
									<th class='col-md-6 col-sm-6'>Test Suite Name</th>
									<th>Description</th>
								</tr>			
						</thead>
						<tbody id="test_suite_table" class="test_request_searchable">
							<?php f_tableDisplay('suite');?>
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
				<br>
					<table class="table">
						<thead>
								<tr>
									<th class='col-md-6 col-sm-6'>Test Name</th>
									<th>Description</th>
								</tr>					
						</thead>
						<tbody id="test_table" class="test_request_searchable">
							<?php f_tableDisplay('test');?>
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
			</div>
			<div id="progress">
				<h2 class="sub-header inline_header">Test in Progress</h2>
				<div class="search_input pull-right col-xs-5">
						<input id="progress_search" class="form-control" placeholder="Search Tests in Progress">
					</div>
					<br><br><br>
				<table class="table">
					<thead>
						<tr>
							<th>Env</th>
							<th>Action</th>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Process ID</th>
							<th>Start Time</th>
							<th>Requester</th>
							<th>Avg Duration</th>
						</tr>
					</thead>
					<tbody id="progress_searchable">
						<?php f_tableProgress(); ?>
					</tbody>
				</table>
			</div>
			
			
			<div id="queue">
				<h2 class="sub-header inline_header">Test in Queue</h2>
				<div class="search_input pull-right col-xs-5">
						<input id="queue_search" class="form-control" placeholder="Search for Test Queue">
					</div>
					<br><br><br>
				<table class="table">
					<thead>
						<tr>
							<th>Env</th>
							<th>Action</th>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Request Time</th>
							<th>Requester</th>
							<th>Avg Duration</th>
						</tr>
					</thead>
					<tbody id="queue_searchable">
						<?php f_tableQueue(); ?>
					</tbody>
				</table>
			</div>
		
			
			<div id="history">
				<h2 class="sub-header inline_header">Test History</h2>
				<div class="search_input pull-right col-xs-5">
						<input id="history_search" class="form-control" placeholder="Search Test History">
					</div>
					<br><br><br>
				<table class="table">
					<thead>
						<tr>
							<th>Env</th>
							<th>Request ID</th>
							<th>Label</th>
							<th>Test Name</th>
							<th>Test Status</th>
							<th>Start Time</th>
							<th>End Time</th>
							<th>Report</th>
							<th>Approval</th>
						</tr>
					</thead>
					<tbody id="history_searchable">
						<?php f_tableHistory($user,200); ?>
					</tbody>
				</table>
			</div>
	
			
			<div id="env">
				<h2 class="sub-header">Env Setting</h2>
				<table class="table">
					<thead>
						<tr>
							<th>Action</th>
							<th>Env Name</th>
							<th>Lock Reason</th>
							<th>User</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableEnv() ?>
					</tbody>
				</table>
			</div>
	
			
			<div id="agents">
				<h2 class="sub-header">Robot Agents</h2>
				<table class="table">
					<thead>
						<tr>
							<th>Agent</th>
							<th>Status</th>
							<th>Since</th>
						</tr>
					</thead>
					<tbody>
						<?php f_tableAgents() ?>
					</tbody>
				</table>
			</div>
            <a href="http://asg.ise.com/tac/Temp" target="_blank">PAT Reports</a>
		</div><!-- end of main-->
	</div>
   
		
		<div class="modal fade" id="test_detail" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div id="test_modal_header" class="modal-header">
						<h4>Header</h4>
					</div>
					<div id="test_modal_body" class="modal-body">
						
					</div>
					<div id="test_modal_footer" class="modal-footer">
						<form id="comment_form" action="#">
							<input id="new_comment_input" name="new_comment" class="form-control" placeholder="Enter new comment here">
							<input id="modal_footer_rid_input" name="rid" type="hidden" value="">
						</form>
					</div>
				</div>
			</div>
	 </body>
</html>