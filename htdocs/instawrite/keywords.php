<?php
    require_once 'base_function.php';
    f_dbConnect();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>InstaWrite</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href='css/instawrite-new.css' rel='stylesheet'>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/instawrite.js"></script>
    </head>
    <body>
        <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container">

                    <a class="brand" href="index-nav.php">Instawrite</a>

                        <ul class="nav">
                          <li class="active"><a href="keywords.php">Keywords</a></li>
                        </ul>
                        <form class="navbar-form pull-right">
                            <input type="text" class="input-large search-query">
                        </form>
                   
                </div>
            </div>
            
        </div>

        <div id="keywords" class="container">
            <table class="table table-bordered">
                <tbody>
                    <?php f_displayAllTags() ?>
                </tbody>
            </table>
        </div>



    </body>
</html>