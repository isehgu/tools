<?php
if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

require_once('includes/SkinTemplate.php');

class SkinAndys extends SkinTemplate {
	/** Using Andys. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'Andys';
		$this->stylename = 'Andys';
		$this->template  = 'AndysTemplate';
	}
}

class AndysTemplate extends QuickTemplate {

	function execute() {

		wfSuppressWarnings();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $this->text('lang') ?>" lang="<?php $this->text('lang') ?>" dir="<?php $this->text('dir') ?>">
<head>




<!--making the favorites box -->

<script>

// basic get and set functions for javascript cookies

function setCookie (cookieName, cookieValue, expires, path, domain, secure) {
document.cookie = 
escape(cookieName) + '=' + escape(cookieValue) 
+ (expires ? '; EXPIRES=' + expires.toGMTString() : '')
+ (path ? '; PATH=' + path : '')
+ (domain ? '; DOMAIN=' + domain : '')
+ (secure ? '; SECURE' : '');
}

function getCookie (cookieName) {
var cookieValue = null;
var posName = document.cookie.indexOf(escape(cookieName) + '=');
if (posName != -1) {
var posValue = posName + (escape(cookieName) + '=').length;
var endPos = document.cookie.indexOf(';', posValue);
if (endPos != -1) {
cookieValue = unescape(document.cookie.substring(posValue, endPos));
} else {
cookieValue = unescape(document.cookie.substring(posValue));
}
}
return cookieValue;
}

// custom code to store and remove links from the cookies

function storePage(){
thisPage = document.location.href;
thisPageTitle = "<?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?>";
var pageList = getCookie("pageList");
var pageListTitle = getCookie("pageListTitle");



// check to see whether this url is already in the cookie's links list

var linkFound = false;
if (pageList != "" && pageList != null) {
pSplit = pageList.split(";");
for (a=0;a<pSplit.length;a++) {
}
}

// if url not already in cookie, then add it here
if (!linkFound) {
if (pageList == null || pageList == ";") {
pageList = thisPage;
pageListTitle = thisPageTitle;
} else {
pageList += ";" + thisPage;
pageListTitle += ";" + thisPageTitle;
}
}


// make the cookie expire in 1 years time:

var now = new Date();
var nextYear = new Date(now.getTime() + 1000 * 60 * 60 * 24 * 365);
setCookie("pageList",pageList,nextYear);
setCookie("pageListTitle",pageListTitle,nextYear);

//refresh screen after link has been added

window.location.reload();
}



function removePage(url){
var pageListTitle = getCookie("pageListTitle");
var linkList = ""


// add each link to the linksList string and skip the one you want to remove
if (pageListTitle != "" && pageListTitle != null) {
pSplitTitle = pageListTitle.split(";");
for (a=0;a<pSplitTitle.length;a++) {
if (pSplitTitle[a] != url && pSplitTitle[a] != '') linkList += ";" + pSplitTitle[a];
}
}


// get the cookie expiry date
var now = new Date();
var nextYear = new Date(now.getTime() + 1000 * 60 * 60 * 24 * 365);
setCookie("pageList",linkList,nextYear);
setCookie("pageListTitle",linkList,nextYear);


// refresh screen after link has been removed
window.location.reload();
}


// code to write the list of urls to the page, and extra link to remove the url from the list.
function writeLinks(){
var pageList = getCookie("pageList")
var pageListTitle = getCookie("pageListTitle")



if (pageList != "" && pageList != null) {
pSplit = pageList.split(";")
for (a=0;a<pSplit.length;a++) {
if (pSplit[a] != '' && pSplit[a] != 'null') {



if (pageListTitle != "" && pageListTitle != null) {
pSplitTitle = pageListTitle.split(";")
for (a=0;a<pSplitTitle.length;a++) {
if (pSplitTitle[a] != '' && pSplitTitle[a] != 'null') {
document.write('<li id="t-whatlinkshere"><a href="' + pSplit[a] + '">' + pSplitTitle[a] + '</a> <a href="javascript:removePage(\'' + pSplitTitle[a] + '\')"><font color="red">[-]</font></a><br></li>');
}
}
}
}
}
}
else {
document.write("<li>You have no favorites.");
}
}
</script>




<script language="JavaScript" src="/w/skins/js/includes.js"></script>
<script language="JavaScript" src="/w/skins/js/MyNav.php?title=Main_Page&dbg="></script>
<script language="JavaScript" src="/w/skins/js/commonNav.php"></script>
	<script type="text/javascript" src="/skins/Andys/ajax/dbx.js"></script>
	<script type="text/javascript" src="/skins/Andys/ajax/dbx-key.js"></script>
	<link rel="stylesheet" type="text/css" href="/skins/Andys/ajax/dbx.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/skins/Andys/ajax/etc.css" media="screen, projection" />

		<meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
		<?php $this->html('headlinks') ?>
		<title><?php $this->text('pagetitle') ?></title>
		<style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/main.css?9"; /*]]>*/</style>
		<link rel="stylesheet" type="text/css" <?php if(empty($this->data['printable']) ) { ?>media="print"<?php } ?> href="<?php $this->text('stylepath') ?>/common/commonPrint.css" />
		<!--[if lt IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE50Fixes.css";</style><![endif]-->
		<!--[if IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE55Fixes.css";</style><![endif]-->
		<!--[if IE 6]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE60Fixes.css";</style><![endif]-->
		<!--[if IE 7]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE70Fixes.css?1";</style><![endif]-->
		<!--[if lt IE 7]><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath') ?>/common/IEFixes.js"></script>
		<meta http-equiv="imagetoolbar" content="no" /><![endif]-->
		
		<?php print Skin::makeGlobalVariablesScript( $this->data ); ?>
                
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath' ) ?>/common/wikibits.js?1"><!-- wikibits js --></script>
<?php	if($this->data['jsvarurl'  ]) { ?>
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('jsvarurl'  ) ?>"><!-- site js --></script>
<?php	} ?>
<?php	if($this->data['pagecss'   ]) { ?>
		<style type="text/css"><?php $this->html('pagecss'   ) ?></style>
<?php	}
		if($this->data['usercss'   ]) { ?>
		<style type="text/css"><?php $this->html('usercss'   ) ?></style>
<?php	}
		if($this->data['userjs'    ]) { ?>
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('userjs' ) ?>"></script>
<?php	}
		if($this->data['userjsprev']) { ?>
		<script type="<?php $this->text('jsmimetype') ?>"><?php $this->html('userjsprev') ?></script>
<?php	}
		if($this->data['trackbackhtml']) print $this->data['trackbackhtml']; ?>
		<!-- Head Scripts -->
		<?php $this->html('headscripts') ?>


</head>
<body <?php if($this->data['body_ondblclick']) { ?>ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload'    ]) { ?>onload="<?php     $this->text('body_onload')     ?>"<?php } ?>
 class="mediawiki <?php $this->text('nsclass') ?> <?php $this->text('dir') ?>">

<!-- HEADER HERE-->
	<div id="globalWrapper">
	<div class="portlet" id="p-personal">
		<h5><?php $this->msg('personaltools') ?></h5>
		<div class="pBody">
			<ul>
<?php 			foreach($this->data['personal_urls'] as $key => $item) { ?>
				<li id="pt-<?php echo htmlspecialchars($key) ?>"<?php
					if ($item['active']) { ?> class="active"<?php } ?>><a href="<?php
				echo htmlspecialchars($item['href']) ?>"<?php
				if(!empty($item['class'])) { ?> class="<?php
				echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
				echo htmlspecialchars($item['text']) ?></a></li>
<?php			} ?>
			</ul>
		</div>
	</div>

<table width='100%'>

<td width='160' valign="top">
	<div class="portlet" id="p-logo"><center>
		<a style="background-image: url();" <?php
			?>href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>" <?php
			?>title="<?php $this->msg('mainpage') ?>"><img src="/skins/Andys/logo.png"></a>
	</div>
		</center>
<div id="column-one">
		
<!-- <div class='container1'>	-->	
	
	
	<div class="container13" id="p-tb">
		<div class="dbx-group" id="purple">


		<div id="p-search" div class="dbx-box">

		<div class="dbx-handle"><h5><?php $this->msg('search') ?></h5></div>
		<div id="searchBody" class="dbx-content">
			<form action="<?php $this->text('searchaction') ?>" id="searchform"><div>
				<input id="searchInput" name="search" type="text" <?php
					if($this->haveMsg('accesskey-search')) {
						?>accesskey="<?php $this->msg('accesskey-search') ?>"<?php }
					if( isset( $this->data['search'] ) ) {
						?> value="<?php $this->text('search') ?>"<?php } ?> />
				<input type='submit' name="go" class="searchButton" id="searchGoButton"	value="<?php $this->msg('searcharticle') ?>" />&nbsp;
				<input type='submit' name="fulltext" class="searchButton" value="<?php $this->msg('searchbutton') ?>" />
			</div></form>
		</div>
	</div>

	<script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script>
	<?php foreach ($this->data['sidebar'] as $bar => $cont) { ?>
	<div class='dbx-box' id="p-tb">  <!-- WAS id='p-<?php echo htmlspecialchars($bar) ?>'  -->
		<div class="dbx-handle"><h5><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></h5></div>
		<div class='dbx-content'>
			<ul>
<?php 			foreach($cont as $key => $val) { ?>
				<li id="<?php echo htmlspecialchars($val['id']) ?>"<?php
					if ( $val['active'] ) { ?> class="active" <?php }
				?>><a href="<?php echo htmlspecialchars($val['href']) ?>"><?php echo htmlspecialchars($val['text']) ?></a></li>
<?php			} ?>
			</ul>
		</div>
	</div>
	<?php } ?>

<div class="dbx-box" id="p-tb">
		<div class="dbx-handle"><h5><?php $this->msg('toolbox') ?></h5></div>
		<div class="dbx-content">
			<ul>
<?php
		if($this->data['notspecialpage']) { ?>
				<li id="t-whatlinkshere"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href'])
				?>"><?php $this->msg('whatlinkshere') ?></a></li>
<?php
			if( $this->data['nav_urls']['recentchangeslinked'] ) { ?>
				<li id="t-recentchangeslinked"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href'])
				?>"><?php $this->msg('recentchangeslinked') ?></a></li>
<?php 		}
		}
		if(isset($this->data['nav_urls']['trackbacklink'])) { ?>
			<li id="t-trackbacklink"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href'])
				?>"><?php $this->msg('trackbacklink') ?></a></li>
<?php 	}
		if($this->data['feeds']) { ?>
			<li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) {
					?><span id="feed-<?php echo htmlspecialchars($key) ?>"><a href="<?php
					echo htmlspecialchars($feed['href']) ?>"><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;</span>
					<?php } ?></li><?php
		}

		foreach( array('contributions', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) {

			if($this->data['nav_urls'][$special]) {
				?><li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
				?>"><?php $this->msg($special) ?></a></li>
<?php		}
		}

		if(!empty($this->data['nav_urls']['print']['href'])) { ?>
				<li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href'])
				?>" rel="nofollow"><?php $this->msg('printableversion') ?></a></li><?php
		}

		if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
				<li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
				?>"><?php $this->msg('permalink') ?></a></li><?php
		} elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
				<li id="t-ispermalink"><?php $this->msg('permalink') ?></li><?php
		}

		wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) ); 
?></li>
			

</center>
</div></div>

<div class="dbx-box" id="p-tb">
<div class="dbx-handle"><h5><label for="searchInput"><font color="#0145DB">G</font><font color="#C61A02">o</font><font color="#F2C102">o</font><font color="#0145DB">g</font><font color="#08940F">l</font><font color="#C61A02">e</font> Search</label></h5></div>
<div class="dbx-content">
<br>
<form name="searchform"
action="http://www.google.com/search" target="blank_">
<input name="ie" value="UTF-8" type="hidden"/>
<input name="oe" value="UTF-8" type="hidden"/>
<input id="searchInput" name="q" type="text" value="Search..." onfocus="if
(this.value==this.defaultValue) this.value='';"/></div>
</form>

</div>

<div class="dbx-box" id="p-tb">

