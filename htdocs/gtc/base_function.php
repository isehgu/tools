<?php

    function f_dbConnect()
    {
        global $db;
        $db_connection = 'test';

        if($db_connection == 'test'){
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpwd  = '';
            $dbname = 'gtccheckout';
            $dbport = '3306';
        }

        $db = new mysqli($dbhost,$dbuser,$dbpwd,$dbname,$dbport);
        if(!$db) echo "Connection failed: ".$db->connect_error; //if condition here can also be -- if !$mysqli

    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    gtc day id of the start date
    //If day_id is not defined, then just use the latest.
    //Output:   Display the landing page
    function f_displayAllCircles($day_id)
    {
        echo "
        <div class='row'>
            <div class='small-8 columns small-centered'>";
        if(empty($day_id)) //if no day_id is specified -- i.e. initial page load
        {
            //Get the day_id here, instead of in the sub-function would make sure day_id is always synced up between start circle, and end circle.
            $day_id = f_getLatestDayId();
        }
        f_displayBigStartCircle($day_id); //$day_id simply means, that value will be marked as selected in the start circle
        f_displayBigEndCircle($day_id);

        echo "</div></div>";

        //Time for small circles
        echo "
        <div class='row'>
        <div class='small-8 columns small-centered'>
          <ul class='list-menu'>";

        // Displaying the following
        //<li><a href='#' class='button large circle'>6PM</a></li>
        // <li><a href='#' class='button large info circle long-text'>10PM</a></li>
        // <li><a href='#' class='button large info circle'>2AM</a></li>
        // <li><a href='#' class='button large info circle'>3AM</a></li>
        // <li><a href='#' class='button large info circle'>4AM</a></li>
        f_displaySmallCircles($day_id);
        echo "</ul></div></div>";

    }//end of f_displayAllCircles()
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display all the small circles for checkout times
    function f_displaySmallCircles($day_id)
    {
        echo "<li>";
        f_display6pmCircle($day_id);
        echo "</li>";

        echo "<li>";
        f_display10pmCircle($day_id);
        echo "</li>";

        echo "<li>";
        f_display2amCircle($day_id);
        echo "</li>";

        echo "<li>";
        f_display3amCircle($day_id);
        echo "</li>";

        echo "<li>";
        f_display4amCircle($day_id);
        echo "</li>";

    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display 6pm small circle

    function f_display6pmCircle($day_id)
    {
        // echo "<a href='#' data-ise-checkout='1' class='button large circle'>6PM</a>";
        $timeruntype = 1; //correspond to TimeRunTypeID
        if(f_checkHappened($day_id,$timeruntype))
        {
            $obsdb_count = f_getOrderCount($day_id,'obsdb',$timeruntype);
            $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);
            $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);
            $iors_count = f_getOrderCount($day_id,'IORSDB',$timeruntype);
            $obsdb_precise_count = f_getSubCount($day_id,'obsdb',$timeruntype,'precise');
            $obsdb_iors_count = f_getSubCount($day_id,'obsdb',$timeruntype,'iors');
            $mp_precise_count = f_getSubCount($day_id,'MarketPlace',$timeruntype,'precise');
            $mp_iors_count = f_getSubCount($day_id,'MarketPlace',$timeruntype,'iors');

            //if obsdb count is different than mp count, or odbsdb and mp's precise and iors sub-counts are different than precise and iors db count, then the button should be red. Else blue
            if($obsdb_count != $mp_count || $obsdb_iors_count!=$iors_count || $obsdb_precise_count!=$precise_count || $mp_iors_count!=$iors_count || $mp_precise_count!=$precise_count)
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle alert checkout-circle'>6PM</a>";
            }
            else //If there's no discrepancy found, blue button
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle checkout-circle'>6PM</a>";
            }
        }
        else //if this check hasn't happened yet, disable the button
        {
            echo "<a href='#' class='button large circle disabled'>6PM</a>";
        }

    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id, source(database or matcher memory), timeruntype(6pm/10pm/2am/3am/4am), usertype(iors/precise/dti)
    //Output:   return the unique order count on that day for that check, and from that source, and that usertype
    function f_getSubCount($day_id,$source,$timeruntype,$usertype)
    {
        global $db;
        $sql_query = "SELECT COUNT( DISTINCT ga.OrderIndex ) as count FROM  gtcaggregate as ga,orderdetail as od WHERE ga.source = '$source'AND ga.gtcdayid = $day_id and ga.TimeRunTypeID = $timeruntype and ga.orderindex = od.OrderIndex and od.usertype = '$usertype' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = '$source'AND gtcdayid = $day_id and TimeRunTypeID = $timeruntype order by CheckTimestamp desc limit 1)";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id, source(database or matcher memory), timeruntype(6pm/10pm/2am/3am/4am)
    //Output:   return the unique order count on that day for that check, and from that source
    function f_getOrderCount($day_id,$source,$timeruntype)
    {
        global $db;
        $sql_query = "SELECT COUNT( DISTINCT OrderIndex ) as count FROM  gtcaggregate WHERE source = '$source'AND gtcdayid = $day_id and TimeRunTypeID = $timeruntype and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = '$source'AND gtcdayid = $day_id and TimeRunTypeID = $timeruntype order by CheckTimestamp desc limit 1)";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id, and timeruntype(6pm/10pm/2am/3am/4am)
    //Output:   true if the check has run, false if it hasn't
    function f_checkHappened($day_id,$timeruntype)
    {
        global $db;
        $sql_query = "select count(*) as count from gtcaggregate where gtcdayid = $day_id and timeruntypeid = $timeruntype";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        if($row['count'] > 0) return true; //if count > 0, meaning the check took place
        else return false; //check didn't take place
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display 10pm small circle
    function f_display10pmCircle($day_id)
    {
        //echo "<a href='#' class='button large circle long-text'>10PM</a>";
        $timeruntype = 2; //correspond to TimeRunTypeID
        $timeruntype_6pm = 1;
        if(f_checkHappened($day_id,$timeruntype))
        {
            $obsdb_count = f_getOrderCount($day_id,'obsdb',$timeruntype);
            $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);
            $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);
            $iors_count = f_getOrderCount($day_id,'IORSDB',$timeruntype);

            $obsdb_count_6pm = f_getOrderCount($day_id,'obsdb',$timeruntype_6pm);
            $mp_count_6pm = f_getOrderCount($day_id,'MarketPlace',$timeruntype_6pm);
            $precise_count_6pm = f_getOrderCount($day_id,'PreciseDB',$timeruntype_6pm);
            $iors_count_6pm = f_getOrderCount($day_id,'IORSDB',$timeruntype_6pm);


            //if the counts are different than their 6pm counterparts then the button should be red. Else blue
            if($obsdb_count!=$obsdb_count_6pm || $mp_count!=$mp_count_6pm||$precise_count!=$precise_count_6pm||$iors_count!=$iors_count_6pm)
            {
                echo "<a href='#'  data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle alert long-text checkout-circle'>10PM</a>";
            }
            else
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle long-text checkout-circle'>10PM</a>";
            }
        }
        else //if this check hasn't happened yet
        {
            echo "<a href='#' class='button large circle disabled long-text'>10PM</a>";
        }
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display 2am small circle
    //Requirement: Core Matcher Memory – Match against Core OBSDB 10pm total count
    function f_display2amCircle($day_id)
    {
        $timeruntype = 3; //correspond to TimeRunTypeID
        $timeruntype_10pm = 2;
        if(f_checkHappened($day_id,$timeruntype))
        {
            $matcher_count = f_getOrderCount($day_id,'matcher',$timeruntype);

            $obsdb_count_10pm = f_getOrderCount($day_id,'obsdb',$timeruntype_10pm);

            //if the counts are different than 10pm counterparts then the button should be red. Else blue
            if($matcher_count!=$obsdb_count_10pm)
            {
                echo "<a href='#'  data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle alert checkout-circle'>2AM</a>";
            }
            else
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle checkout-circle'>2AM</a>";
            }
        }
        else //if this check hasn't happened yet
        {
            echo "<a href='#' class='button large circle disabled'>2AM</a>";
        }
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display 3am small circle
    //Requirement: MarketPlace DB – Match against 2am Core Matcher Memory counts
    function f_display3amCircle($day_id)
    {
        $timeruntype = 4; //correspond to TimeRunTypeID
        $timeruntype_2am = 3;
        if(f_checkHappened($day_id,$timeruntype))
        {
            $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);

            $matcher_count_2am = f_getOrderCount($day_id,'matcher',$timeruntype_2am);

            //if the counts are different than 2am counterparts then the button should be red. Else blue
            if($mp_count!=$matcher_count_2am)
            {
                echo "<a href='#'  data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle alert checkout-circle'>3AM</a>";
            }
            else
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle checkout-circle'>3AM</a>";
            }
        }
        else //if this check hasn't happened yet
        {
            echo "<a href='#' class='button large circle disabled'>3AM</a>";
        }
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display 4am small circle
    //Requirement: PrecISE DB – Match against 3am MarketPlace DB and 2am Core Matcher Memory.
    function f_display4amCircle($day_id)
    {
        //echo "<a href='#' data-ise-checkout='5' class='button large circle'>4AM</a>";
        $timeruntype = 5; //correspond to TimeRunTypeID
        $timeruntype_2am = 3; //correspond to TimeRunTypeID
        $timeruntype_3am = 4; //correspond to TimeRunTypeID

        if(f_checkHappened($day_id,$timeruntype))
        {
            $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);
            $matcher_precise_count_2am = f_getSubCount($day_id,'matcher',$timeruntype,'precise');
            $mp_precise_count_3am = f_getSubCount($day_id,'MarketPlace',$timeruntype,'precise');

            //if obsdb count is different than mp count, or odbsdb and mp's precise and iors sub-counts are different than precise and iors db count, then the button should be red. Else blue
            if($precise_count != $matcher_precise_count_2am || $precise_count!=$mp_precise_count_3am)
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle alert checkout-circle'>4AM</a>";
            }
            else //If there's no discrepancy found, blue button
            {
                echo "<a href='#' data-ise-timeruntype='$timeruntype' data-ise-day-id='$day_id' class='button large circle checkout-circle'>4AM</a>";
            }
        }
        else //if this check hasn't happened yet, disable the button
        {
            echo "<a href='#' class='button large circle disabled'>4AM</a>";
        }

    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    day_id
    //Output:   Display the big start circle
    function f_displayBigStartCircle($day_id)
    {
        global $db;
        $num_days = 5; //Start Circle should display this many dates

        echo "
        <div id='cicle-left' class='circle'>
          <select id='start-date'>";
        //$dates is an associative array -- "day_id"=>"yyyy-mm-dd"
        $dates = f_getStartCircleDates($num_days);
        foreach($dates as $id => $date)
        {
            if($id == $day_id){echo "<option value='$id' selected>$date</option>";}
            else {echo "<option value='$id'>$date</option>";}
        }

        echo "</select></div>";

    }//end of f_displayBigStartCircle()
    ///////////////////////////////////////////////////////////////////////
    //Input:    start date
    //If start date is not defined, then just use the latest.
    //Output:   Display the landing page
    function f_getStartCircleDates($num_days)
    {
        global $db;
        $dates = array();
        $sql_query = "select GTCDayID,StartDatetime from gtcdayconversion order by GTCDayID desc limit $num_days";
        $result = $db->query($sql_query) or die($db->error);
        while($row =$result->fetch_assoc())
        {
            $day_id = '';
            $start_date = '';
            $temp = '';

            $day_id = $row['GTCDayID'];
            $temp = explode(" ",$row['StartDatetime']);
            $start_date = $temp[0];

            $dates[$day_id] = $start_date;
        }
        return $dates;

    }//end of f_getStartCircleDates
     ///////////////////////////////////////////////////////////////////////
    //Input:    $day_id
    //Output:   Display end date big circle
    function f_displayBigEndCircle($day_id)
    {
        $end_date = f_getEndDate($day_id);
        echo "<div id='circle-right' class='circle'>$end_date</div>";
    }//end of f_displayBigEndCircle()
    ///////////////////////////////////////////////////////////////////////
    //Input: $day_id
    //Output: return the end date for that start date from gtcdayconversion table
    function f_getEndDate($day_id)
    {
        global $db;
        $sql_query = "select EndDatetime from gtcdayconversion where GTCDayID = $day_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $temp = explode(" ",$row['EndDatetime']);
        $end_date = $temp[0];
        return $end_date;
    }
    ///////////////////////////////////////////////////////////////////////
    //Input: $day_id
    //Output: return the start date for that start date from gtcdayconversion table
    function f_getStartDate($day_id)
    {
        global $db;
        $sql_query = "select StartDatetime from gtcdayconversion where GTCDayID = $day_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $temp = explode(" ",$row['StartDatetime']);
        $start_date = $temp[0];
        return $start_date;
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    None
    //Output:   Return the latest GTCDayID in the database
    //          in the format of yyyy-mm-dd
    function f_getLatestDayId()
    {
        global $db;
        $sql_query = "select GTCDayID from gtcdayconversion order by GTCDayID desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $temp = explode(" ",$row['GTCDayID']);
        $day_id = $temp[0];
        return $day_id;
    }//end of f_getLatestStartDate()
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am),day_id
    //Output:   echo out html for timeruntype
    function f_displayComparisonSummary($timeruntype,$day_id)
    {
        if($timeruntype == 1) f_display6pmSummary($timeruntype,$day_id);
        if($timeruntype == 2) f_display10pmSummary($timeruntype,$day_id);
        if($timeruntype == 3) f_display2amSummary($timeruntype,$day_id);
        if($timeruntype == 4) f_display3amSummary($timeruntype,$day_id);
        if($timeruntype == 5) f_display4amSummary($timeruntype,$day_id);
    }
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am), day_id
    //Output:   echo out html for 4am summary and differences tables
    //Here are what's being compared at 4am
    //MarketPlace DB – PrecISE DB – Match against 3am MarketPlace DB and 2am Core Matcher Memory.
    function f_display4amSummary($timeruntype,$day_id)
    {
        global $db;
        $diff = 0; //holder for diff calculation

        //Displaying the separators, and separator-circle
        echo "
        <div class='row'>
          <div class='small-10 columns small-centered'>
            <div class='section-separator'></div>";

        //4AM Counts
        $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);

        //2AM Counts
        $timeruntype_2am = 3;
        $matcher_precise_count_2am = f_getSubCount($day_id,'matcher',$timeruntype_2am,'precise');

        //3AM Counts
        $timeruntype_3am = 4;
        $mp_precise_count_3am = f_getSubCount($day_id,'MarketPlace',$timeruntype_3am,'precise');

        //if the counts are different base on comparison requirements then the section header circle should be red. Else blue
        if($precise_count != $matcher_precise_count_2am || $precise_count!=$mp_precise_count_3am)
        {
            echo "<div class='separator-circle-alert'>4AM</div>";
        }
        else
        {
            echo "<div class='separator-circle'>4AM</div>";
        }

        //Closing the separator
        echo "<div class='section-separator'></div>
                    <div class='clear-float'></div>
                  </div>
                </div>";
        //Displaying summary table
        echo "
        <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead><tr><th>Source</th><th>Total</th></tr></thead>
              <tbody>
                <tr><td>Precise</td><td>$precise_count</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        ";


        //Displaying actual comparison table
        echo "
            <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead>
                <tr>
                  <th>Comparing Sources</th>
                  <th width='100'>Differences</th>
                </tr>
              </thead>
              <tbody>
        ";

        //Comparison Summary Table
        //the url for the detail difference contains the following parameters
        //s1 -> source 1
        //s1type -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
        //s2 -> source 2
        //s2type -> source 2's runtimetype
        //precise vs matcher@2am
        if(($precise_count-$matcher_precise_count_2am) != 0) //there is difference
        {
            $diff = abs($precise_count-$matcher_precise_count_2am);
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>Matcher-Precise-2AM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=precise&s1type=$timeruntype&s2=matcher_precise&s2type=$timeruntype_2am' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>Matcher-Precise-2AM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //precise vs mp@3am
        if(($precise_count-$mp_precise_count_3am) != 0) //there is difference
        {
            $diff = abs($precise_count-$mp_precise_count_3am);
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>MarketPlace-Precise-3AM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=precise&s1type=$timeruntype&s2=mp_precise&s2type=$timeruntype_3am' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>MarketPlace-Precise-3AM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        echo "</tbody>
            </table>
          </div>
        </div>";


    }//end of f_display4amSummary
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am), day_id
    //Output:   echo out html for 3am summary and differences tables
    //Here are what's being compared at 3am
    //MarketPlace DB – Match against 2am Core Matcher Memory counts
    function f_display3amSummary($timeruntype,$day_id)
    {
        global $db;
        $diff = 0; //holder for diff calculation

        //Displaying the separators, and separator-circle
        echo "
        <div class='row'>
          <div class='small-10 columns small-centered'>
            <div class='section-separator'></div>";

        //3AM Counts
        $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);

        //2AM Counts
        $timeruntype_2am = 3;
        $matcher_count_2am = f_getOrderCount($day_id,'matcher',$timeruntype_2am);

        //if the counts are different base on comparison requirements then the section header circle should be red. Else blue
        if($mp_count!=$matcher_count_2am)
        {
            echo "<div class='separator-circle-alert'>3AM</div>";
        }
        else
        {
            echo "<div class='separator-circle'>3AM</div>";
        }

        //Closing the separator
        echo "<div class='section-separator'></div>
                    <div class='clear-float'></div>
                  </div>
                </div>";
        //Displaying summary table
        echo "
        <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead><tr><th>Source</th><th>Total</th></tr></thead>
              <tbody>
                <tr><td>MarketPlace</td><td>$mp_count</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        ";


        //Displaying actual comparison table
        echo "
            <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead>
                <tr>
                  <th>Comparing Sources</th>
                  <th width='100'>Differences</th>
                </tr>
              </thead>
              <tbody>
        ";

        //Comparison Summary Table
        //the url for the detail difference contains the following parameters
        //s1 -> source 1
        //s1type -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
        //s2 -> source 2
        //s2type -> source 2's runtimetype
        //matcher vs obsdb@10pm
        if(($mp_count-$matcher_count_2am) != 0) //there is difference
        {
            $diff = abs($mp_count-$matcher_count_2am);
            echo "<tr><td><span class='emphasis'>MarketPlace</span> vs <span class='emphasis'>Matcher-2AM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=mp&s1type=$timeruntype&s2=matcher&s2type=$timeruntype_2am' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>MarketPlace</span> vs <span class='emphasis'>Matcher-2AM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        echo "</tbody>
            </table>
          </div>
        </div>";


    }//end of f_display3amSummary
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am), day_id
    //Output:   echo out html for 2am summary and differences tables
    //Here are what's being compared at 2am
    //Core Matcher Memory – Match against Core OBSDB 10pm total count
    function f_display2amSummary($timeruntype,$day_id)
    {
        global $db;
        $diff = 0; //holder for diff calculation

        //Displaying the separators, and separator-circle
        echo "
        <div class='row'>
          <div class='small-10 columns small-centered'>
            <div class='section-separator'></div>";

        //2AM Counts
        $matcher_count = f_getOrderCount($day_id,'matcher',$timeruntype);

        //10PM Counts
        $timeruntype_10pm = 2;
        $obsdb_count_10pm = f_getOrderCount($day_id,'obsdb',$timeruntype_10pm);

        //if the counts are different base on comparison requirements then the section header circle should be red. Else blue
        if($matcher_count!=$obsdb_count_10pm)
        {
            echo "<div class='separator-circle-alert'>2AM</div>";
        }
        else
        {
            echo "<div class='separator-circle'>2AM</div>";
        }

        //Closing the separator
        echo "<div class='section-separator'></div>
                    <div class='clear-float'></div>
                  </div>
                </div>";
        //Displaying summary table
        echo "
        <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead><tr><th>Source</th><th>Total</th></tr></thead>
              <tbody>
                <tr><td>Matcher</td><td>$matcher_count</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        ";


        //Displaying actual comparison table
        echo "
            <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead>
                <tr>
                  <th>Comparing Sources</th>
                  <th width='100'>Differences</th>
                </tr>
              </thead>
              <tbody>
        ";

        //Comparison Summary Table
        //the url for the detail difference contains the following parameters
        //s1 -> source 1
        //s1type -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
        //s2 -> source 2
        //s2type -> source 2's runtimetype
        //matcher vs obsdb@10pm
        if(($matcher_count-$obsdb_count_10pm) != 0) //there is difference
        {
            $diff = abs($matcher_count-$obsdb_count_10pm);
            echo "<tr><td><span class='emphasis'>Matcher</span> vs <span class='emphasis'>OBSDB-10PM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=matcher&s1type=$timeruntype&s2=obsdb&s2type=$timeruntype_10pm' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>Matcher</span> vs <span class='emphasis'>OBSDB-10PM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        echo "</tbody>
            </table>
          </div>
        </div>";


    }//end of f_display2amSummary
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am), day_id
    //Output:   echo out html for 10pm summary and differences tables
    //Here are what's being compared at 10pm
    // 10pm – Counts will be executed on: (Purpose of this counts is to make sure EOD/Changes did not alter any of the orders)
    // • Core OBSDB – Match against 6pm OBSDB count..
    // • MarketPlace DB – Match against 6pm MarketPlaceDB counts.
    // • PrecISE DB – Match against 6pm PrecISE DB counts.
    // • IORS DB – Match against 6pm IORS DB counts
    function f_display10pmSummary($timeruntype,$day_id)
    {
        global $db;
        $diff = 0; //holder for diff calculation

        //Displaying the separators, and separator-circle
        echo "
        <div class='row'>
          <div class='small-10 columns small-centered'>
            <div class='section-separator'></div>";

        //10PM Counts
        $obsdb_count = f_getOrderCount($day_id,'obsdb',$timeruntype);
        $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);
        $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);
        $iors_count = f_getOrderCount($day_id,'IORSDB',$timeruntype);

        //6PM Counts
        $timeruntype_6pm = 1;
        $obsdb_count_6pm = f_getOrderCount($day_id,'obsdb',$timeruntype_6pm);
        $mp_count_6pm = f_getOrderCount($day_id,'MarketPlace',$timeruntype_6pm);
        $precise_count_6pm = f_getOrderCount($day_id,'PreciseDB',$timeruntype_6pm);
        $iors_count_6pm = f_getOrderCount($day_id,'IORSDB',$timeruntype_6pm);

        //if the counts are different base on comparison requirements then the section header circle should be red. Else blue
        if($obsdb_count!=$obsdb_count_6pm || $mp_count!=$mp_count_6pm||$precise_count!=$precise_count_6pm||$iors_count!=$iors_count_6pm)
        {
            echo "<div class='separator-circle-alert'>10PM</div>";
        }
        else
        {
            echo "<div class='separator-circle'>10PM</div>";
        }

        //Closing the separator
        echo "<div class='section-separator'></div>
                    <div class='clear-float'></div>
                  </div>
                </div>";
        //Displaying summary table
        echo "
        <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead><tr><th>Source</th><th>Total</th></tr></thead>
              <tbody>
                <tr><td>OBSDB</td><td>$obsdb_count</td></tr>
                <tr><td>MarketPlace</td><td>$mp_count</td></tr>
                <tr><td>Precise DB</td><td>$precise_count</td></tr>
                <tr><td>IORS DB</td><td>$iors_count</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        ";


        //Displaying actual comparison table
        echo "
            <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead>
                <tr>
                  <th>Comparing Sources</th>
                  <th width='100'>Differences</th>
                </tr>
              </thead>
              <tbody>
        ";

        //Comparison Summary Table
        //the url for the detail difference contains the following parameters
        //s1 -> source 1
        //s1type -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
        //s2 -> source 2
        //s2type -> source 2's runtimetype
        //obsdb vs obsdb@6pm
        if(($obsdb_count-$obsdb_count_6pm) != 0) //there is difference
        {
            $diff = abs($obsdb_count-$obsdb_count_6pm);
            echo "<tr><td><span class='emphasis'>OBSDB</span> vs <span class='emphasis'>OBSDB-6PM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=obsdb&s1type=$timeruntype&s2=obsdb&s2type=timeruntype_6pm' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>OBSDB</span> vs <span class='emphasis'>OBSDB-6PM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //mp vs mp@6pm
        if(($mp_count-$mp_count_6pm) != 0)
        {
            $diff = abs($mp_count-$mp_count_6pm);
            echo "<tr><td><span class='emphasis'>MarketPlace</span> vs <span class='emphasis'>MarketPlace-6PM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=mp&s1type=$timeruntype&s2=mp&s2type=$timeruntype_6pm' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>MarketPlace</span> vs <span class='emphasis'>MarketPlace-6PM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //IORS vs IORS@6PM
        if(($iors_count-$iors_count_6pm) != 0)
        {
            $diff = abs($iors_count-$iors_count_6pm);
            echo"<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>IORS-6PM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=iors&s1type=$timeruntype&s2=iors&s2type=$timeruntype_6pm' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>IORS-6PM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //Precise vs Precise@6PM
        if(($precise_count-$precise_count_6pm) != 0)
        {
            $diff = abs($precise_count-$precise_count_6pm);
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>Precise-6PM</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=precise&s1type=$timeruntype&s2=precise&s2type=$timeruntype_6pm' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>Precise-6PM</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        echo "</tbody>
            </table>
          </div>
        </div>";


    }//end of f_display10pmSummary
    ///////////////////////////////////////////////////////////////////////
    //Input:    timeruntype(6pm/10pm/2am/3am/4am), day_id
    //Output:   echo out html for 6pm summary and differences tables
    function f_display6pmSummary($timeruntype,$day_id)
    {
        global $db;
        $diff = 0; //holder for diff calculation

        //Displaying the separators, and separator-circle
        echo "
        <div class='row'>
          <div class='small-10 columns small-centered'>
            <div class='section-separator'></div>";


        $obsdb_count = f_getOrderCount($day_id,'obsdb',$timeruntype);
        $mp_count = f_getOrderCount($day_id,'MarketPlace',$timeruntype);
        $precise_count = f_getOrderCount($day_id,'PreciseDB',$timeruntype);
        $iors_count = f_getOrderCount($day_id,'IORSDB',$timeruntype);
        $obsdb_precise_count = f_getSubCount($day_id,'obsdb',$timeruntype,'precise');
        $obsdb_iors_count = f_getSubCount($day_id,'obsdb',$timeruntype,'iors');
        $mp_precise_count = f_getSubCount($day_id,'MarketPlace',$timeruntype,'precise');
        $mp_iors_count = f_getSubCount($day_id,'MarketPlace',$timeruntype,'iors');
        $obsdb_dti_count = $obsdb_count - $obsdb_precise_count - $obsdb_iors_count;
        $mp_dti_count = $mp_count - $mp_precise_count - $mp_iors_count;

        //if obsdb count is different than mp count, or odbsdb and mp's precise and iors sub-counts are different than precise and iors db count, then the section header circle should be red. Else blue
        if($obsdb_count != $mp_count || $obsdb_iors_count!=$iors_count || $obsdb_precise_count!=$precise_count || $mp_iors_count!=$iors_count || $mp_precise_count!=$precise_count)
        {
            echo "<div class='separator-circle-alert'>6PM</div>";
        }
        else
        {
            echo "<div class='separator-circle'>6PM</div>";
        }

        //Closing the separator
        echo "<div class='section-separator'></div>
                    <div class='clear-float'></div>
                  </div>
                </div>";
        //Displaying summary table
        echo "
        <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead><tr><th>Source</th><th>DTI</th><th>Precise Order</th><th>IORS Order</th><th>Total</th></tr></thead>
              <tbody>
                <tr><td>OBSDB</td><td>$obsdb_dti_count</td><td>$obsdb_precise_count</td><td>$obsdb_iors_count</td><td>$obsdb_count</td></tr>
                <tr><td>MarketPlace</td><td>$mp_dti_count</td><td>$mp_precise_count</td><td>$mp_iors_count</td><td>$mp_count</td></tr>
                <tr><td>Precise DB</td><td class='empty-cell'></td><td>$precise_count</td><td class='empty-cell'></td><td>$precise_count</td></tr>
                <tr><td>IORS DB</td><td class='empty-cell'></td><td class='empty-cell'></td><td>$iors_count</td><td>$iors_count</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        ";


        //Displaying actual comparison table
        echo "
            <div class='row'>
          <div class='small-7 columns small-centered'>
            <table class='summary-table'>
              <thead>
                <tr>
                  <th>Comparing Sources</th>
                  <th width='100'>Differences</th>
                </tr>
              </thead>
              <tbody>
        ";

        //obsdb vs MarketPlace
        //the url for the detail difference contains the following parameters
        //s1 -> source 1
        //s1type -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
        //s2 -> source 2
        //s2type -> source 2's runtimetype
        if(($obsdb_count-$mp_count) != 0) //there is difference
        {
            $diff = abs($obsdb_count-$mp_count);
            echo "<tr><td><span class='emphasis'>OBSDB</span> vs <span class='emphasis'>MarketPlace</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=obsdb&s1type=1&s2=mp&s2type=1' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else //no difference
        {
            echo "<tr><td><span class='emphasis'>OBSDB</span> vs <span class='emphasis'>MarketPlace</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //Precise vs obsdb-precise
        if(($precise_count-$obsdb_precise_count) != 0)
        {
            $diff = abs($precise_count-$obsdb_precise_count);
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>OBSDB-Precise</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=precise&s1type=1&s2=obsdb_precise&s2type=1' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>OBSDB-Precise</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //IORS vs obsdb-iors
        if(($iors_count-$obsdb_iors_count) != 0)
        {
            $diff = abs($iors_count-$obsdb_iors_count);
            echo"<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>OBSDB-IORS</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=iors&s1type=1&s2=obsdb_iors&s2type=1' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>OBSDB-IORS</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //Precise vs mp_precise
        if(($precise_count-$mp_precise_count) != 0)
        {
            $diff = abs($precise_count-$mp_precise_count);
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>MarketPlace-Precise</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=precise&s1type=1&s2=mp_precise&s2type=1' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>PreciseDB</span> vs <span class='emphasis'>MarketPlace-Precise</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        //IORS vs mp_iors
        if(($iors_count-$mp_iors_count) != 0)
        {
            $diff = abs($iors_count-$mp_iors_count);
            echo"<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>MarketPlace-IORS</span></td><td><a href='compare_detail.php?day_id=$day_id&s1=iors&s1type=1&s2=obsdb_iors&s2type=1' class='button alert small round' target='_blank'>$diff</a></td></tr>";
        }
        else
        {
            echo "<tr><td><span class='emphasis'>IORSDB</span> vs <span class='emphasis'>MarketPlace-IORS</span></td><td><a href='#' class='button small round disabled' target='_blank'>0</a></td></tr>";
        }

        echo "</tbody>
            </table>
          </div>
        </div>";


    }
    ///////////////////////////////////////////////////////////////////////
    //Input:
    //source1 -> source 1
    //source1_runtimetype -> source 1's runtimetype, since we can compare counts from 6pm to counts from 10pm
    //source2 -> source 2
    //source2_runtimetype -> source 2's runtimetype
    //day_id
    //Output:   echo out html for comparison detail table for compare_detail.php between the 2 sources\
    //Note possible source values are
    //obsdb, obsdb_iors, obsdb_precise, mp, mp_iors, mp_precise, iors, precise
    function f_displayCompareDetail($source1,$source1_runtimetype,$source2,$source2_runtimetype,$day_id)
    {
        global $db;
        $runtimetype_name = array(1=>'6PM',2=>'10PM',3=>'2AM',4=>'3AM',5=>'4AM');
        $source_names = array('obsdb'=>'OBSDB','obsdb_iors'=>'OBSDB-IORS','obsdb_precise'=>'OBSDB-Precise','mp'=>'MarketPlace','mp_iors'=>'MarketPlace-IORS','mp_precise'=>'MarketPlace-Precise','iors'=>'IORS','precise'=>'Precise','matcher'=>'Matcher','matcher_iors'=>'Matcher-IORS','matcher_precise'=>'Matcher-Precise');
        $s1_orders = array(); //array of orderindex for source1
        $s2_orders = array(); //array of orderindex for source2
        //Extract OrderIndex for each source base on runtimetype and day_id
        //Needs to identify first, which source uses sub-count like mp_iors, obsdb_precise, etc
        //Source 1
        if($source1 == 'obsdb_iors') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'obsdb' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source1 == 'obsdb_precise') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'obsdb' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        //now sub-count for MP
        elseif($source1 == 'mp_iors') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'MarketPlace' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source1 == 'mp_precise') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'MarketPlace' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        //sub-count for matcher
        elseif($source1 == 'matcher_iors') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'matcher' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source1 == 'matcher_precise') $sql_query_s1 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source1_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'matcher' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";

        //Now for stuff that doesn't involve subcount
        elseif($source1 == 'obsdb') $sql_query_s1 = "select distinct OrderIndex from gtcaggregate where source = 'obsdb' and timeruntypeid = $source1_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source1 == 'mp') $sql_query_s1 = "select distinct OrderIndex from gtcaggregate where source = 'MarketPlace' and timeruntypeid = $source1_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source1 == 'precise') $sql_query_s1 = "select distinct OrderIndex from gtcaggregate where source = 'PreciseDB' and timeruntypeid = $source1_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'PreciseDB'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source1 == 'iors') $sql_query_s1 = "select distinct OrderIndex from gtcaggregate where source = 'IORSDB' and timeruntypeid = $source1_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'IORSDB'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source1 == 'matcher') $sql_query_s1 = "select distinct OrderIndex from gtcaggregate where source = 'Matcher' and timeruntypeid = $source1_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source1_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        else echo "Invalid source combination -- source 1=".$source1.", Runtimetype=".$source1_runtimetype.", GTCDayID=".$day_id;

        //Source 2
        if($source2 == 'obsdb_iors') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'obsdb' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source2 == 'obsdb_precise') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'obsdb' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        //now sub-count for MP
        elseif($source2 == 'mp_iors') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'MarketPlace' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source2 == 'mp_precise') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'MarketPlace' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";

        //sub-count for Matcher
        elseif($source2 == 'matcher_iors') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'iors' and ga.OrderIndex = od.OrderIndex and ga.source = 'Matcher' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";
        elseif($source2 == 'matcher_precise') $sql_query_s2 = "select distinct ga.OrderIndex from gtcaggregate as ga,orderdetail as od where ga.timeruntypeid = $source2_runtimetype and ga.GTCDayID = $day_id and od.usertype = 'precise' and ga.OrderIndex = od.OrderIndex and ga.source = 'Matcher' and ga.CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by ga.OrderIndex";

        //Now for stuff that doesn't involve subcount
        elseif($source2 == 'obsdb') $sql_query_s2 = "select distinct OrderIndex from gtcaggregate where source = 'obsdb' and timeruntypeid = $source2_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'obsdb'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source2 == 'mp') $sql_query_s2 = "select distinct OrderIndex from gtcaggregate where source = 'MarketPlace' and timeruntypeid = $source2_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'MarketPlace'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source2 == 'precise') $sql_query_s2 = "select distinct OrderIndex from gtcaggregate where source = 'PreciseDB' and timeruntypeid = $source2_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'PreciseDB'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source2 == 'iors') $sql_query_s2 = "select distinct OrderIndex from gtcaggregate where source = 'IORSDB' and timeruntypeid = $source2_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'IORSDB'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        elseif($source2 == 'matcher') $sql_query_s2 = "select distinct OrderIndex from gtcaggregate where source = 'Matcher' and timeruntypeid = $source2_runtimetype and GTCDayID = $day_id and CheckTimestamp = (select CheckTimestamp from gtcaggregate where source = 'matcher'AND gtcdayid = $day_id and TimeRunTypeID = $source2_runtimetype order by CheckTimestamp desc limit 1) order by OrderIndex";
        else echo "Invalid source combination -- source 2=".$source2.", Runtimetype=".$source2_runtimetype.", GTCDayID=".$day_id;

        $result_s1 = $db->query($sql_query_s1) or die($db->error);
        $result_s2 = $db->query($sql_query_s2) or die($db->error);

        //Now getting all orderindex for S1 and S2
        while($row = $result_s1->fetch_assoc())
        {
            $s1_orders[] = $row['OrderIndex'];
        }

        //Now getting all orderindex for S1 and S2
        while($row = $result_s2->fetch_assoc())
        {
            $s2_orders[] = $row['OrderIndex'];
        }

        //compare $s1_orders and $s2_orders, and find the order indices that are in s1 not s2, and vice versa
        $s1_only_orders = array_diff($s1_orders,$s2_orders); //array of orderindex in s1 only
        $s2_only_orders = array_diff($s2_orders, $s1_orders); //array of orderindex in s2 only

        $s1_only_count = sizeof($s1_only_orders);
        $s2_only_count = sizeof($s2_only_orders);

        $source1_name = "$source_names[$source1] from $runtimetype_name[$source1_runtimetype]";
        $source2_name = "$source_names[$source2] from $runtimetype_name[$source2_runtimetype]";
        //Now I have all the data needed for displaying

        echo "<table><thead><tr><th>Comparison Sources</th><th>Count</th></tr></thead>";
        echo "<tbody><tr><td>Order only in $source1_name</td><td>$s1_only_count</td></tr>
        <tr><td>Order only in $source2_name</td><td>$s2_only_count</td></tr></tbody></table>";
        echo "<br><br>";

        echo "<input id='order-detail-search' placeholder='Type in search string'>";
        echo "<br><br>";
        echo "<table id='order-detail-table'><thead><tr>
                <th width='100'>Source</th>
                <th width='45'>User Type</th>
                <th width='180'>Exchange Order ID</th>
                <th width='70'>Product</th>
                <th width='50'>Price</th>
                <th width='40'>Part ID</th>
                <th width='50'>Time In Force</th>
                <th width='170'>Client Order ID</th>
                <th width='40'>Side</th>
                <th width='40'>Qty</th>
                <th width='60'>Status</th>
                <th width='150'>Entry Time</th>
                <th width='70'>BU</th>
                <th>Instrument</th>
        </tr></thead>
        <tbody>";
        //turning those two arrays into strings that can be used in sql query

        //If there are orders in s1 only, then display order detail
        if($s1_only_count > 0)
        {
            $s1_only_order_string = implode(",", $s1_only_orders);
            $sql_query_only_s1 = "select * from orderdetail where OrderIndex in ($s1_only_order_string) order by OrderIndex";
            $result_s1_only = $db->query($sql_query_only_s1) or die($db->error);
            while($row = $result_s1_only->fetch_assoc())
            {
                $user_type = '';
                $exch_id = '';
                $product = '';
                $price = '';
                $partition = '';
                $tif = '';
                $cl_ord_id = '';
                $side = '';
                $qty = '';
                $status = '';
                $entry = '';
                $bu = '';
                $instrument = '';

                $user_type = $row['UserType'];
                $exch_id = $row['ExchangeOrderID'];
                $product = $row['ProductName'];
                $price = $row['Price'];
                $partition = $row['PartitionID'];
                $tif = $row['TimeInForce'];
                $cl_ord_id = $row['ClOrderID'];
                $side = $row['Side'];
                $qty = $row['Quantity'];
                $status = $row['OrderStatus'];
                $entry = $row['EntryTimeStamp'];
                $bu = $row['BUName'];
                $instrument = $row['InstrumentName'];

                echo "<tr class='row-content'>
                    <td>$source1_name</td>
                    <td>$user_type</td>
                    <td>$exch_id</td>
                    <td>$product</td>
                    <td>$price</td>
                    <td>$partition</td>
                    <td>$tif</td>
                    <td>$cl_ord_id</td>
                    <td>$side</td>
                    <td>$qty</td>
                    <td>$status</td>
                    <td>$entry</td>
                    <td>$bu</td>
                    <td>$instrument</td>
                </tr>";

            }//end of while loop for s1

        }//end of s1 only order detail

        //If there are orders in s2 only, then display order detail
        if($s2_only_count > 0)
        {
            $s2_only_order_string = implode(",", $s2_only_orders);
            $sql_query_only_s2 = "select * from orderdetail where OrderIndex in ($s2_only_order_string) order by OrderIndex";
            $result_s2_only = $db->query($sql_query_only_s2) or die($db->error);
            while($row = $result_s2_only->fetch_assoc())
            {
                $user_type = '';
                $exch_id = '';
                $product = '';
                $price = '';
                $partition = '';
                $tif = '';
                $cl_ord_id = '';
                $side = '';
                $qty = '';
                $status = '';
                $entry = '';
                $bu = '';
                $instrument = '';

                $user_type = $row['UserType'];
                $exch_id = $row['ExchangeOrderID'];
                $product = $row['ProductName'];
                $price = $row['Price'];
                $partition = $row['PartitionID'];
                $tif = $row['TimeInForce'];
                $cl_ord_id = $row['ClOrderID'];
                $side = $row['Side'];
                $qty = $row['Quantity'];
                $status = $row['OrderStatus'];
                $entry = $row['EntryTimeStamp'];
                $bu = $row['BUName'];
                $instrument = $row['InstrumentName'];

                echo "<tr class='row-content'>
                    <td>$source2_name</td>
                    <td>$user_type</td>
                    <td>$exch_id</td>
                    <td>$product</td>
                    <td>$price</td>
                    <td>$partition</td>
                    <td>$tif</td>
                    <td>$cl_ord_id</td>
                    <td>$side</td>
                    <td>$qty</td>
                    <td>$status</td>
                    <td>$entry</td>
                    <td>$bu</td>
                    <td>$instrument</td>
                </tr>";

            }//end of while loop for s2
        }//end of s2 only order detail
    }
    ///////////////////////////////////////////////////////////////////////
