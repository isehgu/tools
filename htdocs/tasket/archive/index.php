<?php
	require_once "base_function.php";
	f_dbConnect();
?>
	

<!DOCTYPE HTML>
<html>
	<head>
		<title>TaskeT</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<!--bootstrap-->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="tasket.css" rel="stylesheet">
    
		<script src="js/jquery-1.10.2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="tasket.js"></script>
	</head>	
	<body>

			<div id='top' class='navbar navbar-fixed-top'>
				<p id='logo' class='lead pull-left'>TaskeT</p>
				<form class='newform form-inline' method='post' action='addTask.php'>
					<input type='text' name='task' class='large-font input-xxlarge' placeholder='Task Description' required/>
					<input type='text' name='group' class='large-font input-medium' placeholder='Group' maxlength='50'/>
					<button type='submit' class='btn btn-info' id='newsubmitbtn'>Submit</button>
				</form>
			</div>


		
		<div class='container-fluid'>
			<div id='mid'>
			<?php
				f_displayTotal();
			
				f_displayAll();
			?>
			</div>
		</div>
    
		<!-- Modal -->
		<div id="taskmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tasklabel" aria-hidden="true">
		  <div class="modal-header">
			<h3 id="tasklabel">Modal header</h3>
      <a href='' id='deletebtn' class="btn btn-danger pull-right" title='Delete'><i class='icon-trash icon-white'></i></a>
      <a href='' id='completebtn' class="btn btn-primary pull-right" title='Complete'><i class='icon-ok icon-white'></i></a>
		  </div>
		  <div class="modal-body">
			<p>One fine body…</p>
		  </div>
		</div>
		
	</body>
	
</html>