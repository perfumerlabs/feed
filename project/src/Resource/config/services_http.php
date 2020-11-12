<?php

return [
    'fast_router' => [
        'shared' => true,
        'init' => function(\Perfumer\Component\Container\Container $container) {
            return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
                $r->addRoute('POST', '/collection', 'collection.post');
                $r->addRoute('PATCH', '/collection', 'collection.patch');

                $r->addRoute('GET',    '/record', 'record.get');
                $r->addRoute('POST',   '/record', 'record.post');
                $r->addRoute('DELETE', '/record', 'record.delete');
                $r->addRoute('POST',   '/record/read', 'record/read.post');
                $r->addRoute('POST',   '/record/unread', 'record/unread.post');

                $r->addRoute('GET',  '/records', 'records.get');
                $r->addRoute('POST', '/records/read', 'records/read.post');
                $r->addRoute('DELETE', '/records', 'records.delete');
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
