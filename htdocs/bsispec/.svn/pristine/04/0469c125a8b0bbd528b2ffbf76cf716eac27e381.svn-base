<?php
	include("include/iors.php");
	
	$iorsadapters=array();
	$iorsbsis=array();
	$iorsadvsearch=array();
	
	loadIORSConfiguration($iorsadapters, $iorsbsis);
	loadIORSAdvSearch($iorsadvsearch);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BSISpec 1.01 Dev - IORS</title>
	<script type="text/javascript" src="cfg/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="js/bsispec-iors.js"></script>
	<link href="css/bsispec-iors.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="bar1" class="opsbar">
	<div class="barfield" id="bar1_hd"><span style="color:#000;">IORS</span><br /><a href="index-precise.php">PRECISE</a>
	</div>
	<div class="barfield" id="bar1_search">SEARCH ALL ADAPTERS<br/>
		<input type="text" id="a_search" maxlength="30" autocomplete="off"/><br/>
		<div id="a_searchfeedback">SEARCH</div>
	</div>
	<div class="barfield" id="bar1_advsearch">SEARCH BY FILTER<br/>
		<div class="bardiv" id="advsearch_field_div">
			<select id="advsearch_field" size=1><option></option>
				<option value="preferencing">autoPrefBin</option><option value="preferencing">autoPrefSpreadBin</option>
				<?php foreach ($iorsadvsearch as $ias) { $ias->printAsOption();	} ?>
			</select>
		</div>
		<div class="bardiv" id="advsearch_operator_div" style="margin-right:0px;">
			<select id="advsearch_operator" size=1><option></option>
				<option>Contains</option><option>Does Not Contain</option><option>Begins With</option><option>Does Not Begin With</option>
				<option>Ends With</option><option>Does Not End With</option><option>Equals</option><option>Does Not Equal</option>
			</select>
		</div>
		<br /><br /><br />
		<div class="bardiv" id="advsearch_input_div">
			<input type="text" id="advsearch_input" autocomplete="off" />
			<select id="advsearch_valselect"></select>
		</div>
		<div id="advsearch_submit_div" class="bardiv" style="margin-right:0px;">
			<div id="advsearch_submit">SEARCH</div>
		</div>
	</div>
	<div class="barfield" id="bar1_list">LIST <br/>SELECTED <br/>ADAPTERS
		<div id="list_submit">LIST</div>
	</div>
</div>

<div id="bar2" class="opsbar">
	<div class="barfield" id="bar2_hd">
		<p>Service<br />Groups</p>
	</div>
	<div class="barfield" id="bar2_adapters">
		<?php $bsitype="DCA";
		foreach ($iorsbsis as $ib) { 
			if ($bsitype!=$ib->getBsiType()) echo "<br style='clear:both;' />";
			$ib->printHTML(); 
			$bsitype=$ib->getBsiType();
		} ?>
	</div>
</div>

<div id="bar3" class="opsbar">
	<div class="barfield" id="bar3_hd"><p>Adapter Count<br/><span id="a_count">0</span></p>
	</div>
	<div class="barfield" id="bar3_message">All Adapters</div>
</div>

<br />

<div id="mainpane">

<?php foreach ($iorsadapters as $ia) { $ia->printHTML(); } ?>

<div style="clear:both;"></div> <!-- avoid the great collapse -->
</div>

<div id="inspectpane" class="windowwrapper">
	<div id="inspectclose" class="windowclose">[hide]</div>
	<div id="inspect_hd" class="window_hd"></div>
	<div id="inspect_bd" class="window_bd">
		<span>Fix Adapter Configuration</span>
		<div id="inspect_top" class="inspectcol">
			<table cellspacing="0" cellpadding="0" id="inspect_top_table" class="inspect"></table>
		</div>
		<span>BSI Adapter Configuration</span>
		<div id="inspect_bottom" class="inspectcol">
			<table cellspacing="0" cellpadding="0" id="inspect_bottom_table" class="inspect"></table>
		</div>
	</div>
</div>

<div id="customrulepane" class="windowwrapper">
	<div id="customruleclose" class="windowclose">[hide]</div>
	<div id="customrule_hd" class="window_hd"></div>
	<div id="customrule_bd" class="window_bd">	
		<table cellspacing="0" cellpadding="0" id="customrule_table"></table>
	</div>
</div>

<p><br /></p>
</body>
</html>