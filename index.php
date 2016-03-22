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
//############## Students API ###################//

//Get all students
//$app->get('/api/students', function(Request $request, Response $response) use ($model) {

   // $student = $model->get_students();
    //$response->getBody()->write($student);
    //return $response;
//});

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

//############## Achievements API ###################//
//Get all achievements 
$app->get('/api/achievements', function(Request $request, Response $response) use ($model) {
    $achievements = $model->get_achievements();
    $response->getBody()->write($achievements);
    return $response;
});

//Get achievements based on classID
$app->get('/api/achievements/{classID}', function(Request $request, Response $response) use ($model) {
    $id = $request->getAttribute('classID');
    $ach = $model->get_achievements_for_class($id);
    $response->getBody()->write($ach);
    return $response;
});

//POST: Creates a new achievement 
$app->post('/api/achievement', function ($request, $response, $args) use ($model) {
    // Create new 
    $body = $request->getParsedBody();
    $result = $model->insert_achievement($body['name'], $body['short'], $body['long'], $body['points'], 
        $body['classID']);
    $response->getBody()->write($result);
    return $response;
});

//PUT: edits an achievement
$app->put('/api/achievements/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->edit_achievement($id, $body['name'], $body['short'], $body['long'], $body['points'],
        $body['classID']);
    //echo $result;
    $response->getBody()->write($result);
    return $response;
    //return "{}";
});

//DELET: deletes a student
$app->delete('/api/achievement/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->delet_ID_from_table("achievementID=".$id,"achievements");
    $response->getBody()->write($result);
    return $response;
});

// Run app
$app->run();

?>
