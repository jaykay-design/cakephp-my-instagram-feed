<?php
use Cake\Routing\Router;

Router::plugin(
    'Instagram',
    ['path' => '/Instagram'],
    function ($routes) {
        $routes->connect('/OAuth/:action', ['controller' => 'OAuth']);
    }
);