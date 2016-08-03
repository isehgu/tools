$(document).ready(function() { initialize(); });

var cfg_backendpoll, cfg_browserpoll;
var sortMethod=function() { sortByTotalConns(); }

function initialize() {
	initializeButtons();
	initializeSearch();
	$.post("include/firmcon_engine.php?action=getbrowsercfg", {}, function(i) {
		cfg_backendpoll=i.backendpoll;
		cfg_browserpoll=i.browserpoll;
		for (i=1;i<=3;i++) { // one for each type
			refreshFirmcon(i);
		}
		setTimeout(refreshAlerts,500);
		setTimeout(refreshLLO,250);
	}, "json");
}
	function initializeButtons() {
		$('.alertpanehd').toggle(function() { expandAlertPane($(this)); }, function() { collapseAlertPane($(this)); });
		$('#alphasort').click(function() { sortMethod=sortByFirmName; applySort(); });
		$('#firmconsort').click(function() { sortMethod=sortByTotalConns; applySort(); });
		$('#control-4-3').click(function() { window.location="include/cfg.php"; });
		$('#control-4-5').click(function() { sortMethod=function() { sortByTypeConns(0); }; applySort(); });
		$('#control-4-6').click(function() { sortMethod=function() { sortByTypeConns(1); }; applySort(); });
		$('#control-4-7').click(function() { sortMethod=function() { sortByTypeConns(2); }; applySort(); });
		$('.firmwrapper').click(function() { window.location='userview.php?symbol='+$(this).attr('id')+''; });
	}
	function initializeSearch() {
		$('#firmconsearchfield').keypress( function(e) { if ( e.which == 13 && $(this).val()!="" ) { searchFirms(); } } );
		$('#clearsearch').click(clearSearch);
	}

// ************************************************** SEARCH ****************************************************

function searchFirms() {
	var searchterm=$('#firmconsearchfield').val();
	$.post("include/firmcon_engine.php?action=search&term="+searchterm+"", {}, function(d) {
		hideAllFirms(true);
		$.each(d, function(i,r) { if (r!=0) showFirm(r.sym, true); });
		$('#clearsearch').show();
	}, "json");
}
	function hideAllFirms(b) {
		//var list = $('#allfirmscontainerbdinner');
		if (b) { $('.firmwrapper').hide(); } 
		else { $('.firmwrapper').show(); } // the all firms context
	}
	function showFirm(fid, b) { if (b) { $('#'+fid+'').show(); } else { $('#'+fid+'').hide(); } }
	function clearSearch() { $('#clearsearch').hide(); $('#firmconsearchfield').val(''); hideAllFirms(false); }
	
// ************************************************** CONNECTIONS ****************************************************
	
function refreshFirmcon(i) {
	$.post("include/firmcon_engine.php?action=refresh&interval="+cfg_backendpoll+"&type="+i+"", {}, function(d) {
		$.each(d, function(j,c) {
			if (j==0) {
				$('#'+c.totaltype+'').text(c.totalconn);
			} else {
				//$('.'+c.sym+'').text(c.conns);
				processFirmcon($('.'+c.sym+''),c.conns);
			}
		});
		setTimeout(function() {refreshFirmcon(i);},cfg_browserpoll); // loop indefinitely
		applySort();
	}, "json");
}
	function processFirmcon(jqo,conns) {
		if ($(jqo).text()!=conns) {
			//var oldC=$(jqo).text();
		}
		$(jqo).text(conns);
	}
	function applySort() { sortMethod(); }
	
	function sortByTotalConns() {
		var list = $('#allfirmscontainerbdinner');
		var allfirms = $('.firmwrapper',list); // the all firms context
		allfirms.sort(function(a, b){
			var keyA = parseInt($(a).children().eq(2).children().eq(0).text()) + 
				parseInt($(a).children().eq(2).children().eq(1).text()) + 
				parseInt($(a).children().eq(2).children().eq(2).text());
			var keyB = parseInt($(b).children().eq(2).children().eq(0).text()) + 
				parseInt($(b).children().eq(2).children().eq(1).text()) + 
				parseInt($(b).children().eq(2).children().eq(2).text());
			return keyB - keyA;
		});
		$.each(allfirms, function(i, r){
			list.append(r);
		});
	}

	function sortByTypeConns(i) {
		var list = $('#allfirmscontainerbdinner');
		var allfirms = $('.firmwrapper',list); // the all firms context
		allfirms.sort(function(a, b){
			var keyA = parseInt($(a).children().eq(2).children().eq(i).text());
			var keyB = parseInt($(b).children().eq(2).children().eq(i).text());
			return keyB - keyA;
		});
		$.each(allfirms, function(i, r){
			list.append(r);
		});
	}	
	
	function sortByFirmName() {
		var list = $('#allfirmscontainerbdinner');
		var allfirms = $('.firmwrapper',list);
		allfirms.sort(function(a, b){
			var keyA = $(a).children().eq(0).text().toLowerCase();
			var keyB = $(b).children().eq(0).text().toLowerCase();
			return keyB < keyA;
		});
		$.each(allfirms, function(i, r){
			list.append(r);
		});
	}	

