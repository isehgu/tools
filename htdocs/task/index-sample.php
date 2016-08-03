<?php
	require_once "base_function.php";
	f_dbConnect();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Task Progress</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Han Gu">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/mine.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>


    <div class="container-fluid">
		<div class="row-fluid">
			<div class="span7">
				<span style="font-size:14px"><strong>Test Task 1</strong> -- <span class="badge badge-info">30</span> out of <span class="badge badge-inverse">40</span> Completed</span>
				
				<div class="progress">
					<div class="bar" style="width: 90%;" title="90%"></div>
				</div>
			</div>
			<div class="span5">
				<div></br></div>
				<form class="form myform">
					<div class="input-append">
						<input type="text" class="input myinput" placeholder="Max 50 Characters" maxlength="50">
						<button type="submit" class="btn btn-primary btn-mini"><i class="icon-edit icon-white"></i> Add</button>
						<button type="button" class="btn btn-primary btn-mini" data-toggle="collapse" data-target="#demo">
						<i class="icon-th-list icon-white"></i> Show Detail
						</button>
					</div>					
				</form>
			</div>
			</br>
		</div>

		
			<div id="demo" class="collapse">
				<form class="form">
					<table class='table table-hover table-condensed table-ine'>
						<thead>
							<tr>
								<th style="width: 10%">Completed</th>
								<th style="width: 10%">Remove</th>
								<th style="width: 80%">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="narrow"><input type="checkbox" id="sid1" value="complete"></th>
								<td class="narrow"><input type="checkbox" id="sid1" value="remove"></th>
								<td class="narrow">This is test subitem1</th>
							</tr>
							<tr>
								<td class="narrow"><input type="checkbox" id="sid1" value="complete"></th>
								<td class="narrow"><input type="checkbox" id="sid1" value="remove"></th>
								<td>This is test subitem2</th>
							</tr>
						</tbody>
					</table>
										
					<button type="submit" class="btn btn-primary btn-small">Save</button>
				</form>
			</div>
		
    </div> <!-- /container -->
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/bootstrap.js"></script>

  </body>
</html>
