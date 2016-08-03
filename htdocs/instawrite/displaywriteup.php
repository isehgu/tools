<?php
    require_once 'base_function.php';
    f_dbConnect();

    $writeup_id = $_GET['id'];
    $writeup = f_getSingleWriteup($writeup_id);
    $tags_string = f_getTags($writeup_id);
   
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Writeup</title>
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
                    <?php
                        echo"
                            <form id='updateform' action='writeup_update.php' method='post'>
                                <textarea id='writeup_input' class='input-xxlarge' rows='15' name='writeup'>$writeup</textarea>
                                <input id='taginput' class='input-xxlarge' type='text' name='tag_list' value=\"$tags_string\">
                                <input type='hidden' name='writeup_id' value='$writeup_id'>
                                <button type='submit' class='btn btn-inverse btn-large'><strong>Update</strong></button>
                            </form>
                            ";
                    ?>
                </div> 
            </div>
        </div>
        
    </body>
    
    
    
    
</html>