<?php 	
	include("cfg/functions.php");
	
	class Firm {
		var $name;
		var $symbol;
		var $bu;	
		function __construct($name, $symbol, $bu) {
			$this->name=$name;
			$this->symbol=$symbol;
			$this->bu=$bu;
		}
		function insertInternal(&$localdb) {
			$sql="INSERT INTO firm VALUES (NULL, '".$this->name."','".$this->symbol."','".$this->bu."');";
			$result=mysql_query($sql, $localdb);
			if (!$result) { logProb($sql."\n".mysql_error()); }
		}
	}	

	class Adapter {
		var $logintype;
		var $in_identifier; # for precise this will be a firm id (ie.bu - because you can have multiple users per BU), for iors this will be a user id
		var $out_bu;
		var $out_user;
		var $out_identifier; # for BOTH precise and iors, this will be a userid (of the DTI user)
		function __construct($logintype) {
			$this->logintype=$logintype;
		}
		function insertInternal(&$localdb) {
			if (!vV($this->in_identifier)) { 
				logProb("Adapter connecting to Core GW ".$this->out_bu."-".$this->out_user." is configured to an inactive....IGNORING...");
			} else {
				$sql="INSERT INTO adapter VALUES (NULL, ".$this->logintype.",".$this->in_identifier.",".$this->out_identifier.");";
				$result=mysql_query($sql, $localdb);
				if (!$result) { logProb($sql."\n".mysql_error()); }
			}
		}
	}
	
	class User {
		var $name;
		var $logintype;
		var $firmid;
		var $exp1;
		var $exp2;
		var $exp3;
		function __construct($name, $logintype, $firmid, $exp1, $exp2, $exp3) {
			$this->name=$name;
			$this->logintype=$logintype;
			$this->firmid=$firmid;
			$this->exp1=$exp1;
			$this->exp2=$exp2;
			$this->exp3=$exp3;
		}
		function insertInternal(&$localdb) {
			if (!vV($this->firmid)) {
				logProb("User ".$this->name." does not belong to an active business unit...IGNORING...");
			} else {
				$exp1=(vV($this->exp1))?"'".addslashes($this->exp1)."'":"NULL"; # name fields need slashes
				$exp2=(vV($this->exp2))?"'".addslashes($this->exp2)."'":"NULL"; # name fields need slashes
				$exp3=(vV($this->exp3))?"'".$this->exp3."'":"NULL";
				$sql="INSERT INTO user VALUES (NULL, '".$this->name."',".$this->logintype.",".$this->firmid.",".$exp1.",".$exp2.",".$exp3.");";
				$result=mysql_query($sql, $localdb);
				if (!$result) { logProb("ERROR INSERTING USER: [".$this->name."]...\n".$sql."\n".mysql_error()); }
			}
		}		
	}

