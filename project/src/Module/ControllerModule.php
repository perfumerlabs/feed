<?php

namespace Feed\Module;

use Perfumer\Framework\Controller\Module;

class ControllerModule extends Module
{
    public $name = 'feed';

    public $router = 'feed.router';

    public $request = 'feed.request';

    public $components = [
        'view' => 'view.status'
    ];
}