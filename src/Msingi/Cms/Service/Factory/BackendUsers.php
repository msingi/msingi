<?php

namespace Msingi\Cms\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BackendUsers implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('BackendUsersTableGateway');
        return new \Msingi\Cms\Db\Table\BackendUsers($tableGateway, $serviceLocator);
    }
}