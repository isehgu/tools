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
class ServerRegisterConfig
{
	/**
	 * Actions.
	 * @var array
	 */
	protected $_permissions = null;
	
	/**
	 * OS array.
	 * @var array
	 */
	protected $_serverOS = null;
	
	/**
	 * status array.
	 * @var array
	 */
	protected $_serverStatus = null;
	
	/**
	 * Application array.
	 * @var array
	 */
	protected $_serverApplication = null;
	
	/**
	 * Processor array.
	 * @var array
	 */
	protected $_serverProcessor = null;
	
	/**
	 * Memory array.
	 * @var array
	 */
	protected $_serverMemory = null;
	
	/**
	 * MS .NET array.
	 * @var array
	 */
	protected $_serverNet = null;
	
	/**
	 * C++ Redistributable array.
	 * @var array
	 */
	protected $_serverCpp = null;

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
	 * @param array $os
	 * @return void
	 */
	public function setServerOS($os = array()) 
	{
		$os['t_win2003_64'] = array('name' => 'Win 2003 Server 64bit', 'colour' => 'FFDFDF');
		$os['t_win2003_32'] = array('name' => 'Win 2003 Server 32bit', 'colour' => 'FEA900'); 
		$os['t_winxp_32'] = array('name' => 'WinXP 32bit', 'colour' => 'E1FFDF');
		$os['t_winxp_64'] = array('name' => 'WinXP 64bit', 'colour' => 'FFFFCF');
		$os['t_linux'] = array('name' => 'Linux', 'colour' => 'F9F9F9'); 
		$os['t_vms'] = array('name' => 'VMS', 'colour' => 'E5D4E7');

		
#		$os['t_ts'] = array('name' => 'General Troubleshooting', 'colour' => 'FFE29A');
		
		$this->_serverOS = $os;
	}
	
	/**
	 * Returns the issue type array.
	 *
	 * @return array self::$_serverOS
	 */
	public function getServerOS() 
	{
		if ($this->_serverOS === null) {
			$this->setServerOS();
		}
		return $this->_serverOS;
	}
	
	/**
	 * Sets the issue status array.
	 *
	 * @param array $status
	 * @return void
	 */
	public function setServerStatus($status = array()) 
	{
		$status['s_active'] = array('name' => 'Active', 'colour' => 'F9F9F9');
		$status['s_inactive'] = array('name' => 'Inactive', 'colour' => 'DDC1C4');
		$status['s_prodready'] = array('name' => 'Prod Ready', 'colour' => 'E10800');  
		$status['s_suspended'] = array('name' => 'Suspended', 'colour' => 'A8C3C6');
		$status['s_hwready'] = array('name' => 'Hareware Ready', 'colour' => '5FAE69');
		$status['s_retired'] = array('name' => 'Retired', 'colour' => 'FFE29A');
		
		$this->_serverStatus = $status;
	}
	
	/**
	 * Returns the issue status array.
	 *
	 *@return array self::$_serverStatus
	 */
	public function getServerStatus() 
	{
		if ($this->_serverStatus === null) {
			$this->setServerStatus();
		}
		return $this->_serverStatus;
	}
	
	
	/*********************************************************************/
	/**
		 * Sets the issue application array.
	 *
	 * @param array $application
	 * @return void
	 */
	public function setServerApplication($application = array()) 
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
		
		$this->_serverApplication = $application;
	}
	
	/**
	 * Returns the issue application array.
	 *
	 *@return array self::$_serverApplication
	 */
	public function getServerApplication() 
	{
		if ($this->_serverApplication === null) {
			$this->setServerApplication();
		}
		return $this->_serverApplication;
	}
}
	/*********************************************************************/
	/**
		 * Sets the Processor array.
	 *
	 * @param array $processor
	 * @return void
	 */
	public function setServerProcessor($processor = array()) 
	{
		$processor['p_1'] = array('name' => '1 Core');
		$processor['p_2'] = array('name' => '2 Cores');
		$processor['p_4'] = array('name' => '4 Cores');
		$processor['p_8'] = array('name' => '8 Cores');
		$processor['p_16'] = array('name' => '16 Cores');
		$processor['p_gt16'] = array('name' => 'Greater than 16 Cores');
		
		$this->_serverProcessor = $processor;
	}
	
	/**
	 * Returns the Processor array.
	 *
	 *@return array self::$_serverProcessor
	 */
	public function getServerProcessor() 
	{
		if ($this->_serverProcessor === null) {
			$this->setServerProcessor();
		}
		return $this->_serverProcessor;
	}
}
	/*********************************************************************/
	/**
		 * Sets the Memory array.
	 *
	 * @param array $memory
	 * @return void
	 */
	public function setServerMemory($memory = array()) 
	{
		$memory['m_lt4'] = array('name' => 'Less than 4GB');
		$memory['m_4'] = array('name' => '4GB');
		$memory['m_8'] = array('name' => '8GB');
		$memory['m_16'] = array('name' => '16GB');
		$memory['m_32'] = array('name' => '32GB');
		$memory['m_gt32'] = array('name' => 'Greater than 32GB');
		
		$this->_serverApplication = $memory;
	}
	
	/**
	 * Returns the memory array.
	 *
	 *@return array self::$_serverMemory
	 */
	public function getServerMemory() 
	{
		if ($this->_serverMemory === null) {
			$this->setServerMemory();
		}
		return $this->_serverMemory;
	}
}
	/*********************************************************************/
	/**
		 * Sets the .NET array.
	 *
	 * @param array $net
	 * @return void
	 */
	public function setServerNet($net = array()) 
	{
		$net['n_1'] = array('name' => '.NET 1.1');
		$net['n_2_nosp'] = array('name' => '.NET 2.0 No SP');
		$net['n_2_sp1'] = array('name' => '.NET 2.0 SP1');
		$net['n_2_sp2_nopatch'] = array('name' => '.NET 2.0 SP2 No Patch');
		$net['n_2_sp2_patch'] = array('name' => '.NET 2.0 SP2 Patch KB958481');
		
		$this->_serverNet = $net;
	}
	
	/**
	 * Returns the .NET array.
	 *
	 *@return array self::$_serverNet
	 */
	public function getServerNet() 
	{
		if ($this->_serverNet === null) {
			$this->setServerNet();
		}
		return $this->_serverNet;
	}
}
	/*********************************************************************/
	/**
		 * Sets the C++ Redistributable array.
	 *
	 * @param array $cpp (C++)
	 * @return void
	 */
	public function setServerC($cpp = array()) 
	{
		$cpp['c_2005'] = array('name' => 'C++ Redistributable 2005');
		$cpp['c_2008'] = array('name' => 'C++ Redistributable 2008');

		
		$this->_serverC = $cpp;
	}
	
	/**
	 * Returns the C++ Redistributable array.
	 *
	 *@return array self::$_serverC
	 */
	public function getServerCpp() 
	{
		if ($this->_serverCpp === null) {
			$this->setServerCpp();
		}
		return $this->_serverCpp;
	}
}