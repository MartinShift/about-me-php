<?php
namespace php\about\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use php\about\Models\DoctrineHelper;

class User
{
    public $login;
    public $firstName;
    public string $lastName;
    private $password;
    public $description;
    public $age;
    public $profilePicture;
    public $skills = [];

    public $isLogged;
    private $pdo;

    public function __construct()
    {
        $this->pdo = PDOHelper::getPDO();
        $this->load();
    }

    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }
    public function load()
    {
        $pdo = PDOHelper::getPDO();
        if (isset($_COOKIE['login'])) {
            $login = json_decode($_COOKIE['login'], true);
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
            if ($userRows) {
                $this->login = $userRows[0]['login'];
                $this->firstName = $userRows[0]['first_name'];
                $this->lastName = $userRows[0]['last_name'];
                $this->password = $userRows[0]['password'];
                $this->description = $userRows[0]['description'];
                $this->age = $userRows[0]['age'];
                $this->profilePicture = $userRows[0]['profile_picture'];
                $this->skills = [];
                foreach ($userRows as $row) {
                    if ($row['skill_name']) {
                        $this->skills[] = new Skill($row['skill_name'], $row['progress'], $row['skill_logo']);
                    }
                }
                $this->isLogged = true;
            } else {
                $this->isLogged = false;
            }
        }
    }
    public static function getUsers()
    {

        $qb = new QueryBuilder(DoctrineHelper::getPDO());
        $qb->select('u.*', 's.name AS skill_name', 's.logo AS skill_logo', 'us.progress')
            ->from('users', 'u')
            ->leftJoin('u', 'user_skills', 'us', 'u.login = us.user_login')
            ->leftJoin('us', 'skills', 's', 'us.skill_id = s.id');
        $sql = $qb->getSQL();

        $pdo = PDOHelper::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            $login = $user['login'];
            if (!isset($usersData[$login])) {
                $usersData[$login] = [
                    'FirstName' => $user['first_name'],
                    'LastName' => $user['last_name'],
                    'Password' => $user['password'],
                    'Description' => $user['description'],
                    'Age' => $user['age'],
                    'ProfilePicture' => $user['profile_picture'],
                    'Skills' => []
                ];
            }
            if ($user['skill_name']) {
                $usersData[$login]['Skills'][] = [
                    'Name' => $user['skill_name'],
                    'Progress' => $user['progress'],
                    'Logo' => $user['skill_logo'],
                ];
            }
        }
        return $usersData;
    }
    public static function findByLogin($login)
    {

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

        if ($userRows) {
            $user = new User();
            $user->login = $userRows[0]['login'];
            $user->firstName = $userRows[0]['first_name'];
            $user->lastName = $userRows[0]['last_name'];
            $user->password = $userRows[0]['password']; // Remember to handle passwords securely
            $user->description = $userRows[0]['description'];
            $user->age = $userRows[0]['age'];
            $user->profilePicture = $userRows[0]['profile_picture'];

            foreach ($userRows as $row) {
                if ($row['skill_name']) {
                    $user->skills[] = [
                        'Name' => $row['skill_name'],
                        'Progress' => $row['progress'],
                        'Logo' => $row['skill_logo'],
                    ];
                }
            }

            return $user;
        } else {
            return null; // No user found
        }
    }
    public function Update($postData)
    {
        $this->pdo->beginTransaction();

        try {
            // Update personal information
            $stmt = $this->pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, age = :age, description = :description, profile_picture = :profile_picture WHERE login = :login");
            $stmt->execute([
                ':first_name' => $postData['firstName'],
                ':last_name' => $postData['lastName'],
                ':age' => $postData['age'],
                ':description' => $postData['description'],
                ':profile_picture' => $postData['profilePicture'],
                ':login' => $this->login,
            ]);
            $stmt = $this->pdo->prepare("DELETE FROM user_skills WHERE user_login = :login");
            $stmt->execute([':login' => $this->login]);

            $insertSkillSql = "INSERT INTO user_skills (user_login, skill_id, progress) VALUES (:login, :skill_id, :progress)";
            $skillStmt = $this->pdo->prepare($insertSkillSql);

            foreach ($postData['skills'] as $skillData) {
                $skillId = Skill::getOrAddSkill($skillData['name'], $skillData['logo']);
                $skillStmt->execute([
                    ':login' => $this->login,
                    ':skill_id' => $skillId,
                    ':progress' => $skillData['progress'],
                ]);
            }
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    public static function getUser($login)
    {

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
        if ($userRows) {
            $userData = [
                'Login' => $userRows[0]['login'],
                'FirstName' => $userRows[0]['first_name'],
                'LastName' => $userRows[0]['last_name'],
                'Password' => $userRows[0]['password'],
                'Description' => $userRows[0]['description'],
                'Age' => $userRows[0]['age'],
                'ProfilePicture' => $userRows[0]['profile_picture'],
                'Skills' => []
            ];
            foreach ($userRows as $row) {
                if ($row['skill_name']) {
                    $userData['Skills'][] = [
                        'Name' => $row['skill_name'],
                        'Progress' => $row['progress'],
                        'Logo' => $row['skill_logo'],
                    ];
                }
            }

            return $userData;
        }
    }
    public function addSkill($skillName, $skillProgress, $skillLogo)
    {
        try {
            $skillId = Skill::getOrAddSkill($skillName, $skillLogo);

            $qb = new QueryBuilder(DoctrineHelper::getPDO());
            $qb->insert('user_skills')
                ->values([
                    'user_login' => ':login',
                    'skill_id' => ':skill_id',
                    'progress' => ':progress',
                ]);

            // Get the SQL query
            $insertUserSkillSql = $qb->getSQL();

            $stmt = $this->pdo->prepare($insertUserSkillSql);
            $stmt->execute([
                ':login' => $this->login,
                ':skill_id' => $skillId,
                ':progress' => $skillProgress
            ]);

        } catch (\PDOException $e) {
            throw $e;
        }
    }
    public function addExistingSkill($skillName)
    {
        try {
            $skillId = Skill::getOrAddSkill($skillName, '');
            $qb = new QueryBuilder(DoctrineHelper::getPDO());
            $qb->insert('user_skills')
                ->values([
                    'user_login' => ':login',
                    'skill_id' => ':skill_id',
                    'progress' => ':progress',
                ]);
            $insertUserSkillSql = $qb->getSQL();
            $stmt = $this->pdo->prepare($insertUserSkillSql);
            $stmt->execute([
                ':login' => $this->login,
                ':skill_id' => $skillId,
                ':progress' => 0
            ]);
            header("Location: index.php?c=Home&a=edit");
            exit();

        } catch (\PDOException $e) {
            throw $e;
        }
    }
    public function delete()
    {

    }
    public static function createUser($login, $hashedPassword)
    {
        $pdo = PDOHelper::getPDO();
        $qb = new QueryBuilder(DoctrineHelper::getPDO());

        $qb->insert('users')
            ->values([
                'login' => ':login',
                'first_name' => ':first_name',
                'last_name' => ':last_name',
                'description' => ':description',
                'password' => ':password',
                'age' => ':age',
                'profile_picture' => ':profile_picture',
            ]);
        $sql = $qb->getSQL();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':first_name' => $login,
            ':last_name' => "",
            ':password' => $hashedPassword,
            ':description' => "",
            ':age' => "",
            ':profile_picture' => "img/default-pfp.png"
        ]);

    }

}
