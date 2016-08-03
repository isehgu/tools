<?php
    $test = array();
    $name = 'this is a name';
    $name2 = 2;
    
    $test[$name] += 1;
    $test[$name2] +=2;
    
    //echo $test[$name2];

    foreach($test as $y => $x)
    {
        echo"<p>$y</p>";
    }

?>