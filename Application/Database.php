<?php
namespace Application;

use PDO;
use PDOException;

class Database
{
    private static $dbHost = "";
    private static $dbName = "";
    private static $dbUsername = "";
    private static $dbPassword = "";

    private static $connection = NULL;

    private static $size_value_db = array(
        "varchar" => "varchar(255)",
        "text" => "text",
        "int" => "int(11)",
        "tinyint" => "tinyint(3)",
        "smallint" => "smallint(5)",
        "mediumint" => "mediumint(8)",
        "bigint" => "bigint(20)",
        "boolean" => "tinyint(1)",
        "date" => "date",
        "decimal" => "decimal(10.0)",
        "float" => "float",
        "double" => "double",
        "real" => "double",
        "serial" => "bigint(20)",
        "datetime" => "datetime",
        "timestamp" => "timestamp",
        "time" => "time",
        "timestamp" => "timestamp",
        "year" => "year(4)",
        "char" => "char(1)",
    );

    public function __construct() {}

    public static function connect() {
        if (self::canConnectSpecificsDb(self::$dbName) == false) {
            self::$connection = NULL;
            return self::$connection;
        }
        if (self::$connection == NULL) {
            try {
                self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUsername, self::$dbPassword);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die($e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function init($host, $dbName, $dbUsername, $dbPassword) {

    }

    private static function impermanentConnectionDb($dbName) {
        if (self::$connection == NULL) {
            try {
                self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . $dbName, self::$dbUsername, self::$dbPassword);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die($e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function disconnect() {
        self::$connection = NULL;
    }
    
    public static function initDb(array $confSys) {
        self::$dbHost = $confSys['host'];
        self::$dbName = $confSys['dbName'];
        self::$dbUsername = $confSys['username'];
        self::$dbPassword = $confSys['passsword'];
    }

    private static function getNbTablesInDb($dbName, $connect):int {
        $res = 0;
        $stm = $connect->prepare("SELECT * FROM TABLES WHERE TABLE_SCHEMA=?");
        
        $stm->execute(array($dbName));
        while ($stm->fetch()) {
            $res++;
        }
        return $res;
    }

    public static function createNewDb(string $dbName):bool {
        if (self::canConnectSpecificsDb("mysql") == false)
            return false;
        $connect = self::impermanentConnectionDb("mysql");
        $stm = $connect->prepare("CREATE DATABASE " . $dbName);
        $stm->execute();
        self::disconnect();
        return true;
    }

    public static function getEmptyDb():array {
        $res = array();
        $all_dbs = array();
        $all_db_nb = array();
        $connect = self::impermanentConnectionDb("information_schema");

        $stm = $connect->prepare("SELECT * FROM SCHEMATA WHERE 1");
        $stm->execute();

        while ($resStm = $stm->fetch()) {
            array_push($all_dbs,$resStm['SCHEMA_NAME']);
            array_push($all_db_nb, self::getNbTablesInDb($resStm['SCHEMA_NAME'], $connect));
        }

        for ($i = 0; $i < count($all_db_nb); $i++) {
            if ($all_db_nb[$i] == 0)
                array_push($res, $all_dbs[$i]);
        }
        self::disconnect();
        return $res;
    }

    public static function isDbExists(string $nameDb): bool {
        $resRet = false;
        $connect = self::impermanentConnectionDb("information_schema");
        $stm = $connect->prepare("SELECT * FROM SCHEMATA WHERE 1");

        $stm->execute();
        while ($res = $stm->fetch()) {
            if ($res['SCHEMA_NAME'] == $nameDb) {
                $resRet = true;
                break;
            }
        }
        self::disconnect();
        return $resRet;
    }

    public static function canConnect(): bool {
        $connection = NULL;
        if (self::$dbHost == "" || self::$dbUsername == "")
            return false;
        try {
            $connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUsername, self::$dbPassword);
        } catch (PDOException $e) {
            return false;
        }
        if ($connection != NULL)
            return true;
        return false;
    }

    public static function canConnectSpecificsDb($dbName): bool {
        $connection = NULL;
        if (self::$dbHost == "" || $dbName == "" || self::$dbUsername == "")
            return false;
        try {
            $connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . $dbName, self::$dbUsername, self::$dbPassword);
        } catch (PDOException $e) {
            return false;
        }
        if ($connection != NULL)
            return true;
        return false;
    }

    public static function canConnectWithArray(array $dbIntels):bool {
        $connection = NULL;
        if (self::$dbHost == "" || self::$dbUsername == "")
            return false;
        try {
            $connection = new PDO("mysql:host=" . $dbIntels['host'] . ";dbname=" . $dbIntels['dbName'], $dbIntels['username'], $dbIntels['passsword']);
        } catch (PDOException $e) {
            return false;
        }
        if ($connection != NULL)
            return true;
        return false;
    }

    public static function tabelExists($tableName):bool {
        global $db;
        $resReturn = false;
        $connect = $db->connect();
        $stm = $connect->prepare("select 1 from kp_tables LIMIT 1");
        $stm->execute();
        $res = $stm->fetch();
        if ($res) {
            $stm = $connect->prepare("SELECT * FROM kp_tables WHERE name=?");
            $stm->execute(array($tableName));
            $res = $stm->fetch();
            if ($res)
                $resReturn = true;
        } else {
            $stm = $connect->prepare("select 1 from " . $tableName . " LIMIT 1");
            $stm->execute();
            $res = $stm->fetch();
            if ($res)
                $resReturn = true;
        }
        $db->disconnect();
        return $resReturn;
    }

    public static function tableVariableExists(string $tableName, string $varName):bool {
        global $db;
        $resReturn = false;
        $connect = $db->connect();
        $stm = $connect->prepare("select 1 from kp_tables LIMIT 1");
        $stm->execute();
        $res = $stm->fetch();
        if ($res) {
            $stm = $connect->prepare("SELECT * FROM kp_tables WHERE name=?");
            $stm->execute(array($tableName));
            $res = $stm->fetch();
            if ($res) {
                $vars = explode(",", $res['args']);
                foreach ($vars as $targetedVar) {
                    if ($targetedVar == $varName) {
                        $resReturn = true;
                        break;
                    }
                }
            }
        }
        $db->disconnect();
        return $resReturn;
    }

    private static function getCmdVarToAdd(array $varArray):string {
        $nameSize = "";
        $valueDef = "";
        $nullable = "";
        $index = "";
        if (isset($varArray['size']) && $varArray['size'] != "") {
            $nameSize = $varArray['type'] . "(" . $varArray['size'] . ")";
        } else {
            $nameSize = self::$size_value_db[$varArray['type']];
        }
        if (!isset($varArray['nullable']) || $varArray['nullable'] == "") {
            $nullable = " NOT NULL";
        }
        if (isset($varArray['value']) && $varArray['value'] != "") {
            $valueDef = " DEFAULT " . $varArray['value'];
        } else if ($nullable != "NOT NULL") {
            $valueDef = " DEFAULT NULL";
        }
        if (isset($varArray['index']) && $varArray['index'] != "") {
            $index = " " . $varArray['index'];
            if (isset($varArray['ai']) && $varArray['ai'] != "") {
                $index = " AUTO_INCREMENT" . $index;
            }
        }
        $resStr = $varArray['name'] . " " . $nameSize . $nullable . $valueDef . $index;
        return $resStr;
    }

    public static function addTableToDb(array $tableIntels) {
        global $db;
        if (!isset($tableIntels['name']) || !isset($tableIntels['vars']) || count($tableIntels['vars']) <= 0) {
            return;
        }
        $nb_vars = 1;
        $str_args = $tableIntels['vars'][0]['name'];
        $str_types = $tableIntels['vars'][0]['type'];
        $strAdding = "CREATE TABLE IF NOT EXISTS " . $tableIntels['name'] . " (" . self::getCmdVarToAdd($tableIntels['vars'][0]) . ")";
        $connect = $db->connect();
        $stm = $connect->prepare($strAdding);
        $stm->execute();
        foreach($tableIntels['vars'] as $var) {
            if ($var != $tableIntels['vars'][0]) {
                $str = "ALTER TABLE " . $tableIntels['name'] . " ADD COLUMN " . self::getCmdVarToAdd($var);
                $stm = $connect->prepare($str);
                $stm->execute();
                $nb_vars++;
                $str_args .= "," . $var['name'];
                $str_types .= "," . $var['type'];
            }
        }
        $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->execute(array(
            $tableIntels['name'],
            $nb_vars,
            $str_types,
            $str_args,
            0,
            1,
            1,
            1
        ));
    }

    public static function addVariableToDb(array $tableIntels) {
        if (!isset($tableIntels['name']) || !isset($tableIntels['vars'])) {
            return;
        }
        foreach($tableIntels['vars'] as $var) {

        }
    }
}

?>