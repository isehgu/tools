<?php
    require_once 'base_function.php';
    f_dbConnect();

    $writeup = $_POST['writeup'];
    $raw_tag = $_POST['tag_list']; //a string of tags
    $writeup_id = $_POST['writeup_id'];
    
    //$writeup = mysql_real_escape_string($writeup);
    //$raw_tag = mysql_real_escape_string($raw_tag);
    
    //Break raw_tag into array of tags. Raw tags should be comma separated
    $tag_list = explode(',', $raw_tag);
    $tag_id_list = array();
    //Add tags to database if it's not there already
    foreach($tag_list as $tag)
    {
        $add_result = f_addTag($tag); //f_addTag returns two values as an array, [NewTag(true/false),tag_id]. If tag doesn't exist, it would create it and return the tag_id
        $tag_id_list[] = $add_result[1]; //Collecting the tag IDs
    }
    //Create the writeup and associate all these tags with the writeup
    f_updateWriteup($writeup,$writeup_id,$tag_id_list); //Base on writeup_id, update writeup_content, and link table for tags
    
    header("Location: /instawrite/displaywriteup.php?id=$writeup_id");
?>