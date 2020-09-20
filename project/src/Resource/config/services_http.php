<?php

return [
    'fast_router' => [
        'shared' => true,
        'init' => function(\Perfumer\Component\Container\Container $container) {
            return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
                $r->addRoute('POST', '/collection', 'collection.post');
                $r->addRoute('POST', '/record', 'record.post');
                $r->addRoute('POST', '/record/read', 'read.post');
                $r->addRoute('GET', '/records', 'records.get');
            });
        }
    ],

    'feed.router' => [
        'shared' => true,
        'class' => 'Perfumer\\Framework\\Router\\Http\\FastRouteRouter',
        'arguments' => ['#gateway.http', '#fast_router', [
            'data_type' => 'json',
            'allowed_actions' => ['get', 'post', 'delete', 'patch'],
        ]]
    ],

    'feed.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Feed\\Controller',
            'suffix' => 'Controller'
        ]]
    ],
];
