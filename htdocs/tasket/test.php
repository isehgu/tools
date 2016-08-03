<?php
	require_once "base_function.php";
	f_dbConnect();
?>
	

<!DOCTYPE HTML>
<html>
	<head>
		<title>ASG SIRs</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<!--bootstrap-->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="smartTasks.js"></script>
	</head>	
	<body>
		<div class="container">
			<div id='top' class='navbar navbar-fixed-top'>
				<form class='newform form-inline'>
					<input type='text' class='large-font input-xxlarge' placeholder='Task Description' required/>
					<input type='text' class='large-font' placeholder='Group' maxlength='50'/>
					<button style='margin-top:0px;' type='submit' class='btn btn-primary' id='newsubmit'>Submit</button>
				</form>
			</div>
		</div><!--End of Container-->
		
		<!-- Modal -->
		<div id="cmodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Modal header</h3>
		  </div>
		  <div class="modal-body">
			<p>One fine body…</p>
		  </div>
		  <div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Save changes</button>
		  </div>
		</div>
		
	</body>
	
</html>