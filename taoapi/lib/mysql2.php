<?php
class MySQL2 {
	private $connection2;
	
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->connection2 = mysql_connect($hostname, $username, $password)) {
      		exit('Error: Could not make a database connection using ' . $username . '@' . $hostname);
    	}

    	if (!mysql_select_db($database, $this->connection2)) {
      		exit('Error: Could not connect to database ' . $database);
    	}
		
		mysql_query("SET NAMES 'utf8'", $this->connection2);
		mysql_query("SET CHARACTER SET utf8", $this->connection2);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->connection2);
		mysql_query("SET SQL_MODE = ''", $this->connection2);
  	}
		
  	public function query($sql) {
		$resource = mysql_query($sql, $this->connection2);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;
    	
				$data = array();
		
				while ($result = mysql_fetch_assoc($resource)) {
					$data[$i] = $result;
    	
					$i++;
				}
				
				mysql_free_result($resource);
				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;
				
				unset($data);

				return $query;	
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . mysql_error($this->connection2) . '<br />Error No: ' . mysql_errno($this->connection2) . '<br />' . $sql);
			exit();
    	}
  	}
	
	public function queryOptions($sql)
	{
		$options = array();
		$query = $this->query($sql);
		
		if (!$query->rows) return $options;
		
		foreach ($query->rows as $val)
		{
			$options[$val['Value']] = $val['Title'];
		}
		return $options;
	}
	
	public function escape($value) {
		return mysql_real_escape_string($value, $this->connection2);
	}
	
  	public function countAffected() {
    	return mysql_affected_rows($this->connection2);
  	}

  	public function getLastId() {
    	return mysql_insert_id($this->connection2);
  	}	
	
}
?>