/* ################################################ STATIC DATA ################################################ */
	
	function loadStaticData($type, &$type_array) {
		loadStaticDataInternal($type, $type_array); # if relevant, populates array based on local database
	}

	function loadStaticDataExternal($type) {
		$localdb=db_connect("local");
		$type_array=array();
		switch ($type) {
			case "firm": 
				$extdb=db_connect("core"); # CORE RDB
				$sql="SELECT p.name as 'firm', p.symbol, b.name as 'bu' FROM rdb.business_unit b, rdb.participant p WHERE p.id=b.participant_id AND p.effective_status=1 AND p.type=1 AND b.effective_status=1 ORDER BY p.name, b.name;";
				$result=mysql_query($sql, $extdb);
				if (!$result) logProb("ERROR LOADING EXTERNAL STATIC DATA: FIRM DATA FROM RDB...\n".$sql."\n".mysql_error());
				# handle participants (firms) from core rdb
				while ($row=mysql_fetch_array($result)) {
					array_push($type_array, new Firm($row['firm'],$row['symbol'],$row['bu']));
				}
			break;

			case "user": # for precise, get all the usernames from cleartrust, for iors, get all the fixSessionNames from iors adapter config
				$extdbA=db_connect("ct"); # CLEARTRUST DB 
				$extdbB=db_connect("iors"); # IORS BSI DB
				$extdbC=db_connect("core"); # IORS BSI DB
				$sqlA="SELECT u.NAME, u.FIRST_NAME, u.LAST_NAME, up.STRING_VALUE FROM USER_PROPERTY up, USERS u WHERE u.ID=up.USER_ID AND up.PROPERTY_DEF_ID IN (1862755);"; # property code for GTS Company, ie. Optimise BU Name
				$sqlB="SELECT * FROM adapter_instance_config WHERE property_name IN ('fixSessionName', 'member') ORDER BY adapter_instance_id;";
				$sqlC="SELECT g.loginName, b.name FROM viewdb.GWUserData g, rdb.business_unit b, rdb.participant p WHERE g.businessUnitID=b.id AND p.id=b.participant_id AND p.effective_status=1 AND p.type=1 AND b.effective_status=1;";
				$resultA=mssql_query($sqlA, $extdbA);
				if (!$resultA) logProb("ERROR LOADING EXTERNAL STATIC DATA: FRONT END USER DATA FROM CLEARTRUST...\n".$sql."\n".mssql_get_last_message());
				$resultB=mssql_query($sqlB, $extdbB);
				if (!$resultB) logProb("ERROR LOADING EXTERNAL STATIC DATA: FIX USER DATA FROM IORS BSI DB...\n".$sql."\n".mssql_get_last_message());
				$resultC=mysql_query($sqlC, $extdbC);
				if (!$resultC) logProb("ERROR LOADING EXTERNAL STATIC DATA: CORE GW DATA FROM VIEWDB...\n".$sql."\n".mysql_error());
				# handle cleartrust users
				while ($row=mssql_fetch_array($resultA)) {
					array_push($type_array, new User($row['NAME'],1,lookupBuIdByBuName($row['STRING_VALUE'], $localdb),$row['FIRST_NAME'],$row['LAST_NAME'],""));
				}
				# handle fix users
				$currentindex=count($type_array);
				$currentid=-1;
				while ($row=mssql_fetch_array($resultB)) {
					if ($currentid != $row['adapter_instance_id']) {
						$currentid = $row['adapter_instance_id'];
						$type_array[++$currentindex] = new User("",2,0,"","",""); # specify that the new user is a FIX (IORS) user - also learn PHP overloading
					}
					if ($row['property_name']=="fixSessionName") $type_array[$currentindex]->name = $row['property_value'];
					else if ($row['property_name']=="member") $type_array[$currentindex]->firmid = lookupBuIdByBuName($row['property_value'], $localdb);
				}
				# handle GW users
				while ($row=mysql_fetch_array($resultC)) {
					array_push($type_array, new User($row['loginName'],3,lookupBuIdByBuName($row['name'], $localdb),"","",""));
				}
			break;

			case "adapter": # iors and precise are a little different...
				$extdbA=db_connect("precise"); # PRECISE BSI DB
				$extdbB=db_connect("iors"); # IORS BSI DB
				$sqlA="SELECT * FROM adapter_instance_config WHERE property_name IN ('member','user','subscriptionUser') ORDER BY adapter_instance_id;";
				$sqlB="SELECT * FROM adapter_instance_config WHERE property_name IN ('fixSessionName', 'member', 'user') ORDER BY adapter_instance_id;";
				$resultA=mssql_query($sqlA, $extdbA);
				if (!$resultA) logProb("ERROR LOADING EXTERNAL STATIC DATA: PRECISE ADAPTERS FROM PRECISE BSI DB...\n".$sql."\n".mssql_get_last_message());
				$resultB=mssql_query($sqlB, $extdbB);
				if (!$resultB) logProb("ERROR LOADING EXTERNAL STATIC DATA: IORS ADAPTERS FROM IORS BSI DB...\n".$sql."\n".mssql_get_last_message());
				# handle precise adapters
				$currentindex=0;
				$currentid=-1; # this is internal just to this block, only for transposing the bsi database, no scope beyond this...
				while ($row=mssql_fetch_array($resultA)) {
					if ($currentid != $row['adapter_instance_id']) {
						$currentid = $row['adapter_instance_id'];
						$type_array[++$currentindex."A"]=new Adapter(1); # specify this adapter as precise type (1)
						$type_array[$currentindex."B"]=new Adapter(1); # specify this adapter as precise type (1)
					}
					switch ($row['property_name']) { # assumption here: that precise will always have two users per BU: user and subscriptionUser
						case "member":
							$buid=lookupBuIdByBuName($row['property_value'], $localdb); # assumption here: that this will always return a value
							$type_array[$currentindex."A"]->in_identifier=$buid; 
							$type_array[$currentindex."B"]->in_identifier=$buid; 
							$type_array[$currentindex."A"]->out_bu=$row['property_value']; 
							$type_array[$currentindex."B"]->out_bu=$row['property_value']; 
							# if we need to add expected GW node to the adapter table - this is one place insert will go
						break;
						case "user": $type_array[$currentindex."A"]->out_user=$row['property_value']; break;
						case "subscriptionUser": $type_array[$currentindex."B"]->out_user=$row['property_value']; break;
					}
				}
				# handle iors adapters
				$currentid=-1;
				while ($row=mssql_fetch_array($resultB)) {
					if ($currentid != $row['adapter_instance_id']) {
						$currentid = $row['adapter_instance_id'];
						$type_array[++$currentindex]=new Adapter(2); # specify this adapter as iors type (2)
					}
					switch ($row['property_name']) {
						case "fixSessionName":
							$userid=lookupUserIdByFixSessionName($row['property_value'],$localdb); # assumption here: that this will always return a value
							$type_array[$currentindex]->in_identifier=$userid; 
						break;
						case "member":
							$type_array[$currentindex]->out_bu=$row['property_value']; 
							# if we need to add expected GW node to the adapter table - this is one place insert will go
						break;
						case "user": $type_array[$currentindex]->out_user=$row['property_value']; break;
					}
				}
				# iterate over all adapters, and select the userid of the concat(out_bu, out_user) => out_identifier
				foreach ($type_array as &$tai) {
					$tai->out_identifier=lookupUserIdByName($tai->out_bu."-".$tai->out_user, $localdb);
				}
			break;
		}
		
		#wipe out existing static data
		$sql="TRUNCATE ".$type.";";
		$result=mysql_query($sql, $localdb);
		if (!$result) logProb("Emptying Table ".$type." Failed...\n".$sql."\n".mysql_error());
		
		#insert loaded static data into local database
		foreach ($type_array as $ta) {
			$ta->insertInternal($localdb);
		}
		#update cfg to reflect external load complete
		$sql="UPDATE cfg SET timeA=NOW() WHERE keyA='lastload' AND keyB='".$type."';";
		mysql_query($sql, $localdb);
	}
	
	function lookupBuIdByBuName($bun, &$localdb) {
		$sql="SELECT id FROM firm WHERE bu='".$bun."';";
		$result = mysql_query($sql, $localdb);
		if (!$result || mysql_num_rows($result)==0) {
			# logProb("Could not find BUID for BUNAME=[".$bun."]...BU could be inactive\n".$sql."\n".mysql_error());
		}
		$row=mysql_fetch_array($result);
		return $row['id'];
	}
	
	function lookupUserIdByFixSessionName($fsn, &$localdb) {
		$sql="SELECT id FROM user WHERE logintype=2 AND name='".$fsn."';";
		$result = mysql_query($sql, $localdb);
		if (!$result || mysql_num_rows($result)==0) {
			# logProb("Could not find USERID for FixSessionName=[".$fsn."]...BU could be inactive\n".$sql."\n".mysql_error());
		}
		$row=mysql_fetch_array($result);
		return $row['id'];
	}
	
	function lookupUserIdByName($n, &$localdb) {
		$sql="SELECT id FROM user WHERE name='".$n."';";
		$result = mysql_query($sql, $localdb);
		if (!$result || mysql_num_rows($result)==0) { 
			#logProb("Could not find USERID with Name=[".$n."]...BU could be inactive\n".$sql."\n".mysql_error());
			return 0;
		}
		$row=mysql_fetch_array($result);
		return $row['id'];
	}
		
	function loadStaticDataInternal($type, &$type_array) {
		$localdb=db_connect("local");
		switch ($type) {
			case "firm": 
				#$sql="SELECT name, symbol FROM firm f GROUP BY symbol HAVING COUNT(DISTINCT name)=1 OR (COUNT(DISTINCT name)>1 AND name NOT LIKE '%SC') ORDER BY symbol;";
				$sql="SELECT name, symbol FROM firm f GROUP BY symbol ORDER BY symbol;";
				$result=mysql_query($sql, $localdb);
				if (!$result) logProb($sql."\n".mysql_error());
				while ($row=mysql_fetch_array($result)) {
					array_push($type_array,new Firm($row['name'],$row['symbol'],"-ALL-"));
				}
			break;
			case "user": break;
			case "adapter": break;
		}
	}

