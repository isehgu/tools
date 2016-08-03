<?php
    require_once 'base_function.php';
    $user = isset($_GET['user']) ? $_GET['user'] : $_POST['user'];
    $password = isset($_GET['password']) ? $_GET['password'] : $_POST['password'];
    $keep = isset($_GET['keepme']) ? $_GET['keepme'] : $_POST['keepme'];
    
    f_dbConnect();
    
    $pwd = 'secret'; //The super secretive, and safe password.
    $uid = f_userAuthen($user);
    if($password == $pwd && $uid )
    {
        $user_expire = time()+60*60; //default logged in duration is 1 hour
        
        if($keep == 1)
        {
            $user_expire = time()+60*60*24*30; //one month
        }
        setcookie("cookie_uid",$uid,$uid_expire);
        echo 'pass';
    }
    else
    {
        echo 'fail';
    }
?>