<?php

use php\about\Models\PDOHelper;
function saveData($user)
{
    $users = loadData();
    $userKey = $user->Login;
    if (isset($users->$userKey)) {
        $users->$userKey = $user;
        $usersJson = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents('users.json', $usersJson);
    }

}

function loadData() {
    
    $helper = new PDOHelper();
    $pdo = $helper->getPDO();
    $sql = "SELECT u.*, s.name AS skill_name, s.logo AS skill_logo, us.progress 
    FROM users u
    LEFT JOIN user_skills us ON u.login = us.user_login
    LEFT JOIN skills s ON us.skill_id = s.id";
    // Execute the query and fetch all results
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $usersData = [];
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

function logout()
{
    setcookie('login', '', time() - 3600, '/');
    header("Location: index.php?c=Account&a=login");
    exit;
}
function checkIfUserExists($email)
{
    return isset(loadData()->$email);
}
function loadSkills(){
    return json_decode(file_get_contents("skills.json"), true);
}
?>