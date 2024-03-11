<?php

namespace php\about\Controllers;
require_once __DIR__ . '/../../public/common.php';
class AccountController extends BaseController {

public function login() {
    return $this->render(__DIR__ . "/../Views/Account/login.php");
}
public function register() {
    return $this->render(__DIR__ . "/../Views/Account/register.php");
}
public function log_out() {
     logout();
     return $this->login();
}

}