<?php
	include("include/precise.php");
	$preciseadapters=array();
	$precisebsis=array();
	loadPreciseConfiguration($preciseadapters, $precisebsis);
	$adapterids=$_GET['ids'];
	$searchtitle=$_GET['searchtitle'];
	$adapters=explode(",",$adapterids);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BSISpec - Precise List View</title>
	<style type="text/css">
		body { margin:10; font-family:helvetica; font-size:12px; }
		th { font-weight:bold; text-align:left; border-bottom:1px #000 solid;}
		td { border-right:1px #777 solid;border-bottom:1px #777 solid; }
	</style>
</head>
<body>
<?php echo "<h2>".$searchtitle."</h2>"; ?>
<table cellspacing='0' cellpadding='2' id="listviewtable">
<tr><th width="150">Adapter Name</th><th width="100">Core Login</th><th width="120">Core GW/Port</th><th width="120">Service Group</th></tr>
<?php
	foreach ($preciseadapters as $pa) { 
		if (in_array($pa->PropertySet['internal_id'],$adapters)) { ?>
			<tr>
				<td><?php echo $pa->PropertySet['adapter_name']; ?></td>
				<td><?php echo "".$pa->PropertySet['member']."-".$pa->PropertySet['user'].""; ?></td>
				<td><?php echo "".$pa->PropertySet['node'].":".$pa->PropertySet['port'].""; ?></td>
				<td><?php echo $pa->PropertySet['bsi_name']; ?></td>
			</tr>
		<?php }
	}
?>
</table>

<p><br/></p>

</body></html>