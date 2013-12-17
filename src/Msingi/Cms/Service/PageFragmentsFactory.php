<?php

namespace Msingi\Cms\Service;

use Msingi\Cms\Model\Table\PageFragments;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFragmentsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('PageFragmentsTableGateway');
        return new PageFragments($tableGateway);
    }
}