<?php
/** @see ServerRegisterAction **/
require_once dirname(__FILE__) . '/ServerRegisterAction.php';

/**
 * ServerRegisterActionView class.

 */
class ServerRegisterActionList extends ServerRegisterAction
{	
	/**
	 * Executes the action.
	 *
	 * @return void 
	 */
	public function listAction()
	{
		// Mediawiki globals
		global $wgUser, $wgRequest;
		
		$this->_setDefaultVars();
		$this->_setHookPreferences();
		
		// Conditions
		$conds['project_name'] = addslashes($this->project);
		$conds['deleted'] = 0;
		
		// Filters

			if ($this->filterApplication !== null) {
				if (array_key_exists($this->filterApplication, $this->serverApplication)) {
					$conds['application'] = $this->filterApplication; 
				} 
			}
			
			if ($this->filterOS !== null) {
				if (array_key_exists($this->filterOS, $this->serverOS)) {
					$conds['os'] = $this->filterOS; 
				} 
			}			
			
			if ($this->filterProcessor !== null) {
				if (array_key_exists($this->filterProcessor, $this->serverProcessor)) {
					$conds['processor'] = $this->filterProcessor; 
				} 
			}
							
			if ($this->filterMemory !== null) {
				if (array_key_exists($this->filterMemory, $this->serverMemory)) {
					$conds['memory'] = $this->filterMemory; 
				} 
			}
							
			if ($this->filterNet !== null) {
				if (array_key_exists($this->filterNet, $this->serverNet)) {
					$conds['net'] = $this->filterNet; 
				} 
			}
							
			if ($this->filterCpp !== null) {
				if (array_key_exists($this->filterCpp, $this->serverCpp)) {
					$conds['cpp'] = $this->filterCpp; 
				} 
			}
			
			if ($this->filterStatus !== null) {
				if (array_key_exists($this->filterStatus, $this->serverStatus)) {
					$conds['status'] = $this->filterStatus; 
				} elseif ($this->filterStatus == 'archived') {
					$conds['deleted'] = 1; 
				}
			}

		
		$offset = $wgRequest->getInt('offset', 0);
		
		if ($this->searchNodeString !== null) {
			$this->servers = $this->getModel('default')->getServerNode($this->searchNodeString, $this->project, $offset);
		} 
		elseif ($this->searchIPString !== null) {
			$this->servers = $this->getModel('default')->getServerIP($this->searchIPString, $this->project, $offset);
		} 
		elseif ($this->searchServiceString !== null) {
			$this->servers = $this->getModel('default')->getServerService($this->searchServiceString, $this->project, $offset);
		} 	
		elseif ($this->searchDescriptionString !== null) {
			$this->servers = $this->getModel('default')->getServerDescription($this->searchDescriptionString, $this->project, $offset);
		} 
		elseif ($this->searchBackupNodeString !== null) {
			$this->servers = $this->getModel('default')->getServerBackupNode($this->searchBackupNodeString, $this->project, $offset);
		}
		elseif ($this->searchSerialString !== null) {
			$this->servers = $this->getModel('default')->getServerSerial($this->searchSerialString, $this->project, $offset);
		}  		
		else {
			$this->issues = $this->getModel('default')->getServers($conds, $offset);
		}
		$this->setOutput($this->render());
	}
	
	/**
	 * Sets the default vars.
	 *
	 * @return void 
	 */
	protected function _setDefaultVars()
	{
		// Mediawiki globals
		global $wgScript, $wgUser, $wgRequest;
		
		$this->action         = $this->getAction();
		$this->pageKey        = $this->getNamespace('dbKey');
		$this->project        = $this->getNamespace('dbKey');		
		$this->pageTitle      = $this->getNamespace('text');
		$this->formAction     = $wgScript;
		$this->url            = $wgScript . '?title=' . $this->pageKey . '&bt_action=';
		$this->viewUrl        = $this->url . 'view&bt_issueid=';
		$this->addUrl         = $this->url . 'add';
		$this->editUrl        = $this->url . 'edit&bt_issueid=';
		$this->deleteUrl      = $this->url . 'archive&bt_issueid=';
		$this->isLoggedIn     = $wgUser->isLoggedIn();
		$this->isAllowed      = $wgUser->isAllowed('protect');
		$this->hasDeletePerms = $this->hasPermission('delete');
		$this->hasEditPerms   = $this->hasPermission('edit');
		$this->hasViewPerms   = $this->hasPermission('view');
		$this->search         = true;
		$this->filter         = true;
		$this->auth           = true;
		
		
		$this->serverOS     = $this->_config->getServerOS();
		$this->serverApplication      = $this->_config->getServerApplication();
		$this->serverStatus    = $this->_config->getServerStatus();
		
		$this->serverProcessor     = $this->_config->getServerProcessor();
		$this->serverMemory     = $this->_config->getServerMemory();
		$this->serverNet   = $this->_config->getServerNet();
		$this->serverCpp    = $this->_config->getServerCpp();
		
		// Request vars
		#$this->filterBy       = $wgRequest->getVal('bt_filter_by');
		$this->filterApplication   = $wgRequest->getVal('bt_filter_application');   
		$this->filterStatus   = $wgRequest->getVal('bt_filter_status');
		$this->filterOS   = $wgRequest->getVal('bt_filter_os');
		
		$this->filterProcessor   = $wgRequest->getVal('bt_filter_processor');   
		$this->filterMemory   = $wgRequest->getVal('bt_filter_memory');
		$this->filterNet   = $wgRequest->getVal('bt_filter_net');
		$this->filterCpp   = $wgRequest->getVal('bt_filter_cpp');
		
		
		$this->searchNodeString   = $wgRequest->getVal('bt_search_node_string');
		$this->searchIPString   = $wgRequest->getVal('bt_search_ip_string');
		$this->searchServiceString   = $wgRequest->getVal('bt_search_service_string');
		$this->searchDescriptionString   = $wgRequest->getVal('bt_search_description_string');
		$this->searchBackupNodeString   = $wgRequest->getVal('bt_search_backupnode_string');
		$this->searchSerialString   = $wgRequest->getVal('bt_search_serial_string');
	}
	
	/**
	 * Processes the tag attributes.
	 *
	 * @return void 
	 */
	protected function _setHookPreferences()
	{
		if (array_key_exists('project', $this->_args) && $this->_args['project'] !== '') {
			$this->project = $this->_args['project'];
		}
		if (array_key_exists('search', $this->_args) && $this->_args['search'] == 'false') {
			$this->search = false;
		}
		if (array_key_exists('filter', $this->_args) && $this->_args['filter'] == 'false') {
			$this->filter = false;
		}
		if (array_key_exists('authenticate', $this->_args) && $this->_args['authenticate'] == 'false') {
			$this->auth = false;
		}
	}
}
?>