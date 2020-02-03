<?php
use Cake\Routing\Router;

Router::plugin(
    'MyInstagramFeed',
    ['path' => '/MyInstagramFeed'],
    function ($routes) {
        $routes->connect('/OAuth/:action', ['controller' => 'OAuth']);
    }
);