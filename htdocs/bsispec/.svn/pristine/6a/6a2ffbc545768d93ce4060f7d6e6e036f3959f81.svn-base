$(document).ready(function() { initialize(); });

function initialize() {
	enableSearch();
	enableBSIClick();
	updateCount();
	enableDetail();
	enableClose();
	enableListView();
	enableAMR();
}

function enableAMR() {
	$('#AMR').click(function() { window.location="precise-amr.php"; });
}

function enableListView() {
	$('#list_submit').click(function() { // one day, include the search string as well
		$.post("include/engine.php?app=precise&action=listview", {ids:gatherActiveIds().join(','), searchtitle:$('#bar3_message').text()}, function(o) {
			window.location=o;
		});
	});
}

function enableClose() { 
	$('#inspectclose').click( function() { 
		toggleDiv($('#inspectpane'));
		if ($('#customrulepane').is(':visible')) { toggleDiv($('#customrulepane')); }
	});
	$('#customruleclose').click( function() { toggleDiv($('#customrulepane'));} );
}

function enableSearch() {
	$('#a_search').keypress( function(e) { if ( e.which == 13 ) { searchAdapters(); }	} );
	$('#a_searchfeedback').click(function () { searchAdapters(); } );
	
	// ADVANCED SEARCH - though undeniable, this logic is messy.
	$('#advsearch_field').change(function(){
		var fieldselect=$('option:selected', this);
		$('#advsearch_operator').attr('disabled',false);
		allowAnotherAdvSearch();
		if (fieldselect.attr('disabled')=='true') {
			return false;
		} else if (fieldselect.text()=='') { resetAdvSearch(); // if the blank is chosen reset the form
		} else if (fieldselect.val()=="preferencing") { // AUTOPREF / AUTOPREFSPREAD PREFERENCING 
			var curoptions="";
			$.post("include/engine.php?app=precise&action=advsearchsselect", { field:fieldselect.text() }, function(opt) {
				$('#advsearch_input').hide();
				$.each (opt, function(i,o) {
					curoptions+="<option>"+o.field+"</option>";
				});
				$('#advsearch_valselect').html(curoptions);
				$('#advsearch_operator option:eq(1)').attr('selected',true); // "contains"
				$('#advsearch_operator').attr('disabled',true); // the operator is not relevant for preferencing, so disable it
				$('#advsearch_valselect').show();
			}, "json");
		} else if (fieldselect.text() == 'cc_customrules') { // CUSTOM RULES CUSTOM DROPDOWN
			var curoptions="";
			$.post("include/engine.php?app=precise&action=customruleselect", { }, function(opt) {
				$('#advsearch_operator option:eq(1)').attr('selected',true);
				$('#advsearch_input').hide();
				$.each (opt, function(i,o) {
					curoptions+="<option>"+o.field+"</option>";
				});
				$('#advsearch_valselect').html(curoptions);
				$('#advsearch_valselect').show();
			}, "json");		
		} else if (fieldselect.val() <= 20) { // SHOW A DROPDOWN B/C THERE ARE <= 20 DISTINCT POSSIBLE VALUES
			var curoptions="";
			$.post("include/engine.php?app=precise&action=advsearchsselect", { field:fieldselect.text() }, function(opt) {
				$('#advsearch_input').hide();
				$.each (opt, function(i,o) {
					curoptions+="<option>"+o.field+"</option>";
				});
				$('#advsearch_valselect').html(curoptions);
				$('#advsearch_operator option:eq(7)').attr('selected',true); // choose "EQUALS"
				$('#advsearch_valselect').show();
			}, "json");
		} else { // SHOW AN INPUT BOX B/C THERE ARE > 20 DISTINCT POSSIBLE VALUES
			$('#advsearch_operator option:eq(1)').attr('selected',true); // choose "CONTAINS"
			$('#advsearch_valselect').hide();
			$('#advsearch_input').val('').show();
		}
	});
	// if an advanced search has taken place, subsequent changes to the form should allow another search w/o resetting completely.
	$('#advsearch_valselect').change(function() { allowAnotherAdvSearch(); });
	$('#advsearch_operator').change(function() { allowAnotherAdvSearch(); });
	$('#advsearch_input').change(function() { allowAnotherAdvSearch(); });
	
	$('#advsearch_input').keypress( function(e) { if ( e.which == 13 ) { advSearchAdapters(); }	} );
	$('#advsearch_submit').click(function() { advSearchAdapters();	});
	
	$('#advsearch_field option:eq(0)').attr('selected',true);
	$('#advsearch_operator option:eq(0)').attr('selected',true);
}

function searchAdapters() {
	var txt=$('#a_search').val();
	if (txt=="") return false;
	$('.pa').hide();
	$(".pa_data:contains('"+txt+"')").parent().parent().show(); // this is not the best way to search...add JSON
	$(".pa_hd:contains('"+txt+"')").parent().show();
	updateBSIDisplay();
	updateCount();
	$('#bar3_message').text('Adapters Containing "'+txt+'"');
	cycleSearchFeedback(2);
}

