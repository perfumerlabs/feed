<?php

namespace Feed\Module;

use Perfumer\Framework\Controller\Module;

class CommandModule extends Module
{
    public $name = 'feed';

    public $router = 'router.console';

    public $request = 'feed.request';
}