<?php
require_once "vendor/autoload.php";

use Pavlovich\Project1\Models\Point;

$point = new Point(10,10);

$point->showInfo();
