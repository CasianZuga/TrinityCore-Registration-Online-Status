<?php 

require "Messages.php";

class Database extends Messages
{
	public $conn = null;

	public function __construct($host, $username, $password, $database, $port = "3306")
	{
		self::connect($host, $username, $password, $database, $port);
	}

	protected function connect($host, $username, $password, $database, $port = "3306")
	{
		try
		{
			$this->conn = new mysqli($host, $username, $password, $database, $port);

			if ( $this->conn->errno != 0 || $this->conn->connect_error != null)
			{
				$this->error("Database Connection Error", $this->conn->connect_error." ". $this->conn->errno ." ". $this->conn->error);
			}
			$this->conn->set_charset("utf8");
			
		}
		catch (Exception $e)
		{
			$this->error("Database Connection Error", $e);
		}		
	}

	public function select($table, $column = null, $variables = null, $where = null)
	{
		try
		{
			$sql = "SELECT ";

			# Checks wheter there's a specific value to return
			# If not set to all e.g *
			if ( $column == null )
			{
				$sql .= "* ";
			}
			# Checks if there's more than 1 specific value
			# And sets them
			else if ( is_array($column) )
			{
				$column = implode(",", $column);
				$sql .= $column." ";
			}
			# Sets the specific value
			else
			{
				$sql .= $column." ";
			}

			# Checks wether the table name is empty
			## If it is throws an error with the given message
			if ( !empty($table) )
			{
				$sql .= "FROM $table ";	
			}
			else
			{
				throw new Exception("Table Name Cannot Be Empty", 1);
			}

			# Create the bind_values variable to save the value types that are going to
			## be `changed` in the sql statement
			$bind_values = null;

			# Checks wether the $variables is an array
			## If so run through the values within it and check wether
			## they are numeric or string and adds it into the $bind_values
			if ( is_array($variables) )
			{
				foreach ($variables as $key)
				{
					if ( is_numeric($key) )
					{
						$bind_values .= "i";
					}
					elseif ( is_string($key) )
					{
						$bind_values .= "s";
					} 
				}
			}
			# If it's not an array checks wether its a string and not numeric
			elseif ( is_string($variables) && !is_numeric($variables) )
			{
				$bind_values = "s";
			}
			# Or checks if it's numeric
			elseif ( is_numeric($variables) )
			{
				$bind_values = "i";
			}

			# Adds into the $sql string the value given by the parameter
			if ( $where !== null )
			{
				$sql .= "WHERE $where ";
			}

			# Prepares the statement
			$statement = $this->conn->prepare($sql);

			# Checks wether the $variables is null, if not proceeds
			if ( !is_null($variables) )
			{
				# If it's an array, bind_param with ... to go through all of the array's values
				if ( is_array($variables) )
				{
					$statement->bind_param($bind_values, ...$variables);
				}
				else
				{
					$statement->bind_param($bind_values, $variables);
				}
			}

			# Executes the statement and prints an error if there is one into the logs
			if ( !$statement->execute() )
			{
				$this->error("Information Given Could Not Be Gotten.", $statement->error);
			}

			return $statement->get_result();
		} 
		catch (Exception $e)
		{
			$this->error("Information Given Could Not Be Gotten.", $e);	
		}
	}

