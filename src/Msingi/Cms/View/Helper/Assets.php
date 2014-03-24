<?php

namespace Msingi\Cms\View\Helper;

use Msingi\Cms\View\AbstractHelper;

class Assets extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke()
    {
        $config = $this->serviceLocator->getServiceLocator()->get('Config');

        $route_match = $this->serviceLocator->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
        if ($route_match == null) {
            return $config['assets']['frontend'];
        }

        $route_name = $route_match->getMatchedRouteName();

        $module = substr($route_name, 0, strpos($route_name, '/'));

        return $config['assets'][$module];
    }
}