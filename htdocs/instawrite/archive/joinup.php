<?php
    require_once 'base_function.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Joinup</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <style type='text/css'>
            
            body {
              padding-top: 40px;
              padding-bottom: 40px;
              /*background-color: #f5f5f5;*/
              background-color: #bee5c6;
            }
      
            .form-signin {
              max-width: 400px;
              padding: 19px 29px 29px;
              margin: 0 auto 20px;
              background-color: #fff;
              border: 1px solid #e5e5e5;
              -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                      border-radius: 5px;
              -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                 -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                      box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading {
              margin-bottom: 20px;
            }

        .form-signin input[type="text"],
        .form-signin input[type="submit"]{
              font-size: 18px;
              height: auto;
              margin-right:10px;           
            }
        </style>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        
    </head>
    <body>
        <div class='container'>
            <form class='form-signin form-inline' method='post' action='adduser.php' id='join'>
                <h2 class='form-signin-heading'>Just one click away!</h2>
                <input type='text' class='input-large' placeholder=' Username' name='user'/>
                <input type='submit' class='btn btn-success' value='Join up'>
            </form>
            
        </div>
    
    
    </body>
</html>