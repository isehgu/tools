<?php
	require_once "base_function.php";
	f_dbConnect();
	if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	$user = $_COOKIE['user'];
?>
	

<!DOCTYPE HTML>
<html lang="en">
    <head>
            <title>Complete Test History</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Complete Test History">
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
		<div id="complete_history_view" class="container-fluid">

				<div id="history">
					<h2 class="sub-header inline_header">Complete Test History</h2>
					<div class="search_input pull-right col-xs-5">
							<input id="history_search" class="form-control" placeholder="Search Test History">
						</div>
						<br><br><br>
					<table class="table">
						<thead>
							<tr>
                                <th id='th_header'>Test Status</th>
                                <th>Report</th>
                                <th>Env</th>
                                <th id='th_header'>Request ID</th>
                                <th>Label</th>
                                <th>Test Name</th>
								<th>Requester</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Approval</th>

								<!--<th>Env</th>
								<th>Request ID</th>
								<th>Label</th>
								<th>Requester</th>
								<th>Test Name</th>
								<th>Test Status</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Report</th>
								<th>Approval</th>-->
							</tr>
						</thead>
						<tbody id="history_searchable">
							<?php f_tableHistory($user,'all'); ?>
						</tbody>
					</table>
				</div>

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
		</div>
	</body>
</html>