		<div class="dbx-handle"><h5>My Favorites  (<span title="fixedabsx=[3] requireclick=[on] header=[<img src='/skins/Andys/info.gif' style='vertical-align:middle'>&nbsp;&nbsp;Information] body=[This list is stored as a local cookie.  If you erase your cookies, this list will disappear.]" 
style="cursor:pointer">?</span>)<script src="/skins/Andys/boxover.js"></script></h5></div>
		<div class="dbx-content">
			<ul>

				<div><script>writeLinks();</script></div>
               </ul>
		</div>

</div>


	<!-- end of the left (by default at least) column -->
</td>
<td valign="top"><br>
		<div id="column-content">
	<div id="p-cactions" class="portlet">
		<h5><?php $this->msg('views') ?></h5>
		<ul>
<?php			foreach($this->data['content_actions'] as $key => $tab) { ?>
				 <li id="ca-<?php echo htmlspecialchars($key) ?>"<?php
				 	if($tab['class']) { ?> class="<?php echo htmlspecialchars($tab['class']) ?>"<?php }
				 ?>><a href="<?php echo htmlspecialchars($tab['href']) ?>"><?php
				 echo htmlspecialchars($tab['text']) ?></a></li>
<?php			 } ?>
		</ul>
	</div><br>
	<div id="content">
		<a name="top" id="top"></a>
		<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
		<h1 class="firstHeading"><?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?></h1><a href="javascript:storePage()">Add to favorites</a>
<center>
<!-- <font color="red"><u><b>Important Notice could be placed here.</u></b></font> -->

</center>
		<div id="bodyContent">
			<h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
			<div id="contentSub"><?php $this->html('subtitle') ?></div>
			<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
			<?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
			<?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
			<!-- start content -->
			<?php $this->html('bodytext') ?>
			<?php if($this->data['catlinks']) { ?><div id="catlinks"><?php       $this->html('catlinks') ?></div><?php } ?>
			<!-- end content -->
			<div class="visualClear"></div>
		</div>
	</div>
		</div>
</td>



</table>		
			<div class="visualClear"></div>
			<div id="footer">
<?php
		if($this->data['poweredbyico']) { ?>
				<div id="f-poweredbyico"><?php $this->html('poweredbyico') ?></div>
<?php 	}
		if($this->data['copyrightico']) { ?>
				<div id="f-copyrightico"><?php $this->html('copyrightico') ?></div>
<?php	}

		// Generate additional footer links
?>
			<ul id="f-list">Skin created by <a href="http://cookandy.com/downloads/mediawiki/">Andy Cook</a>.
<?php
		$footerlinks = array(
			'lastmod', 'viewcount', 'numberofwatchingusers', 'credits', 'copyright',
			'privacy', 'about', 'disclaimer', 'tagline',
		); 
		foreach( $footerlinks as $aLink ) {
			if( $this->data[$aLink] ) {
?>				<li id="<?php echo$aLink?>"><?php $this->html($aLink) ?></li>
<?php 		}
		}
?>
			</ul>
		</div>
		
	<?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>
</div>
<?php $this->html('reporttime') ?>
<?php if ( $this->data['debug'] ): ?>
<!-- Debug output:
<?php $this->text( 'debug' ); ?>

-->
<?php endif; ?>

</body></html>
<?php
	wfRestoreWarnings();
	} // end of execute() method
} // end of class
?>
