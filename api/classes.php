<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'index.php';

//Get classes  based on classID
$app->get('/api/classes/{id}', function(Request $request, Response $response) use ($model) {

    $id = $request->getAttribute('id');
    $class = $model->get_class($id);
    $response->getBody()->write($class);
    return $response;
});

$app->get('/api/classes', function(Request $request, Response $response) use ($model){
    $classes = $model->get_classes();
    $response->getBody()->write($classes);
    return $response;
});

//POST: Creates a new class
$app->post('/api/classes', function ($request, $response, $args) use ($model) {
    // Create new 
    $body = $request->getParsedBody();
    $result = $model->add_class($body['name'], $body['short'], $body['long']);
    $response->getBody()->write($result);
    return $response;
});

//PUT: edits a class
$app->put('/api/classes/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->edit_class($id, $body['name'], $body['short'], $body['long']);
    //echo $result;
    $response->getBody()->write($result);
    return $response;
    //return "{}";
});

//DELETE: deletes a class
$app->delete('/api/classes/{id}', function ($request, $response, $args) use ($model) {
    // Create new
    $id	= $args['id']; 
    $body = $request->getParsedBody();
    $result = $model->delete_ID_from_table("classID=".$id,"classes");
    $response->getBody()->write($result);
    return $response;
});

//$app->

?>
