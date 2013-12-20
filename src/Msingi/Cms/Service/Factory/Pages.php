<?php

namespace Msingi\Cms\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Pages implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('PagesTableGateway');
        return new \Msingi\Cms\Db\Table\Pages($tableGateway, $serviceLocator);
    }
}