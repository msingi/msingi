<?php

namespace Msingi\Cms\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Menu implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('MenuTableGateway');
        return new \Msingi\Cms\Db\Table\Menu($tableGateway, $serviceLocator);
    }
}