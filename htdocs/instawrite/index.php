<?php
    //require_once 'base_function.php';
    //f_dbConnect();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>InstaWrite</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href='css/instawrite.css' rel='stylesheet'>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/instawrite.js"></script>
    </head>
    <body>
        <div id='topsection'>
            <div class='container'>
                <h1 id='heading'>InstaWrite</h1>
                <div class='formblock'>
                    <form id='newform' action='writeup_add.php' method='post'>
                        <textarea id='writeup_input' class='input-xxlarge' rows='15' name='writeup' placeholder='Write you stuff here' required></textarea>
                        <input id='taginput' class='input-xxlarge' type='text' name='tag_list' placeholder='tag1,tag2,tag3,tagX' required>
                        <button type='submit' class='btn btn-inverse btn-large'><strong>Submit</strong></button>
                    </form>
                </div> 
            </div>
        </div>

        <div id='midsection'>
            <div class='container'>
                <form id='searchform'>
                    <div id='control-group' class='control-group'>
                        <div class='controls'>
                            <input id='searchinput' class='input-xxlarge search-query' type='text' name='search_tags' placeholder='Search by tag1,tag2,tag3,tagX'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id='bottomsection'>
            
        </div>

    </body>
</html>