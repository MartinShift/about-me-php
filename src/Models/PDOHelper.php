<?php
namespace php\about\Models;
class PDOHelper {
    private static $pdo = null;

    public static function getPDO() {
        if (self::$pdo === null) {
            $host = 'localhost';
            $dbname = 'UserSkillsDB';
            $username = 'Shift';
            $password = 'Shift1593';

            try {
                self::$pdo = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                die("Could not connect to the database $dbname :" . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
