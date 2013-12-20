<?php

namespace Msingi\Cms\Service\Factory\TableGateway;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BackendUsers implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|TableGateway
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(\Msingi\Cms\Db\Table\BackendUsers::getPrototype());
        return new TableGateway('cms_backend_users', $dbAdapter, null, $resultSetPrototype);
    }
}