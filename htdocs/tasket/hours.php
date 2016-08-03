<?php
    require_once 'base_function.php';
    f_dbConnect();
    
    $user = array(
                  'jason'=>$_POST['jason'],
                  'carlos'=>$_POST['carlos']
                  );

    
    f_setHours($user);
    
    header("Location: index.php");
?>