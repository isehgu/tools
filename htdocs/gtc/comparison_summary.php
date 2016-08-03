<?php
  require_once('base_function.php');
  f_dbConnect();
  //starting date
  //$timeruntype = '';
  if(isset($_GET['timeruntype'])) $timeruntype = $_GET['timeruntype'];
  if(isset($_GET['day_id'])) $day_id = $_GET['day_id'];

  //test code
  //echo $timeruntype;
  //timeruntype shows whether it's for 6pm/10pm/2am/3am/4am checkout
  //then echo html to display it.
  //timeruntype should be sent by javascript/ajax
  f_displayComparisonSummary($timeruntype,$day_id);
?>
