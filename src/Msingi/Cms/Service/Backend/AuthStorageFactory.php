<?php

namespace Msingi\Cms\Service\Backend;

use Msingi\Cms\Service\AuthStorage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthStorageFactory
 *
 * @package Msingi\Cms\Service\Factory
 */
class AuthStorageFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|AuthStorage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new \Msingi\Cms\Service\AuthStorage('Msingi\Cms\Service\AuthStorage\Backend');
    }
}