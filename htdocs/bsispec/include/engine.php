<?php
	include("../cfg/functions.php");
	$localdb=db_connect("local");
	
	$action=$_GET['action'];
	$app=$_GET['app'];
	
	switch ($action) {
		case "detail":
			$id=$_POST['internalid'];
			$sql="SELECT property_name, property_value FROM ".$app."  WHERE id=".$id." AND property_name NOT LIKE 'internal_id' ORDER BY property_name";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			$i=0;
			while ($row=mysql_fetch_array($result)) {
				$response[$i++]=array("name"=>$row['property_name'], "value"=>$row['property_value']);
			}
			echo json_encode($response);
		break;
		case "advsearch":
			$field=$_POST['field'];
			if (substr($field, 0,8)=="autoPref") { # logic for checking autoPref or autoPrefSpread preferencing existence
				if (vV($_POST['fieldval'])) 
					$fieldval=" AND property_value LIKE '".$_POST['fieldval']."'";
				if ($_POST['fieldval']=="Any Firm") // "Any Firm" value set below in case "iorsadvsearchsselect"
					$fieldval="";
				$sql="SELECT DISTINCT id FROM ".$app."  WHERE property_name LIKE '".$field."%' ".$fieldval.";";
			} else { # logic for everything else
				$operator=$_POST['operator'];
				$fieldval=$_POST['fieldval'];
				switch ($operator) {
					case "Contains": $operator=" LIKE "; $fieldval="%".$fieldval."%"; break;
					case "Does Not Contain": $operator=" NOT LIKE "; $fieldval="%".$fieldval."%"; break;
					case "Begins With": $operator=" LIKE "; $fieldval=$fieldval."%"; break;
					case "Does Not Begin With": $operator=" NOT LIKE "; $fieldval=$fieldval."%"; break;
					case "Ends With": $operator=" LIKE "; $fieldval="%".$fieldval; break;
					case "Does Not End With": $operator=" NOT LIKE "; $fieldval="%".$fieldval; break;
					case "Equals": $operator=" = "; break;
					case "Does Not Equal": $operator=" <> "; break;
				}
				# return the adapter instance ids associated with the filter
				$sql="SELECT id FROM ".$app."  WHERE property_name='".$field."' AND property_value ".$operator." '".$fieldval."' ;";
			}
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			if (mysql_num_rows($result)==0) {
				echo 0;
			} else {
				$i=0;
				while ($row=mysql_fetch_array($result)) {
					$response[$i++]=array("id"=>$row['id']);
				}
				echo json_encode($response);
			}
		break;
		case "distinctbsi":
			$adapterids=$_POST['ids'];
			$sql="SELECT DISTINCT property_value FROM ".$app."  WHERE id IN (".$adapterids.") AND property_name='bsi_name';";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			$i=0;
			while ($row=mysql_fetch_array($result)) {
				$response[$i++]=array("bsiname"=>$row['property_value']);
			}
			echo json_encode($response);
		break;
		case "listview":
			$adapterids=$_POST['ids'];
			$searchtitle=$_POST['searchtitle'];
			echo "listview-".$app.".php?ids=".$adapterids."&searchtitle=".$searchtitle."";
		break;
		case "advsearchsselect":
			$field=$_POST['field'];
			$i=0;
			if ($field=="autoPrefBin" || $field=="autoPrefSpreadBin") {
				$sql="SELECT DISTINCT property_value FROM ".$app."  i WHERE property_name LIKE '".$field."%' and property_value<>'' ORDER BY property_value ASC;";
				$response[$i++]=array("field"=>"Any Firm"); // add custom choice for preferencing
			} else {
				$sql="SELECT DISTINCT property_value FROM ".$app."  i WHERE property_name='".$field."' and property_value<>'' ORDER BY property_value ASC;";
			}
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			while ($row=mysql_fetch_array($result)) {
				$response[$i++]=array("field"=>$row['property_value']);
			}
			echo json_encode($response);
		break;
		case "customruleselect":
			$sql="SELECT rulename FROM iors_customrules i ORDER BY rulename ASC;";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			$i=0;
			while ($row=mysql_fetch_array($result)) {
				$response[$i++]=array("field"=>$row['rulename']);
			}
			echo json_encode($response);
		break;
		case "getrule":
			$rulename=$_POST['rulename'];
			$sql="SELECT actiontype, direction, fieldtag, matchcriteria, fieldtype, newvalue FROM iors_customrules WHERE rulename LIKE '".$rulename."';";
			$result=mysql_query($sql,$localdb);
			if (!$result) logProb($sql."\n\r".mysql_error());
			$row=mysql_fetch_array($result);
			$response[0]=array("point"=>"Action Type", "value"=>$row['actiontype']);
			$response[1]=array("point"=>"Direction", "value"=>$row['direction']);
			$response[2]=array("point"=>"Field Tag", "value"=>$row['fieldtag']);
			$response[3]=array("point"=>"Match Criteria", "value"=>stripslashes($row['matchcriteria']));
			$response[4]=array("point"=>"Field Type", "value"=>$row['fieldtype']);
			$response[5]=array("point"=>"New Value", "value"=>$row['newvalue']);
			echo json_encode($response);
		break;
	}
?>