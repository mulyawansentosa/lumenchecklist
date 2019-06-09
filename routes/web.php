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
    return str_random(32);
    // return $router->app->version();
});

$router->get('key',function(){
    return str_random(32);
});

// User Auth
$router->post('/register', ['as' => 'user.register', 'uses' => 'AuthController@register']);
$router->post('/login', ['as' => 'user.login', 'uses' => 'AuthController@login']);
$router->get('/user[/{id}]', ['as' => 'user.show', 'uses' => 'AuthController@show']);

// TEMPLATE
$router->get('templates', ['as' => 'templates.index', 'uses' => 'TemplateController@index']);

$router->get('checklists', ['as' => 'checklist.index', 'uses' => 'ChecklistController@index']);

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
$router->get('checklists', ['as' => 'getlistofchecklists', 'uses' => 'ChecklistController@getchecklists']);
