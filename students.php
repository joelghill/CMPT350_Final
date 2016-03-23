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

//Get student based on ID
$app->get('/api/students/{id}', function(Request $request, Response $response) use ($model) {
    $id = $request->getAttribute('id');
    $student = $model->get_student($id);
    $response->getBody()->write($student);
    return $response;
});

//Get Student achievements
$app->get('/api/students/{id}/achievements', function(Request $request, Response $response) use ($model) {
    $id = $request->getAttribute('id');
    $result = $model->get_earned_ach_student($id);
    $response->getBody()->write($result);
    return $response;
});

//Get Student points
$app->get('/api/students/{id}/points', function(Request $request, Response $response) use ($model) {
    $id = $request->getAttribute('id');
    $result = $model->get_total_points_for_student($id);
    $response->getBody()->write($result);
    return $response;
});

//Get Student classes
$app->get('/api/students/{id}/classes', function(Request $request, Response $response) use ($model) {
    $id = $request->getAttribute('id');
    $result = $model->get_student_classes($id);
    $response->getBody()->write($result);
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

//POST: addes a student to a class
$app->post('/api/students/{id}/classes', function ($request, $response, $args) use ($model) {
    // Create new
    $stuID = $requset->getAttribute('id');
    $body = $request->getParsedBody();
    $result = $model->add_student_to_class($stuID, $body['classID'], $body['admin']);
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

//DELET: removes a student from a class
$app->delete('/api/students/{id}/classes', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $classID = $body['classID'];
    $result = $model->delete_ID_from_table("studentID=$id AND classID=$classID", "class_members");
    $response->getBody()->write($result);
    return $response;
});

?>
