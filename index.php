<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'model.php';

// Create and configure Slim app
$app = new \Slim\App;

//echo "Created now Slim app instance....";
$model = new Model();
$model->init("localhost", "root", "root", "A3"); //PUT DATABASE AND PASSWORD HERE!!!! 

//Get all students
$app->get('/api/students', function(Request $request, Response $response) use ($model) {

    $student = $model->get_students();
    $response->getBody()->write($student);
    return $response;
});

//Get student based on ID
$app->get('/api/students/{id}', function(Request $request, Response $response) use ($model) {

    $id = $request->getAttribute('id');
    $student = $model->get_student($id);
    $response->getBody()->write($student);
    return $response;
});

//POST: Creates a new student
$app->post('/api/students', function ($request, $response, $args) use ($model) {
    // Create new 
    $body = $request->getParsedBody();
    $result = $model->insert_student($body['first'], $body['last'], $body['email']);
    $response->getBody()->write($result);
    return $response;
});

//PUT: edits a student
$app->put('/api/students/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->edit_student($id, $body['first'], $body['last'], $body['email']);
    //echo $result;
    $response->getBody()->write($result);
    return $response;
    //return "{}";
});

//DELET: deletes a student
$app->delete('/api/students/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->delete_ID_from_table("studentID=".$id,"students");
    $response->getBody()->write($result);
    return $response;
});

// Run app
$app->run();

?>
