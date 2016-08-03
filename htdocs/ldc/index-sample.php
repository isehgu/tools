<?php
	require_once "base_function.php";
	f_dbConnect();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ASG OAT Progress</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>


    <div class="container-fluid">
      <div class="alert alert-info page-header">
        <h1>Ongoing OAT: 6.0 (<?php f_displayTotalCompleted() ?>/<?php f_displayTotalTest() ?>)</h1>
      </div>
	  <div class="row-fluid">
        <div class="span5">
          <div class="alert alert-error">
			<strong>Current target is minimum 10 test cases per tester per week</strong>
		  </div>
        </div>
        <div class="span7">
          <h2>Overall Total</h2>
           <?php f_displayTestProgress('2013-01-15 00:00:00', '2013-05-20 00:00:00') ?>
       </div>
      </div>
	  

      <div class="row-fluid">
		<div class="span4">
          <h2>4/29/13-5/5/13 <button class="btn disabled">5</button></h2>
           <?php f_displayTestProgress('2013-04-29 00:00:00', '2013-05-06 00:00:00') ?>
        </div>
        <div class="span4">
          <h2>5/6/13-5/12/13 <button class="btn disabled">48</button></h2>
           <?php f_displayTestProgress('2013-05-06 00:00:00', '2013-05-13 00:00:00') ?>
        </div>
        <div class="span4">
          <h2>5/13/13-5/19/13 <button class="btn disabled">28</button></h2>
           <?php f_displayTestProgress('2013-05-13 00:00:00', '2013-05-20 00:00:00') ?>
       </div>
        
      </div>
	  
	  <div class="row-fluid">
        <div class="span4">
          <h2>5/21/13-5/26/13 <button class="btn disabled"></button></h2>
          <?php f_displayTestProgress('2013-05-21 00:00:00', '2013-05-27 00:00:00') ?>
        </div>
		
		<div class="span4">
          <h2>5/27/13-5/31/13 Implementation Week!<button class="btn disabled"></button></h2>
          <?php f_displayTestProgress('2013-05-27 00:00:00', '2013-06-01 00:00:00') ?>
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
