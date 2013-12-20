<?php

namespace Msingi\Cms\Service\Factory;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BackendAuthService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $salt = $config['backend']['auth']['salt'];

        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $dbTableAuthAdapter = new DbTable($dbAdapter, 'cms_backend_users', 'username', 'password', 'SHA1(CONCAT("' . $salt . '", ?, password_salt))');

        $authService = new AuthenticationService();
        $authService->setAdapter($dbTableAuthAdapter);

        $authStorage = $serviceLocator->get('Msingi\Cms\Model\Backend\AuthStorage');
        $authService->setStorage($authStorage);

        return $authService;
    }
}