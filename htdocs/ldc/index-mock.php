<?php
	require_once "base_function.php";
	f_dbConnect();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IORS Order Distribution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="ldc.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>


    <div class="container-fluid">
		<h2>IORS Production Transaction Distribution</h2>
		<p>Distribution by Svc Group and Adapter</p>
		<br>
		<div class="row-fluid">
			<div class="span12">
				<form action="#" method="post">
					<div class="input-prepend">
						<span class="add-on">From</span>
						<input type="text" placeholder="YYYY-MM-DD">
					</div>
					<div class="input-prepend input-append">
						<span class="add-on">To</span>
						<input type="text" placeholder="YYYY-MM-DD">
						<button type="submit" class="btn btn-primary">Search</button>
						<button href="#" class="btn">Clear Search</button>
					</div>
					
				</form>
			</div>
		</div>
		<div class="row-fluid">
			<div class="alert alert-info span12">
				<h4>[2013-05-13] .... [2013-06-03]</h4>
				
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-hover table-condensed">
					<thead>
						<tr>
							<th>Overall Traffic</th>
							<th>Overall New Order(N)</th>
							<th>Overall Cancel(C)</th>
							<th>Overall NewOrderMultiLeg(OM)</th>
							<th>Overall Replace(R)</th>
							<th>Overall NewOrderCross(OC)</th>
							<th>Overall MultiLegCancelReplace(MC)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>800</td>
							<td>800</td>
							<td>800</td>
							<td>800</td>
							<td>800</td>
							<td>800</td>
							<td>800</td>
						</tr>
					</tbody>
				</table>
				<button href="detail.php" class="btn btn-primary">Detail View</button>
			</div>
		</div>
		<br>
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-hover table-condensed">
					<thead>
						<tr>
							<th>Service Group/Adapter</th>
							<th>Total Traffic</th>
							<th>New Order(N)</th>
							<th>Cancel(C)</th>
							<th>NewOrderMultiLeg(OM)</th>
							<th>Replace(R)</th>
							<th>NewOrderCross(OC)</th>
							<th>MultiLegCancelReplace(MC)</th>
						</tr>
					</thead>
					<tbody>
						<tr class="info">
							<td><button class="btn btn-small btn-inverse disabled">BSI.ORA.1</button></td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr>
							<td>ASN04E-20471</td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr>
							<td>ASN04E-20471</td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr>
							<td>ASN04E-20471</td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr>
							<td>ASN04E-20471</td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr>
							<td>ASN04E-20471</td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr class="info">
							<td><button class="btn btn-small btn-inverse disabled">BSI.ORA.2</button></td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr class="info">
							<td><button class="btn btn-small btn-inverse disabled">BSI.ORA.3</button></td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
						<tr class="info">
							<td><button class="btn btn-small btn-inverse disabled">BSI.ORA.4</button></td>
							<td>200</td>
							<td>150</td>
							<td>10</td>
							<td>10</td>
							<td>10</td>
							<td>20</td>
							<td>0</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>

  </body>
</html>
