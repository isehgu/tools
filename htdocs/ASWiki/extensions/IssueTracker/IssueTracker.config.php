<?php
/**
 * Issue Tracking System
 * 
 * Configuration class for the IssueTracker extension.
 *
 * @category    Extensions
 * @package     IssueTracker
 * @author      Federico Cargnelutti
 * @copyright   Copyright (c) 2008 Federico Cargnelutti
 * @license     GNU General Public Licence 2.0 or later
 */
class IssueTrackerConfig
{
	/**
	 * Actions.
	 * @var array
	 */
	protected $_permissions = null;
	
	/**
	 * Issue type array.
	 * @var array
	 */
	protected $_issueType = null;
	
	/**
	 * Issue status array.
	 * @var array
	 */
	protected $_issueStatus = null;
	
	
	protected $_issueApplication = null;
	/**
	 * ...
	 * 
	 * @return void
	 */
	public function setPermissions()
	{
		$perms['list']     = array('group' => '*');
		$perms['view']     = array('group' => '*');
		$perms['add']      = array('group' => '*');
		$perms['edit']     = array('group' => '*');
		$perms['archive']  = array('group' => '*');
		$perms['delete']   = array('group' => '*');
		$perms['assign']   = array('group' => '*');
		$perms['assignee'] = array('group' => '*');
		
		$this->_permissions = $perms;
	}
	
	/**
	 * Returns the permission array.
	 *
	 * @param string $action
	 * @return array self::$_permissions
	 */
	public function getPermissions($action = null)
	{
		if ($this->_permissions === null) {
			$this->setPermissions();
		}
		
		if ($action !== null && array_key_exists($action, $this->_permissions)) {
			return $this->_permissions[$action];
		} else {
			return $this->_permissions;
		}
	}
	
	/**
	 * Sets the issue type array.
	 * 
	 * An issue's type expresses what kind of issue it is and also allows custom 
	 * name and color to be added to an issue.
	 *
	 * @param array $type
	 * @return void
	 */
	public function setIssueType($type = array()) 
	{
		$type['t_bug'] = array('name' => 'Bug Fix', 'colour' => 'FFDFDF');
		$type['t_rel'] = array('name' => 'Release', 'colour' => 'E1FFDF');
		$type['t_brk'] = array('name' => 'Break and Fix', 'colour' => 'FFFFCF');
		$type['t_new'] = array('name' => 'New Build(HW/SW)', 'colour' => 'F9F9F9'); 
		$type['t_imp'] = array('name' => 'Improvement', 'colour' => 'E5D4E7');

		$type['t_fix_CR'] = array('name' => 'Break/Fix Due to CR', 'colour' => 'FEA900'); 
		$type['t_ts'] = array('name' => 'General Troubleshooting', 'colour' => 'FFE29A');
		
		$this->_issueType = $type;
	}
	
	/**
	 * Returns the issue type array.
	 *
	 * @return array self::$_issueType
	 */
	public function getIssueType() 
	{
		if ($this->_issueType === null) {
			$this->setIssueType();
		}
		return $this->_issueType;
	}
	
	/**
	 * Sets the issue status array.
	 *
	 * @param array $status
	 * @return void
	 */
	public function setIssueStatus($status = array()) 
	{
		$status['s_new'] = array('name' => 'New', 'colour' => 'F9F9F9');
		$status['s_asi'] = array('name' => 'Assigned', 'colour' => 'DDC1C4');
		$status['s_on_hold'] = array('name' => 'On Hold', 'colour' => 'E10800');  
		$status['s_clo'] = array('name' => 'Closed', 'colour' => 'A8C3C6');
		$status['s_clo_esc'] = array('name' => 'Closed-Escalated', 'colour' => '5FAE69');
		
		$this->_issueStatus = $status;
	}
	
	/**
	 * Returns the issue status array.
	 *
	 *@return array self::$_issueStatus
	 */
	public function getIssueStatus() 
	{
		if ($this->_issueStatus === null) {
			$this->setIssueStatus();
		}
		return $this->_issueStatus;
	}
	
	
	/*********************************************************************/
	/**
		 * Sets the issue application array.
	 *
	 * @param array $application
	 * @return void
	 */
	public function setIssueApplication($application = array()) 
	{
		$application['a_billing'] = array('name' => 'Billing');
		$application['a_clearing'] = array('name' => 'Clearing');
		$application['a_combex'] = array('name' => 'Combex');
		$application['a_epe'] = array('name' => 'EPE');
		$application['a_iors'] = array('name' => 'IORS');
		$application['a_ivs'] = array('name' => 'IVS');
		$application['a_midas'] = array('name' => 'Midas');
		$application['a_ods'] = array('name' => 'ODS');
		$application['a_oht'] = array('name' => 'OHT');
		$application['a_omx'] = array('name' => 'OMX');
		$application['a_precise'] = array('name' => 'Precise');
		$application['a_rtp'] = array('name' => 'RTP');			
		$application['a_surveillance'] = array('name' => 'Surveillance');
		$application['a_optimise'] = array('name' => 'Optimise');
		
		$this->_issueApplication = $application;
	}
	
	/**
	 * Returns the issue application array.
	 *
	 *@return array self::$_issueApplication
	 */
	public function getIssueApplication() 
	{
		if ($this->_issueApplication === null) {
			$this->setIssueApplication();
		}
		return $this->_issueApplication;
	}
}
