<?php

namespace Project;

use Perfumer\Framework\Gateway\CompositeGateway;

class Gateway extends CompositeGateway
{
    protected function configure(): void
    {
        $this->addModule('feed', 'FEED_HOST', null, 'http');
        $this->addModule('feed', 'feed',      null, 'cli');
    }
}