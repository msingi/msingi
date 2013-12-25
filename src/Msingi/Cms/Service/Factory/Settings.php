<?php

namespace Msingi\Cms\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Settings implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get('SettingsTableGateway');
        return new \Msingi\Cms\Db\Table\Settings($tableGateway, $serviceLocator);
    }
}