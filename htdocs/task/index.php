<?php
    require_once "base_function.php";
    f_dbConnect();
    
    $cf = $_GET["cf"];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Han Gu">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/mine.css" rel="stylesheet">
    <script src="js/jquery-1.9.1.min.js"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container-fluid">

        <div class="row-fluid" id="main_logo_div">
          <div class="span12 text-center">
            <img class="main_logo_position" src="img\ISELogo.jpg" alt="ISE Logo"/>
          </div>
        </div>
        </br>

        <form class="form-inline" action="addTask.php" method="post">
            <input class="input-xxlarge" id="inputTask" name="newTask" type="text" placeholder="Add a task">
            <button type="submit" class="btn btn-primary">Add Task</button>        
        </form>


        <?php 
            f_displayMain();
            if(isset($cf)){
                echo"<script>$('#$cf').focus();</script>";
            }
        ?>
                
    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="js/bootstrap.js"></script>

  </body>
</html>
