<?php
  require_once('base_function.php');
  f_dbConnect();
  //starting date
  $day_id = '';
  if(isset($_GET['day_id'])) $day_id = $_GET['day_id'];
?>
<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GTC Comparison</title>
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
      <div id='section-home'>
        <?php f_displayAllCircles($day_id); ?>
      </div>


      <!-- <div id='test' class='section-wrapper'>
       <?php f_displayComparisonSummary(1,2584);?>
      </div>
      <div id='test' class='section-wrapper'>
       <?php f_displayComparisonSummary(1,2584);?>
      </div>
      <div id='test' class='section-wrapper'>
       <?php f_displayComparisonSummary(1,2584);?>
      </div>
      <div id='test' class='section-wrapper'>
       <?php f_displayComparisonSummary(1,2584);?>
      </div> -->



      <div id='check6pm' class='section-wrapper hide'>

      </div>

      <div id='check10pm' class='section-wrapper hide'>
        <!-- <div class='row'>
          <div class='small-12 columns small-centered'>
            <div class='section-separator'></div>
            <div class='separator-circle'>10PM</div>
            <div class='section-separator'></div>
            <div class='clear-float'></div>
          </div>
        </div> -->
      </div>

      <div id='check2am' class='section-wrapper hide'>
        <!-- <div class='row'>
          <div class='small-12 columns small-centered'>
            <div class='section-separator'></div>
            <div class='separator-circle'>2AM</div>
            <div class='section-separator'></div>
            <div class='clear-float'></div>
          </div>
        </div> -->
      </div>

      <div id='check3am' class='section-wrapper hide'>
        <!-- <div class='row'>
          <div class='small-12 columns small-centered'>
            <div class='section-separator'></div>
            <div class='separator-circle'>3AM</div>
            <div class='section-separator'></div>
            <div class='clear-float'></div>
          </div>
        </div> -->
      </div>

      <div id='check4am' class='section-wrapper hide'>
        <!-- <div class='row'>
          <div class='small-12 columns small-centered'>
            <div class='section-separator'></div>
            <div class='separator-circle'>4AM</div>
            <div class='section-separator'></div>
            <div class='clear-float'></div>
          </div>
        </div>
      </div> -->
    </div><!--end of content-->


    <script>
      $(document).foundation();
    </script>

  </body>
</html>
