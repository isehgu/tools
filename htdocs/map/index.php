<?php
  require_once("base_function.php");
?>

<!-- Added change to foundation.css to fix bug with textbox in top-bar:
http://foundation.zurb.com/forum/posts/1348-topbar-text-input-too-big
 -->

<!DOCTYPE html>
<html>

  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>MAP</title>
      <link rel="icon" type="image/ico" href="favicon.ico">
      <link rel="stylesheet" href="css/foundation.css" />
      <link rel="stylesheet" href="foundation-icons/foundation-icons.css" />

      <link rel="stylesheet" href="ise/css/main.css" />
      <link rel="stylesheet" href="ise/css/widgets.css" />
      <link rel="stylesheet" href="ise/css/modal.css" />
      <link rel="stylesheet" href="ise/css/event_history.css" />
      <link rel="stylesheet" href="ise/css/test_live.css" />
      <link rel="stylesheet" href="ise/css/test_history.css" />
      <link rel="stylesheet" href="ise/css/trigger.css" />
      <link rel="stylesheet" href="ise/css/test_request.css" />
      <link rel="stylesheet" href="ise/css/user.css" />
      <link rel="stylesheet" href="ise/css/task.css" />

      <script src="js/vendor/modernizr.js"></script>
      <script src="js/vendor/jquery.js"></script>
      <script src="js/vendor/fastclick.js"></script>
      <script src="js/foundation.min.js"></script>
      <script src="js/stickyfloat.js"></script>
      <script src="js/jquery.visible.js"></script>

      <script src="ise/js/main.js"></script>
      <!-- <script src="ise/js/secondbar.js"></script>
      <script src="ise/js/topbar.js"></script> -->
      <script src="ise/js/trigger.js"></script>
      <script src="ise/js/test_request.js"></script>
      <script src="ise/js/user.js"></script>
      <script src="ise/js/template.js"></script>
      <script src="ise/js/test_history.js"></script>
      <script src="ise/js/event_history.js"></script>
      <script src="ise/js/test_queue.js"></script>
      <script src="ise/js/task.js"></script>
   </head>
  <body>
    <div class='fixed'>
      <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
          <li class="name">
            <h1 id="appname"><a href="index.php">MAP</a></h1>
          </li>
           <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
          <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
          <!-- Left Nav Section -->
          <ul class="left">
            <li>
              <a class='top_nav_link' id="history_btn" href="#">Events</a>
            </li>
            <li><a class='top_nav_link' id="task_btn" href="#">Tasks</a></li>
            <li><a class='top_nav_link' id="template_btn" href="#">Templates</a></li>
            <li><a class='top_nav_link' id="test_btn" href="#">Tests</a></li>
            <li><a class='top_nav_link' id="trigger_btn" href="#">Triggers</a></li>
            <li><a class='top_nav_link' id="statistics_btn" href="#">Statistics</a></li>
            <li><a class='top_nav_link' id="tools_btn" href="#">Tools</a></li>
            <!-- <li><a class='top_nav_link' id="debug_btn" href="#">Debug</a></li> -->
          </ul>

<?php
  f_userHeaderDisplay();
?>

        </section>
      </nav>

      <dl id="slidebar" class="sub-nav">
        <dd><a href="#">item 1</a></dd>
        <dd><a href="#">item 2</a></dd>
      </dl>
    </div><!--end of div fixed-->

    <div id='content'>

      <!-- <div class="row">
        <div class="small-12 columns">
          <iframe src="http://ic-spk01.inf.ise.com:8000/account/insecurelogin?username=bdtreadonly&password=readme&return_to=/app/ise_bdt/bdt_health"
            width=1000,
            height=900>
          </iframe>
        </div>
      </div> -->

    </div><!--End of content-->


    <!--Detailed Modal-->
    <div id="detail_modal" class="reveal-modal" data-reveal>
      <div class='row'>
        <div class='small-12 columns'>
          <h1 class='font_syncopate'>10.0.040 : OAT</h1>
        </div>
      </div>

      <div class='row'>
        <div class='modal_content small-12 columns'>
          <table>
            <thead>
              <tr>
                <th>Latest Event</th>
                <th>Entry Time</th>
              </tr>
            </thead>
            <tbody>
              <tr class='generic_table_row'>
                <td>Test Started</td>
                <td>2014 15:00:23</td>
              </tr>
              <tr class='generic_table_row'>
                <td>Deploy Completed</td>
                <td>2014 14:00:23</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <a class="close-reveal-modal">&#215;</a>
    </div>
    <!--End of Modal-->

    <!-- Foundation JS -->

    <div id="trigger_tests_modal" class="reveal-modal" data-reveal>
    </div>
    <div id="trigger_exec_modal" class="reveal-modal" data-reveal>
    </div>
    <div id="toast_div" class="">
    </div>
    <script>
      $(document).foundation();
    </script>

  </body>
</html>