function allowAnotherAdvSearch() {
	if ($('#advsearch_submit').text()=='CLEAR SEARCH') {
		$('#advsearch_submit').unbind().text('SEARCH').click(function () { advSearchAdapters(); } );
	}
}

function advSearchAdapters() {
	var curfield=$('#advsearch_field option:selected').text();
	var curoperator=$('#advsearch_operator option:selected').text();
	var curfieldval=($('#advsearch_valselect').is(':visible')) ? $('#advsearch_valselect option:selected').text() : $('#advsearch_input').val() ;
	if (curfieldval=="") return false;
	$.post("include/engine.php?app=precise&action=advsearch", { field:curfield, operator:curoperator, fieldval:curfieldval }, function(a) {
		$('.pa').hide();
		$.each (a, function(i,p) {
			$('#'+p.id+'').show();
		});
		updateBSIDisplay();
		updateCount();
		$('#bar3_message').text('Adapters Where '+curfield+' '+curoperator+' "'+curfieldval+'"');
		cycleSearchFeedback(3);
	}, "json");
}

function clearSearch() {
	$('#a_search').val("");
	$('.pa').show();
	$('#bar3_message').text('All Adapters');
	colorBSIs();
	updateCount();
	resetAdvSearch();
	cycleSearchFeedback(2);
}

	function cycleSearchFeedback(s) { // UTTER MESS...can't filter simple search results, can't add simple search to filter search
		if (s==2) {
			if ($('#a_search').val()=="") { // SEARCH is over?
				$('#a_searchfeedback').unbind().text('SEARCH').click(function () { searchAdapters(); } );
			} else { // SEARCH HAS BEEN ENTERED
				$('#a_searchfeedback').unbind().text('CLEAR SEARCH').click(function () { clearSearch(); } );
			}
			if ($('#advsearch_submit').text()=='CLEAR SEARCH') {
				resetAdvSearch();
				$('#advsearch_submit').unbind().text('SEARCH').click(function () { advSearchAdapters(); } );
			}
		} else if (s==3) { // ADV SEARCH SUBMITTED
			$('#a_search').val('');
			$('#a_searchfeedback').unbind().text('SEARCH').click(function () { searchAdapters(); } );
			$('#advsearch_submit').unbind().text('CLEAR SEARCH').click(function () { clearSearch(); } );
			$('#advsearch_operator').attr('disabled',false);
		}
	}
	
/* BSI DISPLAY FUNCTIONS */
function gatherActiveIds() {
	var activeids=new Array();
	$('.pa:visible').each(function() {
		activeids.push($(this).attr('id'));
	});
	return activeids;
}

function updateBSIDisplay() {
	var aids=gatherActiveIds().join(',');
	if (aids=="") greyBSIs();
	else {
		$.post("include/engine.php?app=precise&action=distinctbsi", {ids:aids}, function(b) {
			greyBSIs(); // strip BSIs
			$.each (b, function(i,s) { // loop and add active
				$('.bsi[id="'+s.bsiname+'"]').removeClass('INACTIVE');
			});
		},"json");
	}
}
	function greyBSIs() { $('.bsi').each(function() { $(this).addClass('INACTIVE'); }); }
	function colorBSIs() { $('.bsi').each(function() { $(this).removeClass('INACTIVE'); }); }
	
function enableBSIClick() {
	$('.bsi').click(function() {
		number=$(this).children('.bsinumber').text();
		$('#a_search').val("precise.bsi."+number)
		searchAdapters();
		$('#bar3_message').text('Adapters in Service Group '+$(this).attr('id')+'');
	});
}

function enableDetail() {
	$('.pa').click(function() {
		if ($('#advsearchpane').is(':visible')) { toggleDiv($('#advsearchpane')); }
		if ($('#inspectpane').is(':hidden')) { toggleDiv($('#inspectpane')); }
		loadThenShowIA($(this).attr('id'));
	});
}
	
/* DETAIL FUNCTIONS */
function loadThenShowIA(id) {
	$('#inspect_table').empty();
	adjustDetailHeader(id);
	$.post("include/engine.php?app=precise&action=detail", { internalid : id }, function(detail) {
		$.each (detail, function(i,p) {
			addDetailRow(p);
		});
	}, "json");
}

	function addDetailRow(p) {
		$('#inspect_table').append("<tr><td width='250'>"+p.name+"</td><td>"+p.value+"</td></tr>");
	}
	
	function adjustDetailHeader(id) {
		var fsn=$('.pa[id='+id+']').find('.pa_hd').text();
		var bsi=$('.pa[id='+id+']').find('.pa_data:first').text();
		$('#inspect_hd').text(fsn+" - "+bsi);
	}
	
function toggleDiv(o) { o.toggle(); }

/* MISCELLANEOUS FUNCTIONS */
function updateCount() {
	$('#a_count').text($('.pa:visible').length);
}

function resetAdvSearch() {
	$('#advsearch_valselect').hide();
	$('#advsearch_input').val('').show();
	$('#advsearch_field option:eq(0)').attr('selected',true);
	$('#advsearch_operator option:eq(0)').attr('selected',true);
}