/* ################################################ FIRM STATUS ################################################ */
	
	function refreshFirmconExternal($type,$sinceid,&$localdb) {
		# lock the cfg
		$sql="UPDATE cfg SET locked=1 WHERE keyA='lastload' AND keyB='connection' AND valueA='".$type."';";
		mysql_query($sql, $localdb);
		
		$newsincevalueB="valueB"; # if this doesn't get updated, set valueB to itself.
		$newsincetimeB="timeB"; # if this doesn't get updated, set timeB to itself. (need timeB for dual login/out from CORE)
		switch ($type) {
			case 1: # precise/front end (PRECISE audit events)
				if (!vV($sinceid)) {
					$sinceid="(SELECT MAX(evt_id) FROM as_events WHERE evt_timestamp < '".date("Y-m-d")." 00:00:00')"; 
				}
				$db=db_connect("precise");
				$sqlA="SELECT s.evt_id 'newsince', s.machine_name, s.app_name, user_id, user_action, s.evt_timestamp 'ts' FROM as_audit_details a, as_events s WHERE a.evt_id=s.evt_id AND a.result='Success' AND a.entity='Session' AND s.evt_id > ".$sinceid." ORDER BY s.evt_id;";
				$resultA=mssql_query($sqlA, $db);
				#logProb("Loading precise...".$sqlA);
				if (!$resultA) logProb("Could not load Precise connections...\n".$sqlA."\n".mysql_error());
				
				while ($row=mssql_fetch_array($resultA)) {
					$actiontype=($row['user_action']=="Login")?1:-1;
					$userid=lookupUserIdByName($row['user_id'], $localdb);
					$newsincevalueB=$row['newsince'];
					if ($userid==0) {
						logProb("User ".$row['user_id']." is configured to a suspended/inactive business unit...IGNORING...\n");
						continue;
					}
					$sqlB="INSERT INTO connection VALUES(NULL, ".$type.", ".$actiontype.",'".$row['machine_name']."','".$row['app_name']."','".$userid."','".$row['ts']."', NULL);";
					$resultB=mysql_query($sqlB,$localdb);
					if (!$resultB) logProb("Failed to load PRECISE/FRONT END connection (".$row['user_id'].") into local db...\n".$sqlB."\n".mysql_error());
				}
			break;
			case 2: # fix/iors (ISEAPPS_CONFIG ops_events)
				if (!vV($sinceid)) {
					$sinceid="(SELECT MAX(evt_id) FROM dbo.ops_events WHERE evt_ts < '".date("Y-m-d")." 00:00:00')"; 
				}
				$db=db_connect("cfg");
				$sqlA="SELECT evt_id 'newsince', machine_name, instance_name, user_id, user_action, evt_ts 'ts' FROM dbo.ops_events WHERE machine_name like '%bsi02%' AND result='Success' AND evt_id > ".$sinceid.";"; # assumption IORS will always be on BSI02 nodes
				$resultA=mssql_query($sqlA, $db);
				#logProb("Loading FIX...".$sqlA);
				if (!$resultA) logProb("Could not load IORS/FIX connections to 'cfg' db...\n".$sqlA."\n".$db."\n".mssql_get_last_message());
				if(mssql_num_rows($resultA)==0) {
					logProb("No rows were found from the following query in IORS/FIX 'cfg' database\n".$sqlA."\n".mssql_get_last_message());
				}

				while ($row=mssql_fetch_array($resultA)) {
					$actiontype=($row['user_action']=="Login")?1:-1;
					$userid=lookupUserIdByName($row['user_id'], $localdb);
					$newsincevalueB=$row['newsince'];
					if ($userid==0) {
						logProb("User ".$row['user_id']." is configured to a suspended/inactive business unit...IGNORING...\n");
						continue;
					}
					$sqlB="INSERT INTO connection VALUES(NULL, ".$type.", ".$actiontype.",'".$row['machine_name']."','".$row['instance_name']."','".$userid."','".$row['ts']."', NULL);";
					$resultB=mysql_query($sqlB,$localdb);
					if (!$resultB) logProb("Failed to load FIX/IORS connection (".$row['user_id'].") into local db...\n".$sqlB."\n".mysql_error());
				}
			break;
			
			case 3: # gateway (CMDB) DTI ???
				# query for latest time (i.e. result will look like this '2013-08-20 09:57:18')
				$sql="SELECT timeB FROM cfg WHERE keyA='lastload' AND keyB='connection' AND valueA='3' AND timeB IS NOT NULL;";
				$result=mysql_query($sql,$localdb);
				
				# gets the number of rows in the last query. If empty, variable $sincetime is given system's time, ELSE get sql query result's data
				if (mysql_num_rows($result)==0) { 
					$sincetime=date("Y-m-d")." 00:00:00";
				} else {
					$row=mysql_fetch_array($result);
					$sincetime=$row['timeB'];
				}
				$db=db_connect("core");
				# GET ALL THE CORE LOGOUTS - 
				# 10/13/11 - will not process logouts whose session was connected before midnight
				$newsincetimeB="'".date("Y-m-d H:i:s", strtotime('-1 second'))."'"; # avoid missing split-second logouts
				$sqlA="SELECT S.LoginName, H.Name as Host, P.ProcessName, CONVERT_TZ(S.LogoutTimestamp,'+0:00','-4:00') as LoggedOut, S.SessionID FROM CMDB.SessionStateActive S, CMDB.Process P, CMDB.Host H WHERE P.HostID=H.HostID AND P.ProcessNameID=S.GatewayID AND S.ApplicationTypeID=1 AND CONVERT_TZ(S.LogoutTimestamp,'+0:00','-4:00') > '".$sincetime."' AND CONVERT_TZ(S.LogoutTimestamp,'+0:00','-4:00') <= ".$newsincetimeB." AND S.IsActive=0 AND CONVERT_TZ(S.LoginTimestamp,'+0:00','-4:00') >= '".date("Y-m-d")." 00:00:00' ORDER BY LoggedOut;"; # DAYLIGHT SAVINGS!
				#print "query is: $sqlA\n";
				$resultA=mysql_query($sqlA, $db);
				# logProb("Loading Core LOGOUTS...There are [".mysql_num_rows($resultA)."] new ones to load.\n".$sqlA);
				if (!$resultA) logProb("Could not load Core logouts...\n".$sqlA."\n".mysql_error());
				
				# inserting into localdb: table <connection>
				while ($rowA=mysql_fetch_array($resultA)) {
					$sqlB="INSERT INTO connection VALUES(NULL, ".$type.",-1,'".$rowA['Host']."','".$rowA['ProcessName']."','".lookupUserIDByName($rowA['LoginName'], $localdb)."','".$rowA['LoggedOut']."', '".$rowA['SessionID']."');";
					$resultB=mysql_query($sqlB,$localdb);
					if (!$resultB) { logProb("Failed to load Core GW/DTI connection logout (".$rowA['LoginName'].") into local db...\n".$sqlB."\n".mysql_error()); }
				}
				
				# GET ALL THE CORE LOGINS
				if (!vV($sinceid)) {
					# this is slow, but in theory once everything starts working this is never executed.
					$sinceid="(SELECT SessionID FROM CMDB.SessionState WHERE CONVERT_TZ(LoginTimestamp,'+0:00','-4:00') < '".date("Y-m-d")." 00:00:00' ORDER BY SessionID DESC LIMIT 1)"; # get yesterday's highest session number...ASSUMING THERE WAS A YESTERDAY...or just use the lastload time.
				}
				
				$sqlC="SELECT S.SessionID as newsince, S.LoginName, H.Name as Host, P.ProcessName, CONVERT_TZ(S.LoginTimestamp,'+0:00','-4:00') as LoggedIn FROM CMDB.SessionStateActive S, CMDB.Process P, CMDB.Host H WHERE P.HostID=H.HostID AND P.ProcessNameID=S.GatewayID AND S.ApplicationTypeID=1 AND S.SessionID > ".$sinceid." ORDER BY SessionID;"; # DAYLIGHT SAVINGS!
				$resultC=mysql_query($sqlC, $db);
				#print "query is: $sqlC\n";

				#logProb("Loading CORE LOGINS...".$sqlC);
				if (!$resultC) logProb("Could not load Core logins...\n".$sqlC."\n".mysql_error());
				while ($rowC=mysql_fetch_array($resultC)) {
					$sqlD="INSERT INTO connection VALUES(NULL, ".$type.",1,'".$rowC['Host']."','".$rowC['ProcessName']."','".lookupUserIDByName($rowC['LoginName'], $localdb)."','".$rowC['LoggedIn']."', '".$rowC['newsince']."');";
					$resultD=mysql_query($sqlD,$localdb);
					if (!$resultD) logProb("Failed to load Core GW/DTI connection login (".$rowC['LoginName'].") into local db...\n".$sqlD."\n".mysql_error());
					$newsincevalueB=$rowC['newsince'];
				}
				
				# FIX 0000-00-00 00:00:00 values following LLO cutover
				$sqlE="SELECT id, exp1 FROM connection c WHERE timestamp='0000-00-00 00:00:00';";
				$resultE=mysql_query($sqlE, $localdb);
				if (!$resultE) logProb("Could not load 000000 logins...\n".$sqlE."\n".mysql_error());
				if (mysql_num_rows($resultE)>0) {
					while ($rowE=mysql_fetch_array($resultE)) {
						$sqlF="SELECT CONVERT_TZ(S.LoginTimestamp,'+0:00','-4:00') as LoggedIn FROM CMDB.SessionStateActive S WHERE SessionID=".$rowE['exp1']." AND S.LoginTimestamp!='0000-00-00 00:00:00';";
						$resultF=mysql_query($sqlF, $db);
						if (!$resultF) logProb("Could not load specific 00000 login...\n".$sqlF."\n".mysql_error());
						if (mysql_num_rows($resultF)>0) {
							$rowF=mysql_fetch_array($resultF);
							$sqlG="UPDATE connection SET timestamp='".$rowF['LoggedIn']."' WHERE id=".$rowE['id'].";";
							$resultG=mysql_query($sqlG,$localdb);
							#logProb("Adjusting LLO 00000's: ".$sqlG."");
							if (!$resultG) logProb("Failed to update Core LLO connection login into local db...\n".$sqlG."\n".mysql_error());
						}
					}
				}
			break;
		}
		# update the cfg
		$sql="UPDATE cfg SET locked=0,timeA=NOW(),valueB=".$newsincevalueB.",timeB=".$newsincetimeB." WHERE keyA='lastload' AND keyB='connection' AND valueA='".$type."';";
		mysql_query($sql, $localdb);
	}

