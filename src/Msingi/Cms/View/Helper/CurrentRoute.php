<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;


/**
 * Class CurrentRoute
 * @package Msingi\Cms\View\Helper
 */
class CurrentRoute extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /* @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $routeMatch = $this->serviceLocator->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();

        return $routeMatch->getMatchedRouteName();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

}