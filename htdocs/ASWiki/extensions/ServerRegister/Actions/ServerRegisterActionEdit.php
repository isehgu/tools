<?php
/** @see ServerRegisterActionAdd **/
require_once dirname(__FILE__) . '/ServerRegisterActionAdd.php';

/**
 * ServerRegisterActionEdit class.
  */
  
class ServerRegisterActionEdit extends ServerRegisterActionAdd
{	
	/**
	 * Executes the edit action.
	 *
	 * @return void 
	 */
	public function editAction()
	{
		global $wgUser;
		
		$this->_setDefaultVars();
		$this->_setHookPreferences();
		
		if (isset($_POST['bt_submit']) && $this->serverId !== 0) {
			$errorMessages = $this->_getErrors($this->_requiredFields);	
			if (count($errorMessages) == 0) {
				$userId = $wgUser->getID();
				$userName = $wgUser->getName();
				$result = $this->getModel('default')->updateServer($this->serverId, $_POST);
				header('Location: ' . $this->listUrl);
			} else {
				$this->errors = implode('<br />', $errorMessages);
			}
		} elseif ($this->serverId !== 0) {
			$rs = $this->getModel('default')->getServerById($this->serverId);
			$row = $rs->fetchObject();
			
			$_POST = array(
				'bt_serverid'  => $this->serverId,
				'bt_node'    => $row->node,
				'bt_ip'    => $row->ip,
				'bt_status'    => $row->status,
				'bt_application'    => $row->application,
				'bt_service'    => $row->service,
				'bt_description'    => $row->description,
				'bt_backupnode'    => $row->backup_node,
				'bt_os'    => $row->os,
				'bt_processor'    => $row->processor,
				'bt_memory'    => $row->memory,
				'bt_net'    => $row->net,
				'bt_cpp'    => $row->cpp,
				'bt_serial'    => $row->serial,				
				
			);
		} else {
			header('Location: ' . $this->listUrl);
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
		global $wgRequest;
		
		parent::_setDefaultVars();
		
		$this->action = 'edit';
		$this->serverId = (int) $wgRequest->getText('bt_serverId');
	}
	
	/**
	 * Processes the tag attributes.
	 *
	 * @return void 
	 */
	protected function _setHookPreferences()
	{
		parent::_setHookPreferences();
	}
}
?>