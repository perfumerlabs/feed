<?php

namespace Feed\Controller;

use Perfumer\Framework\Controller\ViewController;
use Perfumer\Framework\Router\Http\FastRouteRouterControllerHelpers;
use Perfumer\Framework\View\StatusViewControllerHelpers;

class LayoutController extends ViewController
{
    use FastRouteRouterControllerHelpers;
    use StatusViewControllerHelpers;

    protected function validateNotEmpty($var, $name)
    {
        if (!$var) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter must be set"]);
        }
    }

    protected function validateRegex($var, $name, $regex)
    {
        if (!preg_match($regex, $var)) {
            $this->forward('error', 'badRequest', ["\"$name\" parameter is invalid, only letters, digits and underscore signs are allowed"]);
        }
    }
}
