<?php
 
# MediaWiki Tasks version 0.5.4
# Copyright (c) 2005 Aran Clary Deltac
# http://arandeltac.com/MediaWiki_Tasks
# Copyright (c) 2006 Sylvain Machefert, mods by mhc, Filo, James,
# Guillaume Pratte, Dangerville
# http://www.mediawiki.org/wiki/Extension:Tasks_Extension
# Distributed under that same terms as MediaWiki itself.
 
$wgExtensionFunctions[] = "wfTasksExtension";
$wgHooks['ArticleSave'][] = 'clearTasks';
$wgHooks['ArticleSaveComplete'][] = 'saveTasks';
global $tasks_buffer;
 
#-----------------------------------------------#
# Purpose   : Declare parser extensions.
function wfTasksExtension() {
	global $wgParser;
	$wgParser->setHook( "tasks", "tasksHook" );
}
 
#-----------------------------------------------#
# Purpose   : Display a list of tasks.
# Parameters: content, A list of tasks, one per line.
#             args, An array of arguments.
# Arguments : hidden, All tasks will be hidden in the HTML.
# Returns   : Tasks HTML.
function tasksHook( $content, $args = null, &$parser) {
	global $tasks_buffer;
	clearTasks();
	addToTaskBuffer($content);
 
	$hidden = '';
	if (isset($args['hidden'])) {
		$hidden = 'y';
	}
	else {
		$hidden = 'n';
	}
 
	$output = '';
#if you want tasks create a new section...
#	$parserOutput = $parser->parse('== [[Special:Tasks|Tasks]] ==', $parser->mTitle, $parser->mOptions, false, false);
#	$output .= $parserOutput->getText();
	foreach ($tasks_buffer as $task) {
		$task['hidden'] = $hidden;
		$output .= formatTask(
					$task['status'],
					$task['summary'],
					$task['owner'],
					'',
					$parser);
	}
	return $output;
}
 
function addToTaskBuffer($content) {
	global $tasks_buffer;
	$tasks = preg_split("/[\n\r]+/", $content);
	foreach ($tasks as $task) {
		if (preg_match('/^\s*\[([123x! ])\]\s*(.*)$/',$task,$matches)) {
			$status = $matches[1];
			$summary = $matches[2];
 
			$owner = '';
                        # the regexp updated by SebM (sebm(at)seren.com.pl) 22 March 2007    
                        # to allow parentheses ( ) in task description                                   
			if (preg_match('/^(.+?)\s*\(([^()]+)\)$/',$summary,$matches)) {
				$summary = $matches[1];
				$owner = $matches[2];
			}
 
			$tasks_buffer[] = array(
				'summary' => $summary,
				'status' => $status,
				'owner' => $owner,
				'hidden' => 'n'
			);
		}
	}
}
 
#-----------------------------------------------#
# Purpose   : HTML format a task.
function formatTask( $status, $summary, $owner='', $page='', &$parser ) {
	global $wgScriptPath, $wgTitle;
	$imgTitle = 'Regular task'; $alt = '[ ]';
	$img = '<img src="'.$wgScriptPath.'/images/task';
	switch ($status) {
		case 'x': $img .= '_done'; $alt = '[x]'; $imgTitle = 'Closed task'; break;
		case '!': $img .= '_alert'; $alt = '[!]'; $imgTitle = 'Urgent task'; break;
		case '1': $img .= '_1'; $alt = '[1]'; $imgTitle = 'High priority task'; break;
		case '2': $img .= '_2'; $alt = '[2]'; $imgTitle = 'Medium priority task'; break;
		case '3': $img .= '_3'; $alt = '[3]'; $imgTitle = 'Low priority task'; break;
	}
	$img .= '.png" width="13" height="13" alt="'.$alt.'" title="'.$imgTitle.'" /> ';
 
	if ($owner) { $summary .= ' ([[Special:Tasks/owner='.$owner.'|'.$owner.']])'; }
	if ($page) { $summary = "[[:$page]]: ".$summary; }
 
	$parserOutput = $parser->parse($summary, $parser->mTitle, $parser->mOptions, false, false);
	return $img . ' ' . $parserOutput->getText() . '<br />';
}
 
#-----------------------------------------------#
# Purpose   : Used before saveing a page to clear the tasks buffer.
function clearTasks() {    
	global $tasks_buffer;
	$tasks_buffer = array();
	return 1;
}
 
#-----------------------------------------------#
# Purpose   : Used after a page is saved to first delete
#             all tasks and then save the new ones created
#             in the tasks buffer.
# Parameters: article, The article object.
# rev: 2006-03-12: <calendar> extension will cause double-logging of
# tasks rendered on a sub-page.  "Rebuild" section prevents this.
function saveTasks( $article, $user, $text ) {
	global $tasks_buffer;
	$page_id = $article->getID();
	$dbr =& wfGetDB( DB_MASTER );
	# Delete all tasks for this page.
	$dbr->delete(
		'tasks',
		array( 'page_id' => $page_id )
	);
 
	# Rebuild the $tasks_buffer array (in case we're on a <calendar> page)
	clearTasks();
	$matches = array();
	$elements[] = 'tasks';
 
	$text = Parser::extractTagsAndParams( $elements, $text, $matches );
	foreach( $matches as $marker => $data ) {
		list( $element, $content, $params, $tag ) = $data;
		addToTaskBuffer($content);
	}
 
	# Re-insert all tasks that were created when parseing this page.
	foreach ($tasks_buffer as $task) {
		$task['page_id'] = $page_id;
		$dbr->insert(
				'tasks',
				$task
		);
	}
	return 1;
}
?>
