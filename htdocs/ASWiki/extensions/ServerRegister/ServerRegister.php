<?php
/**
 * 

 */
if (! defined('MEDIAWIKI')) {
	echo 'This file is an extension to the MediaWiki software and cannot be used standalone.';
	die;
}

$wgServerRegisterExtensionVersion = '1.0';

$wgExtensionCredits['parserhook'][]  = array(
	'name'          => 'ServerRegister',
	'author'        => 'Han Gu',
	
	'description'   => 'Server Registration System',
	'url'           => 'http://',
	'description'   => 'Adds <servers /> parser function for viewing and adding issues',
	'version'       => $wgServerRegisterExtensionVersion
);
$wgExtensionCredits['specialpage'][] = array(
	'name'          => 'ServerRegister',
	'author'        => 'hgu',
	'email'         => 'hgu@ise.com',
	'description'   => 'Server Register System',
	'url'           => 'http://',
	'description'   => 'Adds a special page for managing server',
	'version'       => $wgServerRegisterExtensionVersion
);

$dir = dirname(__FILE__) . '/';

// Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['ServerRegister'] = $dir . 'ServerRegister.i18n.php';

// Autoload the ServerRegister class
$wgAutoloadClasses['ServerRegister'] = $dir . 'ServerRegister.body.php'; 

// Let MediaWiki know about your new special page.
$wgSpecialPages['ServerRegister'] = 'ServerRegister'; 

// Add Extension Functions
$wgExtensionFunctions[] = 'wfServerRegisterSetParserHook';

// Add any aliases for the special page
$wgHooks['LanguageGetSpecialPageAliases'][] = 'wfServerRegisterLocalizedTitle';
$wgHooks['ParserAfterTidy'][] = 'wfServerRegisterDecodeOutput';

/**
 * A hook to register an alias for the special page
 * @return bool
 */
function wfServerRegisterLocalizedTitle(&$specialPageArray, $code = 'en') 
{
	// The localized title of the special page is among the messages of the extension:
	wfLoadExtensionMessages('ServerRegister');
	  
	// Convert from title in text form to DBKey and put it into the alias array:
	$text = wfMsg('ServerRegister');
	$title = Title::newFromText($text);
	$specialPageArray['ServerRegister'][] = $title->getDBKey();
	
	return true;
}

/**
 * Register parser hook
 * @return void
 */
function wfServerRegisterSetParserHook() 
{
	global $wgParser;
	$wgParser->setHook('servers', array('ServerRegister', 'executeHook')); # <servers> is defined here
}

/**
 * Processes HTML comments with encoded content.
 * 
 * @param OutputPage $out Handle to an OutputPage object presumably $wgOut (passed by reference).
 * @param String $text Output text (passed by reference)
 * @return Boolean Always true to give other hooking methods a chance to run.
 */
function wfServerRegisterDecodeOutput(&$parser, &$text) 
{
    $text = preg_replace(
        '/@ENCODED@([0-9a-zA-Z\\+\\/]+=*)@ENCODED@/e',
        'base64_decode("$1")',
        $text
    );
    return true;
}
