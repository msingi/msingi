<?php

namespace Msingi\Cms\Service\Backend;

use Msingi\Cms\Service\AuthStorage;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BackendAuthService
 * @package Msingi\Cms\Service\Factory
 */
class AuthServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authAdapter = $serviceLocator->get('Msingi\Cms\Service\Backend\AuthAdapter');
        $authStorage = $serviceLocator->get('Msingi\Cms\Service\Backend\AuthStorage');

        return new AuthenticationService($authStorage, $authAdapter);
    }
}
