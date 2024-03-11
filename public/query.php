<?php
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use php\about\Models\DoctrineHelper;
use php\about\Models\PDOHelper;
require_once("../vendor/autoload.php");
$login = "Shift";

$qb = new QueryBuilder(DoctrineHelper::getPDO());

// Build the SELECT query
$qb->select('u.*', 's.name AS skill_name', 's.logo AS skill_logo', 'us.progress')
   ->from('users', 'u')
   ->leftJoin('u', 'user_skills', 'us', 'u.login = us.user_login')
   ->leftJoin('us', 'skills', 's', 'us.skill_id = s.id')
   ->where('u.login = :login');

// Get the SQL query
$sql = $qb->getSQL();

$pdo = PDOHelper::getPDO();
$stmt = $pdo->prepare($sql);
$stmt->execute([':login' => $login]);
$userRows = $stmt->fetchAll(\PDO::FETCH_ASSOC);