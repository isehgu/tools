<?php
/** @see IssueTrackerModel **/
require_once dirname(__FILE__) . '/ServerRegisterModel.php';

/**
 */
class ServerRegisterModelDefault extends ServerRegisterModel
{
	/**
	 * Database table name.
	 * @var string 
	 */
	protected $_table = 'server_register';
	
	/**
	 * Selects a limited number of servers ordered by id.
	 *
	 * @param mixed $conds Conditions
	 * @param int $offset
	 * @param int $limit
	 * @return ResultSet
	 */
	public function getServers($conds, $offset, $limit = 100)
	{		
		$options = array(
			'ORDER BY' => 'priority_date DESC, server_id ASC',
			'LIMIT'    => $limit,
			'OFFSET'   => (int) $offset
		);
		
		return $this->_dbr->select($this->_table, '*', $conds, 'Database::select', $options);
	}
	
	/*****************************************************************************************************************************************************/
	#  Search Node Name
	public function getServersNode($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`node` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
	
	#  Search IP
	public function getServersIP($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`ip` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
	
	#  Search Services
	public function getServersService($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`service` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
	
	#  Search Description
	public function getServersDescription($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`description` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
		
	#  Search Backup Node
	public function getServersBackupNode($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`backup_node` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
	
	#  Search Serial#
	public function getServersSerial($string, $project, $offset)
	{
		$project = addslashes($project);
		$string = addslashes($string);
		$conds = "`project_name` = '".$project."' 
		          AND `deleted` = 0 
		          AND (`serial` LIKE '%".$string."%')";
		
		return $this->getServers($conds, $offset);
	}
	/*****************************************************************************************************************************************************/
	/**
	 * Selects an Server based on a given id.
	 *
	 * @param int $serverId
	 * @return ResultSet
	 */
	public function getServerById($serverId)
	{
		$conds['server_id'] = (int) $serverId;
		return $this->_dbr->select($this->_table, '*', $conds, 'Database::select');
	}
	
	

	
	
	
	/**
	 * Adds a new Server to the database.
	 *
	 */
	public function addServer($postData, $userId, $userName)
	{
		$data = array(
			'node'         => $postData['bt_node'], 
			'ip'       => $postData['bt_ip'], 
			'status'       => $postData['bt_status'],
			'application'   => $postData['bt_application'], 
			'service'          => $postData['bt_service'], 
			'description'        => $postData['bt_description'], 
			'backup_node'      => $postData['bt_backupnode'],
			'os'      => $postData['bt_os'],
			'processor'      => $postData['bt_processor'],
			'memory'      => $postData['bt_memory'],
			'net'      => $postData['bt_net'],
			'cpp'      => $postData['bt_cpp'],
			'serial'      => $postData['bt_serial'],			
			
			'user_id'       => $userId,
			'user_name'     => $userName,
			'project_name'  => $postData['bt_project'],
			'priority_date' => date('Y-m-d H:i:s'),
		);
		
		return $this->_dbr->insert($this->_table, $data);
	}
	
	/**
	 * Updates a server.
	 */
	public function updateServer($serverId, $postData)
	{
		$value = array(
			'node'         => $postData['bt_node'], 
			'ip'       => $postData['bt_ip'], 
			'status'       => $postData['bt_status'],
			'application'   => $postData['bt_application'], 
			'service'          => $postData['bt_service'], 
			'description'        => $postData['bt_description'], 
			'backup_node'      => $postData['bt_backupnode'],
			'os'      => $postData['bt_os'],
			'processor'      => $postData['bt_processor'],
			'memory'      => $postData['bt_memory'],
			'net'      => $postData['bt_net'],
			'cpp'      => $postData['bt_cpp'],
			'serial'      => $postData['bt_serial'],	
		);
		
		$conds['server_id'] = (int) $serverId;
		
		return $this->_dbr->update($this->_table, $value, $conds);
	}
	
	/**
	 * Archives a server.
	 *
	 * @param int $serverId
	 * @return bool Returns true on success or false on failure.
	 */
	public function archiveIssue($serverId)
	{
		$value['deleted'] = 1;
		$conds['server_id'] = (int) $serverId;
		
		return $this->_dbr->update($this->_table, $value, $conds);
	}
}
?>