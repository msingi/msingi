<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class Assets extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

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

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}