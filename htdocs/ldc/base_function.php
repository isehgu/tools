<?php
	function f_dbConnect()
	{
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpwd = '';
		$dbname = 'order_flow';
		
		$con = mysql_connect($dbhost, $dbuser, $dbpwd);
		if(!$con){ die('Couldn\'t connect to database: '.mysql_error()); }
		if(!(mysql_select_db($dbname, $con))){ die('Couldn\'t select database: '.mysql_error()); }
	}
	
	function f_displayDateRange($from, $to, $searchFlag)
	{
		if($searchFlag) #with valid from and to date
		{
			echo "[$from] .... [$to]"; 
		}
		else #if no valid date range, then just the latest date
		{
			$date = f_latestDate();
			echo "[$date]"; 
		}
	}
	
	function f_displayOverallStats($from, $to, $searchFlag)
	{
		$total_e = 0;
		$total_n = 0;
		$total_c = 0;
		$total_om = 0;
		$total_r = 0;
		$total_oc = 0;
		$total_mc = 0;
		if($searchFlag) #with valid from and to date
		{
			$result = mysql_query("select * from adapter_stats where date >= '$from' and date <= '$to'") or die(mysql_error());
			while($row = mysql_fetch_array($result))
			{
				$total_e += $row['num_events'];
				$total_n += $row['num_event_N'];
				$total_c += $row['num_event_C'];
				$total_om += $row['num_event_OM'];
				$total_r += $row['num_event_R'];
				$total_oc += $row['num_event_OC'];
				$total_mc += $row['num_event_MC'];
			}
			$total_e = number_format($total_e);
			$total_n = number_format($total_n);
			$total_c = number_format($total_c);
			$total_om = number_format($total_om);
			$total_r = number_format($total_r);
			$total_oc = number_format($total_oc);
			$total_mc = number_format($total_mc);
			echo "<td>$total_e</td>";
			echo "<td>$total_n</td>";
			echo "<td>$total_c</td>";
			echo "<td>$total_om</td>";
			echo "<td>$total_r</td>";
			echo "<td>$total_oc</td>";
			echo "<td>$total_mc</td>";
		}
		else #if no valid date range, then just the latest date
		{
			$date = f_latestDate();
			$result = mysql_query("SELECT * FROM adapter_stats a where date = '$date'") or die(mysql_error());
			while($row = mysql_fetch_array($result))
			{
				$total_e += $row['num_events'];
				$total_n += $row['num_event_N'];
				$total_c += $row['num_event_C'];
				$total_om += $row['num_event_OM'];
				$total_r += $row['num_event_R'];
				$total_oc += $row['num_event_OC'];
				$total_mc += $row['num_event_MC'];
			}
			
			$total_e = number_format($total_e);
			$total_n = number_format($total_n);
			$total_c = number_format($total_c);
			$total_om = number_format($total_om);
			$total_r = number_format($total_r);
			$total_oc = number_format($total_oc);
			$total_mc = number_format($total_mc);
			echo "<td>$total_e</td>";
			echo "<td>$total_n</td>";
			echo "<td>$total_c</td>";
			echo "<td>$total_om</td>";
			echo "<td>$total_r</td>";
			echo "<td>$total_oc</td>";
			echo "<td>$total_mc</td>";
		}
	}
	
	function f_displayDetailStats($from, $to, $searchFlag)
	{
		if($searchFlag) #Valid Search dates
		{
			$bsi_query = mysql_query("select distinct svc_grp_name, svc_grp_id from all_adapters where svc_grp_name not like '%DCA%' order by svc_grp_name ASC") or die(mysql_error()); # Now we have the names of all Svc Groups
			while($bsi_row = mysql_fetch_array($bsi_query))
			{
				$bsi_name = "";
				$bsi_name = $bsi_row['svc_grp_name'];
				$bsi_id = $bsi_row['svc_grp_id'];
				# Now we have one bsi svc grp name
				echo "<tr class='info'>";
				echo "<td><button class='btn btn-small btn-inverse disabled'>$bsi_name</button></td>";
				$stats_query = mysql_query("select * from adapter_stats where date >= '$from' and date <= '$to' and svc_grp_id = (select distinct svc_grp_id from all_adapters where svc_grp_name = '$bsi_name')") or die(mysql_error());
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				while($stats_row = mysql_fetch_array($stats_query))
				{
					$total_e += $stats_row['num_events'];
					$total_n += $stats_row['num_event_N'];
					$total_c += $stats_row['num_event_C'];
					$total_om += $stats_row['num_event_OM'];
					$total_r += $stats_row['num_event_R'];
					$total_oc += $stats_row['num_event_OC'];
					$total_mc += $stats_row['num_event_MC'];
				}
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";
				f_displayAdapterStats($from, $to, $bsi_id, $searchFlag);
			}
		}
		else # No valid search, just latest date
		{
			$date = f_latestDate();
			$bsi_query = mysql_query("select distinct svc_grp_name, svc_grp_id from all_adapters where svc_grp_name not like '%DCA%' order by svc_grp_name ASC") or die(mysql_error()); # Now we have the names of all Svc Groups
			while($bsi_row = mysql_fetch_array($bsi_query))
			{
				$bsi_name = "";
				$bsi_id = 0;
				$bsi_name = $bsi_row['svc_grp_name'];
				$bsi_id = $bsi_row['svc_grp_id'];
				# Now we have one bsi svc grp name
				echo "<tr class='info'>";
				echo "<td><button class='btn btn-small btn-inverse disabled'>$bsi_name</button></td>";
				$stats_query = mysql_query("select * from adapter_stats where date = '$date' and svc_grp_id = (select distinct svc_grp_id from all_adapters where svc_grp_name = '$bsi_name')") or die(mysql_error());
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				while($stats_row = mysql_fetch_array($stats_query))
				{
					$total_e += $stats_row['num_events'];
					$total_n += $stats_row['num_event_N'];
					$total_c += $stats_row['num_event_C'];
					$total_om += $stats_row['num_event_OM'];
					$total_r += $stats_row['num_event_R'];
					$total_oc += $stats_row['num_event_OC'];
					$total_mc += $stats_row['num_event_MC'];
				}
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";
				f_displayAdapterStats($from, $to, $bsi_id, $searchFlag);
			}
		}
	}
	
	function f_displayAdapterStats($from, $to, $bsi_id, $searchFlag)
	{
		if($searchFlag)
		{
			$adapter_query = mysql_query("select distinct adapter_name from adapter_stats where svc_grp_id = '$bsi_id'") or die(mysql_error()); # getting all the adapter names
			while($row = mysql_fetch_array($adapter_query)) #cylcing through all the adapters of that svc group
			{
				#For each adapter
				$adapter_name = "";
				$adapter_name = $row['adapter_name'];
				
				$stats_query = mysql_query("select * from adapter_stats where adapter_name = '$adapter_name' and date >= '$from' and date <= '$to'") or die(mysql_error()); # stats on each adapter between the date range
				
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				
				while($stats_row = mysql_fetch_array($stats_query))
				{
					$total_e += $stats_row['num_events'];
					$total_n += $stats_row['num_event_N'];
					$total_c += $stats_row['num_event_C'];
					$total_om += $stats_row['num_event_OM'];
					$total_r += $stats_row['num_event_R'];
					$total_oc += $stats_row['num_event_OC'];
					$total_mc += $stats_row['num_event_MC'];
				}
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<tr>";
				echo "<td>$adapter_name</td>";
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";				
			}
		}
		else
		{
			$date = f_latestDate();
			$adapter_query = mysql_query("select * from adapter_stats where svc_grp_id = '$bsi_id' and date = '$date'") or die(mysql_error());
			while($row = mysql_fetch_array($adapter_query))
			{
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				$adapter_name = "";
				$adapter_name = $row['adapter_name'];
				$total_e = $row['num_events'];
				$total_n = $row['num_event_N'];
				$total_c = $row['num_event_C'];
				$total_om = $row['num_event_OM'];
				$total_r = $row['num_event_R'];
				$total_oc = $row['num_event_OC'];
				$total_mc = $row['num_event_MC'];
				
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<tr>";
				echo "<td>$adapter_name</td>";
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";				
			}
		}
	}
	function f_displayBSIStats($from, $to, $searchFlag)
	{
		if($searchFlag) #Valid Search dates
		{
			$bsi_query = mysql_query("select distinct svc_grp_name from all_adapters where svc_grp_name not like '%DCA%' order by svc_grp_name ASC") or die(mysql_error()); # Now we have the names of all Svc Groups
			while($bsi_row = mysql_fetch_array($bsi_query))
			{
				$bsi_name = "";
				$bsi_name = $bsi_row['svc_grp_name'];
				# Now we have one bsi svc grp name
				echo "<tr class='info'>";
				echo "<td><button class='btn btn-small btn-inverse disabled'>$bsi_name</button></td>";
				$stats_query = mysql_query("select * from adapter_stats where date >= '$from' and date <= '$to' and svc_grp_id = (select distinct svc_grp_id from all_adapters where svc_grp_name = '$bsi_name')") or die(mysql_error());
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				while($stats_row = mysql_fetch_array($stats_query))
				{
					$total_e += $stats_row['num_events'];
					$total_n += $stats_row['num_event_N'];
					$total_c += $stats_row['num_event_C'];
					$total_om += $stats_row['num_event_OM'];
					$total_r += $stats_row['num_event_R'];
					$total_oc += $stats_row['num_event_OC'];
					$total_mc += $stats_row['num_event_MC'];
				}
				
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";
			}
		}
		else # No valid search, just latest date
		{
			$date = f_latestDate();
			$bsi_query = mysql_query("select distinct svc_grp_name from all_adapters where svc_grp_name not like '%DCA%' order by  svc_grp_name ASC") or die(mysql_error()); # Now we have the names of all Svc Groups
			while($bsi_row = mysql_fetch_array($bsi_query))
			{
				$bsi_name = "";
				$bsi_name = $bsi_row['svc_grp_name'];
				# Now we have one bsi svc grp name
				echo "<tr class='info'>";
				echo "<td><button class='btn btn-small btn-inverse disabled'>$bsi_name</button></td>";
				$stats_query = mysql_query("select * from adapter_stats where date = '$date' and svc_grp_id = (select distinct svc_grp_id from all_adapters where svc_grp_name = '$bsi_name')") or die(mysql_error());
				$total_e = 0;
				$total_n = 0;
				$total_c = 0;
				$total_om = 0;
				$total_r = 0;
				$total_oc = 0;
				$total_mc = 0;
				while($stats_row = mysql_fetch_array($stats_query))
				{
					$total_e += $stats_row['num_events'];
					$total_n += $stats_row['num_event_N'];
					$total_c += $stats_row['num_event_C'];
					$total_om += $stats_row['num_event_OM'];
					$total_r += $stats_row['num_event_R'];
					$total_oc += $stats_row['num_event_OC'];
					$total_mc += $stats_row['num_event_MC'];
				}
				$total_e = number_format($total_e);
				$total_n = number_format($total_n);
				$total_c = number_format($total_c);
				$total_om = number_format($total_om);
				$total_r = number_format($total_r);
				$total_oc = number_format($total_oc);
				$total_mc = number_format($total_mc);
				echo "<td>$total_e</td>";
				echo "<td>$total_n</td>";
				echo "<td>$total_c</td>";
				echo "<td>$total_om</td>";
				echo "<td>$total_r</td>";
				echo "<td>$total_oc</td>";
				echo "<td>$total_mc</td>";
				echo "</tr>";
			}
		}
	}
	
	function f_latestDate()
	{
		$date_query = mysql_query("select date from adapter_stats order by date DESC limit 1") or die(mysql_error());
		while($row = mysql_fetch_array($date_query))
		{
			$date = "";
			$date = $row['date'];
		}
		return $date;
	}
	
	
###############################################################################################
?>