#########################################################################################################################
	function refreshFirmcon($type,$interval) { # since will be midnight if it's the first load, otherwise it will be an exact timestamp.
		$localdb=db_connect("local");
		# if lastload,connection for this type's timestamp is < NOW() - backendpoll, load external.
		$sql="SELECT valueB FROM cfg WHERE keyA='lastload' AND keyB='connection' AND valueA='".$type."' AND (timeA < DATE_SUB(NOW(),INTERVAL ".$interval." SECOND) OR timeA IS NULL) AND locked=0;";
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb($sql."\n".mysql_error()); }
		if (mysql_num_rows($result)>0) {
			$row=mysql_fetch_array($result);
			refreshFirmconExternal($type,$row['valueB'],$localdb); # selects from system databases and inserts into local databases based on valueB (last id), connection
		}
		
		# differentiate here the logintypes 1,2 and 3 (to exclude Precise/FIX users from the DTI counts) ie. exclude users with a core login adapter 
		$dtimmonly = ($type==3) ? " AND u.id NOT IN (SELECT DISTINCT ad.out_identifier FROM adapter ad ) ":"";
		
		$sql="SELECT f.symbol, count(f.symbol) as 'actives' FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=".$type." AND c.timestamp > '".date("Y-m-d")." 00:00:00' AND u.logintype=".$type." AND u.id=c.userid AND fi.id=u.firmid $dtimmonly GROUP BY c.userid HAVING SUM(c.actiontype)=1) a, firm f WHERE a.bu=f.bu GROUP BY f.symbol ORDER BY f.symbol ASC;";
		
		#logProb("Calculating connections for type=".$type." :".$sql);
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb("PROBLEM RETRIEVING ACTIVE LOGINS PER FIRM\n".$sql."\n".mysql_error()); }
		$i=1;
		$activecount=0;
		while ($row=mysql_fetch_array($result)) {
			$response[$i++]=array("sym"=>$row['symbol']."-".$type."", "conns"=>$row['actives']);
			$activecount+=$row['actives'];
		}
		$response[0]=array("totaltype"=>"totalconn".$type."", "totalconn"=>1);
		
		# For *disconnections* representing the last connection by firm (per type) - find all the bu's (thus firms) whose sum connection total is 0 
		# then check to see if any other bu in the firm has active connections, if no, display a 0 (for that type)
		$sqlN="SELECT f.symbol FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=".$type." AND c.timestamp > '".date("Y-m-d")." 00:00:00' AND u.logintype=".$type." AND u.id=c.userid AND fi.id=u.firmid $dtimmonly GROUP BY c.userid HAVING SUM(c.actiontype)=0) a, firm f WHERE a.bu=f.bu AND symbol NOT IN (SELECT f.symbol FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=".$type." AND c.timestamp > '".date("Y-m-d")." 00:00:00' AND u.logintype=".$type." AND u.id=c.userid AND fi.id=u.firmid $dtimmonly GROUP BY c.userid HAVING SUM(c.actiontype)=1) a, firm f WHERE a.bu=f.bu GROUP BY f.symbol) GROUP BY f.symbol ORDER BY f.symbol ASC;";
		
		#logProb("Calculating zero-ed connections".$sqlN);
		$resultN=mysql_query($sqlN, $localdb);
		if (!$resultN) { logProb("PROBLEM RETRIEVING INACTIVE (ZERO'd) LOGINS PER FIRM\n".$sqlN."\n".mysql_error()); }
		while ($rowN=mysql_fetch_array($resultN)) {
			$response[$i++]=array("sym"=>$rowN['symbol']."-".$type."", "conns"=>0);
		}		
		
		echo json_encode($response);
		#logProb(getRealIpAddr()); # for finding 'zombies'
	}

	function refreshLLO() { # since will be midnight if it's the first load, otherwise it will be an exact timestamp.
		$localdb=db_connect("local");

		# no external loading necesasary
		$llowhere=" AND (process REGEXP 'Gateway-3[0123456789]-[12]_001' OR process REGEXP 'Gateway-2[456789]-[12]_001') ";
	
		$sql="SELECT f.symbol, count(f.symbol) as 'actives' FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=3 AND c.timestamp > '".date("Y-m-d")." 00:00:00' $llowhere AND u.logintype=3 AND u.id=c.userid AND fi.id=u.firmid GROUP BY c.userid HAVING SUM(c.actiontype)=1) a, firm f WHERE a.bu=f.bu GROUP BY f.symbol ORDER BY f.symbol ASC;";
				
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb("PROBLEM RETRIEVING LLO LOGINS PER FIRM\n".$sql."\n".mysql_error()); }
		$i=1;
		$activecount=0;
		while ($row=mysql_fetch_array($result)) {
			$response[$i++]=array("sym"=>$row['symbol'], "conns"=>$row['actives']);
			$activecount+=$row['actives'];
		}
		$response[0]=array("lloconn"=>$activecount);
		
		# For *disconnections* representing the last connection by firm (per type) - find all the bu's (thus firms) whose sum connection total is 0 
		# then check to see if any other bu in the firm has active connections, if no, display a 0 (for that type)
		$sqlN="SELECT f.symbol FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=3 AND c.timestamp > '".date("Y-m-d")." 00:00:00' $llowhere AND u.logintype=3 AND u.id=c.userid AND fi.id=u.firmid GROUP BY c.userid HAVING SUM(c.actiontype)=0) a, firm f WHERE a.bu=f.bu AND symbol NOT IN (SELECT f.symbol FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=3 AND c.timestamp > '".date("Y-m-d")." 00:00:00' $llowhere AND u.logintype=3 AND u.id=c.userid AND fi.id=u.firmid GROUP BY c.userid HAVING SUM(c.actiontype)=1) a, firm f WHERE a.bu=f.bu GROUP BY f.symbol) GROUP BY f.symbol ORDER BY f.symbol ASC;";
		
		#logProb("Calculating zero-ed connections".$sqlN);
		$resultN=mysql_query($sqlN, $localdb);
		if (!$resultN) { logProb("PROBLEM RETRIEVING INACTIVE (ZERO'd) LLO LOGINS PER FIRM\n".$sqlN."\n".mysql_error()); }
		while ($rowN=mysql_fetch_array($resultN)) {
			$response[$i++]=array("sym"=>$rowN['symbol'], "conns"=>0);
		}		
		echo json_encode($response);
	}

	
