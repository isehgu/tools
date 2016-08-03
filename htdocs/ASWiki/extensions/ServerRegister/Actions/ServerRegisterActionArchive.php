<?php
/** @see ServerRegisterAction **/
require_once dirname(__FILE__) . '/ServerRegisterAction.php';

/**
 * ServerRegisterActionArchive class.
 */
class ServerRegisterActionArchive extends ServerRegisterAction
{
	/**
	 * Initialize class.
	 * 
	 * @return bool
	 */
	public function init()
	{
		return $this->isLoggedIn();
	}
	
	/**
	 * Executes the action.
	 *
	 * @return void 
	 */
	public function archiveAction()
	{
		global $wgUser, $wgScript, $wgRequest;
		
		$listUrl = $wgScript . '?title=' . $this->getNamespace('dbKey') . '&bt_action=list';
		
		$userId = $wgUser->getID();
		$userName = $wgUser->getName();
		
		$serverId = $wgRequest->getText('bt_serverid');
		$this->getModel('default')->archiveIssue($serverId);
		
		header('Location: ' . $listUrl);
	}
}
?>