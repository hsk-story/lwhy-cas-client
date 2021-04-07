<?php


$router = app('router');

$router->namespace('\Hsk9044\LwhyCasClient\Http\Controller')
    ->prefix('cas')
    ->group(function ($router){

        $router->get('test', 'IndexController@index');

    });