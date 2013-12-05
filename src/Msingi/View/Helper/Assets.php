<?php

namespace Msingi\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class Assets extends AbstractHelper implements ServiceLocatorAwareInterface
{
    public function __invoke()
    {
        $route_name = $this->getServiceLocator()->getServiceLocator()->get('application')->getMvcEvent()
            ->getRouteMatch()->getMatchedRouteName();

        $module = substr($route_name, 0, strpos($route_name, '/'));

        $config = $this->getServiceLocator()->getServiceLocator()->get('Config');

        return $config['assets'][$module];
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}