/* ################################################ ALERTS ################################################ */
	
	function getFirmCountAsOf($firmid, $logintype, $asof, $justbefore, &$localdb) {
		$asofoperator=($justbefore)?"<":"<=";
		$sql="SELECT count(f.symbol) as 'actives' FROM (SELECT fi.bu as 'bu' FROM connection c, user u, firm fi WHERE c.logintype=".$logintype." AND c.timestamp > '".date("Y-m-d")." 00:00:00' AND c.timestamp ".$asofoperator." '".$asof."' AND u.logintype=".$logintype." AND u.id=c.userid AND fi.id=u.firmid GROUP BY c.userid HAVING SUM(c.actiontype)=1) a, firm f WHERE a.bu=f.bu AND f.symbol=(SELECT fii.symbol FROM firm fii WHERE fii.id=".$firmid.") GROUP BY f.symbol;";
		
		# the solution commented below will only work if there can be duplicates for DTI users, which to my knowledge, there can not be.
		#$sql="SELECT sum(a.at) as 'actives' FROM (SELECT fi.bu as 'bu', SUM(c.actiontype) as 'at' FROM connection c, user u, firm fi WHERE c.logintype=".$logintype." AND c.timestamp > '".date("Y-m-d")." 00:00:00' AND c.timestamp ".$asofoperator." '".$asof."' AND u.logintype=".$logintype." AND u.id=c.userid AND fi.id=u.firmid GROUP BY c.userid) a, firm f WHERE a.bu=f.bu AND f.symbol=(SELECT fii.symbol FROM firm fii WHERE fii.id=".$firmid.") GROUP BY f.symbol;";
		
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb("PROBLEM RETRIEVING ACTIVE LOGINS PER FIRM\n".$sql."\n".mysql_error()); }
		$row=mysql_fetch_array($result);
		return (!vV($row['actives']))?0:$row['actives'];
	}
	
	function processAlerts($lastdisconid, $timespan, $percentage, $mintime, $maxtime, $minconn, $marketopen, $marketclose, &$localdb) {
		# update the cfg
		$sql="UPDATE cfg SET locked=1 WHERE keyA='lastload' AND keyB='alert';";
		$result=mysql_query($sql, $localdb);
		
		$newsincevalueA="valueA";
		$lastclause = (vV($lastdisconid)) ? " c.id > ".$lastdisconid." " : " c.timestamp >= '".date("Y-m-d")." 00:00:00' ";
		# get the disconnects that have occurred since the last alert check who occurred at least 'timespan' seconds in the past
		$sql="SELECT c.id as 'newsince', c.logintype, c.timestamp, DATE_ADD(c.timestamp, INTERVAL ".$timespan." SECOND) as 'checktimestamp', f.id as 'firmid' FROM connection c, user u, firm f WHERE c.userid=u.id AND u.firmid=f.id AND c.actiontype=-1 AND ".$lastclause." AND c.timestamp >= '".date("Y-m-d")." ".$marketopen."' AND c.timestamp <= '".date("Y-m-d")." ".$marketclose."' AND c.timestamp <= DATE_SUB(NOW(),INTERVAL ".$timespan." SECOND) ORDER BY c.id LIMIT 200;";
		$result=mysql_query($sql,$localdb);
		if (!$result) { logProb("PROBLEM RETRIEVING DISCONNECTIONS FOR ALERTS: \n".$sql."\n".mysql_error()); }
		while ($row=mysql_fetch_array($result)) {
			$newsincevalueA="'".$row['newsince']."'";
			#for each disconnect...firm count before and after
			$beforeconn=getFirmCountAsOf($row['firmid'], $row['logintype'], $row['timestamp'], true, $localdb);
				# if they want a separate alert type for mass disconnects regardless of span...take another count here (eg. "$currentcount")
			$afterconn=getFirmCountAsOf($row['firmid'], $row['logintype'], $row['checktimestamp'], false, $localdb);
			if ($beforeconn>=$minconn && $afterconn<($beforeconn*$percentage)) {
				# INSERT NEW ALERT
				$sqlA="INSERT INTO alert VALUES (NULL, 1, ".$row['firmid'].", ".$row['logintype'].", 1, ".$beforeconn.", '".$row['timestamp']."');";
				$resultA=mysql_query($sqlA,$localdb);
				if (!$resultA) { logProb("PROBLEM CREATING NEW ALERT: \n".$sqlA."\n".mysql_error()); }
			}
		}
		
		#Deactivate alerts older than the maxtime
		$sqlB="UPDATE alert SET active=0 WHERE DATE_ADD(triggered, INTERVAL ".$maxtime." MINUTE) < NOW();";
		$resultB=mysql_query($sqlB, $localdb);
		if (!$resultB) { logProb("Couldn't deactivate alerts: \n".$sqlB."\n".mysql_error()); }
		
		#Deactivate remaining alerts if the connections are back up and the mintime has elapsed, first finds ones that would be eligible for such a thing.
		$sqlC="SELECT id, firmid, logintype, startconn, triggered FROM alert WHERE active=1 AND DATE_ADD(triggered, INTERVAL ".$mintime." MINUTE) < NOW();";
		$resultC=mysql_query($sqlC, $localdb);
		if (!$resultC) { logProb("Error finding alerts to deactivate between mintime and maxtime: \n".$sqlC."\n".mysql_error()); }
		while ($rowC=mysql_fetch_array($resultC)) {
			$currentconn=getFirmCountAsOf($rowC['firmid'], $rowC['logintype'], date("Y-m-d H:i:s"), false, $localdb);
			if ($currentconn>($rowC['startconn']*$percentage)) {
				#Deactivate alerts because mintime has passed the threshold is no longer breached
				$sqlD="UPDATE alert SET active=0 WHERE id=".$rowC['id'].";";
				$resultD=mysql_query($sqlD, $localdb);
				if (!$resultD) { logProb("Couldn't deactivate alert id=[".$rowC['id']."]: \n".$sqlD."\n".mysql_error()); }
			}
		}
		
		# update the cfg
		$sql="UPDATE cfg SET locked=0,timeA=NOW(),valueA=".$newsincevalueA." WHERE keyA='lastload' AND keyB='alert';";
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb("PROBLEM UPDATING lastload,alert CFG: \n".$sql."\n".mysql_error()); }
	}
	
	function refreshAlerts($interval, $timespan, $percentage, $mintime, $maxtime, $minconn, $marketopen, $marketclose) {
		$localdb=db_connect("local");
		# if lastload,alert's timestamp is < NOW() - backendpoll, load external.
		$sql="SELECT valueA FROM cfg WHERE keyA='lastload' AND keyB='alert' AND (timeA < DATE_SUB(NOW(),INTERVAL ".$interval." SECOND) OR timeA IS NULL) AND locked=0;";
			#valueA=last processed (dis)connection id
		$result=mysql_query($sql, $localdb);
		if (!$result) { logProb($sql."\n".mysql_error()); }
		if (mysql_num_rows($result)>0) {
			$row=mysql_fetch_array($result);
			# will identify new alerts AND determine if existing alerts are still active...
			processAlerts($row['valueA'], $timespan, $percentage, $mintime, $maxtime, $minconn, $marketopen, $marketclose, $localdb); 
		}
		$sql="SELECT f.symbol, f.name as firmname, a.logintype, a.active as active, a.triggered as triggered, DATE_SUB(a.triggered, INTERVAL ".$timespan." SECOND) as pretrigger, DATE_ADD(a.triggered, INTERVAL ".$timespan." SECOND) as posttrigger, a.startconn, a.id as alertid FROM alert a, firm f WHERE a.firmid=f.id AND a.triggered > '".date("Y-m-d")." 00:00:00' GROUP BY a.triggered, f.symbol ORDER BY a.logintype, a.triggered;";
		$result=mysql_query($sql, $localdb);
		#logProb("Debug: First Alert Query: ".$sql);
		if (!$result) { logProb("PROBLEM RETRIEVING ACTIVE ALERTS PER FIRM\n".$sql."\n".mysql_error()); }
		$i=0;
		while ($row=mysql_fetch_array($result)) {
			# group responses by similar alerts (after the first record)
			if ($i>0 && $response[$i-1]["symbol"]==$row['symbol'] && $response[$i-1]["logintype"]==$row['logintype'] && 
				(strtotime($row['pretrigger']) <= strtotime($response[$i-1]["started"]))) {
				
			} else {
				$response[$i++]=array("symbol"=>$row['symbol'], "firm"=>$row['firmname'], "active"=>$row['active'], "logintype"=>$row['logintype'], "started"=>$row['triggered'], "startconn"=>$row['startconn'], "ended"=>$row['posttrigger'], "alertid"=>$row['alertid']); 
			}
		}
		# need a separate while to determine the logouts from the started time...
		if (count($response)>0) {
			foreach ($response as &$alert) {
				$sql="SELECT COUNT(c.id) as discons FROM connection c, user u, firm f WHERE c.userid=u.id AND u.firmid=f.id AND f.symbol='".$alert['symbol']."' AND c.actiontype=-1 AND c.timestamp >= '".$alert['started']."' AND c.timestamp <= '".$alert['ended']."';";
				$result=mysql_query($sql,$localdb);
				#logProb("Debug: First Alert Disconnect Query: ".$sql);
				if (!$result) { logProb("PROBLEM RETRIEVING DISCONNECTS PER FIRM\n".$sql."\n".mysql_error()); }
				$row=mysql_fetch_array($result);
				$alert['discons']=$row['discons'];
				$alert['started']=datetimetohms($alert['started']);
				$alert['ended']=datetimetohms($alert['ended']);
			}
		}
		if (vV($response)) { 
			echo json_encode($response);
		} else { echo 0; }
	}
?>