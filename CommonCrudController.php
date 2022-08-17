<?php

class CommonCrudController {

    private $mysqli = '';
    private $result = [];
    private $conn = false;
	private $conditions='';
	private $joinConditions='';
	private $slgConditions='';
	/**
	 * this is use for database connection only
	 */
    public function __construct()
    {
        if (!$this->conn) {
			$this->mysqli = new mysqli('localhost', 'root', 'suman', 'portfolio_db');

			if ($this->mysqli->connect_error) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
    }
    

	/**
	 * $table_name=> Read data from table_name database
	 * $selectedField=> selected data from table_name database
	 * join data read=> selected data from table_name database
	 * $joinClause=> where clause write condition
	 * $slg => s->order by , l=limit, g= group by
	 */

    public function select(string $table_name,string $selectedField = '*', string $joinClause=null, string $whereClause = null, string $slg=null)
    {
		if ($whereClause != null) {
			$this->conditions="WHERE $whereClause";
		} 

		if ($joinClause != null) {
			$this->joinConditions=$joinClause;
		} 
		
		if($slg!=null){
			$this->slgConditions=$slg;
		}

		$sql = "SELECT {$selectedField} {$this->joinConditions} FROM {$table_name} {$this->conditions} {$this->slgConditions}";
		// echo $sql;
		// die();

		if ($this->mysqli->query($sql)) {
			$selectResult = $this->mysqli->query($sql);

			$all_result = [];
			foreach ($selectResult as $key => $value) {
				$all_result[] = $value;
			}

			array_push($this->result, $all_result);
			return true;
		} else {
			array_push($this->result, $this->mysqli->error);
			return false;
		}
    }
/**
 * inserting data into database
 * $table_name: database table_name
 * $param : array(
 * 					[fieldName]=>'Data'
 * 				)
 */
    public function create(string $table_name, array $param = array())
    {
		$table_column = implode(', ', array_keys($param));
		$field_valuses = implode("', '", $param);

		$sql = "INSERT INTO {$table_name} ({$table_column}) VALUES ('{$field_valuses}')";

		if ($this->mysqli->query($sql)) {
			array_push($this->result, 'Inserted successfull');
			return true;
		} else {
			array_push($this->result, $this->mysqli->error);
			return false;
		}
    }

    /**
	 * updated data from database table_name
	 * @ string $table_name = table_name
	 * $param = array(
	 * 			[fieldName]=>'Data'	
	 *			 )
	 * 
	 */
    public function update(string $table_name, array $param = array(), string $whereClause)
    {
		$fieldValues = [];
		foreach ($param as $key => $value) {
			$fieldValues[] = "$key = '$value'";
		}

		$field_valuses = implode(", ", $fieldValues);
        $sql = "UPDATE {$table_name} SET {$field_valuses} WHERE {$whereClause}";

		if ($this->mysqli->query($sql)) {
			array_push($this->result, 'Updated successfull');
			return true;
		} else {
			array_push($this->result, $this->mysqli->error);
			return false;
		}
    }
	/**
	 * delete data from database table
	 * 
	 */
    public function delete(string $table_name, string $delConditions = null)
    {
		if($delConditions!=null){
			$this->delConditions ="Where ". $delConditions;
		}

        $sql = "DELETE FROM {$table_name} {$this->delConditions} ";

		if ($this->mysqli->query($sql)) {
			array_push($this->result, 'Delete successfull');
			return true;
		} else {
			array_push($this->result, $this->mysqli->error);
			return false;
		}
    }

	public function resultDisplay()
	{
		$val = $this->result;
		$this->result = array();
		return $val;
	}

	public function debug($data,$strict = false){
			echo '<pre>';
				print_r($data);
			echo '</pre>';
			if($strict==true){
				die();
			}
	}

    public function __destruct()
    {
        if ($this->conn) { //True = has conncection
            if ($this->mysqli->close()) {
                $this->conn = false;
                return true;
            }
        } else { //False = has no conncection
            return false;
        }
    }
} 