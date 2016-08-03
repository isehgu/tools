<?php
  require_once('base_function.php');
  f_dbConnect();
  //Possible sources --
  //obsdb, obsdb_precise,obsdb_iors
  //mp,mp_precise,mp_iors
  //iors
  //precise
  $source1 = $_GET['s1'];
  $source1_runtimetype = $_GET['s1type'];
  $source2 = $_GET['s2'];
  $source2_runtimetype = $_GET['s2type'];
  $day_id = $_GET['day_id'];

  $start_date = f_getStartDate($day_id);

  //timeruntype shows whether it's for 6pm/10pm/2am/3am/4am checkout

?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GTC Comparison Detail</title>
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="foundation-icons/foundation-icons.css" />
    <link rel="stylesheet" href="ise/gtc.css" />
    <!--<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>-->

    <script src="js/vendor/modernizr.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/fastclick.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/stickyfloat.js"></script>
    <script src="js/jquery.visible.js"></script>
    <script src="ise/gtc.js"></script>
  </head>
  <body>
    <div id='content'>
        <div class='row section-wrapper big-table-wrapper'>
            <div class='small-12 columns small-centered'>
                <div class='section-separator section-separator-long'></div>
                <div class='separator-circle medium-circle'>Start Date <?php echo"$start_date";?></div>
                <div class='section-separator section-separator-long'></div>
                <div class='clear-float'></div>
            </div>
        </div>
        <div class='row section-wrapper big-table-wrapper'>
          <div class='small-12 columns small-centered'>
            <?php f_displayCompareDetail($source1,$source1_runtimetype,$source2,$source2_runtimetype,$day_id);?>
          </div>
        </div>

    </div><!--end of content-->


    <script>
      $(document).foundation();
    </script>

  </body>
</html>
