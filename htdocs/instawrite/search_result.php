<?php
    require_once 'base_function.php';
    f_dbConnect();
    
    $raw_tag = $_GET['search_tags']; //a string of tags
    $tag_list = explode(',', $raw_tag); //getting an array of tags
    
    f_displayWriteups($tag_list); //This would generate all the writeup_block html base on the tags
    
?>