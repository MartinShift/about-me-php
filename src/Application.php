<?php
namespace php\about;


class Application {
    public function run() {
        // Default controller and action
        $controllerName = $_GET['c'] ?? 'Home';
        $actionName = $_GET['a'] ?? 'index';
        $controllerClass = "php\\about\\Controllers\\" . $controllerName . 'Controller';

        // Instantiate the controller
        $controller = new $controllerClass();

        // Check if the action exists in the controller
        if(!method_exists($controller, $actionName)) {
            die('Action does not exist.');
        }
        
        echo $controller->$actionName();
    }
}