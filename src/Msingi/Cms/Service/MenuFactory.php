<?php

namespace Msingi\Cms\Service;

use Msingi\Cms\Model\Table\Menu;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('MenuTableGateway');
        return new Menu($tableGateway);
    }
}