	public function insert($table, $variables, $columns = null)
	{
		try
		{
			$sql = "INSERT INTO ";

			# Checks wether the table name is empty
			## If it is throws an error with the given message
			if ( !empty($table) )
			{
				$sql .= "$table ";	
			}
			else
			{
				throw new Exception("Table Name Cannot Be Empty", 1);
			}

			# Checks if there's more than 1 specific value
			# And sets them
			$sql .= "(";
			if ( is_array($columns) )
			{
				$columns = implode(",", $columns);
				$sql .= $columns;
			}
			# Sets the specific value
			else
			{
				$sql .= $columns;
			}
			$sql .= ") VALUES (";

			# Create the bind_values variable to save the value types that are going to
			## be `changed` in the sql statement
			$bind_values = null;

			# Checks wether the $variables is an array
			## If so run through the values within it and check wether
			## they are numeric or string and adds it into the $bind_values
			if ( is_array($variables) )
			{
				foreach ($variables as $key)
				{
					if ( substr($sql, -1) !== "," && substr($sql, -1) == "?")
					{
						$sql .= ",";
					}

					if ( is_numeric($key) )
					{
						$bind_values .= "i";
						$sql .= "?";
					}
					elseif ( is_string($key) && !is_numeric($key) )
					{
						$bind_values .= "s";
						$sql .= "?";
					} 
				}
			}
			# If it's not an array checks wether its a string and not numeric
			elseif ( is_string($variables) && !is_numeric($variables) )
			{
				$bind_values = "s";
				$sql .= "?";
			}
			# Or checks if it's numeric
			elseif ( is_numeric($variables) )
			{
				$bind_values = "i";
				$sql .= "?";
			}
			$sql .= ")";

			# Prepares the statement
			$statement = $this->conn->prepare($sql);

			if ( is_bool($statement) )
			{
				throw new Exception("Error Processing Request", 1);
				
			}

			# Checks wether the $variables is null, if not proceeds
			if ( !is_null($variables) )
			{
				# If it's an array, bind_param with ... to go through all of the array's values
				if ( is_array($variables) )
				{
					$statement->bind_param($bind_values, ...$variables);
				}
				else
				{
					$statement->bind_param($bind_values, $variables);
				}
			}

			# Executes the statement and prints an error if there is one into the logs
			if ( !$statement->execute() )
			{
				$this->error("Information Given Could Not Be Gotten.", $statement->error);
			}

			return true;
		} 
		catch (Exception $e)
		{
			$this->error("Information Given Could Not Be Gotten.", $e);	
		}
	}

	public function update($table, $variables = null, $where_values = null, $where = null)
	{
		try
		{
			$sql = "UPDATE ";
			$bind_values = "";

			if ( !empty($table) )
			{
				$sql .= $table." ";
			}

			if ( !empty($variables) )
			{
				$sql .= "SET ";
				if ( is_array($variables) )
				{
					foreach ($variables as $key)
					{
						$sql .= $key."=?,";

						if ( is_numeric($key) )
						{
							$bind_values .= "i";
						}
						elseif ( is_string($key) )
						{
							$bind_values .= "s";
						}
					}
				}
				elseif ( is_string($variables) && !is_numeric($variables) )
				{
					$sql .= $variables."=?";
					$bind_values .= "s";
				}
				elseif ( is_numeric($variables) )
				{
					$sql .= $variables."=?";
					$bind_values .= "i";
				}
			}

			$sql .= "WHERE ";
			if ( is_array($where_values) )
			{
				$i = 0;
				foreach ($where_values as $key)
				{
					if ( is_array($where) )
					{
						if ( is_numeric($where_values) )
						{
							$bind_values .= "i";
						}
						elseif ( is_string($where_values) )
						{
							$bind_values .= "s";
						}
						$sql .= $key ."=?". $where[$i];
						$i++;
					}
					elseif ( is_string($where) )
					{
						$bind_values .= "s";
						$sql .= $key ."=?". $where;
					}
					elseif ( is_numeric($where) )
					{
						$bind_values .= "i";
						$sql .= $key ."=?". $where;
					}
				}
			}
			elseif ( is_string($where_values) )
			{
				$sql .= $where_values ."=?";
				$bind_values .= "s";
			}
			elseif ( is_numeric($where_values) )
			{
				$sql .= $where_values ."=?";
				$bind_values .= "i";
			}

			$statement = $this->conn->prepare($sql);
			if ( !$statement )
			{
				$this->error("Information Given Could Not Be Gotten.", $statement->error." ".$this->conn->connect_error);
			}

			if ( is_array($variables) )
			{
				$statement->bind_param($bind_values, ...$variables);
			}
			else
			{
				$statement->bind_param($bind_values, $variables);
			}

			if ( !$statement->execute() )
			{
				$this->error("Information Given Could Not Be Gotten.", $statement->error);
			}

			return $statement->get_result();
		} 
		catch (Exception $e)
		{
			$this->error("Information Given Could Not Be Gotten.", $e);	
		}
	}

}