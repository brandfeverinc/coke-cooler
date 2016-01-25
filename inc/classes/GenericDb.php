<?php
/*
    GenericDb.php extends BaseModelPdo.php
    
    A generic class that connects to any table using the PDO connection established in BaseModelPdo.php.
    
    Note that currently this class returns data in named arrays
      (Not in objects like other Phase 3 database classes)
    
    Created by: Jay Davis
    With credit to: http://freecode.com/projects/php-pdo-wrapper-class
    Creation date: 05/05/2015
    Updated:       05/12/2015
*/

require_once("BaseModelPdo.php");

class GenericDb extends BaseModelPdo
{

    public function __construct()
    {
        // run parent constructor
        parent::__construct();
    }

    // can use directly for most SQL statements
    public function run($sql, $vars = "")
    {
        $sql = trim($sql);
        $vars = $this->assureArray($vars);

        try {
            $stmt = $this->prepare($sql);
            if ($stmt->execute($vars) !== false) {
                if (preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $sql)) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } elseif (preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $sql)) {
                    return $stmt->rowCount();
                }
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->handleError($error);
            return false;
        }
    }

    // insert data: $arr should be an associative array
    //   useful for inserting $_REQUEST array that has field names same as database table
    public function arrayInsert($table, $arr)
    {
        $fields = $this->filter($table, $arr);
        $sql = "INSERT INTO `" . $table . "` (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
        $bind = array();
        foreach ($fields as $field) {
            $bind[":$field"] = $arr[$field];
        }
        return $this->run($sql, $bind);
    }

    // update data: $arr should be an associative array
    //   useful for updating $_REQUEST array that has field names same as database table
    public function arrayUpdate($table, $arr, $where, $bind = "")
    {
        $fields = $this->filter($table, $arr);
        $fieldSize = sizeof($fields);

        $sql = "UPDATE `" . $table . "` SET ";
        for ($f = 0; $f < $fieldSize; ++$f) {
            if ($f > 0) {
                $sql .= ", ";
            }
            $sql .= $fields[$f] . " = :update_" . $fields[$f];
        }
        $sql .= " WHERE " . $where . ";";

        $bind = $this->assureArray($bind);
        foreach ($fields as $field) {
            $bind[":update_$field"] = $arr[$field];
        }
        
        return $this->run($sql, $bind);
    }
    
    // filter removes array fields that aren't in the table
    //   - good for $_REQUEST array -- removes 'submit', etc.
    private function filter($table, $arr)
    {
        $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver == 'sqlite') {
            $sql = "PRAGMA table_info('" . $table . "');";
            $key = "name";
        } else {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }

        if (false !== ($list = $this->run($sql))) {
            $fields = array();
            foreach ($list as $record) {
                $fields[] = $record[$key];
            }
            return array_values(array_intersect($fields, array_keys($arr)));
        }
        return array();
    }

    public function delete($table, $where, $bind = "")
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        $this->run($sql, $bind);
    }

    public function select($table, $where = "", $bind = "", $fields = "*")
    {
        $sql = "SELECT " . $fields . " FROM " . $table;
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }
        $sql .= ";";
        return $this->run($sql, $bind);
    }

    // Generic save function: determines if this is an existing or new entry and calls appropriate function
    // not implemented
    public function save()
    {
        return null;
    } // end save()
}
