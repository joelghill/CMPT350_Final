<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//require 'vendor/autoload.php';
//require 'model.php';
require_once 'index.php';

$app->get('/api/students', function(Request $request, Response $response) use ($model) {

    $student = $model->get_students();
    $response->getBody()->write($student);
    return $response;
});

?>
