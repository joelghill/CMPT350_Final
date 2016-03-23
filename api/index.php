<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'model.php';

// Create and configure Slim app
$app = new \Slim\App;

//echo "Created now Slim app instance....";
$model = new Model();
$model->init("localhost", "root", "root", "finalv2"); //PUT DATABASE AND PASSWORD HERE!!!! 

require 'students.php';
require 'achievements.php';
require 'classes.php';

// Run app
$app->run();

?>
