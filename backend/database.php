<?php
require_once 'config.php';

class CRUD extends mysqli
{


	
	function __construct()
	{
		parent::__construct(DB::$host, DB::$user, DB::$pass, DB::$db);
	}

// Core CRUD methods
	public function create($table, $fields, $values)
	{
		$this->set_charset('utf8');
		if (is_array($fields))
		{
			foreach($fields as $value)
			{
				$fieldString += $value . ',';
			}
			$fields = rtrim($fieldString, ',');
		}

		if (is_array($values))
		{
			foreach($values as $value)
			{
				$valueString += $value . ',';
			}
			$values = rtrim($valueString, ',');
		}

		if ($fields != NULL)
		{
			$query = 'insert into ' . $table . ' (' . $fields . ') values (' . $values . ')';
		} else 
		{
			$query = 'insert into ' . $table . ' values (' . $values . ')';
		}
		$result = $this->query($query);
		return $result;
	}

	public function read($table, $fields, $argument)
	{
		$this->set_charset('utf8');
		if ($fields == NULL)
		{
			$fields = '*';
		}
		$fields = str_replace(', ', ',', $fields);

		if($argument != NULL){
			if(strpos($table, "join") !== false)
			{
				$argument = ' on ' . $argument;
			} else
			{
				$argument = ' where ' . $argument;
			}

			$query = 'select ' . $fields . ' from ' . $table . $argument;

		} else {
			$query = 'select ' . $fields . ' from ' . $table;
		}
		$result = $this->query($query);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$rows[] = $row;
		}
		return $rows;
	}

	public function update($table, $keys, $values, $argument)
	{
		$this->set_charset('utf8');
		if (is_array($keys) === FALSE)
		{
			$keys = explode(',',$keys);
		}
		
		if (is_array($values) === FALSE)
		{
			$values = explode(',',$values);
		}

		if ($argument != NULL)
		{
			$argument = ' where ' . $argument;
		} else 
		{
			$argument = ' where 1';
		}

		if ($table != NULL and count($keys) === count($values))
		{
			$query = 'update ' . $table . ' set ';

			for($i = 0; $i < count($keys); $i++)
			{
				$query .= $keys[$i] . '=' . $values[$i] . ', ';
			}

			$query = rtrim($query, ", ");
			$query .= ' ' . $argument;

			$result = $this->query($query);
			return $result;
		} else 
		{
			return FALSE;
		}

	}

	public function delete ($table, $arguments)
	{
		if ($table != NULL)
		{
			$query = 'delete from ' . $table . ' where ' . $arguments;
			$result = $this->query($query);

			return $result;
		}
	}

// Helper methods for parsing and displaying

	public function queryJSON($table, $fields, $argument)
	{
		$rows = $this->read($table, $fields, $argument);
		$result = json_encode($rows);
		return $result;
	}

	public function display($table, $fields, $argument)
	{
		$result = $this->read($table, $fields, $argument);
		foreach ($result as $value)
		{
			foreach($value as $val)
			{
				echo $val . ' ';
			}
			echo '<br>';
		}
	}
}


/*
EXAMPLE: 

if($queryTable != NULL)
{
	
	//Define $crud as an instance of the class Query and give the instance the arguments needed to open a mysql connection
	
	$crud = new CRUD(DB::$host, DB::$user, DB::$pass, DB::$db);
	
	if ($crud->connect_errno) {
	 	die("Connect failed: %s\n" . $crud->connect_error);
	}

	$result = $crud->display($queryTable, $queryFields, $queryArgs);
	
	$result2 = $crud->queryJSON($queryTable, $queryFields, $queryArgs);
		
	$crud->close();
} 
*/

?>