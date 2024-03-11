<?php

namespace php\about\Controllers;

require_once __DIR__ . '/../../public/common.php';
use php\about\Models\User;
use php\about\Models\PDOHelper;

class HomeController extends BaseController
{

    public function index()
    {
            $user = new User();
            if ($user->isLogged == false || $user->isLogged == null) {
                header("Location: index.php?c=Account&a=login");
                return;
            }
            return $this->render(__DIR__ . "/../Views/Home/index.php", ['aboutMe' => $user]);
    }
    public function edit()
    {
        $user = new User();
        if ($user->isLogged == false || $user->isLogged == null) {
            header("Location: index.php?c=Account&a=login");
            return;
        }
        return $this->render(__DIR__ . "/../Views/Home/edit.php", ['aboutMe' => $user]);
    }

}