<?php

function f_dbConnect()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpwd = "";
    $dbname = "instawrite";
    
    //connect to databases
    $con = mysql_connect($dbhost, $dbuser, $dbpwd);
    if(!$con){die('Couldn\'t connect: '.mysql_error());}
    
    if(!(mysql_select_db($dbname, $con))) //Selecting the database under the login
    {
        die("Couldn\'t select database $dbname: " .mysql_error());
    }
}//End of f_dbConnect


//f_addTag
//input:    $tag -- single tag name
//output:   f_addTag returns two values as an array, [NewTag(true/false),tag_id].
//          If tag doesn't exist, it would create it and return the tag_id.
//          First element -- true:new tag, false:existing tag
function f_addTag($tag)
{
    $tag = mysql_real_escape_string($tag);
    $return_array = array();
    $result = mysql_query("select tag_id from tag where tag_name = '$tag'") or die(mysql_error());
    if (mysql_num_rows($result) <= 0) //This is not an existing tag
    {   //insert the new tag
        mysql_query("insert into tag(tag_name) values('$tag')") or die(mysql_error());
        $return_array[] = true; //first element of the return_array
        $return_array[] = mysql_insert_id(); //2nd element, which is tag_id
        
    }
    else //if it is existing tag
    {
        $row = mysql_fetch_array($result); //no while loop here, since it's just one row
        $return_array[] = false;
        $return_array[] = $row['tag_id'];
    }
    return $return_array;
}

//f_addWriteup
//input:    $writeup -- mysql-escaped text of the writeup
//          $tag_id_list -- array of all tag_ids for this new writeup
//output:   returns the new writeup id
function f_addWriteup($writeup,$tag_id_list)
{
    $writeup = mysql_real_escape_string($writeup);
    mysql_query("insert into writeup (writeup_content) values('$writeup')") or die(mysql_error());
    $writeup_id = mysql_insert_id(); //last inserted row's autoincrement index
    foreach($tag_id_list as $tag_id)
    {   //link up the $tag_id and $writeup_id
        mysql_query("insert into link(writeup_id,tag_id) values($writeup_id,$tag_id)") or die(mysql_error());
    }
    return $writeup_id;
}

//f_tagLookup
//input:    $tag_id
//output:   return tag string itself
function f_tagLookup($tag_id)
{
    $result = mysql_query("select tag_name where tag_id = $tag_id") or die(mysql_error());
    while($row = mysql_fetch_array($result))
    {
        $tag_name = $row['tag_name'];
    }
    return $tag_name;
}

//f_displayWriteups
//input:    $tag_list -- an array of tags
//output:   echoing out all the html for all writeup_block for all writeups from the search
function f_displayWriteups($tag_list)
{
    //translate $tag_list into an array of tag_id
    $tag_id_list = f_tagsToIds($tag_list);
    //build database query
    $tag_id_string = implode(',',$tag_id_list);
    
    if (count($tag_id_list) <= 0)
    {
        echo "<p>No writeups matched searched tag(s)</p>";
        return;
    }
    //Extract all writeup_id that has at least one of the tag ids from link table.
    //select writeup_id from link where tag_id in (1,2,3,4) order by writeup_id
    $query_string = "select writeup_id from link where tag_id in (".$tag_id_string.") order by writeup_id";
    
    //create an array. Loop through the sql output, and every time a writeup_id is found, then increment array[$writeup_id] +=1
    //At the end, this array should contain all resulting writeup_id and counters. Then display the writeup with the highest counter.
    //The writeup with the highest counter indicates that it matches the most number of tags requested.
    $writeup_id_list = array();
    $result = mysql_query($query_string) or die(mysql_error());
    while ($row = mysql_fetch_array($result))
    {
        $writeup_id = $row['writeup_id'];
        $writeup_id_list[$writeup_id] +=1; //increment the writeup id counter by 1
    }
    //sort the resulting $writeup_id_list by each writeup_id's counter, then display them from most frequent to the least one
    arsort($writeup_id_list); //reverse sort of the associative array by value, so it's in descending order by count
    foreach($writeup_id_list as $writeup_id => $count)//loop through resulting writeup, from most frequent to least, and display them
    {
        f_displaySingleWriteup($writeup_id);//display html for a single writeup
    }
}

