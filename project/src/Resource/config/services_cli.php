<?php

return [
    'feed.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Feed\\Command',
            'suffix' => 'Command'
        ]]
    ]
];
