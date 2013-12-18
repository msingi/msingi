<?php

namespace Msingi\Cms\Service;

use Msingi\Cms\Model\Table\Pages;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PagesFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('PagesTableGateway');
        return new Pages($tableGateway, $serviceLocator);
    }
}