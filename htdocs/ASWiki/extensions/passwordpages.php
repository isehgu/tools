<?php
// This module needs Special Pages, and in some instances its not included
require_once( "$IP/includes/SpecialPage.php" );
 
// Password protection
@session_start();
$wgExtensionCredits['validextensionclass'][] = array(
       'name' => 'Password Protected Pages',
       'author' =>'Stinkfly', 
       'url' => 'http://www.stinkfly.co.uk', 
       'description' => 'This password protects pages, so only people with the password can access. (failsafe: check database)'
       );
$wgSpecialPages['Depassword'] = new SpecialPage("Depassword", '', true, "wfSpecialDepassword");
$wgExtensionFunctions[] = "wfPasswordProtect";
$pwdLang = array(
    'en' => array( 'depassword' => "Remove Password Protection",
		   'pwd-protect' => "Attempting to access password protected page",
		   'pwd-ask' => "<div style='border: 1px solid red; border-bottom-size: 3px; padding: 5px;'>This page is password protected! Any user requires an password for access. <form action=\"$1\" method=post>Password: <input type='password' name='password' /><input value='Unlock' type='submit' /></form></div>",
		   'pwd-ok' => "<div style='border: 1px solid red; border-bottom-size: 3px; padding: 5px;'>This page is password protected and you can view it for this session. Do not close your web browser otherwise you'll need to re-enter the password. <form action=\"$1\" method=get><input type='hidden' name='password' value='' /><input type='submit' name='locker' value='Lock' /></form></div>",
		   'depwd' => "<p>This removes password protection from pages. This means that you also have to remove the &lt;password&gt; field</p>",
		   'depwdtext' => "<p>Are you sure you wish to remove protection on $1?</p>",
		   'depwdnoprotection' => "<p><b>Cannot continue</b> An fatal error has occured: The page doesn't even have password protection</p>",
		   'removeprotection' => "Remove Protection",
		   'depwddone' => "<p>Password protect has been removed. You may need to force-refresh the page for changes to appear</p>"),
    'de' => array( 'depassword' => "Passwortschutz entfernen",
                   'pwd-protect' => "Es wird versucht auf eine passwortgesch&uuml;tzte Seite zu zu greifen",
                   'pwd-ask' => "<div style='border: 1px solid red; border-bottom-size: 3px; padding: 5px;'>Diese Seite ist passwortgesch&uuml;tzt! Jeder Benutzer ben&ouml;tigt ein Passwort für den Zugriff. <form action=\"$1\" method=post>Passwort: <input type='password' name='password' /><input value='Entsperren' type='submit' /></form></div>",
                   'pwd-ok' => "<div style='border: 1px solid red; border-bottom-size: 3px; padding: 5px;'>Diese Seite ist passwortgesch&uuml;tzt und Sie k&ouml;nnen die Seite für die Dauer der aktuellen Session sehen. Schlie&szlig;en Sie den Web-Browser nicht, sonst m&uuml;ssen Sie das Passwort erneut eingebend. <form action=\"$1\" method=get><input type='hidden' name='password' value='' /><input type='submit' name='locker' value='Sperren' /></form></div>",
                   'depwd' => "<p>Hier wird der Passwortschutz entfernt. Das bedeutet, dass Sie das &lt;password&gt;-Feld ebenfalls entfernen m&uuml;en.</p>",
                   'depwdtext' => "<p>Sind Sie sicher, dass die den Passwortschutz auf $1 entfernen m&ouml;chten?</p>",
                   'depwdnoprotection' => "<p><b>Fortsetzung nicht möglich</b> Fataler Fehler: Die Seite ist nicht passwortgesch&uuml;tzt</p>",
                   'removeprotection' => "Passwortschutz entfernen",
                   'depwddone' => "<p>Der Passwortschutz wurde entfernt. Sie m&uuml;ssen m&ouml;glicherweise das Aktualisieren der Seite erzwingen um die &Auml;nderungen zu sehen.</p>")
);
$wgHooks['OutputPageBeforeHTML'][] = 'wfPwdCheck';
$wgHooks['userCan'][] = "wfPwdUsercan";
 
function wfPasswordProtect(){
      global $wgMessageCache, $pwdLang, $wgParser;
      $wgParser->setHook( 'password', 'wfPasswordTag' );
      foreach ( $pwdLang as $lang => $langMessages ) {
	$wgMessageCache->addMessages( $langMessages, $lang );
      }
}
 
