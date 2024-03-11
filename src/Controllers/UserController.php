<?php

namespace php\about\Controllers;
require_once __DIR__ . '/../../public/common.php';
use php\about\Models\User;
class UserController extends BaseController {

public function userList() {
   $users = User::getUsers();
    return $this->render(__DIR__ . "/../Views/User/userlist.php", ['usersData'=> $users]);
}
public function viewuser() {
    $userId = $_GET["userId"];
    $user = User::getUsers()[$userId];
    return $this->render(__DIR__ . "/../Views/User/viewuser.php", ['user'=> $user]);
}


}