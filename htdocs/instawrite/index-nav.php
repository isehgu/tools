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
                      <li><a href="keywords.php">Keywords</a></li>
                    </ul>
                    <form class="navbar-form pull-right">
                        <input type="text" class="input-large search-query">
                    </form>
                </div>
            </div>
            
        </div>
        
            
        </div>
        <div id='topsection'>
            <div class='container'>
                <div class='formblock'>
                    <form id='newform' action='writeup_add.php' method='post'>
                        <textarea id='writeup_input' class='input-xxlarge' rows='15' name='writeup' placeholder='Write you stuff here' required></textarea>
                        <input id='taginput' class='input-xxlarge' type='text' name='tag_list' placeholder='tag1,tag2,tag3,tagX' required>
                        <button type='submit' class='btn btn-inverse btn-large'><strong>Submit</strong></button>
                    </form>
                </div> 
            </div>
        </div>

        <div id='bottomsection'>
           <div id="" class="hero-unit">
                <h1>Search results goes here</h1>
           </div>

        </div>

    </body>
</html>