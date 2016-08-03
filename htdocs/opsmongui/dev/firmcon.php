<?php
	include('include/firmcon.php');
	$firms=array();
	$users=array();
	$adapters=array();
	loadStaticData("firm", $firms);
	loadStaticData("user", $users);	
	loadStaticData("adapter", $adapters);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="refresh" content="7200;">
<title>firmcon 0.8 Dev</title>
	<script type="text/javascript" src="cfg/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="js/firmcon.js"></script>
	<link href="css/firmcon.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="firmconhd" class="fullbar">
        <div id="firmcontitle">FIRMCON: Firm Connectivity Monitor</div>
        <div id="firmconsearch">Search: <input type="text" id="firmconsearchfield" size="20" autocomplete="off" />
			<img id="clearsearch" width="18" src="img/round_delete_icon&24.png" alt="Clear Search"/></div>
    </div>
	
    <div id="alerts-1" class="firmcontainer">
        <div class="firmcontainerhd">
            <div class="vaouter">
                   <div class="vainner">Precise<br/> Alerts</div>
            </div>
			<div class="firmcontainerhdcontrols">
				<div class="control-slot-1" id="control-1-1"></div>
				<div class="control-slot-2" id="control-1-2"><!-- <img class="IDLE" width="18" src="img/zoom_icon&24.png" alt="Analyze Active Alerts" />--></div>
				<div class="control-slot-3" id="control-1-3"><!--<img class="IDLE" width="18" src="img/attention_icon&24.png" alt="Analyze All Alerts"/>--></div>
				<div class="control-slot-4 IDLE" id="control-1-4"></div>
			</div>
        </div>
        <div class="firmcontainerbd">
			<div class="firmcontainerbdinner" id="containerbdinner-1"></div>
        </div>
    </div>

	<div id="alerts-2" class="firmcontainer">
        <div class="firmcontainerhd">
            <div class="vaouter">
                   <div class="vainner">FIX<br/> Alerts</div>
            </div>
			<div class="firmcontainerhdcontrols">
				<div class="control-slot-1" id="control-2-1"></div>
				<div class="control-slot-2" id="control-2-2"><!--<img class="IDLE" width="18" src="img/zoom_icon&24.png" alt="Analyze Active Alerts"/>--></div>
				<div class="control-slot-3" id="control-2-3"><!--<img class="IDLE" width="18" src="img/attention_icon&24.png" alt="Analyze All Alerts" />--></div>
				<div class="control-slot-4 IDLE" id="control-2-4"></div>
			</div>
		</div>
        <div class="firmcontainerbd">
			<div class="firmcontainerbdinner" id="containerbdinner-2"></div>
        </div>
    </div>
	
	<div id="alerts-3" class="firmcontainer">
        <div class="firmcontainerhd">
            <div class="vaouter">
                   <div class="vainner">DTI<br/> Alerts</div>
            </div>
			<div class="firmcontainerhdcontrols">
				<div class="control-slot-1" id="control-3-1"></div>
				<div class="control-slot-2" id="control-3-2"><!--<img class="IDLE" width="18" src="img/zoom_icon&24.png" alt="Analyze Active Alerts"/>--></div>
				<div class="control-slot-3" id="control-3-3"><!--<img class="IDLE" width="18" src="img/attention_icon&24.png" alt="Analyze All Alerts" />--></div>
				<div class="control-slot-4 IDLE" id="control-3-4"></div>
			</div>
        </div>
        <div class="firmcontainerbd">
			<div class="firmcontainerbdinner" id="containerbdinner-3"></div>
        </div>
    </div>
	
	<div id="allfirms" class="firmcontainer af-5bar">
	    <div class="firmcontainerhd"> 
            <div class="vaouter">
                   <div class="vainner">All<br/>Firms</div>
            </div>
			<div class="firmcontainerhdcontrols">
				<div class="control-slot-1" id="control-4-1">
					<img id="alphasort" class="IDLE" width="18" src="img/font_size_icon&24.png" alt="Sort Alphabetically" /></div>
				<div class="control-slot-2" id="control-4-2">
					<img id="firmconsort" class="IDLE" width="18" src="img/connect_icon&24.png" alt="Sort by Total Connections"/></div>
				<div class="control-slot-3" id="control-4-3"><img class="IDLE" width="18" src="img/cogs_icon&24.png" alt="Settings" /></div>
				<div class="control-slot-4" id="control-4-4"></div>
				<div class="control-slot-5" id="control-4-5">P</div>
				<div class="control-slot-6" id="control-4-6">F</div>
				<div class="control-slot-7" id="control-4-7">D</div>
				<div class="control-slot-8" id="control-4-8"></div>
			</div>
        </div>	
		<div class="firmcontainerbd">
			<div class="firmcontainerbdinner afcbdi-5bar" id="allfirmscontainerbdinner">
			<?php foreach ($firms as $f) { ?>
				<div id="<?php echo $f->symbol; ?>" class="firmwrapper NORMAL">
					<div class="firmhd"><?php echo $f->name; ?></div>
					<div class="firmlabelwrapper">
						<div class="firmlabel">PRECISE</div><div class="firmlabel">FIX</div><div class="firmlabel">DTI</div>
					</div>
					<div class="firmvaluewrapper">
						<div class="firmvalue <?php echo $f->symbol; ?>-1">0</div><div class="firmvalue <?php echo $f->symbol; ?>-2">0</div><div class="firmvalue <?php echo $f->symbol; ?>-3">0</div>
					</div>
					<!--<div class="firmbadge"></div> added in JS-->
				</div>
			<?php } ?>
			</div>
		</div>
	</div>	
	
	<div class="alertpane COLLAPSE" id="alertpane-1">
		<div class="alertpanehd">
			<div class="alertpanepopout"><img id="alert-popout-1" class="IDLE" width="24" src="img/sq_up_icon&24.png" alt="Show Precise Alerts" /></div>
			<div class="alertpanelabel">PRECISE</div>
			<div class="alertslot as-1" id="alert-1-1"></div>
			<div class="alertslot as-2" id="alert-1-2"></div>
			<div class="alertslot as-3" id="alert-1-3"><img class="IDLE" width="24" src="img/attention_icon&24.png" alt="Analyze All Alerts" /></div>
			<div class="alertslot as-4" id="alert-1-4">0</div>
		</div>
		<div class="alertpanebd"></div>
	</div>

	<div class="alertpane COLLAPSE" id="alertpane-2">
		<div class="alertpanehd">
			<div class="alertpanepopout"><img id="alert-popout-2" class="IDLE" width="24" src="img/sq_up_icon&24.png" alt="Show FIX Alerts" /></div>
			<div class="alertpanelabel">FIX</div>
			<div class="alertslot as-1" id="alert-2-1"></div>
			<div class="alertslot as-2" id="alert-2-2"></div>
			<div class="alertslot as-3" id="alert-2-3"><img class="IDLE" width="24" src="img/attention_icon&24.png" alt="Analyze All Alerts" /></div>
			<div class="alertslot as-4" id="alert-2-4">0</div>
		</div>
		<div class="alertpanebd"></div>
	</div>

	<div class="alertpane COLLAPSE" id="alertpane-3">
		<div class="alertpanehd">
			<div class="alertpanepopout"><img id="alert-popout-3" class="IDLE" width="24" src="img/sq_up_icon&24.png" alt="Show DTI Alerts" /></div>
			<div class="alertpanelabel">DTI</div>
			<div class="alertslot as-1" id="alert-3-1"></div>
			<div class="alertslot as-2" id="alert-3-2"></div>
			<div class="alertslot as-3" id="alert-3-3"><img class="IDLE" width="24" src="img/attention_icon&24.png" alt="Analyze All Alerts" /></div>
			<div class="alertslot as-4" id="alert-3-4">0</div>
		</div>
		<div class="alertpanebd"></div>
	</div>
	
	<div id="firmconft" class="fullbar">
        <div id="firmconsummaryhd">Connectivity Summary</div>
		<div id="firmconsummarybd">
			<div class="firmconsummaryfield"><a href="userview.php?llo=1">Low Latency: <div class="firmconsummaryvalue" id="lloconn">0</div></a></div>
			<div class="firmconsummaryfield">DTI: <div class="firmconsummaryvalue" id="totalconn3">0</div></div>
			<div class="firmconsummaryfield">FIX: <div class="firmconsummaryvalue" id="totalconn2">0</div></div>
			<div class="firmconsummaryfield">Precise: <div class="firmconsummaryvalue" id="totalconn1">0</div></div>
		</div>
    </div>
</body>
</html>