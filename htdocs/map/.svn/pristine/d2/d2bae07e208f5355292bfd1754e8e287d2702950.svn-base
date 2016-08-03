<?php
    require_once("shared.php");

    ///////////////////////////////////////////////////////////////////////
    //input: None
    //output: prints etcd contents from file
    function f_displayETCDDump(){
        $filename = 'etcd_contents.log';
        if(file_exists($filename)){
            $f = fopen($filename, 'r');
            echo "
                <div class='row'>
                    <div class='small-12 columns'>
                        <table>
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                <tr>
                            </thead>
                            <tbody>
            ";
            while(!feof($f)) {
                $line = fgets($f);
                $line_array = explode("-->", $line);
                echo "
                                <tr>
                                    <td>".htmlspecialchars($line_array[0])."</td>
                                    <td>".htmlspecialchars($line_array[1])."</td>
                                </tr>
                ";
            }
            fclose($f);
            echo "
                            </tbody>
                        <table>
                    </div>
                </div>
            ";
        }
        else{
            echo "File not found";
        }
    }


?>