function refreshLLO() {
	$.post("include/firmcon_engine.php?action=refreshllo", {}, function(d) {
		$.each(d, function(j,c) {
			if (j==0) {
				$('#lloconn').text("FF");
			} else {
				//$('.'+c.sym+'').text(c.conns);
				processLLO($('#'+c.sym+' .firmhd'),c.conns);
			}
		});
		setTimeout(function() {refreshLLO();},cfg_browserpoll); // loop indefinitely
	}, "json");
}	
	function processLLO(jqo,conns) {
		if (conns>0) {
			$(jqo).addClass('LLO');
		} else {
			$(jqo).removeClass('LLO');
		}
	}

// ************************************************** ALERTS ****************************************************
	
function refreshAlerts() {
	$.post("include/firmcon_engine.php?action=refreshAlerts&interval="+cfg_backendpoll+"", {}, function(d) {
		var alertcounts=[0,0,0,0];
		clearAlerts();
		$.each(d, function(j,c) {
			alertcounts[c.logintype]++;
			if (c.active==1) {
				if ($('#'+c.symbol+'-'+c.logintype+'-fb').length) {
					var eA=parseInt($('#'+c.symbol+'-'+c.logintype+'-fb').text())
					$('#'+c.symbol+'-'+c.logintype+'-fb').text(eA+1);
				} else {
					$('#containerbdinner-'+c.logintype+'').append(
						$('#'+c.symbol+'').clone(false).removeAttr('id').removeClass('NORMAL').addClass('ALERT').click(function() { 
							window.location='userview.php?symbol='+c.symbol+'';}).append('<div id="'+c.symbol+'-'+c.logintype+'-fb" class="firmbadge ALERT">1</div>')
					);
				}
			}
			if (alertcounts[c.logintype]>$('#alert-'+c.logintype+'-4').text()) {
				logAlertToPane(c, alertcounts[c.logintype]); // log new alerts to pane
			}
		});
		for (i=1;i<=3;i++) { $('#alert-'+i+'-4').text(alertcounts[i]); }
		containerDisplayLogic();
		setTimeout(function() {refreshAlerts();},cfg_browserpoll); // loop indefinitely
	}, "json");
}

// move all alerts off the alert bars
function clearAlerts() {
	var alertfirms = $('.ALERT');
	$.each(alertfirms, function(i,r) { $(r).remove(); });
}

	function containerDisplayLogic() {
		var activegroups=0;
		// count the number of objects with ALERT class within each firmcontainer
		for (var i=1;i<=3;i++) {
			if ($('#containerbdinner-'+i+'').children('.ALERT').length>0) {
				$('#alerts-'+i+'').show();
				activegroups++;
			} else {
				$('#alerts-'+i+'').hide();
			}
		}
		// give #allfirmscontainerbdinner and #allfirms the modulo bar class
		$('#allfirmscontainerbdinner').removeClass('afcbdi-2bar afcbdi-3bar afcbdi-4bar afcbdi-5bar').addClass('afcbdi-'+(5-activegroups)+'bar');
		$('#allfirms').removeClass('af-2bar af-3bar af-4bar af-5bar').addClass('af-'+(5-activegroups)+'bar');
	}

function logAlertToPane(a, i) {
	var timephrase = 'between '+a.started+' and '+a.ended+'';
	var alertshade = (i%2==0) ? 'EVEN' : 'ODD';
	var targetpane = $('#alertpane-'+a.logintype+'').children('.alertpanebd').eq(0);
	targetpane.html('<a href="userview.php?symbol='+a.symbol+'&alertid='+a.alertid+'"><div class="alerttext '+alertshade+'"><strong>'+a.started+' - '+a.discons+' disconnects</strong><br/>'+a.discons+'/'+a.startconn+' users from '+a.firm+' ('+a.symbol+') disconnected '+timephrase+'.</div></a>'+targetpane.html()+'');
}
	
// yes, these could easily be combined, but it's easy to read this way isn't it?
function expandAlertPane(o) {
	var alertpane=$(o).parent();
	$(o).children('.alertpanepopout').children('img').attr('src','img/sq_down_icon&24.png');
	alertpane.animate({ bottom:40 }, 100);
}
function collapseAlertPane(o) {
	var alertpane=$(o).parent();
	$(o).children('.alertpanepopout').children('img').attr('src','img/sq_up_icon&24.png');
	alertpane.animate({ bottom:-460 }, 100);
}