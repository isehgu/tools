$(document).ready(function() { initialize(); });

function initialize() {
	enableSearch();
	enableBSIClick();
	updateCount();
	enableDetail();
	enableClose();
	enableListView();
}

function enableListView() {
	$('#list_submit').click(function() { // one day, include the search string as well
		$.post("include/engine.php?app=iors&action=listview", {ids:gatherActiveIds().join(','), searchtitle:$('#bar3_message').text()}, function(o) {
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
			$.post("include/engine.php?app=iors&action=advsearchsselect", { field:fieldselect.text() }, function(opt) {
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
			$.post("include/engine.php?app=iors&action=customruleselect", { }, function(opt) {
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
			$.post("include/engine.php?app=iors&action=advsearchsselect", { field:fieldselect.text() }, function(opt) {
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
	$('.ia').hide();
	$(".ia_data:contains('"+txt+"')").parent().parent().show(); // this is not the best way to search...add JSON
	$(".ia_hd:contains('"+txt+"')").parent().show();
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
	$.post("include/engine.php?app=iors&action=advsearch", { field:curfield, operator:curoperator, fieldval:curfieldval }, function(a) {
		$('.ia').hide();
		$.each (a, function(i,p) {
			$('#'+p.id+'').show();
		});
		updateBSIDisplay();
		updateCount();
		if (curfield=="autoPrefBin" || curfield=="autoPrefSpreadBin") {
			curfieldval = (curfieldval=="") ? "" : "to "+curfieldval+"";
			$('#bar3_message').text('Adapters Configured with '+curfield+' Preferencing '+curfieldval+'');
		} else if (curfield=="cc_customrules") {
			$('#bar3_message').text('Adapters Configured with the "'+curfieldval+'" Custom Rule');
		} else { $('#bar3_message').text('Adapters Where '+curfield+' '+curoperator+' "'+curfieldval+'"'); }
		cycleSearchFeedback(3);
	}, "json");
}

function clearSearch() {
	$('#a_search').val("");
	$('.ia').show();
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
	$('.ia:visible').each(function() {
		activeids.push($(this).attr('id'));
	});
	return activeids;
}

function updateBSIDisplay() {
	var aids=gatherActiveIds().join(',');
	if (aids=="") greyBSIs();
	else {
		$.post("include/engine.php?app=iors&action=distinctbsi", {ids:aids}, function(b) {
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
		type=$(this).children('.bsitype').text();
		number=$(this).children('.bsinumber').text();
		$('#a_search').val(type+"-MAT-"+number)
		searchAdapters();
		$('#bar3_message').text('Adapters in Service Group '+$(this).attr('id')+'');
	});
}

function enableDetail() {
	$('.ia').click(function() {
		if ($('#advsearchpane').is(':visible')) { toggleDiv($('#advsearchpane')); }
		if ($('#inspectpane').is(':hidden')) { toggleDiv($('#inspectpane')); }
		loadThenShowIA($(this).attr('id'));
	});
}
	
/* DETAIL FUNCTIONS */
function loadThenShowIA(id) {
	$('#inspect_top_table').empty();
	$('#inspect_bottom_table').empty();
	adjustDetailHeader(id);
	$.post("include/engine.php?app=iors&action=detail", { internalid : id }, function(detail) {
		$.each (detail, function(i,p) {
			addDetailRow(p);
		});
		bindCustomRuleAction();
	}, "json");
}

	function addDetailRow(p) {
		var topside = p.name.search(/_/);
		if (topside>=0) {
			if (p.name=="cc_customrules") { p.value=prepareCustomRuleLinks(p.value); }
			$('#inspect_top_table').append("<tr><td width='250'>"+p.name+"</td><td>"+p.value+"</td></tr>");
		} else {
			$('#inspect_bottom_table').append("<tr><td width='250'>"+p.name+"</td><td>"+p.value+"</td></tr>");
		}
	}
	
	function adjustDetailHeader(id) {
		var fsn=$('.ia[id='+id+']').find('.ia_hd').text();
		var bsi=$('.ia[id='+id+']').find('.ia_data:first').text();
		$('#inspect_hd').text(fsn+" - "+bsi);
	}
	
function toggleDiv(o) { o.toggle(); }

/* CUSTOM RULE FUNCTIONS */
function prepareCustomRuleLinks(str) {
	linkstr="";
	rules=str.split(',');
	for (i=0;i<rules.length;i++) {
		linkstr=linkstr+"<a class='customrule'>"+rules[i]+"</a> ";
	}
	return linkstr;
}

function bindCustomRuleAction() {
	$('a.customrule').click(function() {
		populateCustomRulePane($(this).text());
		if ($('#advsearchpane').is(':visible')) { toggleDiv($('#advsearchpane')); }
		if ($('#customrulepane').is(':hidden')) { toggleDiv($('#customrulepane')); }
	});
}

function populateCustomRulePane(rule) {
	$('#customrule_table').empty();
	$('#customrule_hd').text(rule);
	$.post("include/engine.php?app=iors&action=getrule", { rulename : rule }, function(r) {
		$.each (r, function(i,p) {
			$('#customrule_table').append("<tr><td align='right'><strong>"+p.point+":</strong></td><td class='cellval'>"+p.value+"</td></tr>");
		});
	}, "json");
}

/* MISCELLANEOUS FUNCTIONS */
function updateCount() {
	$('#a_count').text($('.ia:visible').length);
}

function resetAdvSearch() {
	$('#advsearch_valselect').hide();
	$('#advsearch_input').val('').show();
	$('#advsearch_field option:eq(0)').attr('selected',true);
	$('#advsearch_operator option:eq(0)').attr('selected',true);
}