function wfPasswordTag( $input, $args, $parser ){
    // here we need to update our table to say that we've got password protection here
    global $wgDBprefix, $wgTitle;
    $db = wfGetDB( DB_MASTER );
//     $parser->disableCache();
    $db->query("DELETE FROM `{$wgDBprefix}protect_pages` WHERE pageid='".$wgTitle->getArticleID()."'");
    $db->query("INSERT INTO `{$wgDBprefix}protect_pages` (`pageid`,`password`) VALUES ('".$wgTitle->getArticleID()."','".$input."')");
    return "";
}
$one = false;
function wfPwdCheck( &$out, &$text ){
    global $wgTitle, $one;
    // here is the magic: we need to get it all right here or it won't work
    if(wfPwdIsProtected($wgTitle)){
	 // right, this page is now recoginsed as being protect, we need to now check to see if we're allowed in
	 if(isset($_POST['password']) && $_POST['password']){
	      $r = wfPwdChk($wgTitle, $_POST['password']);
	      if($r == true){
		$_SESSION['pwd-' . $wgTitle->getText()] = "yoyo";
	      }
	 }
	 if(isset($_GET['locker']) && $_GET['locker'])
	      unset($_SESSION['pwd-' . $wgTitle->getText()]);
	 if($_SESSION['pwd-' . $wgTitle->getText()] != "yoyo"){
	      // show form
	      $out->setPageTitle(wfMsg("pwd-protect"));
	      if($one == false){ $one = true;
	      $text = wfMsg("pwd-ask", array(1 => $wgTitle->getLocalUrl()));
	      }
	 }
	 else{
	      $text = wfMsg("pwd-ok", array(1 => $wgTitle->getLocalUrl())) . $text;
	 }
    }
      return true;
}
 
function wfPwdUsercan( $title, $user, $action, &$result ){
    global $wgOut, $one;
    $wgTitle = $title;
    // here is the magic: we need to get it all right here or it won't work
    if(wfPwdIsProtected($wgTitle) and $action == "edit" or $action == "viewsource"){
	 // right, this page is now recoginsed as being protect, we need to now check to see if we're allowed in
	 if(isset($_POST['password']) && $_POST['password']){
	      $r = wfPwdChk($wgTitle, $_POST['password']);
	      if($r == true){
		$_SESSION['pwd-' . $wgTitle->getText()] = "yoyo";
	      }
	 }
	 if(isset($_GET['locker']) && $_GET['locker'])
	      unset($_SESSION['pwd-' . $wgTitle->getText()]);
	 if($_SESSION['pwd-' . $wgTitle->getText()] != "yoyo"){
	      // show form
	      $wgOut->setPageTitle(wfMsg("pwd-protect"));
	     // if($one != true){ $one = true;
	     // $wgOut->addHtml(wfMsg("pwd-ask", array(1 => $wgTitle->getLocalUrl())));
	     //}
	      $result = false;return $result;
	 }
    }
    $result = true;
    return true;
}
 
function wfPwdChk($title, $password){
     // let's do some checks: is $title password == $password
     global $wgDBprefix;
     if($password == "")
	return false;
     $db = wfGetDB( DB_MASTER );
     $result = $db->query("SELECT * FROM {$wgDBprefix}protect_pages WHERE pageid='".$title->getArticleID()."'");
     while ( $row = $db->fetchObject( $result ) ) {
        $myrow = $row;
    }
    if($myrow->password == $password)
      return true;
    else
      return false;
}
 
function wfPwdIsProtected($title){
     // let's do some checks: is $title password protected or not?
     global $wgDBprefix;
     $db = wfGetDB( DB_MASTER );
     $result = $db->query("SELECT * FROM {$wgDBprefix}protect_pages WHERE pageid='".$title->getArticleID()."'");
 
     while ( $row = $db->fetchObject( $result ) ) {
        // return as, we've found our row
	return true;
    }
    return false;
}
 
function wfSpecialDepassword($Par){
      // here we need to check to see if $wgUser is the creator of $Par
      global $wgUser, $wgOut, $wgTitle, $wgDBprefix;
      $title = Title::newFromText($Par);
      $article = new Article($title);
      $user = $article->getUser();
      $wgOut->addHtml(wfMsg("depwd"));
      if(!wfPwdIsProtected($title)){
	$wgOut->addHtml(wfMsg("depwdnoprotection"));
	return;
      }
      if($wgUser->getId() == $user or wfIsSysop($wgUser)){
	if(isset($_POST['iamsure']) && $_POST['iamsure']){
	   $db = wfGetDB( DB_MASTER );
	   $db->query("DELETE FROM `{$wgDBprefix}protect_pages` WHERE pageid='".$title->getArticleID()."'");
	   $wgOut->addHtml(wfMsg("depwddone"));
	   return;
	}
	// This is when we ask the user if he/she really wants to remove the password (admins can do this too)
	$wgOut->addHtml(wfMsg("depwdtext", array(1 => $title->getText())));
	$wgOut->addHtml("<form action='' method=post><input type='submit' value='".wfMsg('removeprotection')."' name='iamsure' /></form>");
      }
}
 
function wfIsSysop($user){
    $groups = $user->getGroups();
    if(array_search('sysop', $groups))
      return true;
    else
      return false;
}
?>
