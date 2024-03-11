<?php

namespace php\about\Controllers;
abstract class BaseController {
    
protected function render(string $view, array $data = []) {
    extract($data);
    ob_start();
    include($view);
    $content = ob_get_clean(); 
    return $content; 
}
}