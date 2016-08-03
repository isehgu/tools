$(document).ready(function() { initialize(); });

var cfg_backendpoll, cfg_browserpoll;

function initialize() {
	$.post("include/firmcon_engine.php?action=getbrowsercfg", {}, function(i) {
		cfg_backendpoll=i.backendpoll;
		cfg_browserpoll=i.browserpoll;
		for (i=1;i<=3;i++) { // one for each type
			refreshFirmcon(i);
		}
		setTimeout(refreshLLO,250);
	}, "json");
}

// ************************************************** CONNECTIONS ****************************************************
	
function refreshFirmcon(i) {
	$.post("include/firmcon_engine.php?action=refresh&interval="+cfg_backendpoll+"&type="+i+"", {}, function(d) {
		$.each(d, function(j,c) {
			if (j==0) {
				$('#'+c.totaltype+'').text("asdf");
			} 
		});
		setTimeout(function() {refreshFirmcon(i);},cfg_browserpoll); // loop indefinitely
	}, "json");
}
	function applySort() { sortMethod(); }
	
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
				$('#lloconn').text(c.lloconn);
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