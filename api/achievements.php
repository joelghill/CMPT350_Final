<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'model.php';

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

?>
