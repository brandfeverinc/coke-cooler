<?php
/**
 *  BaseModelPdo class - base class for all models, contains common functionality
 *  PHP PDO version by Jay Davis 05/12/2015
 *    - Inspired by http://freecode.com/projects/php-pdo-wrapper-class
 *    - Updated to conform to PSR1 and PSR2
**/
class BaseModelPdo extends PDO
{
    protected $dbengine = "mysql";
    protected $hostname = "10.10.1.74";
    protected $database = "cc_cooler";
    protected $user = "cc_cooler_user";
    protected $pass = "Fr33ze!";

    protected $options = array(
        PDO::ATTR_PERSISTENT => false, // true causes all classes that inherit from this one to merge together messily
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    public $isdev;

    public function __construct()
    {
        $this->isdev = $this->isDevelopment();
        $dsn = $this->dbengine . ":host=" . $this->hostname . ";dbname=" . $this->database;
        try {
            parent::__construct($dsn, $this->user, $this->pass, $this->options);
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->handleError($error);
            return false;
        }
    } // end __construct()

    protected function isDevelopment()
    {
        //if ($_SESSION['se__show_sql_errors'] == 'foo') return true;
        return true;
    }

    protected function handleError($e)
    {
        if (!$this->isdev) {
            return;
        }
        echo '<pre>' . $e . '</pre>';
        /*
        echo '<pre>';
        print_r($this);
        echo '</pre>';
        */
    } // end handleError()

    // Runs a query and returns new id (used for INSERT)
    protected function insertQuery($sql)
    {
        $result = "#ERR";

        try {
            $stmt = $this->prepare($sql);
            $stmt->execute();
            $result = $this->lastInsertId();
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->handleError($error);
            $result = -1;
        }

        return $result;  // return inserted row id
    } // end insertQuery($sql)

    // Runs a query; has no return value (used for UPDATE or DELETE)
    // Note: Not optimized for PDO (bind vars)
    protected function updateQuery($sql)
    {
        $result = "#ERR";

        try {
            $stmt = $this->prepare($sql);
            $stmt->execute();
            $ret = $stmt->rowCount(); // get # affected rows... but not using
            $result = true;
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->handleError($error);
            $result = false;
        }

        return $result; // success true/false
    } // end updateQuery()

    // Runs a query and returns the db result object (used for SELECT)
    protected function returnResultSet($sql, $vars = "")
    {
        $result = "#ERR";

        $vars_array = $this->assureArray($vars);

        try {
            $stmt = $this->prepare($sql);
            $stmt->execute($vars_array);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            if ($e->getCode() == 'HY093') {
                // "Invalid parameter number: no parameters were bound"
                // This error is returned by PDO when getting empty result set
                $result = array();
            }
            else {
                $error = $e->getMessage();
                $this->handleError($error);
            }
        }

        return $result;
    } // end returnResultSet()

    // Runs a query that is expected to return a single value
    // Note from Jay: I have not tested this function!
    protected function returnValue($sql)
    {
        $result = "#ERR";

        try {
            $stmt = $this->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->handleError($error);
            return false;
        }

        $returnVal = "";
        if ($result) {
            $returnVal = $result[0];
        }
        return $returnVal;
    } // end returnValue()

    // Assures we have an array for bind vars
    public function assureArray($vars)
    {
        if (!is_array($vars)) {
            if (!empty($vars)) {
                $vars = array($vars);
            } else {
                $vars = array();
            }
        }
        return $vars;
    } // end assureArray()
}
