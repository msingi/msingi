<?php
namespace Msingi\Cms\Service;

use Msingi\Cms\RouteListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RouteListenerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|RouteListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RouteListener();
    }
}