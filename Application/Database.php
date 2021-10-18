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
}

?>