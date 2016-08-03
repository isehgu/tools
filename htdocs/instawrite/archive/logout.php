<?php
    setcookie("cookie_uid","",time()-3600);
    setcookie("cookie_refresh","",time()-3600);
    header("Location: index.php");  
?>
