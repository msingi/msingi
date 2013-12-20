<?php

namespace Msingi\Cms\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFragments implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('PageFragmentsTableGateway');
        return new \Msingi\Cms\Db\Table\PageFragments($tableGateway, $serviceLocator);
    }
}