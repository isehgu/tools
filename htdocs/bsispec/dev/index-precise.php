<?php
	include("include/precise.php");
	
	$preciseadapters=array();
	$precisebsis=array();
	$preciseadvsearch=array();
	
	loadPreciseConfiguration($preciseadapters, $precisebsis);
	loadPreciseAdvSearch($preciseadvsearch);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BSISpec 1.01 - PRECISE</title>
	<script type="text/javascript" src="cfg/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="js/bsispec-precise.js"></script>
	<link href="css/bsispec-precise.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="bar1" class="opsbar">
	<div class="barfield" id="bar1_hd"><a href="index.php">IORS</a><br/><span style="color:#000;">PRECISE</span>
	</div>
	<div class="barfield" id="bar1_search">SEARCH ALL ADAPTERS<br/>
		<input type="text" id="a_search" maxlength="30" autocomplete="off"/><br/>
		<div id="a_searchfeedback">SEARCH</div>
	</div>
	<div class="barfield" id="bar1_advsearch">SEARCH BY FILTER<br/>
		<div class="bardiv" id="advsearch_field_div">
			<select id="advsearch_field" size=1><option></option>
				<?php foreach ($preciseadvsearch as $pas) { $pas->printAsOption();	} ?>
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
		<?php foreach ($precisebsis as $pb) { 
			$pb->printHTML(); 
		} ?>
	</div>
	<div class="barfield" id="bar2_amr">
		<div class="PRECISE" id="AMR">
			<span class="bsinumber">AMR</span>
		</div>
	</div>
</div>

<div id="bar3" class="opsbar">
	<div class="barfield" id="bar3_hd"><p>Adapter Count<br/><span id="a_count">0</span></p>
	</div>
	<div class="barfield" id="bar3_message">All Adapters</div>
</div>

<br />

<div id="mainpane">

<?php foreach ($preciseadapters as $pa) { $pa->printHTML(); } ?>

<div style="clear:both;"></div> <!-- avoid the great collapse -->
</div>

<div id="inspectpane" class="windowwrapper">
	<div id="inspectclose" class="windowclose">[hide]</div>
	<div id="inspect_hd" class="window_hd"></div>
	<div id="inspect_bd" class="window_bd">

		<span>BSI Adapter Configuration</span>
		<div id="inspect_bottom" class="inspectcol">
			<table cellspacing="0" cellpadding="0" id="inspect_table" class="inspect"></table>
		</div>
	</div>
</div>

<p><br /></p>
</body>
</html>