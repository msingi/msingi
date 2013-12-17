<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class Assets extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @return string
     */
    public function __invoke()
    {
        $route_match = $this->getServiceLocator()->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
        if($route_match == null)
            return '';

        $route_name = $route_match->getMatchedRouteName();

        $module = substr($route_name, 0, strpos($route_name, '/'));

        $config = $this->getServiceLocator()->getServiceLocator()->get('Config');

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
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}