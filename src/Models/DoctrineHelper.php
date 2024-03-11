<?php
namespace php\about\Models;

use Doctrine\DBAL\DriverManager;

require_once("../vendor/autoload.php");
class DoctrineHelper
{
    private static $conn = null;
    public static function getPDO()
    {
        if (self::$conn === null) {
            $connection = array(
                'dbname' => 'UserSkillsDB',
                'user' => 'Shift',
                'password' => 'Shift1593',
                'host' => 'localhost',
                'driver' => 'pdo_mysql',
            );
            try {
                self::$conn = DriverManager::getConnection($connection);
            } catch (\PDOException $e) {
                die("Could not connect to the database :" . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
