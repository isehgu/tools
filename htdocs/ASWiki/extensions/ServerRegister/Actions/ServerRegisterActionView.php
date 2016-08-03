<?php
/** @see ServerRegisterAction **/
require_once dirname(__FILE__) . '/ServerRegisterAction.php';

/**
 * ServerRegisterActionView class.
 */
class ServerRegisterActionView extends ServerRegisterAction
{	
	/**
	 * Executes the action.
	 *
	 * @return void 
	 */
	public function viewAction()
	{
		// Mediawiki globals
		global $wgOut;
		
		$this->_setDefaultVars();
		$this->_setHookPreferences();
		
		if ($this->serverId) {
			$rs = $this->getModel('default')->getServerById($this->serverId);
			$this->server = $rs->fetchObject();

			$output = $this->render();
		} else {
			$output = wfMsg('invalid_id'); 
		}
		
		$this->setOutput($output);
	}
	
	/**
	 * Sets the default vars.
	 *
	 * @return void 
	 */
	protected function _setDefaultVars()
	{
		// Mediawiki globals
		global $wgScript, $wgRequest;
		
		$this->action      = $this->getAction();
		$this->serverId     = $wgRequest->getText('bt_serverid');
		$this->pageKey     = $this->getNamespace('dbKey');
		$this->pageTitle   = $this->getNamespace('text');
		
		$this->applicationArray = $this->_config->getServerApplication();
		/***************************************/
		#$this->typeArray = $this->_config->getIssueType();
		$this->statusArray = $this->_config->getServerStatus();
		$this->osArray = $this->_config->getServerOS();
		$this->processorArray = $this->_config->getServerProcessor();
		$this->memoryArray = $this->_config->getServerMemory();
		$this->netArray = $this->_config->getServerNet();
		$this->cppArray = $this->_config->getServerCpp();
		$this->formAction = $wgScript;
		
		$this->url         = $wgScript . '?title=' . $this->pageKey . '&bt_action=';
		$this->editUrl     = $this->url . 'edit&bt_serverid=' . $this->serverId;
		$this->deleteUrl   = $this->url . 'archive&bt_serverid=' . $this->serverId;
		$this->listUrl     = $this->url . 'list';
		$this->isLoggedIn  = $this->isLoggedIn();
	}
	
	/**
	 * Processes the tag arguments.
	 *
	 * @return void 
	 */
	protected function _setHookPreferences()
	{
		if (array_key_exists('project', $this->_args) && $this->_args['project'] !== '') {
			$this->pageKey = $this->_args['project'];
		}
	}
}
?>