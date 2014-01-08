<?php
namespace Msingi\Cms\Service;

use Msingi\Cms\HttpListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HttpListenerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return HttpListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new HttpListener();
    }
}