<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    // return str_random(32);
    return 'Unauthorized';
});

$router->get('key',function(){
    return str_random(32);
});

// User Auth
$router->post('/register', ['as' => 'user.register', 'uses' => 'AuthController@register']);
$router->post('/login', ['as' => 'user.login', 'uses' => 'AuthController@login']);
$router->get('/user/{id}', ['as' => 'user.show', 'uses' => 'AuthController@show']);
$router->delete('/user/{id}', ['as' => 'user.destroy', 'uses' => 'AuthController@destroy']);
$router->get('/logout', ['as' => 'user.logout', 'uses' => 'AuthController@logout']);

// TEMPLATE
$router->get('/checklists/templates', ['as' => 'listallchecklisttemplate', 'uses' => 'TemplateController@listallchecklisttemplate']);
$router->get('/checklists/templates/{templateId}', ['as' => 'getchecklisttemplate', 'uses' => 'TemplateController@getchecklisttemplate']);
$router->post('checklists/templates', ['as' => 'createchecklisttemplate', 'uses' => 'TemplateController@store']);
$router->patch('checklists/templates/{templateId}', ['as' => 'updatechecklisttemplate', 'uses' => 'TemplateController@update']);
$router->delete('/checklists/templates/{templateId}', ['as' => 'deletechecklisttemplate', 'uses' => 'TemplateController@destroy']);

// ITEMS
$router->post('checklists/{checklistId}/items', ['as' => 'createchecklistitem', 'uses' => 'ItemController@store']);
$router->get('checklists/{checklistId}/items/{itemId}', ['as' => 'getchecklistitem', 'uses' => 'ItemController@getchecklistitem']);
$router->post('checklists/complete', ['as' => 'completeitems', 'uses' => 'ItemController@completeitems']);
$router->post('checklists/incomplete', ['as' => 'incompleteitems', 'uses' => 'ItemController@incompleteitems']);
$router->get('checklists/{checklistId}/items', ['as' => 'listofitemingivenchecklist', 'uses' => 'ChecklistController@listofitemingivenchecklist']);
$router->patch('checklists/{checklistId}/items/{itemId}', ['as' => 'updatechecklistitem', 'uses' => 'ItemController@update']);
$router->delete('checklists/{checklistId}/items/{itemId}', ['as' => 'deletechecklistitem', 'uses' => 'ItemController@destroy']);
$router->post('checklists/{checklistId}/items/_bulk', ['as' => 'updatebulkchecklist', 'uses' => 'ItemController@bulkupdate']);
$router->get('checklists/items/summaries', ['as' => 'summaryitem', 'uses' => 'ItemController@summaries']);

// CHECKLIST
$router->get('checklists/{checklistId}', ['as' => 'getchecklist', 'uses' => 'ChecklistController@getchecklist']);
$router->patch('checklists/{checklistId}', ['as' => 'updatechecklist', 'uses' => 'ChecklistController@update']);
$router->delete('checklists/{checklistId}', ['as' => 'deletechecklist', 'uses' => 'ChecklistController@destroy']);
$router->post('checklists', ['as' => 'createchecklist', 'uses' => 'ChecklistController@store']);
$router->get('checklists', ['as' => 'getlistofchecklists', 'uses' => 'ChecklistController@getlistofchecklist']);