//f_displaySingleWriteup
//input:    $writeup_id -- id of the writeup
//output:   echo out html for the requested writeup
function f_displaySingleWriteup($writeup_id)
{
    $result = mysql_query("select * from writeup where writeup_id = $writeup_id limit 1") or die(mysql_error());
    
    
    while($row = mysql_fetch_array($result))
    {
        $writeup_content = $row['writeup_content'];
        $writeup_time = $row['writeup_time'];
        $writeup_content = stripslashes($writeup_content);
        $writeup_content = htmlspecialchars($writeup_content);
        echo "<div id='$writeup_id' class='writeup_wrapper'>";
        echo "<p class='writeup_time pull-right'>$writeup_time</p>";
        echo "<br>";
        echo "<p>".$writeup_content."</p>";
        echo "</div>";
    }
    
}

//f_getSingleWriteup
//input:    $writeup_id -- id of the writeup
//output:   return writeup content
function f_getSingleWriteup($writeup_id)
{
    $result = mysql_query("select writeup_content from writeup where writeup_id = $writeup_id limit 1") or die(mysql_error());
    $row = mysql_fetch_array($result);
    $writeup_content = $row['writeup_content'];
    $writeup_content = stripslashes($writeup_content);
    return $writeup_content;
}

//f_getTags
//input:    $writeup_id -- id of the writeup
//output:   return string of comma delimited tags
function f_getTags($writeup_id)
{
    $tag_name_list = array();
    $result = mysql_query("select tag_id from link where writeup_id = $writeup_id") or die(mysql_error());
    while($row = mysql_fetch_array($result))
    {
        $tag_id = $row['tag_id'];
        $temp_tag = f_getSingleTag($tag_id); //temp_tag is stripslashed
        $tag_name_list[] = $temp_tag; //collecting tags
    }
    $tags_string = implode(',',$tag_name_list); //getting the tags into comma delimited string
    return $tags_string;
}

//f_getSingleTag
//input:    $tag_id
//output:   return tag_name, stripslashed
function f_getSingleTag($tag_id)
{
    $result = mysql_query("select tag_name from tag where tag_id = $tag_id limit 1") or die(mysql_error());
    $row = mysql_fetch_array($result);
    $tag_name = $row['tag_name'];
    $tag_name = stripslashes($tag_name);
    return $tag_name;
}

//f_tagsToIds
//input:    $tag_list -- an array of tags
//output:   returns an array of tag_ids based on the provided tag array. Tag just need to be similar, not exactly the same as
//          provided in the input array.
function f_tagsToIds($tag_list)
{
    $tag_id_list = array();
    //Each tag should be mysql_escape_string() first, before searching for tags. Or do it in the subfunction f_tagsToIds calls to
    //translate a single tag to tag id.
    foreach($tag_list as $tag)
    {
        $tag = mysql_escape_string($tag);
        $result = mysql_query("select tag_id from tag where tag_name like '%$tag%'");
        while($row = mysql_fetch_array($result))
        {
            $tag_id_list[] = $row['tag_id'];
        }//End of while
    }//End of foreach
    $tag_id_list = array_unique($tag_id_list); //Just returning unique list of tag_ids
    return $tag_id_list;
}

//f_getSingleTag
//input:    $writeup,$writeup_id,$tag_id_list
//output:   Return nothing. Base on writeup_id, update writeup_content, and link table for tags
function f_updateWriteup($writeup,$writeup_id,$tag_id_list)
{
    $writeup = mysql_real_escape_string($writeup);
    foreach($tag_id_list as $tag_id)
    {
        $link_count = mysql_query("select * from link where tag_id = $tag_id and writeup_id = $writeup_id") or die(mysql_error());
        if(mysql_num_rows($link_count) == 0) //if no current link is found
        {
            mysql_query("insert into link(writeup_id,tag_id) values($writeup_id,$tag_id)") or die(mysql_error()); //link tag to writeup if it hasn't been linked yet
        }
    }//end of foreach
    mysql_query("update writeup set writeup_content = '$writeup' where writeup_id = $writeup_id") or die(mysql_error()); //update writeup
}

//f_displayAllTags
//input:    
//output:   echo html tags in table format
function f_displayAllTags()
{
    $column = 5;
    $counter = 1;
    $result = mysql_query("select tag_name from tag order by tag_name") or die(mysql_error());
    while($row = mysql_fetch_array($result))
    {
        if($counter%$column == 1) echo "<tr>";
        $tag_name = $row['tag_name'];
        $tag_name = stripslashes($tag_name);
        echo "<td>$tag_name</td>";
        if($counter%$column ==0)echo "</tr>";
        $counter++;
    }
}
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
?>